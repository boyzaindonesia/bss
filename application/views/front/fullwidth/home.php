<?php include('header.php'); ?>

    <!-- Page Content Wraper -->
    <div class="page-content-wraper">
        <!-- Intro -->
        <section id="intro" class="intro">
            <!-- Revolution Slider -->
            <div id="rev_slider_1078_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-source="gallery" style="background-color: transparent; padding: 0px;">
                <!-- START REVOLUTION SLIDER 5.3.0.2 fullwidth mode -->
                <div id="rev_slider_1078_1" class="rev_slider fullwidthabanner" style="display: none;" data-version="5.3.0.2">
                    <ul>
                        <?php
                        $front_home_headline = front_home_headline();
                        $i = 0;
                        foreach ($front_home_headline as $key => $val) {
                            $get_read_link    = get_read_link($val->link_type, $val->link_value);
                            $headline_setting = json_decode($val->headline_setting);
                            $i += 1;

                            $data_start = 500;
                        ?>
                        <li data-index="<?php echo 'rs-'.$i ?>" data-transition="random" data-slotamount="7" data-masterspeed="500" data-thumb="" data-saveperformance="off" data-title="<?php echo $i ?>">

                            <?php if(!$this->isMobile){ // DESKTOP ?>
                            <img src="<?php echo get_image(base_url().'assets/collections/headline/large/'.$val->headline_images) ?>" alt="" title="<?php echo $val->headline_title ?>" width="1920" height="1100" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="6" class="rev-slidebg" data-no-retina />

                            <?php if($val->headline_title_2 != ""){ ?>
                            <h1 class="tp-caption NotGeneric-Title tp-resizeme <?php echo $headline_setting->textalign ?>" style="letter-spacing: 0px; line-height: 60px;"
                                data-x="<?php echo $headline_setting->title2datax ?>"
                                data-y="<?php echo $headline_setting->title2datay ?>"
                                data-hoffset="<?php echo $headline_setting->title2datahoffset ?>"
                                data-voffset="<?php echo $headline_setting->title2datavoffset ?>"
                                data-transform_idle="o:1;"
                                data-width="['auto','auto','auto','auto']"
                                data-height="['auto','auto','auto','auto']"
                                data-transform_in="y:50px;opacity:0;s:700;e:Power3.easeOut;"
                                data-transform_out="s:500;e:Power3.easeInOut;s:500;e:Power3.easeInOut;"
                                data-start="<?php echo $data_start ?>"
                                data-speed="500"
                                data-endspeed="500"
                                data-splitin="none"
                                data-splitout="none"
                                data-responsive_offset="on"><?php echo $val->headline_title_2 ?>
                            </h1>
                            <?php
                                $data_start += 300;
                            } ?>

                            <?php if($val->headline_title_3 != ""){ ?>
                            <h3 class="tp-caption NotGeneric-Title tp-resizeme h3 normal <?php echo $headline_setting->textalign ?>" style="letter-spacing: 0px;"
                                data-x="<?php echo $headline_setting->title3datax ?>"
                                data-y="<?php echo $headline_setting->title3datay ?>"
                                data-hoffset="<?php echo $headline_setting->title3datahoffset ?>"
                                data-voffset="<?php echo $headline_setting->title3datavoffset ?>"
                                data-transform_idle="o:1;"
                                data-width="['auto','auto','auto','auto']"
                                data-height="['auto','auto','auto','auto']"
                                data-transform_in="y:50px;opacity:0;s:700;e:Power3.easeOut;"
                                data-transform_out="s:500;e:Power3.easeInOut;s:500;e:Power3.easeInOut;"
                                data-start="<?php echo $data_start ?>"
                                data-speed="500"
                                data-endspeed="500"
                                data-splitin="none"
                                data-splitout="none"
                                data-responsive_offset="on"><?php echo $val->headline_title_3 ?>
                            </h3>
                            <?php
                                $data_start += 300;
                            } ?>

                            <?php if($val->headline_images_child != ""){ ?>
                            <h1 class="tp-caption NotGeneric-Title tp-resizeme <?php echo $headline_setting->textalign ?>" style="letter-spacing: 0px; line-height: 60px;"
                                data-x="<?php echo $headline_setting->title2datax ?>"
                                data-y="<?php echo $headline_setting->title2datay ?>"
                                data-hoffset="<?php echo $headline_setting->title2datahoffset ?>"
                                data-voffset="<?php echo $headline_setting->title2datavoffset ?>"
                                data-transform_idle="o:1;"
                                data-width="['auto','auto','auto','auto']"
                                data-height="['auto','auto','auto','auto']"
                                data-transform_in="y:50px;opacity:0;s:700;e:Power3.easeOut;"
                                data-transform_out="s:500;e:Power3.easeInOut;s:500;e:Power3.easeInOut;"
                                data-start="<?php echo $data_start ?>"
                                data-speed="500"
                                data-endspeed="500"
                                data-splitin="none"
                                data-splitout="none"
                                data-responsive_offset="on">
                                <img src="<?php echo get_image(base_url().'assets/collections/headline/large/'.$val->headline_images_child) ?>" alt="" style="width: 100%; max-width: 682px;" />
                            </h1>
                            <?php
                                $data_start += 300;
                            } ?>

                            <a class="tp-caption NotGeneric-Title tp-resizeme btn btn-md btn-color"
                                href="<?php echo ($get_read_link!=''?$get_read_link:'javascript:void(0);') ?>"
                                data-x="<?php echo $headline_setting->buttondatax ?>"
                                data-y="<?php echo $headline_setting->buttondatay ?>"
                                data-hoffset="<?php echo $headline_setting->buttondatahoffset ?>"
                                data-voffset="<?php echo $headline_setting->buttondatavoffset ?>"
                                data-transform_idle="o:1;"
                                data-width="['auto','auto','auto','auto']"
                                data-height="['auto','auto','auto','auto']"
                                data-transform_in="y:50px;opacity:0;s:700;e:Power3.easeOut;"
                                data-transform_out="s:500;e:Power3.easeInOut;s:500;e:Power3.easeInOut;"
                                data-start="<?php echo $data_start ?>"
                                data-speed="500"
                                data-endspeed="500"
                                data-splitin="none"
                                data-splitout="none"
                                data-responsive_offset="on">Lihat Selengkapnya
                            </a>
                        <?php } else { ?>
                            <img src="<?php echo get_image(base_url().'assets/collections/headline/large/'.($val->headline_images_mobile!=""?$val->headline_images_mobile:$val->headline_images)) ?>" alt="" title="<?php echo $val->headline_title ?>" width="720" height="250" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="6" class="rev-slidebg" data-no-retina />
                        <?php } ?>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <!-- End Revolution Slider -->
        </section>
        <!-- End Intro -->

        <!-- Promo Box -->
        <section id="promo" class="section-padding-sm promo ">
            <div class="container">
                <div class="promo-box row">
                    <div class="col-md-4 mtb-sm-30 promo-item">
                        <div class="icon"><i class="fa fa-truck" aria-hidden="true"></i></div>
                        <div class="info">
                            <h6 class="normal">Fast Delivery</h6>
                            <p>Langsung Kirim Dihari Yang Sama</p>
                        </div>
                    </div>
                    <div class="col-md-4 mtb-sm-30 promo-item">
                        <div class="icon"><i class="fa fa-thumbs-up" aria-hidden="true"></i></div>
                        <div class="info">
                            <h6 class="normal">Premium Quality</h6>
                            <p>Kualitas Premium Harga Terjangkau</p>
                        </div>
                    </div>
                    <div class="col-md-4 mtb-sm-30 promo-item">
                        <div class="icon"><i class="fa fa-whatsapp" aria-hidden="true"></i></div>
                        <div class="info">
                            <h6 class="normal">Online Support</h6>
                            <p>Whatsapp Chat: <a href="<?php echo $configuration->configuration_whatsapp_link ?>" target="_blank">0<?php echo ltrim($configuration->configuration_whatsapp, "62") ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Promo Box -->

        <!-- Promo Banner -->
        <section id="promo-banner" class="section-padding-b">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                        <?php
                        $i   = 0;
                        $max = 2;
                        $promo_banner = get_banner_homepage_new_product();
                        foreach ($promo_banner as $key => $val) {
                            $get_read_link    = get_read_link($val->link_type, $val->link_value);
                            $margin = "mb-30";
                            if($i == 1){ $margin = "mb-sm-30"; }
                            if($i >= $max){
                                echo '</div></div><div class="col-md-6"><div class="row">';
                                $i = 0;
                            }
                            $i += 1;
                        ?>
                            <div class="col-12 <?php echo $margin ?>">
                                <a href="<?php echo ($get_read_link!=''?$get_read_link:'javascript:void(0);') ?>">
                                <div class="promo-banner-wrap">
                                    <div class="promo-image-wrap">
                                        <img src="<?php echo get_image(base_url().'assets/collections/banner/large/'.$val->banner_images) ?>" alt="<?php echo $val->banner_title ?>" />
                                    </div>
                                </div>
                                </a>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Promo Banner -->

        <!-- On Discount -->
        <?php
        $product_discount = get_product_discount('12');
        if(count($product_discount) > 0){ ?>
        <section class="section-padding bg-gray">
            <div class="container">
                <h2 class="page-title">ON DISCOUNT</h2>
            </div>
            <div class="container">
                <div id="new-tranding" class="product-item-4 owl-carousel owl-theme nf-carousel-theme1">
                    <?php
                    foreach ($product_discount as $key => $val) {
                        include('inc/layout_product.php');
                    }
                    ?>
                </div>
            </div>
        </section>
        <?php } ?>
        <!-- End On Discount -->

        <!-- New Product -->
        <section class="<?php echo (count($product_discount) > 0?'section-padding':'section-padding-b') ?>">
            <div class="container">
                <h2 class="page-title">New Product</h2>
            </div>
            <div class="container">
                <div id="new-product" class="product-item-4 owl-carousel owl-theme nf-carousel-theme1">
                    <?php
                    $product_new = get_product_new('12');
                    foreach ($product_new as $key => $val) {
                        include('inc/layout_product.php');
                    }
                    ?>
                </div>
            </div>
        </section>
        <!-- End New Product -->

        <!-- Categories -->
        <section class="">
            <div class="section-padding container-fluid bg-image text-center overlay-light90" data-background-img="<?php echo base_url();?>assets/front/img/bg/bg_8.jpg" data-bg-position-x="center center">
                <div class="container">
                    <h2 class="page-title">Shop by Categories</h2>
                </div>
            </div>
            <div class="container container-margin-minus-t">
                <div class="row">
                <?php
                $shop_category = get_shop_by_category();
                foreach ($shop_category as $key => $val) {
                    $get_read_link    = get_read_link($val->link_type, $val->link_value);
                ?>
                    <div class="col-md-4">
                        <div class="categories-box">
                            <div class="categories-image-wrap">
                                <a href="<?php echo ($get_read_link!=''?$get_read_link:'javascript:void(0);') ?>">
                                <img src="<?php echo get_image(base_url().'assets/collections/banner/large/'.$val->banner_images) ?>" alt="" />
                                </a>
                            </div>
                            <div class="categories-content">
                                <a href="<?php echo ($get_read_link!=''?$get_read_link:'javascript:void(0);') ?>">
                                    <div class="categories-caption">
                                        <h6 class="normal"><?php echo $val->banner_title ?></h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </section>
        <!-- End Categories -->

        <!-- Best Seller -->
        <?php
        $m = get_product_best_selling('12');
        if(count($m) > 0){ ?>
        <section class="section-padding">
            <div class="container">
                <h2 class="page-title">Best Seller</h2>
            </div>
            <div class="container">
                <div id="best-seller" class="row product-list-item">
                    <?php
                    foreach ($m as $key => $val) { ?>
                    <div class="product-item-element col-sm-6 col-md-4 col-lg-3">
                        <?php include('inc/layout_product.php'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <?php } ?>
        <!-- End Best Seller -->

        <!-- Mukena -->
        <?php
        $m = get_product_related('8', '12');
        if(count($m) > 0){ ?>
        <section class="section-padding-b">
            <div class="container">
                <h2 class="page-title">Mukena</h2>
            </div>
            <div class="container">
                <div id="best-seller" class="row product-list-item">
                    <?php
                    foreach ($m as $key => $val) { ?>
                    <div class="product-item-element col-sm-6 col-md-4 col-lg-3">
                        <?php include('inc/layout_product.php'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <?php } ?>
        <!-- End Mukena -->

        <!-- Bra / BH Sport -->
        <?php
        $m = get_product_related('7', '12');
        if(count($m) > 0){ ?>
        <section class="section-padding-b">
            <div class="container">
                <h2 class="page-title">Bra / BH Sport</h2>
            </div>
            <div class="container">
                <div id="best-seller" class="row product-list-item">
                    <?php
                    foreach ($m as $key => $val) { ?>
                    <div class="product-item-element col-sm-6 col-md-4 col-lg-3">
                        <?php include('inc/layout_product.php'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <?php } ?>
        <!-- End Bra / BH Sport -->

        <!-- Legging -->
        <?php
        $m = get_product_related('6', '12');
        if(count($m) > 0){ ?>
        <section class="section-padding-b">
            <div class="container">
                <h2 class="page-title">Legging</h2>
            </div>
            <div class="container">
                <div id="best-seller" class="row product-list-item">
                    <?php
                    foreach ($m as $key => $val) { ?>
                    <div class="product-item-element col-sm-6 col-md-4 col-lg-3">
                        <?php include('inc/layout_product.php'); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <?php } ?>
        <!-- End Legging -->

        <!-- Like & Share Banner -->
        <section id="like-share" class="like-share">
            <div class="container">
                <div class="like-share-inner overlay-black40">
                    <h3>Temukan juga produk kami di Marketplace dan jangan lupa untuk <span class="color">Favoritkan</span></h3>
                    <ul class="social-icon">
                        <li><a href="<?php echo $configuration->configuration_shopee_link ?>" target="_blank" class="js_tooltip" data-mode="top" data-tip="Shopee"><img src="<?php echo base_url()."assets/front/images/marketplace/shopee-white-48.png" ?>"/></a></li>
                        <li><a href="<?php echo $configuration->configuration_tokped_link ?>" target="_blank" class="js_tooltip" data-mode="top" data-tip="Tokopedia"><img src="<?php echo base_url()."assets/front/images/marketplace/tokopedia-white-48.png" ?>"/></a></li>
                        <li><a href="<?php echo $configuration->configuration_bl_link ?>" target="_blank" class="js_tooltip" data-mode="top" data-tip="Bukalapak"><img src="<?php echo base_url()."assets/front/images/marketplace/bukalapak-white-48.png" ?>"/></a></li>
                        <li><a href="<?php echo $configuration->configuration_laz_link ?>" target="_blank" class="js_tooltip" data-mode="top" data-tip="Lazada"><img src="<?php echo base_url()."assets/front/images/marketplace/lazada-white-48.png" ?>"/></a></li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- End Like & Share Banner -->

        <!-- Blog & News -->
        <section class="section-padding">
            <div class="container">
                <h2 class="page-title">Blog & News</h2>
            </div>
            <div class="container">
                <div id="blog-carousel" class="blog-carousel owl-carousel owl-theme nf-carousel-theme1">
                    <?php
                    $latest_news = front_latest_news();
                    if(count($latest_news) > 0){
                        foreach ($latest_news as $key => $val) {
                        ?>
                        <div class="product-item">
                            <div class="blog-box">
                                <div class="blog-img-wrap">
                                    <a href="<?php echo base_url().'news/'.$val->url; ?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/article/small/".$val->article_image, 'no_image_720x439.jpg');?>" alt="" />
                                    </a>
                                </div>
                                <div class="blog-box-content">
                                    <div class="blog-box-content-inner">
                                        <h4 class="blog-title"><a href="<?php echo base_url().'news/'.$val->url; ?>"><?php echo $val->article_title ?></a></h4>
                                        <p class="info"><span>by <a href="javascript:void(0);"><?php echo get_user_name($val->article_user_id) ?></a></span><span><?php echo convDateTable($val->article_publishdate) ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
        <!-- End Blog & News -->

        <!-- Newsletter -->

        <section class="section-padding dark-bg container-fluid bg-image text-center overlay-black40" data-background-img="<?php echo base_url();?>assets/front/img/bg/bg_10.jpg" data-bg-position-x="center top">
            <div class="container newsletter section-padding-b">
                <h2 class="page-title">Subscribe To Newsletter</h2>
                <form id="form-newsletter" class="newsletter-from" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-input">
                        <input type="email" name="email" class="input-lg" placeholder="Masukan Email.." title="Masukan Email.." required>
                    </div>
                    <button type="submit" class="btn btn-lg btn-color">Subscribe</button>
                </form>
                <p class="italic">Sign up For Exclusive Updates, New Arrivals And Insider-Only Discount.</p>
            </div>
        </section>

        <!-- Newsletter -->

        <!-- About blocks -->
        <section class="">
            <div class="container container-margin-minus-t">
                <div class="home-about-blocks">
                    <div class="col-12 about-blocks-wrap">
                        <div class="row">
                            <!--Customer Say-->
                            <div class="col-sm-6 col-md-6 customer-say">
                                <div class="about-box-inner">
                                    <h4 class="mb-25">Customer Say</h4>

                                    <!--Customer Carousel-->
                                    <div class="testimonials-carousel owl-carousel owl-theme nf-carousel-theme1">
                                        <?php
                                        $testimonial = front_testimonial();
                                        foreach ($testimonial as $key => $val) { ?>
                                        <div class="product-item">
                                            <p class="large quotes"><?php echo $val->testimonial_desc ?></p>
                                            <h6 class="quotes-people">- <?php echo $val->testimonial_name ?> (<?php echo $val->testimonial_email ?>)</h6>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <!--End Customer Carousel-->
                                </div>
                            </div>

                            <!--About Shop-->
                            <div class="col-sm-6 col-md-6 about-shop">
                                <div class="about-box-inner">
                                    <h4 class="mb-25">About Butik Sasha</h4>
                                    <p class="mb-20">Selamat Datang di <b class="black">Butik Sasha</b> - Butik Sasha adalah Toko Online Hijab yang menjual bermacam-macam jilbab berkualitas premium dengan harga terjangkau. Yuk lengkapi koleksi hijabmu dengan belanja di Butik Sasha.</p>
                                    <a href="<?php echo base_url() ?>about" class="btn btn-xs btn-black">Lihat Selengkapnya <i class="fa fa-angle-right right"></i></a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End About blocks -->

        <!-- Brnad Logo -->
        <section id="brand-logo" class="section-padding brand-logo">
            <div class="container">
                <ul class="list-none-ib brand-logo-carousel owl-carousel owl-theme">
                    <?php
                    $product_brand = get_product_brand();
                    foreach ($product_brand as $key => $val) { ?>
                    <li class="brand-item">
                        <a href="<?php echo base_url().'brand/'.$val->url; ?>">
                            <img src="<?php echo get_image(base_url()."assets/collections/brand/large/".$val->product_brand_image, 'no_image_product.jpg');?>" alt="<?php echo $val->product_brand_name ?>" />
                        </a>
                    </li>
                    <?php } ?>
                </ul>

            </div>
        </section>
        <!-- End Brnad Logo -->

        <!-- Instagram -->
        <section class="section-padding instagram bg-gray">
            <div class="container text-center">
                <h2 class="page-title">Butik Sasha on Instagram</h2>
                <p class="">Follow Us <a class="strong" href="<?php echo $configuration->configuration_ig_link; ?>" target="_blank">@ButikSashaHijab</a></p>
            </div>
            <div class="container">
                <div id="instagram-feed" class="instagram-feed"><img style="position:absolute;left:50%;margin-left:-12px;top:0px;width:24px;" src="<?php echo base_url();?>assets/front/images/loading.gif"/></div>
            </div>
        </section>
        <style type="text/css">
            .instagram-feed a { display: block; }
            .instagram-feed .instagram_gallery:after {
                content:".";
                display:block;
                clear:both;
                visibility:hidden;
                line-height:0;
                height:0;
            }
            .instagram-feed .instagram_gallery { display:block; }
        </style>
        <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/instagramFeed/jquery.instagramFeed.js"></script>
        <script type="text/javascript">
            $(window).on('load', function(){
                $.instagramFeed({
                    'username': 'butiksashahijab',
                    'container': "#instagram-feed",
                    'display_profile': false,
                    'display_biography': false,
                    'display_gallery': true,
                    'get_raw_json': false,
                    'callback': null,
                    'styling': true,
                    'items': 10,
                    'items_per_row': 5,
                    'margin': 1
                });
            });
        </script>
        <!-- End Instagram -->
    </div>
    <!-- End Page Content Wraper -->


    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/plugins/rev_slider/css/settings-ver.5.3.1.css">
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/rev_slider/js/jquery.themepunch.revolution.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/rev_slider/js/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/rev_slider/js/revolution.extension.navigation.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/rev_slider/js/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/rev_slider/js/revolution.extension.slideanims.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/plugins/rev_slider/js/revolution.extension.layeranimation.min.js"></script>
    <!--Slider Revolution Js File-->

    <script type="text/javascript">
        var e, i = jQuery; i(document).ready(function () { void 0 == i("#rev_slider_1078_1").revolution ? revslider_showDoubleJqueryError("#rev_slider_1078_1") : e = i("#rev_slider_1078_1").show().revolution({ sliderType: "standard", jsFileLocation: MOD_URL+"assets/front/plugins/rev_slider/js/", sliderLayout: "fullwidth", dottedOverlay: "none", delay: 5e3, touchenabled: "on", swipe_velocity: .7, swipe_min_touches: 1, swipe_max_touches: 1, drag_block_vertical: !1, keyboardNavigation: "on", fullWidth: "on", fullScreen: "off", navigation: { keyboardNavigation: "off", keyboard_direction: "horizontal", mouseScrollNavigation: "off", mouseScrollReverse: "default", onHoverStop: "off", arrows: { style: "zeus", enable: !0, hide_onmobile: !0, hide_under: 1025, hide_onleave: !1, tmp: "", left: { h_align: "left", v_align: "center", h_offset: 20, v_offset: 0 }, right: { h_align: "right", v_align: "center", h_offset: 20, v_offset: 0 } }, bullets: { enable: !0, hide_onmobile: !1, hide_over: 1025, style: "metis", hide_onleave: !1, direction: "horizontal", h_align: "center", v_align: "bottom", h_offset: 0, v_offset: 20, space: 10, tmp: "" } }, viewPort: { enable: !0, outof: "pause", visible_area: "80%", presize: !1 }, hideTimerBar: "on", responsiveLevels: [1240, 1024, 778, 480], visibilityLevels: [1240, 1024, 778, 480], gridwidth: [1240, 1024, 778, 480], gridheight: [600, 500, 380, 200], lazyType: "smart", shadow: 0, stopLoop: "off", stopAfterLoops: -1, stopAtSlide: -1, shuffle: "off", autoHeight: "off", hideThumbsOnMobile: "off", hideBulletsOnMobile: "off", hideArrowsOnMobile: "off", hideSliderAtLimit: 0, hideCaptionAtLimit: 0, hideAllCaptionAtLilmit: 0, debugMode: !1, fallbacks: { simplifyAll: "off", nextSlideOnWindowFocus: "off", disableFocusListener: !1 } }) });
    </script>

<?php include('footer.php'); ?>