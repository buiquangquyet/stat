<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\mysql\db\Lession */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lession-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= Html::activeDropDownList($model, 'course_id', ArrayHelper::map(\common\models\mysql\modeldb\CourseRewrite::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'link_video')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sugget_vocabulary')->textInput(['maxlength' => true]) ?>


    <?php echo $form->field($model, 'image')->fileInput() ?>
    <img src="<?=$model->image?>" alt="test"/>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
