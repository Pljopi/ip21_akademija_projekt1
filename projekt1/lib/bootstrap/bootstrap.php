<?php
require_once '/var/www/projekt1/lib/mysql.php';
$connect = new mysql();
require_once '/var/www/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('/var/www/projekt1/lib/views');
$twig = new \Twig\Environment($loader, [
]);
