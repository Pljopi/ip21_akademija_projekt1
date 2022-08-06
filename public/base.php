<?php
require_once '/var/www/projekt1/lib/bootstrap/bootstrap.php';

//echo ($twig->render('favourites.html.twig', ['printFav' => $printFav]));
echo ($twig->render('base.html.twig', ['printFav' => $printFav, 'ListOfCurrencies' => $list]));
