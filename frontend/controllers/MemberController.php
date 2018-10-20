<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\models\Member;
use frontend\models\SmsDemo;
use Yii;

class MemberController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    //>>注册会员
    public function actionRegist(){
        $model = new Member();
        $request = Yii::$app->request;
        //var_dump($request->isPost);exit;
        if($request->isPost){
            //注意:加载的时候第二个参数用空表示,因为传过来的表单不是用活动表单创建,传的值不是二维数组
            $model->load($request->post(),'');
            if($request->post('password') == $request->post('confirm_password')){
                $model->password = $request->post('password');
                $model->confirm_password = $request->post('confirm_password');
                //var_dump($model);exit;
                if($model->validate()){
                    $model->save(false);
                    Yii::$app->session->setFlash('success','会员注册成功');
                    return $this->redirect(['login']);
                }else{
                    return $model->getErrors();
                }
            }else{
                return $model->addError('confirm_password','两次密码输入不一致');
            }

        }
        return $this->renderPartial('regist',['model'=>$model]);
    }

    //>>登录
    public function actionLogin(){
        $model = new Member();
        $request = Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            $model->password = $request->post('password');
            if($request->post('remember')){
                $model->remember = 1;
            }
            $model->last_login_ip = $request->getUserIP();
            //var_dump($model);exit;
            if($model->login()){
                $model->tongBu();//登录成功后,同步cookie中的购物车数据到当前用户对应的购物车数据表
                Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['goods/index']);
            }
        }
        return $this->renderPartial('login',['model'=>$model]);
    }

    //>>注销
    public function actionLogout(){
        Yii::$app->user->logout();
        $this->redirect(['login']);
    }

    //>>修改
    public function actionEdit($id){
        $model = Member::findOne(['id'=>$id]);
    }

    //>>发短信
    public function actionSms(){
        //接收电话号码
        $phone = Yii::$app->request->post('phone');
        $code = rand(1000,9999);
        Yii::$app->session->set('code_'.$phone,$code);

        /*$demo = new SmsDemo(
            "LTAIgQIRelVvjUv6",  //AK
            "c5PPtwmOhXjutqMgs5mdCswEgaRJCP"   //SK
        );
        echo "SmsDemo::sendSms\n";
        $response = $demo->sendSms(
            "宏盛毛绒", // 短信签名
            "SMS_148380684", // 短信模板编号
            $phone, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code
                //"product"=>"dsd"
            )
        );
        if($response->Message == 'ok'){
            echo '发送成功';
        }else{
            echo '发送失败';
        }*/

        echo $code;
    }

    //>> ajax 验证用户唯一性
    public function actionValidateUser($username){
        //成功返回 'ture' 失败返回 'false' 字符串
        $user = Member::findOne(['username'=>$username]);
        if($user){
            return 'false';
        }
        return 'true';
    }

    //>> ajax 验证短信验证码
    public function actionValidateSms($phone,$captcha){
        //成功返回 'ture' 失败返回 'false' 字符串
        $code = Yii::$app->session->get('code_'.$phone);
        if($code == null || $code != $captcha){
            return 'false';
        }
        return 'true';
    }

    //>>测试redis
    public function actionRedis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        //写入数据
        $redis->set('student','张三');
        echo 'ok';
    }

    //>>测试登录
    public function actionMember(){
        var_dump(Yii::$app->user->identity);
    }
}
