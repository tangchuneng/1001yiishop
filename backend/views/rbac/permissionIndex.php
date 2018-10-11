<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 下午 7:19
 */
?>
<a href="<?= \yii\helpers\Url::to(['rbac/add-permission'])?>" class="btn btn-info">添加权限</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($permissions as $permission):?>
        <tr>
            <td><?= $permission->name?></td>
            <td><?= $permission->description?></td>
            <td>
                <!--原始的修改方法-->
                <a href="<?= \yii\helpers\Url::to(['rbac/edit','name'=>$permission->name]) ?>" class="btn btn-default exit_btn">
                    <span class="glyphicon glyphicon-pencil">修改</span>
                </a>
                <!--使用ajax删除-->
                <a href="javascript:;" class="btn btn-default del_btn">
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
$del_url = \yii\helpers\Url::to(['rbac/del-$permission']);//保存ajax需要请求的地址
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
JS
));
