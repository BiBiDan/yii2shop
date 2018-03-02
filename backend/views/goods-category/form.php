<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->textInput();
/**********************************************************************************************/
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJs(
    <<<JS
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
                callback: {
                onClick: function(event, treeId, treeNode) {
                  //alert(treeNode.id);
                  $('#goodscategory-parent_id').val(treeNode.id)
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes =  {$nodes};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //将树状分类打开
       // var zTreeObj = $.fn.zTree.getZTreeObj("tree");
        //zTreeObj.expandAll(true);
        //默认选中
        zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->parent_id}", null));
JS
);
echo <<<HTML
        <div>
            <ul id="treeDemo" class="ztree"></ul>
        </div>
HTML;


/**********************************************************************************************/
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::button('提交',['type'=>'submit','class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();