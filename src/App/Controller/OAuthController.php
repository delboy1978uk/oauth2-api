<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateTime;

class OAuthController extends Controller
{
    public function pingAction()
    {
        $date = new DateTime();
        $this->sendJsonResponse(['pong' => $date->format('Y-m-d H:i:s')]);
    }
}
