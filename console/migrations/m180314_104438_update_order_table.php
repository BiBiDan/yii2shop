<?php

use yii\db\Migration;

/**
 * Class m180314_104438_update_order_table
 */
class m180314_104438_update_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('alter table `order` engine=innodb');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180314_104438_update_order_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180314_104438_update_order_table cannot be reverted.\n";

        return false;
    }
    */
}
