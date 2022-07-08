<?php
require_once '/home/andrej/ip21_akademija_projekt1/lib/model.php';
//Set variable as decoded array of supported currenices, got from the apiCall function
$listOfSupportedCurrencies = apiCall("https://api.coingecko.com/api/v3/simple/supported_vs_currencies");

//if no user input echo Help, and exit.
if (!isset($argv[1])) {
    helpTxt();
    return;
}
//Controller
try { //try, catch block around the controller
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
                //Sets user input 2 and 3 as variables.
                $criptoCurrency = strtolower($argv[2]);
                $currency = strtolower($argv[3]);
            }
            if (!areTheEnterdTagsOnList($currency, $listOfSupportedCurrencies) || !areTheEnterdTagsOnList($criptoCurrency, $listOfSupportedCurrencies)) {
                throw new \Exception("The currency pair you have entered is not on the list of supported currencies\n");
                return;
            } //Checks that the enterd currency  and criptocurrency TAGs are not the same
            if ($currency === $criptoCurrency) {
                throw new \Exception("You have entered two of the same currencies, input different currencies\n");
                return;
            }

            //Sets 2nd and 3rd user inputs as  api url variables, calls apiCall(), sets array variables for crypto currency, value and currency. Prints out the pair price value;
            getPrice($criptoCurrency, $currency);
            break;

        default;
            return helpTxt();
    }} catch (\Exception$e) { //Catches exceptions, and returns the exception msg.
    echo $e->getMessage();
}
//help text
function helpTxt()
{
    echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >FiatCurrency< TAG >criptoCurrency TAG< \n";
    return;
}

//Call api function for currencies list and price pair value

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
        throw new \Exception("After price, enter criptoCurrency and currency TAG\n");
        return false;
    } else if ((strlen($argv[2]) > 5) || (strlen($argv[3])) > 5) {
        throw new \Exception("User input error - no input can be longer than 5 characters\n");
        return false;
    }
}
//Get's price pair value and echoes it to the user

//Echoes price pair, uses $pricePair array values 0-2 as parameters (from inside getPrice function)

function echoPrice($criptoCurrencyTAG, $pairValue, $currencyTAG)
{

    echo sprintf("Spot price of %s is %s %s\n", $criptoCurrencyTAG, $pairValue, $currencyTAG);
    return;
}
