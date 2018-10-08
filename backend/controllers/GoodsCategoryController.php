<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\helpers\Url;

class GoodsCategoryController extends \yii\web\Controller
{
    //分类列表
    public function actionIndex()
    {
        $model = GoodsCategory::find()->all();
        return $this->render('index',['categories'=>$model]);
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
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                    echo '操作成功';
                }else{
                    //添加顶级分类
                    $model->makeRoot();
                }

                //$model->save();
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(Url::to(['goods-category/index']));
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
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

    //删除
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsCategory::findOne(['id'=>$id]);
        //判断是否有着天数据
        if($model){
            //判断是否有子节点
            if($model->isLeaf()){  //判断是否是叶子节点(是否有子节点)
                //无子节点,直接删除
                $model->deleteWithChildren();
                return 'success';
            }else{
                //有子节点:在js中提示用户有子节点不能删除
                return 'fail';
            }
        }
    }

    //修改
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断添加顶级分类还是非顶级分类(子分类)
                if($model->parent_id){
                    //非顶级分类
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    //修改顶级分类,不改变层级
                    //判断旧的属性parent_id是否为0,有两种方法,方法一是最普通的方法
                    //方法一:查询数据表,获取旧的parent_id

                    //方法二:直接过去当前对象的旧属性
                    if($model->getOldAttribute('parent_id') == 0){
                        $model->save();//使用save方法是因为保存的是顶级分类,不会影响到数据表里面的左右值
                    }else{
                        $model->makeRoot();
                    }
                    //$model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(Url::to(['goods-category/index']));
            }else{
                //验证失败
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}
