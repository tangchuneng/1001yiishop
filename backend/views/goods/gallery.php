
<h3>商品相册</h3>
<?php
use yii\web\JsExpression;
//>>>>>>>>>>>>>>>>uploadifive插件处理上传的logo(Ajax上传,发起ajax请求),开始
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['goods_id' => $goods->id],
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
                //回显图片
                var html = '<tr data-id="'+data.imgId+'"><td><img src="'+data.fileUrl+'" width="800"></td>';
                html += '<td><a href="javascript:;" class="btn btn-danger del_btn">删除</a></td></tr>';
                $(html).appendTo("table");
                }
            }
EOF
        ),
    ]
]);

//echo \yii\bootstrap\Html::img($model->logo,['id'=>'img','height'=>80]);//回显上传后的图片,增加用户体验
//>>>>>>>>>>>>>>>>uploadifive插件处理上传的logo(Ajax上传,发起ajax请求),结束

?>
<table class="table">
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods->galleries as $gallery):?>
        <tr data-id="<?=$gallery->id?>">
            <td><img src="<?=$gallery->path?>" width="800"></td>
            <td><a href="javascript:;" class="btn btn-danger del_btn">删除</a></td>
        </tr>
    <?php endforeach;?>
</table>

<?php
$url = \yii\helpers\Url::to(['goods/del-gallery']);
$this->registerJs(new JsExpression(
        <<<JS
$("table").on('click',".del_btn",function(){
        var tr = $(this).closest('tr');
        var id = tr.attr('data-id');
        $.post("{$url}",{id:id},function () {
            tr.fadeOut();
        })
    });
JS

));
