<?php

namespace models;

use components\RedisProvider;
use exceptions\NotFoundException;

abstract class StringMessage extends RedisMessage
{
    /**
     * @throws \ReflectionException
     * @throws NotFoundException
     * @throws \RedisException
     */
    public static function getModel(string $id): RedisMessage|null
    {
        $fullId = static::prefix() . $id;
        $redis = RedisProvider::getInstance()->getRedis();
        $modelDataJson = $redis->get($fullId);
        $modelDataStdClass = json_decode($modelDataJson);
        if (empty($modelDataStdClass)){
            throw new NotFoundException("This model was not found in database");
        }

        $model = (new \ReflectionClass($modelDataStdClass->class))->newInstance();
        foreach ((array) $modelDataStdClass as $property => $value){
           $model->$property = $value;
        }

        return $model;
    }

    public function saveModel()
    {
        $fullId = $this->getFullId();
        if ($fullId === null) {
            return false;
        }

        $redis = $this->redisProvider->getRedis();
        $modelJson = json_encode($this);

        if ($redis->set($fullId, $modelJson)){
            return true;
        }
        throw new \Exception("Something went wrong when saving model");
    }
}