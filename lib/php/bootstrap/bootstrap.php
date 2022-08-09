<?php

require_once '../lib/php/forConsole/mysql.php';
$connect = new mysql();
require_once '../lib/php/forConsole/model.php';
$model = new Model();

require_once '../.gitignore/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('../lib/views/');
$twig = new \Twig\Environment($loader, [
]);
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Ljubljana');
