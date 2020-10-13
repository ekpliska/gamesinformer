<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/game',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/sign-up',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/sign-in',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/user',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'POST /update' => 'update',
            'POST /change-password' => 'change-password',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/meta',
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
        'controller' => 'v3/favorite',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET add/<id:[\d-]+>' => 'add',
            'GET remove/<id:[\d-]+>' => 'remove',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/series',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/token-push',
        'pluralize' => false
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/news',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET view/<id:[\d-]+>' => 'view',
            'GET like/<id:[\d-]+>' => 'like',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/advertising',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/share',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET /get-share-types' => 'get-share-types',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/platform',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/genre',
        'pluralize' => true,
        'extraPatterns' => [
            'GET /' => 'index',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/comments',
        'pluralize' => false,
        'extraPatterns' => [
            'POST /create' => 'create',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v3/favorite-series',
        'pluralize' => false,
        'extraPatterns' => [
            'GET /' => 'index',
            'GET add/<id:[\d-]+>' => 'add',
            'GET remove/<id:[\d-]+>' => 'remove',
        ]
    ],
];
