<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use backend\models\Gooods;
//use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use yii\helpers\Url;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    //添加
    public function actionAdd(){
        $model = new Gooods();//商品实例
        $category = new GoodsCategory();//分类实例
        $goods_intro = new GoodsIntro();//商品详情实例

        $request = \Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if($model->validate()){
                $model->create_time = time();
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(Url::to(['goods/index']));
            }else{
                return $model->getErrors();
            }
        }
        return $this->render('add',[
            'model'=>$model,
            'category'=>$category,
            'goods_intro'=>$goods_intro
        ]);
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
                    'extensions' => ['jpg', 'png','gif'],
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

                //配置UEditor,文件上传相关配置
                /*'upload' => [
                    'class' => 'kucha\ueditor\UEditorAction',
                    'config' => [
                        "imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                        "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                        "imageRoot" => \Yii::getAlias("@webroot"),
                    ],
                ]*/
            ],
        ];
    }
}
