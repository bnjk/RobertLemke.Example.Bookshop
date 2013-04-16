<?php
namespace RobertLemke\Example\Bookshop\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "RobertLemke.Example.Bookshop".              *
 *                                                                        *
 *                                                                        */

use RobertLemke\Example\Bookshop\Domain\Model\Category;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionController;
use \RobertLemke\Example\Bookshop\Domain\Model\Book;
use TYPO3\Fluid\View\AbstractTemplateView;

/**
 * Book controller for the RobertLemke.Example.Bookshop package
 */
class BookController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \RobertLemke\Example\Bookshop\Domain\Repository\BookRepository
	 */
	protected $bookRepository;

	/**
	 * @Flow\Inject
	 * @var \RobertLemke\Example\Bookshop\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @Flow\Inject
	 * @var \RobertLemke\Example\Bookshop\Domain\Model\Basket
	 */
	protected $basket;

	/**
	 * @Flow\Inject
	 * @var \RobertLemke\Example\Bookshop\Service\IsbnLookupService
	 */
	protected $isbnLookupService;

	/**
	 * A hacky way to implement a menu
	 *
	 * @return void
	 */
	public function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view) {
		$view->assign('controller', array('book' => TRUE));
		$view->assign('categories', $this->categoryRepository->findAll());
		$view->assign('basket', $this->basket);
	}

	/**
	 * Shows a list of books
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Category $category
	 * @return void
	 */
	public function indexAction(Category $category = NULL) {
		if ($category !== NULL) {
			$books = $this->bookRepository->findByCategory($category);
		} else {
			$books = $this->bookRepository->findAll();
		}
		$this->view->assign('books', $books);
	}

	/**
	 * Shows a single book object
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Book $book The book to show
	 * @return void
	 */
	public function showAction(Book $book) {
		$this->view->assign('book', $book);
	}

	/**
	 * Shows a form for creating a new book object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * Adds the given new book object to the book repository
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Book $newBook A new book to add
	 * @return void
	 */
	public function createAction(Book $newBook) {
		$this->bookRepository->add($newBook);
		$this->addFlashMessage('Created a new book.');
		$this->redirect('index');
	}

	/**
	 * Adds the book specified by an ISBN
	 *
	 * @param array $newBook An array containing an isbn property
	 * @return void
	 */
	public function createIsbnAction(array $newBook) {
		$bookInfo = $this->isbnLookupService->getBookInfo($newBook['isbn']);
		if ($bookInfo === array()) {
			$this->addFlashMessage('No book found with ISBN %s.', 'Invalid ISBN', Message::SEVERITY_ERROR, array($newBook['isbn']));
			$this->redirect('index');
		}
		$book = new Book();
		$book->setTitle($bookInfo['title']);
		$book->setDescription($bookInfo['description']);
		$book->setIsbn($newBook['isbn']);
		$book->setPrice(16);
		$this->bookRepository->add($book);
		$this->addFlashMessage('Created a new book.');
	}

	/**
	 * Shows a form for editing an existing book object
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Book $book The book to edit
	 * @return void
	 */
	public function editAction(Book $book) {
		$this->view->assign('book', $book);
	}

	/**
	 * Updates the given book object
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Book $book The book to update
	 * @return void
	 */
	public function updateAction(Book $book) {
		$this->bookRepository->update($book);
		$this->addFlashMessage('Updated the book.');
		$this->redirect('index');
	}

	/**
	 * Removes the given book object from the book repository
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Book $book The book to delete
	 * @return void
	 */
	public function deleteAction(Book $book) {
		$this->bookRepository->remove($book);
		$this->addFlashMessage('Deleted a book.');
		$this->redirect('index');
	}

}

?>