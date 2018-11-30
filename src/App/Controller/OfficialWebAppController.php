<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use Bone\Mvc\Controller;

class OfficialWebAppController extends Controller
{
    public function indexAction()
    {

    }

    public function registerAction()
    {
        $form = new RegistrationForm('register');
        $this->view->form = $form;
    }

    /**
     *  Sample page using client_credentials grant to connect to the API
     */
    public function clientCredentialsExampleAction()
    {

    }

}
