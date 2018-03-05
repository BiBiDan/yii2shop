<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/4 0004
 * Time: 下午 10:32
 */

namespace backend\controllers;


use yii\web\Controller;

class TestController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        return $this->render('index');
    }
}