<?php

namespace App\Controller;

use Del\Common\ContainerService;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use OAuth\AccessToken;
use OAuth\Exception\OAuthException;
use OAuth\Repository\AccessTokenRepository;
use Zend\Diactoros\Response;

class ResourceServerController extends BaseController
{
    /** @var AccessToken $accessToken*/
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
        /** @var AccessTokenRepository $accessTokenRepository */
        $accessTokenRepository = $container['repository.AccessToken'];
        $publicKeyPath = 'file://' . APPLICATION_PATH . '/data/keys/public.key';
        $server = new ResourceServer(
            $accessTokenRepository,
            $publicKeyPath
        );
        try {
            $request = $server->validateAuthenticatedRequest($this->getRequest());
            $this->setRequest($request);
            $this->accessToken = $accessTokenRepository->findOneBy(['identifier' => $request->getAttribute('oauth_access_token_id')]);
            $this->client = $request->getAttribute('oauth_client_id');
            $this->scopes = $request->getAttribute('oauth_scopes');
            $this->user = $request->getAttribute('user');
        } catch (OAuthServerException $e) {
            $response = $e->generateHttpResponse(new Response());
            $this->sendResponse($response);
        }
    }

    /**
     * @param array $scopes
     * @return bool
     * @throws OAuthException
     */
    protected function scopeCheck(array $scopes): bool
    {
        $grantedScopes = $this->scopes;
        foreach ($scopes as $scope) {
            if (!in_array($scope, $grantedScopes)) {
                throw new OAuthException('Required scope has not been granted.');
            }
        }
        return true;
    }
}