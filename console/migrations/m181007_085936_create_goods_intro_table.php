<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m181007_085936_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer()->unsigned()->notNull()->comment('商品id'),
            'content' => $this->text()->notNull()->comment('商品介绍'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
