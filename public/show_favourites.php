<?php

require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->printOrInsertFavourite(null, null);
echo $twig->render('pages/favourites.html.twig',
    [
        'printFavourites' => $printFavourites,
        'ListOfCurrencies' => $list,
    ]);
