<?php

namespace backend\controllers;

use backend\filters\RbacFilter;

class GoodsDayCountController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::class,
            ]
        ];
    }
}
