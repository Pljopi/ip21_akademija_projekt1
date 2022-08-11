<?php
require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->printOrInsertFavourite(null, null);
echo ($twig->render('pages/list.html.twig', ['printFavourites' => $printFavourites, 'ListOfCurrencies' => $list]));
