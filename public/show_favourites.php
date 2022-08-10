<?php

require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFav = $model->printFav();
echo $twig->render('twig/pages/favourites.html.twig',
    [
        'printFav' => $printFav,
        'ListOfCurrencies' => $list,
    ]);
