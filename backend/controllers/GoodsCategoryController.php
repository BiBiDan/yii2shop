<?php

namespace backend\controllers;



use backend\models\GoodsCategory;


class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = GoodsCategory::find()->all();

        return $this->render('index',['model'=>$model]);
    }
    public function actionAdd(){
        $model = new GoodsCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //两种 一个是创建根节点  一个子节点
                if($model->parent_id){
                    //存在的话 添加子节点
                    $good = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($good);
                }else{
                    //不存在 创建根节点
                    $model->makeRoot();
                }
                $model->save();
                \Yii::$app->session->setFlash('success','提交成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>'0','parent_id'=>'0','name'=>'顶级分类'];
        $nodes = json_encode($nodes);
        return $this->render('form',['model'=>$model,'nodes'=>$nodes]);
    }
//    public function actionTest(){
//        $goodsCategory = new GoodsCategory();
//        $goodsCategory->name = '家用电器';
//        $goodsCategory->parent_id = 0;
//        $goodsCategory->makeRoot();
//
//        $goodCategory = GoodsCategory::findOne(['id'=>1]);
//        $goodsCategory  =  new GoodsCategory();
//        $goodsCategory->name = '洗衣机';
//        $goodsCategory->parent_id = $goodCategory->id;
//        $goodsCategory ->prependTo($goodCategory);
//        var_dump($goodsCategory->getErrors());
//    }
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //两种 一个是创建根节点  一个子节点
                if($model->parent_id){
                    //存在的话 添加子节点
                    $good = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($good);
                }else{
                    //如果修改的是顶级分类到顶级分类插件有bug  先判断是不是顶级分类 如果是 直接保存就行
                    if($model->getOldAttribute('parent_id')==0){
                     $model->save();
                    }else{
                        //不存在 创建根节点
                        $model->makeRoot();
                    }
                }
                $model->save();
                \Yii::$app->session->setFlash('success','提交成功');
                return $this->redirect(['goods-category/index']);
            }
        }
        $nodes = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[] = ['id'=>'0','parent_id'=>'0','name'=>'顶级分类'];
        $nodes = json_encode($nodes);
        return $this->render('form',['model'=>$model,'nodes'=>$nodes]);
    }

    public function actionDelete($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        $goodsCategorys = GoodsCategory::find()->all();
        foreach ($goodsCategorys as $goodsCategory){
            if($model->id == $goodsCategory->parent_id){
                \Yii::$app->session->setFlash('success','有子分类不能删除');
                return $this->redirect(['goods-category/index']);
        }
        }
        $model->deleteWithChildren();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods-category/index']);
    }




    public function actionZtree(){

        return $this->renderPartial('ztree');
    }
}
