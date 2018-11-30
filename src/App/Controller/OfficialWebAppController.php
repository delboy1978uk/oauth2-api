<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use App\OAuth\SelfSignedProvider;
use Bone\Mvc\Controller;
use Bone\Mvc\Registry;
use GuzzleHttp\Client;
use Zend\Diactoros\Response\JsonResponse;

class OfficialWebAppController extends Controller
{
    public function indexAction()
    {

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

        // This code fetches your access token
        // The self signed provider is for dev use only!
        $apiKeys = Registry::ahoy()->get('apiKeys');
        $keys = $apiKeys['clientCredentials'];

        $provider = new SelfSignedProvider([
            'clientId'                => $keys['clientId'],
            'clientSecret'            => $keys['clientSecret'],
            'redirectUri'             => '',
            'urlAuthorize'            => 'http://not-used-with-this-grant',
            'urlAccessToken'          => $keys['urlAccessToken'],
            'urlResourceOwnerDetails' => $keys['urlResourceOwnerDetails'],
            'verify' => false,
        ]);

        $accessToken = $provider->getAccessToken('client_credentials', ['scope' => ['admin']]);

        // From here on we start calling the API
        $client = new Client(['verify' => false]);
        $response = $client->get('https://apache/client', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken->getToken(),
            ],
        ]);
        $data = \json_decode($response->getBody()->getContents());
        $response = new JsonResponse($data);

        return $response; // usually the data would be sent to a view for display, but that's outwith the scope
    }

}
