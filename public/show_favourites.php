<?php
ob_start(); 
require_once '../lib/bootstrap.php';
$list = $model->getList();
$printFavourites = $model->getAllFavourites();

if (isset($_SESSION['userid'])) {
    $userFavourites = $mysql->getUserFavourites($_SESSION['userid']);

    echo $twig->render(
        'pages/favourites.html.twig',
        [
            'printFavourites' => $userFavourites,
            'ListOfCurrencies' => $list,
        ]
    );

} else {
    echo $twig->render(
        'pages/favourites.html.twig',
        [
            'printFavourites' => $printFavourites,
            'ListOfCurrencies' => $list,
        ]
    );
}

if (isset($_POST['favourite'])) {
    $favourite = $_POST['favourite'];
    $mysql->removeFavorites($favourite);
    header("Location: /show_favourites.php");
    exit;
}
