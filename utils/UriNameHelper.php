<?php

namespace utils;

class UriNameHelper
{
    public static function controllerClassNameFromId(string $id): string
    {
        $wordArray = explode("-", $id);
        $upperCaseWordArray = [];
        foreach ($wordArray as $item) {
            $upperCaseWordArray[] = ucfirst($item);
        }
        $className = "\\http\\controllers\\" . implode("", $upperCaseWordArray) . "Controller";

        return $className;
    }

    public static function actionNameFromId(string $id): string
    {
        $wordArray = explode("-", $id);
        $upperCaseWordArray = [];
        foreach ($wordArray as $item) {
            $upperCaseWordArray[] = ucfirst($item);
        }
        $actionName = "action" . implode("", $upperCaseWordArray);

        return $actionName;
    }
}