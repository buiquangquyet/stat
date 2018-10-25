<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\mysql\modeldb\DictionaryRewrite */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dictionaries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dictionary-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dictionary', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'word',
            'pronunciation',
            'mean',
            'image',
            //'sentence:ntext',
            //'creat_time',
            //'send_time',
            //'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <form action="/dictionary/index" method="get" id="signup-form">
        <input type="submit" value="submit" name="submit">
    </form>

    <script>

        ga('myTracker.require', 'displayfeatures', {
            cookieName: 'display_features_cookie'
        });

//        var form = document.getElementById('signup-form');
//        console.log(form);
//
//        // Adds a listener for the "submit" event.
//        form.addEventListener('submit', function(event) {
//
//            // Prevents the browser from submitting the form
//            // and thus unloading the current page.
//            event.preventDefault();
//
//            // Creates a timeout to call `submitForm` after one second.
//            setTimeout(submitForm, 3000);
//
//            // Keeps track of whether or not the form has been submitted.
//            // This prevents the form from being submitted twice in cases
//            // where `hitCallback` fires normally.
//            var formSubmitted = false;
//
//            function submitForm() {
//                if (!formSubmitted) {
//                    formSubmitted = true;
//                    //form.submit();
//                }
//            }
//
//            // Sends the event to Google Analytics and
//            // resubmits the form once the hit is done.
//            ga('send', 'event', 'Signup Form', 'submit', {
//                hitCallback: submitForm
//            });
//        });


    </script>
</div>
