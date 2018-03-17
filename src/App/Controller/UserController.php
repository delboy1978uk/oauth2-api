<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use Del\Common\ContainerService;
use Del\Exception\UserException;
use Del\Service\UserService;
use Exception;
use OAuth\User;

class UserController extends BaseController
{
    /** @var UserService */
    private $userService;

    public function init()
    {
        $c = ContainerService::getInstance()->getContainer();
        $this->userService = $c['service.user'];
    }

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
     *     @SWG\Response(response="200", description="Registers a new unactivated user"),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="the users email",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="a password for the user",
     *         required=true,
     *         default="password"
     *     ),
     *     @SWG\Parameter(
     *         name="confirm",
     *         in="formData",
     *         type="string",
     *         description="password confirmation",
     *         required=true,
     *         default="password"
     *     )
     * )
     * @throws Exception
     */
    public function registerAction()
    {
        $form = new RegistrationForm('register');

        if ($this->getRequest()->getMethod() == 'POST') {

            $formData = $this->getRequest()->getParsedBody();
            $form->populate($formData);

            try {
                $data = $form->getValues();
                $user = $this->userService->registerUser($data);
                $link = $this->userService->generateEmailLink($user);
                $this->sendJsonResponse([
                    'user' => $this->userService->toArray($user),
                    'link' => [
                        'token' => $link->getToken(),
                        'expires' => $link->getExpiryDate()->format('Y-m-d H:i:s'),
                    ],
                ]);

            } catch (UserException $e) {

                switch ($e->getMessage()) {
                    case UserException::USER_EXISTS:
                        throw new Exception($e->getMessage(), 400);
                        break;
                    case UserException::WRONG_PASSWORD:

                        throw new Exception($e->getMessage(), 400);
                        break;
                }
                throw $e;
            }

            $form->populate($formData);
        }
    }



    /**
     * Activate from the email link.
     * @SWG\Get(
     *     path="/user/activate/{email}/{token}",
     *     tags={"users"},
     *     @SWG\Response(response="200", description="Registers a new unactivated user"),
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the users email",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @SWG\Parameter(
     *         name="token",
     *         in="path",
     *         type="string",
     *         description="the email link token",
     *         required=true,
     *         default="r4nd0mT0k3n"
     *     )
     * )
     * @throws Exception
     */
    public function activateAction()
    {
        $email = $this->getParam('email');
        $token = $this->getParam('token');

        $this->sendJsonResponse([
            'email' => $email,
            'token' => $token
        ]);
    }
}
