<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/2 0002
 * Time: 下午 5:37
 */
?>
<a href="<?= \yii\helpers\Url::to(['brand/add'])?>" class="btn btn-info">添加品牌</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>logo</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr data-id="<?= $model->id?>">
        <td><?= $model->id?></td>
        <td><?= $model->name ?></td>
        <td><?= $model->intro ?></td>
        <td><img src="<?= $model->logo?>" height="50" /> </td>
        <td><?= $model->sort ?></td>
        <td><?= $model->is_delete ?></td>
        <td>
            <!--原始的修改方法-->
            <a href="<?= \yii\helpers\Url::to(['brand/edit','id'=>$model->id]) ?>" class="btn btn-default exit_btn">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>
            <!--使用ajax删除-->
            <a href="javascript:;" class="btn btn-default del_btn">
                <span class="glyphicon glyphicon-trash"></span>
            </a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<a href="<?= \yii\helpers\Url::to(['brand/add'])?>" class="btn btn-info">添加品牌</a>
<?php
/**
 * @var $this \yii\web\View
 */
$del_url = \yii\helpers\Url::to(['brand/del']);//保存ajax需要请求的地址
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
