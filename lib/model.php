<?php

/**
 * [Description Model]
 */
require_once 'mysql.php';
$connect = new mysql();
class Model
{

    /**
     * @var null
     */
    private $listOfCurencies = null;

    /**
     * @return [type]
     */
    public function getList()
    {
        if ($this->listOfCurencies) {
            return $this->listOfCurencies;
        } else {
            $this->listOfCurencies = $this->apiCall("https://api.coingecko.com/api/v3/simple/supported_vs_currencies");
            return $this->listOfCurencies;
        }
    }

    /**
     * @param mixed $criptoCurrency
     * @param mixed $currency
     *
     * @return [type]
     */
    public function getPrice($criptoCurrency, $currency)
    {
        $pricePairUrl = sprintf("https://api.coinbase.com/v2/prices/%s-%s/spot", $criptoCurrency, $currency);
        $pricePairUrlApiCall = $this->apiCall($pricePairUrl);

        $pricePair = [
            $pricePairUrlApiCall["data"]["base"],
            $pricePairUrlApiCall["data"]["amount"],
            $pricePairUrlApiCall["data"]["currency"],
        ];
        return [$pricePair[0], $pricePair[1], $pricePair[2]];
    }

    /**
     * @param mixed $pricePairOrUrlPair
     *
     * @return [type]
     */
    private function apiCall($pricePairOrUrlPair)
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $pricePairOrUrlPair,
                CURLOPT_RETURNTRANSFER => 1,
            ));
            $curl_data = curl_exec($ch);
            $httpCode = (curl_getinfo($ch, CURLINFO_HTTP_CODE));
            //Checks that the url request has succeded, otherwise it ends execution and echoes message for user //not sure, if I should be catching exceptions in the model part
            if (json_decode($curl_data, true) === null || json_decode($curl_data, true) === false) {
                throw new \Exception("Api is down\n");

            }
            if ($httpCode !== 200) {
                throw new \Exception("You have entered a unsupported currency pair\n");

            }
            curl_close($ch);

            return json_decode($curl_data, true);

        } catch (\Exception$e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @param mixed $currency
     *
     * @return [type]
     */
    public function areTheEnterdTagsOnList($currency)
    {
        $list = $this->getList();
        return array_search($currency, $list, true) !== false;

    }

    /**
     * @param mixed $list
     *
     * @return [type]
     */
    public function favouriteCurrency()
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
     * @param mixed $favCurrency
     * @param mixed $list
     *
     * @return [type]
     */
    public function parseFav($favCurrency, $list)
    {
        foreach ($favCurrency as $value) {
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
                $this->insertFav($value, $list[$value]);
                $favTags[] = $list[$value];

            }
            return $favTags;
        } else {
            echo "No currencies were added to favourites\n";
            exit;
        }

    }

    /**
     * @param mixed $list
     *
     * @return [type]
     */
    public function insertFav($ID, $TAG)
    {
        $object = new mysql();
        $pdo = $object->connect();
        $query = 'INSERT INTO Favourites (ID, TAG) VALUES (:ID,:TAG) ON DUPLICATE KEY UPDATE ID=:ID, TAG=:TAG';
        $stmt = $pdo->prepare($query);
        return $stmt->execute(
            [
                ':ID' => $ID,
                ':TAG' => $TAG,
            ]
        );

    }
    public function printFav()
    {
        $object = new mysql();
        $pdo = $object->connect();
        $query = 'SELECT * FROM Favourites';
        $stmt = $pdo->query($query);
        $printFav = $stmt->fetchAll();
        foreach ($printFav as $value) {
            $storeFav[] = $value['TAG'];
        }

        return $storeFav;

    }

}
