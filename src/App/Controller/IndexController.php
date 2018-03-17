<?php

namespace App\Controller;

use Bone\Exception;
use Bone\Mvc\Controller;
use DateTime;
use Swagger;

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

    public function apiAction()
    {
        $swagger = Swagger\scan(APPLICATION_PATH.'/src');
        header('Content-Type: application/json');
        echo $swagger;
        exit;
    }

    public function fakeClientCallbackAction()
    {
        $request = $this->getRequest();
        die(var_dump($request));
    }
}
