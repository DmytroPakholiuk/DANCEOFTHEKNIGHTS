<?php

class ControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testRender()
    {
        $view = "../tests/_data/test_view";
        ob_start();
        $controller = new \http\controllers\Controller();
        $controller->render($view, ["test" => "test"]);
        $renderedResult = ob_get_clean();

        self::assertEquals("<h1>A test view</h1>", $renderedResult);
    }

    public function testGetDefaultAction()
    {
        $controller = new \http\controllers\Controller();
        self::assertNotEmpty($controller->defaultAction());
        self::assertIsString($controller->defaultAction());
    }

//    public function testRedirect()
//    {
//        $expectedHeader = 4;
//        var_dump(get_headers("l"));die();
//    }
}