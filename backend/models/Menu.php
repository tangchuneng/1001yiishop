<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $parent_id
 * @property integer $sort
 * @property string $url
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'sort'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 255],
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
            'name' => '菜单名称',
            'parent_id' => '上级菜单',
            'sort' => '排序',
            'url' => '地址/路由',
        ];
    }

    //>>静态获取所有菜单
    public static function getAll(){
        $menus = Menu::find()->asArray()->all();
        $menu_list[0]  = '顶级菜单';
        foreach ($menus as $menu){
            $menu_list[$menu['id']]  = $menu['name'];
        }
        return $menu_list;
    }
}
