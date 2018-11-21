<?php

namespace App\Controller;

use Del\Common\ContainerService;
use League\OAuth2\Server\ResourceServer;

class ResourceServerController extends BaseController
{
    /**
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function init()
    {
        $container = ContainerService::getInstance()->getContainer();
        $accessTokenRepository = $container['repository.AccessToken'];
        $publicKeyPath = 'file://'.APPLICATION_PATH.'/data/keys/public.key';
        $server = new ResourceServer(
            $accessTokenRepository,
            $publicKeyPath
        );
        $server->validateAuthenticatedRequest($this->getRequest());
    }

}