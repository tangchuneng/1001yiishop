<?php

namespace backend\controllers;

use app\models\Article_Category;
use yii\helpers\Url;

class ArticleController extends \yii\web\Controller
{
    //文章分类列表
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
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect([Url::to(['article/category-index'])]);
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
        return $this->render('addCategory',['model'=>$model]);
    }

    //修改分类
    public function actionEditCategory($id){
        $model = Article_Category::findOne($id);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //验证成功
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(Url::to(['article/category-index']));
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
        return $this->render('editCategory',['model'=>$model]);
    }
}
