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
        <th>名称</th>
        <th>简介</th>
        <th>logo</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?= $model->name ?></td>
        <td><?= $model->intro ?></td>
        <td><img src="<?= $model->logo?>" height="50" /> </td>
        <td><?= $model->sort ?></td>
        <td><?= $model->is_delete ?></td>
        <td><a href="<?= \yii\helpers\Url::to(['brand/edit','id'=>$model->id]) ?>">修改</a> </td>
    </tr>
    <?php endforeach;?>
</table>
<a href="<?= \yii\helpers\Url::to(['brand/add'])?>" class="btn btn-info">添加品牌</a>