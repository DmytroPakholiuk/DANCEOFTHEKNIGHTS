<?php

class EmailModelTest extends \PHPUnit\Framework\TestCase
{
    public $redisProvider;
    public function setUp(): void
    {
        $this->redisProvider = \components\RedisProvider::getInstance();
        $this->redisProvider->getRedis()->select(1);
    }

    public function testSave()
    {
        $email = new \models\Email();
        $email->id = "example@example.com";
        $email->confirmationHash = "neirngienngisenreisign";
        $email->saveModel();

        $emailRecordString = $this->redisProvider->getRedis()->get("email_example@example.com");
        $expectedEmail = "{\"id\":\"example@example.com\",\"class\":\"models\\\\Email\"," .
            "\"active\":false,\"confirmationHash\":\"neirngienngisenreisign\"}";

        self::assertEquals($expectedEmail, $emailRecordString);
    }

    public function testActivationCorrect()
    {
        $email = new \models\Email();
        $email->id = "example@example.com";
        $confirmation = $email->generateConfirmationKey();
        $email->generateConfirmationHash($confirmation);
        $email->saveModel();

        $email->activate($confirmation);
        self::assertTrue($email->active);
    }

    public function testActivationIncorrect()
    {
        $email = new \models\Email();
        $email->id = "example@example.com";
        $confirmation = $email->generateConfirmationKey();
        $email->generateConfirmationHash($confirmation);
        $email->saveModel();

        $email->activate(random_bytes(10));
        self::assertFalse($email->active);
    }

    public function tearDown(): void
    {
        $this->redisProvider->getRedis()->flushDB();
        $this->redisProvider->getRedis()->close();
    }
}