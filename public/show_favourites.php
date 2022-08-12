<?php

require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->getAllFavourites();
echo $twig->render('pages/favourites.html.twig',
    [
        'printFavourites' => $printFavourites,
        'ListOfCurrencies' => $list,
    ]);
