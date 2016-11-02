<?php

namespace Users\Form;


use Users\Filter\AuthInputFilter;
use Zend\Form\Form;

class AuthForm extends Form
{
    public function __construct(){

        parent::__construct();

        $this->setAttribute('method', 'post');


        $this->add(
            [
                'type' => 'Zend\Form\Element\Csrf',
                'name' => 'csrf',
            ]
        );

        $this->add(
            [
                'name' => 'email',
                'type' => 'Zend\Form\Element\Email',
                'attributes' => [
                    'placeholder' => 'email',
                    'autocomplete' => 'off'
                ],
                'options' => [
                    'label' => 'Email'
                ]
            ]
        );

        $this->add(
            [
                'name' => 'password',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => [
                    'placeholder' => 'password',
                    'autocomplete' => 'off'
                ],
                'options' => [
                    'label' => 'Password'
                ]
            ]
        );


        $this->add(
            [
                'name' => 'submitAuth',
                'type' => 'Submit',
                'attributes' => [
                    'value' => 'Authentication',
                    'class' => 'btn btn-success btn-margin'
                ],
            ]
        );

        $this->setInputFilter(new AuthInputFilter());
    }

}