<?php
echo 'You are viewing ' . $_SERVER['REQUEST_URI'];
echo '<br>stripped: ' . ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
echo '<br>' . $_SERVER['REQUEST_METHOD'];
try {
	include __DIR__ . '/../includes/autoload.php';

	$route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

	$entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Rdb\RdbRoutes());
	$entryPoint->run();
}
catch (PDOException $e) {
	$title = 'An error has occurred';

	$output = 'Database error: ' . $e->getMessage() . ' in ' .
	$e->getFile() . ':' . $e->getLine();

	include  __DIR__ . '/../templates/layout.html.php';
}
