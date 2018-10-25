<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\mysql\modeldb\NewsRewrite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'categoryId') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'image') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'published') ?>

    <?php // echo $form->field($model, 'order') ?>

    <?php // echo $form->field($model, 'createDate') ?>

    <?php // echo $form->field($model, 'updateDate') ?>

    <?php // echo $form->field($model, 'createEmail') ?>

    <?php // echo $form->field($model, 'updateEmail') ?>

    <?php // echo $form->field($model, 'storeId') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'languageId') ?>

    <?php // echo $form->field($model, 'originId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
