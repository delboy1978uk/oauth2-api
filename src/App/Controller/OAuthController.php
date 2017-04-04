<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateInterval;
use DateTime;
use Del\Common\ContainerService;
use Exception;
use OAuth2\Server;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

class OAuthController extends Controller
{
    /** @var Server $oauth2Server */
    protected $oauth2Server;

    public function init()
    {
        $container = ContainerService::getInstance()->getContainer();
        $clientRepository = $container['repository.Client'];
        $accessTokenRepository = $container['repository.AccessToken'];
        $scopeRepository = $container['repository.Scope'];
        $userRepository = $container['repository.User'];
        $refreshTokenRepository = $container['repository.RefreshToken'];


        $server = new Server([
            'client_credentials' => $clientRepository,
            'user_credentials'   => $userRepository,
            'access_token'       => $accessTokenRepository,
        ], [
            'auth_code_lifetime' => 30,
            'refresh_token_lifetime' => 30,
        ]);
        $this->oauth2Server = $server;
    }


    /**
     * Sends a response with the time
     */
    public function pingAction()
    {
        $date = new DateTime();
        $this->sendJsonResponse(['pong' => $date->format('Y-m-d H:i:s')]);
    }

    /**
     * @param ResponseInterface $response
     */
    public function sendResponse(ResponseInterface $response)
    {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
        exit();
    }
}
