<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/9 0009
 * Time: 下午 3:02
 */

namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $remember;
    public $code;
    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['remember','safe'],
            ['code','captcha','captchaAction'=>'site/captcha']
        ];
    }
    public function login(){
        //根据传来的用户名判断有没有
        $member = Member::findOne(['username'=>$this->username]);
        if($member){
            //成功 验证密码
            if(\Yii::$app->security->validatePassword($this->password_hash,$member->password_hash)){
                //成功的话 添加用户的登录时间信息等
                $member->last_login_time = time();
                $member->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $member->save(0);
                //记住我选项的配置
                $duration = $this->remember?7*24*3600:0;
                //var_dump($duration);die;
                // 保存用户信息到session
                return \Yii::$app->user->login($member,$duration);
            }else{
                $this->addError('password','密码错误');
            }
        }else{
            $this->addError('username','用户名不正确');
        }
    }
    //同步购物车
    public function cart(){
        $cookies = \Yii::$app->request->cookies;
        $value = $cookies->getValue('cart');
        if($value){
            $cart = unserialize($value);
            $member_id = \Yii::$app->user->id;
            foreach ($cart as $goods_id=>$amount){
                $member_carts = Cart::find()->where(['goods_id'=>$goods_id])->all();
                if($member_carts){
                    foreach ($member_carts as $member_cart){
                        $member_cart->member = $member_id;
                        $member_cart->amount = $member_cart->amount+$amount;
                        $member_cart->save();
                    }
                }else{
                    $member_cart = new Cart();
                    $member_cart->member = $member_id;
                    $member_cart->goods_id = $goods_id;
                    $member_cart->amount = $amount;
                    $member_cart->save();
                }
            }
            $cookies = \Yii::$app->response->cookies;
            $cookies->remove('cart');
            //这里用户如果在cookie中买了东西需要结算了 我们就可以认为用户不需要再去购物 直接跳到购物车页面
            return true;
        }
    }

}