<?php

$criptocurrency = $argv[1];
$currency = $argv[2];

if (($currency == '') or ($criptocurrency == '')) {
    echo "after file name input criptocurrency TAG sapce currency TAG of the pair you would like to see";
} else if ($currency == "help" or $criptocurrency == "help") {
    echo "after file name input criptocurrency TAG sapce currency TAG of the pair you would like to see";
} else {
    $json = file_get_contents("https://api.coinbase.com/v2/prices/$criptocurrency-$currency/spot");
    $json_data = json_decode($json, true);
    echo "Spot price of " . $json_data["data"]["base"] . " is " . $json_data["data"]["amount"] . " " . $json_data["data"]["currency"];
}
