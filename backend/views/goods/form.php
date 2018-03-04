<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'logo')->hiddenInput();
//=============================================================================================================//
//js代码块 用户webUpload上传图片 并通过Ajax回显
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/webuploader-0.1.5/webuploader.css');
$this->registerJsFile('@web/webuploader-0.1.5/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className()//解决依赖
]);
$url_upload_img = \yii\helpers\Url::to(['goods/upload-logo']);
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
    // 文件上传成功，回显图片
    uploader.on( 'uploadSuccess', function( file,response ) {
        var img = response.url;
        //将图片路径赋值给logo输入框
        $('#goods-logo').val(img);
        $('#data-img').attr('src',img);
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
echo "<img id='data-img' width='100px'>";
//=============================================================================================================//
echo $form->field($model,'goods_category_id')->hiddenInput();
//=============================================================================================================//
//树状商品分类
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJs(
    <<<JS
    var zTreeObj;
   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
       	data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "parent_id",
			rootPId: 0
		}
	},
	//点击事件获取用户选择的分类
	    callback: {
		    onClick: function(event, treeId, treeNode) {
		      $('#goods-goods_category_id').val(treeNode.id)
		    }
	    }
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$category};
      zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    //默认选中
      zTreeObj.selectNode(zTreeObj.getNodeByParam('id',"{$model->goods_category_id}",null))
   
JS
);
echo <<<HTML
    <div>
       <ul id="treeDemo" class="ztree"></ul>
    </div>
HTML;

//=============================================================================================================//
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::getBrandName());
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList(['上线','下线']);
echo $form->field($model,'sort')->textInput();
echo $form->field($modelContent,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions'=>[//编辑区域大小
        'initialFrameHeight' => ' 200 ',
        //设置语言
        'lang' => 'zh-cn']
]);
echo \yii\bootstrap\Html::button('提交',['type'=>'submit','class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
