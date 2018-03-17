<?php

namespace App\Form\User;

use Del\Form\AbstractForm;
use Del\Form\Field\Submit;
use Del\Form\Field\Text\EmailAddress;
use Del\Form\Field\Text\Password;
use Del\Form\Filter\Adapter\FilterAdapterZf;
use Del\Form\Renderer\HorizontalFormRenderer;
use Zend\Filter\StringToLower;

class RegistrationForm extends AbstractForm
{
    public function init()
    {
        $email = new EmailAddress('email');
        $email->setRequired(true)
            ->setAttribute('size', 40)
            ->setId('regemail')
            ->setLabel('Email')
            ->setCustomErrorMessage('You must input a valid email address.');

        $password = new Password('password');
        $password->setRequired(true)
            ->setClass('form-control password')
            ->setLabel('Password')
            ->setId('regpassword')
            ->setAttribute('size', 40)
            ->setAttribute('placeholder', 'Enter a password')
            ->setCustomErrorMessage('You must input a password.');

        $confirm = new Password('confirm');
        $confirm->setRequired(true)
            ->setLabel('Confirm Password')
            ->setAttribute('size', 40)
            ->setAttribute('placeholder', 'Retype your password')
            ->setCustomErrorMessage('You must retype your password.');

        $submit = new Submit('submit');
        $submit->setValue('Register');

        $stringToLower = new StringToLower();
        $email->addFilter(new FilterAdapterZf($stringToLower));

        $renderer = new HorizontalFormRenderer();

        $this->addField($email)
            ->addField($password)
            ->addField($confirm)
            ->addField($submit)
            ->setFormRenderer($renderer);
    }

}