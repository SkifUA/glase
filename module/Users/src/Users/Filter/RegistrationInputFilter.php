<?php


namespace Users\Filter;


use Zend\InputFilter\InputFilter;

class RegistrationInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add( [
            'name' => 'password',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 20,
                    ],
                ],
            ],
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]);

        $this->add( [
            'name' => 'repeat_password',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 20,
                    ],
                ],
            ],
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],

        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options'=>[
                        'messages'=>[
                            'emailAddressInvalid'         => AuthInputFilter::EMAIL_ADDRESS_INVALID,
                            'emailAddressInvalidFormat'   => AuthInputFilter::EMAIL_ADDRESS_INVALID,
                            'emailAddressInvalidHostname' => AuthInputFilter::EMAIL_ADDRESS_INVALID,
                        ]
                    ]
                ]
            ],
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]);

        $this->add([
            'name' => 'csrf',
            'required' => false,
        ]);

        $this->add([
            'name' => 'firstName',
            'required' => false,
        ]);

        $this->add([
            'name' => 'lastName',
            'required' => false,
        ]);
    }


}