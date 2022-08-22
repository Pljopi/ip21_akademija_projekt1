<?php
session_start();
require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->getAllFavourites();
$twig->addGlobal('session', $_SESSION);
echo $twig->render('pages/list.html.twig', ['printFavourites' => $printFavourites, 'ListOfCurrencies' => $list]);
