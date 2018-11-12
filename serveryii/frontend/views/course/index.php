<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 09/11/2018
 * Time: 11:43
 */
use common\components\TextUtility;

?>
<div id="page-header">
    <div class="container clearfix">
        <h3 class="mb-0 float-md-left">
            Products List
        </h3>
        <!-- Page header breadcrumb -->
        <nav class="breadcrumb float-md-right"> <a class="breadcrumb-item" href="index.html">Home</a> <a class="breadcrumb-item" href="shop.html">Shop Homepage</a> <span class="breadcrumb-item active">Products List</span> </nav>
    </div>
</div>

<div class="container">
    <div class="col-lg-9 order-lg-2">
        <div class="row">
            <hr class="my-4">
            <?php if(!empty($listLession)){ foreach ($listLession as $key=>$value){?>
                <div class="col-lg-12">
                    <!-- Product 2 -->
                    <div class="card product-card mb-4">
                        <!-- Ribbon -->
                        <div class="card-ribbon card-ribbon-bottom card-ribbon-right bg-primary text-white">Chương I</div>
                        <!-- Content -->
                        <div class="card-body p-3 pos-relative row">
                            <!-- Image content -->
                            <div class="col-md-4 mb-2 mb-md-0">
                                <img class="rounded img-fluid" src="/<?=$value->image?>" alt="<?=$value->name?>">
                            </div>
                            <!-- Product details -->
                            <div class="col-md-8 d-flex flex-column align-items-start">
                                <p class="text-muted text-uppercase text-xs mb-0"><span class="text-primary">Womens</span> / shoes</p>
                                <h4 class="card-title mb-2">
                                    <a href="/lession/<?=TextUtility::alias($value->name)?>-<?=$value->id?>.html" class="text-grey-dark"><?=$value->name?></a>
                                </h4>

                                <p class="pos-md-absolute pos-t pos-r mr-3 text-md-right">
                                    <i class="fa fa-star text-primary"></i>
                                    <i class="fa fa-star text-primary"></i>
                                    <i class="fa fa-star text-primary"></i>
                                    <i class="fa fa-star text-primary"></i>
                                    <i class="fa fa-star-o text-primary"></i>
                                </p>
                                <p class="text-muted text-xs"><?=$value->description?></p>
                                <div class="mt-auto">
                                    <a href="shop-cart.html" class="btn btn-primary btn-sm"> Detail</a>
                                    <a href="#" class="btn btn-link btn-sm"><i class="fa fa-heart"></i> Add to Wishlist</a>
                                    <p class="text-muted text-xs d-none d-lg-inline">129 in stock</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } }?>

        </div>
    </div>
</div>
