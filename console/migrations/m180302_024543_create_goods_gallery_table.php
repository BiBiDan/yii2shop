<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_gallery`.
 */
class m180302_024543_create_goods_gallery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_gallery', [
            'id' => $this->primaryKey(),

            /*            字段名	类型	注释
            id	primaryKey
            goods_id	int	商品id
            path	varchar(255)	图片地址*/
            'goods_id'=>$this->integer()->unsigned()->notNull()->comment('商品id'),
            'path'=>$this->string()->comment('图片地址')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_gallery');
    }
}
