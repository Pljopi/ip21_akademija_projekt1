<?php

require_once './vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('lib/views');
$twig = new \Twig\Environment($loader, [
]);
require_once './lib/model.php';
$model = new Model();
$list = $model->getList();
echo ($twig->render('list.html.twig', ['ListOfCurrencies' => $list]));
