<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12 0012
 * Time: 下午 1:34
 */

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'user')->dropDownList(
    \backend\models\User::getUsers(),['prompt'=>'=请选择用户=']
);
echo $form->field($model,'roles')->inline(true)->checkboxList(
    \backend\models\RoleForm::getRolesItem()
);

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();