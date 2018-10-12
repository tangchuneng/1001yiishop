<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "Article_category".
 *
 * @property string $id
 * @property string $name
 * @property string $intro
 * @property string $sort
 * @property integer $is_delete
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['sort', 'is_delete'], 'required'],
            [['sort', 'is_delete'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
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
            'sort' => '排序',
            'is_delete' => '状态',
        ];
    }

    //>>静态获取所有文章分类
    public static function getCategories(){
        $categories = ArticleCategory::find()->where(['!=','is_delete',0])->all();
        $category_list = [];
        foreach ($categories as $category){
            $category_list[$category->id] = $category->name;
        }
        return $category_list;
    }
}
