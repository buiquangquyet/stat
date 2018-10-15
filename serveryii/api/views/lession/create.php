<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\mysql\db\Lession */

$this->title = 'Create Lession';
$this->params['breadcrumbs'][] = ['label' => 'Lessions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lession-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
