<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $is_delete
 */
class Brand extends \yii\db\ActiveRecord
{
    //public $file;//保存上传文件
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['intro'], 'string'],
            [['sort', 'is_delete'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            //['file','file','extensions'=>['jpg','png','gif']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '品牌名称',
            'intro' => '品牌简介',
            'logo' => 'LOGO',
            'sort' => '排序',
            'is_delete' => '状态',
        ];
    }

    //>>静态的获取所有数据的方法
    public static function getAll(){
        $brands = Brand::find()->asArray()->all();
        $brand_list = [];
        foreach ($brands as $brand){
            $brand_list[$brand['id']] = $brand['name'];
        }
        //返回重新排列好的数组
        return $brand_list;
    }
}
