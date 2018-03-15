<?php
echo <<<HTML
<h1>添加权限</h1>
HTML;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'des')->textInput();
echo \yii\bootstrap\Html::button('提交',['type'=>'submit','class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();