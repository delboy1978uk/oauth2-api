<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateTime;
use Del\Common\ContainerService;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Scope;
use OAuth2\Server;
use Psr\Http\Message\ResponseInterface;
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
        $authCodeRepository = $container['repository.AuthCode'];
        $scopeRepository = $container['repository.Scope'];
        $userRepository = $container['repository.User'];
        $refreshTokenRepository = $container['repository.RefreshToken'];


        $server = new Server([
            'client_credentials' => $clientRepository,
            'user_credentials'   => $userRepository,
            'access_token'       => $accessTokenRepository,
            'authorization_code' => $authCodeRepository,
            'refresh_token'      => $refreshTokenRepository,
        ], [
            'auth_code_lifetime' => 30,
            'refresh_token_lifetime' => 30,
        ]);

        $server->addGrantType(new ClientCredentials($clientRepository));
        $server->addGrantType(new AuthorizationCode($authCodeRepository));
        $server->addGrantType(new RefreshToken($refreshTokenRepository, [
            'always_issue_new_refresh_token' => true,
        ]));

        $scope = new Scope(array(
            'supported_scopes' => array('email', 'personal_details')
        ));
        $server->setScopeUtil($scope);

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
