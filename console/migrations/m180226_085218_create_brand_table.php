<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m180226_085218_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
        /*
        字段名	类型	注释
        id	primaryKey
        name	varchar(50)	名称
        intro	text	简介
        logo	varchar(255)	LOGO图片
        sort	int(11)	排序
        is_deleted	int(1)	状态(0正常 1删除)
        */
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'logo'=>$this->string()->notNull()->comment('LOGO'),
            'sort'=>$this->integer()->unsigned()->notNull()->comment('排序'),
            'is_delete'=>$this->smallInteger()->notNull()->comment('状态')
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
