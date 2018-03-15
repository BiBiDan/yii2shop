<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Mune;

class MuneController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Mune::find()->all();

        return $this->render('index',['model'=>$model]);
    }
    //添加菜单
    public function actionAdd(){
        $model = new Mune();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            echo '<pre>';
//            var_dump($model);die;
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                //return $this->redirect(['mune/index']);
            }
        }
        return $this->render('form',['model'=>$model]);
    }
    //菜单修改
    public function actionEdit($id){
        $model = Mune::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            echo '<pre>';
//            var_dump($model);die;
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改');
                return $this->redirect(['mune/index']);
            }
        }
        return $this->render('form',['model'=>$model]);
    }
    //删除菜单
    public function actionDelete($id){
        $model = Mune::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['mune/index']);
    }

    public function actionTest(){
        var_dump(Mune::showMune());
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
