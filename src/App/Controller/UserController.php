<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use App\Form\User\ResetPasswordForm;
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
        if (!$this->httpMethodCheck('POST')) { return; }

        $id = $this->getParam('id');

        /** @var UserService $userSvc */
        $userSvc = ContainerService::getInstance()->getContainer()['service.user'];

        $user = $userSvc->findUserById($id);
        if (!$user) {
            $this->sendJsonResponse(['error' => 'User not found'], 404);
            return;
        }

        $this->sendJsonObjectResponse($user);
    }



    /**
     * Activate from the email link token.
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
        if (!$this->httpMethodCheck('GET')) { return; }

        $email = $this->getParam('email');
        $token = $this->getParam('token');

        $userService = $this->userService;

        try {

            /** @todo  handle exceptions */
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


    /**
     * Refresh the activation email link token.
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
        if (!$this->httpMethodCheck('GET')) { return; }

        $email = $this->getParam('email');

        $user = $this->userService->findUserByEmail($email);
        if (!$user) {
            $this->sendJsonResponse(['error' => UserException::USER_NOT_FOUND], 404);
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
     * Get a lost password email link token.
     *
     * @SWG\Get(
     *     path="/user/lost-password/{email}",
     *     tags={"users"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the email of the user",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @SWG\Response(response="200", description="Sends email link details")
     * )
     * @throws Exception
     */
    public function lostPasswordAction()
    {
        if (!$this->httpMethodCheck('GET')) { return; }

        $email = $this->getParam('email');

        $user = $this->userService->findUserByEmail($email);
        if (!$user) {
            $this->sendJsonResponse(['error' => UserException::USER_NOT_FOUND], 404);
            return;
        }

        if ($user->getState()->getValue() == State::STATE_UNACTIVATED) {
            $this->sendJsonResponse(['error' => UserException::USER_UNACTIVATED], 400);
            return;
        }

        $link = $this->userService->generateEmailLink($user);
        $this->sendJsonObjectResponse($link);
    }

    /**
     * Register as a new user. Returns an email link token.
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
        if (!$this->httpMethodCheck('POST')) { return; }

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
     * Resets the users password. Requires an email link token.
     *
     * @SWG\Post(
     *     path="/user/reset-password/{email}/{token}",
     *     tags={"users"},
     *     @SWG\Response(response="200", description="Resets a users email"),
     *     @SWG\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the email of the user",
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
    public function resetPassAction()
    {
        if (!$this->httpMethodCheck('POST')) { return; }

        $email = $this->getParam('email');
        $token = $this->getParam('token');

        $user = $this->userService->findUserByEmail($email);
        if (!$user) {
            $this->sendJsonResponse(['error' => UserException::USER_NOT_FOUND], 404);
            return;
        }

        try {
            $link = $this->userService->findEmailLink($email, $token);
        } catch (EmailLinkException $e) {
            $code = $e->getMessage() == EmailLinkException::LINK_EXPIRED ? 400 : 404;
            $this->sendJsonResponse(['error' => $e->getMessage(), $code]);
            return;
        }

        $form = new ResetPasswordForm('reset-pass');

        $data = $this->getRequest()->getParsedBody();

        $form->populate($data);

        if ($form->isValid()) {

            if ($data['password'] == $data['confirm']) {
                $this->userService->changePassword($user, $data['password']);
                $this->userService->deleteEmailLink($link);
                $this->sendJsonResponse(['success' => 'Password successfully changed'], 200);
                return;
            } else {
                $this->sendJsonResponse(['error' => 'Passwords did not match, please try again.'], 400);
            }
        } else {
            $errors = [];
            $fields = $form->getFields();
            foreach ($fields as $field) {
                $validators = $field->getValidators();
                foreach ($validators as $validator) {
                    $errors[$field->getName()] = $validator->getMessages();
                }
            }
            $this->sendJsonResponse(['error' => $errors], 400);
        }
    }
}
