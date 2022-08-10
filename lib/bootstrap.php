<?php

require_once __DIR__ . '/mysql.php';
$connect = new mysql();
require_once __DIR__ . '/model.php';
$model = new Model();
require_once __DIR__ . '/../.gitignore/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views/');
$twig = new \Twig\Environment($loader, [
]);
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Ljubljana');
