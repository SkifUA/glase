<?php
return [
    'AclResource' => [
        'Users\Controller\UsersController' => [
            'auth',
            'registration',
            'index',
        ],
        'Goods\Controller\GoodsController' => [
            'index',
            'products',
            'product'
        ],
        'Admin\Controller\GoodsAdminController' => [
            'products',
            'product',
            'edit-image',
            'create-product'
        ],
        'Admin\Controller\TypeAdminController' => [
            'types',
            'edit',
            'delete',
            'create-type'
        ],
        'Admin\Controller\UserAdminController' => [
            'users',
            'edit'
        ]
    ],
    'AclRole' => [
        \Application\Module::ACL_ROLE_GUEST => [
            'allow' => [
                'Users\Controller\UsersController' => [
                    'auth',
                    'registration',
                ],
                'Goods\Controller\GoodsController' => [

                ],
            ],
            'deny' => [

            ],
        ],

        \Application\Module::ACL_ROLE_USER => [
            'allow' => [
                'Users\Controller\UsersController' => [
                    'index',
                ],
                'Goods\Controller\GoodsController' => [
                    'index',
                    'product',
                    'products'
                ],
            ],
            'deny' => [

            ],
        ],

        \Application\Module::ACL_ROLE_ADMIN => [
            'allow' => [
                'Users\Controller\UsersController' => [

                ],
                'Goods\Controller\GoodsController' => [

                ],
                'Admin\Controller\GoodsAdminController' => [
                    'products',
                    'product',
                    'edit-image',
                    'create-product'
                ],
                'Admin\Controller\TypeAdminController' => [
                    'types',
                    'edit',
                    'delete',
                    'create-type'
                ],
                'Admin\Controller\UserAdminController' => [
                    'users',
                    'edit'
                ]
            ],
            'deny' => [

            ],
        ],

    ]
];