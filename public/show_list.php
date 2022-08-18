<?php
ob_start();
require_once '../lib/bootstrap.php';
$list = $model->getList();

echo $twig->render(
    'pages/show.list.html.twig',
    [
        'ListOfCurrencies' => $list,
    ]
);

if (isset($_POST['currency'])) {
    $currency = $_POST['currency'];
    $key = array_search($currency, $list);
    $mysql->insertFavorites($key, $list[$key]);
    header("Location: /show_list.php");
    exit;
}
