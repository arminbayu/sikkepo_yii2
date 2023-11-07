<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-unit',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'unit\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-unit',
        ],
        'user' => [
            'identityClass' => 'common\models\Unit',
            'enableAutoLogin' => false,
            'authTimeout' => 3600,
            'identityCookie' => ['name' => '_identity-unit', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the unit
            'name' => 'advanced-unit',
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
