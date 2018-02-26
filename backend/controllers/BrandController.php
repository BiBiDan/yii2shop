<?php

namespace backend\controllers;

use app\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Brand::find()->where(['is_delete'=>0])->all();
        return $this->render('index',['model'=>$model]);
    }
    //添加
    public function actionAdd(){
        $model = new Brand();
        $request = \Yii::$app->request;

        if($request->isPost){
           // var_dump($_POST);die;
            $model->load($request->post());
            //实例化上传对象保存在属性中
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            $model->is_delete = 0;
            if($model->validate()){
                if($model->imgFile){
                    $file = '/upload/brand/'.uniqid().$model->imgFile->extension;
                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){
                        $model->logo = $file;
                    }
                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('form',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //实例化上传对象保存在属性中
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            $model->is_delete = 0;
            if($model->validate()){
                if($model->imgFile){
                    $file = '/upload/brand/'.uniqid().$model->imgFile->extension;
                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){
                        $model->logo = $file;
                    }
                }
                $model->save(0);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('form',['model'=>$model]);
    }
    //假删除
    public function actionDelete($id){
       \Yii::$app->db->createCommand("update `brand` set `is_delete`=1 where `id`=$id")->execute();
       \Yii::$app->session->setFlash('success','删除成功');
       return $this->redirect(['brand/index']);
    }
}
