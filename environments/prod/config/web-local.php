<?php

return [
    'components' => [
        'mailer' => [
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '',
                'username' => '',
                'password' => '',
                'port' => '',
                'encryption' => 'tls',
            ],
        ],
        'request' => [
            'cookieValidationKey' => '',
        ],
        'view' => [
            'enableMinify' => true,
        ],
    ],
];
