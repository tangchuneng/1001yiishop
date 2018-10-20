<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',

    //设置语言
    'language' => 'zh-CN',
    //设置布局文件(false表示关闭)
    //'layout'=>'diy_main',//在这里设置会影响到整个项目
    //修改默认路由
    'defaultRoute' => 'goods/index',//注:如果控制器后面是index,可以省略不写

    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'frontend\models\member',
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
        //地址美化,地址重写,伪静态
        'urlManager' => [
            'enablePrettyUrl' => true,//是否启用美化地址
            'showScriptName' => false,//是否显示脚本文件(index.php)
            //'suffix' => '.html',//设置伪静态后缀
            'rules' => [
            ],
        ],

    ],
    'params' => $params,
];
