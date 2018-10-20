<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;
    public $confirm_password;
    public $remember;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'tel'], 'required'],
            [['username','email','tel'],'unique'],
            [['last_login_time', 'last_login_ip', 'status', 'create_time', 'update_time'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
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
            'auth_key' => '自动登录密匙',
            'password_hash' => '密码(密文)',
            'email' => '邮箱',
            'tel' => '电话',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => '状态',
            'create_time' => '注册时间',
            'update_time' => '修改时间',
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

    //>>保存前要做的事
    public function beforeSave($insert)
    {
        //$insert , bool值 是否添加 : true 添加 false 修改
        if($insert){
            //添加前:密码加密  添加时间 auth_key
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $this->create_time = time();
            $this->auth_key = Yii::$app->security->generateRandomString();//生成一个随机字符串,用于自动登录
        }else{
            //修改前
            $this->update_time = time();
            //判断是否有password传过来,如果有才 加密后 保存到password_hash字段
            if($this->password){
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $this->auth_key = Yii::$app->security->generateRandomString();//生成一个随机字符串,用于自动登录
            }
        }
        return parent::beforeSave($insert); ////必须返回父类的方法,因为该方法要返回true,save()方法才会执行
    }

    //>>登录
    public function login(){
        $member = Member::findOne(['username'=>$this->username]);
        //验证用户
        if($member){
            //用户存在,验证密码(调用Yii的security组件验证)
            if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                //保存最后登录时间和IP
                $member->last_login_time = time();
                $member->last_login_ip = $this->last_login_ip;
                $member->save(false);
                //密码正确,登录用户
                if($this->remember){
                    //如果勾选了记住我就登录用户并保存7天
                    return Yii::$app->user->login($member,7*24*3600);
                }
                return \Yii::$app->user->login($member);
            }else{
                //密码不正确
                return $this->addError('password','密码不正确');
            }
        }
        //用户不存在
        return $this->addError('username','用户不存在');
    }

    //>>登录过后同步cookie中购物车的信息
    public function tongBu(){
        $cookies = Yii::$app->request->cookies;
        $value = $cookies->getValue('carts');
        //var_dump($value);exit;
        if($value){
            //1.取出cookie中的购物车数据
            $carts = unserialize($value);
            //var_dump($carts);exit;
            //2.遍历购物车数据
            foreach ($carts as $goods_id=>$amount){
                //判断数据表中是否有该商品的数据
                $cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
                if($cart){
                    //追加
                    $cart->amount += $amount;
                    $cart->save();
                }else{
                    //添加
                    $cart_model = new Cart();
                    $cart_model->goods_id = $goods_id;
                    $cart_model->amount = $amount;
                    $cart_model->member_id = Yii::$app->user->id;
                    $cart_model->save();
                }
            }
            //3.清除购物车cookie
            $cookies2 = Yii::$app->response->cookies;
            $cookies2->remove('carts');
        }
    }
}
