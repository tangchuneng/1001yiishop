<?php

namespace backend\controllers;

use backend\models\User;

class UserController extends \yii\web\Controller
{
    //添加用户
    public function actionAdd(){
        $model = new User();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                /*
                实际在保存之前还有很多操作,这里把操作封装在模型里面.
                这里会自动调用模型中的 beforeSave() 方法
                为了更规范,更加体现 MVC 和 OOP 的思想
                 */
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['index']);
            }else{
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //用户列表
    public function actionIndex()
    {
        return $this->render('index');
    }

}
