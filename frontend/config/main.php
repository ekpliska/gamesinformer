<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'GamePlay',
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
                'news/delete/<id:[\d-]+>/<type:[\d-]+>' => 'news/delete',
                'news/delete-all/<type_rss:[\d-]+>' => 'news/delete-all',
                'news/<action>/<id:[\d-]+>' => 'news/<action>',
                'rss/<action>/<id:[\d-]+>' => 'rss/<action>',
                'shares/<action>/<id:[\d-]+>' => 'shares/<action>',
                'advertising/<action>/<id:[\d-]+>' => 'advertising/<action>',
                'comments/<action>/<game_id:[\d-]+>' => 'comments/<action>',
                'comments/<action>/<id:[\d-]+>' => 'comments/<action>',
                'users' => 'users/index',
                'series' => 'series/index',
                'platform' => 'platform/index',
                'genre' => 'genre/index',
                'news' => 'news/index',
                'shares' => 'shares/index',
                'advertising' => 'advertising/index',
                'comments' => 'comments/index',
            ],
        ],
    ],
    'params' => $params,
];
