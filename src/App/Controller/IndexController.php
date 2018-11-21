<?php

namespace App\Controller;

use DateTime;
use Swagger;
use Zend\Diactoros\Response;

/**
 * @SWG\Swagger(
 *     schemes={"https"},
 *     host="awesome.scot",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="BONE MVC API",
 *         description="This be a swashbucklin' API."
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="By delboy1978uk",
 *         url="https://github.com/delboy1978uk"
 *     ),
 *     @SWG\SecurityScheme(
 *         securityDefinition="client_credentials", type="oauth2", description="OAuth2 Client Credentials Grant", flow="client_credentials",
 *         authorizationUrl="https://awesome.scot/oauth2/authorize",
 *         tokenUrl="https://awesome.scot/oauth2/token",
 *         scopes={"admin": "Admin scope.", "test_scope": "Testing scope"}
 *     ),
 *     @SWG\SecurityScheme(
 *         securityDefinition="authorization_code", type="oauth2", description="OAuth2 Authorization Code Grant", flow="authorization_code",
 *         authorizationUrl="https://awesome.scot/oauth2/authorize",
 *         tokenUrl="https://awesome.scot/oauth2/token",
 *         scopes={"admin": "Admin scope.", "test_scope": "Testing scope"}
 *     )
 * )
 *
 */
class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->enableLayout();
        $this->enableView();
    }

    /**
     * Check basic connectivity. Returns a timestamp.
     * @SWG\Get(
     *     path="/ping",
     *     tags={"status"},
     *     @SWG\Response(response="200", description="Sends a response with the time")
     * )
     *
     */
    public function pingAction()
    {
        $date = new DateTime();
        $this->sendJsonResponse(['pong' => $date->format('Y-m-d H:i:s')]);
    }

    /**
     * @return Response
     */
    public function apiAction()
    {
        $swagger = Swagger\scan(APPLICATION_PATH.'/src')->__toString();
        $response = new Response();
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write($swagger);
        return $response;
    }

    public function fakeClientCallbackAction()
    {
        $this->sendJsonResponse($this->getParams());
    }

    /**
     * temporary development view for writing email templates
     * @return Response
     */
    public function emailAction()
    {
        $reg = $this->getViewEngine()->render('emails/user_registration/user_registration', [
            'siteUrl' => $this->getServerEnvironment()->getSiteURL(),
            'activationLink' => '/user/activate/fhd@hgf.net/dhfdhfddhfdh',
        ]);
        $response = new Response();
        $response->getBody()->write($reg);
        return $response;
    }
}
