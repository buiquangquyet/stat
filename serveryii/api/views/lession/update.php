<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\mysql\db\Lession */

$this->title = 'Update Lession: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lessions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lession-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
