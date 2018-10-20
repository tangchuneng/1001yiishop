<?php
/* @var $this yii\web\View */

?>
<h3>商品列表</h3>
<!--搜索工具条-->
<form id="w0" class="form-inline" action="<?= \yii\helpers\Url::to(['goods/index'])?>" method="get" role="form">
<!--    <div class="form-group field-goodssearchform-name has-success">-->
        <input value="<?= $data['name']?>" type="text" id="goodssearchform-name" class="form-control" name="GoodsSearchForm[name]" placeholder="商品名" aria-invalid="false">
<!--    </div>-->
<!--    <div class="form-group field-goodssearchform-sn has-success">-->
        <input value="<?= $data['sn']?>" type="text" id="goodssearchform-sn" class="form-control" name="GoodsSearchForm[sn]" placeholder="货号" aria-invalid="false">
<!--    </div>-->
<!--    <div class="form-group field-goodssearchform-minprice has-success">-->
        <label class="sr-only" for="goodssearchform-minprice">-</label>
        <input value="<?= $data['minPrice']?>" type="text" id="goodssearchform-minprice" class="form-control" name="GoodsSearchForm[minPrice]" placeholder="￥" aria-invalid="false">
<!--    </div>-->
<!--    <div class="form-group field-goodssearchform-maxprice has-success">-->
        <label class="sr-only" for="goodssearchform-maxprice">-</label>
        <input value="<?= $data['maxPrice']?>" type="text" id="goodssearchform-maxprice" class="form-control" name="GoodsSearchForm[maxPrice]" placeholder="￥" aria-invalid="false">
<!--    </div>-->
    <button type="submit" class="btn btn-default">
        <span class="glyphicon glyphicon-search">搜索</span>
    </button>
</form>

<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>货号</th>
        <th>名称</th>
        <th>价格</th>
        <th>LOGO</th>
        <th>库存</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr data-id="<?= $model->id?>">
            <td><?= $model->id?></td>
            <td><?= $model->sn ?></td>
            <td><?= $model->name ?></td>
            <td><?= $model->shop_price ?></td>
            <td><img src="<?= $model->logo?>" height="50" /> </td>
            <td><?= $model->stock ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::to(['goods/gallery','id'=>$model->id]) ?>" class="btn btn-default exit_btn">
                    <span class="glyphicon glyphicon-picture">相册</span>
                </a>
                <a href="<?= \yii\helpers\Url::to(['goods/edit','id'=>$model->id]) ?>" class="btn btn-warning exit_btn">
                    <span class="glyphicon glyphicon-edit">修改</span>
                </a>
                <a href="javascript:;" class="btn btn-danger del_btn">
                    <span class="glyphicon glyphicon-trash">删除</span>
                </a>
                <a href="<?= \yii\helpers\Url::to(['goods/#','id'=>$model->id]) ?>" class="btn btn-success exit_btn">
                    <span class="glyphicon glyphicon-film">预览</span>
                </a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
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
$del_url = \yii\helpers\Url::to(['goods/del']);//保存ajax需要请求的地址
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