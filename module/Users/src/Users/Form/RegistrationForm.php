<?php


namespace Users\Form;

use Users\Filter\RegistrationInputFilter;
use Zend\Form\Form;


class RegistrationForm extends Form
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
                'name' => 'firstName',
                'type' => 'text',
                'attributes' => [
                    'placeholder' => 'first Name',
                    'autocomplete' => 'off'
                ],
            ]
        );

        $this->add(
            [
                'name' => 'lastName',
                'type' => 'text',
                'attributes' => [
                    'placeholder' => 'last Name',
                    'autocomplete' => 'off'
                ],
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
                'name' => 'repeat_password',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => [
                    'placeholder' => 'repeat password',
                    'autocomplete' => 'off'
                ],
                'options' => [
                    'label' => 'Password'
                ]
            ]
        );

        $this->add(
            [
                'name' => 'submitRegistration',
                'type' => 'Submit',
                'attributes' => [
                    'value' => 'Registration',
                    'class' => 'btn btn-success btn-margin'
                ],
            ]
        );

        $this->setInputFilter(new RegistrationInputFilter);
    }

}