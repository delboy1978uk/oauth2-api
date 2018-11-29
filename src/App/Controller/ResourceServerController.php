<?php

namespace App\Controller;

use Del\Common\ContainerService;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Zend\Diactoros\Response;

class ResourceServerController extends BaseController
{
    /** @var string $accessToken*/
    protected $accessToken;

    /** @var string $client */
    protected $client;

    /** @var array $scopes */
    protected $scopes;

    /** @var null|int $user */
    protected $user;

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
            $request = $server->validateAuthenticatedRequest($this->getRequest());
            $this->setRequest($request);
            $this->accessToken = $request->getAttribute('oauth_access_token_id');
            $this->client = $request->getAttribute('oauth_client_id');
            $this->scopes = $request->getAttribute('oauth_scopes');
            $this->user = $request->getAttribute('user');
        } catch (OAuthServerException $e) {
            $response = $e->generateHttpResponse(new Response());
            $this->sendResponse($response);
        }
    }

}