<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m180226_103659_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'article_id' => $this->primaryKey(),
        /*            字段名	类型	注释
        article_id	primaryKey	文章id
        content	text	简介*/
            'content'=>$this->text()->comment('内容')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
