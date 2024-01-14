<?php

class GoodsItemModelTest extends \PHPUnit\Framework\TestCase
{
    public $redisProvider;
    public function setUp(): void
    {
        $this->redisProvider = \components\RedisProvider::getInstance();
        $this->redisProvider->getRedis()->select(1);
    }

    public function testSave()
    {
        $goodsItem = new \models\GoodsItem();
        $goodsItem->id = "https://www.olx.ua/d/uk/obyavlenie/krilo-kapot-fara-ksenon-bamper-skoda-octavia-a5-IDU7Xl1.html";
        $goodsItem->price = "250 грн.";
        $goodsItem->name = "Крило капот фара ксенон бампер skoda Octavia A5";
        $goodsItem->saveModel();

        $goodsRecordString = $this->redisProvider->getRedis()->get("goods_item_https://www.olx.ua/d/uk/obyavlenie/krilo-kapot-fara-ksenon-bamper-skoda-octavia-a5-IDU7Xl1.html");
        $expectedRecordString = "{\"id\":\"https:\/\/www.olx.ua\/d\/uk\/obyavlenie\/krilo-kapot-fara-ksenon-bamper-" .
        "skoda-octavia-a5-IDU7Xl1.html\",\"class\":\"models\\\\GoodsItem\",\"price\":\"250 \u0433\u0440\u043d.\",\"" .
        "name\":\"\u041a\u0440\u0438\u043b\u043e \u043a\u0430\u043f\u043e\u0442 \u0444\u0430\u0440\u0430" .
         " \u043a\u0441\u0435\u043d\u043e\u043d \u0431\u0430\u043c\u043f\u0435\u0440 skoda Octavia A5\"}";


        self::assertEquals($expectedRecordString, $goodsRecordString);
    }

    public function tearDown(): void
    {
        $this->redisProvider->getRedis()->flushDB();
        $this->redisProvider->getRedis()->close();
    }
}