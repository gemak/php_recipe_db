<?php
namespace Rdb\Entity;
class Author {
    const EDIT_RECIPES = 1;
    const DELETE_RECIPES = 2;
    const LIST_CATEGORIES = 4;
    const EDIT_CATEGORIES = 8;
    const REMOVE_CATEGORIES = 16;
    const EDIT_USER_ACCESS = 32;

	public $id;
	public $name;
	public $email;
	public $password;
    public $permission;
	private $recipesTable;


	public function __construct(\Ninja\DatabaseTable $recipeTable) {
		$this->recipesTable = $recipeTable;
	}
	public function getRecipes() {
		return $this->recipesTable->find('authorId', $this->id);
	}
	public function addRecipe($recipe) {
		$recipe['authorId'] = $this->id;
		return $this->recipesTable->save($recipe);
	}
    public function hasPermission($permission) {
        return $this->permissions & $permission;
    }
}
