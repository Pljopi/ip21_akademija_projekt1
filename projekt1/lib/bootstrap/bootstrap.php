<?php
require_once '/var/www/projekt1/lib/mysql.php';
$connect = new mysql();
require_once '/var/www/projekt1/lib/model.php';
$model = new Model();
$list = $model->getList();
$printFav = $model->printFav();
require_once '/var/www/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('/var/www/projekt1/lib/views');
$twig = new \Twig\Environment($loader, [
]);
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Ljubljana');
