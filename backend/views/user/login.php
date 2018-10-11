<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/9 0009
 * Time: 下午 12:13
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'remember')->checkbox();
echo $form->field($model,'captcha')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'user/captcha',//修改为自定义的验证码显示规则
    'template'=>
    '<div class="row">
        <div class="col-lg-1">{image}</div>
        <div class="col-lg-1">{input}</div>
    </div>'
]);
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();