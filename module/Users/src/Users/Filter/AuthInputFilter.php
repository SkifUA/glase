<?php


namespace Users\Filter;


use Zend\InputFilter\InputFilter;

class AuthInputFilter extends InputFilter
{
    const EMAIL_ADDRESS_INVALID = 'The input is not a valid email address. Use the basic format local-part@hostname';

    public function __construct()
    {
        $this->add([
            'name' => 'email',
            'required' => true,
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options'=>[
                        'messages'=>[
                            'emailAddressInvalid'         => self::EMAIL_ADDRESS_INVALID,
                            'emailAddressInvalidFormat'   => self::EMAIL_ADDRESS_INVALID,
                            'emailAddressInvalidHostname' => self::EMAIL_ADDRESS_INVALID,
                        ]
                    ]
                ]
            ],
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
        ]);

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

        $this->add([
            'name' => 'csrf',
            'required' => false,
        ]);

    }

}