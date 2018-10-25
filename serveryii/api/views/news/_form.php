<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\mysql\db\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo$form->field($model, 'categoryId')->textInput() ?>

    <?php //echo$form->field($model, 'userId')->textInput() ?>

    <?php //echo$form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?php echo$form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 10],
        'preset' => 'full'
    ]) ?>


    <?php //echo$form->field($model, 'published')->textInput() ?>

    <?php //echo$form->field($model, 'order')->textInput() ?>

    <?php //echo$form->field($model, 'createDate')->textInput() ?>

    <?php //echo$form->field($model, 'updateDate')->textInput() ?>

    <?php //echo$form->field($model, 'createEmail')->textInput(['maxlength' => true]) ?>

    <?php //echo$form->field($model, 'updateEmail')->textInput(['maxlength' => true]) ?>

    <?php //echo$form->field($model, 'storeId')->textInput() ?>

    <?php //echo$form->field($model, 'type')->textInput() ?>

    <?php //echo$form->field($model, 'languageId')->textInput() ?>

    <?php //echo$form->field($model, 'originId')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
