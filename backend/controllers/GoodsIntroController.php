<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\GoodsIntro;

class GoodsIntroController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        $model = GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('index',['model'=>$model]);
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
