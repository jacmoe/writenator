<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Writenator',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Europe/Copenhagen',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'defaultRoute' => 'plan/index',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'T59ET_KVDzl6NuxnTmbOquyvQVr4iFTt',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
            'bundles' => [
                'yii\bootstrap4\BootstrapAsset' => false,
                'yii\bootstrap4\BootstrapPluginAsset' => false,
            ]
        ],
        'view' => [
            'class' => 'yii\web\View',
            'defaultExtension' => 'pug',
            'renderers' => [
                'pug' => [
                    'class' => 'Pug\\Yii\\ViewRenderer',
                    'cachePath' => '@runtime/pug/cache',
                    'options' => [
                        'pretty' => true,
                        'lifeTime' => 0,
                        'expressionLanguage' => 'php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];

return $config;
