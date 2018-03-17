<?php

namespace App\Controller;

use App\Form\User\RegistrationForm;
use Bone\Mvc\Controller;

class FirstPartyWebsiteController extends Controller
{
    public function indexAction()
    {

    }
    public function registerAction()
    {
        $form = new RegistrationForm('register');
        $this->view->form = $form;
    }
}
