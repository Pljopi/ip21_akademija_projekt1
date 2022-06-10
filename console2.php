<?php

$criptocurrency = $argv[1];
$currency = $argv[2];

$lenghtcurrency = strlen($currency);
$lenghtcriptocurrency = strlen($criptocurrency);

if (($currency == '') or ($criptocurrency == '')) {
    echo "after file name input criptocurrency TAG sapce currency TAG of the pair you would like to see";
} else if ($currency == "help" or $criptocurrency == "help") {
    echo "After file name input criptocurrency TAG sapce currency TAG of the pair you would like to see";
} else if ($lenghtcriptocurrency or $currency > 5) {
    echo "Currency and criptocurrency TAG cannot be longer than 5 characters";
    $json = file_get_contents("https://api.coinbase.com/v2/prices/$criptocurrency-$currency/spot");
    $json_data = json_decode($json, true);
    echo "Spot price of " . $json_data["data"]["base"] . " is " . $json_data["data"]["amount"] . " " . $json_data["data"]["currency"];
}
