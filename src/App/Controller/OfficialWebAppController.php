<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use App\OAuth\SelfSignedProvider;
use Bone\Mvc\Controller;
use Bone\Mvc\Registry;
use Zend\Diactoros\Response\JsonResponse;

class OfficialWebAppController extends Controller
{
    public function indexAction()
    {
        $x;
    }

    public function registerAction()
    {
        $form = new RegistrationForm('register');
        $this->view->form = $form;
    }

    /**
     * Sample page using client_credentials grant to connect to the API
     *
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function clientCredentialsExampleAction()
    {
        try {
            // This code fetches your access token
            // The self signed provider is for dev use only!
            $apiKeys = Registry::ahoy()->get('apiKeys');
            $options = $apiKeys['clientCredentials'];

            $provider = new SelfSignedProvider($options);

            $accessToken = $provider->getAccessToken('client_credentials', ['scope' => ['admin']]);
            $request = $provider->getAuthenticatedRequest('GET', $options['host'] . '/client', $accessToken);
            $response = $provider->getResponse($request);

            $data = \json_decode($response->getBody()->getContents());
            $response = new JsonResponse($data);

            return $response; // usually the data would be sent to a view for display, but that's outwith the scope
        } catch (\Exception $e) {
            die($e->getCode() . $e->getMessage() .  $e->getTraceAsString());
        }
    }

}
