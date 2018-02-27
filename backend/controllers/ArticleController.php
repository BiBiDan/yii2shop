<?php

namespace backend\controllers;

use app\models\Article;
use app\models\ArticleDetail;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        $model = Article::find()->where(['article_category_id'=>$id])->all();
        //var_dump($model);die;
        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model = new Article();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->create_time = time();
            if($model->validate()){
                $articleDetail = new ArticleDetail();
                $articleDetail->content = $model->content;
                $articleDetail->save();
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index','id'=>$model->article_category_id]);
            }else{
                var_dump($model->getErrors());die;
            }
        }
        return $this->render('form',['model'=>$model]);
    }
}
