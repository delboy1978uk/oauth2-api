<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use InvalidArgumentException;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Http\Message\ServerRequestInterface;

class BaseController extends Controller
{
    /** @var Serializer */
    protected $serializer;

    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request);
        $this->serializer = SerializerBuilder::create()->build();
        $this->disableView();
        $this->disableLayout();
    }

    /**
     * @param $object
     * @param int $statusCode
     */
    public function sendJsonObjectResponse($object, $statusCode = 200)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('You must pass an object.');
        }
        $this->disableLayout();
        $this->disableView();
        $this->setHeader('Cache-Control', 'no-cache, must-revalidate');
        $this->setHeader('Expires','Mon, 26 Jul 1997 05:00:00 GMT');
        $this->setHeader('Content-Type','application/json');
        $json = $this->serializer->serialize($object, 'json');
        $this->setBody($json);
        $this->setStatusCode($statusCode);
    }

    protected function httpMethodCheck($method)
    {
        if ($this->getRequest()->getMethod() !== $method) {
            $this->sendJsonResponse(['error' => 'Method not allowed'], 405);

            return false;
        }

        return true;
    }
}