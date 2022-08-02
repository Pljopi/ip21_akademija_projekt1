<?php

require_once '/var/www/lib/mysql.php';
require_once '/var/www/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('/var/www/lib/views');
$twig = new \Twig\Environment($loader, [
]);
require_once '/var/www/projekt1/lib/model.php';
$model = new Model();
$list = $model->getList();
echo ($twig->render('list.html.twig', ['ListOfCurrencies' => $list]));
$object = new mysql();
$object->connect();
