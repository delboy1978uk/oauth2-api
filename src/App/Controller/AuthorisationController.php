<?php

namespace App\Controller;

use Del\Form\Field\Radio;
use Del\Form\Field\Submit;
use Del\Form\Form;
use OAuth2\Request;
use OAuth2\Response;

class AuthorisationController extends OAuthController
{

    public function authAction()
    {
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
    }

    /**
     * @return Form
     */
    private function getForm()
    {
        $form = new Form('auth');
        $radio = new Radio('auth');
        $radio->setOptions([
            'yes' => 'Yes',
            'no' => 'No',
        ]);
        $radio->setLabel('Do you authorise TestClient?');
        $radio->setRenderInline(true);
        $radio->setRequired(true);
        $submit = new Submit('submit');

        $form->addField($radio)
            ->addField($submit);

        return $form;
    }
}