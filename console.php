<?php

//spremenljivke
$criptocurrency = strtolower($argv[1]);
$currency = strtolower($argv[2]);
$jsonALL = file_get_contents("https://api.coingecko.com/api/v3/simple/supported_vs_currencies"); //Supported pairs list
$jsonALL_data = json_decode($jsonALL, true);
set_error_handler("errorHandler", E_WARNING);







if (!$currency  || !$criptocurrency) {
    echo "No input - After file name enter cryptocurrency TAG and currency TAG in that order";
} elseif ($currency == "help" || $criptocurrency == "help") {
    echo "Help - After file name input criptocurrency TAG sapce currency TAG of the pair you would like to see";
} else if (strlen($currency) > 5 || strlen($criptocurrency) > 5) {
    echo "Tag too long - Currency and criptocurrency TAG cannot be longer than 5 characters";
} else  if (hasCurrency($currency, $jsonALL_data)  && hasCriptocurrency($criptocurrency, $jsonALL_data)) {
    pairAny($criptocurrency, $currency);
} else {
    ErrorHandler();
}





//Echo price of chosen pair
function pairAny($criptocurrency, $currency)
{
    $json = file_get_contents(sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptocurrency, $currency));
    $json_data = json_decode($json, true);
    echo (sprintf("Spot price of %s is %s %s", $json_data["data"]["base"], $json_data["data"]["amount"], $json_data["data"]["currency"])); //Pair price api
}


//Verifies that the crypto TAG entered by user is on the list of supported pairs
function hasCriptocurrency($criptocurrency, $jsonALL_data)
{
    foreach ($jsonALL_data as $value) {
        if ($criptocurrency == $value) {
            return true;
        }
    }
}

//Verifies that the fiat TAG entered by user is on the list of supported pairs
function hasCurrency($currency, $jsonALL_data)
{
    foreach ($jsonALL_data as $value) {
        if ($currency == $value) {
            return true;
        }
    }
}

//Displays error message for E_WARNING's
function errorHandler()
{
    echo "Error -  Your input is incorrect, after file name enter criptocurrency TAG, space, currency TAG";
    exit;
}
