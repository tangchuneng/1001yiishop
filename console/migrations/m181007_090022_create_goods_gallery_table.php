<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_gallery`.
 */
class m181007_090022_create_goods_gallery_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_gallery', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer()->unsigned()->notNull()->comment('商品id'),
            'path' => $this->string(255)->notNull()->comment('图片路径')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_gallery');
    }
}
