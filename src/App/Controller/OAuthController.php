<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use Del\Common\ContainerService;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;

class OAuthController extends BaseController
{
    /** @var AuthorizationServer $oauth2Server */
    protected $oauth2Server;

    public function init()
    {

        $container = ContainerService::getInstance()->getContainer();
        $clientRepository = $container['repository.Client'];
        $accessTokenRepository = $container['repository.AccessToken'];
        $scopeRepository = $container['repository.Scope'];

        // Setup the authorization server
        $server = new AuthorizationServer($clientRepository, $accessTokenRepository, $scopeRepository,
            'file://'.APPLICATION_PATH.'/data/keys/private.key',    // path to private key
            'file://'.APPLICATION_PATH.'/data/keys/public.key'      // path to public key
        );

        $this->oauth2Server = $server;
    }

    /**
     * @param ResponseInterface $response
     */
    protected function sendResponse(ResponseInterface $response)
    {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
        exit;
    }
}
