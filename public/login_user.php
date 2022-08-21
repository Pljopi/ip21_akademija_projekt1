<?php
require_once '../lib/bootstrap.php';
session_start();
$twig->addGlobal('session', $_SESSION);
echo $twig->render('pages/login.html.twig', ['user' => $_SESSION['useruid']]);
