<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

class ErrorController extends BaseController
{
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request);
    }

    public function errorAction()
    {
        /** @var Exception $e */
        $e = $this->getParam('error');
        $responseArray = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ];
        $this->sendJsonResponse($responseArray, $e->getCode());
    }

    public function notFoundAction()
    {
        $this->sendJsonResponse(['message' => 'Resource not found'], 404);
    }

    public function notAuthorisedAction(){}
}