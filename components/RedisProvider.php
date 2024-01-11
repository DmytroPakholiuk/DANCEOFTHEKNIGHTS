<?php

namespace components;

use models\RedisMessage;

class RedisProvider
{
//    protected string $appPrefix = "danceoftheknights_";
    protected \Redis $redis;
    public function getRedis(): \Redis
    {
        return $this->redis;
    }
//    public function saveModel(RedisMessage $message)
//    {
//        $id = $this->fullIdOfModel($message);
//    }

//    protected function fullIdOfModel(RedisMessage $message): ?string
//    {
//        $id = !empty($message->id) ? $message->prefix() . $message->id : null;
//
//        return $id;
//    }
    private static self $instance;
    public static function getInstance(): self
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->pconnect("175.10.20.5");
    }
}