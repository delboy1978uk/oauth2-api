<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use App\OAuth\SelfSignedProvider;
use Bone\Mvc\Controller;
use Bone\Mvc\Registry;
use Del\Icon;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Stream;

class OfficialWebAppController extends Controller
{
    /** @var SelfSignedProvider $oAuthClient */
    private $oAuthClient;

    /** @var string $token */
    private $token;

    /** @var string $host */
    private $host;

    /**
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function init()
    {
        $apiKeys = Registry::ahoy()->get('apiKeys');
        $options = $apiKeys['clientCredentials'];

        $this->host = $options['host'];
        $this->oAuthClient = new SelfSignedProvider($options);
        $this->token = $this->oAuthClient->getAccessToken('client_credentials', ['scope' => ['admin']]);
    }

    public function indexAction()
    {

    }

    public function registerAction()
    {
        $form = new RegistrationForm('register');

        if ($this->getRequest()->getMethod() == 'POST') {

            $formData = $this->getRequest()->getParsedBody();
            $form->populate($formData);
            $values = $form->getValues();
            $request = $this->oAuthClient->getAuthenticatedRequest(
                'POST',
                $this->host . '/en_GB/user/register',
                $this->token
            );
            $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
            $streamData = http_build_query([
                'email' => $values['email'],
                'password' => $values['password'],
                'confirm' => $values['confirm'],
            ]);
            $request = $request->withBody($this->createStreamFromString($streamData));
            try {
                $response = $this->oAuthClient->getResponse($request);
                $this->view->message = ['you have been registered redirect to thanks page', 'success'];
            } catch (ClientException $e) {
                $data = \json_decode($e->getResponse()->getBody()->getContents(), true);
                $this->view->message = [Icon::WARNING . ' ' . $data['message'], 'danger'];
            }
        }

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
        } catch (Exception $e) {
            die($e->getCode() . $e->getMessage() .  $e->getTraceAsString());
        }
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
