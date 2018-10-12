<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\helpers\Url;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
    //添加:原始的方法
    public function actionAdd2(){
        //实例化模型
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost) {
            $model->load($request->post());
                //处理上传的文件
                $model->file = UploadedFile::getInstance($model,'file');
                //var_dump($model->file);exit;
                //验证
                if ($model->validate()) {
                    //移动文件
                    $file_name = '/upload/'.uniqid().'.'.$model->file->getExtension();
                    $model->file->saveAs(\Yii::getAlias('@webroot').$file_name,false);
                    //将上传文件的路径赋值给logo字段
                    $model->logo = $file_name;

                //>>注意<<//
                //save方法默认会再次执行验证 $model->validate(),
                //所以,如果有验证码,就需要在save方法中给一个false参数,避免重复验证导致数据添加失败
                $model->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success', '品牌添加成功!');
                return $this->redirect(Url::to(['brand/index']));
            } else {
                //var_dump($model->getErrors());exit;
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //添加:使用了 Ajax 上传的方法
    public function actionAdd(){
        //实例化模型
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost) {
            $model->load($request->post());
            //var_dump($model);exit;
            //验证
            if ($model->validate()) {
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '品牌添加成功!');
                return $this->redirect(Url::to(['brand/index']));
            } else {
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //列表
    public function actionIndex()
    {
        $models = Brand::find()->where(['!=','is_delete',-1])->all();
        return $this->render('index',['models'=>$models]);
    }

    //修改
    public function actionEdit($id){
        $model = Brand::findOne($id);
        //var_dump($model);exit;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //验证
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(Url::to(['brand/index']));
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Brand::findOne(['id'=>$id]);
        if($model){
            $model->is_delete = -1;
            $model->save();
            return 'success';
        }
        return 'fail';
    }

    //使用 Web Uploadify（AJAX上传）插件上传图片,上传成功后回显图片
    public function actions() {
        //接收ajax请求
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, //默认true 跨站请求攻击验证
                'postFieldName' => 'Filedata', // default

                //>>格式化文件名1
                //BEGIN METHOD
                /*'format' => [$this, 'methodName'],*/
                //END METHOD

                //>>格式化文件名2
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH

                //格式化文件名3
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME

                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();//输出图片路径(一般给前端使用)
                    //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    //$action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    //$action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }

    //将文件上传到七牛云
    /**
     * 以下数据根据七牛云得来,注册申请对象存储的功能即可
     * 存储空间名称:1001yiishop
     * 域名:pg3snxkbt.bkt.clouddn.com
     * AK : zeruqjEP3mikewDb0OXvMn7qPvuQzyxqMIoWBzBE
     * SK : Tv494xqV9Xe4zqXmKqrpovqlRfaTrR78iqP_Jt3y
     */
    //测试七牛云:随便上传一张图片上七牛云试试
    public function actionQiniu(){
        $config = [
            'accessKey'=>'zeruqjEP3mikewDb0OXvMn7qPvuQzyxqMIoWBzBE',
            'secretKey'=>'Tv494xqV9Xe4zqXmKqrpovqlRfaTrR78iqP_Jt3y',
            'domain'=>'http://pg3snxkbt.bkt.clouddn.com/',
            'bucket'=>'1001yiishop',
            'area'=>Qiniu::AREA_HUADONG //华东
        ];

        $qiniu = new Qiniu($config);
        $key = '1.jpg';
        //上传文件到七牛云,同时指定一个 key(文件名)
        $file = \Yii::getAlias('@webroot/upload/1.jpg');
        $qiniu->uploadFile($file,$key);
        //获取七牛云上文件的url地址
        $url = $qiniu->getLink($key);
        var_dump($url);
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
