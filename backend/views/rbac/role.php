<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'role')->textInput();
echo $form->field($model,'desName')->textInput();
echo $form->field($model,'permission',['inline'=>1])->checkboxList(\backend\models\Role::getPermission());
echo \yii\bootstrap\Html::button('提交',['type'=>'submit','class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();