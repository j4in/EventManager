<?php

class EventManagerTest extends PHPUnit_Framework_TestCase
{
    public static $res;
    private $em;
    function __construct()
    {
        $this->em = new \J4in\EventManager();
    }

    function testTestSuite()
    {
        $this->assertTrue($this->em instanceof \J4in\EventManager);
    }
    function testClausureCallback()
    {
        $this->em->bind('my.event', function($param){
            self::$res =  $param;
        });
        \J4in\EventManager::fire('my.event', 'working');
        $this->assertEquals(self::$res,'working');
    }
    function testStaticCallbackFunction()
    {
        $this->em->bind('static.callback', 'myStaticFunction');
        \J4in\EventManager::fire('static.callback', 'somerandomstuff');
        $this->assertEquals(self::$res,'somerandomstuff');
    }
    function testNonStaticCallbackFunction()
    {
        $this->em->bind('static.callback', 'myNonStaticFunction');
        \J4in\EventManager::fire('static.callback', 'myNonStaticFunction');
        $this->assertEquals(self::$res,'myNonStaticFunction');
    }

    function testNonStaticCallbackFunctionThatDoesNotExist()
    {
        $this->em->bind('static.callback', 'notMyNonStaticFunction');
        $response = \J4in\EventManager::fire('static.callback', 'myNonStaticFunction');
        $this->assertEquals($response, 'NO_METHOD');
    }
    function testStaticCallbackFunctionThatDoesNotExist()
    {
        $this->em->bind('static.callback', 'notMyStaticFunction');
        $response = \J4in\EventManager::fire('static.callback', 'myNonStaticFunction');
        $this->assertEquals($response, 'NO_METHOD');
    }

    public static function myStaticFunction($param)
    {
        self::$res = $param;
    }
    public function myNonStaticFunction($param)
    {
        self::$res = $param;
    }

}