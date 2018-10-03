<?php

?>
<a href="<?= \yii\helpers\Url::to(['article/add-category'])?>" class="btn btn-info">添加分类</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($categorys as $category):?>
    <tr>
        <td><?= $category->name?></td>
        <td><?= $category->intro?></td>
        <td><?= $category->sort?></td>
        <td><?= $category->is_delete?></td>
        <td>
            <a href="<?= \yii\helpers\Url::to(['article/edit-category','id'=>$category->id])?>">修改</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<a href="<?= \yii\helpers\Url::to(['article/add-category'])?>" class="btn btn-info">添加分类</a>
