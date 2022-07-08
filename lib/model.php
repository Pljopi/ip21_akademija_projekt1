<?php

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
        throw new \Exception("You have entered a unsupported currency pair, or api is down\n");
        return;
    }

    return json_decode($curl_data, true);

    curl_close($ch);

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

    echoPrice($pricePair[0], $pricePair[1], $pricePair[2]);

}
//Echoes price pair, uses $pricePair array values 0-2 as parameters (from inside getPrice function)
