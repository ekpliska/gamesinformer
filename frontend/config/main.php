<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'GameNotificator',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'frontend\models\SysUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'login' => 'site/login',
                'game/<id:[\d-]+>' => 'game/update',
                'game/remove/<id:[\d-]+>' => 'game/delete',
                'game/create' => 'game/index',
                'users/<id:[\d-]+>' => 'user/view',
                'series/<action>/<id:[\d-]+>' => 'series/<action>',
                'platform/<action>/<id:[\d-]+>' => 'platform/<action>',
                'genre/<action>/<id:[\d-]+>' => 'genre/<action>',
                'news/<action>/<id:[\d-]+>' => 'news/<action>',
                'rss/<action>/<id:[\d-]+>' => 'rss/<action>',
                'advertising/<action>/<id:[\d-]+>' => 'advertising/<action>',
                'users' => 'user/index',
                'series' => 'series/index',
                'platform' => 'platform/index',
                'genre' => 'genre/index',
                'news' => 'news/index',
                'advertising' => 'advertising/index',
            ],
        ],
    ],
    'params' => $params,
];
