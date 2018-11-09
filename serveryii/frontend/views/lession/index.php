<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 09/11/2018
 * Time: 15:46
 */
?>
<div class="hero-area section">

    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(/edusite/img/blog-post-background.jpg)"></div>
    <!-- /Backgound Image -->

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <ul class="hero-area-tree">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="blog.html">Blog</a></li>
                    <li>How to Get Started in Photography</li>
                </ul>
                <h1 class="white-text">How to Get Started in Photography</h1>
                <ul class="blog-post-meta">
                    <li class="blog-meta-author">By : <a href="#">John Doe</a></li>
                    <li>18 Oct, 2017</li>
                    <li class="blog-meta-comments"><a href="#"><i class="fa fa-comments"></i> 35</a></li>
                </ul>
            </div>
        </div>
    </div>

</div>

<div class="content">
    <div class="container">
        <div class="row">
            <h3><?=$lession->name?></h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?=$lession->link_video?>
            </div>
            <div class="col-md-6">
            <?=$lession->description ?>
            </div>
        </div>
    </div>
</div>

