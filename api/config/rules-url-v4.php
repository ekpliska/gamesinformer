<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/game',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET /spot-aaa-game' => 'spot-aaa-game',
            'GET like/<id:\d+>' => 'like',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/sign-up',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/sign-in',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/user',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'POST /update' => 'update',
            'POST /change-password' => 'change-password',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/meta',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /platforms' => 'platforms',
            'GET /genres' => 'genres',
            'GET /rss' => 'rss',
            'GET /rss-youtube' => 'rss-youtube',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/favorite',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET add/<id:[\d-]+>' => 'add',
            'GET remove/<id:[\d-]+>' => 'remove',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/series',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/token-push',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/news',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET view/<id:[\d-]+>' => 'view',
            'GET like/<id:[\d-]+>' => 'like',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/advertising',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/share',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET /get-share-types' => 'get-share-types',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/platform',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/genre',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/comments',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /create' => 'create',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v4/favorite-series',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET add/<id:[\d-]+>' => 'add',
            'GET remove/<id:[\d-]+>' => 'remove',
        ]
    ],
];
