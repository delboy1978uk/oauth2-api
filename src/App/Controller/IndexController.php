<?php

namespace App\Controller;

use Bone\Mvc\Controller;
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
