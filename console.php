<?php
error_reporting(E_ALL);
set_error_handler('errorHandler');




//TRY BLOCK 0, Does the api work?
try {
    $urlList = "https://api.coingecko.com/api/v3/simple/supported_vs_currencies";
    $jsonAll = file_get_contents($urlList);
    if ($jsonAll === false) {
        throw new Exception("Try block 0. List Api is down, try again later");
    } else {
        $jsonAll_data = json_decode($jsonAll, true);
        global $jsonALL_data;
    }
} catch (\Exception $e) {
    echo $e->getMessage();
    exit();
}





//Checks 1-input, 2-list, 3-help, 4-input length, 5-price 
if (!isset($argv[1])) {
    echo "Type >help< for help, and >list< to get a list of supported currenies";
    exit();
} else if (in_array("help", $argv)) {
    echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >criptocurrency TAG< >criptocurrency TAG< \n";
    exit();
} else if (in_array("list", $argv)) {
    $list = strtolower($argv[1]);
    printList($jsonAll_data);
    exit();
} else if ((strlen($argv[1]) > 5) or (strlen($argv[2]) > 5) or (strlen($argv[3])) > 5) {
    echo "User input error - no input can be longer than 5 characters";
    exit();
} else if ($argv[1] == "price") {  //After input 1 is price, it checks for input 2 and 3
    $price = strtolower($argv[1]);
    if ($argv[2] && $argv[3]) {    //If both input 2 and 3 are present it inpurs them into respective variables
        $currency = strtolower($argv[3]);
        $criptocurrency = strtolower($argv[2]);
        global $currency;
        global $criptocurrency;
    } else {
        echo "After price, enter criptocurrency and currency TAG"; //If price is the only input...
        exit();
    }
    try {
        $urlPair = sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptocurrency, $currency);  //Sets price pair ulr variable and tries to decode it
        $json = file_get_contents($urlPair);
        if ($json === false) {
            throw new Exception("try block 1. User input is incorrect or price api is down");
        } else {
            $json_data = json_decode($json, true);
            global $json_data;
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
        exit();
    }
}

//If all is well it prints our the Price Pair
printForValid($currency, $criptocurrency, $jsonAll_data, $json_data, $price);







//Verifies that the fiat TAG entered by user is on the list of supported pairs
function hasCurrency($currency, $jsonAll_data)
{
    foreach ($jsonAll_data as $value) {
        if ($currency == $value) {
            return true;
        }
    }
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

//If user input is valid it outputs the pair price
function printForValid($currency, $criptocurrency, $jsonAll_data, $json_data, $price)
{
    if ((hasCurrency($currency, $jsonAll_data)  && hasCriptocurrency($criptocurrency, $jsonAll_data) === true)) {
        echo (sprintf("Spot price of %s is %s %s", $json_data["data"]["base"], $json_data["data"]["amount"], $json_data["data"]["currency"]));
    } else {
        echo "Unsupported currenices, type list to get the list of supported currencies ";
    }
}

//It echoes the list of all supported currencies, both fiat and crypto. It is triggerd by $argv[1] === list
function printList($jsonAll_data)
{
    natcasesort($jsonAll_data);
    foreach ($jsonAll_data as $value) {
        echo "$value \n";
    }
}

//error handler, Except for muting warnings, this is the only way I found to display custo mmessage without the original warning message-.-??? 
function errorHandler(
    int $type,
    string $msg,
    ?string $file = null,
    ?int $line = null
) {
    if (strpos($msg, "400 Bad Request") !== false) {
        echo "Error 400 bad request. User input is incorrect. Type help after file name for help. \n";
    }
    if (strpos($msg, "404 Not Found") !== false) {
        echo "Error 404 url not found. Api is down or user input is incorrect, try again later\n";
    }
    if (strpos($msg, "Undefined variable") !== false) {
        echo "Error Invalid user input, type help for help";
    }
    if (strpos($msg, "Undefined array key 3") !== false) {
        echo "You did not enter the currency TAG \n";
    }

    echo $type . '//// ' . $msg . ' /// ' . $file . ' //// ' . $line;
};
