<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    //添加
    public function actionAdd(){
        //实例化模型
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());

            //处理上传的文件,实例化上传对象
            $model->file = UploadedFile::getInstance($model,'file');
            //验证
            if($model->validate()){
                //移动文件
                $file_name = '/upload/'.uniqid().'.'.$model->file->getExtension();
                $model->file->saveAs(\Yii::getAlias('@webroot').$file_name,false);
                //将上传文件的路径赋值给logo字段
                $model->logo = $file_name;

                //>>注意<<//
                //save方法默认会再次执行验证 $model->validate(),
                //所以,如果有验证码,就需要在save方法中给一个false参数,避免重复验证导致数据添加失败
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','品牌添加成功!');
                $this->redirect(['brand/index']);
            }else{
                $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
