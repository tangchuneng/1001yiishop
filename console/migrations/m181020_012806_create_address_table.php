<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m181020_012806_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->unsigned()->notNull()->comment('用户id'),
            'name' => $this->string(50)->notNull()->comment('收货人'),
            'province' => $this->string(20)->notNull()->comment('省'),
            'city' => $this->string(20)->notNull()->comment('市'),
            'area' => $this->string(20)->notNull()->comment('县'),
            'address' => $this->string(255)->notNull()->comment('详细地址'),
            'tel' => $this->string(11)->notNull()->comment('电话'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
