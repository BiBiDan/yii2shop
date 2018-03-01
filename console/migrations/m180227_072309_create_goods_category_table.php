<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m180227_072309_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),

        /*            字段名	类型	注释
        id	primaryKey
        tree	int()	树id
        lft	int()	左值
        rgt	int()	右值
        depth	int()	层级
        name	varchar(50)	名称
        parent_id	int()	上级分类id
        intro	text()	简介*/
            'tree'=>$this->integer()->unsigned()->notNull()->comment('树id'),
            'lft'=>$this->integer()->unsigned()->notNull()->comment('左值'),
            'rgt'=>$this->integer()->unsigned()->notNull()->comment('右值'),
            'depth'=>$this->integer()->unsigned()->notNull()->comment('层级'),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'parent_id'=>$this->integer()->unsigned()->notNull()->comment('上级分类id'),
            'intro'=>$this->text()->comment('简介')
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
