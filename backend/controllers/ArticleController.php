<?php

namespace backend\controllers;

use app\models\Article_Category;

class ArticleController extends \yii\web\Controller
{
    public function actionCategoryIndex()
    {
        $categorys = Article_Category::find()->All();
        //var_dump($category);exit;
        return $this->render('categoryIndex',['categorys'=>$categorys]);
    }

    //添加文章分类
    public function actionAddCategory(){
        $model = new Article_Category();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //验证成功
                $model->save();
                $this->redirect(['article/category-index']);
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
        return $this->render('addCategory',['model'=>$model]);
    }
}
