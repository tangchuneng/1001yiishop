<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m181011_091910_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique()->comment('菜单名称'),
            'parent_id' => $this->integer()->unsigned()->notNull()->comment('父菜单ID'),
            'sort' => $this->integer()->notNull()->comment('排序'),
            'url' => $this->string(255)->notNull()->comment('路由'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
