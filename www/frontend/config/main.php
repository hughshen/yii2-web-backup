<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'timeZone' => 'Asia/Shanghai',
    'modules' => [
        'api' => [
            'class' => 'frontend\modules\api\Module',
        ],
    ],
    'components' => [
        'request' => [
            // For .htaccess
            //'baseUrl' => '',
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
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
            // For .htaccess
            //'scriptUrl' => '/index.php',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
                [
                    'pattern' => 'sitemap',
                    'route' => 'site/sitemap',
                    'suffix' => '.xml',
                ],
                [
                    'pattern' => 'google\w+',
                    'route' => 'site/google-verification',
                    'suffix' => '.html',
                ],
                [
                    'pattern' => 'BingSiteAuth',
                    'route' => 'site/bing-auth',
                    'suffix' => '.xml',
                ],
                // Api modules
                [
                    'pattern' => '<module:api>/<controller>',
                    'route' => '<module>/<controller>/index',
                    'suffix' => '',
                ],
                [
                    'pattern' => '<module:api>/<controller>/<action>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => '',
                ],
                '/' => 'site/index',
                '<action:post|tool|category|tag>/<slug>' => 'site/<action>',
                '<action:error|search|categories|tags>' => 'site/<action>',
                '<slug>' => 'site/page',
            ],
        ],
    ],
    'params' => $params,
];
