<?php

namespace backend\controllers;

use backend\models\GoodsCategory;

class GoodsCategoryController extends \yii\web\Controller
{
    //分类列表
    public function actionIndex()
    {
        return $this->render('index');
    }

    //添加
    public function actionAdd(){
        $model = new GoodsCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //验证成功

                //判断是顶级分类还是非顶级分类(子分类)
                if($model->parent_id){
                    //添加子分类
                    $parent = GoodsCategory::findOne(['id'=>1]);
                    $model->prependTo($parent);
                    echo '操作成功';
                }else{
                    //添加顶级分类
                    $model->makeRoot();
                }

                //$model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['goods-category/index']);
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
        //转换成json数据格式再传过去
        return $this->render('add',['model'=>$model]);
    }

    //测试嵌套集合插件
    public function actionTest(){
        //创建1级分类
        /*$model = new GoodsCategory(['name' => '家用电器']);
        $model->parent_id = 0;
        $model->intro = '家电';
        $model->makeRoot();
        var_dump($model->getErrors());
        echo '创建顶级分类成功!';*/

        //创建子分类
        $parent = GoodsCategory::findOne(['id'=>1]);
        $child = new GoodsCategory(['name' => '大家电']);
        $child->parent_id = $parent->id;
        $child->prependTo($parent);
        echo '操作成功';
    }

    //测试 Ztree 插件
    public function actionZtree(){
        //$this->layout = false;//关闭默认的布局文件
        //获取分类数据:只获取指定的字段并转换成数组
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //var_dump($categories);exit;
        return $this->renderPartial('ztree',['categories'=>$categories]);
    }
}
