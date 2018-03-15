<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'role',['inline'=>1])->checkboxList(\backend\models\Admin::getRoles());
echo \yii\helpers\Html::button('提交',['type'=>'submit','class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();