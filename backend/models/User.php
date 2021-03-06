<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property integer $last_login_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;//铭文密码
    public $captcha;
    public $remember;
    //常量定义场景
    const SCENARIO_ADD = 'add';
    const SCENARIO_EdIT = 'edit';
    const SCENARIO_LOGIN = 'login';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email','status','password',/*'captcha'*/], 'required'],
            //on 指定场景,该规则只在该场景下生效
            ['password','required','on'=>[self::SCENARIO_ADD,self::SCENARIO_LOGIN]],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password'], 'string'],
            [['username'], 'unique'],
            [['email','username'], 'unique'],
            [['password_reset_token'], 'unique'],
            //验证验证码,必须加上验证的场景
            ['captcha','captcha','captchaAction'=>'user/captcha','on'=>self::SCENARIO_LOGIN],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'captcha' => '验证码',
            'remember' => '自动登录',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录IP',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;//如果需要自动登录就必须重写该方法并将用户数据中的auth_key返回
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;//如果比对成功则自动登录成功
    }

    //保存之前要做的事
    public function beforeSave($insert)
    {
        //$insert , bool值 是否添加 : true 添加 false 修改
        if($insert){
            //添加前:密码加密  添加时间 auth_key
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $this->created_at = time();
            $this->auth_key = Yii::$app->security->generateRandomString();//生成一个随机字符串,用于自动登录
        }else{
            //修改前
            $this->updated_at = time();
            //判断是否有password传过来,如果有才 加密后 保存到password_hash字段
            if($this->password){
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $this->auth_key = Yii::$app->security->generateRandomString();//生成一个随机字符串,用于自动登录
            }
        }
        return parent::beforeSave($insert); //必须返回父类的方法,因为该方法要返回true,save()方法才会执行
    }

    //在模型中进行登录验证
    public function login(){
        $user = User::findOne(['username'=>$this->username]);
        //验证用户
        if($user){
            //用户存在,验证密码(调用Yii的security组件验证)
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //保存最后登录时间和IP
                $user->last_login_time = time();
                $user->last_login_ip = $this->last_login_ip;
                $user->save(false);
                //密码正确,登录用户
                if($this->remember){
                    //如果勾选了记住我就登录用户并保存7天
                    return Yii::$app->user->login($user,7*24*3600);
                }
                return \Yii::$app->user->login($user);
            }else{
                //密码不正确
                return $this->addError('password','密码不正确');
            }
        }
        //用户不存在
        return $this->addError('username','用户不存在');
    }

    //>>静态获取所有用户数据
    public static function getUsers(){
        $users = User::find()->all();
        $items = [];
        foreach ($users as $user){
            $items[$user->id] = $user->username;
        }
        return $items;
    }

    //>>根据用户来获取顶级菜单和对应的子菜单
    public function getMenus(){
        //获取该用户所有的权限
        $permissions = Yii::$app->authManager->getPermissionsByUser(Yii::$app->user->getId());

        $children = [];//保存该有用户所拥有的子菜单
        //根据该用户的权限获取所有的二级菜单
        foreach ($permissions as $permission){
            if(Menu::findOne(['url'=>$permission->name])){
                $children[] = Menu::findOne(['url'=>$permission->name]);
            }
        }
        //var_dump($children);exit;

        //根据子菜单找到所有的父菜单
        $parent_id = [];
        foreach ($children as $child){
            $parent_id[] = $child->parent_id;
        }
        $parent_id = array_unique($parent_id);
        //var_dump($parent_id);exit;

        $menuItems = [];
        foreach ($parent_id as $parent){
            $items = [];
            $children = Menu::find()->where(['parent_id'=>$parent])->all();
            //var_dump($children);exit;
            foreach ($children as $child){
                //判断当前用户是否有该路由的权限,根据权限生成菜单
                if(Yii::$app->user->can($child->url)){
                    $items[] = ['label'=>$child->name,'url'=>[$child->url]];
                }
            }
            $menuItems[] = ['label'=>Menu::findOne(['id'=>$parent])->name,'items'=>$items];
        }
        return $menuItems;
    }
    //>>获取所有的顶级菜单和根据用户的权限来获取子菜单
    public function getMenuss(){
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        $menuItems = [];
        foreach ($menus as $menu){
            $items = [];
            $children = Menu::find()->where(['parent_id'=>$menu->id])->all();
            //var_dump($children);exit;
            foreach ($children as $child){
                //判断当前用户是否有该路由的权限,根据权限生成菜单
                if(Yii::$app->user->can($child->url)){
                    $items[] = ['label'=>$child->name,'url'=>[$child->url]];
                }
            }
            $menuItems[] = ['label'=>$menu->name,'items'=>$items];
        }
        return $menuItems;
    }
}
