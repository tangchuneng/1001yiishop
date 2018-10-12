<?php

use yii\db\Migration;

/**
 * Handles the creation of table `articl_detail`.
 */
class m181012_143454_create_articl_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('articl_detail', [
            'article_id' => $this->primaryKey()->comment('文章id'),
            'content' => $this->text()->comment('内容'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('articl_detail');
    }
}
