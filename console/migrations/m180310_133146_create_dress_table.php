<?php

use yii\db\Migration;

/**
 * Handles the creation of table `dress`.
 */
class m180310_133146_create_dress_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dress', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull()->comment('用户id'),
            'name'=>$this->string()->notNull()->comment('收货人'),
            'province'=>$this->string()->notNull()->comment('省'),
            'city'=>$this->string()->notNull()->comment('城市'),
            'area'=>$this->string()->notNull()->comment('县'),
            'dress'=>$this->string()->notNull()->comment('地址'),
            'phone'=>$this->string()->notNull()->comment('手机号'),
            'status'=>$this->integer()->notNull()->comment('默认地址')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('dress');
    }
}
