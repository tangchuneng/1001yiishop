<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_day_count`.
 */
class m181007_085118_create_goods_day_count_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_day_count', [
            'id' => $this->primaryKey(),
            'day' => $this->date()->notNull()->comment('日期'),
            'count' => $this->integer()->unsigned()->comment('商品数'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_day_count');
    }
}
