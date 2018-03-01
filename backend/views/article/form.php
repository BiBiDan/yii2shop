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
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\Article::getArticleCategoryName());
echo $form->field($model,'sort')->textInput();
echo $form->field($articleDetail,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions'=>[//编辑区域大小
                    'initialFrameHeight' => ' 200 ',
                        //设置语言
                    'lang' => 'zh-cn']
]);
echo "<button type='submit' class='btn btn-primary'>提交</button>";
\yii\bootstrap\ActiveForm::end();