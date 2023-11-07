<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-pegawai',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'pegawai\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-pegawai',
        ],
        'user' => [
            'identityClass' => 'common\models\StaffUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-pegawai', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the pegawai
            'name' => 'advanced-pegawai',
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
