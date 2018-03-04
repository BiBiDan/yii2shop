<?php

namespace backend\controllers;

use backend\models\GoodsGallery;
use yii\web\UploadedFile;

class GoodsGalleryController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionIndex($id)
    {
            $model = GoodsGallery::find()->where(['goods_id'=>$id])->all();
            //var_dump($model);die;
            return $this->render('index',['model'=>$model]);


    }
    public function actionUploadPic(){
        $upload = UploadedFile::getInstanceByName('file');
        $file = '/upload/goods-gallery/'.uniqid().'.'.$upload->extension;
        if($upload->saveAs(\Yii::getAlias('@webroot').$file)){
            return json_encode(['url'=>$file]);
        }else{
            return false;
        }
    }
    public function actionAjaxAdd($id){
        $model = new GoodsGallery();
            $model->goods_id = $id;
            $model->path = $_POST['url'];
            $res = $model->save();
            if($res){
                return json_encode(['url'=>$model->path,'id'=>$model->id]);
            }else{
                return 'fail';
            }
    }
    public function actionDelete($id){
        $model = GoodsGallery::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods-gallery/index','id'=>$model->goods_id]);
    }
}
