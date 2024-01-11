<?php

namespace http;

use exceptions\NotFoundException;
use http\controllers\Controller;
use utils\UriNameHelper;

class Router
{
    protected string $uri;
    protected string $defaultController = "site";
    protected Controller $controller;
    public function __construct()
    {
        $this->uri = $_SERVER["REQUEST_URI"];
    }

    /**
     * Main entrance from Application. It analyzes the URI and decides which action of
     * which controller should be called. The whole scheme of work resembles that of Yii2 routing:
     * the controller class is generated from its id by applying PascalCase to it and appending "Controller".
     * The action name is generated by applying PascalCase and prepending "action"
     * @return void
     */
    public function executeAction()
    {
        try {
            $this->controller = $this->generateController();
            $this->triggerAction();
        } catch (NotFoundException $exception) {
            echo "404";
        }
    }

    /**
     * creates an instance of controller whose action should be called
     * @throws NotFoundException
     */
    private function generateController()
    {
        try {
            $controllerName = $this->getControllerId();
            $fullClassName = UriNameHelper::controllerClassNameFromId($controllerName);
            $controller = (new \ReflectionClass($fullClassName))->newInstance();

            return $controller;
        } catch (\ReflectionException) {
            throw new NotFoundException();
        }
    }

    /**
     * returns the controller id found in URI
     * @return string
     */
    private function getControllerId(): string
    {
        $uriArray = explode("/", $this->uri);

        return !empty($uriArray[1]) ? $uriArray[1] : $this->defaultController;
    }

    /**
     * Decides which action of the controller should be called and calls it
     * @return mixed
     */
    private function triggerAction()
    {
        $actionName = UriNameHelper::actionNameFromId($this->getActionId());

        return call_user_func([$this->controller, $actionName]);
    }

    /**
     * returns the action id that is found in URI
     * @return string
     */
    private function getActionId(): string
    {
        $uriArray = explode("/", $this->uri);

        return !empty($uriArray[2]) ? $uriArray[2] : $this->controller->defaultAction();
    }
}