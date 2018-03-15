<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "mune".
 *
 * @property int $id
 * @property string $name 菜单名
 * @property int $parent_id 上级菜单
 * @property string $url_to 地址(路由)
 * @property int $sort 排序
 */
class Mune extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mune';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sort','parent_id'], 'required'],
            ['url_to','safe'],
            [['parent_id', 'sort'], 'integer'],
            [['name', 'url_to'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名',
            'parent_id' => '上级菜单',
            'url_to' => '地址(路由)',
            'sort' => '排序',
        ];
    }
    //展示上级分类
    public static function showParent(){
        $names = self::find()->where(['parent_id'=>0])->all();
        $arr[] = '顶级分类';
        foreach ($names as $name){
            $arr[$name->id] = $name->name;
        }
        return $arr;
    }
    //展示路由地址
    public static function showUrl(){
        $authManager = Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        $arr = [];
        foreach ($permissions as $permission){
            $arr[$permission->name] = $permission->name;
        }
        return $arr;
    }
    //导航栏输出
    public static function showMune(){
        $munes = self::find()->where(['parent_id'=>0])->all();
        //var_dump($munes);die;
        foreach ($munes as $mune){
            $items = [];
            $children = self::find()->where(['parent_id'=>$mune->id])->all();
                foreach ($children as $child){
                        if(Yii::$app->user->can($child->url_to))
                        $items[] = ['label'=>$child->name,'url'=>\yii\helpers\Url::to([$child->url_to])];
            }
                if($items)
                $menuItems[] = ['label'=>$mune->name, 'items'=> $items];
        }
            return $menuItems;
    }

//        $menuItems[] = [
//            'label'=>'商品',
//            'items'=>[
//                ['label'=>'商品分类管理','url'=>\yii\helpers\Url::to(['goods-category/index'])],
//                ['label'=>'商品管理','url'=>\yii\helpers\Url::to(['goods/index'])],
//            ]
//        ];


}
