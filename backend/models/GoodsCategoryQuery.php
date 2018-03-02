<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/1 0001
 * Time: 上午 11:37
 */

namespace backend\models;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class GoodsCategoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}