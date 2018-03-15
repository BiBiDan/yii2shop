<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    //设置中文
    'language'=>'zh-CN',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    //关闭layout
    'layout'=>false,
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            //设置用户的主键
            'class'=>'\yii\web\User',
            'identityClass' => 'frontend\models\Member',
            'enableAutoLogin' => true,
            'loginUrl'=> ['member/login'],
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
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix'=>'.html',
            'rules' => [
            ],
        ],
        'msm' => [
            'class'=> 'frontend\aliyun\SmsHandler',
            'ak'=>'LTAIfdRnJs5DQDDX',
            'sk'=>'adNW5AtGWaMqvrwsYDDGBxLxXHsiKs',
            'sign'=>'爱豆乐宠',
            'template'=>'SMS_126890009'
        ]

    ],
    'params' => $params,
];
