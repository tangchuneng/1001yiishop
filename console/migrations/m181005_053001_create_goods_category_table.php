<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m181005_053001_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull(),//树id
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),//深度,层级
            'name' => $this->string()->notNull(),
            'parent_id' => $this->integer()->unsigned()->notNull()->comment('父分类id'),
            'intro' => $this->text()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
