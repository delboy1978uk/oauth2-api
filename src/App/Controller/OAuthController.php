<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateInterval;
use DateTime;
use Del\Common\ContainerService;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use OAuth\Repository\AccessTokenRepository;
use OAuth\Repository\ClientRepository;
use OAuth\Repository\RefreshTokenRepository;
use OAuth\Repository\ScopeRepository;
use OAuth\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OAuthController extends Controller
{
    /** @var AuthorizationServer $oauth2Server */
    private $oauth2Server;

    public function init()
    {
        $container = ContainerService::getInstance()->getContainer();
        $clientRepository = $container['repository.Client'];
        $accessTokenRepository = $container['repository.AccessToken'];
        $scopeRepository = $container['repository.Scope'];
        $userRepository = $container['repository.User'];
        $refreshTokenRepository = $container['repository.RefreshToken'];

        // Setup the authorization server
        $server = new AuthorizationServer($clientRepository, $accessTokenRepository, $scopeRepository,
            'file://'.APPLICATION_PATH.'/data/keys/private.key',    // path to private key
            'file://'.APPLICATION_PATH.'/data/keys/public.key'      // path to public key
        );

        $grant = new PasswordGrant($userRepository, $refreshTokenRepository);

        $grant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month

        // Enable the password grant on the server with a token TTL of 1 hour
        $server->enableGrantType(
            $grant,
            new DateInterval('PT1H') // access tokens will expire after 1 month
        );
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


    public function accessTokenAction()
    {
        $this->sendJsonResponse(['accessTokenRequested' => time()]);
    }
}
