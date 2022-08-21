<?php

require_once __DIR__ . '/mysql.php';

$mysql = new Mysql(
    $servername = getenv('DB_SERVERNAME'),
    $username = getenv('DB_USERNAME'),
    $password = getenv('DB_PASSWORD'),
    $dbname = getenv('DB_NAME'),
    $charset = getenv('DB_CHARSET')
);
require_once __DIR__ . '/model.php';
$model = new Model();
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
$twig = new \Twig\Environment($loader, [
]);

$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Ljubljana');
