<?php
ob_start();
require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->getAllFavourites();

echo $twig->render('pages/favourites.html.twig',
    [
        'printFavourites' => $printFavourites,
        'ListOfCurrencies' => $list,
    ]);
if (isset($_POST['favourite'])) {
    $favourite = $_POST['favourite'];
    $model->removeFromFavourites($favourite);
    header("Location: /show_favourites.php");
    exit;
}
