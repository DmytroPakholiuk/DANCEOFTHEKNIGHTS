<?php

namespace models;

use components\RedisProvider;
use exceptions\NotFoundException;

abstract class ListMessage extends RedisMessage
{
    /**
     * contains all models that are inside that list
     * @var StringMessage[]
     */
    public array $containedModels = [];

    /**
     * returns a ListMessage model of the class that was called in.
     * Do not call this method on abstract class as it does not see the intended ListMessage class itself
     * @param string $id
     * @return ListMessage|null
     * @throws \RedisException
     * @throws \ReflectionException
     */
    public static function getModel(string $id): static|null
    {
        $fullId = static::prefix() . $id;
        $redis = RedisProvider::getInstance()->getRedis();
        $modelsDataArray = $redis->lRange($fullId, 0, -1);

//        if (empty($modelsDataArray)){
//            throw new NotFoundException("This model was not found in database");
//        }
        $listModel = new static();
        $listModel->id = $id;
        foreach ($modelsDataArray as $item) {
            $modelDataStdClass = json_decode($item);
            $model = (new \ReflectionClass($modelDataStdClass->class))->newInstance();
            foreach ((array) $modelDataStdClass as $property => $value){
                $model->$property = $value;
            }
            $listModel->containedModels[] = $model;
        }

        return $listModel;
    }

    public function appendUniqueModel(StringMessage $message)
    {
        $this->removeModel($message);
        $this->appendModel($message);
    }

    public function appendModel(StringMessage $message)
    {
        $redis = $this->redisProvider->getRedis();
        $fullId = $this->getFullId();
        if (! $message->validateFilled()){
            return false;
        }
        $modelData = json_encode($message);
        $this->containedModels[] = $message;
        return $redis->lPush($fullId, $modelData);
    }

    public function removeModel(StringMessage $message)
    {
        $redis = $this->redisProvider->getRedis();
        $fullId = $this->getFullId();
        $modelData = json_encode($message);
        $redis->lRem($fullId, $modelData, 1);
        if (($key = array_search($message, $this->containedModels)) !== false) {
            unset($this->containedModels[$key]);
        }
    }
}