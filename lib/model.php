<?php

/**
 * [Description Model]
 */

class Model
{
    private $mysql;

    public function __construct()
    {
        $this->mysql = new Mysql(
            $this->servername = getenv('DB_SERVERNAME'),
            $this->username = getenv('DB_USERNAME'),
            $this->password = getenv('DB_PASSWORD'),
            $this->dbname = getenv('DB_NAME'),
            $this->charset = getenv('DB_CHARSET')
        );
    }

    private $listOfCurencies = null;

    /**
     * @return array
     */

    public function getList()
    {
        if (!$this->listOfCurencies) {
            $this->listOfCurencies = $this->apiCall("https://api.coingecko.com/api/v3/simple/supported_vs_currencies");
        }
        return $this->listOfCurencies;
    }

    /**
     * @param string $criptoCurrency
     * @param string $currency
     *
     * @return array
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
     * @return array
     */
    private function apiCall($pricePairOrUrlPair)
    {

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $pricePairOrUrlPair,
            CURLOPT_RETURNTRANSFER => 1,
        ));
        $curl_data = curl_exec($ch);
        $httpCode = (curl_getinfo($ch, CURLINFO_HTTP_CODE));

        if (json_decode($curl_data, true) === null || json_decode($curl_data, true) === false) {
            throw new \Exception("Api is down\n");
        }
        if ($httpCode !== 200) {
            throw new \Exception("You have entered a unsupported currency pair\n WAT?=");
        }
        curl_close($ch);

        return json_decode($curl_data, true);

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

    /**
     * @param int $id
     * @param string $tag
     *
     */
    public function saveFavourite($parsedFavourite, $list)
    {

        foreach ($parsedFavourite as $tag) {
            $key = array_search($tag, $list);
            $this->mysql->insertFavorites($key, $list[$key]);
        }

    }

    public function getAllFavourites()
    {

        $getFavourites = $this->mysql->getFavorites();

        if (empty($getFavourites)) {
            return;
        }
        foreach ($getFavourites as $value) {
            $storeFavourites[] = $value['tag'];
        }

        return $storeFavourites;

    }

    public function removeFromFavourites($tag)
    {
        $this->mysql->removeFavorites($tag);
    }
}
