<div class="recipelist">
    <ul class="categories">
        <?php foreach ($categories as $category): ?>
            <li><a href="/recipe/list?category=<?=$category->id?>"><?=$category->name?></a></li>
        <?php endforeach;?>
    </ul>
<div class="recipes">
<p><?=$totalRecipes?> recipes have been submitted to the Internet Recipe Database.</p>

<?php foreach($recipes as $recipe): ?>
    <blockquote>

        <?=(new \Ninja\Markdown($recipe->recipetext))->toHtml()?>

        (by <a href="mailto:<?=htmlspecialchars($recipe->getAuthor()->email, ENT_QUOTES,
                        'UTF-8'); ?>">
                    <?=htmlspecialchars($recipe->getAuthor()->name, ENT_QUOTES,
                        'UTF-8'); ?></a> on <?php $date = new DateTime($recipe->recipedate); echo $date->format('jS F Y');?>)

    <?php if ($user):?>

        <?php if ($user->id == $recipe->authorId || $user->hasPermission(\Rdb\Entity\Author::EDIT_RECIPES)): ?>
              <a href="/recipe/edit?id=<?=$recipe->id?>">Edit</a>
        <?php endif;?>
        <?php if ($user->id == $recipe->authorId || $user->hasPermission(\Rdb\Entity\Author::DELETE_RECIPES)): ?>
              <form action="/recipe/delete" method="post">
                  <input type="hidden" name="id" value="<?=$recipe->id?>">
                  <input type="submit" value="Delete">
              </form>
        <?php endif; ?>
    <?php endif;?>
    </blockquote>
    <?php endforeach; ?>

    Select page:
    <?php
    //Calculate the number of pages
    $numPages = ceil($totalRecipes/2);

    // Display a link for each page
    for ($i = 1; $i <= $numPages; $i++):
        if ($i == $currentPage) :?>
            <a class="currentpage" href="/recipe/list?page=<?=$i?><?=!empty($categoryId) ? '&category=' . $categoryId : ''?>"><?=$i?></a>
        <?php else:?>
            <a href="/recipe/list?page=<?=$i?><?=!empty($categoryId) ? '&category=' . $categhoryId : '' ?>"><?=$i?></a>
        <?php endif;?>
    <?php endfor;?>
</div>
