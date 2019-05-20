<?php
namespace Rdb\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;
class Recipe {
	private $authorsTable;
	private $recipesTable;
	private $categoriesTable;
	private $authentication;
	public function __construct(DatabaseTable $recipesTable, DatabaseTable $authorsTable, DatabaseTable $categoriesTable, Authentication $authentication) {
		$this->recipesTable = $recipesTable;
		$this->authorsTable = $authorsTable;
		$this->categoriesTable = $categoriesTable;
		$this->authentication = $authentication;
	}
	public function list() {
		$page = $_GET['page'] ?? 1;

		$offset = ($page-1) * 2;

		if (isset($_GET['category']))
		{
			$category = $this->categoriesTable->findById($_GET['category']);
			$recipes = $category->getRecipes(2, $offset);
			$totalRecipes = $category->getNumRecipes();
		}
		else
		{
			$recipes = $this->recipesTable->findAll('recipedate DESC', 2, $offset);
			$totalRecipes = $this->recipesTable->total();
		}
		$title = 'Recipe list';
		$totalRecipes = $this->recipesTable->total();
		$author = $this->authentication->getUser();
		return ['template' => 'recipes.html.php',
				'title' => $title,
				'variables' => [
						'totalRecipes' => $totalRecipes,
						'recipes' => $recipes,
						'user' => $author,
						'categories' => $this->categoriesTable->findAll(),
						'currentPage' => $page,
						'category' => $_GET['category'] ?? null
					]
				];
	}
	public function home() {
		$title = 'Internet Recipe Database';
		return ['template' => 'home.html.php', 'title' => $title];
	}
	public function delete() {
		$author = $this->authentication->getUser();
		$recipe = $this->recipesTable->findById($_POST['id']);
		if ($recipe->authorId != $author->id && !$author->hasPermission(\Rdb\Entity\Author::DELETE_RECIPES)) {
			return;
		}

		$this->recipesTable->delete($_POST['id']);
		header('location: /recipe/list');
	}
	public function saveEdit() {
		$author = $this->authentication->getUser();
		$recipe = $_POST['recipe'];
		$recipe['recipedate'] = new \DateTime();
		$recipeEntity = $author->addRecipe($recipe);
		$recipeEntity->clearCategories();
		foreach ($_POST['category'] as $categoryId) {
			$recipeEntity->addCategory($categoryId);
		}
		header('location: /recipe/list');
	}
	public function edit() {
		$author = $this->authentication->getUser();
		$categories = $this->categoriesTable->findAll();
		if (isset($_GET['id'])) {
			$recipe = $this->recipesTable->findById($_GET['id']);
		}
		$title = 'Edit recipe';
		return ['template' => 'editrecipe.html.php',
				'title' => $title,
				'variables' => [
						'recipe' => $recipe ?? null,
						'user' => $author,
						'categories' => $categories
					]
				];
	}

}
