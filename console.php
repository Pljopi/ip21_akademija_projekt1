<?php
//Set supported currencies list api url variable
$urlList = "https://api.coingecko.com/api/v3/simple/supported_vs_currencies";
//Set variable as decoded array of supported currenices
$listOfSupportedCurrencies = getListOfSupportedCurrencies($urlList);
//if no user input echo Help, and exit.
if (!isset($argv[1])) {
    echo "Type >help< for help, and >list< to get a list of supported currenies\n";
    exit();
}
//Controller
switch (strtolower($argv[1])) {
//if 1st user input is help
    case 'help';
        helpTxt();
        break;
//If 1st user input is 'list'
    case 'list';
        printList($listOfSupportedCurrencies);
        break;
//If first user inout is 'price'
    case 'price';
        //Checks if 2nd and 3rd input are there and that they are not too long
        ifEmptyOrLong($argv);
        //Sets 2nd and 3rd user inputs as variables, and calls getPricePairValue();
        getPrice($argv, $listOfSupportedCurrencies);
        break;

    default:helpTxt();
}
//help text
function helpTxt()
{
    echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >FiatCurrency< TAG >criptoCurrency TAG< \n";
    exit();
}
//Echoes price pair, uses $pricePair array values 0-2 as parameters
function printPricePair($pricePair)
{
    echo sprintf("Spot price of %s is %s %s\n", $pricePair[0], $pricePair[1], $pricePair[2]);
    exit();
}

//Call api function to get a list of supported currencies
function getListOfSupportedCurrencies($pricePairOrUrlPair)
{
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $pricePairOrUrlPair,
        CURLOPT_RETURNTRANSFER => 1,
    ));
    $curl_data = curl_exec($ch);
    $httpCode = (curl_getinfo($ch, CURLINFO_HTTP_CODE));
    //Checks that the url request has succeded, otherwise it ends execution and echoes message for user
    if ($httpCode == !200) {
        echo "Supported currencies list Api is down";
        exit();
    }
    //returns array of supported currencies
    return json_decode($curl_data, true);
    curl_close($ch);
}
//Call api function for the price pair, and checks that the price pair is supported
function getPricePairValue($pricePairOrUrlPair)
{
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $pricePairOrUrlPair,
        CURLOPT_RETURNTRANSFER => 1,
    ));
    $curl_data = curl_exec($ch);
    $httpCode = (curl_getinfo($ch, CURLINFO_HTTP_CODE));
    //Checks that the url request has succeded, otherwise it ends execution and echoes message for user
    if ($httpCode == !200) {
        echo "Currency price api is down";
        exit();
    }
    $decode = json_decode($curl_data, true);
    //checks that "data" key exists, returns array with parameters for printPricePair function
    if (array_key_exists("data", $decode) === true) {
        return array(
            $decode["data"]["base"],
            $decode["data"]["amount"],
            $decode["data"]["currency"]);
        //Checks if "errors" key exists. this stops program execution in case 2 criptocurrency values are entered as user input 1 and 2, which would lead to program not stopping at all.
    } else if (array_key_exists("errors", $decode)) {
        echo "Unsupported currency pair, type list, for a list of supported currencies\n";
        exit();
        //probably not needed, haven't triggerd it yet -.-'' If triggered, ends program execution with a message for user.
    } else {
        echo "uknown error";
        exit();
    }
    curl_close($ch);
}
//Sorts currencies list by alphabet order and breaks line after each one.Ease of read only.
function printList($listOfSupportedCurrencies)
{
    natcasesort($listOfSupportedCurrencies);
    foreach ($listOfSupportedCurrencies as $value) {
        echo "$value \n";
    }
}
//Checks if user input 2/3 are on the list of supported currencies.
function areTheEnterdTagsOnList($currencyOrCriptoCurrency, $listOfSupportedCurrencies)
{
    return array_search($currencyOrCriptoCurrency, $listOfSupportedCurrencies, true) !== false;

}
//Checks if user input 1 and 2 have been filled, and are not longer than 5 characters.
function ifEmptyOrLong($argv)
{
    if (empty($argv[2]) || empty($argv[3])) {
        echo "After price, enter criptoCurrency and currency TAG\n";
        exit();
    } else if ((strlen($argv[2]) > 5) || (strlen($argv[3])) > 5) {
        echo "User input error - no input can be longer than 5 characters\n";
        exit();
    }
}
//Get's price pair value and echoes it to the user
function getPrice($argv, $listOfSupportedCurrencies)
{ //Sets user input 2 and 3 as variables, sets price pair url, according to user input 1 and 2.
    $criptoCurrency = strtolower($argv[2]);
    $currency = strtolower($argv[3]);
    $PricePairUrl = sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptoCurrency, $currency);
    //Checks that both user inputs are on the list of supported currencies
    if (!areTheEnterdTagsOnList($currency, $listOfSupportedCurrencies) || !areTheEnterdTagsOnList($criptoCurrency, $listOfSupportedCurrencies)) {
        echo "The currency pair you have entered is not on the list of supported currencies\n";
        exit();
    } else {
        //Sets array price pair parameters as return value from getPricePairValue api function
        $pricePair = getPricePairValue($PricePairUrl);
        //Echoes price pair value according to set parameters
        printPricePair($pricePair, $currency, $criptoCurrency);
    }
}
