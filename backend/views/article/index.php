<?php
/* @var $this yii\web\View */
?>
<h3>文章列表</h3>
<!--搜索工具条-->
<form id="w0" class="form-inline" action="<?= \yii\helpers\Url::to(['article/index'])?>" method="get" role="form">
    <input value="<?= $data['name']?>" type="text" id="articlesearchform-name" class="form-control" name="articleSearchForm[name]" placeholder="文章名称" aria-invalid="false">
    <input value="<?= $data['intro']?>" type="text" id="articlesearchform-sn" class="form-control" name="articleSearchForm[intro]" placeholder="文章简介" aria-invalid="false">
    <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search">搜索</span>
    </button>
</form>

<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $article):?>
        <tr data-id="<?= $article->id?>">
            <td><?= $article->id ?></td>
            <td><?= $article->name ?></td>
            <td><?= date('Y-m-d H:m:s',$article->create_time) ?></td>
            <td width="250">
                <!--原始的修改方法-->
                <a href="<?= \yii\helpers\Url::to(['article/edit','id'=>$article->id]) ?>" class="btn btn-success exit_btn">
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
<!--<a href="<?/*= \yii\helpers\Url::to(['brand/add'])*/?>" class="btn btn-info">添加品牌</a>-->
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination' => $pager,
    'nextPageLabel' => '下一页',
    'prevPageLabel' => '上一页'
]);

/**
 * @var $this \yii\web\View
 */
$del_url = \yii\helpers\Url::to(['article/del']);//保存ajax需要请求的地址
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