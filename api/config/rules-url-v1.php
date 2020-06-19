<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/game',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/sign-up',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/sign-in',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/user',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'POST /update' => 'update',
            'POST /change-password' => 'change-password',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/meta',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /platforms' => 'platforms',
            'GET /genres' => 'genres',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/favorite',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET add/<id:[\d-]+>' => 'add',
            'GET remove/<id:[\d-]+>' => 'remove',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/series',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/token-push',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/news',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET view/<id:[\d-]+>' => 'view',
        ]
    ],
];
