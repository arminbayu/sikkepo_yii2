<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-apiv2',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'apiv2\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-apiv2',
        ],
        'user' => [
            'identityClass' => 'apiv2\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-apiv2', 'httpOnly' => true],
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the apiv2
            'name' => 'advanced-apiv2',
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
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    
                    $response->statusCode = 200;
                }
            },
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // 'urlManager' => [
        //     'enablePrettyUrl' => true,
        //     'enableStrictParsing' => true,
        //     'showScriptName' => false,
        //     'rules' => [
        //         'GET '=>'site/index',
        //         'POST auth/login'=>'auth/login'
        //     ],
        // ],
    ],
    'params' => $params,
];
