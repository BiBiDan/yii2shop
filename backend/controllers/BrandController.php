<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends \yii\web\Controller
{
    //关闭csrf验证
    public $enableCsrfValidation = false;

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
            //$model->imgFile = UploadedFile::getInstance($model,'imgFile');
            $model->is_delete = 0;
            if($model->validate()){
//                if($model->imgFile){
//                    $file = '/upload/brand/'.uniqid().$model->imgFile->extension;
//                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){
//                        $model->logo = $file;
//                    }
//                }
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
            //$model->imgFile = UploadedFile::getInstance($model,'imgFile');
            $model->is_delete = 0;
            if($model->validate()){
//                if($model->imgFile){
//                    $file = '/upload/brand/'.uniqid().$model->imgFile->extension;
//                    if($model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0)){
//                        $model->logo = $file;
//                    }
//                }
                $model->save();
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
    public function actionUploadLogo(){
        $uploadImg = UploadedFile::getInstanceByName('file');
        //var_dump($upload);
        $file = '/upload/brand/'.uniqid().'.'.$uploadImg->extension;
        $res = $uploadImg->saveAs(\Yii::getAlias('@webroot').$file);
        if($res){
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey ="tcdzUJ71rWdNaAlZtxztlDdfgNhcoxFeSE37xxse";
            $secretKey = "U8KoOosIvaeQMPYXE32MoLFgai0OZYc9D3TTDtD3";
            $bucket = "banana";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot').$file;
            // 上传到七牛后保存的文件名
            $key = $file;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                var_dump($err);die;
            } else {
                //七牛云地址http://<domain>/<key>
                return json_encode(['url'=>"http://p4t192xf8.bkt.clouddn.com/{$file}"]);
            }
        }
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
