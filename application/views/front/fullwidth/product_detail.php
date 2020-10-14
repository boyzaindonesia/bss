<?php include('header.php'); ?>

<?php $product_brand = get_product_brand($product->product_brand_id); ?>

    <!-- Page Content Wraper -->
    <div class="page-content-wraper">
        <!-- Bread Crumb -->
        <section class="breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="breadcrumb-link">
                            <a href="<?php echo base_url()?>">Home</a>
                            <a href="<?php echo base_url().$product->url_product_category ?>"><?php echo $product->product_category_name; ?></a>
                            <span><?php echo $product->product_name; ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!-- Bread Crumb -->

        <!-- Page Content -->
        <section id="product" class="content-page single-product-content">

            <!-- Product -->
            <div id="product-detail" class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-12 product-content sidebar-position-right">
                        <form class="form-cart cart-item cart-item-<?php echo $product->product_id ?>" data-id="<?php echo $product->product_id ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Product Image -->
                            <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
                                <div class="product-page-image">
                                    <!-- Slick Image Slider -->
                                    <div class="product-image-slider product-image-gallery" id="product-image-gallery" data-pswp-uid="3">
                                        <?php foreach ($product->product_images as $key => $val) { ?>
                                        <div class="item">
                                            <a class="product-gallery-item" href="<?php echo get_image(base_url()."assets/collections/product/large/".$val->images, 'no_image_product.jpg');?>" data-size="" data-med="<?php echo get_image(base_url()."assets/collections/product/large/".$val->images, 'no_image_product.jpg');?>" data-med-size="">
                                                <img src="<?php echo get_image(base_url()."assets/collections/product/large/".$val->images, 'no_image_product.jpg');?>" alt="image <?php echo ($key + 1) ?>" />
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <!-- End Slick Image Slider -->

                                    <a href="javascript:void(0)" id="zoom-images-button" class="zoom-images-button"><i class="fa fa-expand" aria-hidden="true"></i></a>

                                </div>

                                <!-- Slick Thumb Slider -->
                                <div class="product-image-slider-thumbnails">
                                    <?php foreach ($product->product_images as $key => $val) { ?>
                                    <div class="item">
                                        <img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".$val->images, 'no_image_product.jpg');?>" alt="image <?php echo ($key + 1) ?>" />
                                    </div>
                                    <?php } ?>
                                </div>
                                <!-- End Slick Thumb Slider -->
                            </div>
                            <!-- End Product Image -->

                            <!-- Product Content -->
                            <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
                                <div class="product-page-content">
                                    <h2 class="product-title"><?php echo $product->product_name; ?></h2>
                                    <div class="product-rating">
                                        <div class="star-rating" itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating" title="Rated <?php echo $product->product_rating; ?> out of 5">
                                            <span style="width: <?php echo calcPercent($product->product_rating, 5) ?>%;"></span>
                                        </div>
                                        <div class="product-rating-count">( <span class="count"><?php echo $product->product_review; ?></span> Reviews )</div>
                                    </div>
                                    <div class="product-price">
                                        <?php
                                        if($product->product_price_discount > 0){
                                            echo '<del>'.convertRp($product->product_price_sale).'</del>';
                                            echo '<span><span class="product-price-text">'.convertRp($product->product_price_discount).'</span></span>';
                                        } else {
                                            echo '<span><span class="product-price-text cart-price-span">'.convertRp($product->product_price_sale).'</span></span>';
                                        } ?>
                                    </div>

                                    <?php if($product->product_price_discount == 0 && $product->product_price_grosir != '' && $product->product_price_grosir != '[]'){ ?>
                                    <div class="product-price-grosir">
                                        <span class="product-price-grosir-container">
                                            <span class="badge">GROSIR</span>
                                            <span class="product-price-grosir-btn">Beli Banyak Lebih Murah</span>
                                            <div class="table-product-price-grosir">
                                                <table class="table">
                                                    <colgroup>
                                                        <col>
                                                        <col>
                                                    </colgroup>
                                                    <thead>
                                                        <tr>
                                                            <th><strong>Kuantitas</strong></th>
                                                            <th><strong>Harga Satuan</strong></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $product_price_grosir = json_decode($product->product_price_grosir);
                                                        if(is_array($product->product_price_grosir)){ $product_price_grosir = $product->product_price_grosir; }
                                                        foreach ($product_price_grosir as $key2 => $val2) { ?>
                                                        <tr>
                                                            <td class="text-left"><?php echo $val2->name ?> item</td>
                                                            <td class="text-left"><?php echo convertRP($val2->price) ?></td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </span>
                                    </div>
                                    <?php } ?>

                                    <div class="product-meta">
                                        <span>SKU : <span class="sku" itemprop="sku"><?php echo $product->product_code ?></span></span>
                                        <span>Status : <span class="status" itemprop="status"><?php echo $product->product_status_name ?></span></span>
                                        <span>Kategori : <span class="category" itemprop="category"><a href="<?php echo base_url().$product->url_product_category; ?>"><?php echo $product->product_category_name ?></a></span></span>
                                        <span>Brand : <span class="brand" itemprop="brand"><a href="<?php echo base_url().'brand/'.$product_brand->url; ?>"><?php echo $product->product_brand_name ?></a></span></span>
                                    </div>
                                    <p class="product-description"><?php echo getFirstParaLg($val->product_description); ?></p>

                                    <?php if($product->product_status_id != 3 && $product->product_stock_detail != "" && $product->product_stock_detail != "[]"){ ?>
                                    <div class="row product-filters">
                                        <div class="col-md-12 filters-varian">
                                            <label for="select-varian">Pilih Variasi</label>
                                            <div class="varian-selector">
                                                <?php
                                                $i = 0;
                                                $cart_varian_id  = "";
                                                $cart_varian_qty = "";
                                                $product_stock_detail = json_decode($product->product_stock_detail);
                                                if(is_array($product->product_stock_detail)){ $product_stock_detail = $product->product_stock_detail; }
                                                foreach ($product_stock_detail as $key2 => $val2) {
                                                    if($val2->qty > 0 && $val2->status == 1){
                                                        if($i == 0){ $cart_varian_id = $val2->id; $cart_varian_qty = $val2->qty; }
                                                    ?>
                                                <div class="entry <?php echo ($i==0?'active':'') ?>" data-id="<?php echo $val2->id ?>" data-qty="<?php echo $val2->qty ?>"><?php echo $val2->name ?></div>
                                                <?php
                                                    $i += 1;
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <input type="hidden" name="cart-varian" value="<?php echo $cart_varian_id ?>">
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <?php if($product->product_status_id != 3){ ?>
                                    <div class="product-qty-stock">
                                        Stok tersedia: <span class="qty"><?php echo $cart_varian_qty ?></span>
                                    </div>
                                    <div class="single-variation-wrap">
                                        <?php if($this->jCfg['member']['member_login']==0){ ?>
                                        <div class="label text-danger mb-10"><em>Kamu harus Login dahulu untuk membeli produk ini.</em></div>
                                        <?php } ?>
                                        <div class="product-quantity">
                                            <span data-value="+" class="quantity-btn quantityPlus"></span>
                                            <input class="quantity input-lg cart-qty" step="1" min="1" max="<?php echo $cart_varian_qty ?>" name="quantity" value="1" title="Quantity" type="number" />
                                            <span data-value="-" class="quantity-btn quantityMinus"></span>
                                        </div>
                                        <?php if($this->jCfg['member']['member_login']==0){ ?>
                                        <button type="button" class="btn btn-lg btn-black btn-popup-buy-marketplace modal-view" data-toggle="modal" data-target="#productModal"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Beli Sekarang</button>
                                        <?php } else { ?>
                                        <button type="submit" class="btn btn-lg btn-black cart-add-btn"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Beli Sekarang</button>
                                        <?php } ?>
                                    </div>
                                    <?php } else { ?>
                                    <div class="single-variation-wrap">
                                        <button type="button" class="btn btn-lg btn-black disabled" disabled><?php echo $product->product_status_name ?></button>
                                    </div>
                                    <?php } ?>

                                    <script type="text/javascript">
                                        $(document).ready(function() {

                                        });
                                    </script>

                                    <div style="display:none;">
                                        <input type="hidden" name="cart-id" value="<?php echo $product->product_id ?>" />
                                        <input type="hidden" name="cart-name" value="<?php echo $product->product_name ?>" />
                                        <input type="hidden" name="cart-image" value="<?php echo $product->image_cover ?>" />
                                        <input type="hidden" name="cart-link" value="<?php echo $product->product_name ?>" />
                                        <input type="hidden" name="cart-price" value="<?php echo $product->product_name ?>" />
                                        <input type="hidden" name="cart-weight" value="<?php echo $product->product_weight ?>" />
                                        <input type="hidden" name="cart-total-weight" value="<?php echo $cart_total_weight ?>" />
                                        <input type="hidden" name="cart-total-qty" value="<?php echo $cart_total_qty ?>" />
                                        <input type="hidden" name="cart-total-price" value="<?php echo $cart_total_price ?>" />
                                        <input type="hidden" name="cart-id-detail" value="<?php echo $cart_id_detail ?>" />
                                        <input type="hidden" name="cart-action" value="add" />

                                        <div class="data-normal-price" style="display:none;"><?php echo ($product->product_price_discount>0?$product->product_price_discount:$product->product_price_sale) ?></div>
                                        <?php
                                        $product_price_grosir = "";
                                        if($product->product_price_discount == 0 && $product->product_price_grosir != '' && $product->product_price_grosir != '[]'){
                                            $product_price_grosir = json_decode($product->product_price_grosir);
                                            if(is_array($product->product_price_grosir)){ $product_price_grosir = json_encode($product->product_price_grosir); }
                                        }
                                        ?>
                                        <div class="data-grosir-price" style="display:none;"><?php echo $product_price_grosir ?></div>
                                    </div>

                                    <div class="single-add-to-wrap">
                                        <a class="single-add-to-wishlist"><i class="fa fa-heart left" aria-hidden="true"></i><span>Add to Wishlist</span></a>
                                        <a class="single-add-to-compare "><i class="fa fa-refresh left" aria-hidden="true"></i><span>Add to Compare</span></a>
                                    </div>
                                    <div class="product-meta">
                                        <?php
                                        $tags = "";
                                        if($product->product_tags != ""){
                                            $i = 0;
                                            foreach ($product->product_tags as $key => $val) {
                                                $tag   = get_detail_product_tags($val->product_tags_id);
                                                $tags .= ($i==0?'':',&nbsp;').'<a href="'.base_url().'tag/'.$tag->url.'">'.ucwords($tag->product_tags_name).'</a>';
                                                $i += 1;
                                            }
                                        }
                                        ?>
                                        <span>Tag : <span class="tag" itemprop="tag"><?php echo $tags ?></span></span>
                                    </div>
                                    <div class="product-share">
                                        <span>Share :</span>
                                        <ul>
                                            <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo base_url() ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                            <li><a href="http://twitter.com/share?url=<?php echo base_url() ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                            <li><a href="http://plus.google.com/share?url=<?php echo base_url() ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                            <li><a href="mailto:?subject=Check this <?php echo base_url() ?>" target="_blank"><i class="fa fa-envelope"></i></a></li>
                                            <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo base_url() ?>exampleImage.jpg" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>

                        <!-- Product Content Tab -->
                        <div class="product-tabs-wrapper container">
                            <ul class="product-content-tabs nav nav-tabs" role="tablist">

                                <li class="nav-item">
                                    <a class="active" href="#tab_description" role="tab" data-toggle="tab">Deskripsi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="" href="#tab_additional_information" role="tab" data-toggle="tab">Informasi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="" href="#tab_reviews" role="tab" data-toggle="tab">Reviews (<span><?php echo $product->product_review ?></span>)</a>
                                </li>
                            </ul>
                            <div class="product-content-Tabs_wraper tab-content container">
                                <div id="tab_description" role="tabpanel" class="tab-pane fade in active">
                                    <h6 class="product-collapse-title" data-toggle="collapse" data-target="#tab_description-coll">Deskripsi</h6>
                                    <div id="tab_description-coll" class="shop_description product-collapse collapse container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                if($product->product_description == ""){
                                                    echo "Tidak ada deskripsi.";
                                                } else {
                                                    echo $product->product_description;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab_additional_information" role="tabpanel" class="tab-pane fade">
                                    <h6 class="product-collapse-title" data-toggle="collapse" data-target="#tab_additional_information-coll">Informasi</h6>
                                    <div id="tab_additional_information-coll" class="container product-collapse collapse">
                                        <div class="row">
                                            <div class="col-md-12">
                                                Tidak ada informasi.
                                            </div>
                                        </div>
                                        <!-- <table class="shop_attributes">
                                            <tbody>
                                                <tr>
                                                    <th>Color</th>
                                                    <td>Black, Gray, White</td>
                                                </tr>
                                                <tr>
                                                    <th>Weight</th>
                                                    <td>5 kg</td>
                                                </tr>
                                                <tr>
                                                    <th>Dimensions</th>
                                                    <td>60 x 40 x 60 cm</td>
                                                </tr>
                                                <tr>
                                                    <th>Washcare</th>
                                                    <td>Dry Clean</td>
                                                </tr>
                                                <tr>
                                                    <th>Composition</th>
                                                    <td>100% Polyester</td>
                                                </tr>
                                                <tr>
                                                    <th>Lining composition</th>
                                                    <td>100% Polyester</td>
                                                </tr>
                                                <tr>
                                                    <th>Other info</th>
                                                    <td>Ullamcorper nisl mus integer mollis vestibulu</td>
                                                </tr>
                                            </tbody>
                                        </table> -->

                                    </div>
                                    <!-- End Accordian Content -->
                                </div>
                                <div id="tab_reviews" role="tabpanel" class="tab-pane fade">
                                    <!-- Accordian Title -->
                                    <h6 class="product-collapse-title" data-toggle="collapse" data-target="#tab_reviews-coll">Reviews (<?php echo $product->product_review ?>)</h6>
                                    <!-- End Accordian Title -->
                                    <!-- Accordian Content -->
                                    <div id="tab_reviews-coll" class=" product-collapse collapse container">
                                        <div class="row">

                                            <?php
                                            $product_review = get_product_review_by_product($product->product_id);
                                            ?>
                                            <div class="comments col-md-6">
                                                <h6 class="review-title">Customer Reviews</h6>
                                                <?php if(count($product_review) == 0){ ?>
                                                <p class="review-blank">Belum ada review untuk produk ini.</p>
                                                <?php } else { ?>
                                                <ul class="commentlist">
                                                    <?php foreach ($product_review as $key => $val) { ?>
                                                    <li id="comment-<?php echo $val->product_review_id ?>" class="comment-<?php echo $val->product_review_id ?>">
                                                        <img src="<?php echo base_url();?>assets/front/img/avatar.jpg" class="avatar" alt="author" />
                                                        <div class="comment-text">
                                                            <div class="star-rating" itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating" title="Rated 4 out of 5">
                                                                <span style="width: 100%"></span>
                                                            </div>
                                                            <p class="meta">
                                                                <strong itemprop="author"><?php echo $val->product_review_name ?></strong>
                                                                &nbsp;&mdash;&nbsp;
                                                            <time itemprop="datePublished" datetime=""><?php echo $val->product_review_date ?></time>
                                                            </p>
                                                            <div class="description" itemprop="description">
                                                                <p><?php echo $val->product_review_desc ?></p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Accordian Content -->
                                </div>
                            </div>
                        </div>
                        <!-- End Product Content Tab -->

                    </div>
                    <div class="col-lg-3 col-md-12 product-sidebar">
                        <?php include('inc/menu_product_right.php'); ?>
                    </div>
                </div>

            </div>
            <!-- End Product -->

            <!-- Product Carousel -->
            <div class="container product-carousel">
                <h2 class="page-title">Kamu Mungkin Juga Suka</h2>
                <div id="related-product" class="product-item-4 owl-carousel owl-theme nf-carousel-theme1">
                    <?php
                    $product_related = get_product_related($product->product_category_id,'12','0');
                    foreach ($product_related as $key => $val) {
                        include('inc/layout_product_related.php');
                    }
                    ?>
                </div>
            </div>
            <!-- End Product Carousel -->

        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

    <link type="text/css" href="<?php echo base_url();?>assets/front/plugins/photoswipe_popup/photoswipe.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo base_url();?>assets/front/plugins/photoswipe_popup/default-skin/default-skin.css" rel="stylesheet" />
    <!-- Photoswipe Popup-->
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/photoswipe_popup/photoswipe.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/photoswipe_popup/photoswipe-ui-default.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/photoswipe_popup/photoswipe-core.js"></script>
    <!--Photoswipe Popup-->

<?php include('footer.php'); ?>