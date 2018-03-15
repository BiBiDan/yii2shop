<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7 0007
 * Time: 下午 4:09
 */

namespace backend\models;


use yii\base\Model;

class Role extends Model
{
    public $role;
    public $desName;
    public $permission;
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_ADD = 'add';
    public function rules()
    {
        return [
            [['role','permission'],'required'],
            ['desName','safe'],
            ['role','validateAdd','on'=>self::SCENARIO_ADD],
            ['role','validateEdit','on'=>self::SCENARIO_EDIT]
        ];
    }
    public function attributeLabels()
    {
        return [
            'role'=>'角色(职位)',
            'desName'=>'职位描述',
            'permission'=>'权限'
        ];
    }
    //自定义一个验证规则 路由不能重复name
    public function validateAdd(){
        $authManager = \Yii::$app->authManager;
        if($authManager->getRole($this->role)){
            //如果存在 添加错误信息
            $this->addError('role','已存在不能添加');
        }
    }
    public function validateEdit(){
        //检查有没有这个数据
        $authManager = \Yii::$app->authManager;
        //$role= $authManager->getRole(\Yii::$app->request->get('name'));
        //判断这个属性和老属性是不一样的 并且不能重复其他的
        if(($this->role != \Yii::$app->request->get('name')) && $this->validateAdd()){
            $this->addError('role','已存在不能修改');
        }
    }
    //添加角色并赋予权限
    public function createRole(){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->createRole($this->role);
        $role->description = $this->desName;
        $authManager->add($role);
        $role = $authManager->getRole($this->role);
        //permission 权限是数组
        foreach ($this->permission as $v){
            $permission = $authManager->getPermission($v);
            $authManager->addChild($role,$permission);
        }
        return true;
    }
    //获取所有已有的权限信息
    public static function getPermission(){
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        $perNames = [];
        foreach ($permissions as $permission){
            //var_dump($permission->name);
            $perNames[$permission->name] = $permission->description;
        }
        return $perNames;
    }
    //获取该用户的已有权限信息
    public static function getPermissionOne(){
        $authManager = \Yii::$app->authManager;
        $permissions = $authManager->getChildren(\Yii::$app->request->get('name'));
        $arr = [];
        foreach ($permissions as $permission){
            $arr[$permission->name] = $permission->description;
        }
        return $arr;
    }
    //修改用户关联角色
    public function editRole(){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole(\Yii::$app->request->get('name'));
        //var_dump($role);die;
            $role->name = $this->role;
            $role->description = $this->desName;
            $authManager->update(\Yii::$app->request->get('name'),$role);
            $authManager->removeChildren($role);
            foreach ($this->permission as $v){
                $permission = $authManager->getPermission($v);
                //var_dump($permission);
                $authManager->addChild($role,$permission);
        }
        return true;
    }
}