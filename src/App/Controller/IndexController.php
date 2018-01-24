<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateTime;
use Swagger;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="awesome.dev",
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
class IndexController extends Controller
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
        $this->disableLayout();
        $this->disableView();
        header('Content-Type: application/json');
        echo $swagger;
        exit;
    }
}
