<?php

class ConsoleView
{
    //Echoes price pair, uses $pricePair array values 0-2 as parameters (from inside getPrice function)
    public function printPrice($criptoCurrencyTAG, $pairValue, $currencyTAG)
    {

        echo sprintf("Spot price of %s is %s %s\n", $criptoCurrencyTAG, $pairValue, $currencyTAG);
        return;
    }
// Echo list of supported currencies, each currency in a new line
    public function printList($list)
    {$listOfSupportedCurrencies = $list;
        natcasesort($listOfSupportedCurrencies);
        foreach ($listOfSupportedCurrencies as $value) {
            echo "$value \n";
        }
    }
//Echo help txt
    public function printHelpTxt()
    {
        echo "Help -\n After file name input either >list< to get a list of supported currencies\nor\n>price< >FiatCurrency< TAG >criptoCurrency TAG< \n";

    }

}
