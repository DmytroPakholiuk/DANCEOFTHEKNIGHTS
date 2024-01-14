<?php

class EmailsForGoodsModelTest extends \PHPUnit\Framework\TestCase
{
    public $redisProvider;
    public function setUp(): void
    {
        $this->redisProvider = \components\RedisProvider::getInstance();
        $this->redisProvider->getRedis()->select(1);
    }

    public function testAppendUniqueModel()
    {
        $emailsForItem = new \models\EmailsForItem();
        $emailsForItem->id = "https://www.olx.ua/d/uk/obyavlenie/krilo-kapot-fara-ksenon-bamper-skoda-octavia-a5-IDU7Xl1.html";

        $email = new \models\Email();
        $email->id = "example@example.com";
        $email->confirmationHash = "neirngienngisenreisign";
        $email->saveModel();

        $emailsForItem->appendUniqueModel($email);

        $resultRecordArray = $this->redisProvider->getRedis()->lRange("emails_for_item_https://www.olx.ua/d/uk/obyavlenie/krilo-kapot-fara-ksenon-bamper-skoda-octavia-a5-IDU7Xl1.html", 0, -1);
        $expectedContains = "{\"id\":\"example@example.com\",\"class\":\"models\\\\Email\"," .
            "\"active\":false,\"confirmationHash\":\"neirngienngisenreisign\"}";

        self::assertContains($expectedContains, $resultRecordArray);
    }

    public function tearDown(): void
    {
        $this->redisProvider->getRedis()->flushDB();
        $this->redisProvider->getRedis()->close();
    }
}