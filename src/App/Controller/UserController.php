<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use App\Form\User\ResetPasswordForm;
use Bone\Mvc\Registry;
use Bone\Service\MailService;
use Del\Common\ContainerService;
use Del\Exception\EmailLinkException;
use Del\Exception\UserException;
use Del\Service\UserService;
use Del\Value\User\State;
use Exception;
use Zend\Validator\EmailAddress;

/**
 * Class UserController
 * @package App\Controller
 * @todo get swagger updated with oauth 2 stuff to get header in
 */
class UserController extends BaseController
{
    /** @var UserService */
    private $userService;

    public function init()
    {
        parent::init();
        $c = ContainerService::getInstance()->getContainer();
        $this->userService = $c['service.user'];
    }

    /**
     * Fetch user details by ID.
     *
     * @OA\Get(
     *     path="/user/{id}",
     *     tags={"user"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="the type of response",
     *         required=false,
     *         default=1
     *     ),
     *     @OA\Response(response="200", description="Sends user details"),
     *     security={
     *      {"clientCredentials": {"admin"}}
     *     }
     * )
     *
     */
    public function indexAction()
    {
        if (!$this->httpMethodCheck('GET')) { return; }

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
     * @OA\Get(
     *     path="/{locale}/user/activate/{email}/{token}",
     *     tags={"user_registration"},
     *     @OA\Response(response="200", description="Registers a new unactivated user"),
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         type="string",
     *         description="the locale to use",
     *         required=true,
     *         default="en_GB"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the users email",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @OA\Parameter(
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
                case EmailLinkException::LINK_NO_MATCH:
                    $data = [
                        'success' => false,
                        'error' => $e->getMessage(),
                    ];
                    $code = 403;
                    break;
                case EmailLinkException::LINK_NOT_FOUND:
                    $data = [
                        'success' => false,
                        'error' => $e->getMessage(),
                    ];
                    $code = 404;
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
     * @OA\Get(
     *     path="/{locale}/user/activate/resend/{email}",
     *     tags={"user_registration"},
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         type="string",
     *         description="the locale to use",
     *         required=true,
     *         default="en_GB"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the email of the user registering",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @OA\Response(response="200", description="Sends email link details")
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

        $mail = $this->getMailService();
        $env = $this->getServerEnvironment();
        $email = $user->getEmail();
        $token = $link->getToken();

        $message = $this->getViewEngine()->render('emails/user_registration/user_registration', [
            'siteUrl' => $env->getSiteURL(),
            'activationLink' => '/' . $this->getParam('locale') . '/user/activate/' . $email . '/' . $token,
        ]);

        $mail->setFrom('noreply@' . $env->getServerName())
            ->setTo($user->getEmail())
            ->setSubject($this->getTranslator()
                    ->translate('email.user.register.thankswith') . ' ' . Registry::ahoy()->get('site')['name'])
            ->setMessage($message)
            ->send();


        $this->sendJsonObjectResponse($link);
    }

    /**
     * Get a lost password email link token.
     *
     * @OA\Get(
     *     path="/{locale}/user/lost-password/{email}",
     *     tags={"user_registration"},
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         type="string",
     *         description="the locale to use",
     *         required=true,
     *         default="en_GB"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the email of the user",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @OA\Response(response="200", description="Sends email link details")
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
     * @OA\Post(
     *     path="/{locale}/user/register",
     *     tags={"user_registration"},
     *     @OA\Response(response="200", description="Registers a new unactivated user"),
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         type="string",
     *         description="the locale to use",
     *         required=true,
     *         default="en_GB"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="the users email",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="a password for the user",
     *         required=true,
     *         default="password"
     *     ),
     *     @OA\Parameter(
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

            if ($form->isValid()) {
                try {
                    $data = $form->getValues();
                    $user = $this->userService->registerUser($data);
                    $link = $this->userService->generateEmailLink($user);
                    $mail = $this->getMailService();
                    $env = $this->getServerEnvironment();
                    $email = $user->getEmail();
                    $token = $link->getToken();

                    $message = $this->getViewEngine()->render('emails/user_registration/user_registration', [
                        'siteUrl' => $env->getSiteURL(),
                        'activationLink' => '/' . $this->getParam('locale') . '/user/activate/' . $email . '/' . $token,
                    ]);

                    $mail->setFrom('noreply@' . $env->getServerName())
                        ->setTo($user->getEmail())
                        ->setSubject($this->getTranslator()
                            ->translate('email.user.register.thankswith') . ' ' . Registry::ahoy()->get('site')['name'])
                        ->setMessage($message)
                        ->send();
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
            } else {
                throw new Exception('Invalid request data;', 400);
            }

        }
    }

    /**
     * Resets the users password. Requires an email link token.
     *
     * @OA\Post(
     *     path="/{locale}/user/reset-password/{email}/{token}",
     *     tags={"user_registration"},
     *     @OA\Response(response="200", description="Resets a users email"),
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         type="string",
     *         description="the locale to use",
     *         required=true,
     *         default="en_GB"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the email of the user",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         type="string",
     *         description="the email link token",
     *         required=true,
     *         default="r4nd0mT0k3n"
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="a password for the user",
     *         required=true,
     *         default="password"
     *     ),
     *     @OA\Parameter(
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
                $this->sendJsonResponse(['success' => 'Password successfully changed']);
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


    /**
     * @OA\Post(
     *     path="/{locale}/me/change-password/",
     *     tags={"user_profile"},
     *     @OA\Response(response="200", description="Changes a users password."),
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         type="string",
     *         description="the locale to use",
     *         required=true,
     *         default="en_GB"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the email of the user",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         type="formData",
     *         description="the email link token",
     *         required=true,
     *         default="r4nd0mT0k3n"
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="a password for the user",
     *         required=true,
     *         default="password"
     *     ),
     *     @OA\Parameter(
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
    public function changePassAction()
    {
//        $request = $this->getRequest();
//
//        $form = new Application_Form_ChangePass();
//
//        if ($request->isPost()) {
//
//            $data = $request->getPost();
//
//            if ($form->isValid($data)) {
//
//                $oldPassword = $form->getValue('oldPassword');
//                $password = $form->getValue('password');
//                $confirm = $form->getValue('confirm');
//
//                if ($password != $confirm) {
//
//                    $this->view->message = ['Your new passwords didn\'t match!','danger'];
//
//                } else {
//
//                    $user = $this->getUserService()->findUserById(Zend_Auth::getInstance()->getIdentity());
//
//                    if($this->getUserService()->checkPassword($user, $oldPassword)) {
//
//                        $this->getUserService()->changePassword($user, $password);
//                        $this->view->message = ['Password successfully updated. Click here to <a href ="/">return</a>.','success'];
//                        return;
//
//                    } else {
//
//                        $this->view->message = ['Your password was wrong!','danger'];
//
//                    }
//                }
//            }
//        }
//        $this->view->form = $form;
    }

    /**
     * @OA\Post(
     *     path="/{locale}/me/change-email/",
     *     tags={"user_profile"},
     *     @OA\Response(response="200", description="Changes a users email."),
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         type="string",
     *         description="the locale to use",
     *         required=true,
     *         default="en_GB"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="path",
     *         type="string",
     *         description="the email of the user",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         type="formData",
     *         description="the email link token",
     *         required=true,
     *         default="r4nd0mT0k3n"
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="a password for the user",
     *         required=true,
     *         default="password"
     *     ),
     *     @OA\Parameter(
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
    public function changeEmailAction()
    {
//        /* @var $request Zend_Controller_Request_Http */
//        $request = $this->getRequest();
//
//        $user = $this->getUserService()->findUserById(Zend_Auth::getInstance()->getIdentity());
//
//        $form = new Application_Form_ChangeEmail();
//
//        if ($request->isPost()) {
//
//            $data = $request->getPost();
//
//            if ($form->isValid($data)) {
//
//                $newEmail = $form->getValue('email');
//                $password = $form->getValue('password');
//
//                $existing = $this->getUserService()->findUserByEmail($newEmail);
//                if($existing) {
//
//                    $this->view->message = ['This email is already registered with a Cloud Tax Return account.','danger'];
//                    return;
//                }
//
//                if ($this->getUserService()->checkPassword($user, $password)) {
//
//                    $link = $this->getUserService()->generateEmailLink($user);
//
//                    try {
//
//                        $siteURL = SITE_URL;
//                        $currentEmail = $user->getEmail();
//                        $token = $link->getToken();
//
//                        $message =
//                            <<<END
//                            You have requested to change the login email for your Cloud Tax Return account to $newEmail. Click on the link below to confirm this change.
//                            You can ignore this email if you do not wish to change your address.<br /&nbsp;<br />
//<a href="$siteURL/reset-email/$currentEmail/$newEmail/$token">Switch to my new email address.</a>.
//END;
//
//                        /** @var Mail $emailService */
//                        $mail = new Mail();
//                        $mail->setFrom('noreply@cloudtaxreturn.co.uk')
//                            ->setTo($currentEmail)
//                            ->setSubject('Change your email address on Cloud Tax Return.')
//                            ->setHeader(Template::getHeader())
//                            ->setFooter(Template::getFooter())
//                            ->setMessage($message)
//                            ->send();
//
//                        $this->view->message = ['Please check your email for a link to activate your new address.','info'];
//                        $this->view->form = null;
//
//                    } catch (Exception $e) {
//                        $this->view->message = ['We were unable to send your e-mail confirmation. Please contact '.$this->config->email->support.'.','danger'];
//                    }
//
//                } else {
//                    $this->view->message = ['Your password was wrong','danger'];
//                }
//
//            }
//        }
//        $this->view->form = $form;
    }


    /**
     * @OA\Post(
     *     path="/{locale}/me/reset-email/",
     *     tags={"user_profile"},
     *     @OA\Response(response="200", description="Resets the users email."),
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         type="string",
     *         description="the locale to use",
     *         required=true,
     *         default="en_GB"
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="the email of the user",
     *         required=true,
     *         default="someone@email.com"
     *     ),
     *     @OA\Parameter(
     *         name="newemail",
     *         in="formData",
     *         type="string",
     *         description="password confirmation",
     *         required=true,
     *         default="password"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         type="string",
     *         description="the email link token",
     *         required=true,
     *         default="r4nd0mT0k3n"
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="a password for the user",
     *         required=true,
     *         default="password"
     *     ),
     * )
     * @throws Exception
     */
    public function resetEmailAction()
    {
//        $email = $this->_request->getParam('email');
//        $newEmail = $this->_request->getParam('newemail');
//        $token = $this->_request->getParam('token');
//
//        try {
//
//            $link = $this->getUserService()->findEmailLink($email, $token);
//            $user = $link->getUser();
//            $user->setEmail($newEmail);
//            $this->getUserService()->saveUser($user);
//            $this->getUserService()->deleteEmailLink($link);
//            $this->view->message = ['You have switched your email address. Please log in with '.$newEmail.' from now on.', 'success'];
//
//        } catch (EmailLinkException $e) {
//            $this->view->message = [$e->getMessage(), 'danger'];
//            return;
//        } catch (Exception $e) {
//            throw $e;
//        }
    }
}
