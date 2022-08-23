<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/mysql.php';





  $mysql = new Mysql();
require_once __DIR__ . '/model.php';
$model = new Model();
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
$twig = new \Twig\Environment($loader, [  ]);

$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Ljubljana');
$twig->addGlobal('session', $_SESSION);
