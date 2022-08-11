<?php
require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFav = $model->printFav();
echo ($twig->render('twig/pages/list.html.twig', ['printFav' => $printFav, 'ListOfCurrencies' => $list]));
