<?php
// $user_id  = "1";
// $store_id = get_user_store($user_id);
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

    <body class="tooltips" style="padding-top: 0px;">


        <!-- BEGIN PAGE -->
        <div class="wrapper">

            <!-- BEGIN PAGE CONTENT -->
            <div class="page-content" style="margin-left: 0px;">
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

                    <?php get_info_message();?>



<!-- INJECT -->
<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-warning btn-upload">
                <i class="fa fa-upload"></i> Upload
            </button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.btn-upload', function(e){
            e.preventDefault();
            var $this    = $(this);
            $('html, body').css('overflow','hidden');
            $('.popup-upload').addClass('active');
            $('#formUpload')[0].reset();
        });

        <?php if(isset($_GET['download']) && $_GET['download'] != ""){ ?>
            window.open("<?php echo $_GET['download'] ?>", '_blank');
        <?php } ?>

    });
</script>
<div class="popup popup-upload">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="false"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">
            <div class="popup-content">

                <form id="formUpload" action="<?php echo $own_links.'/upload'?>" method="post" autocomplete="off" enctype="multipart/form-data">
                    <legend>Upload</legend>
                    <div class="row popup-content-product mb-20">
                        <div class="col-sm-4" style="padding-right: 5px;">
                            <select name="type_name" class="form-control" required>
                                <option value="" selected>--- Pilih ---</option>
                                <option value="update_stock_shopee">Update Stok Shopee</option>

                            </select>
                        </div>
                        <div class="col-sm-8" style="padding-left: 0px; padding-right: 5px;">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="files" required />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <input type="hidden" name="thisAction" value="upload" />
                        <button type="submit" name="save_update" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-default popup-close" data-remove-content="false">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>




                </div><!-- /.container-fluid -->

                <footer>
                    &copy; 2017 <a href="<?php echo base_url();?>" target="_blank"><?php echo get_name_app("configuration_name");?></a>. All Rights Reserved.
                </footer>

            </div>
        </div>
        <!-- END PAGE -->

        <!-- MAIN APPS JS -->
        <script src="<?php echo base_url();?>assets/admin/js/apps.js"></script>
        <script src="<?php echo base_url();?>assets/admin/js/script.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/chat/chat.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/timeago/jquery.timeago.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                <?php if(isset($_GET['swal_type']) && $_GET['swal_type']!=''){ ?>
                    swal({
                        title: "<?php echo $_GET['swal_title'] ?>",
                        html: "<ul><?php echo $_GET['swal_msg'] ?></ul>",
                        type: "<?php echo $_GET['swal_type'] ?>"
                    });
                <?php } ?>
            });
        </script>
    </body>
</html>