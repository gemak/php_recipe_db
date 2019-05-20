<?php
namespace Rdb\Entity;

class Recipe {
	public $id;
	public $authorId;
	public $recipedate;
	public $recipetext;
	private $authorsTable;
	private $author;
    private $recipeCategoriesTable;

	public function __construct(\Ninja\DatabaseTable $authorsTable, \Ninja\DatabaseTable $recipeCategoriesTable) {
		$this->authorsTable = $authorsTable;
        $this->recipeCategoriesTable = $recipeCategoriesTable;
	}

	public function getAuthor() {
		if (empty($this->author)) {
			$this->author = $this->authorsTable->findById($this->authorId);
		}

		return $this->author;
	}
    public function addCategory($categoryId) {
        $recipeCat = ['recipeId' => $this->id,
                    'categoryId' => $categoryId];
        $this->recipeCategoriesTable->save($recipeCat);
    }
    public function hasCategory($categoryId) {
        $recipeCategories = $this->recipeCategoriesTable->find('recipeId', $this->id);

        foreach($recipeCategories as $recipeCategory) {
            if ($recipeCategory->categoryId == $categoryId) {
                return true;
            }
        }
    }
    public function clearCategories() {
        $this->recipeCategoriesTable->deleteWhere('recipeId', $this->id);
    }

}
