<?php

require_once '../lib/php/bootstrap/bootstrap.php';
$list = $model->getList();
$printFav = $model->printFav();
echo $twig->render('favourites.html.twig',
    [
        'printFav' => $printFav,
        'ListOfCurrencies' => $list,
    ]);
