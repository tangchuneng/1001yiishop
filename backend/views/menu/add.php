<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/11 0011
 * Time: 下午 8:05
 */
$menu_list = \backend\models\Menu::getAll();
$url_list = [];
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList(
    $menu_list,['prompt'=>'=请选择上级菜单=']
);
echo $form->field($model,'url')->dropDownList(
    $url_list,['prompt'=>'=请选路由=']
);
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();