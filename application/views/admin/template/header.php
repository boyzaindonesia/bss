<?php
$user_id  = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
$store_id = get_user_store($user_id);

$configuration = get_configuration();
$product_setup = get_product_setup("1");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

    <head>
        <!-- Basic -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title><?php echo isset($title)?$title:'Home';?></title>

        <meta name="author" content="dewacode"/>
        <meta name="description" content=""/>
        <meta name="keywords" content="">
        <meta name="Resource-type" content="" />
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/collections/images/favicon.ico">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
        <link href="<?php echo base_url();?>assets/admin/css/bootstrap.min.css" rel="stylesheet">

        <!-- PLUGINS CSS -->
        <link href="<?php echo base_url();?>assets/plugins/magnific-popup/magnific-popup.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet">

        <!-- MAIN CSS (REQUIRED ALL PAGE)-->
        <link href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/plugins/jquery-ui/autocomplete/jquery-ui-autocomplete.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/admin/css/style.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/admin/css/style-responsive.css" rel="stylesheet">

        <!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
        <script src="<?php echo base_url();?>assets/admin/js/jquery.min.js"></script>
        <script src="<?php echo base_url();?>assets/admin/js/bootstrap.min.js"></script>
        <!-- <script src="<?php echo base_url();?>assets/plugins/retina/retina.min.js"></script> -->
        <script src="<?php echo base_url();?>assets/plugins/nicescroll/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

        <script src="<?php echo base_url();?>assets/plugins/jquery-ui/autocomplete/jquery-ui-autocomplete.min.js"></script>

        <!-- PLUGINS -->
        <script src="<?php echo base_url();?>assets/plugins/cookie/jquery.cookie.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/magnific-popup/magnific-popup.min.js"></script>

        <script src="<?php echo base_url();?>assets/plugins/sweetalert2/es6-promise.auto.min.js"></script> <!-- IE support -->
        <script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo base_url();?>assets/plugins/custom_general/custom_general.js"></script>

        <script type="text/javascript">
           var MOD_URL   = '<?php echo base_url();?>';
           var THEME_URL = '<?php echo themeUrl();?>';
           var OWN_LINKS = '<?php echo $own_links;?>';

            /** GENERATE DELAY FUNCTION **/
            var delay = (function(){
                var timer = 0;
                return function(callback, ms){
                    clearTimeout (timer);
                    timer = setTimeout(callback, ms);
                };
            })();
            // $('input').keyup(function() {
            //     delay(function(){
            //         alert('Time elapsed!');
            //     }, 1000 );
            // });
            /** END GENERATE DELAY FUNCTION **/

        </script>

    </head>

    <body class="tooltips <?php echo ($this->jCfg['user']['color']!=''?$this->jCfg['user']['color'].'-color':'') ?>">

        <!-- CHANGE BACKGROUND -->
        <div class="box-demo">
            <?php include('inc/menu_background.php'); ?>
        </div>
        <!-- END CHANGE BACKGROUND -->

        <!-- BEGIN PAGE -->
        <div class="wrapper">

            <div class="top-navbar">
                <?php include('inc/menu_top_navbar.php'); ?>
            </div>

            <div class="sidebar-left sidebar-nicescroller <?php echo ($this->jCfg['user']['bg']!=''?$this->jCfg['user']['bg'].'-color':'') ?>">
                <?php include('inc/menu_left_sidebar.php'); ?>
            </div>

            <?php include('inc/menu_right_sidebar.php'); ?>

            <!-- BEGIN PAGE CONTENT -->
            <div class="page-content <?php echo ($this->jCfg['user']['color']!=''?$this->jCfg['user']['color'].'-color':'') ?>">
                <div class="container-fluid">
                    <?php if(isset($this->content_top) && $this->content_top=='mail'){ ?>
                    <div class="mail-apps-wrap margin-bottom">
                        <div class="the-box <?php echo isset($this->content_bg)?$this->content_bg:'bg-success';?> no-border no-margin heading">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h1><i class="fa <?php echo isset($this->content_icon)?$this->content_icon:'fa-comment';?> icon-lg icon-circle icon-bordered"></i> <?php echo isset($title)?$title:'';?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <h1 class="page-heading"><?php echo isset($title)?$title:'';?></h1>
                    <?php } ?>

                    <?php get_breadcrumb($this->breadcrumb);?>

                    <!-- <ul class="nav nav-tabs <?php echo ($this->jCfg['user']['color']!=''?'nav-'.$this->jCfg['user']['color']:'') ?> item-color">
                        <?php //echo isset($links)?getLinks($links):'';?>
                    </ul> -->

                    <?php get_info_message();?>
