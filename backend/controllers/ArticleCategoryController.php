<?php

namespace backend\controllers;

use backend\models\ArticleCategory;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = ArticleCategory::find()->where(['is_deleted'=>0])->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model = new ArticleCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->is_deleted = 0;
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('form',['model'=>$model]);
    }
    public function actionEdit($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->is_deleted = 0;
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('form',['model'=>$model]);
    }
    //假删除
    public function actionDelete($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
        $model->is_deleted=1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article-category/index']);
    }
}
