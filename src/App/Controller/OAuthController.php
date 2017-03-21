<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateInterval;
use DateTime;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use OAuth2ServerExamples\Repositories\AccessTokenRepository;
use OAuth\Repository\ClientRepository;
use OAuth2ServerExamples\Repositories\RefreshTokenRepository;
use OAuth2ServerExamples\Repositories\ScopeRepository;
use OAuth2ServerExamples\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OAuthController extends Controller
{
    /** @var AuthorizationServer $oauth2Server */
    private $oauth2Server;

    public function init()
    {
        $this->oauth2Server = function () {
            // Setup the authorization server
            $server = new AuthorizationServer(
                new ClientRepository(),                 // instance of ClientRepositoryInterface
                new AccessTokenRepository(),            // instance of AccessTokenRepositoryInterface
                new ScopeRepository(),                  // instance of ScopeRepositoryInterface
                'file://'.__DIR__.'/../private.key',    // path to private key
                'file://'.__DIR__.'/../public.key'      // path to public key
            );

            $grant = new PasswordGrant(
                new UserRepository(),           // instance of UserRepositoryInterface
                new RefreshTokenRepository()    // instance of RefreshTokenRepositoryInterface
            );

            $grant->setRefreshTokenTTL(new \DateInterval('P1M')); // refresh tokens will expire after 1 month

            // Enable the password grant on the server with a token TTL of 1 hour
            $server->enableGrantType(
                $grant,
                new DateInterval('PT1H') // access tokens will expire after 1 month
            );
            return $server;
        };
    }

    public function pingAction()
    {
        $date = new DateTime();
        $this->sendJsonResponse(['pong' => $date->format('Y-m-d H:i:s')]);
    }
}
