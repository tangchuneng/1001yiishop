<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19 0019
 * Time: 下午 7:48
 */
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\HttpException;

class OrderController extends Controller{
    public $enableCsrfValidation = false;
    //>>生成订单
    public function actionOrder(){
        //判断必须是登录状态
        if(\Yii::$app->user->isGuest){
            //未登录就直接跳转到登录页面
            return $this->redirect(['member/login']);
        }

        $request = \Yii::$app->request;
        $model = new Order();
        //获取所有地址
        $addresses = Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        //获取所有购物车的数据
        $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        $goods_id = [];
        $amount = [];//保存购物车中商品和数量的关系
        foreach ($carts as $cart){
            $goods_id[] = $cart->goods_id;
            $amount[$cart->goods_id] = $cart->amount;
        }
        //根据购物车的数据找到对应的所有商品
        $goods = Goods::find()->where(['in','id',$goods_id])->all();

        if($request->isPost){
            //var_dump($request->post());exit;
            $model->load($request->post(),'');
            $address_id = $request->post('address_id');//获取地址的id
            $address = Address::findOne(['id'=>$address_id]);
            $delivery_id = $request->post('delivery_id');
            $payment_id = $request->post('payment_id');
            //赋值
            $model->member_id = \Yii::$app->user->id;//保存用户id
            $model->name = $address->name;
            $model->province = $address->province;
            $model->city = $address->city;
            $model->area = $address->area;
            $model->address = $address->address;
            $model->tel = $address->tel;
            $model->delivery_name = Order::$delivery[$delivery_id][0];
            $model->delivery_price = Order::$delivery[$delivery_id][1];
            $model->payment_name = Order::$payment[$payment_id][0];
            $model->status = 1;//待付款
            $model->trade_no = '第三方支付交易号,暂无..';
            $model->create_time = time();
            //保存

            //在操作mysql之前开启事务:保存之前
            $transaction = \Yii::$app->db->beginTransaction();//开启
            try{//尝试执行
                if($model->validate()){
                    $model->save(false);
                }else{
                    exit('验证失败!');
                }

                //订单商品详情表order_goods
                foreach ($goods as $good){
                    //检查库存
                    if($good->stock < $amount[$good->id]){
                        //库存不足,不能下单:抛出异常
                        throw new Exception($good->name.'&nbsp;:&nbsp;商品库存不足,不能下单.还剩余'.$good->stock.'件,请到购物车修改数量.');
                    }
                    //下单成功则扣减库存
                    $good->stock -= $amount[$good->id];
                    $good->save(false);

                    $order_goods = new OrderGoods();
                    $order_goods->order_id = $model->id;
                    $order_goods->goods_id = $good->id;
                    $order_goods->goods_name = $good->name;
                    $order_goods->logo = $good->logo;
                    $order_goods->price = $good->shop_price;
                    $order_goods->amount = $amount[$good->id];
                    $order_goods->total = $amount[$good->id] * $good->shop_price;
                    if($order_goods->validate()){
                        $order_goods->save();
                    }else{
                        exit('订单商品详情表验证失败!');
                    }

                    //无异常,提交事务
                    $transaction->commit();
                    //删除购物车的信息

                    //跳转到下单成功提示页
                    $this->redirect(['success']);
                }
            }catch (Exception $e){
                //捕获异常,不能下单,需要回滚
                $transaction->rollBack();
                echo $e->getMessage();
            }
        }
        //显示订单表单,分配数据
        return $this->renderPartial('flow2',[
            'model'=>$model,
            'addresses'=>$addresses,
            'goods'=>$goods,
            'amount'=>$amount,
        ]);
    }

    //>>订单提交成功
    public function actionSuccess(){
        return $this->renderPartial('flow3');
    }

    //>>订单列表
    public function actionIndex(){
        if(\Yii::$app->user->isGuest){
            exit('请先登录');
        }
        $user = \Yii::$app->user->identity;
        $models = Order::find()->where(['member_id'=>$user->getId()])->all();

        return $this->renderPartial('index',['models'=>$models]);
    }

    //>>判断订单状态
    public function actionOrderStatus($id){
        $order_one = Order::findOne(['id'=>$id]);
        switch($order_one){
            case 0:
                echo '已取消';
                break;
            case 1:
                echo '待付款';
                break;
            case 2:
                echo '待发货';
                break;
            case 3:
                echo '待收货';
                break;
            default:
                echo '完成';
        }
    }

    //>>微信支付
    public function actionPay($order_id){
        $model = Order::findOne(['id'=>$order_id,'status'=>1]);
        //判断订单是否存在
        if($model == null){
            throw new HttpException(404,'该订单不存在或以支付');
        }
        //存在
    }
}