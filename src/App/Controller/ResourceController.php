<?php

namespace App\Controller;

use OAuth2\Request;

class ResourceController extends OAuthController
{

    public function resourceAction()
    {
        $server = $this->oauth2Server;
        if (!$server->verifyResourceRequest(Request::createFromGlobals())) {
            $server->getResponse()->send();
            exit;
        }
        $this->sendJsonResponse(['success' => true, 'message' => 'You accessed my APIs!']);
    }
}