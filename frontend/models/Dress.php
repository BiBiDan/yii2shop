<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "dress".
 *
 * @property int $id
 * @property int $member_id 用户id
 * @property string $name 收货人
 * @property string $province 省
 * @property string $city 城市
 * @property string $area 县
 * @property string $dress 地址
 * @property string $phone 手机号
 * @property int $status 默认地址
 */
class Dress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name', 'province', 'city', 'area', 'dress', 'phone'], 'required'],
            ['status','safe'],
            [['member_id'], 'integer'],
            [['name', 'province', 'city', 'area', 'dress', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'name' => 'Name',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'dress' => 'Dress',
            'phone' => 'Phone',
            'status' => 'Status',
        ];
    }
}
