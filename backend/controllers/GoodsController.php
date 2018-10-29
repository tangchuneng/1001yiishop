<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\Goods;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use yii\helpers\Url;
use yii\data\Pagination;
use backend\filters\RbacFilter;

class GoodsController extends \yii\web\Controller
{
    //public $enableCsrfValidation = false;
    //>>商品列表(分页展示)
    public function actionIndex()
    {
        //实例化一个查询器(简化写法)
        $query = Goods::find();

            //判断是否有搜索条件
            $data = \Yii::$app->request->get('GoodsSearchForm');
            //var_dump($data);exit;
            if(!empty($data['name'])){
                $query->andWhere(['like','name',$data['name']]);
            }
            if(!empty($data['sn'])){
                $query->andWhere(['like','sn',$data['sn']]);
            }
            if(!empty($data['minPrice'])){
                $query->andWhere(['>=','shop_price',$data['minPrice']]);
            }
            if(!empty($data['maxPrice'])){
                $query->andWhere(['<=','shop_price',$data['maxPrice']]);
            }

        //当前页码数(get参数)
        //实例化分页工具类(主要用来获取分页的数据)
        $pager = new Pagination([
            'totalCount' => $query->count(),//总共多少条
            'defaultPageSize' => 5,//每页多少条
        ]);
        //根据分页工具类获取数据
        //limit 0(offset偏移量),10(limit)
//        $models = $query->all();
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        //显示到页面
        return $this->render('index',['models'=>$models,'pager'=>$pager,'data'=>$data]);
    }

    //添加
    public function actionAdd(){
        $model = new Goods();//商品实例
        $category = new GoodsCategory();//分类实例
        $goods_intro = new GoodsIntro();//商品详情实例
        $goods_day_count = new GoodsDayCount();
        //$goods_gallery = new GoodsGallery();

        $request = \Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $goods_intro->load($request->post());
            //var_dump($model);exit;
            if($model->validate() && $goods_intro->validate()){
                $model->create_time = time();
                //记录每天添加的商品数
                if(GoodsDayCount::findOne(['day'=>date('Y-m-d')])){
                    $day = GoodsDayCount::findOne(['day'=>date('Y-m-d')]);
                    $day->count++;
                    $day->save();
                    $sn = date('Ymd').'000000' + $day->count;
                    //var_dump($sn);exit;
                    $model->sn = (string)$sn;
                }else{
                    $goods_day_count->day = date('Y-m-d');
                    $goods_day_count->count++;
                    $goods_day_count->save();
                    $sn = date('Ymd').'000001';
                    $model->sn = (string)$sn;
                }
                $model->save();

                //保存商品详情,只能放在模型保存过后
                $goods_intro->goods_id = $model->id;
                $goods_intro->save();
                //保存商品图片,只能放在模型保存过后
                /*$goods_gallery->goods_id = $model->id;
                $goods_gallery->path = $model->logo;
                $goods_gallery->save();*/

                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(Url::to(['goods/index']));
            }else{
                return $model->getErrors();
            }
        }
        return $this->render('add',[
            'model'=>$model,
            'category'=>$category,
            'goods_intro'=>$goods_intro,
            //'goods_gallery'=>$goods_gallery,
        ]);
    }

    //>>修改
    public function actionEdit($id){
        $model = goods::findOne($id);//商品实例
        $category = new GoodsCategory();//分类实例
        $goods_intro = GoodsIntro::findOne(['goods_id'=>$id]);//商品详情实例

        $request = \Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $goods_intro->load($request->post());
            //var_dump($model);exit;
            if($model->validate() && $goods_intro->validate()){
                $model->create_time = time();
                //记录每天添加的商品数
                /*if(GoodsDayCount::findOne(['day'=>date('Y-m-d')])){
                    $day = GoodsDayCount::findOne(['day'=>date('Y-m-d')]);
                    $day->count++;
                    $day->save();
                    $sn = date('Ymd').'000000' + $day->count;
                    //var_dump($sn);exit;
                    $model->sn = (string)$sn;
                }else{
                    $goods_day_count->day = date('Y-m-d');
                    $goods_day_count->count++;
                    $goods_day_count->save();
                    $sn = date('Y-m-d').'000001';
                    $model->sn = (string)$sn;
                }*/
                $model->save();

                //保存商品详情,只能放在模型保存过后
                $goods_intro->goods_id = $model->id;
                $goods_intro->save();
                //保存商品图片,只能放在模型保存过后
                $goods_gallery = GoodsGallery::findOne(['goods_id'=>$id]);
                if($goods_gallery){
                    $goods_gallery->goods_id = $model->id;
                    $goods_gallery->path = $model->logo;
                    $goods_gallery->save();

                }
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

    //>>删除
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = goods::findOne($id);
        //var_dump($model);exit;
        if($model){
            $model->is_on_sale = 0;
            $model->save();
            return 'success';
        }else{
            return 'fail';
        }
    }

    //>>预览相册
    public function actionGallery($id){
        $goods = Goods::findOne(['id'=>$id]);
        return $this->render('gallery',['goods'=>$goods]);
    }

    //>>Ajax删除相册图片
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        $model->delete();
    }

    //使用 Web Uploadify（AJAX上传）插件上传图片,上传成功后回显图片
    public function actions() {
        //接收ajax请求
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload/goods',
                'baseUrl' => '@web/upload/goods',
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

                //自定义文件命名格式
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();//获取文件扩展名
                    $filehash = date('Ymd',time()) . uniqid();//生成唯一字符串拼接时间戳并进行hash运算
                    //$p1 = substr($filehash, 0, 2);
                    //$p2 = substr($filehash, 2, 2);
                    //return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                    return 'logo_'.$filehash.'.'.$fileext;// logo + 日期 + 唯一字符串
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

                    //获取商品id
                    $goods_id = \Yii::$app->request->post('goods_id');
                    $gallery = new GoodsGallery();
                    $gallery->path = $action->getWebUrl();
                    $gallery->goods_id = $goods_id;
                    $gallery->save();
                    $action->output['imgId'] = $gallery->id;
                }
            ],
            //配置UEditor,文件上传相关配置
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yiishop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/goods/photo/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];
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
