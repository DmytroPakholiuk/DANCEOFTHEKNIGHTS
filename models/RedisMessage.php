<?php

namespace models;

use components\RedisProvider;

abstract class RedisMessage
{
    public const TYPE_STRING = 1;
    public const TYPE_LIST = 2;
    public string $id;
    public string $class;
    protected RedisProvider $redisProvider;
    public static abstract function prefix(): string;

    public function getFullId(): ?string
    {
        return !empty($this->id) ? static::prefix() . $this->id : null;
    }

    public static abstract function getModel(string $id): RedisMessage|null;

    public function __construct()
    {
        $this->class = static::class;
        $this->redisProvider = RedisProvider::getInstance();
    }
}