<?php
require_once '../lib/bootstrap.php';
$criptoCurrency = $_GET['cripto_currency'];
$currency = $_GET['currency'];

$list = $model->getList();

$printFavourites = $model->getAllFavourites();
$key = array_search($criptoCurrency, $list);
$key2 = array_search($currency, $list);
$mysql->insertFavorites($key, $list[$key], null);
$mysql->insertFavorites($key2, $list[$key2], null);
if(! list($criptoCurrencyTAG, $pairValue, $currencyTAG) = $model->getPrice($criptoCurrency, $currency)){
    header("Location: /index.php?error=unsupported_currency_pair");
}
   



echo $twig->render(
    'pages/price.html.twig',
    [
        'criptoCurrencyTAG' => $criptoCurrencyTAG,
        'pairValue' => $pairValue,
        'currencyTAG' => $currencyTAG,
        'ListOfCurrencies' => $list,
    ]
);
