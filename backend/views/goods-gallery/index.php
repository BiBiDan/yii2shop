<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/webuploader-0.1.5/webuploader.css');
$this->registerJsFile('@web/webuploader-0.1.5/webuploader.js',[
        'depends'=>\yii\web\JqueryAsset::className()
]);
$url_upload_img = \yii\helpers\Url::to(['goods-gallery/upload-pic']);
$url_upload_pic = \yii\helpers\Url::to(['goods-gallery/ajax-add','id'=>$_GET['id']]);
$this->registerJs(
        <<<JS
        // 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '@web/webuploader-0.1.5/Uploader.swf',

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
    uploader.on( 'uploadSuccess', function( file ,response) {
        //支持多文件上传
        $.post('{$url_upload_pic}',response,function(v) {
          //支持多文件回显 自动生成html文件
          var html = "<tr><td><img src='"+v.url+"' width='100px'><a href='/goods-gallery/delete.html?id="+v.id+"' class='btn btn-info'>删除</a></td></tr>";
          $('#ajaxpic').append(html);
        },'json')
    });
JS
);


echo <<<HTML
    <!--dom结构部分-->
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
    </div>
HTML;

?>
<table class="table table-responsive">
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <thead id="ajaxpic"></thead>
    <?php foreach ($model as $value):?>
    <tr>
        <td>
            <img src="<?=$value->path?>" alt="" width="100px">
        </td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['goods-gallery/delete','id'=>$value->id])?>" class="btn btn-info">删除</a>
        </td>
    </tr>
        <?php endforeach;?>
</table>
