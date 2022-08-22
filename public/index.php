<?php

require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->getAllFavourites();
//$twig->addGlobal('session', $_SESSION);  === $_SESSION['username'] in php file Will be equivalent to {{ session.username }} in your twig template
echo ($twig->render('pages/list.html.twig', ['printFavourites' => $printFavourites, 'ListOfCurrencies' => $list]));
