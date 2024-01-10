<?php

namespace http;

class Application
{

    public function run()
    {
        $router = new Router();
        $router->executeAction();
    }
}