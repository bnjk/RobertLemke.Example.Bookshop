<?php
namespace RobertLemke\Example\Bookshop\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "RobertLemke.Example.Bookshop".              *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

use TYPO3\Flow\Mvc\Controller\ActionController;
use \RobertLemke\Example\Bookshop\Domain\Model\Category;

/**
 * Category controller for the RobertLemke.Example.Bookshop package
 */
class CategoryController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \RobertLemke\Example\Bookshop\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Shows a list of categories
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('categories', $this->categoryRepository->findAll());
	}

	/**
	 * Shows a single category object
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Category $category The category to show
	 * @return void
	 */
	public function showAction(Category $category) {
		$this->view->assign('category', $category);
	}

	/**
	 * Shows a form for creating a new category object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * Adds the given new category object to the category repository
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Category $newCategory A new category to add
	 * @return void
	 */
	public function createAction(Category $newCategory) {
		$this->categoryRepository->add($newCategory);
		$this->addFlashMessage('Created a new category.');
		$this->redirect('index');
	}

	/**
	 * Shows a form for editing an existing category object
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Category $category The category to edit
	 * @return void
	 */
	public function editAction(Category $category) {
		$this->view->assign('category', $category);
	}

	/**
	 * Updates the given category object
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Category $category The category to update
	 * @return void
	 */
	public function updateAction(Category $category) {
		$this->categoryRepository->update($category);
		$this->addFlashMessage('Updated the category.');
		$this->redirect('index');
	}

	/**
	 * Removes the given category object from the category repository
	 *
	 * @param \RobertLemke\Example\Bookshop\Domain\Model\Category $category The category to delete
	 * @return void
	 */
	public function deleteAction(Category $category) {
		$this->categoryRepository->remove($category);
		$this->addFlashMessage('Deleted a category.');
		$this->redirect('index');
	}

}

?>