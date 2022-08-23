<?php
require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->getAllFavourites();
$twig->addGlobal('session', $_SESSION);
echo $twig->render('pages/signup.html.twig', ['printFavourites' => $printFavourites, 'ListOfCurrencies' => $list]);
