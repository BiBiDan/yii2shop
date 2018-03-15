<?php

use yii\db\Migration;

/**
 * Class m180314_104806_update_order_goods_table
 */
class m180314_104806_update_order_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('alter table `order_goods` engine=innodb');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180314_104806_update_order_goods_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180314_104806_update_order_goods_table cannot be reverted.\n";

        return false;
    }
    */
}
