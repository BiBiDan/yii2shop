<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名称
 * @property string $sn 货号
 * @property string $logo LOGO图片
 * @property int $goods_category_id 商品分类id
 * @property int $brand_id 品牌分类
 * @property string $market_price 市场价格
 * @property string $shop_price 商品价格
 * @property int $stock 库存
 * @property int $is_on_sale 是否在售
 * @property int $status 状态/假删除
 * @property int $sort 排序
 * @property int $create_time 添加时间
 * @property int $view_times 浏览次数
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','goods_category_id','brand_id','is_on_sale','sort','market_price','shop_price'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '商品id',
            'name' => '名称',
            'sn' => '货号',
            'logo' => 'Logo',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价',
            'shop_price' => '售价',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '点击次数',
        ];
    }
    //关联商品表
    public static function getBrandName(){
        $brands = Brand::find()->all();
        $brandName = [];
        foreach ($brands as $brand){
            $brandName[$brand->id] = $brand->name;
        }
        return $brandName;
    }
    //关联分类表
    public static function getCategoryName(){
        $categorys = GoodsCategory::find()->all();
        $categoryName = [];
        foreach ($categorys as $category){
            $categoryName[$category->id] = $category->name;
        }
        return $categoryName;
    }
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    public function saveGoodsDay(){
        //根据时间字段查表
        $day = date('Y-m-d',time());
        $model = GoodsDayCount::find()->where(['day'=>$day])->one();
        //var_dump($model);die;
        if($model && $model->day == $day){
            $model->count = ($model->count*1)+1;
        }else{
            $model = new GoodsDayCount();
            $model->day = date('Ymd',time());
            $model->count = 1;
        }
        $model->save();
        $sn = date('Ymd',time()).str_pad($model->count,5,'0',STR_PAD_LEFT);
        return $sn;
    }
}
