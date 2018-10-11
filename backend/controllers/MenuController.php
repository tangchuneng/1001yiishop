<?php

namespace backend\controllers;

use backend\models\Menu;

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
            }else{
                return $model->getErrors();
            }
        }

        return $this->render('add',['model'=>$model]);
    }
}
