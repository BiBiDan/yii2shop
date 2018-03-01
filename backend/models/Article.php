<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $article_category_id
 * @property string $sort
 * @property integer $is_deleted
 * @property string $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'article_category_id', 'sort'], 'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'is_deleted', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类id',
            'sort' => '排序',
            'is_deleted' => '状态',
            'create_time' => '创建时间',
        ];
    }
    //得到文章分类表
    public static function getArticleCategoryName(){
        $articleCategorys = ArticleCategory::find()->where(['is_deleted'=>0])->all();
        $articleCategoryNames = [];
        foreach ($articleCategorys as $articleCategory){
            $articleCategoryNames[$articleCategory->id] = $articleCategory->name;
        }
        return $articleCategoryNames;
    }
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
}
