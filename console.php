<?php
//Declare $apiUrlList variable as the return value from function apiUrlList();
$urlList = "https://api.coingecko.com/api/v3/simple/supported_vs_currencies";
$apiUrlList = callAPi($urlList);

// If no user input... exit
switch ($argv) {
    case (empty($argv[1]));
        echo "Type >help< for help, and >list< to get a list of supported currenies\n";
        exit();

//If user input is help...exit

    case (in_array("help", $argv));
        echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >FiatCurrency< TAG >criptoCurrency TAG< \n";
        exit();
//If user input is list...exit
    case (in_array("list", $argv));
        printList($apiUrlList);
        exit();

//If there is no 2nd and 3rd input + but first input is price...exit
    case (empty($argv[2]) || empty($argv[3]));
        if (strtolower($argv[1]) == "price") {echo "After price, enter criptoCurrency and currency TAG\n";
            exit();
        } else {echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >FiatCurrency< TAG >criptoCurrency TAG< \n";
            exit();

        }

//if any of the inputs are too long...exit
    case ((strlen($argv[1]) > 5) || (strlen($argv[2]) > 5) || (strlen($argv[3])) > 5);
        echo "User input error - no input can be longer than 5 characters\n";
        exit();

//if first input is price + check if 2nd and 3rd input ar on the list of valid currencies
    case (strtolower($argv[1]) == "price");

        $criptoCurrency = strtolower($argv[2]);
        $currency = strtolower($argv[3]);
        $urlPair = sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptoCurrency, $currency);
        if (!areTheEnterdTagsOnList($currency, $apiUrlList) || !areTheEnterdTagsOnList($criptoCurrency, $apiUrlList)) {
            echo "The currency pair you have entered is not on the list of supported currencies\n";
            exit();
        } else {
            $pricePair = callApi($urlPair); //Declare $pricePair variable as the return value of function getPriceFromApi();

        }

        //If all is good echo the price pair
        printPricePair($pricePair);
        exit();
    default:echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >FiatCurrency< TAG >criptoCurrency TAG< \n";
}
//function to echo PricePair
function printPricePair($pricePair)
{
    echo sprintf("Spot price of %s is %s %s\n", $pricePair["data"]["base"], $pricePair["data"]["amount"], $pricePair["data"]["currency"]);

}
//Call api function, placeholder variable x.
function callApi($x)
{
    if (in_array("HTTP/1.1 404 Not Found", get_headers($x))) {
        echo "Api is down, or you have entered a unsupported currency pair. First enter the Fiat Currency TAG, then enter the Cripto Currency TAG \n";
        exit();
    }
    @$jsonAll = file_get_contents($x);
    try {
        if ($jsonAll === false) {
            throw new Exception("Could not decode json file\n");
        }
        return $jsonAll_data = json_decode($jsonAll, true);
    } catch (\Exception$e) {

        echo $e->getMessage();
        exit();
    }
}

//print supported currencies list.
function printList($apiUrlList)
{
    natcasesort($apiUrlList);
    foreach ($apiUrlList as $value) {
        echo "$value \n";
    }
}
//Verify that the enterd currency Tags are on the list of supported currencies, placeholder variable $y
function areTheEnterdTagsOnList($y, $apiUrlList)
{
    foreach ($apiUrlList as $value) {
        if ($y == $value) {
            return true;
        }
    }
}
