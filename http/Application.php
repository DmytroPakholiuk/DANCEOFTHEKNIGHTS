<?php

namespace http;

class Application
{
    protected Router $router;

    public function run()
    {
        $this->router->executeAction();
    }

    public function __construct()
    {
        $this->router = new Router();
    }
}