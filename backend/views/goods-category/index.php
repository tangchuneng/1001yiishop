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
    <tr>
        <td><?= $category->id?></td>
        <td><?= $category->depth.'_'.$category->name?></td>
        <td><?= $category->intro?></td>
        <!--<td><?/*= $category->tree*/?></td>
        <td><?/*= $category->parent_id*/?></td>
        <td><?/*= $category->lft*/?></td>
        <td><?/*= $category->rgt*/?></td>-->
        <td>
            <a href="<?= \yii\helpers\Url::to(['goods-category/edit','id'=>$category->id])?>" class="btn btn-default edit_btn">
                <span class="glyphicon glyphicon-pencil"></span>
            </a>
            <a href="<?= \yii\helpers\Url::to(['goods-category/del','id'=>$category->id])?>" class="btn btn-default del_btn">
                <span class="glyphicon glyphicon-trash"></span>
            </a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<a href="<?= \yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-info">添加分类</a>