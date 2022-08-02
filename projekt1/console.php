<?php

/**
 * @author Andrej C. <andrejcepak@gmail.com>
 */

/**
 * Instantiating Twig and model.php
 * @var $loader
 * @var $twig
 * @var $model
 */
require_once '/var/www/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('/var/www/lib/views');
$twig = new \Twig\Environment($loader, [
]);

require_once '/var/www/lib/model.php';
$model = new Model();
require_once '/var/www/lib/mysql.php';
$connect = new mysql();
/**
 * @return [string]
 */
if (!isset($argv[1])) {

    echo $twig->render('help.twig', []);

    return;
}
/**
 * controller block
 */
try {
    switch (strtolower($argv[1])) {
        case 'help';
            echo $twig->render('help.twig', []);
            break;

        case 'list';
            $list = $model->getList();
            $pdo = $connect->connect();
            echo $twig->render('list.twig', ['ListOfCurrencies' => $list]);
            echo "Do you wish to mark any currency as your favourite?(y/n)\n";
            $favCurrency = $model->favouriteCurrency();
            $favTags = implode("\n", $model->parseFav($favCurrency, $list));
            echo "The following currencies: \n" . $favTags . "\nhave been added to your database.\n";

            break;

        case 'price';

            if (!isset($argv[2]) || !isset($argv[3])) {
                throw new \Exception("After price, enter criptoCurrency and currency TAG\n");

            } else if (ifLongOrShort($argv[2], $twig) && ifLongOrShort($argv[3], $twig)) {

                $criptoCurrency = strtolower($argv[2]);
                $currency = strtolower($argv[3]);
            }

            if ($currency === $criptoCurrency) {
                throw new \Exception("You have entered two of the same currencies, input different currencies\n");

            }
            $list = $model->getList();
            if (!$model->areTheEnterdTagsOnList($currency) || !$model->areTheEnterdTagsOnList($criptoCurrency)) {
                throw new \Exception("The currency pair you have entered is not on the list of supported currencies\n");

            }

            list($criptoCurrencyTAG, $pairValue, $currencyTAG) = $model->getPrice($criptoCurrency, $currency);

            echo $twig->render('price.twig', ['criptoCurrencyTAG' => $criptoCurrencyTAG, 'pairValue' => $pairValue, 'currencyTAG' => $currencyTAG]);
            break;

        default;
            echo $twig->render('help.twig', []);
    }

} catch (\Exception$e) {
    echo $e->getMessage();
}

/**!
 * @param mixed $userInput
 *
 * @return [type]
 */
function ifLongOrShort($userInput, $twig)
{
    if (strlen($userInput) >= 5 or (strlen($userInput) <= 2)) {
        throw new \Exception($twig->render('error.$message.twig', []));

    }
    return true;
}
