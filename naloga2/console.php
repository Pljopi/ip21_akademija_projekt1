<?php

$json = file_get_contents("https://api.coinbase.com/v2/prices/BTC-USD/spot");
$json_data = json_decode($json, true);
echo "Spot price of " . $json_data["data"]["base"] . " is " . $json_data["data"]["amount"] . " " . $json_data["data"]["currency"];
