<?php

namespace App\Controller;

use OAuth2\Request;

class AccessTokenController extends OAuthController
{

    public function accessTokenAction()
    {
        $server = $this->oauth2Server;
        $response = $server->handleTokenRequest(Request::createFromGlobals());
        $response->send();
        exit;
    }
}