<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 下午 9:56
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea(['rows'=>'5']);
echo $form->field($model,'article_category_id')->dropDownList(\app\models\Article::getArticleCategoryName());
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'content')->textarea(['rows'=>'10']);
echo "<button type='submit' class='btn btn-primary'>提交</button>";
\yii\bootstrap\ActiveForm::end();