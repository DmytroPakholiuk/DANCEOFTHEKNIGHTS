<?php

namespace components;

use exceptions\NotFoundException;
use models\GoodsItem;

class OlxDomParser
{
    public const PAGES_DIRECTORY = __DIR__ . "/../storage/olxPages/";
    public string $url;
    public GoodsItem $goodsItem;
    public string $page;

    /**
     *
     * @param GoodsItem $item - the GoodsItem passed to this method must have its ID filled with advert URL
     * @return GoodsItem
     */
    public function populateGoodsItem(GoodsItem $item): GoodsItem
    {
        if (!isset($item->id)){
            return $item;
        }
        $this->downloadPage($item->id);
        $item->price = $this->getPrice();
        $item->name = $this->getName();
        return $item;
    }
    public function downloadPage($url)
    {
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);

        $this->page = curl_exec($curlHandle);
        if(curl_getinfo($curlHandle,  CURLINFO_RESPONSE_CODE) === 404
            || curl_error($curlHandle)) {
            throw new NotFoundException("The link that you sent was not valid");
        }
        curl_close($curlHandle);
    }

    public function getPrice(): ?string
    {
        $matches = [];
        preg_match("/data-testid=\"ad-price-container\".*?>.*?<h3.*?>(.*?)<\/h3>/", $this->page, $matches);
        return $matches[1] ?? "";
    }

    public function getName(): ?string
    {
        $matches = [];
        preg_match("/data-cy=\"ad_title\".*?>.*?<h4.*?>(.*?)<\/h4>/", $this->page, $matches);
        return $matches[1] ?? "";
    }
}