<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12 0012
 * Time: 下午 1:21
 */
namespace backend\models;

use yii\base\Model;

class UserForm extends Model{
    public $user;
    public $roles;

    public function rules()
    {
        return [
            [['user','roles'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user'=>'用户',
            'roles'=>'角色'
        ];
    }
}