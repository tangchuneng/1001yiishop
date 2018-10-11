<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/11 0011
 * Time: 上午 10:05
 */
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;

    const SCENARIO_EDIT = 'edit';//定义场景常量
    const SCENARIO_ADD = 'add';//定义场景常量

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
            ['name','validateEditName','on'=>self::SCENARIO_EDIT],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'角色描述',
            'permissions'=>'权限分配',
        ];
    }
    //如果定义的场景在rules中没有配置,就需要通过scenarios()方法申明,否则会提示场景不存在
    /*public function scenarios()
    {
        return [
            self::SCENARIO_EDIT => [],//指定该场景下需要验证哪些字段(空数组表示所有字段)
        ];
    }*/

    //>>自定义验证方法1
    public function validateName(){    //只关心问题
        if(\Yii::$app->authManager->getRole($this->name)){
            $this->addError('name','该角色已存在');
        }
    }
    //>>自定义验证方法2
    public function validateEditName(){    //只关心问题
        //1.没改name的情况,不做任何处理

        //2.修改了name,但不能与已存在的name重复
        //怎样获取旧name?通过GET获取
        if(\Yii::$app->request->get('name') != $this->name){
            if(\Yii::$app->authManager->getRole($this->name)){
                $this->addError('name','该角色已存在');
            }
        }
    }

    //>>静态获取所有权限数据
    public static function getPermissionItem(){
        $permissions = \Yii::$app->authManager->getPermissions();
        $item = [];
        foreach ($permissions as $permission){
            $item[$permission->name] = $permission->description;
        }
        return $item;
    }
}