<?php

namespace backend\controllers;

use app\models\Article_Category;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    //添加文章分类
    public function actionAddCategory(){
        $model = new Article_Category();
        //var_dump($model);
        return $this->render('addCategory',['model'=>$model]);
    }
}
