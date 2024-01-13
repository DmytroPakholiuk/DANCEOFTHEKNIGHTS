<?php

namespace components;

class Config
{
    protected const CONFIG_DIRECTORY_PATH = __DIR__ . "/../config/";

    public static function getConfigArray(string $configName): array
    {
        $fileName = self::CONFIG_DIRECTORY_PATH . $configName . ".php";
        $configArray = require $fileName;

        return $configArray;
    }
}