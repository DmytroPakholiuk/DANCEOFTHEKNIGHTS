<?php

namespace models;

use components\RedisProvider;
use exceptions\NotFoundException;

abstract class StringMessage extends RedisMessage
{
    public function validateFilled(): bool
    {
        $errors = [];
        foreach ($this->fields() as $field){
            if (!isset($this->$field)){
                $errors[] = " Field $field of " . static::class . " should not be empty \n ";
            }
        }
        if (!empty($errors)){
            throw new \AssertionError(implode($errors));
        }
        return true;
    }

    /**
     * It has to return an array of strings which indicates the props that are fields that have to be filled
     * @return string[]
     */
    protected function fields(): array
    {
        return [
            "id",
            "class"
        ];
    }

    /**
     * Generates models one-by-one, so you have to use it in foreach() loop.
     * This way it takes a lot less RAM than if we were to fetch it with foreach and return in array
     * @throws \ReflectionException
     * @throws \RedisException
     */
    public static function getAllModels(): \Generator
    {
        $redis = RedisProvider::getInstance()->getRedis();
        $allKeys = $redis->scan($iterator, static::prefix() . "*");

        foreach ($allKeys as $key){
            $searchKey = substr($key, strlen(static::prefix()));
            yield static::getModel($searchKey);
        }
    }

    /**
     * @throws \ReflectionException
     * @throws \RedisException
     */
    public static function getModel(string $id): static|null
    {
        $fullId = static::prefix() . $id;
        $redis = RedisProvider::getInstance()->getRedis();
        $modelDataJson = $redis->get($fullId);
        $modelDataStdClass = json_decode($modelDataJson);
        if (empty($modelDataStdClass)){
            return null;
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
        if (! $this->validateFilled()) {
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