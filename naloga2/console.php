<?php

$currency = readline('Enter a string:');

if ($currency == '') {
    echo  "When propmted to enter a string, enter a crypto currency tag... try again... ";
} else if ($currency = "help") {
    echo "When propmted too enter a string, enter a cryptocurrency TAG, try BTC";
} else {
    $json = file_get_contents("https://api.coinbase.com/v2/prices/$currency-USD/spot");
    $json_data = json_decode($json, true);



    echo "Spot price of " . $json_data["data"]["base"] . " is " . $json_data["data"]["amount"] . " " . $json_data["data"]["currency"];
}
