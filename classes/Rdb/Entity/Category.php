<?php
namespace Rdb\Entity;
use Ninja\DatabaseTable;
class Category {
	public $id;
	public $name;
	private $recipesTable;
	private $recipeCategoriesTable;
	public function __construct(DatabaseTable $recipesTable, DatabaseTable $recipeCategoriesTable) {
		$this->recipesTable = $recipesTable;
		$this->recipeCategoriesTable = $recipeCategoriesTable;
	}
	public function getRecipes($limit = null, $offset = null) {
		$recipeCategories = $this->recipeCategoriesTable->find('categoryId', $this->id, null, $limit, $offset);
		$recipes = [];
		foreach ($recipeCategories as $recipeCategory) {
			$recipe =  $this->recipesTable->findById($recipeCategory->recipeId);
			if ($recipe) {
				$recipes[] = $recipe;
			}
		}
		usort($recipes, [$this, 'sortRecipes']);

		return $recipes;
	}
	private function sortRecipes($a, $b) {
		$aDate = new \DateTime($a->recipedate);
		$bDate = new \DateTime($b->recipedate);

		if ($aDate->getTimestamp() == $bDate->getTimestamp()) {
			return 0;
		}
		return $aDate->getTimeStamp() > $bDate->getTimeStamp() ? -1 : 1;
	}
	public function getNumRecipes() {
		return $this->recipeCategoriesTable->total('categoryId', $this->id);
	}
}
