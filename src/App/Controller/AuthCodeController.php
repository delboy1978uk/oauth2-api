<?php

namespace App\Controller;

use DateInterval;
use Del\Common\ContainerService;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use OAuth\User;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class AuthCodeController extends OAuthController
{
    public function init()
    {
        parent::init();
        $container = ContainerService::getInstance()->getContainer();
        $authCodeRepository = $container['repository.AuthCode'];
        $refreshTokenRepository = $container['repository.RefreshToken'];
        $this->oauth2Server->enableGrantType(
            new AuthCodeGrant(
                $authCodeRepository,
                $refreshTokenRepository,
                new DateInterval('PT10M')
            ),
            new DateInterval('PT1H')
        );
    }

    /**
     *
     * @SWG\Get(
     *     path="/oauth2/authorize",
     *     @SWG\Response(response="200", description="An access token"),
     *     tags={"auth"},
     *     @SWG\Parameter(
     *         name="response_type",
     *         in="query",
     *         type="string",
     *         description="the type of response",
     *         required=true,
     *         default="code"
     *     ),
     *     @SWG\Parameter(
     *         name="client_id",
     *         in="query",
     *         type="string",
     *         description="the client identifier",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="redirect_uri",
     *         in="query",
     *         type="string",
     *         description="where to send the response",
     *         required=false
     *     ),
     *     @SWG\Parameter(
     *         name="state",
     *         in="query",
     *         type="string",
     *         description="with a CSRF token. This parameter is optional but highly recommended.",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="scope",
     *         in="query",
     *         type="string",
     *         description="allowed scopes, space separated",
     *         required=false,
     *     )
     * )
     */
    public function authorizeAction()
    {
        /* @var AuthorizationServer $server */
        $server = $this->oauth2Server;

        $request = $this->getRequest();
        $response = new Response();

        try {
            // Validate the HTTP request and return an AuthorizationRequest object.
            // The auth request object can be serialized into a user's session
            $authRequest = $server->validateAuthorizationRequest($request);
            // Once the user has logged in set the user on the AuthorizationRequest
            $authRequest->setUser(new User());
            // Once the user has approved or denied the client update the status
            // (true = approved, false = denied)
            $authRequest->setAuthorizationApproved(true);
            // Return the HTTP redirect response
            $response = $server->completeAuthorizationRequest($authRequest, $response);

        } catch (OAuthServerException $e) {
            $response = $e->generateHttpResponse($response);

        } catch (Exception $e) {
            $body = new Stream('php://temp', 'r+');
            $body->write($e->getMessage());
            $response = $response->withStatus(500)->withBody($body);
        }
        $this->sendResponse($response);
    }



    /**
     * @SWG\Post(
     *     path="/oauth2/access-token",
     *     operationId="accessToken",
     *     @SWG\Response(response="200", description="An access token"),
     *     tags={"auth"},
     *     @SWG\Parameter(
     *         name="grant_type",
     *         in="body",
     *         type="string",
     *         description="the type of grant",
     *         required=true,
     *         default="authorization_code",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Parameter(
     *         name="client_id",
     *         in="body",
     *         type="string",
     *         description="the client id",
     *         required=true,
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Parameter(
     *         name="client_secret",
     *         in="body",
     *         type="string",
     *         description="the client secret",
     *         required=true,
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Parameter(
     *         name="redirect_uri",
     *         in="body",
     *         type="string",
     *         description="with the same redirect URI the user was redirect back to",
     *         required=true,
     *         default="authorization_code",
     *         @SWG\Schema(type="string")
     *     ),
     *     @SWG\Parameter(
     *         name="code",
     *         in="body",
     *         type="string",
     *         description="with the authorization code from the query string",
     *         required=true,
     *         default="authorization_code",
     *         @SWG\Schema(type="string")
     *     ),
     * )
     */
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
}
