<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m180314_064653_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
            'payment_name'=>$this->string()->comment('配送方式名称'),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment');
    }
}
