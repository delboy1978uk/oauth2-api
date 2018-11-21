<?php

namespace App\Controller;

use Del\Common\ContainerService;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Zend\Diactoros\Response;

class ResourceServerController extends BaseController
{
    /**
     * @throws OAuthServerException
     */
    public function init()
    {
        $container = ContainerService::getInstance()->getContainer();
        $container['repository.Client']; // this is a weird doctrine/pimple bug?
        // comment this ^ out and you cant get the repo below! mapping from access token to scope!
        $accessTokenRepository = $container['repository.AccessToken'];
        $publicKeyPath = 'file://' . APPLICATION_PATH . '/data/keys/public.key';
        $server = new ResourceServer(
            $accessTokenRepository,
            $publicKeyPath
        );
        try {
            $server->validateAuthenticatedRequest($this->getRequest());
        } catch (OAuthServerException $e) {
            $response = $e->generateHttpResponse(new Response());
            $this->sendResponse($response);
        }
    }

}