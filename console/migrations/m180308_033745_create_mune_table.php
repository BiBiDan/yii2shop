<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mune`.
 */
class m180308_033745_create_mune_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('mune', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(30)->notNull()->comment('菜单名'),
            'parent_id'=>$this->integer()->notNull()->comment('上级菜单'),
            'url_to'=>$this->string(30)->notNull()->comment('地址(路由)'),
            'sort'=>$this->integer()->notNull()->comment('排序')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('mune');
    }
}
