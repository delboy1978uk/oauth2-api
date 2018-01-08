<?php

namespace App\Controller;

use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use OAuth\User;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class AuthCodeController extends OAuthController
{

    /**
     * @SWG\Get(
     *     path="/authorize",
     *     @SWG\Response(response="200", description="An access token")
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
            $body = new Stream('php://temp', 'r+');
            $body->write($exception->getMessage());
            $response = $response->withStatus(500)->withBody($body);
        }
        $this->sendResponse($response);
    }
}
