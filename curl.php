<?php

try {

    $handle = curl_init();
    $url = "https://api.coingecko.com/api/v3/simple/supported_vs_currencie";
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_exec($handle);

} catch (\Exception$e) {

    echo $e->getMessage();
    exit();
}
