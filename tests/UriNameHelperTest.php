<?php

namespace tests;

use utils\UriNameHelper;

class UriNameHelperTest extends \PHPUnit\Framework\TestCase
{
    public function testControllerClassNameFromId()
    {
        $controllerId = "death-skipper";
        $expectedControllerClass = "\\http\\controllers\\DeathSkipperController";
        $producedClassName = UriNameHelper::controllerClassNameFromId($controllerId);

        self::assertEquals($expectedControllerClass, $producedClassName);
    }

    public function testActionNameFromId()
    {
        $actionId = "email-sent";
        $expectedActionName = "actionEmailSent";
        $producedActionName = UriNameHelper::actionNameFromId($actionId);

        self::assertEquals($expectedActionName, $producedActionName);
    }
}