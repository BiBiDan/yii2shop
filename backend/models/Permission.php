<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7 0007
 * Time: 下午 3:16
 */

namespace backend\models;


use Behat\Gherkin\Loader\YamlFileLoader;
use yii\base\Model;
use yii\web\HttpException;

class Permission extends Model
{
    public $name;
    public $des;
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    public function rules()
    {
        return [
            [['name','des'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
            ['name','validateEdit','on'=>self::SCENARIO_EDIT]
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名称(路由)',
            'des'=>'描述'
        ];
    }
    //自定义一个验证规则 路由不能重复name
    public function validateName(){
        $authManager = \Yii::$app->authManager;
        if($authManager->getPermission($this->name)){
            //如果存在 添加错误信息
            $this->addError('name','已存在不能添加');
        }
    }
    //自定义规则 修改的时候不会去验证自己 但是也不能修改到已存在的路由
    public function validateEdit(){
        //判断这个属性和老属性是不一样的 并且不能重复其他的
        if((\Yii::$app->request->get('name') != $this->name) && $this->validateName()){
                $this->addError('name','已存在不能修改');
        }
    }
    //添加权限
    public function createPermission(){
        $authManager = \Yii::$app->authManager;
        //创建权限 路由
        $permission = $authManager->createPermission($this->name);
        $permission->description = $this->des;
        return $authManager->add($permission);
    }
    //修改权限
    public function editPermission(){
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission(\Yii::$app->request->get('name'));
        $permission->name = $this->name;
        $permission->description = $this->des;
        //var_dump($permission);die;
        return $authManager->update(\Yii::$app->request->get('name'),$permission);
    }
}