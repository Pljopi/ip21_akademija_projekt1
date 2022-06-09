<?php

$currency = readline('Enter a string:');

if ($currency == '') {
    echo  "When propmted to, enter a crypto currency tag... try again... ";
} else if ($currency == "help") {
    echo "When propmted to, enter a crypto currency tag like BTC, ETH, ...";
} else {
    $json = file_get_contents("https://api.coinbase.com/v2/prices/$currency-USD/spot");
    $json_data = json_decode($json, true);


    echo "Spot price of " . $json_data["data"]["base"] . " is " . $json_data["data"]["amount"] . " " . $json_data["data"]["currency"];
}
