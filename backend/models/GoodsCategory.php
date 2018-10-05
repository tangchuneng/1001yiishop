<?php

namespace backend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use backend\models\CategoryQuery;
/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property string $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '名称',
            'parent_id' => '上级分类',
            'intro' => '简介',
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',//这里必须打开,支持多棵树,因为有多个一级分类(多棵树)
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    //获取ztree数据
    public static function getZNodes(){
        //获取分类数据:只获取指定的字段并转换成数组
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //该数组保存一个id为0的特殊分类
        $top = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        //将两个数组合并再传过去
        array_unshift($categories,$top);
        //var_dump($categories);exit;
        return $categories;
    }
}
