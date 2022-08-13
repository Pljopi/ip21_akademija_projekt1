<?php
require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->getAllFavourites();
echo ($twig->render('pages/list.html.twig', ['printFavourites' => $printFavourites, 'ListOfCurrencies' => $list]));
