<?php
ob_start();
require_once '../lib/bootstrap.php';

echo $twig->render(
    'pages/no.favourites.twig',
    []
);
