<?php

namespace App\Controller;

use App\Form\User\LoginForm;
use App\Form\User\RegistrationForm;
use App\OAuth\SelfSignedProvider;
use Bone\Mvc\Controller;
use Bone\Mvc\Registry;
use Del\Exception\EmailLinkException;
use Del\Icon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\MultipartStream;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Stream;

class OfficialWebAppController extends Controller
{
    /** @var SelfSignedProvider $oAuthClient */
    private $oAuthClient;

    /** @var string $host */
    private $host;

    /** @var string $locale */
    private $locale;

    /**
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function init()
    {
        $apiKeys = Registry::ahoy()->get('apiKeys');
        $options = $apiKeys['clientCredentials'];

        $this->host = $options['host'];
        $this->oAuthClient = new SelfSignedProvider($options);
        $this->locale = $this->getParam('locale', 'en_GB');
    }

    public function indexAction()
    {

    }

    public function thanksForRegisteringAction()
    {

    }

    /**
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function activateUserAccountAction()
    {
        $email = $this->getParam('email');
        $token = $this->getParam('token');
        $url = '/' . $this->locale.'/user/activate/' . $email . '/' . $token;
        $request = $this->getAuthenticatedRequest($url);
        try {
            $this->oAuthClient->getResponse($request);
            $this->view->activated = true;
            $this->view->message = [Icon::CHECK . ' Email successfully validated.', 'success'];
        } catch (ClientException $e) {
            $data = \json_decode($e->getResponse()->getBody()->getContents(), true);
            $this->view->message = [Icon::WARNING . '&nbsp;' . $data['error'], 'danger'];
            $this->view->activated = false;
            if ($data['error'] ==  EmailLinkException::LINK_EXPIRED) {
                $this->view->resendLink = '/website/resend-activation/' . $email;
            }
        }
    }

    /**
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function resendActivationAction()
    {
        $email = $this->getParam('email');
        $url = '/' . $this->locale . '/user/activate/resend/' . $email;
        $request = $this->getAuthenticatedRequest($url);
        try {
            $this->oAuthClient->getResponse($request);
            $response = new Response();
            $html = $this->viewEngine->render('official-web-app/thanks-for-registering');
            $html = $this->viewEngine->render('layouts/layout', ['content' => $html]);
            $stream = $this->createStreamFromString($html);

            return $response->withBody($stream);

        } catch (ClientException $e) {
            $data = \json_decode($e->getResponse()->getBody()->getContents(), true);
            $this->view->message = [Icon::WARNING . '&nbsp;' . $data['error'], 'danger'];
        }
    }

    /**
     * @return RedirectResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function registerAction()
    {
        $form = new RegistrationForm('register');

        if ($this->getRequest()->getMethod() == 'POST') {

            $formData = $this->getRequest()->getParsedBody();
            $form->populate($formData);
            if ($form->isValid()) {
                $values = $form->getValues();
                $request = $this->getAuthenticatedRequest('/en_GB/user/register', 'POST');
                $request = $this->addMultipartFormData($request, [
                    'email' => $values['email'],
                    'password' => $values['password'],
                    'confirm' => $values['confirm'],
                ]);

                try {

                    $this->oAuthClient->getResponse($request);
                    return new RedirectResponse('/website/thanks-for-registering');

                } catch (ClientException $e) {

                    $data = \json_decode($e->getResponse()->getBody()->getContents(), true);
                    $this->view->message = [Icon::WARNING . ' ' . $data['message'], 'danger'];
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function loginAction()
    {
        $form = new LoginForm('login');

        if ($this->getRequest()->getMethod() == 'POST') {

            $formData = $this->getRequest()->getParsedBody();
            $form->populate($formData);
            if ($form->isValid()) {
                $values = $form->getValues();
                $this->view->email = $values['email'];
                $request = $this->getAuthenticatedRequest('/en_GB/user/login', 'POST');
                $request = $this->addMultipartFormData($request, [
                    'email' => $values['email'],
                    'password' => $values['password'],
                ]);

                try {

                    $response = $this->oAuthClient->getResponse($request);
                    die(var_dump($response));

                } catch (ClientException $e) {

                    $data = \json_decode($e->getResponse()->getBody()->getContents(), true);
                    $this->view->message = [Icon::WARNING . ' ' . $data['message'], 'danger'];
                }
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
        $request = $this->getAuthenticatedRequest('/client');
        $response = $this->oAuthClient->getResponse($request);

        $data = \json_decode($response->getBody()->getContents());
        $response = new JsonResponse($data);

        return $response; // usually the data would be sent to a view for display, but that's outwith the scope
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


    /**
     * @param array $data
     * @return MultipartStream
     */
    public function createMultipartStream(array $data)
    {
        $elements = [];
        foreach ($data as $key => $val) {
            $elements[] = [
                'name' => $key,
                'contents' => $val,
            ];
        }
        $stream = new MultipartStream($elements);

        return $stream;
    }

    /**
     * @param $url
     * @param string $method
     * @return RequestInterface
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function getAuthenticatedRequest($url, $method = 'GET')
    {
        $token = $this->getAccessToken();
        $request = $this->oAuthClient->getAuthenticatedRequest($method, $this->host . $url, $token);

        return $request;
    }

    /**
     * @param RequestInterface $request
     * @param array $data
     * @return RequestInterface
     */
    public function addMultipartFormData(RequestInterface $request, array $data)
    {
        return $request->withBody($this->createMultipartStream($data));
    }

    /**
     * @return \League\OAuth2\Client\Token\AccessTokenInterface
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    private function getAccessToken()
    {
        return $this->oAuthClient->getAccessToken('client_credentials', ['scope' => ['admin']]);
    }
}
