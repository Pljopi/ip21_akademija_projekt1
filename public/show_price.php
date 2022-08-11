<?php
require_once '../lib/bootstrap.php';
$criptoCurrency = $_GET['cripto_currency'];
$currency = $_GET['currency'];
list($criptoCurrencyTAG, $pairValue, $currencyTAG) = $model->getPrice($criptoCurrency, $currency);
$list = $model->getList();
$printFavourites = $model->printOrInsertFavourite(null, null);
echo $twig->render('pages/price.html.twig',
    [
        'criptoCurrencyTAG' => $criptoCurrencyTAG,
        'pairValue' => $pairValue,
        'currencyTAG' => $currencyTAG,
        'ListOfCurrencies' => $list,
    ]);
