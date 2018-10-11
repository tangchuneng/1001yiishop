<?php

namespace backend\controllers;

use backend\models\PasswordForm;
use backend\models\User;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class UserController extends \yii\web\Controller
{
    //public $enableCsrfValidation = false;//关闭Csrf验证
    //public $layout = false;//关闭布局文件
    //>>添加用户
    public function actionAdd(){
        //$this->layout = false;//关闭布局文件
        $model = new User();
        $model->scenario = User::SCENARIO_ADD;//指定当前方法的使用场景是SCENARIO_ADD场景.
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //var_dump($model);exit;
            if($model->validate()){

                /*
                实际在保存之前还有很多操作,这里把操作封装在模型里面.
                这里会自动调用模型中的 beforeSave() 方法
                为了更规范,更加体现 MVC 和 OOP 的思想
                 */

                //>>注意<<//
                //save方法默认会再次执行验证 $model->validate(),
                //所以,如果有验证码,就需要在save方法中给一个false参数,避免重复验证导致数据添加失败
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(Url::to(['user/index']));
            }else{
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //>>删除用户
    public function actionDel(){
        //ajax是通过post方式传值的,所以需要在post中获取id值
        $id = \Yii::$app->request->post('id');
        //根据id删除数据
        $model = User::findOne(['id'=>$id]);
        if($model->delete()){
            return 'success';
        }
        return 'fail';
    }

    //>>修改用户
    public function actionEdit($id){
        $model = User::findOne(['id'=>$id]);
        //判断一下是否有该用户,否则直接报错
        if($model == null){
            throw new NotFoundHttpException('用户不存在');//抛出一个异常:提示错误
        }
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                /*
                实际在保存之前还有很多操作,这里把操作封装在模型里面.
                这里会自动调用模型中的 beforeSave() 方法
                为了更规范,更加体现 MVC 和 OOP 的思想
                 */
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['index']);
            }else{
                return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //>>用户列表
    public function actionIndex()
    {
        $model = User::find()->all();

        return $this->render('index',['model'=>$model]);
    }

    //>>登录用户
    public function actionLogin(){
        //1.显示登录表单
        $model = new User();
        //2.实例化请求组件
        $request = \Yii::$app->request;
        //3.判断
        if($request->isPost){
            $model->load($request->post());
            //手动将是否记住我的选项赋值给$model->remember
            $model->remember = $_POST['User']['remember'];
            //验证
            if($model->login()){
                $model->last_login_time = time();
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['user/index']);
            }
        }
        //渲染视图
        return $this->render('login',['model'=>$model]);
    }
    //>>测试用户登录信息
    public function actionUser(){
        var_dump(\Yii::$app->user->identity);
    }
    //>>退出登录
    public function actionLogout(){
        \Yii::$app->user->logout();
        $this->redirect(['login']);
    }

    //>>修改密码
    public function actionPassword(){
        //先判断是否 登录
        if(\Yii::$app->user->isGuest){
            //没有登录就跳转到登录页面
            return $this->redirect(['user/login']);
        }
        $model = new PasswordForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //此处验证旧密码的方法也在模型中完成
                //更新密码
                $user = \Yii::$app->user->identity;
                $user->password = $model->newPassword;
                $user->save();

                //更改密码后应该退出当前用户重新登录
                $this->redirect(['logout']);
            }
        }
        return $this->render('password',['model'=>$model]);
    }

    //>>自定义规则
    public function actions()
    {
        return [
            //验证码规则,如果不设置默认使用SiteController的规则
            'captcha' => [
                'class'=>'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => '4',//最小长度
                'maxLength' => '4',//最大长度
                'backColor' => 0xFFFFFF,//背景色
            ]
        ];
    }
}