<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m180309_030705_create_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),

            /*            字段名	类型	注释
            id	primaryKey
            username	varchar(50)	用户名
            auth_key	varchar(32)
            password_hash	varchar(100)	密码（密文）
            email	varchar(100)	邮箱
            tel	char(11)	电话
            last_login_time	int	最后登录时间
            last_login_ip	int	最后登录ip
            status	int(1)	状态（1正常，0删除）
            created_at	int	添加时间
            updated_at	int	修改时间*/
            'username'=>$this->string(50)->notNull()->comment('用户名'),
            'auth_key'=>$this->string(32)->notNull()->comment('自动登录钥匙'),
            'password_hash'=>$this->string(100)->notNull()->comment('密码'),
            'email'=>$this->string(100)->notNull()->comment('邮箱'),
            'tal'=>$this->char(11)->notNull()->comment('电话'),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'last_login_ip'=>$this->integer()->comment('最后登录ip'),
            'status'=>$this->integer()->comment('1正常,0删除'),
            'created_at'=>$this->integer()->comment('添加时间'),
            'updated_at'=>$this->integer()->comment('修改时间')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('member');
    }
}
