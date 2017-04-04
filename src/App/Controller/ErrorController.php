<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use Exception;

class ErrorController extends Controller
{

    public function errorAction()
    {
        /** @var Exception $e */
        $e = $this->getParam('error');
        $responseArray = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ];
        $this->sendJsonResponse($responseArray);
    }

    public function notFoundAction(){}

    public function notAuthorisedAction(){}
}