<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php
        $page_title    = isset($this->page)&&$this->page!=''?$this->page:'';
        $article_title = isset($article->article_title)&&$article->article_title!=''?$article->article_title:'';
        $meta_title    = get_name_app('configuration_meta_title');
        if($page_title != ''){ $meta_title = $page_title.' | '.get_name_app('configuration_name'); }
        if($article_title != ''){ $meta_title = $article_title.' | '.get_name_app('configuration_name'); }
    ?>

    <title><?php echo $meta_title ?></title>

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <meta name="author" content="Butik Sasha"/>
    <meta name="title" content="<?php echo $meta_title ?>" />
    <meta name="description" content="<?php echo isset($article->article_meta_description)&&$article->article_meta_description!=""?$article->article_meta_description:get_name_app('configuration_meta_desc');?>" />
    <meta name="keywords" content="<?php echo isset($article->article_meta_keywords)&&$article->article_meta_keywords!=""?$article->article_meta_keywords:get_name_app('configuration_meta_keyword');?>" />
    <meta name="Resource-type" content="Document" />

    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/collections/images/favicon.ico">
    <link rel="icon" type="img/png" href="<?php echo base_url();?>assets/collections/images/favicon.png">
    <link rel="apple-touch-icon" href="<?php echo base_url();?>assets/collections/images/favicon.png">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/plugins/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/plugins/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/plugins/animate.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/style.css">

    <!-- JAVASCRIPT -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/plugins/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/plugins/modernizr.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/plugins/tether.min.js"></script>
    <!--Bootstrap tooltips require Tether (Tether Js)-->
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/plugins/bootstrap.min.js"></script>

    <script src="<?php echo base_url();?>assets/plugins/cookie/jquery.cookie.min.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/magnific-popup/magnific-popup.min.js"></script>

    <script src="<?php echo base_url();?>assets/plugins/sweetalert2/es6-promise.auto.min.js"></script> <!-- IE support -->
    <script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>

    <!-- <script src="<?php echo base_url();?>assets/plugins/jquery-ui/autocomplete/jquery-ui-autocomplete.min.js"></script> -->

    <!-- <script src="<?php echo base_url();?>assets/plugins/custom_general/custom_general.js"></script> -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-88117319-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-88117319-1');
    </script>


    <script type="text/javascript">
       var MOD_URL   = '<?php echo base_url();?>';
    </script>

    <?php js_blastnotif() ?>

    <?php // if($this->isMobile){ echo 'Mobile'; } else { echo 'Desktop'; } ?>

    <?php $configuration = get_configuration(); ?>

</head>
<body class="<?php echo ($this->isMobile?"mobile":"desktop") ?>">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Newsletter Popup -->
    <section id="nlpopup" data-expires="30" data-delay="10">
        <!--Close Button-->
        <a href="javascript:void(0)" class="nlpopup_close nlpopup_close_icon">
            <img src="<?php echo base_url();?>assets/front/img/close-icon-white.png" alt="Newsletter Close" /></a>
        <!--End Close Button-->

        <h3 class="mb-40">Hai Kak,</h3>
        <p class="black mb-20">
            Biar kamu tidak ketinggalan update Produk Terbaru kami<br />
            yuk join dengan cara Subscribe website kami.
        </p>
        <form class="form-newsletter" action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <input type="email" name="email" class="input-md" title="Masukan Email.." placeholder="example@gmail.com" required>
            <button type="submit" class="btn btn-md btn-color">Subscribe</button>
        </form>
        <label class="mt-20">
            Terima kasih, selamat berbelanja<br />
            Semoga harimu menyenangkan.</label>
        <a class="nlpopup_close nlpopup_close_link mt-40">&#10006; Tutup</a>
    </section>
    <!-- Overlay -->
    <div id="nlpopup_overlay"></div>
    <!-- End Newsletter Popup -->

    <!-- Sidebar Menu (Cart Menu) -->
    <section id="sidebar-right" class="sidebar-menu sidebar-right">
        <div class="cart-sidebar-wrap">

            <!-- Cart Headiing -->
            <div class="cart-widget-heading">
                <h4>Keranjangku</h4>
                <!-- Close Icon -->
                <a href="javascript:void(0);" id="sidebar_close_icon" class="close-icon-white"></a>
                <!-- End Close Icon -->
            </div>
            <!-- End Cart Headiing -->

            <!-- Cart Product Content -->
            <div class="cart-widget-content">
                <div class="cart-widget-product ">
                    <?php $cart = array(); ?>

                    <?php if(count($cart) == 0){ ?>
                    <div class="cart-empty show">
                        <p>Belum ada produk dikeranjangku.</p>
                    </div>
                    <?php } else { ?>

                    <!-- Cart Products -->
                    <ul class="cart-product-item">

                        <!-- Item -->
                        <li>
                            <!--Item Image-->
                            <a href="#" class="product-image">
                                <img src="<?php echo base_url();?>assets/front/img/product-img/small/product_12547554.jpg" alt="" /></a>

                            <!--Item Content-->
                            <div class="product-content">
                                <!-- Item Linkcollateral -->
                                <a class="product-link" href="index.html#">Alpha Block Black Polo T-Shirt</a>

                                <!-- Item Cart Totle -->
                                <div class="cart-collateral">
                                    <span class="qty-cart">1</span>&nbsp;<span>&#215;</span>&nbsp;<span class="product-price-amount"><span class="currency-sign">$</span>399.00</span>
                                </div>

                                <!-- Item Remove Icon -->
                                <a class="product-remove" href="javascript:void(0)"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                            </div>
                        </li>

                        <!-- Item -->
                        <li>
                            <!--Item Image-->
                            <a href="index.html#" class="product-image">
                                <img src="<?php echo base_url();?>assets/front/img/product-img/small/product_12547555.jpg" alt="" /></a>

                            <!--Item Content-->
                            <div class="product-content">
                                <!-- Item Linkcollateral -->
                                <a class="product-link" href="index.html#">Red Printed Round Neck T-Shirt</a>

                                <!-- Item Cart Totle -->
                                <div class="cart-collateral">
                                    <span class="qty-cart">2</span>&nbsp;<span>&#215;</span>&nbsp;<span class="product-price-amount"><span class="currency-sign">$</span>299.00</span>
                                </div>

                                <!-- Item Remove Icon -->
                                <a class="product-remove" href="javascript:void(0)"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                            </div>
                        </li>

                    </ul>
                    <!-- End Cart Products -->
                    <?php } ?>

                </div>
            </div>
            <!-- End Cart Product Content -->

            <!-- Cart Footer -->
            <div class="cart-widget-footer">
                <div class="cart-footer-inner">

                    <!-- Cart Total -->
                    <h4 class="cart-total-hedding normal"><span>Total :</span><span class="cart-total-price">Rp 0</span></h4>
                    <!-- Cart Total -->

                    <!-- Cart Buttons -->
                    <div class="cart-action-buttons">
                        <a href="<?php echo base_url() ?>cart" class="view-cart btn btn-md btn-gray">Lihat Keranjang</a>
                        <a href="<?php echo base_url() ?>checkout" class="checkout btn btn-md btn-color">Checkout</a>
                    </div>
                    <!-- End Cart Buttons -->

                </div>
            </div>
            <!-- Cart Footer -->
        </div>
    </section>
    <!--Overlay-->
    <div class="sidebar_overlay"></div>
    <!-- End Sidebar Menu (Cart Menu) -->

    <!-- Search Overlay Menu -->
    <section class="search-overlay-menu">
        <a href="javascript:void(0)" class="search-overlay-close"></a>
        <div class="container">
            <form role="search" id="searchform" action="<?php echo base_url()?>search" method="get">
                <div class="search-icon-lg">
                    <img src="<?php echo base_url();?>assets/front/img/search-icon-lg.png" alt="" />
                </div>
                <label class="h6 normal search-input-label" for="search-query">Masukan keyword untuk mencari produk</label>
                <input value="" name="keyword" type="search" placeholder="Temukan disini..." />
                <button type="submit">
                    <img src="<?php echo base_url();?>assets/front/img/search-lg-go-icon.png" alt="" />
                </button>
            </form>
        </div>
    </section>
    <!-- End Search Overlay Menu -->

    <!--==========================================-->
    <!-- wrapper -->
    <!--==========================================-->
    <div class="wraper">
        <!-- Header -->
        <header class="header">
            <!--Topbar-->
            <div class="header-topbar">
                <div class="header-topbar-inner">
                    <!--Topbar Left-->
                    <div class="topbar-left hidden-sm-down">
                        <div class="phone"><i class="fa fa-whatsapp left" aria-hidden="true"></i>Whatsapp Chat: <b><a href="<?php echo $configuration->configuration_whatsapp_link ?>" target="_blank">0<?php echo ltrim($configuration->configuration_whatsapp, "62") ?></a></b></div>
                    </div>
                    <!--End Topbar Left-->

                    <!--Topbar Right-->
                    <div class="topbar-right">
                        <ul class="list-none">
                            <li>
                                <a href="<?php echo base_url() ?>login"><i class="fa fa-lock left" aria-hidden="true"></i><span class="hidden-sm-down">Login</span></a>
                            </li>
                            <li class="dropdown-nav">
                                <a href="<?php echo base_url() ?>account"><i class="fa fa-user left" aria-hidden="true"></i><span class="hidden-sm-down">My Account</span><i class="fa fa-angle-down right" aria-hidden="true"></i></a>
                                <!--Dropdown-->
                                <div class="dropdown-menu">
                                    <ul>
                                        <li><a href="<?php echo base_url() ?>account">My Account</a></li>
                                        <li><a href="<?php echo base_url() ?>list-orders">List Order</a></li>
                                        <li><a href="<?php echo base_url() ?>wishlist">Wishlist</a></li>
                                        <li><a href="<?php echo base_url() ?>confirm-payment">Konfirmasi Pembayaran</a></li>
                                    </ul>
                                    <span class="divider"></span>
                                    <ul>
                                        <li><a href="<?php echo base_url() ?>login"><i class="fa fa-lock left" aria-hidden="true"></i>Login</a></li>
                                        <li><a href="<?php echo base_url() ?>register"><i class="fa fa-user left" aria-hidden="true"></i>Create an Account</a></li>
                                    </ul>
                                </div>
                                <ul>
                            </ul>
                                <!--End Dropdown-->
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>about">About</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>contact">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Topbar Right -->
                </div>
            </div>
            <!--End Topbart-->

            <!-- Header Container -->
            <div id="header-sticky" class="header-main">
                <div class="header-main-inner">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="<?php echo base_url(); ?>">
                            <img src="<?php echo base_url();?>assets/front/images/logo-2.png" alt="Butik Sasha" />
                        </a>
                    </div>
                    <!-- End Logo -->


                    <!-- Right Sidebar Nav -->
                    <div class="header-rightside-nav">
                        <!-- Login-Register Link -->
                        <div class="header-btn-link hidden-lg-down"><a href="javascript:void(0);" class="btn btn-sm btn-color">Hai Kak...</a></div>
                        <!-- End Login-Register Link -->

                        <!-- Sidebar Icon -->
                        <div class="sidebar-icon-nav">
                            <ul class="list-none-ib">
                                <!-- Search-->
                                <li><a id="search-overlay-menu-btn"><i aria-hidden="true" class="fa fa-search"></i></a></li>

                                <!-- Whishlist-->
                                <!-- <li><a class="js_whishlist-btn"><i aria-hidden="true" class="fa fa-heart"></i><span class="countTip">10</span></a></li> -->
                                <li><a class="js_whishlist-btn"><i aria-hidden="true" class="fa fa-heart"></i></a></li>

                                <!-- Cart-->
                                <li><a id="sidebar_toggle_btn">
                                    <div class="cart-icon">
                                        <i aria-hidden="true" class="fa fa-shopping-bag"></i>
                                    </div>

                                    <div class="cart-title">
                                        <span class="cart-count">0</span>
                                        /
                                    <span class="cart-price strong">Rp 0</span>
                                    </div>
                                </a></li>
                            </ul>
                        </div>
                        <!-- End Sidebar Icon -->
                    </div>
                    <!-- End Right Sidebar Nav -->


                    <!-- Navigation Menu -->
                    <nav class="navigation-menu">
                        <ul>
                            <li>
                                <a href="<?php echo base_url(); ?>home">Home</a>
                            </li>
                            <?php
                            $category_menu = front_get_category_menu();
                            if(count($category_menu) > 0){
                            foreach($category_menu as $c){ ?>
                            <li class="<?php echo ($category_id==$c->product_category_id?'active':'') ?>">
                                <a href="<?php echo base_url().$c->url ?>"><?php echo $c->product_category_title ?></a>
                            </li>
                            <?php }
                            } ?>

                            <li>
                                <a href="<?php echo base_url().'brand' ?>">Brand</a>
                                <ul class="nav-dropdown js-nav-dropdown">
                                    <li class="container">
                                        <ul class="row">
                                            <li class="nav-dropdown-grid">
                                                <ul>
                                                <?php
                                                $max_row_brand = 3;
                                                $menubrand = get_product_brand();
                                                if(count($menubrand) > 0){
                                                    $i = 0;
                                                    foreach ($menubrand as $key => $val) {
                                                        if($i >= $max_row_brand){
                                                            echo '</ul></li><li class="nav-dropdown-grid"><ul>';
                                                            $i = 0;
                                                        }
                                                        $i += 1;
                                                        echo '<li><a href="'.base_url().'brand/'.$val->url.'">'.$val->product_brand_name.'</a></li>';
                                                    }
                                                }
                                                ?>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    <!-- End Navigation Menu -->

                </div>
            </div>
            <!-- End Header Container -->
        </header>
        <!-- End Header -->

