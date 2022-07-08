<?php
//Set supported currencies list api url variable
$urlList = "https://api.coingecko.com/api/v3/simple/supported_vs_currencies";
//Set variable as decoded array of supported currenices
$listOfSupportedCurrencies = apiCall($urlList);
//if no user input echo Help, and exit.
if (!isset($argv[1])) {
    helpTxt();
    return;
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
        if (ifEmptyOrLong($argv) !== false) {
            //Sets user input 2 and 3 as variables, sets price pair url, according to user input 1 and 2.
            $criptoCurrency = strtolower($argv[2]);
            $currency = strtolower($argv[3]);
        } else {
            return;}

        if (!areTheEnterdTagsOnList($currency, $listOfSupportedCurrencies) || !areTheEnterdTagsOnList($criptoCurrency, $listOfSupportedCurrencies)) {
            echo "The currency pair you have entered is not on the list of supported currencies\n";
            return;
        }
        if ($currency === $criptoCurrency) {
            echo "You have entered two of the same currencies, input different currencies\n";
            return;
        }

        //Sets 2nd and 3rd user inputs as variables, and calls apiCall();
        getPrice($criptoCurrency, $currency);
        break;

    default;
        return helpTxt();
}
//help text
function helpTxt()
{
    echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >FiatCurrency< TAG >criptoCurrency TAG< \n";
    return;
}

//Call api function for currencies list and price pair value
function apiCall($pricePairOrUrlPair)
{
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $pricePairOrUrlPair,
        CURLOPT_RETURNTRANSFER => 1,
    ));
    $curl_data = curl_exec($ch);
    $httpCode = (curl_getinfo($ch, CURLINFO_HTTP_CODE));
    //Checks that the url request has succeded, otherwise it ends execution and echoes message for user
    if ($httpCode !== 200) {
        echo "You have entered a unsupported currency pair, or currency price api is down\n";
        exit();
    }

    if ($pricePairOrUrlPair == "https://api.coingecko.com/api/v3/simple/supported_vs_currencies") {
        return json_decode($curl_data, true);
    } else {
        $decode = json_decode($curl_data, true);
        if (array_key_exists("data", $decode) === true) {
            return [
                $decode["data"]["base"],
                $decode["data"]["amount"],
                $decode["data"]["currency"]];
            //Checks if "errors" key exists. this stops program execution in case 2 criptocurrency values are entered as user input 1 and 2, which would lead to program not stopping at all.
        } else if (array_key_exists("errors", $decode)) {
            echo "Unsupported currency pair, type list, for a list of supported currencies\n";
            return;
            //probably not needed, haven't triggerd it yet -.-'' If triggered, ends program execution with a message for user.
        } else {
            echo "uknown error";
            exit();
        }
        curl_close($ch);
    }

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
        return false;
    } else if ((strlen($argv[2]) > 5) || (strlen($argv[3])) > 5) {
        echo "User input error - no input can be longer than 5 characters\n";
        return false;
    }
}
//Get's price pair value and echoes it to the user
function getPrice($criptoCurrency, $currency)
{

    $pricePairUrl = sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptoCurrency, $currency);
    //Sets price pair variables as return values from apiCall array.
    $criptoCurrencyTAG = apiCall($pricePairUrl)[0];
    $pairValue = apiCall($pricePairUrl)[1];
    $currencyTAG = apiCall($pricePairUrl)[2];

    //Echoes price pair, uses $pricePair array values 0-2 as parameters
    echo sprintf("Spot price of %s is %s %s\n", $criptoCurrencyTAG, $pairValue, $currencyTAG);
    return;
}
