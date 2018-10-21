<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 下午 7:19
 */
?>
<!--第一步：引入Javascript / CSS （CDN）-->
<!-- DataTables CSS -->
<!--<link rel="stylesheet" type="text/css" href="DataTables-1.10.15/media/css/jquery.dataTables.css">-->
<?=\yii\helpers\Html::jsFile('@web/DataTables-1.10.15/media/js/jquery.js')?>

<!-- jQuery -->
<!--<script type="text/javascript" charset="utf8" src="DataTables-1.10.15/media/js/jquery.js"></script>-->
<?=\yii\helpers\Html::jsFile('@web/DataTables-1.10.15/media/js/jquery.dataTables.js')?>

<!-- DataTables -->
<!--<script type="text/javascript" charset="utf8" src="DataTables-1.10.15/media/js/jquery.dataTables.js"></script>-->
<?=\yii\helpers\Html::cssFile('@web/DataTables-1.10.15/media/css/jquery.dataTables.css')?>

    <h3>权限列表</h3>
<table id="table_id_example" class="table table-bordered table-responsive">
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($permissions as $permission):?>
        <tr data-id="<?= $permission->name?>">
            <td><?= $permission->name?></td>
            <td><?= $permission->description?></td>
            <td>
                <!--原始的修改方法-->
                <a href="<?= \yii\helpers\Url::to(['rbac/edit-permission','name'=>$permission->name]) ?>" class="btn btn-warning exit_btn">
                    <span class="glyphicon glyphicon-pencil">修改</span>
                </a>
                <!--使用ajax删除-->
                <a href="javascript:;" class="btn btn-danger del_btn">
                    <span class="glyphicon glyphicon-trash">删除</span>
            </td>
        </tr>
    <?php endforeach;?>
</table>
    <a href="<?= \yii\helpers\Url::to(['rbac/add-permission'])?>" class="btn btn-info">添加权限</a>
<?php

//>>>>>>>>>>>>>>>>>>使用ajax删除<<<<<<<<<<<<<<<<<<<<<//
/**
 * @var $this \yii\web\View
 */
$del_url = \yii\helpers\Url::to(['rbac/del-permission']);//保存ajax需要请求的地址
//注册JS代码:其实就是通过 JsExpression 类返回一个 heredoc 字符串,该字符串中是需要执行的JS代码
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $(".del_btn").click(function(){
      if(confirm('确认删除吗?')){
          var tr = $(this).closest('tr');
          var id = tr.attr("data-id");
          $.post("{$del_url}",{id:id},function(data){
            if(data == 'success'){
                alert('删除成功');
                tr.hide('slow');
            }else{
                alert('删除失败');
            }
          });
      }
    });
    /*$(document).ready( function () {
        $('#table_id_example').DataTable();
    } );*/
JS
));
