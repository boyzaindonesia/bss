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

    <div class="btn-toolbar">
        <div class="btn-group">
            <div class="btn btn-info btn-tindakan"><i class="fa fa-plus"></i> Sudah Ditindak ( 0 )</div>
        </div>
    </div>

    <form id="form1" action="<?php echo $own_links.'/save_multi_notif_status' ?>" method="post" enctype="multipart/form-data">
    <div class="relative no-border mt-20">
        <div class="checkbox">
            <label><input type="checkbox" name="checkallfiles" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"> Check All</label>
        </div>

        <div class="product-list mb-0">
            <?php
            $timestamp = timestamp();
            // debugCode($data);
            if(count($data) > 0){
                $i = 1;
                $pageNumber = $this->uri->segment(4);
                if(!empty($pageNumber)){
                    $i = $pageNumber + 1;
                }
                foreach($data as $r){
            ?>
            <div class="product-list-item with-checkbox magnific-popup-wrap" data-id="<?php echo $r->notif_id ?>">
                <div class="checkbox">
                    <label><input type="checkbox" name="checked_files[]" value="<?php echo $r->notif_id;?>" title="<?php echo $r->notif_id;?>"> &nbsp;</label>
                </div>

                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/large/".get_cover_image_detail($r->product_id));?>">
                    <div class="product-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>);"></div>
                </a>
                <div class="hide">
                    <?php
                    $get_image_detail = get_image_detail($r->product_id);
                    $ii = 0;
                    foreach ($get_image_detail as $key) {
                        if($ii > 0){
                        ?>
                    <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/large/".$key->image_filename);?>">&nbsp;</a>
                    <?php
                        }
                        $ii += 1;
                    } ?>
                </div>

                <div class="product-list-content">
                    <p class="product-list-title no-margin no-padding mb-5"><strong><?php echo $r->product_name.' - '.$r->product_code;?></strong></p>
                    <h6 class="no-margin no-padding mb-5">Harga Beli <span style=""><?php echo convertRp($r->product_price_buy);?></span></h6>
                    <h6 class="no-margin no-padding mb-5">Harga Jual <span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?></h6>

                    <div class="product-list-action">

                        <?php
                        if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){
                            $varian_text = $r->product_name_simple." update ready stok terakhir tanggal: ".convDateTable($timestamp)."<br>";
                            foreach (json_decode($r->product_stock_detail) as $key3 => $val3) {
                                if($r->product_stock_copy == 1){
                                    $varian_text .= "- No ".ltrim($val3->id,"0")." ".($val3->qty > 0?"":"(HABIS)")."<br>";
                                } else if($r->product_stock_copy == 2){
                                    $varian_text .= "- ".$val3->name." ".($val3->qty > 0?"":"(HABIS)")."<br>";
                                }
                            }
                            ?>
                            <div class="btn-group pull-right mr-5">
                                <a href="javascript:void(0);" class="btn btn-success btn-xs btn-copy-clipboard" data-text-clipboard="<?php echo $varian_text ?>"><i class="fa fa-clone"></i> Copy Last Stock</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="relative">
                    <p class="no-margin no-padding">Stok: <?php echo $r->product_stock;?> pcs</p>
                    <?php if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){
                        $foundSold = false;
                        ?>
                    <ul class="product-list-item-stock clearfix mb-5">
                        <?php
                        foreach (json_decode($r->product_stock_detail) as $key => $value) {
                            if($value->status==1){ ?>
                            <li><p><?php echo $value->name ?> = <?php echo $value->qty ?></p></li>
                        <?php } else { $foundSold = true; } ?>
                        <?php } ?>
                    </ul>
                    <?php if($r->product_status_id==1 && $foundSold){ ?>
                    <p class="no-margin no-padding text-danger">Variasi Sold:</p>
                    <ul class="product-list-item-stock clearfix mb-0">
                        <?php
                        foreach (json_decode($r->product_stock_detail) as $key => $value) {
                            if($value->status==2){ ?>
                            <li><p class="text-danger"><?php echo $value->name ?> = <?php echo $value->qty ?></p></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <?php
                }
            } else {
                echo 'Produk tidak ditemukan...';
            }
            ?>
        </div>

    </div>
    </form>

    <?php if(isset($paging) && $paging!=''){ ?>
    <div class="btn-toolbar mt-20 mb-20" role="toolbar">
        <div class="btn-group pull-right">
            <?php echo $paging ?>
        </div>
    </div>
    <?php } ?>

</div>

<script type="text/javascript">
    function check_all_files(e){
        var checkbox = $('input[name="checked_files[]"]');
        if($(e).is(':checked')){
            checkbox.prop('checked', true);
            checkbox.parents('tr').addClass('selected');
        } else {
            checkbox.prop('checked', false);
            checkbox.parents('tr').removeClass('selected');
        }
        calc_check_files();
    }

    calc_check_files();
    function calc_check_files(){
        var form1 = $('form#form1');
        var checked_files = form1.find('input[name="checked_files[]"]:checked');
        $('.btn-tindakan').html('<i class="fa fa-plus"></i> Sudah Ditindak ( '+checked_files.length+' )');
    }

    $(document).ready(function() {
        $(document).on('change', 'input[name="checked_files[]"]', function(e){
            e.preventDefault();
            if($(this).is(':checked')){
                $(this).parents('tr').addClass('selected');
            } else {
                $(this).parents('tr').removeClass('selected');
            }
            calc_check_files();
        });
        $(document).on('click', '.btn-tindakan', function(e){
            e.preventDefault();
            var form1 = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau ditindak.",
                    type: "error"
                });
            } else {
                form1.submit();

                // var thisId  = "";
                // var thisVal = "";
                // checked_files.each(function() {
                //     thisVal = $(this).val();
                //     thisId += (thisId=="" ? thisVal : "-" + thisVal);
                // });

                // var url   = $(this).attr('data-url');
                // window.open(url+'?id='+thisId, '_blank');
            }
        });


    });
</script>




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