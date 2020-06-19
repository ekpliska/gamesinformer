<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/game',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/sign-up',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/sign-in',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/user',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'POST /update' => 'update',
            'POST /change-password' => 'change-password',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/meta',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /platforms' => 'platforms',
            'GET /genres' => 'genres',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/favorite',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET add/<id:[\d-]+>' => 'add',
            'GET remove/<id:[\d-]+>' => 'remove',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/series',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/token-push',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v2/news',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET view/<id:[\d-]+>' => 'view',
        ]
    ],
];
