<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/5 0005
 * Time: 下午 2:17
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();

//>>>>>>>>>>ztree<<<<<<<<<<<<<//
//接收zNods数据并转换为json格式
$categories = json_encode(\backend\models\GoodsCategory::getZNodes());
echo '<ul id="treeDemo" class="ztree"></ul>';

//>>>>>>>>>>ztree<<<<<<<<<<<<<//

echo $form->field($model,'intro')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

//>>>>>>>>>>>>>>>>>注册Ztree的静态资源和JS代码<<<<<<<<<<<<<<<<<<<<//
/**
 * 添加该注释方便编辑器提示
 * @var $this \yii\web\View
 */
//注册CSS文件
//$this->registerCssFile('@web/ztree/css/demo.css');//边框资源
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
        var node = zTreeObj.getNodeByParam("id","{$model->parent_id}",null);
        zTreeObj.selectNode(node);
JS
));