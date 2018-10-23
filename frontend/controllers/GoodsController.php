<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/18 0018
 * Time: 下午 12:16
 */
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsIntro;
use frontend\models\Cart;
use yii\data\Pagination;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller{
    public $enableCsrfValidation = false;

    //>>首页
    public function actionIndex()
    {
        //原始的渲染首页的方法
        $categories1 = GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->renderPartial('index2',['categories1'=>$categories1]);

        /**
         * 常规使用ob缓存的步骤
         * ob_start();//开启ob
         * echo '123456';//页面输出
         * $data = ob_get_contents();//获取ob缓存内容
         * file_put_contents('index.html',$data);//保存到静态页面
         */

        /**
         * 使用Yii的ob缓存机制:
         * 在后台的System控制器中
         * 调用 IndexStatic() 方法
         */

        //return $this->renderPartial('index');
    }

    //>>商品列表
    public function actionList($id){
        //根据传过类的分类id获取下面所有的商品
        $query = Goods::find();
        $category = GoodsCategory::findOne(['id'=>$id]);
        //判断
        if($category->depth == 2){//三级分类
            $query->andWhere(['goods_category_id'=>$id]);
        }elseif($category->depth == 1){//二级分类
            $ids = [];
            $children = GoodsCategory::find()->where(['parent_id'=>$category->id])->all();
            foreach ($children as $child){
                $ids[] = $child->id;
            }
            $query->andWhere(['in','goods_category_id',$ids]);//使用 in 条件
        }else{//一级分类
            //该写法实际一级和二级查询通用,跟上面的方法做一个对比
            $ids2 = $category->children()->select('id')->andWhere(['depth'=>2])->column();
            /**
             * andWhere 传的是 数组 数组 数组 数组 数组 数组 数组 数组 数组 数组 数组
             */
            $query->andWhere(['in','goods_category_id',$ids2]);
        }
        $pager = new Pagination([
            'totalCount' => $query->count(),//总共多少条
            'defaultPageSize' => 10,//每页多少条
        ]);
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->renderPartial('list',['models'=>$models]);
    }

    //>>商品详情
    public function actionDetails($id){
        $model = Goods::findOne($id);
        $goods_intro = GoodsIntro::findOne(['goods_id'=>$model->id]);
        return $this->renderPartial('details',[
            'model'=>$model,
            'goods_intro'=>$goods_intro,
        ]);
    }

    //>>添加到购物车
    public function actionAddtocart($goods_id,$amount){
        //未登录保存到cookie,已登录保存到数据表
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            //判断是否有该cookie
            if($value){
                //有数据
                $carts = unserialize($value);
            }else{
                //没数据
                $carts = [];
            }
            //判断是否存在当前需要添加的商品
            if(array_key_exists($goods_id,$carts)){
                //有就在原来的数据上累加
                $carts[$goods_id] += $amount;
            }else{
                //没有就直接写入
                $carts[$goods_id] = $amount;
            }

            //未登录 将购物车信息保存到cookie
            $cookies = \Yii::$app->response->cookies;//可写cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);//序列化后再保存,因为cookie的值不能是数组
            $cookie->expire = time()+60*60*24;//过期时间戳
            $cookies->add($cookie);
        }else{
            $id = \Yii::$app->user->id;
            $model = new Cart();
            //判断是否存在当前需要添加的商品
            $goods = Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>$id])->one();
            if($goods){
                //有就在原来的数据上累加
                $goods->amount += $amount;
                $goods->save(false);
            }else{
                //没有就直接写入
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->member_id = $id;
                $model->save(false);
            }
        }
        return $this->redirect(['cart']);
    }

    //>>购物车页面
    public function actionCart(){
        //获取购物车数据
        if(\Yii::$app->user->isGuest){
            //未登录 从cookie中取
            $cookies = \Yii::$app->request->cookies;
            $cart = unserialize($cookies->getValue('carts'));
            if($cart){
                $goods = Goods::find()->where(['in','id',array_keys($cart)])->all();
            }else{
                $goods = [];
            }
        }else{
            //已登录 从数据表取
            $id = \Yii::$app->user->id;
            $carts = Cart::find()->where(['member_id'=>$id])->all();
            //var_dump($carts);exit;
            $goods_id = [];
            $cart = [];//保存商品id与数量的对应关系
            foreach ($carts as $cart2){
                $goods_id[] = $cart2->goods_id;
                $cart[$cart2->goods_id] = $cart2->amount;
            }
            $goods = Goods::find()->where(['in','id',$goods_id])->all();
        }
        return $this->renderPartial('cart',['goods'=>$goods,'cart'=>$cart]);
    }

    //>>Ajax 请求修改购物车商品数量
    public function actionEditCart(){
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            //判断是否有该cookie
            if($value){
                //有数据
                $carts = unserialize($value);
            }else{
                //没数据
                $carts = [];
            }
            //判断是否存在当前需要添加的商品
            if(array_key_exists($goods_id,$carts)){
                //有就在原来的数据上累加
                $carts[$goods_id] = $amount;
            }
            //未登录 将购物车信息保存到cookie
            $cookies = \Yii::$app->response->cookies;//可写cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);//序列化后再保存,因为cookie的值不能是数组
            $cookie->expire = time()+60*60*24;//过期时间戳
            $cookies->add($cookie);
            echo 'ok';
        }else{
            $id = \Yii::$app->user->id;
            $cart = Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>$id])->one();
            if($cart){
                $cart->amount = $amount;
                $cart->save(false);
            }
        }
    }

    //>>删除购物车商品
    public function actionDelCart(){
        $id = \Yii::$app->request->post('id');
        if(\Yii::$app->user->isGuest){
            //未登录 从cookie中取
            $cookies = \Yii::$app->request->cookies;
            $value = unserialize($cookies->getValue('carts'));
            if($value){
                unset($value[$id]);

                $cookies2 = \Yii::$app->response->cookies;
                $cookie = new Cookie();
                $cookie->name = 'carts';
                $cookie->value = serialize($value);
                $cookie->expire = time()+3600*24;
                $cookies2->add($cookie);
                echo 'success';
            }else{
                echo 'fail';
            }
        }else{
            $cart = Cart::findOne(['goods_id'=>$id,'member_id'=>\Yii::$app->user->id]);
            if($cart->delete()){
                echo 'success';
            }else{
                echo 'fail';
            }
        }
    }
}