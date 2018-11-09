<!--home-->
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
                    <form class="login-form" method="post" action="/auth/login">
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
<!-- /home -->

<!-- About -->
<div id="about" class="section">

    <!-- container -->
    <div class="container">

        <!-- row -->
        <div class="row">

            <div class="col-md-6">
                <div class="section-header">
                    <h2>Welcome to Edusite</h2>
                    <p class="lead">Libris vivendo eloquentiam ex ius, nec id splendide abhorreant.</p>
                </div>

                <!-- feature -->
                <div class="feature">
                    <i class="feature-icon fa fa-flask"></i>
                    <div class="feature-content">
                        <h4>Online Courses </h4>
                        <p>Ceteros fuisset mei no, soleat epicurei adipiscing ne vis. Et his suas veniam nominati.</p>
                    </div>
                </div>
                <!-- /feature -->

                <!-- feature -->
                <div class="feature">
                    <i class="feature-icon fa fa-users"></i>
                    <div class="feature-content">
                        <h4>Expert Teachers</h4>
                        <p>Ceteros fuisset mei no, soleat epicurei adipiscing ne vis. Et his suas veniam nominati.</p>
                    </div>
                </div>
                <!-- /feature -->

                <!-- feature -->
                <div class="feature">
                    <i class="feature-icon fa fa-comments"></i>
                    <div class="feature-content">
                        <h4>Community</h4>
                        <p>Ceteros fuisset mei no, soleat epicurei adipiscing ne vis. Et his suas veniam nominati.</p>
                    </div>
                </div>
                <!-- /feature -->

            </div>

            <div class="col-md-6">
                <div class="about-img">
                    <img src="/edusite/img/about.png" alt="">
                </div>
            </div>

        </div>
        <!-- row -->

    </div>
    <!-- container -->
</div>
<!-- /About -->

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

                <!-- single course -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="#" class="course-img">
                            <img src="/edusite/img/course01.jpg" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="#">Beginner to Pro in Excel: Financial Modeling and Valuation</a>
                        <div class="course-details">
                            <span class="course-category">Business</span>
                            <span class="course-price course-free">Free</span>
                        </div>
                    </div>
                </div>
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

<!-- Call To Action -->
<div id="cta" class="section">

    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(/edusite/img/cta1-background.jpg)"></div>
    <!-- /Backgound Image -->

    <!-- container -->
    <div class="container">

        <!-- row -->
        <div class="row">

            <div class="col-md-6">
                <h2 class="white-text">Ceteros fuisset mei no, soleat epicurei adipiscing ne vis.</h2>
                <p class="lead white-text">Ceteros fuisset mei no, soleat epicurei adipiscing ne vis. Et his suas veniam nominati.</p>
                <a class="main-button icon-button" href="#">Get Started!</a>
            </div>

        </div>
        <!-- /row -->

    </div>
    <!-- /container -->

</div>
<!-- /Call To Action -->

<!-- Why us -->
<div id="why-us" class="section">

    <!-- container -->
    <div class="container">

        <!-- row -->
        <div class="row">
            <div class="section-header text-center">
                <h2>Why Edusite</h2>
                <p class="lead">Libris vivendo eloquentiam ex ius, nec id splendide abhorreant.</p>
            </div>

            <!-- feature -->
            <div class="col-md-4">
                <div class="feature">
                    <i class="feature-icon fa fa-flask"></i>
                    <div class="feature-content">
                        <h4>Online Courses</h4>
                        <p>Ceteros fuisset mei no, soleat epicurei adipiscing ne vis. Et his suas veniam nominati.</p>
                    </div>
                </div>
            </div>
            <!-- /feature -->

            <!-- feature -->
            <div class="col-md-4">
                <div class="feature">
                    <i class="feature-icon fa fa-users"></i>
                    <div class="feature-content">
                        <h4>Expert Teachers</h4>
                        <p>Ceteros fuisset mei no, soleat epicurei adipiscing ne vis. Et his suas veniam nominati.</p>
                    </div>
                </div>
            </div>
            <!-- /feature -->

            <!-- feature -->
            <div class="col-md-4">
                <div class="feature">
                    <i class="feature-icon fa fa-comments"></i>
                    <div class="feature-content">
                        <h4>Community</h4>
                        <p>Ceteros fuisset mei no, soleat epicurei adipiscing ne vis. Et his suas veniam nominati.</p>
                    </div>
                </div>
            </div>
            <!-- /feature -->

        </div>
        <!-- /row -->

        <hr class="section-hr">

        <!-- row -->
        <div class="row">

            <div class="col-md-6">
                <h3>Persius imperdiet incorrupte et qui, munere nusquam et nec.</h3>
                <p class="lead">Libris vivendo eloquentiam ex ius, nec id splendide abhorreant.</p>
                <p>No vel facete sententiae, quodsi dolores no quo, pri ex tamquam interesset necessitatibus. Te denique cotidieque delicatissimi sed. Eu doming epicurei duo. Sit ea perfecto deseruisse theophrastus. At sed malis hendrerit, elitr deseruisse in sit, sit ei facilisi mediocrem.</p>
            </div>

            <div class="col-md-5 col-md-offset-1">
                <a class="about-video" href="#">
                    <img src="/edusite/img/about-video.jpg" alt="">
                    <i class="play-icon fa fa-play"></i>
                </a>
            </div>

        </div>
        <!-- /row -->

    </div>
    <!-- /container -->

</div>
<!-- /Why us -->

<!-- Contact CTA -->
<div id="contact-cta" class="section">

    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(/edusite/img/cta2-background.jpg)"></div>
    <!-- Backgound Image -->

    <!-- container -->
    <div class="container">

        <!-- row -->
        <div class="row">

            <div class="col-md-8 col-md-offset-2 text-center">
                <h2 class="white-text">Contact Us</h2>
                <p class="lead white-text">Libris vivendo eloquentiam ex ius, nec id splendide abhorreant.</p>
                <a class="main-button icon-button" href="#">Contact Us Now</a>
            </div>

        </div>
        <!-- /row -->

    </div>
    <!-- /container -->

</div>
<!-- /Contact CTA -->
