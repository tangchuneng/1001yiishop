<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/7 0007
 * Time: 下午 7:02
 */
use yii\web\JsExpression;
use \kucha\ueditor\UEditor;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();

echo $form->field($model,'logo')->hiddenInput();
//>>>>>>>>>>>>>>>>uploadifive插件处理上传的logo(Ajax上传,发起ajax请求),开始
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 100,
        'height' => 30,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传文件的路径写入logo字段的隐藏域
        $("#gooods-logo").val(data.fileUrl);
        //回显图片
        $("#img").attr("src",data.fileUrl);
    }
}
EOF
        ),
    ]
]);

echo \yii\bootstrap\Html::img($model->logo,['id'=>'img','height'=>100]);//回显上传后的图片,增加用户体验
//>>>>>>>>>>>>>>>>uploadifive插件处理上传的logo(Ajax上传,发起ajax请求),结束

//>>>>>>>>>>>>>>>>ztree显示商品分类,开始<<<<<<<<<<<//
//接收zNods数据并转换为json格式
$categories = json_encode(\backend\models\GoodsCategory::getZNodes());
//var_dump($categories);exit;
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo '<p class="help-block help-block-error"></p>';
//>>>>>>>>>>>>>>>>ztree显示商品分类,结束<<<<<<<<<<<//

//获取品牌分类数据并显示
//echo $form->field($model,'brand_id')->hiddenInput();
$brand_list = \backend\models\Brand::find()->select(['name'])->all();
?>
<div class="form-group field-gooods-shop_price required">
    <label class="control-label" for="goods-brand_id">品牌分类</label>
    <select id="goods-brand_id" class="form-control" name="Goods[brand_id]" aria-required="true" aria-invalid="true">
        <option>=请选择品牌=</option>
        <?php foreach ($brand_list as $brand):?>
        <option value="<?= $brand['id']?>"><?= $brand['name']?></option>
        <?php endforeach;?>
    </select>
    <p class="help-block help-block-error"></p>
</div>

<?php
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale')->inline()->radioList([1=>'在售',0=>'下架']);
echo $form->field($model,'sort')->textInput();

//>>>>>>>>>>>>>>>>>使用UEditor开始<<<<<<<<<<<<<<<<<<//
echo $form->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '250',
        //设置语言
        'lang' =>'zh-cn',//英文为 en
        ]
]);
//编辑器相关配置参考
/*echo UEditor::widget([
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
        //定制菜单
        'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'fontsize',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                'forecolor', 'backcolor', '|',
                'lineheight', '|',
                'indent', '|'
            ],
        ]
    ]
]);*/
//>>>>>>>>>>>>>>>>>使用UEditor结束<<<<<<<<<<<<<<<<<<//


echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();


//>>>>>>>>>>>>>>>>>注册Ztree的静态资源和JS代码<<<<<<<<</<<<<<<<<</
/**
 * 添加该注释方便编辑器提示
 * @var $this \yii\web\View
 */
//注册CSS文件
$this->registerCssFile('@web/ztree/css/demo.css');//边框资源
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
//注册JS文件
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {//时间回调函数
                onClick: function(event,treeId,treeNode) {
                  console.log(treeNode);
                  //获取当前节点的id,写入到 parent_id 的值
                  $("#goodscategory-parent_id").val(treeNode.id);
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$categories};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开全部节点 true展开  false不展开
        zTreeObj.expandAll(true);
        //修改:根据分类的parent_id来选中节点
        //获取你所需要选中的节点
        var node = zTreeObj.getNodeByParam("id","{$category->parent_id}",null);
        zTreeObj.selectNode(node);
        
        //将选中分类的id写入到goods_category_id字段的隐藏域
        //$("#gooods-logo").val(zNodes);
        
        //将选中品牌的id写入到brand_id的隐藏域
        //$("#gooods-brand_id").val($("#Goods[brand_id]"));
JS
));