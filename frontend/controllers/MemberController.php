<?php

namespace frontend\controllers;

use frontend\models\Dress;
use frontend\models\LoginForm;
use frontend\models\Member;

class MemberController extends \yii\web\Controller
{
    public function actionIndex()
    {
        var_dump(\Yii::$app->user->isGuest);
    }
    //注册页面
    public function actionRegist(){

        $model = new Member();
        $request = \Yii::$app->request;
        if($request->isPost){
            //var_dump($_POST);die;
            $model->load($request->post(),'');
            if($model->validate()){
                $model->auth_key = \Yii::$app->security->generateRandomString();
                $model->status = 1;
                $model->created_at = time();
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(0);
                //var_dump($model->getErrors());die;
            }else{
                var_dump($model->getErrors());die;
            }
        }

        return $this->render('regist');
    }
    //异步验证用户名存在
    public function actionValidateName(){
        $request = \Yii::$app->request;
        if($request->isGet){
            $member = Member::findOne(['username'=>$request->get('username')]);
            if($member){
                return 'false';
            }else{
                return 'true';
            }
        }
    }
    //异步验证邮箱是否存在
    public function actionValidateEmail(){
        $request = \Yii::$app->request;
        if($request->isGet){
            $member = Member::findOne(['email'=>$request->get('email')]);
            if($member){
                return 'false';
            }else{
                return 'true';
            }
        }
    }
    //异步验证手机号正确
    public function actionValidateTel(){
        $request = \Yii::$app->request;
        if($request->isGet){
            $member = Member::findOne(['tal'=>$request->get('tal')]);
            if($member){
                return 'false';
            }else{
                return 'true';
            }
        }
    }
    //登录用户
    public function actionLogin(){
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            //var_dump($request->post());die;
            $model->load($request->post(),'');
            //echo '<pre>';
            //var_dump($model);die;
            if($model->validate()){
                //调用模型中的登录方法
                if($model->login()){
                    //登录成功后 把存在cookie中的购物车同步到到数据库
                    if($model->cart()){
                        //如果返回truecookie有值 跳转购物车
                        return $this->redirect(['goods/show-cart']);
                    }
                    return $this->redirect(['goods/index']);
                }else{
                }
            }else{
                var_dump($model->getErrors());die;
            }
        }

        return $this->render('login');
    }
    //用户退出
    public function actionLogout(){
        $cookie = \Yii::$app->response->cookies;
        $cookie->remove('username');
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }
    //展示用户收货地址
    public function actionDress(){
        $model = Dress::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        return $this->render('address',['model'=>$model]);
    }
    //用户添加地址
    public function actionAddDress(){
        $model = new Dress();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                $model->member_id = \Yii::$app->user->id;
                    $model->save();
                    return $this->redirect(['member/dress']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
    }
    //ajax用户修改地址
    public function actionEditDress(){
        $request = \Yii::$app->request;
        if($request->isGet){
            $id = $request->get('id');
            $model = Dress::find()->where(['id'=>$id])->asArray()->one();
            return json_encode($model);
        }else{
            $id = $request->get('id');
            $model = Dress::findOne(['id'=>$id]);
            $model->load($request->post());
            if($model->validate()){
                $model->save();
            }
        }
    }
    //ajax用户删除
    public function actionDeleteDress(){
        $request = \Yii::$app->request;
        if($request->isGet){
            $id = $request->get('id');
            $model = Dress::findOne(['id'=>$id]);
            $model->delete();
            return 'success';
        }else{
            return 'fail';
        }
    }
    //ajax设置默认地址
    public function actionDressDefault($id){

            $model = Dress::findOne(['id'=>$id]);
            $model->status = 1;
            $models = Dress::find()->where(['status'=>1])->one();
            if($models){
                $models->status = 0;
                $models->save();
            }
            $model->save();
        return $this->redirect(['member/dress']);
    }
    //发送短信
    public function actionSendMsm($tel){
        $code = rand(1000,9999);
        $res = \Yii::$app->msm->setTel($tel)->setParams(['code'=>$code])->send();
        if($res){
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('code_'.$tel,$code,5*60);
            return 'success';
        }else{
            return 'fail';
        }
    }
    //验证短信验证码
    public function actionValidateCode($tel,$code){
        //取出redis中这个验证码
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $recode = $redis->get('code_'.$tel);
        //和传过来的验证码进行比较
        if($recode == $code){
            return 'true';
        }else{
            return 'false';
        }
    }

}
