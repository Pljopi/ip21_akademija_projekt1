<?php
//Imports functions from model.php, for api calling
require_once './lib/model.php';

//immports functions from consoleView.php for echoing responses to the user.
require_once './lib/views/consoleView.php';

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
            printList($getList);
            break;
//If first user inout is 'price'
        case 'price';
            //Checks if 2nd and 3rd input are there and that they are not too long. Once it passes this function it defined the 2nd and 3rd user input as variables.
            if (ifNotEmptyOrLong($argv)) {
                //Sets user input 2 and 3 as variables.
                $criptoCurrency = strtolower($argv[2]);
                $currency = strtolower($argv[3]);
            }
            //Checks that the enterd currency  and criptocurrency TAGs are not the same
            if ($currency === $criptoCurrency) {
                throw new \Exception("You have entered two of the same currencies, input different currencies\n");

            } //Checks if criptocurrency and currency variables are on the list of supported currencies
            if (!areTheEnterdTagsOnList($currency, $getList) || !areTheEnterdTagsOnList($criptoCurrency, $getList)) {
                throw new \Exception("The currency pair you have entered is not on the list of supported currencies\n");

            }

            //sets the return value from the getPrice function as a list of variables for the echoPrice function;
            list($criptoCurrencyTAG, $pairValue, $currencyTAG) = getPrice($criptoCurrency, $currency);
            //prints the final value of Criptocurrency denominated in the fiat currency.
            echoPrice($criptoCurrencyTAG, $pairValue, $currencyTAG);
            break;

        default;
            return helpTxt();
    }
} catch (\Exception$e) { //Catches exceptions, and returns the exception msg.
    echo $e->getMessage();
}

//Checks if user input 2/3 are on the list of supported currencies.
function areTheEnterdTagsOnList($currencyOrCriptoCurrency, $getList)
{
    return array_search($currencyOrCriptoCurrency, $getList, true) !== false;

}
//Checks if user input 1 and 2 have been filled, and are not longer than 5 characters.
function ifNotEmptyOrLong($argv)
{
    if (empty($argv[2]) || empty($argv[3])) {
        throw new \Exception("After price, enter criptoCurrency and currency TAG\n");

    } else if ((strlen($argv[2]) > 5) || (strlen($argv[3])) > 5) {
        throw new \Exception("User input error - no input can be longer than 5 characters\n");

    }
    return true;
}
