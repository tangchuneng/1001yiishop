<?php
namespace frontend\controllers;
use frontend\models\Address;
use frontend\models\Locations;
use frontend\models\Member;
use Yii;
class MemberController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    //>>注册会员
    public function actionRegist(){
        $model = new Member();
        $request = Yii::$app->request;
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

    //>>收货地址页面
    public function actionAddress(){
        $addresses = Address::find()->where(['member_id'=>Yii::$app->user->id])->all();

        return $this->renderPartial('address',['addresses'=>$addresses]);
    }

    //>>添加收货地址
    public function actionAddAddress(){
        $request = Yii::$app->request;
        if($request->isPost){
            $model = new Address();
            $model->load($request->post(),'');

            $model->member_id = Yii::$app->user->id;
            $model->province = Locations::findOne(['id'=>$request->post('province')])->name;
            $model->city = Locations::findOne(['id'=>$request->post('city')])->name;
            $model->area = Locations::findOne(['id'=>$request->post('area')])->name;
            //var_dump($model);exit;
            if($model->validate()) {
                $model->save();
                return $this->redirect(['address']);
            }else{
                return $model->getErrors();
            }
        }
        return false;
    }

    //>>删除收货地址
    public function actionDelAddress(){
        $id = Yii::$app->request->post('id');
        $address = Address::findOne(['id'=>$id]);
        if($address->delete()){
            echo 'success';
        }else{
            echo 'fail';
        }
    }

    //>>修改收货地址 : 还没做完
    public function actionEditAddress($id){
        $request = Yii::$app->request;
        $addresses = Address::find()->where(['member_id'=>Yii::$app->user->id])->all();
        $address_one = Address::findOne(['id'=>$id]);
        if($request->isPost){
            $model = new Address();
            $model->load($request->post(),'');

            $model->member_id = Yii::$app->user->id;
            $model->province = Locations::findOne(['id'=>$request->post('province')])->name;
            $model->city = Locations::findOne(['id'=>$request->post('city')])->name;
            $model->area = Locations::findOne(['id'=>$request->post('area')])->name;
            //var_dump($model);exit;
            if($model->validate()) {
                $model->save();
                return $this->redirect(['address']);
            }else{
                return $model->getErrors();
            }
        }
        return $this->renderPartial('address',['addresses'=>$addresses,'address_one'=>$address_one]);
    }

    //>>三级联动
    public function actionLocations($pid){
        $data = Locations::find()->where(['parent_id'=>$pid])->asArray()->all();
        echo json_encode($data);
    }

    //>>发短信
    public function actionSms(){
        /**
         * 短信验证码实际应该保存到redis,这里使用session来测试
         * 短信优化
         * 有效期:5-30分钟 使用redis的过期机制
         * 刷短信:限制发送频率,一个手机号码1分钟只能发送一条短信 一天只能发20条
         * 识别脚本:验证码(发送短信前需要先输入验证码)
         * 网络不好,用户一次收到多条短信:短时间内发送同样的验证码;给短信编号
         */
        //接收电话号码
        $phone = Yii::$app->request->post('phone');

        //发送前 : 判断是否能够发送短信
        //1 . 一个手机一分钟只能发送一条短信
        $time = Yii::$app->session->get('time_'.$phone);//上次发送短信的时间
        //如果有这个session 并且 当前事前时间减去上次发送的时间小于60秒,则判断为 '恶意刷短信'
        if($time && (time() - $time < 60)){
            //处理不能发送的情况
            echo '两次短信发送的间隔必须超过一分钟';exit;
        }
        //利用最后一次发送时间检查上一次发送短信的时间是否是今天
        if(date('Ymd',$time) < date('Ymd')){
            //最后一次不是今天发送的短信,说明短信发送的次数应该清零了
            Yii::$app->session->set('count_'.$phone,0);
        }

        //2 . 一天只能发20条
        $count = Yii::$app->session->get('count_'.$phone);//上次发送短信的次数
        if($count && ($count >= 20)){
            //处理不能发送的情况
            echo '今天发送次数已超过20次,请明天再试';exit;
        }

        $code = rand(1000,9999);
        Yii::$app->session->set('code_'.$phone,$code);//保存短信内容
        Yii::$app->session->set('time_'.$phone,time());//保存短信发送的时间
        Yii::$app->session->set('count_'.$phone,++$count);//保存短信发送的次数 这里使用了先运算再赋值的技巧,可以在变量不存在的情况下使用

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

    //>>Ajax获取登录状态
    public function actionUserStatus(){
        $user = Yii::$app->user->identity;
        if($user){
            $isLogin = true;
            $username = $user->username;
        }else{
            $isLogin = false;
            $username = '';
        }
        //var_dump($isLogin,$username);exit;
        return json_encode(['isLogin'=>$isLogin,'name'=>$username]);
    }

    //>>发送邮件
    public function actionEmail(){
        $result = Yii::$app->mailer->compose()
            ->setFrom('18780111552@163.com')//发件人
            ->setTo('18780111552@163.com')//收件人
            ->setSubject('京西商城测试')//主题
            ->setHtmlBody(
                '为了方便您使用邮箱，建议您安装 邮箱大师 ，不仅能随时随地收发邮件，还有最快的新邮件免费提醒等功能哦！'
            )//内容
            ->send();//发送
        var_dump($result);
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
