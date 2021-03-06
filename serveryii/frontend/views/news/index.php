<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\mysql\modeldb\NewsRewrite */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create News', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'image',
            'name',
            //'description',
            //'content:ntext',
            //'published',
            //'order',
            //'createDate',
            //'updateDate',
            //'createEmail:email',
            //'updateEmail:email',
            //'storeId',
            //'type',
            //'languageId',
            //'originId',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
