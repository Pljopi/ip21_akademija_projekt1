<?php
require_once '../lib/bootstrap.php';

if(($_GET['num_tries']) == 'failed'){
    $login = "You have exceeded the maximum number of tries, try again in 1h\n";
    echo ($twig->render('pages/unsucessfull.login.html.twig', ['login' => $login]));
}else {
$numberOfRemaingTries = $_GET['num_tries'];

        $login = "Incorrect password. You have $numberOfRemaingTries tries left.\n";
    echo ($twig->render('pages/unsucessfull.login.html.twig', ['login' => $login]));
    exit;
}







