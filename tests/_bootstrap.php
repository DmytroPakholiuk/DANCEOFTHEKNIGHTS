<?php


spl_autoload_register(function($className)
{
    $namespace = str_replace("\\","/",__NAMESPACE__);
    $className = str_replace("\\","/",$className);
    $class = __DIR__ . "/../" . (empty($namespace) ? "" : $namespace . "/") . "{$className}.php";
    require_once($class);
});
