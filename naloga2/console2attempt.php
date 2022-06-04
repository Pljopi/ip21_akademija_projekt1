<?php



$curl = curl_init();


curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_URL, "https://api.coinbase.com/v2/prices/BTC-USD/spot");

curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);

$response = curl_exec($curl);


curl_setopt($curl, CURLOPT_URL, "https://api.coinbase.com/v2/prices/ETH-USD/spot");


$response2 = curl_exec($curl);


//var_dump($decodeData2);



curl_close($curl);
