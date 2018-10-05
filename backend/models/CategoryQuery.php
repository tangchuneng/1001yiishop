<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/5 0005
 * Time: 下午 1:49
 */
namespace backend\models;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class CategoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}