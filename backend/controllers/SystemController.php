<?php
namespace backend\controllers;

use backend\models\Goods;
use frontend\models\Order;
use yii\web\Controller;
use backend\models\SphinxClient;

class SystemController extends Controller{
    //>>首页静态化的方法
    public function actionIndexStatic(){
        //1.获取ob缓存内容
        $data = $this->renderPartial('@frontend/views/goods/index.php');
        //var_dump($data);
        //2.保存到静态页面
        file_put_contents(\Yii::getAlias('@frontend/web/html/index.html'),$data);
        echo '首页静态化成功!';
    }

    //>>手动更新订单状态,这里只是测试.实际使用是用terminal调用Yii的console模块
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
            echo '订单更新成功'.date('Y-m-d H:m:s');
        }
        //注意:php脚本的最大执行时间默认是30秒,可以在配置文件中改.所以在死循环执行30秒后就会报错
    }

    //>>使用 Sphinx 商品搜索
    public function actionSearch($keyword){
        $cl = new SphinxClient ();
        $cl->SetServer ( '127.0.0.1', 9312);//sphinx的服务配置
        //$cl->SetServer ( '10.6.0.6', 9312);
        //$cl->SetServer ( '10.6.0.22', 9312);
        //$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );//超时时间
        $cl->SetArrayResult ( true );//结果的格式
        // $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);//设置匹配模式
        $cl->SetLimits(0, 1000);//设置返回结果范围
        $res = $cl->Query($keyword, 'goods');//shopstore_search
        //将查询出的结果遍历,取出里面的id
        $ids = [];
        if($res['matchs']){
            //查询到了
            foreach ($res['matchs'] as $match){
                $ids[] = $match['id'];
            }
        }
        //再根据id获取商品信息
        $model = Goods::find()->where(['in','id',$ids])->all();
        print_r($res);
    }
}