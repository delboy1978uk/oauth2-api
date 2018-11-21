<?php

namespace App\Controller;

use DateInterval;
use Del\Common\ContainerService;
use Del\Form\Field\Radio;
use Del\Form\Field\Submit;
use Del\Form\Form;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use OAuth\OAuthUser;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

class OAuthServerController extends BaseController
{
    /** @var AuthorizationServer $oauth2Server */
    protected $oauth2Server;

    /**
     * @throws \Exception
     */
    public function init()
    {
        $container = ContainerService::getInstance()->getContainer();
        $clientRepository = $container['repository.Client'];
        $accessTokenRepository = $container['repository.AccessToken'];
        $scopeRepository = $container['repository.Scope'];
        $authCodeRepository = $container['repository.AuthCode'];
        $refreshTokenRepository = $container['repository.RefreshToken'];

        // Setup the authorization server
        $server = new AuthorizationServer($clientRepository, $accessTokenRepository, $scopeRepository,
            'file://'.APPLICATION_PATH.'/data/keys/private.key',    // path to private key
            'file://'.APPLICATION_PATH.'/data/keys/public.key'      // path to public key
        );

        $this->oauth2Server = $server;

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
     * @OA\Get(
     *     path="/oauth2/authorize",
     *     @OA\Response(response="200", description="An access token"),
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="response_type",
     *         in="query",
     *         type="string",
     *         description="the type of response",
     *         required=true,
     *         default="code"
     *     ),
     *     @OA\Parameter(
     *         name="client_id",
     *         in="query",
     *         type="string",
     *         description="the client identifier",
     *         required=true,
     *         default="testclient"
     *     ),
     *     @OA\Parameter(
     *         name="client_secret",
     *         in="query",
     *         type="string",
     *         description="the client identifier",
     *         required=false,
     *         default="testclient"
     *     ),
     *     @OA\Parameter(
     *         name="redirect_uri",
     *         in="query",
     *         type="string",
     *         description="where to send the response",
     *         required=false
     *     ),
     *     @OA\Parameter(
     *         name="state",
     *         in="query",
     *         type="string",
     *         description="with a CSRF token. This parameter is optional but highly recommended.",
     *         required=false,
     *     ),
     *     @OA\Parameter(
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
     * @OA\Post(
     *     path="/oauth2/access-token",
     *     operationId="accessToken",
     *     @OA\Response(response="200", description="An access token"),
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="grant_type",
     *         in="formData",
     *         type="string",
     *         description="the type of grant",
     *         required=true,
     *         default="client_credentials",
     *     ),
     *     @OA\Parameter(
     *         name="client_id",
     *         in="formData",
     *         type="string",
     *         description="the client id",
     *         required=true,
     *         default="ceac682a9a4808bf910ad49134230e0e"
     *     ),
     *     @OA\Parameter(
     *         name="client_secret",
     *         in="formData",
     *         type="string",
     *         description="the client secret",
     *         required=false,
     *         default="JDJ5JDEwJGNEd1J1VEdOY0YxS3QvL0pWQzMxay52"
     *     ),
     *     @OA\Parameter(
     *         name="scope",
     *         in="formData",
     *         type="string",
     *         description="the scopes you wish to use",
     *         required=false,
     *         default="admin"
     *     ),
     *     @OA\Parameter(
     *         name="redirect_uri",
     *         in="formData",
     *         type="string",
     *         description="with the same redirect URI the user was redirect back to",
     *         required=false,
     *     ),
     *     @OA\Parameter(
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
            $code = $e->getCode() ?: 500;
            $response = $response
                ->withStatus($code)
                ->withHeader('content-type', 'application/json; charset=UTF-8');
            $response->getBody()->write(\json_encode([
                'error' => $code,
                'message' => $e->getMessage(),
            ]));
        }
        $this->sendResponse($response);
    }

    /**
     * @return Form
     */
    private function getForm()
    {
        $form = new Form('auth');
        $radio = new Radio('auth');
        $radio->setOptions([
            'yes' => 'Yes',
            'no' => 'No',
        ]);
        $radio->setLabel('Do you authorise TestClient?');
        $radio->setRenderInline(true);
        $radio->setRequired(true);
        $submit = new Submit('submit');

        $form->addField($radio)
            ->addField($submit);

        return $form;
    }
}
