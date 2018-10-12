<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12 0012
 * Time: 下午 10:44
 */

//var_dump($categories);exit;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textInput();
echo $form->field($model,'article_category_id')->dropDownList(
    \backend\models\ArticleCategory::getCategories(),['prompt'=>'=请选择文章分类=']
);
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'is_delete')->inline()->radioList([1=>'正常',0=>'隐藏']);

//>>>>>>>>>>>>>>>>>使用UEditor开始<<<<<<<<<<<<<<<<<<//
echo $form->field($detail,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '250',
        //设置语言
        'lang' =>'zh-cn',//英文为 en
    ]
]);
//编辑器相关配置参考
/*echo UEditor::widget([
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
        //定制菜单
        'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'fontsize',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                'forecolor', 'backcolor', '|',
                'lineheight', '|',
                'indent', '|'
            ],
        ]
    ]
]);*/
//>>>>>>>>>>>>>>>>>使用UEditor结束<<<<<<<<<<<<<<<<<<//

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();