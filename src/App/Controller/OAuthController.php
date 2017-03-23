<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateInterval;
use DateTime;
use Del\Common\ContainerService;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

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
        /* @var AuthorizationServer $server */
        $server = $this->oauth2Server;

        $request = $this->getRequest();
        $response = new Response();

        try {
            // Try to respond to the access token request
            $response = $server->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);
        } catch (Exception $exception) {
            $body = $response->getBody();
            $body->write($exception->getMessage());
            $response = $response->withStatus(500)->withBody($body);
        }
        $this->sendResponse($response);
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
