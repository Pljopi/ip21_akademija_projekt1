<?php
require_once '/var/www/projekt1/lib/bootstrap/bootstrap.php';
require_once '/var/www/projekt1/lib/model.php';
$model = new Model();
$list = $model->getList();
$printFav = $model->printFav();
echo ($twig->render('list.html.twig', ['ListOfCurrencies' => $list]));
echo ($twig->render('favourites.html.twig', ['printFav' => $printFav]));
