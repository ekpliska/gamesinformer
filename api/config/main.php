<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'language' => 'ru',
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-admin',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
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
            ],        
        ]
    ],
    'params' => $params,
];



