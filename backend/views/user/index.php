<?php
/* @var $this yii\web\View */
?>
    <h3>用户列表</h3>
    <!--<a href="<?/*= \yii\helpers\Url::to(['user/add'])*/?>" class="btn btn-info">添加用户</a>-->
    <table class="table table-bordered table-responsive">
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>邮箱</th>
            <th>最后登录时间</th>
            <th>状态</th>
            <th width="200px">操作</th>
        </tr>
        <?php foreach ($model as $user):?>
            <tr data-id="<?= $user->id?>">
                <td><?= $user->id?></td>
                <td><?= $user->username ?></td>
                <td><?= $user->email ?></td>
                <td>
                    <?= $user->last_login_time?date('Y-m-d H:m:s',$user->last_login_time) : '暂无' ?>
                </td>
                <td><?= $user->status?'启用':'禁用' ?></td>
                <td>
                    <!--原始的修改方法-->
                    <a href="<?= \yii\helpers\Url::to(['user/edit','id'=>$user->id]) ?>" class="btn btn-default exit_btn">
                        <span class="glyphicon glyphicon-pencil">修改</span>
                    </a>
                    <!--使用ajax删除-->
                    <a href="javascript:;" class="btn btn-default del_btn">
                        <span class="glyphicon glyphicon-trash">删除</span>
                    </a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
    <!--<a href="<?/*= \yii\helpers\Url::to(['user/add'])*/?>" class="btn btn-info">添加用户</a>-->
<?php
/**
 * @var $this \yii\web\View
 */
$del_url = \yii\helpers\Url::to(['user/del']);//保存ajax需要请求的地址
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
