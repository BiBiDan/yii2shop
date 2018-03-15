<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m180313_091237_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'member'=>$this->integer()->notNull()->comment('用户id'),
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            'amount'=>$this->integer()->notNull()->comment('数量')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cart');
    }
}
