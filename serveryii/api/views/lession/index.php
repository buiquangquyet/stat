<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\mysql\modeldb\LessionRewrite */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lessions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lession-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lession', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'course_id',
            'alias',
            'description:ntext',
            //'link_video',
            //'content:ntext',
            //'sugget_vocabulary',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
