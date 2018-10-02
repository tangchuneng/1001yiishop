<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m181002_031358_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->comment('品牌名称')->notNull(),
            'intro' => $this->text()->comment('品牌简介'),
            'logo' => $this->string(255)->comment('logo图片'),
            'sort' => $this->integer()->comment('排序'),
            'is_delete' => $this->smallInteger(2)->comment('状态 0删除 1正常')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
