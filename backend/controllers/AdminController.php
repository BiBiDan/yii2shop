<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Admin;
use backend\models\EditAdminForm;
use backend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;

class AdminController extends \yii\web\Controller
{

    public function actionIndex()
    {
        $model = Admin::find()->all();

        return $this->render('index',['model'=>$model]);
    }
    //添加用户
    public function actionAdd(){
        $model = new Admin();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->save()){
                    $model->addRole();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('form',['model'=>$model]);
    }
    //修改用户
    public function actionEdit($id){
        $model = Admin::findOne(['id'=>$id]);
        $authManager = \Yii::$app->authManager;
        $model->role = array_keys($authManager->getAssignments($id));
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->save()){
                 $model->editRole();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('editall',['model'=>$model]);
    }
    //初始化密码
    public function actionPwd($id){
        $model = Admin::findOne(['id'=>$id]);
        $model->password_hash = \Yii::$app->security->generatePasswordHash('123');
        $model->save();
        \Yii::$app->session->setFlash('success','初始化成功');
        return $this->redirect(['admin/index']);
    }
    //ajax删除
    public function actionDelete(){
        $request = \Yii::$app->request;
        if($request->isPost){
           $id = $request->post('id');
           $model = Admin::findOne(['id'=>$id]);
           if($model->delete()){
                $data['code']=1;
                $data['msg']='删除成功';
                $data['data']='';
           }else{
               $data['code']=0;
               $data['msg']='删除失败';
               $data['data']='';
           }
        }
        echo json_encode($data);
    }
    //修改个人密码
    public function actionEditPwd(){
        $model = Admin::findOne(['id'=>\Yii::$app->user->id]);
        //var_dump($model);die;
        //定义修改个人密码的场景
        $model->setScenario(Admin::SCENARIO_PWD);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->newPwd);
                //var_dump($model->oldPwd);die;
                $model->save(false);//因为把新密码保存进数据库了 save方法会再验证一次 所以老密码会一直报错 把save改为0 false
                //var_dump($model->getErrors());die;
                \Yii::$app->user->logout();
                \Yii::$app->session->setFlash('success','修改成功,请重新登录');
                return $this->redirect(['admin/login']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    //配置一个验证码行为
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'maxLength'=>3,
                'minLength'=>3
            ]
        ];
    }

    //管理员登录
    public function actionLogin(){
/*        //首先验证cookie中有没有用户信息 有直接保存 没有再让用户登录
        $cookie = \Yii::$app->request->cookies;
        //echo "<pre>";
        //var_dump($cookie->getValue('password'));
        $admin = Admin::findOne(['username'=>$cookie->getValue('username')]);
        if($admin && \Yii::$app->security->validatePassword($cookie->getValue('password'),$admin->password_hash)){
            //var_dump($admin->password_hash);die;
                //把用户信息保存进session
                \Yii::$app->user->login($admin);
                \Yii::$app->session->setFlash('success','欢饮回来');
                return $this->redirect(['admin/index']);
        }*/
        //实例化一个登录表单
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
//            echo "<pre>";
//            var_dump($request->post());die;
            $model->load($request->post());
            if($model->validate()){
                //调用登录模型的验证功能
                if($model->login()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['admin/admin']);
                }
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //注销
    public function actionLogout(){
        $cookie = \Yii::$app->response->cookies;
        $cookie->remove('username');
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['admin/login']);
    }
/*    public function actionTest(){
        $authManager = \Yii::$app->authManager;
        //创建权限 路由
        $permission1 = $authManager->createPermission('admin/add');
        $permission1->description = '添加用户';
        //保存到数据表
        $authManager->add($permission1);

        //创建权限 路由
        $permission2 = $authManager->createPermission('admin/index');
        $permission2->description = '查看用户';
        //保存到数据表
        $authManager->add($permission2);

        //创建角色
        $role1 = $authManager->createRole('超级管理员');
        $role2 = $authManager->createRole('普通管理员');
        $authManager->add($role1);
        $authManager->add($role2);

        //为权限和角色关联
        $role1 = $authManager->getRole('超级管理员');
        $permission1 = $authManager->getPermission('admin/index');
        $authManager->addChild($role1,$permission1);

        $role2 = $authManager->getRole('普通管理员');
        $permission2 = $authManager->getPermission('admin/index');
        $authManager->addChild($role2,$permission2);
        //给用户指派角色
        $role1 = $authManager->getRole('超级管理员');
        $role2 = $authManager->getRole('普通管理员');
        $authManager->assign($role1,8);
        $authManager->assign($role2,3);
        echo '执行成功';
    }*/
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
                'except'=>['logout','login','captcha','edit-pwd','admin']
            ]
        ];
    }
    public function actionAdmin(){
        return $this->render('admin');
    }
}
