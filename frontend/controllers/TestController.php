<?php
namespace frontend\controllers;
use yii\helpers\Url;
use yii\web\Controller;

class TestController extends Controller{
    public function actionTest(){
        echo '<pre/>';
        var_dump(Url::base(true));
        var_dump(Url::canonical());
        var_dump(Url::home(true));
        var_dump(Url::to(['goods/details','id'=>6]));
    }
}