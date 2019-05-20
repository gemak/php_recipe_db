<!DOCTYPE html>
<html>
    <head>
        <meta charset = "utf-8">
        <link rel="stylesheet" href="/recipes.css">
        <title><?=$title?></title>
    </head>
    <body>
        <nav>
        <header>
            <h1>Internet Recipe Database</h1>
        </header>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/recipe/list">Recipes List</a></li>
                <li><a href="/recipe/edit">Add a new Recipe</a></li>
                <?php if ($loggedIn): ?>
                    <li><a href="/logout">Log out</a></li>
                <?php else: ?>
                    <li><a href="/login">Log in</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <main>
            <?=$output?>
        </main>
        <footer>
            &copy; Recipe Practice, 2019
        </footer>
    </body>
</html>
