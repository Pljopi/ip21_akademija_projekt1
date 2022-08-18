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
            throw new \Exception("You have entered a unsupported currency pair\n");
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
     * @param mixed $id
     * @param mixed $tag
     *
     * @return [type]
     */
    public function saveFavourite($parsedFavourite, $list)
    {
        $mysql = new Mysql;
        foreach ($parsedFavourite as $tag) {
            $key = array_search($tag, $list);
        }

        $mysql->insertFavorites($key, $list[$key]);

    }

    /**
     * @return [type]
     */
    public function getAllFavourites()
    {
        $mysql = new Mysql;
        $getFavourites = $mysql->getFavorites();

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
        $mysql = new Mysql;
        $mysql->removeFavorites($tag);
    }
}
