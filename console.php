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
require_once './vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('lib/views');
$twig = new \Twig\Environment($loader, [
]);

require_once './lib/model.php';
$model = new Model();
/**
 * @return [string]
 */
if (!isset($argv[1])) {

    echo $twig->render('help.html.twig', []);

    return;
}
/**
 * controller block
 */
try {
    switch (strtolower($argv[1])) {
/**
 * cases are triggered by the coresponding user input
 * @var $list
 * @var $argv
 * @var $criptocurrency
 * @var $currency
 * @var $criptoCurrencyTAG
 * @var $pairValue
 * @var $currencyTAG
 * @return [string]
 */
        case 'help';
            echo $twig->render('help.html.twig', []);
            break;

        case 'list';
            $list = $model->getList();
            echo $twig->render('list.html.twig', ['ListOfCurrencies' => $list]);
            break;

        case 'price';

            if (!isset($argv[2]) || !isset($argv[3])) {
                throw new \Exception("After price, enter criptoCurrency and currency TAG\n");

            } else if (ifLongOrShort($argv[2]) && ifLongOrShort($argv[3])) {

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

            echo $twig->render('price.html.twig', ['criptoCurrencyTAG' => $criptoCurrencyTAG, 'pairValue' => $pairValue, 'currencyTAG' => $currencyTAG]);
            break;

        default;
            echo $twig->render('help.html.twig', []);
    }

} catch (\Exception$e) {
    echo $e->getMessage();
}

/**
 * @param mixed $userInput
 *
 * @return [type]
 */
function ifLongOrShort($userInput)
{
    if (strlen($userInput) >= 5 or (strlen($userInput) <= 2)) {
        throw new \Exception("User input error - no input can be longer than 5 characters or shorter than 2 characters\n");

    }
    return true;
}
