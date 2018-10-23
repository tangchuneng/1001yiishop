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

    //>>获取ztree数据
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

    //>>获取首页商品分类数据
    public static function getGoodsCategories(){
        //1.实例化redis对象(php自带)
        $redis = new \Redis();
        //2.链接redis
        $redis->connect('127.0.0.1');
        //3.获取数据
        $html = $redis->get('goods_categories');
        //如果redis缓存里面有就是用缓存里面的$html 如果没有就生成$html并保存到redis缓存
        if($html === false){
            $html = '';
            $categories1 = self::find()->where(['parent_id'=>0])->all();
            foreach ($categories1 as $k1=>$category1){
                $html .= '<div class="cat '.($k1?'':'item1').'">';
                $html .= '<h3><a href="'.\yii\helpers\Url::to(["goods/list?id=$category1->id"]).'">'.$category1->name.'</a><b></b></h3>';
                $html .= '<div class="cat_detail">';
                foreach ($category1->children(1)->all() as $k2=>$category2){
                    $html .= '<dl class="'.($k2?'':'dl_1st').'">';
                    $html .= '<dt><a href="'.\yii\helpers\Url::to(["goods/list?id=$category2->id"]).'">'.$category2->name.'</a></dt>';
                    foreach ($category2->children()->all() as $category3){
                        $html .= '<dd>';
                        $html .= '<a href="'.\yii\helpers\Url::to(["goods/list?id=$category3->id"]).'">'.$category3->name.'</a>';
                        $html .= '</dd>';
                    }
                    $html .= '</dl>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }
            //4.没有该数据就添加 并 设置生效的时间
            $redis->set('goods_categories',$html,24*3600);
        }
        return $html;
    }
}
