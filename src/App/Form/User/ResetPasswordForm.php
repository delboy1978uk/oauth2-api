<?php

namespace App\Form\User;

use Del\Form\AbstractForm;
use Del\Form\Field\Submit;
use Del\Form\Field\Text\EmailAddress;
use Del\Form\Field\Text\Password;
use Del\Form\Filter\Adapter\FilterAdapterZf;
use Del\Form\Renderer\HorizontalFormRenderer;
use Zend\Filter\StringToLower;

class ResetPasswordForm extends AbstractForm
{
    public function init()
    {
        $password = new Password('password');
        $password->setRequired(true)
            ->setClass('form-control password')
            ->setLabel('Password')
            ->setId('password')
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

        $this->addField($password)
            ->addField($confirm)
            ->addField($submit);
    }

}