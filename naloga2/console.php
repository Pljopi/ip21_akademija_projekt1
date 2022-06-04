<?php

$json = file_get_contents('response.json');

$json_data = json_decode($json, true);

//sort($json_data);
print_r($json_data);
