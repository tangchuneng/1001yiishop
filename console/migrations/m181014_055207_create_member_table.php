<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m181014_055207_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->comment('自动登录密匙'),
            'password_hash' => $this->string(100)->notNull()->comment('密码(密文)'),
            'email' => $this->string(100)->notNull()->comment('邮箱'),
            'tel' => $this->string(11)->notNull()->comment('电话'),
            'last_login_time' => $this->integer()->unsigned()->notNull()->comment('最后登录时间'),
            'last_login_ip' => $this->integer()->unsigned()->notNull()->comment('最后登录ip'),
            'status' => $this->integer()->notNull()->comment('状态'),
            'create_time' => $this->integer()->unsigned()->comment('注册时间'),
            'update_time' => $this->integer()->unsigned()->comment('修改时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
