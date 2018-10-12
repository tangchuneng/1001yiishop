<?php
use yii\web\JsExpression;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
//使用了ajax上传过后,就将传过来的文件名保存到这个隐藏域中,表单提交的时候就提交到控制器中
echo $form->field($model,'logo')->hiddenInput();

//>>>>>>>>>>>>>>>>uploadifive插件(Ajax上传,发起ajax请求)
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 100,
        'height' => 30,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传文件的路径写入logo字段的隐藏域
        $("#brand-logo").val(data.fileUrl);
        //回显图片
        $("#img").attr("src",data.fileUrl);
    }
}
EOF
        ),
    ]
]);
//>>>>>>>>>>>>>>>>uploadifive结束

//回显上传后的图片,增加用户体验
echo \yii\bootstrap\Html::img($model->logo,['id'=>'img','height'=>80]);
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'is_delete')->radioList(['隐藏','正常']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();