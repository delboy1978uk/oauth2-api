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
     * @SWG\Get(
     *     path="/authorize",
     *     @SWG\Response(response="200", description="An access token"),
     *     tags={"auth"}
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

        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);

        } catch (Exception $exception) {
            die(var_dump($exception));
            $body = new Stream('php://temp', 'r+');
            $body->write($exception->getMessage());
            $response = $response->withStatus(500)->withBody($body);
        }
        $this->sendResponse($response);
    }
}
