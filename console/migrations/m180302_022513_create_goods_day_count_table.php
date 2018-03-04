<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_day_count`.
 */
class m180302_022513_create_goods_day_count_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_day_count', [

            /*            字段名	类型	注释
            day	date	日期
            count	int	商品数*/
            'day'=>$this->date()->comment('日期'),
            'count'=>$this->integer()->unsigned()->comment('商品数')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_day_count');
    }
}
