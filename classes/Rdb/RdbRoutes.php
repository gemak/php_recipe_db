<?php
namespace Rdb;

class RdbRoutes implements \Ninja\Routes {
	private $authorsTable;
	private $recipesTable;
	private $authentication;
	private $categoriesTable;
	private $recipeCategoriesTable;

	public function __construct() {
		include __DIR__ . '/../../includes/DatabaseConnection.php';

		$this->recipesTable = new \Ninja\DatabaseTable($pdo, 'recipe', 'id', '\Rdb\Entity\Recipe', [&$this->authorsTable, &$this->recipeCategoriesTable]);
 		$this->authorsTable = new \Ninja\DatabaseTable($pdo, 'author', 'id', '\Rdb\Entity\Author', [&$this->recipesTable]);
 		$this->categoriesTable = new \Ninja\DatabaseTable($pdo, 'category', 'id', 'Rdb\Entity\Category', [&$this->recipesTable, &$this->recipeCategoriesTable]);
		$this->recipeCategoriesTable = new \Ninja\DatabaseTable($pdo, 'recipe_category', 'categoryId');
		$this->authentication = new \Ninja\Authentication($this->authorsTable, 'email', 'password');
	}

	public function getRoutes(): array {
		$recipeController = new \Rdb\Controllers\Recipe($this->recipesTable, $this->authorsTable, $this->categoriesTable, $this->authentication);
		$authorController = new \Rdb\Controllers\Register($this->authorsTable);
		$loginController = new \Rdb\Controllers\Login($this->authentication);
		$categoryController = new \Rdb\Controllers\Category($this->categoriesTable);

		$routes = [
			'author/register' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'registrationForm'
				],
				'POST' => [
					'controller' => $authorController,
					'action' => 'registerUser'
				]
			],
			'author/success' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'success'
				]
			],
			'recipe/edit' => [
				'POST' => [
					'controller' => $recipeController,
					'action' => 'saveEdit'
				],
				'GET' => [
					'controller' => $recipeController,
					'action' => 'edit'
				],
				'login' => true
			],
			'recipe/delete' => [
				'POST' => [
					'controller' => $recipeController,
					'action' => 'delete'
				],
				'login' => true
			],
			'recipe/list' => [
				'GET' => [
					'controller' => $recipeController,
					'action' => 'list'
				]
			],
			'login/error' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'error'
				]
			],
			'login/success' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'success'
				]
			],
			'logout' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'logout'
				]
			],
			'login' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'loginForm'
				],
				'POST' => [
					'controller' => $loginController,
					'action' => 'processLogin'
				]
			],
			'category/edit' => [
				'POST' => [
					'controller' => $categoryController,
					'action' => 'saveEdit'
				],
				'GET' => [
					'controller' => $categoryController,
					'action' => 'edit'
				],
				'login' => true,
				'permissions' => \Rdb\Entity\Author::EDIT_CATEGORIES
			],
			'category/delete' => [
				'POST' => [
					'controller' => $categoryController,
					'action' => 'delete'
				],
				'login' => true,
				'permissions' => \Rdb\Entity\Author::REMOVE_CATEGORIES
			],
			'category/list' => [
				'GET' => [
					'controller' => $categoryController,
					'action' => 'list'
				],
				'login' => true,
				'permissions' => \Rdb\Entity\Author::LIST_CATEGORIES
			],
			'login/permission' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'permissionerror'
				]
			],
			'author/permissions' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'permissions'
				],
				'POST' => [
					'controller' => $authorController,
					'action' => 'savePermissions'
				],
				'login' => true,
				'permissions' => \Rdb\Entity\Author::EDIT_USER_ACCESS
			],
			'author/list' => [
				'GET' => [
					'controller' => $authorController,
					'action' => 'list'
				],
				'login' => true,
				'permissions' => \Rdb\Entity\Author::EDIT_USER_ACCESS
			],
			'' => [
				'GET' => [
					'controller' => $recipeController,
					'action' => 'home'
				]
			]
		];

		return $routes;
	}

	public function getAuthentication(): \Ninja\Authentication {
		return $this->authentication;
	}
	public function checkPermission($permission) : bool {
		$user = $this->authentication->getUser();

		if ($user && $user->hasPermission($permission)) {
			return true;
		}
		else {
			return false;
		}
	}

}
