<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m180302_024225_create_goods_intro_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_intro', [
            /*            字段名	类型	注释
            goods_id	int	商品id
            content	text	商品描述*/
            'goods_id'=>$this->integer()->unsigned()->comment('商品id'),
            'content'=>$this->text()->comment('商品类容')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_intro');
    }
}
