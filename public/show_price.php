<?php
require_once '../lib/bootstrap.php';
$criptoCurrency = $_GET['cripto_currency'];
$currency = $_GET['currency'];

$list = $model->getList();

$printFavourites = $model->getAllFavourites();
$key = array_search($criptoCurrency, $list);
$key2 = array_search($currency, $list);
$mysql->insertFavorites($key, $list[$key]);
$mysql->insertFavorites($key2, $list[$key2]);

try {
    list($criptoCurrencyTAG, $pairValue, $currencyTAG) = $model->getPrice($criptoCurrency, $currency);
    echo $twig->render(
        'pages/price.html.twig',
        [
            'criptoCurrencyTAG' => $criptoCurrencyTAG,
            'pairValue' => $pairValue,
            'currencyTAG' => $currencyTAG,
            'ListOfCurrencies' => $list,
        ]
    );
} catch (\Exception$e) {
    echo "The currency pair you have entered is not on the list of supported currencies\n";
}
