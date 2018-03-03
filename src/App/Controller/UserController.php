<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateTime;
use Del\Common\ContainerService;
use Del\Service\UserService;
use OAuth\User;

class UserController extends Controller
{
    /**
     * Fetch user details
     * @SWG\Get(
     *     path="/user/{id}",
     *     tags={"users"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="the type of response",
     *         required=false,
     *         default=1
     *     ),
     *     @SWG\Response(response="200", description="Sends user details")
     * )
     *
     */
    public function indexAction()
    {
        $id = $this->getParam('id');
        /** @var UserService $userSvc */
        $userSvc = ContainerService::getInstance()->getContainer()['service.user'];
        /** @var User $user */
        $user = $userSvc->findUserById($id);
        if (!$user) {
            $this->sendJsonResponse(['User not found']);
        }
        $this->sendJsonResponse(['email' => $user->getEmail()]);
    }

    /**
     * Register as a new user.
     * @SWG\Post(
     *     path="/user/register",
     *     tags={"users"},
     *     @SWG\Response(response="200", description="Registers a new unactivated user")
     * )
     *
     */
    public function registerAction()
    {
        $date = new DateTime();
        $this->sendJsonResponse(['hello' => $date->format('Y-m-d H:i:s')]);
    }
}
