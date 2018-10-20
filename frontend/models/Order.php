<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/19 0019
 * Time: 下午 7:50
 */
namespace frontend\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    //>>保存配送方式
    public static $delivery = [
        1=>['顺丰快递',25,'速度快','价格高','服务好'],
        2=>['圆通快递',15,'速度一般','价格中等','服务将就'],
        3=>['EMS',22,'速度慢','价格高','服务勉强','全国各地都能到达'],
    ];
}