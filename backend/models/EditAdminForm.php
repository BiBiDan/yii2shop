<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/4 0004
 * Time: 下午 8:32
 */

namespace backend\models;


use yii\base\Model;

class EditAdminForm extends Model
{
    //修改密码 定义三个属性 老 新 确认
    public $oldPwd;
    public $newPwd;
    public $rePwd;
    public function rules()
    {
        return [
            [['oldPwd','newPwd','rePwd'],'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'oldPwd'=>'旧的密码',
            'newPwd'=>'新的密码',
            'rePwd'=>'确认密码'
        ];
    }
    public function edit($password){
        //验证成功后进行比较 原始密码
//        var_dump($this->oldPwd);
//        var_dump($this->newPwd);
//        var_dump($this->rePwd);
//        die;
        $res = \Yii::$app->security->validatePassword($this->oldPwd,$password);
        //var_dump($res);die;
        if($res){
            //成功 比较新密码和确认的密码
            if($this->newPwd === $this->rePwd){
                //成功 保存新密码进入数据库
                  return $this->newPwd;
            }else{
                //失败 返回错误提示
                $this->addError('rePwd','两次输入不同');
            }
        }else{
            //失败 返回错误提示
            $this->addError('oldPwd','密码不对');
        }
    }
}