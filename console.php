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

require_once __DIR__ . '/lib/bootstrap.php';

/**
 * @return [string]
 */

if (!isset($argv[1])) {

    echo $twig->render('pages/help.twig', []);

    return;
}
/**
 * controller block
 */
try {
    switch (strtolower($argv[1])) {
        case 'help';
            echo $twig->render('pages/help.twig', []);
            break;

        case 'list';
            $list = $model->getList();

            echo $twig->render('pages/list.twig', ['ListOfCurrencies' => $list]);
            echo "Do you wish to mark any currency as your favourite?(y/n)\n";

            $favouriteCurrency = favouriteCurrency();
            $favouriteTags = implode("\n", parseFavourite($favouriteCurrency, $list, $model));

            echo $twig->render('pages/added.favourites.twig', ['FavTags' => $favouriteTags]);

            break;

        case 'favourites';
            $printFavourites = ($model->getAllFavourites());

            if (empty($printFavourites)) {
                echo "No favourites added yet\n";
                return;
            }
            echo $twig->render('pages/favourites.twig', ['printFavourites' => $printFavourites]);

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
            if (!$model->isCurrencyOnListOfSupported($currency) || !$model->isCurrencyOnListOfSupported($criptoCurrency)) {
                throw new \Exception("The currency pair you have entered is not on the list of supported currencies\n");

            }

            list($criptoCurrencyTAG, $pairValue, $currencyTAG) = $model->getPrice($criptoCurrency, $currency);

            echo $twig->render('pages/price.twig', ['criptoCurrencyTAG' => $criptoCurrencyTAG, 'pairValue' => $pairValue, 'currencyTAG' => $currencyTAG]);
            break;

        default;
            echo $twig->render('pages/help.twig', []);
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
        throw new \Exception($twig->render('pages/error.$message.twig', []));

    }
    return true;
}
/**
 * @param mixed $list
 *
 * @return [type]
 */
function favouriteCurrency()
{
    if (strtolower(readline()) === 'y' || strtolower(readline()) === 'yes') {
        echo "Enter currency code:\n";
        $input = readline();

        if (empty($input) && $input !== '0') {
            echo "For this to work you have to enter a currency code, try again.\n";
            exit;
        } else {
            return explode(",", str_replace(" ", "", ($input)));
        }
    } else {
        echo "Bye!\n";
        exit;
    }}

/**
 * @param mixed $favouriteCurrency
 * @param mixed $list
 *
 * @return [type]
 */
function parseFavourite($favouriteCurrency, $list, $model)
{
    foreach ($favouriteCurrency as $value) {
        if (array_key_exists($value, $list)) {

            $favs[] = $value;
        } else {
            $fail[] = $value;
        }
    }
    if (!empty($fail)) {
        echo "The following currencie codes do not exist:\n";
        foreach ($fail as $value) {
            echo $value . "\n";
        }
    }
    if (!empty($favs)) {
        foreach ($favs as $value) {
            $model->saveFavourite($value, $list[$value]);
            $favouriteTags[] = $list[$value];

        }
        return $favouriteTags;
    }

}
