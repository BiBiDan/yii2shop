
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username')->textInput(['readonly'=>true]);
echo $form->field($model,'oldPwd')->passwordInput();
echo $form->field($model,'newPwd')->passwordInput();
echo $form->field($model,'rePwd')->passwordInput();
echo \yii\helpers\Html::button('确认修改',['type'=>'submit','class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();