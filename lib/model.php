<?php
//sets variable with api url with list of all supported currencies
function getList()
{
    return apiCall("https://api.coingecko.com/api/v3/simple/supported_vs_currencies");
}

//Call api function for currencies list and price pair value
function apiCall($pricePairOrUrlPair)
{
    try {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $pricePairOrUrlPair,
            CURLOPT_RETURNTRANSFER => 1,
        ));
        $curl_data = curl_exec($ch);
        $httpCode = (curl_getinfo($ch, CURLINFO_HTTP_CODE));
        //Checks that the url request has succeded, otherwise it ends execution and echoes message for user //not sure, if I should be catching exceptions in the model part
        if (json_decode($curl_data, true) === null || json_decode($curl_data, true) === false) {
            throw new \Exception("Api is down\n");

        }
        if ($httpCode !== 200) {
            throw new \Exception("You have entered a unsupported currency pair\n");

        }
        curl_close($ch);

        return json_decode($curl_data, true);

    } catch (\Exception$e) { //Catches exceptions, and returns the exception msg.
        echo $e->getMessage();
        exit; //without this exit, function keeps executing. Don't know how to solve
    }
}

//Get's price pair value and echoes it to the user
function getPrice($criptoCurrency, $currency)
{

    $pricePairUrl = sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptoCurrency, $currency);
    $pricePairUrlApiCall = apiCall($pricePairUrl);
    $pricePair = [
        $pricePairUrlApiCall["data"]["base"],
        $pricePairUrlApiCall["data"]["amount"],
        $pricePairUrlApiCall["data"]["currency"],
    ];
    return [$pricePair[0], $pricePair[1], $pricePair[2]];
}
