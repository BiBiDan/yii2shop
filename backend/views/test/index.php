<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>开始使用layui</title>
    <link rel="stylesheet" href="../layui/css/layui.css">
</head>
<body>

<!-- 你的HTML代码 -->

<script src="../layui/layui.js"></script>
<script>
    //一般直接写在一个js文件中
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer
            ,form = layui.form;

        layer.msg('Hello World');
    });
</script>
</body>
</html>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/layui-master/dist/css/layui.css');
$this->registerJsFile('@web/layui-master/dist/lay/modules/layer.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJs(
    <<<JS
 
JS
);