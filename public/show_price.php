<?php

require_once '/var/www/projekt1/lib/bootstrap/bootstrap.php';
$criptoCurrency = $_GET['cripto_currency'];
$currency = $_GET['currency'];
list($criptoCurrencyTAG, $pairValue, $currencyTAG) = $model->getPrice($criptoCurrency, $currency);

echo $twig->render('price.html.twig', ['criptoCurrencyTAG' => $criptoCurrencyTAG, 'pairValue' => $pairValue, 'currencyTAG' => $currencyTAG, 'ListOfCurrencies' => $list]);
