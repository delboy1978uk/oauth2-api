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
        $request = $this->getRequest();
        die(var_dump($request));
    }

    public function emailAction()
    {
        $reg = $this->getViewEngine()->render('emails/user_registration', [
            'siteUrl' => $this->getServerEnvironment()->getSiteURL(),
            'activationLink' => '/user/activate/fhd@hgf.net/dhfdhfddhfdh',
        ]);
        echo $reg; exit;
    }
}
