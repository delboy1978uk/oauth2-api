<?php

namespace App\Controller;

use Zend\Diactoros\Response;

class AccessTokenController extends OAuthController
{

    public function accessTokenAction()
    {
        $server = $this->oauth2Server;

        $request = $this->getRequest();
        $response = new Response();


        $this->sendResponse($response);
    }
}