<?php

namespace http\controllers;

class Controller
{
    protected string $viewDirectoryPath = "/../../views/";
    public function defaultAction()
    {
        return "index";
    }

    public function render(string $viewName, array $params = [])
    {
        extract($params, EXTR_OVERWRITE);
        require __DIR__ . $this->viewDirectoryPath . $viewName . ".php";
    }

    public function redirect(string $url, $status = 303)
    {
        header('Location: ' . $url, true, $status);
        die();
    }
}