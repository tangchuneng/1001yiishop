<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 下午 4:16
 */
namespace backend\models;

use yii\base\Model;

class PasswordForm extends Model{
    public $oldPassword;
    public $newPassword;
    public $rePassword;

    public function rules()
    {
        return [
            [['oldPassword','newPassword','rePassword'],'required'],
            ['rePassword','compare','compareAttribute'=>'newPassword','message'=>'两次密码不一致'],
            //自定义密码验证方法
            ['oldPassword','validatePassword']
        ];
    }

    //>>自定义验证方法,只考虑验证不通过的情况
    public function validatePassword(){
        //比对旧密码
        if(!\Yii::$app->security->validatePassword($this->oldPassword,\Yii::$app->user->identity->password_hash)){
            //只考虑验证不通过的情况,尽量简化代码
            $this->addError('oldPassword','旧密码错误');
        }
    }

    public function attributeLabels()
    {
        return [
            'oldPassword'=>'旧密码',
            'newPassword'=>'新密码',
            'rePassword'=>'确认密码',
        ];
    }
}