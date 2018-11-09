<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="home" class="hero-area">

    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(/edusite/img/home-background.jpg)"></div>
    <!-- /Backgound Image -->
    <div class="home-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-9 title-top-banner">
                    <h1 class="white-text">Edusite Free Online Training Courses</h1>
                    <p class="lead white-text">Libris vivendo eloquentiam ex ius, nec id splendide abhorreant, eu pro alii error homero.</p>
                    <a class="main-button icon-button" href="#">Get Started!</a>
                </div>

                <div class="col-md-3 login-sec">
                    <h2 class="text-center white-text">Login Now</h2>
                    <form class="login-form" method="post" action="/site/login">
                        <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="text-uppercase white-text">Username</label>
                            <input type="text" name="LoginForm[username]" class="form-control" placeholder="">

                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1" class="text-uppercase white-text">Password</label>
                            <input type="password" name="LoginForm[password]" class="form-control" placeholder="">
                        </div>


                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input">
                                <small class="white-text">Remember Me</small>
                            </label>
                            <button type="submit" class="btn btn-login float-right white-text">Submit</button>
                        </div>

                    </form>
                    <div class="copy-text white-text">Created with <i class="fa fa-heart"></i> by <a href="http://grafreez.com">Grafreez.com</a></div>
                </div>

            </div>
        </div>
    </div>


</div>
