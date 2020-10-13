<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo get_name_app('configuration_name'); ?></title>

    <meta name="author" content="Dewacode"/>
    <meta name="title" content="<?php echo isset($article->article_title)?$article->article_title:get_name_app('configuration_meta_title');?>" />
    <meta name="description" content="<?php echo isset($article->article_meta_description)&&$article->article_meta_description!=""?$article->article_meta_description:get_name_app('configuration_meta_desc');?>" />
    <meta name="keywords" content="<?php echo isset($article->article_meta_keywords)&&$article->article_meta_keywords!=""?$article->article_meta_keywords:get_name_app('configuration_meta_keyword');?>" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- [ FONT-AWESOME ICON ] -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/lib/font-awesome-4.3.0/css/font-awesome.min.css">

    <!-- [ PLUGIN STYLESHEET ] -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/coming-soon/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/magnific-popup.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/vegas.min.css">

    <!-- [ DEFAULT STYLESHEET ] -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/lib/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/reset.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/structure.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/main-style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/coming-soon/css/responsive.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-88117319-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-88117319-1');
    </script>
</head>
<body class="slideshow-bg">

    <div class="page-loader-wrapper">
        <div class="loader">

        </div>
    </div>

    <!-- [ MAIN-WRAPPER ] -->
    <div id="main-wrapper">

        <!-- [ PAGE-SECTION ] -->
        <main id="main" class="background" data-image="<?php echo base_url();?>assets/coming-soon/images/single-bg.jpg">

            <!-- [ BACKGROUND-OVERLAY ] -->
            <div class="overlay" style="background-color: rgba(0,0,0,0.4)"></div>
            <!-- [ BACKGROUND-OVERLAY END ] -->

            <div class="nc-content-section index nc-active vhm">

                <!-- [ HOME-PAGE ] -->
                <div id="home-page" class="page-wrapper vhm-item active-home anim s01">
                    <div class="container">

                        <!-- [ LOGO ] -->
                        <div class="logo ac anim fadeInUp s01 delay-0-5s">
                            <div class="logo-wrp">
                                <img src="<?php echo base_url();?>assets/coming-soon/images/logo_butik_sasha_w.png" alt="Logo">
                            </div>
                        </div>
                        <!-- [ LOGO END ] -->

                        <!-- [ TAGLINE ] -->
                        <div class="tagline ac anim fadeInUp s01 delay-0-6s">

                            <!-- ANIMATION TEXT -->
                            <div class="carousel-widget text-carousel" data-itemrange="false" data-in="flipInX" data-out="fadeOut" data-autoplay="true" data-loop="true" data-items="1" data-mouseDrag="false" data-touchDrag="false">
                                <div class="carousel">
                                    <div class="owl-carousel owl-theme">
                                        <div class="item">
                                            <h1>Website sedang<br>dalam pengembangan</h1>
                                        </div>
                                        <div class="item">
                                            <h1>Lihat katalog produk<br>di Instagram, Bukalapak atau Tokopedia</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="anim fadeInUp s01 delay-0-7s">Our Website Is Coming Soon, We'll be here soon with our new
                            Product, subscribe to be notified.</p>
                        </div>
                        <!-- [ TAGLINE END ] -->

                        <!-- [ SUBSCRIBE ] -->
                        <div class="subscribe ac anim fadeInUp s01 delay-0-8s">
                            <form id="notifyMe" class="subscription-form clear form-field-wrapper" method="POST" action="<?php echo base_url();?>notify-me">
                                <input type="hidden" name="notify_me_id">
                                <input type="text" class="form-control" name="email" placeholder="Subscribe">
                                <button type="submit" id="submit" class="icon vhm"><i class="fa fa-envelope-o vhm-item"></i></button>
                            </form>
                        </div>
                        <!-- [ SUBSCRIBE END ] -->

                        <!-- [ SOCIAL ICON ] -->
                        <div class="social-icon ac anim fadeInUp s01 delay-0-9s">
                            <a class="icon vhm" href="https://www.facebook.com/ButikSashaHijab" target="_blank">
                                <span class="box"></span>
                                <i class="fa fa-facebook vhm-item"></i>
                            </a>
                            <a class="icon vhm" href="https://twitter.com/ButikSashaHijab" target="_blank">
                                <span class="box"></span>
                                <i class="fa fa-twitter vhm-item"></i>
                            </a>
                            <a class="icon vhm" href="https://www.instagram.com/ButikSashaHijab" target="_blank">
                                <span class="box"></span>
                                <i class="fa fa-instagram vhm-item"></i>
                            </a>
                            <a class="icon vhm" href="https://www.bukalapak.com/butiksasha" target="_blank">
                                <span class="box"></span>
                                <i class="fa fa-bukalapak vhm-item"></i>
                            </a>
                            <a class="icon vhm" href="https://www.tokopedia.com/butiksasha" target="_blank">
                                <span class="box"></span>
                                <i class="fa fa-tokopedia vhm-item"></i>
                            </a>
                        </div>
                        <!-- [ SOCIAL ICON END ] -->

                        <!-- [ COPYRIGHT ] -->
                        <div class="copyrights ac anim fadeInUp s01 delay-1s">
                            <span><i class="fa fa-copyright"></i></span>2016 Butik Sasha
                        </div>
                        <!-- [ COPYRIGHT END ] -->

                    </div>
                </div>
                <!-- [ HOME-PAGE END ] -->

                <!-- [ AJAX-PAGE ] -->
                <div id="ajax-page" class="page-wrapper vhm-item">

                </div>
                <!-- [ AJAX-PAGE END ] -->

            </div>

            <!-- [ OC-CLICK OVERLAY ] -->
            <div class="nc-overlay"></div>
            <!-- [ OC-CLICK OVERLAY END ] -->

        </main>
        <!-- [ PAGE-SECTION END ] -->

    </div>
    <!-- [ MAIN-WRAPPER END ] -->

    <!-- [ DEFAULT SCRIPT ] -->
    <script src="<?php echo base_url();?>assets/coming-soon/js/jquery-1.11.3.min.js"></script>

    <!-- [ PLUGIN SCRIPT ] -->
    <script src="<?php echo base_url();?>assets/coming-soon/js/plugins.js"></script>
    <script src="<?php echo base_url();?>assets/coming-soon/js/vegas.min.js"></script>

    <!-- [ COMMON SCRIPT ] -->
    <script src="<?php echo base_url();?>assets/coming-soon/js/common.js"></script>

    <script type="text/javascript">
        $(".slideshow-bg main.background").vegas({
            delay: 9000,
            slides: [
                { src: '<?php echo base_url().'assets/coming-soon/images/'.folder_views_lg().'slideshow_1.jpg' ?>' },
                { src: '<?php echo base_url().'assets/coming-soon/images/'.folder_views_lg().'slideshow_2.jpg' ?>' },
                { src: '<?php echo base_url().'assets/coming-soon/images/'.folder_views_lg().'slideshow_3.jpg' ?>' },
                // { src: '<?php echo base_url().'assets/coming-soon/images/'.folder_views_lg().'slideshow_4.jpg' ?>' },
                { src: '<?php echo base_url().'assets/coming-soon/images/'.folder_views_lg().'slideshow_6.jpg' ?>' },
                { src: '<?php echo base_url().'assets/coming-soon/images/'.folder_views_lg().'slideshow_5.jpg' ?>' }
            ],
            animation: 'kenburns'
        });
    </script>
</body>
</html>