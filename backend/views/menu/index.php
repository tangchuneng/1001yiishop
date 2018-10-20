<?php
/* @var $this yii\web\View */
?>
    <h3>菜单列表</h3>
<!--<a href="--><?//= \yii\helpers\Url::to(['menu/add'])?><!--" class="btn btn-info">添加菜单</a>-->
<table class="table table-bordered table-responsive">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>地址/路由</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $menu):?>
        <tr data-id="<?= $menu->id?>">
            <td><?= $menu->id?></td>
            <td><?= $menu->name?></td>
            <td><?= $menu->url?></td>
            <td width="200">
                <!--原始的修改方法-->
                <a href="<?= \yii\helpers\Url::to(['menu/edit','id'=>$menu->id]) ?>" class="btn btn-default exit_btn">
                    <span class="glyphicon glyphicon-pencil">修改</span>
                </a>
                <!--使用ajax删除-->
                <a href="javascript:;" class="btn btn-default del_btn">
                    <span class="glyphicon glyphicon-trash">删除</span>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<a href="<?= \yii\helpers\Url::to(['menu/add'])?>" class="btn btn-info">添加菜单</a>

<?php
//>>>>>>>>>>>>>>>>>>使用ajax删除<<<<<<<<<<<<<<<<<<<<<//
/**
 * @var $this \yii\web\View
 */
$del_url = \yii\helpers\Url::to(['menu/del']);//保存ajax需要请求的地址
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
                alert('删除失败,该菜单下面还有子菜单');
            }
          });
      }
    });
JS
));
