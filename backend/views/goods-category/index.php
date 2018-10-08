<?php

?>
<a href="<?= \yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-info">添加分类</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <!--<th>树</th>
        <th>父分类ID</th>
        <th>左值</th>
        <th>右值</th>-->
        <th>操作</th>
    </tr>
    <?php foreach ($categories as $category):?>
    <tr data-id="<?= $category->id?>">
        <td><?= $category->id?></td>
        <td><?= str_repeat('>',$category->depth).$category->name;?></td>
        <td><?= $category->intro?></td>
        <!--<td><?/*= $category->tree*/?></td>
        <td><?/*= $category->parent_id*/?></td>
        <td><?/*= $category->lft*/?></td>
        <td><?/*= $category->rgt*/?></td>-->
        <td>
            <a href="<?= \yii\helpers\Url::to(['goods-category/edit','id'=>$category->id])?>" class="btn btn-default edit_btn">
                <span class="glyphicon glyphicon-pencil">修改</span>
            </a>
            <a href="javascript:;" class="btn btn-default del_btn">
                <span class="glyphicon glyphicon-trash">删除</span>
            </a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<a href="<?= \yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-info">添加分类</a>

<?php
/**
 * @var $this \yii\web\View
 */
$del_url = \yii\helpers\Url::to(['goods-category/del']);//保存ajax需要请求的地址
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
                alert('删除失败,该分类下面还有子分类');
            }
          });
      }
    });
JS
));
