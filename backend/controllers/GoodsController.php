<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    //关闭csrf验证
    public $enableCsrfValidation = false;

    public function actionAjaxIndex(){
        //var_dump($_GET['name']);die;
        if($_GET){
            //var_dump($_GET['GoodsSearchForm']['name']);die;
            if(empty($_GET['min']) || empty($_GET['max'])){
                $query = Goods::find()->where(['status'=>0])->andWhere(['and',['like','name',$_GET['name']],['like','sn',$_GET['sn']]]);
            }else{
                $query = Goods::find()->where(['status'=>0])->andWhere(['and',['like','name',$_GET['name']],['like','sn',$_GET['sn']],['>','shop_price',$_GET['min']],['<','shop_price',$_GET['max']]]);
            }

            $pager = new Pagination();
            $pager->defaultPageSize = 5;
            $pager->totalCount = $query->count();
            $model = $query->offset($pager->offset)->limit($pager->defaultPageSize)->asArray()->all();

//            echo '<pre>';
//            var_dump($model);die;
            return json_encode(['model'=>$model,'brand'=>Goods::getBrandName(),'category'=>Goods::getCategoryName()]);
        }
    }

    public function actionIndex()
    {
        $query = Goods::find()->where(['status'=>0]);
        $pager = new Pagination();
        $pager->defaultPageSize = 5;
        $pager->totalCount = $query->count();
        $model = $query->offset($pager->offset)->limit($pager->defaultPageSize)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model  = new Goods();
        $modelContent = new GoodsIntro();
        $category = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $category = json_encode($category);

        $request = \Yii::$app->request;
        if($request->isPost){
            //var_dump($request->post());die;
            $model->load($request->post());
            $modelContent->load($request->post());
            if($model->validate()&&$modelContent->validate()){
                $model->create_time = time();
                $model->status = 0;
/*                $good = Goods::find()->orderBy(['create_time'=>SORT_DESC])->one();
                //var_dump(intval(date('Ymd',$good->create_time)));die;
                if($good){
                    if(date('Ymd',$good->create_time) != date('Ymd',time())){

                    }else{
                        //echo 111111111;die;
                    $model->sn = ($good->sn*1) + 1;
                    var_dump($model->sn);die;
                    }
                }*/
                //添加数据时同时关联商品天数内容表
                $model->sn = $model->saveGoodsDay();
                $model->save();
                $modelContent->goods_id = $model->id;
                $modelContent->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('form',['model'=>$model,'modelContent'=>$modelContent,'category'=>$category]);
    }
    public function actionUploadLogo(){
        //return json_encode(['url'=>111111111111111]);
        //先把图片上传到upload
        $upload = UploadedFile::getInstanceByName('file');
        $file = '/upload/goods-logo/'.uniqid().'.'.$upload->extension;
        //var_dump($file);die;
        $res = $upload->saveAs(\Yii::getAlias('@webroot').$file);
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
                var_dump($err);
            } else {
                return json_encode(['url'=>'http://p4t192xf8.bkt.clouddn.com/'.$key]);
            }
        }
    }
    public function actionEdit($id){
        $model  = Goods::findOne(['id'=>$id]);
        $modelContent = GoodsIntro::findOne(['goods_id'=>$id]);
        //var_dump($modelContent);die;
        $category = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $category = json_encode($category);

        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->status = 0;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('form',['model'=>$model,'modelContent'=>$modelContent,'category'=>$category]);
    }
    public function actionDelete($id){
        $model = Goods::findOne(['id'=>$id]);
        $model->status = 1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods/index']);
    }
    //Ueditor插件
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.cn"
            ],
            ]
        ];
    }
    public function actionTest(){

        $num=12;
        $num=str_pad($num,5,"0",STR_PAD_LEFT);
        echo $num;
}
}
