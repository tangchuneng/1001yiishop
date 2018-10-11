<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/8 0008
 * Time: 下午 7:28
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'email')->textInput(['type'=>'email']);
echo $form->field($model,'status')->radioList([10=>'正常',0=>'隐藏']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();