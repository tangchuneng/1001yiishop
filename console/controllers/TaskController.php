<?php
namespace console\controllers;

use yii\console\Controller;
use frontend\models\Order;

class TaskController extends Controller{
    //>>手动更新订单状态.使用方法:在terminal中输入 Yii task/clean(路由) 调用
    public function actionClean(){
        //设置脚本的最大执行时间(不终止)
        set_time_limit(0);
        //超时未支付订单
        //当前时间 - 24小时 > 创建时间
        // sql : update order set status = 0 where status = 1 and (create_time<time()-24*3600);
        while(true){//死循环
            Order::updateAll(['status'=>0],'status = 1 and create_time<'.time()-24*3600);
            //每隔一秒执行一次
            sleep(1);
            //转码函数 iconv(); mb_convert_encoding();

            echo iconv('utf-8','gbk','订单更新成功'.date('Y-m-d H:m:s'."\n"));
        }
        //注意:php脚本的最大执行时间默认是30秒,可以在配置文件中改.所以在死循环执行30秒后就会报错
    }
}