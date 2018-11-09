
<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 09/11/2018
 * Time: 11:02
 */
use common\components\TextUtility;

?>

<!-- Courses -->
<div id="courses" class="section">

    <!-- container -->
    <div class="container">

        <!-- row -->
        <div class="row">
            <div class="section-header text-center">
                <h2>Explore Courses</h2>
                <p class="lead">Libris vivendo eloquentiam ex ius, nec id splendide abhorreant.</p>
            </div>
        </div>
        <!-- /row -->

        <!-- courses -->
        <div id="courses-wrapper">

            <!-- row -->
            <div class="row">
                <?php
                if(!empty($Courses)){
                    foreach ($Courses as $key=>$value){?>
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="course">
                                <a href="/course/<?= TextUtility::alias($value->name)?>-<?=$value->id?>.html" class="course-img">
                                    <img src="/edusite/img/course01.jpg" alt="">
                                    <i class="course-link-icon fa fa-link"></i>
                                </a>
                                <a class="course-title" href="/course/<?= TextUtility::alias($value->name)?>-<?=$value->id?>.html">
                                    <?= $value->name?>
                                </a>
                                <p><?=$value->description?></p>
                                <div class="course-details">
                                    <span class="course-category">Business</span>
                                    <span class="course-price course-free">Free</span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <!-- single course -->

                <!-- /single course -->

                <!-- single course -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="#" class="course-img">
                            <img src="/edusite/img/course02.jpg" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="#">Introduction to CSS </a>
                        <div class="course-details">
                            <span class="course-category">Web Design</span>
                            <span class="course-price course-premium">Premium</span>
                        </div>
                    </div>
                </div>
                <!-- /single course -->

                <!-- single course -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="#" class="course-img">
                            <img src="/edusite/img/course03.jpg" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="#">The Ultimate Drawing Course | From Beginner To Advanced</a>
                        <div class="course-details">
                            <span class="course-category">Drawing</span>
                            <span class="course-price course-premium">Premium</span>
                        </div>
                    </div>
                </div>
                <!-- /single course -->

                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="#" class="course-img">
                            <img src="/edusite/img/course04.jpg" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="#">The Complete Web Development Course</a>
                        <div class="course-details">
                            <span class="course-category">Web Development</span>
                            <span class="course-price course-free">Free</span>
                        </div>
                    </div>
                </div>
                <!-- /single course -->

            </div>
            <!-- /row -->

            <!-- row -->
            <div class="row">

                <!-- single course -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="#" class="course-img">
                            <img src="/edusite/img/course05.jpg" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="#">PHP Tips, Tricks, and Techniques</a>
                        <div class="course-details">
                            <span class="course-category">Web Development</span>
                            <span class="course-price course-free">Free</span>
                        </div>
                    </div>
                </div>
                <!-- /single course -->

                <!-- single course -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="#" class="course-img">
                            <img src="/edusite/img/course06.jpg" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="#">All You Need To Know About Web Design</a>
                        <div class="course-details">
                            <span class="course-category">Web Design</span>
                            <span class="course-price course-free">Free</span>
                        </div>
                    </div>
                </div>
                <!-- /single course -->

                <!-- single course -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="#" class="course-img">
                            <img src="/edusite/img/course07.jpg" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="#">How to Get Started in Photography</a>
                        <div class="course-details">
                            <span class="course-category">Photography</span>
                            <span class="course-price course-free">Free</span>
                        </div>
                    </div>
                </div>
                <!-- /single course -->


                <!-- single course -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="#" class="course-img">
                            <img src="/edusite/img/course08.jpg" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="#">Typography From A to Z</a>
                        <div class="course-details">
                            <span class="course-category">Typography</span>
                            <span class="course-price course-free">Free</span>
                        </div>
                    </div>
                </div>
                <!-- /single course -->

            </div>
            <!-- /row -->

        </div>
        <!-- /courses -->

        <div class="row">
            <div class="center-btn">
                <a class="main-button icon-button" href="#">More Courses</a>
            </div>
        </div>

    </div>
    <!-- container -->

</div>
<!-- /Courses -->
