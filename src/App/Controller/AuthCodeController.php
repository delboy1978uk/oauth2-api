<?php

namespace App\Controller;

use DateInterval;
use Del\Common\ContainerService;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use OAuth\OAuthUser;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class AuthCodeController extends OAuthController
{
    /**
     * @throws Exception
     */
    public function init()
    {
        parent::init();
        $container = ContainerService::getInstance()->getContainer();
        $authCodeRepository = $container['repository.AuthCode'];
        $refreshTokenRepository = $container['repository.RefreshToken'];
        $this->oauth2Server->enableGrantType(
            new ClientCredentialsGrant(),
            new DateInterval('PT1H')
        );
        $this->oauth2Server->enableGrantType(
            new AuthCodeGrant(
                $authCodeRepository,
                $refreshTokenRepository,
                new DateInterval('PT10M')
            ),
            new DateInterval('PT1H')
        );
        $refreshGrant = new RefreshTokenGrant($refreshTokenRepository);
        $refreshGrant->setRefreshTokenTTL(new DateInterval('PT1M'));
        $this->oauth2Server->enableGrantType(
            $refreshGrant,
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
     *         required=true,
     *         default="testclient"
     *     ),
     *     @SWG\Parameter(
     *         name="client_secret",
     *         in="query",
     *         type="string",
     *         description="the client identifier",
     *         required=false,
     *         default="testclient"
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
            $authRequest->setUser(new OAuthUser());
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

        $redirectUri = $response->getHeader('Location');
        if (!empty($redirectUri)) {
            if (\substr($redirectUri[0], 0, 1) == '?') {
                $uri = \str_replace('?', '', $redirectUri[0]);
                \parse_str($uri, $vars);
                $this->sendJsonResponse($vars);
            }
        } else {
            $this->sendResponse($response);
        }

        return $response;
    }



    /**
     * @SWG\Post(
     *     path="/oauth2/access-token",
     *     operationId="accessToken",
     *     @SWG\Response(response="200", description="An access token"),
     *     tags={"auth"},
     *     @SWG\Parameter(
     *         name="grant_type",
     *         in="formData",
     *         type="string",
     *         description="the type of grant",
     *         required=true,
     *         default="client_credentials",
     *     ),
     *     @SWG\Parameter(
     *         name="client_id",
     *         in="formData",
     *         type="string",
     *         description="the client id",
     *         required=true,
     *         default="ceac682a9a4808bf910ad49134230e0e"
     *     ),
     *     @SWG\Parameter(
     *         name="client_secret",
     *         in="formData",
     *         type="string",
     *         description="the client secret",
     *         required=false,
     *         default="JDJ5JDEwJGNEd1J1VEdOY0YxS3QvL0pWQzMxay52"
     *     ),
     *     @SWG\Parameter(
     *         name="scope",
     *         in="formData",
     *         type="string",
     *         description="the scopes you wish to use",
     *         required=false,
     *         default="admin"
     *     ),
     *     @SWG\Parameter(
     *         name="redirect_uri",
     *         in="formData",
     *         type="string",
     *         description="with the same redirect URI the user was redirect back to",
     *         required=false,
     *     ),
     *     @SWG\Parameter(
     *         name="code",
     *         in="formData",
     *         type="string",
     *         description="with the authorization code from the query string",
     *         required=false,
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
        } catch (OAuthServerException $e) {
            $response = $e->generateHttpResponse($response);
        } catch (Exception $e) {
            $response = $response
                ->withStatus($e->getCode())
                ->withHeader('content-type', 'application/json; charset=UTF-8');
            $response->getBody()->write(\json_encode([
                'error' => $e->getCode(),
                'message' => $e->getMessage(),
            ]));
        }
        $this->sendResponse($response);
    }
}
