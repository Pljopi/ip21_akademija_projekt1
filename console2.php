<?php
//error handler
set_error_handler("errorHandler", E_WARNING);
function errorHandler()
{
    echo " Your input is incorrect, after file name enter cryptocurrency TAG, space, currency TAG";
    exit;
}

//spremenljivke
$criptocurrency = $argv[1];
$currency = $argv[2];
$jsonALL = file_get_contents("https://api.coingecko.com/api/v3/simple/supported_vs_currencies"); //ta je za listo valut katerih pari so podprti
$jsonALL_data = json_decode($jsonALL, true);

//išče user input fiat v listi podprtih parov
function currency($currency, $jsonALL_data)
{
    foreach ($jsonALL_data as $value) {
        if ("$currency" == $value) {
            return true;
        }
    }
}
//išče user input crypto v listi podprtih parov
function criptocurrency($criptocurrency, $jsonALL_data)
{
    foreach ($jsonALL_data as $value) {
        if ("$criptocurrency" == $value) {
            return true;
        }
    }
}

//izpis cene poljubnega para
function pairANY($criptocurrency, $currency,)
{
    $json = file_get_contents(sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptocurrency, $currency));
    $json_data = json_decode($json, true);
    echo "Spot price of " . $json_data["data"]["base"] . " is " . $json_data["data"]["amount"] . " " . $json_data["data"]["currency"]; //ta je za pair price

}

if (currency($currency, $jsonALL_data) == true && criptocurrency($criptocurrency, $jsonALL_data) == true) {
    pairANY($criptocurrency, $currency);
} else if ($currency == "help" || $criptocurrency == "help") {
    echo "After file name input criptocurrency TAG sapce currency TAG of the pair you would like to see, both in lower case letters";
} else if (strlen($currency) > 5 || strlen($criptocurrency) > 5) {
    echo "Currency and criptocurrency TAG cannot be longer than 5 characters";
} else {
    echo "Error, the currency and/or criptocurrency TAG you have entered does not exist, make sure that both TAGs are lower case. ";
}
