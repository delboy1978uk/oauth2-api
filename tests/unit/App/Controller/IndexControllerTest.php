<?php
namespace App\Controller;

use InvalidArgumentException;
use Zend\Diactoros\ServerRequest;

class IndexControllerTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var IndexController */
    protected $controller;

    /**
     * @throws \Exception
     */
    protected function _before()
    {
        if (!defined('APPLICATION_PATH')){
            define('APPLICATION_PATH','.');
        }
        $request = new ServerRequest();
        $this->controller = new IndexController($request);
    }

    protected function _after()
    {
        unset($this->controller);
    }

    public function testIndexAction()
    {
        $this->assertNull($this->controller->indexAction());
    }

    public function testSendObjectThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->controller->sendJsonObjectResponse('oops');
    }
}