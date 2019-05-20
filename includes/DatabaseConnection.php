<?php
    $pdo = new PDO('mysql:host=localhost; dbname=rdb; charset=utf8',
    'rdbuser', 'mypassword');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
