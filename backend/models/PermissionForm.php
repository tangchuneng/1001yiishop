<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 下午 6:45
 */
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model{
    public $name;//权限名称
    public $description;//权限描述

    public function rules()
    {
        return [
            [['name','description'],'required'],
            //自定义验证规则
            ['name','validateName'],
        ];
    }
    //>>字段标签
    public function attributeLabels()
    {
        return [
          'name'=>'权限名称',
          'description'=>'权限描述',
        ];
    }

    //>>自定义验证规则
    public function validateName(){
        //只关心问题
        if(\Yii::$app->authManager->getPermission($this->name)){
            $this->addError('name','该权限已存在');
        }
    }

}