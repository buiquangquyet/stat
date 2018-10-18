<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\mysql\db\Dictionary */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dictionary-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'word')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pronunciation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mean')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'image')->fileInput() ?>
    <?php //echo $form->field($model, 'image')->textInput(['maxlength' => true])->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'sentence')->textarea(['rows' => 6]) ?>

    <?php // echo $form->field($model, 'creat_time')->textInput() ?>

    <?php // echo $form->field($model, 'send_time')->textInput() ?>

    <?php // echo $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
