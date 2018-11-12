
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
                                    <img src="<?=$value->image?>" alt="<?=$value->name?>">
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
