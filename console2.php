<?php
set_error_handler("errorHandler", E_WARNING);
function errorHandler(
    int $type,
    string $msg,
    ?string $file = null,
    ?int $line = null
) {
    echo " Your input is incorrect, after file name enter cryptocurrency TAG, space, currency TAG"; //$type . " " . $msg . " " . " in " . $file . " on line " . $line . "BLYAT";
    exit;
}

$criptocurrency = $argv[1];
$currency = $argv[2];

if ((!$currency) or (!$criptocurrency)) {
    echo "after file name input criptocurrency TAG sapce currency TAG of the pair you would like to see";
} else if ($currency == "help" || $criptocurrency == "help") {
    echo "After file name input criptocurrency TAG sapce currency TAG of the pair you would like to see";
} else if (strlen($currency) > 5 || strlen($criptocurrency) > 5) {
    echo "Currency and criptocurrency TAG cannot be longer than 5 characters";
} else {
    $json = file_get_contents(sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptocurrency, $currency));
    $json_data = json_decode($json, true);
    echo "Spot price of " . $json_data["data"]["base"] . " is " . $json_data["data"]["amount"] . " " . $json_data["data"]["currency"];
}
