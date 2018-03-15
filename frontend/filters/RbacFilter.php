<?php
namespace frontend\filters;
use yii\web\HttpException;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/10 0010
 * Time: 下午 10:36
 */
class RbacFilter extends \yii\base\ActionFilter
{
    //配置控制器执行前
    public function beforeAction($action)
    {
        //如果用户没有登录就访问 跳转登录页面
        if(\Yii::$app->user->isGuest){
            return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
        }
        //如果用户没有被允许这个行为
        if(!\Yii::$app->user->can($action->uniqueId)){
            throw new HttpException('403','您没有权限访问页面');
        }
        return true;
    }
}