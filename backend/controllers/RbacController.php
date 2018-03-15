<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7 0007
 * Time: 下午 3:16
 */

namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Permission;
use backend\models\Role;
use Behat\Gherkin\Loader\YamlFileLoader;
use yii\web\Controller;
use yii\web\HttpException;

class RbacController extends Controller
{
    //添加权限
    public function actionAddPermission(){
        $model = new Permission();
        $model->scenario = Permission::SCENARIO_ADD;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->createPermission()){
                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect(['rbac/index-permission']);
                }
            }
        }
        return $this->render('permission',['model'=>$model]);
    }
    //添加角色
    public function actionAddRole(){
        $model = new Role();
        $model->scenario = Role::SCENARIO_ADD;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            echo '<pre>';
//            var_dump($model);die;
            if($model->validate()){
                if($model->createRole()){
                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect(['rbac/index-role']);
                }
            }
        }
        return $this->render('role',['model'=>$model]);
    }
    //展示权限列表页
    public function actionIndexPermission(){
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        return $this->render('index',['model'=>$permissions]);
    }
    //展示角色列表
    public function actionIndexRole(){
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRoles();
        return $this->render('index-role',['roles'=>$roles]);
    }
    //删除权限
    public function actionDeletePermission($name){
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if(!$permission){
            throw new HttpException('404','未找到该页面');
        }
        $authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['rbac/index-permission']);
    }
    //修改权限
    public function actionEditPermission($name){
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if(!$permission){
            throw new HttpException('404','未找到该页面');
        }
        $model = new Permission();
        $model->scenario = Permission::SCENARIO_EDIT;
        $model->name = $permission->name;
        $model->des = $permission->description;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->editPermission()){
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['rbac/index-permission']);
                }
            }
        }
        return $this->render('permission',['model'=>$model]);
    }
    //删除角色
    public function actionDeleteRole($name){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        //var_dump($role);die;
        if(!$role){
            throw new HttpException('404','未找到该页面');
        }
        $authManager->remove($role);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['rbac/index-role']);
    }
    //修改角色
    public function actionEditRole($name){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if(!$role){
            throw new HttpException('404','未找到该页面');
        }
        $model = new Role();
        $model->scenario = Role::SCENARIO_EDIT;
        $model->role = $role->name;
        $model->desName = $role->description;
        foreach (Role::getPermissionOne()  as $key=>$value){
            $model->permission[] = $key;
        }
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->editRole()){
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['rbac/index-role']);
                }
            }
        }
        return $this->render('role',['model'=>$model]);
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
            ]
        ];
    }







    //测试
    public function actionTest(){
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        $role = $authManager->getRoles();
//        foreach ($permissions as $permission){
//            var_dump($permission);
//        }
        foreach ($role as $value){
            var_dump($value);
        }
    }
}