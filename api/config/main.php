<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$rules = array_merge(
    require(__DIR__ . '/rules-url-v1.php'),
    require(__DIR__ . '/rules-url-v2.php'),
    require(__DIR__ . '/rules-url-v3.php'),
    require(__DIR__ . '/rules-url-v4.php')
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
        ],
        'v2' => [
            'basePath' => '@app/modules/v2',
            'class' => 'api\modules\v2\Module'
        ],
        'v3' => [
            'basePath' => '@app/modules/v3',
            'class' => 'api\modules\v3\Module'
        ],
        'v4' => [
            'basePath' => '@app/modules/v4',
            'class' => 'api\modules\v4\Module'
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
            'rules' => $rules,
        ]
    ],
    'params' => $params,
];



