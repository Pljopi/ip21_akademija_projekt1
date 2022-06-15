<?php
//try block 0 checks if user has submitted input
try {
    $criptocurrency = @strtolower($argv[1]);
    $currency = @strtolower($argv[2]);
    noInput($currency, $criptocurrency);
    minLength($currency, $criptocurrency);
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}
//price pair try block1 + help exception
try {
    $json = @file_get_contents(sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptocurrency, $currency));
    help($currency, $criptocurrency);
    if ($json === false) {
        throw new Exception("Error 404 on try block 1, cannot read the price pair api, first enter criptocurrency TAG, then enter fiat currency TAG\n");
    }
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}

//all supported currencies try block2 + help exception
try {
    $jsonAll = @file_get_contents("https://api.coingecko.com/api/v3/simple/supported_vs_currencies");
    help($currency, $criptocurrency);
    if ($jsonAll === false) {
        throw new Exception("Error 404 on try block 2, cannot read the supported TAG's api \n");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
//
try {
    $jsonAll_data = json_decode($jsonAll, true);
    $json_data = json_decode($json, true);
    if ($json_data && $jsonAll_data == null) {
        throw new Exception("Error try block 3 - Unsupported price pair, first enter the criptocurrency TAG and the the fiat currency TAG");
    } else {
        printForValid($currency, $criptocurrency, $jsonAll_data, $json_data);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}




//Verifies that the crypto TAG entered by user is on the list of supported pairs
function hasCriptocurrency($criptocurrency, $jsonAll_data)
{
    foreach ($jsonAll_data as $value) {
        if ($criptocurrency == $value) {
            return true;
        }
    }
}

//Verifies that the fiat TAG entered by user is on the list of supported pairs
function hasCurrency($currency, $jsonAll_data)
{
    foreach ($jsonAll_data as $value) {
        if ($currency == $value) {
            return true;
        }
    }
}
//verifies that user has input atleast 1 value //to allow for help();
function noInput($currency, $criptocurrency)
{
    if (!$currency && !$criptocurrency) {
        throw new Exception("Error on try block 0, Undefined array key, after file name enter cryptocurrency TAG and currency TAG in that order \n");
        exit();
    }
}
//Checks if the user has input help
function help($currency, $criptocurrency)
{
    if ($currency == "help" || $criptocurrency == "help") {
        echo "Help - After file name input criptocurrency TAG sapce currency TAG of the pair you would like to see \n";
    }
}
//If user input is valid it outputs the pair price
function printForValid($currency, $criptocurrency, $jsonAll_data, $json_data)
{
    if ((hasCurrency($currency, $jsonAll_data)  && hasCriptocurrency($criptocurrency, $jsonAll_data) === true)) {
        echo (sprintf("Spot price of %s is %s %s", $json_data["data"]["base"], $json_data["data"]["amount"], $json_data["data"]["currency"]));
    }
}


function minLength($currency, $criptocurrency)
{
    if (strlen($currency) > 5 || strlen($criptocurrency) > 5) {
        echo "Error on try block 0, currency and criptocurrency TAG cannot be longer than 5 characters";
        exit();
    }
}
