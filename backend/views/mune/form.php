<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Mune::showParent(),['prompt'=>'==菜单分类列表==']);
echo $form->field($model,'url_to')->dropDownList(\backend\models\Mune::showUrl(),['prompt'=>'==请选择路由==']);
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::button('提交',['type'=>'submit','class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();