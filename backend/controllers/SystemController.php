<?php
namespace backend\controllers;

use yii\web\Controller;

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
}