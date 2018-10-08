<?php

use yii\db\Migration;

/**
 * Handles the creation of table `gooods`.
 */
class m181007_085840_create_gooods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('gooods', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull()->comment('商品名称'),
            'sn' => $this->string(20)->notNull()->comment('货号'),
            'logo' => $this->string(255)->notNull()->comment('logo'),
            'goods_category_id' => $this->integer()->unsigned()->notNull()->comment('商品分类id'),
            'brand_id' => $this->integer()->unsigned()->notNull()->comment('品牌分类id'),
            'market_price' => $this->decimal(10,2)->comment('市场价'),
            'shop_price' => $this->decimal(10,2)->notNull()->comment('商品价格'),
            'stock' => $this->integer()->unsigned()->notNull()->comment('库存'),
            'is_on_sale' => $this->integer(1)->unsigned()->notNull()->defaultValue(1)->comment('是否在售 1:在售 0:下架'),
            'status' => $this->integer(1)->unsigned()->notNull()->defaultValue(1)->comment('状态 1:正常 0:回收'),
            'sort' => $this->integer()->unsigned()->notNull()->comment('排序'),
            'create_time' => $this->integer()->unsigned()->notNull()->comment('添加时间'),
            'view_times' => $this->integer()->unsigned()->notNull()->comment('浏览次数')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('gooods');
    }
}
