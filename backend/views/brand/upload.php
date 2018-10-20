<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/16 0016
 * Time: 上午 11:03
 */
$form = \yii\bootstrap\ActiveForm::begin();
//echo $form->field($model,'name')->textInput();
echo $form->field($model,'file')->fileInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();