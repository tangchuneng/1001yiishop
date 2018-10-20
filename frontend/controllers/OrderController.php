<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19 0019
 * Time: 下午 7:48
 */
namespace frontend\controllers;

use frontend\models\Order;
use yii\web\Controller;

class OrderController extends Controller
{
    //>>生成订单
    public function actionOrder(){
        //判断必须是登录状态
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }
        $request = \Yii::$app->request;
        $model = new Order();
        if($request->isPost){
            $model->load($request->post(),'');
            $address_id = \Yii::$app->request->post('address_id');
            $address = \Address::findOne(['address_id'=>$address_id,'member_id'=>\Yii::$app->user->id]);
            //地址信息赋值
            $model->name = $address->name;
            //....
            //配送方式赋值
            $model->delivery_name = Order::$delivery['delivery_name'][0];
        }
        //显示订单表单
        $this->renderPartial('flow2');
    }
}