<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleDetail;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Article::find()->where(['is_deleted'=>0])->all();
        //var_dump($model);die;
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model = new Article();
        $articleDetail = new ArticleDetail();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $articleDetail->load($request->post());
            $model->create_time = time();
            if($model->validate() && $articleDetail->validate()){
                $articleDetail->save();
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('form',['model'=>$model,'articleDetail'=>$articleDetail]);
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yii2shop.cn",//图片访问路径前缀
            ]
        ]
    ];
}
    public function actionEdit($id){
        $model = Article::findOne(['id'=>$id]);
        $articleDetail = ArticleDetail::findOne(['article_id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $articleDetail->load($request->post());
            $model->create_time = time();
            if($model->validate() && $articleDetail->validate()){
                $articleDetail->save();
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('form',['model'=>$model,'articleDetail'=>$articleDetail]);
    }
    public function actionDelete($id){
        $model = Article::findOne(['id'=>$id]);
        $model->is_deleted = 1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article/index']);
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
