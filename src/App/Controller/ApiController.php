<?php

namespace App\Controller;

use Bone\Mvc\Controller;
use DateTime;
use Del\Common\ContainerService;
use Del\Service\UserService;
use Doctrine\ORM\EntityManager;
use OAuth\Repository\UserRepository;
use OAuth\User;
use Psr\Http\Message\ServerRequestInterface;
use Swagger;

/**
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends Controller
{

    /**
     * Fetch user details
     * @SWG\Get(
     *     path="/api/users/{id}",
     *     tags={"users"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="the type of response",
     *         required=true,
     *         default=1
     *     ),
     *     @SWG\Response(response="200", description="Sends user details")
     * )
     *
     */
    public function usersAction()
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
}
