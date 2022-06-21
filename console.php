<?php
//Declare $apiList variable as the return value from function apiList();
$apiList = apiList();

// If no user input... exit
if (!isset($argv[1])) {
    echo "Type >help< for help, and >list< to get a list of supported currenies\n";
    exit();
}
//If user input is help...exit
if (in_array("help", $argv)) {
    echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >criptocurrency< TAG >criptocurrency TAG< \n";
    exit();
}
//If user input is list...exit
if (in_array("list", $argv)) {
    printList($apiList);
    exit();
}

//If there is no 2nd and 3rd input...exit
if (empty($argv[2]) || empty($argv[3])) {
    echo "After price, enter criptocurrency and currency TAG\n";
    exit();
}
//if any of the inputs are too long...exit
if ((strlen($argv[1]) > 5) || (strlen($argv[2]) > 5) || (strlen($argv[3])) > 5) {
    echo "User input error - no input can be longer than 5 characters\n";
    exit();
}
//if first input is not "price" echo instructions, otherwise declare price pair variables
if (strtolower($argv[1]) !== "price") {
    echo "Type >help< for help, and >list< to get a list of supported currenies\n";
    exit();
} else {
    $criptocurrency = strtolower($argv[2]);
    $currency = strtolower($argv[3]);

} //Check if the priice pair variables are on the list of supported currencies
if (currency($currency, $apiList) == false || criptocurrency($criptocurrency, $apiList) == false) {
    echo "The currency pair you have entered is not on the list of supported currencies";
    exit();
} else {
    $pricePair = apiPrice($criptocurrency, $currency); //Declare $pricePair variable as the return value of function apiPrice();
}

//If all is good echo the price pair
printForValid($pricePair);

function printForValid($pricePair)
{
    echo (sprintf("Spot price of %s is %s %s", $pricePair["data"]["base"], $pricePair["data"]["amount"], $pricePair["data"]["currency"]));

}

//check that the api url is valid and that json file was decoded sucessfully
function apiPrice($criptocurrency, $currency)
{try {
    $urlPair = sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptocurrency, $currency);
    if (in_array("HTTP/1.1 404 Not Found", get_headers($urlPair))) {
        echo "You have entered a unsupported price pair. First enter the cryptocurrency tag, then the fiat currency tag\n Or price pair api is down \n";
        exit();
    }
    $json = file_get_contents($urlPair);
    if ($json === false) {
        throw new Exception("Json decode fail price pair api");
    } else {
        return $json_data = json_decode($json, true);

    }
} catch (\Exception$e) {
    echo $e->getMessage();
    exit();
}
}
//Check if the url is valid and the json file was decoded sucessfully
function apiList()
{
    $urlList = "https://api.coingecko.com/api/v3/simple/supported_vs_currencies";
    if (in_array("HTTP/1.1 404 Not Found", get_headers($urlList))) {
        echo "Supported currencies api is down \n";
        exit();
    }

    $jsonAll = file_get_contents($urlList);
    try {
        if ($jsonAll === false) {
            throw new Exception("Could not decode json file");
        }
        return $jsonAll_data = json_decode($jsonAll, true);
    } catch (\Exception$e) {

        echo $e->getMessage();
        exit();
    }

}
//print supported currencies list.
function printList($apiList)
{
    natcasesort($apiList);
    foreach ($apiList as $value) {
        echo "$value \n";
    }
}

//išče user input fiat v listi podprtih parov
function currency($currency, $apiList)
{
    foreach ($apiList as $value) {
        if ("$currency" == $value) {
            return true;
        }
    }
}
//išče user input crypto v listi podprtih parov
function criptocurrency($criptocurrency, $apiList)
{
    foreach ($apiList as $value) {
        if ("$criptocurrency" == $value) {
            return true;
        }
    }
}
