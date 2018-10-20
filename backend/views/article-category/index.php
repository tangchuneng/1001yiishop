
<table class="table table-bordered table-responsive">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($categories as $category):?>
    <tr data-id="<?= $category->id?>">
        <td><?= $category->name?></td>
        <td><?= $category->intro?></td>
        <td width="250px">
            <!--原始的修改方法-->
            <a href="<?= \yii\helpers\Url::to(['article-category/edit','id'=>$category->id]) ?>" class="btn btn-success exit_btn">
                <span class="glyphicon glyphicon-pencil">修改</span>
            </a>
            <!--使用ajax删除-->
            <a href="javascript:;" class="btn btn-danger del_btn">
                <span class="glyphicon glyphicon-trash">删除</span>
            </a>
        </td>
    </tr>
    <?php endforeach;?>
</table>

<?php
/**
 * @var $this \yii\web\View
 */
$del_url = \yii\helpers\Url::to(['article-category/del']);//保存ajax需要请求的地址
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