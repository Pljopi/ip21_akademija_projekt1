<?php

/**
 * [Description Model]
 */

class Model
{

    /**
     * @var null
     */
    private $listOfCurencies = null;
    /**
     * @var null
     */
    private $connect = null;
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
     * @param mixed $ID
     * @param mixed $TAG
     *
     * @return [type]
     */
    public function printOrInsertFavourite($ID, $TAG)
    {
        $this->connect = new mysql("php_app_db:3306", "root", "root", "crypto", "utf8mb4");
        $pdo = $this->connect->connect();

        if (isset($TAG)) {
            $query = 'INSERT INTO Favourites (ID, TAG) VALUES (:ID,:TAG) ON DUPLICATE KEY UPDATE ID=:ID, TAG=:TAG';
            $stmt = $pdo->prepare($query);

            return $stmt->execute(
                [
                    ':ID' => $ID,
                    ':TAG' => $TAG,
                ]
            );
        } else {
            $query = 'SELECT * FROM Favourites';
            $stmt = $pdo->query($query);
            $printFavourites = $stmt->fetchAll();

            if (empty($printFavourites)) {
                return;
            }
            foreach ($printFavourites as $value) {
                $storeFavourites[] = $value['TAG'];
            }
            return $storeFavourites;
        }

    }
}
