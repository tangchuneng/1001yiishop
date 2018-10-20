<?php

namespace backend\controllers;

use backend\models\Menu;
use backend\filters\RbacFilter;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Menu::find()->all();

        return $this->render('index',['model'=>$model]);
    }

    //>>添加菜单
    public function actionAdd(){
        $model = new Menu();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id){
        $model = Menu::findOne($id);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //>>删除菜单
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Menu::findOne(['id'=>$id]);
        if($model){
            if(Menu::find()->where(['parent_id'=>$id])->all()){
                return 'fail';
            }
            $model->delete();
            return 'success';
        }else{
            return 'fail';
        }
    }

    //>>添加过滤器:当前所有的操作都会经过这个过滤器来操作
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                //'except'=>['login','logout','captcha','error'],
            ]
        ];
    }
}
