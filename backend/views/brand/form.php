<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 下午 5:17
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
//------------------------------------------------------------------------//
/**
 * @var $this \yii\web\View
 */
echo $this->registerCssFile('/webuploader-0.1.5/webuploader.css');
echo $this->registerJsFile('/webuploader-0.1.5/webuploader.js',[
    //解决依赖
    'depends'=>\yii\web\JqueryAsset::className()
]);
echo <<<HTML
    <!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
HTML;
$url_upload_img = \yii\helpers\Url::to(['brand/upload-logo']);
$this->registerJs(
    <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/webuploader-0.1.5/Uploader.swf',

    // 文件接收服务端。
    server: '{$url_upload_img}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png'
        //mimeTypes: 'image/*'
    }
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file , response ) {
    //console.log(response);
    var imgurl = response.url;
    //将路劲赋值给logo字段输入框
    $('#brand-logo').val(imgurl);
    //图片回显
    $('#logo_img').attr('src',imgurl);
});
JS
);
echo '<img id="logo_img">';
//------------------------------------------------------------------------//
echo $form->field($model,'sort')->textInput();
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();