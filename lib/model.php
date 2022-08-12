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
     * @return [type]
     */
    public function getList()
    {
        if (!$this->listOfCurencies) {
            $this->listOfCurencies = $this->apiCall("https://api.coingecko.com/api/v3/simple/supported_vs_currencies");
        }
        return $this->listOfCurencies;
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
    public function isCurrencyOnListOfSupported($currency)
    {
        $list = $this->getList();
        return array_search($currency, $list, true) !== false;
    }

    private function connect()
    {
        $mysql = new mysql("php_app_db:3306", "root", "root", "crypto", "utf8mb4");
        return $mysql->__construct("php_app_db:3306", "root", "root", "crypto", "utf8mb4");
    }
    /**
     * @param mixed $id
     * @param mixed $tag
     *
     * @return [type]
     */
    public function saveFavourite($id, $tag)
    {
        $pdo = $this->connect();
        if (isset($id) && isset($tag)) {
            $query = 'INSERT INTO Favourites (id, tag) VALUES (:id,:tag) ON DUPLICATE KEY UPDATE id=:id, tag=:tag';
            $stmt = $pdo->prepare($query);

            return $stmt->execute(
                [
                    ':id' => $id,
                    ':tag' => $tag,
                ]
            );
        }
    }

    public function getAllFavourites()
    {
        $pdo = $this->connect();
        $query = 'SELECT * FROM Favourites';
        $stmt = $pdo->query($query);
        $getFavourites = $stmt->fetchAll();

        if (empty($getFavourites)) {
            return;
        }
        foreach ($getFavourites as $value) {
            $storeFavourites[] = $value['tag'];
        }
        return $storeFavourites;
    }
}
