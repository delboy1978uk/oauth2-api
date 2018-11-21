<?php

namespace App\Controller;


use OAuth2\Request;
use OAuth2\Response;

class AuthorisationController extends OAuthServerController
{

    public function authAction()
    {
        /*
        $server = $this->oauth2Server;

        $request = Request::createFromGlobals();
        $response = new Response();

        if (!$server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            exit;
        }

        $form = $this->getForm();

        if (empty($_POST)) {
            $html = $form->render();
            echo $html;
            exit;
        } else {
            $post = $this->getRequest()->getParsedBody();
            $form->populate($post);
            if ($form->isValid()) {
                $data = $form->getValues();
                $isAuthorized = ($data['auth'] === 'yes');
                $server->handleAuthorizeRequest($request, $response, $isAuthorized);
                $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
                exit("SUCCESS! Authorization Code: $code");
            }
        }
        $response->setStatusCode(400);
        $response->send();
        exit;
        */
    }


}