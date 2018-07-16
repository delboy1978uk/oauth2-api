<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use Del\Common\ContainerService;
use Del\Exception\EmailLinkException;
use Del\Exception\UserException;
use Del\Service\UserService;
use Del\Value\User\State;
use Exception;

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
     * Fetch user details by ID.
     *
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

        $user = $userSvc->findUserById($id);
        if (!$user) {
            $this->sendJsonResponse(['User not found'], 404);
        }

        $this->sendJsonObjectResponse($user);
    }

    /**
     * Register as a new user. Gives an email link token.
     *
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
                $this->sendJsonObjectResponse($link);

            } catch (UserException $e) {

                switch ($e->getMessage()) {
                    case UserException::USER_EXISTS:
                    case UserException::WRONG_PASSWORD:
                        throw new Exception($e->getMessage(), 400);
                        break;
                }
                throw $e;
            }
        }
    }


    /**
     * Get a new activation email link.
     *
     * @SWG\Get(
     *     path="/user/activate/resend/{email}",
     *     tags={"users"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the email of the user registering",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @SWG\Response(response="200", description="Sends email link details")
     * )
     * @throws Exception
     */
    public function resendActivationAction()
    {
        $email = $this->getParam('email');

        $user = $this->userService->findUserByEmail($email);
        if (!$user) {
            $this->sendJsonResponse(['User not found'], 404);
            return;
        }

        if ($user->getState()->getValue() == State::STATE_ACTIVATED) {
            $this->sendJsonResponse(['error' => UserException::USER_ACTIVATED], 400);
            return;
        }

        $link = $this->userService->generateEmailLink($user);
        $this->sendJsonObjectResponse($link);
    }



    /**
     * Activate from the email link.
     *
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

        $userService = $this->userService;
        $this->view->success = false;

        try {

            $link = $userService->findEmailLink($email, $token);
            $user = $link->getUser();
            $user->setState(new State(State::STATE_ACTIVATED));
            $userService->saveUser($user);
            $userService->deleteEmailLink($link);
            $data = ['success' => true];
            $code = 200;

        } catch (EmailLinkException $e) {
            switch ($e->getMessage()) {
                case EmailLinkException::LINK_EXPIRED:
                    $data = [
                        'success' => false,
                        'error' => 'The activation link has expired. You can send a new activation <a href="/user/activate/resend/' . $email . '">here.</a>',
                    ];
                    $code = 403;
                    break;
                default:
                    $data = [
                        'success' => false,
                        'error' => $e->getMessage(),
                    ];
                    $code = 500;
                    break;
            }
        }

        $this->sendJsonResponse($data, $code);
    }
}
