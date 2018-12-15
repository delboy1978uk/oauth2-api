<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use InvalidArgumentException;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Stream;

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
        if (!is_object($object) && !is_array($object)) {
            throw new InvalidArgumentException('You must pass an object or array.');
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

    /**
     * @param ResponseInterface $response
     */
    protected function sendResponse(ResponseInterface $response)
    {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
        exit;
    }

    /**
     * @param $content
     * @return Stream
     */
    public function createStreamFromString($content)
    {
        $stream = new Stream('php://memory', 'wb+');
        $stream->write($content);
        $stream->rewind();

        return $stream;
    }
}