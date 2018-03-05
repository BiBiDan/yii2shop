<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/4 0004
 * Time: 上午 11:33
 */

namespace backend\models;


use yii\base\Model;
use yii\web\Cookie;

class LoginForm extends Model
{
    //验证码
    public $code;
    //用户名
    public $username;
    //密码
    public $password;
    //记住我
    public $remember;
    //定义属性名
    public function attributeLabels()
    {
        return [
            'code'=>'验证码',
            'username'=>'用户名',
            'password'=>'密码',
            'remember'=>'记住我'
        ];
    }
    //定义规则
    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['remember','safe'],
            ['code','captcha','captchaAction'=>'admin/captcha']
        ];
    }
    //登录验证
    public function login(){
        //验证有没有这个用户名
        $admin = Admin::findOne(['username'=>$this->username]);
        if($admin){//如果用户名存在 继续向下验证密码
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                //密码成功后 最后登录时间更新
                $admin->last_login_time = time();
                $admin->last_login_ip = ip2long(\Yii::$app->request->userIP);
                //var_dump(\Yii::$app->user->isGuest);die;
                $admin->save();
                if($this->remember){
                    //如果用户勾选了remember 保存信息到cookie
                    $cookie = \Yii::$app->response->cookies;//可读写的cookie组件添加cookie信息
                    $cookies = new Cookie();//实例化cookie
                    $cookies->name = 'username';
                    $cookies->value = $this->username;
                    $cookies->expire = time()+(60*60*24);
                    $cookies->path = '/';
                    $cookie->add($cookies);

                    $cookiess = new Cookie();
                    $cookiess->name = 'password';
                    $cookiess->value = $this->password;
                    $cookiess->expire = time()+(60*60*24);
                    $cookiess->path = '/';
                    $cookie->add($cookiess);
                }
                //var_dump($cookie);die;
                //把用户信息保存进session
                return  \Yii::$app->user->login($admin);
            }else{

            }
        }else{
            $this->addError('password','用户名或密码错误');
        }
    }
}