<?php if (empty($recipe->id) || $user->id == $recipe->authorId || $user->hasPermission(\Ijdb\Entity\Author::EDIT_RECIPES)): ?>
<form action="" method="POST">
    <input type="hidden" name="recipe[id]"
        value="<?=$recipe->id ?? ''?>">
    <label for="recipetext">Type your recipe here:</label>
    <textarea id="recipetext" name="recipe[recipetext]" rows="3" cols="40"><?=$recipe->recipetext ?? '' ?></textarea>

    <p>Select categories for this recipe:</p>
    <?php foreach ($categories as $category): ?>
        <?php if ($recipe && $recipe->hasCategory($category->id)): ?>
            <input type="checkbox" checked name="category[]" value="<?=$category->id?>"/>
        <?php else: ?>
            <input type="checkbox" name="category[]" value="<?=$category->id?>"/>
        <?php endif; ?>
        <label><?=$category->name?></label>
    <?php endforeach;?>
    <input type="submit" name="submit" value="Save">
</form>
<?php else: ?>
    <p> You may only edit recipes that you posted.</p>
<?php endif; ?>
