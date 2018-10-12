<?php

namespace backend\controllers;

use app\models\ArticleCategory;
use yii\helpers\Url;
use backend\filters\RbacFilter;
class ArticleCategoryController extends \yii\web\Controller
{
    //>>列表
    public function actionIndex()
    {
        $categories = ArticleCategory::find()->where(['is_delete'=>1])->all();
        //var_dump($categories);exit;

        return $this->render('index',['categories'=>$categories]);
    }

    //>>添加
    public function actionAdd(){
        $model = new ArticleCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //验证成功
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect([Url::to(['article-category/index'])]);
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //>>修改
    public function actionEdit($id){
        $model = ArticleCategory::findOne($id);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //验证成功
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(Url::to(['article-category/index']));
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //>>删除
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($model){
            $model->is_delete = 0;
            $model->save();
            return 'success';
        }
        return 'fail';
    }

    //>>添加过滤器:当前所有的操作都会经过这个过滤器来操作
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                //'except'=>['captcha','error'],//不经过过滤器的方法
            ]
        ];
    }
}
