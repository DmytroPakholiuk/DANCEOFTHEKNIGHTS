<?php

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    public \http\Application $application;
    public $routerMock;
    public function setUp(): void
    {
        $_SERVER["REQUEST_URI"] = "/";
        $this->application = new \http\Application();
        $this->routerMock = $this->getMockBuilder(\http\Router::class)->getMock();

        $reflection = new ReflectionClass($this->application);
        $reflection_property = $reflection->getProperty("router");
        $reflection_property->setAccessible(true);

        $reflection_property->setValue($this->application, $this->routerMock);

//        ob_start();
    }

    public function testRun()
    {
        $this->routerMock->expects($this->once())
            ->method("executeAction")
            ->willReturn(null);
        $this->application->run();
    }

    public function tearDown(): void
    {
        ob_clean();
    }
}