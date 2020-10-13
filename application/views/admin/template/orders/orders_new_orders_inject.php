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

<ul class="nav nav-tabs item-color">
    <?php
    $arr_count_orders = array(3);
    foreach ($arr_count_orders as $id) {
        $orders_status  = get_orders_status($id);
        $name           = $orders_status['name'];
        $url            = $orders_status['url'];
    ?>
    <li class="<?php echo ($tab=='tab'.$id?'active':'') ?>"><a href="#"><?php echo $name ?></a></li>
    <?php } ?>
</ul>


<?php
    $timestamp = timestamp();
    // $dateStart = convDatepickerDec(getMinDay($timestamp, 0));
    // $dateEnd   = convDatepickerDec($timestamp);
    // $dateStart = "2018-09-28";
    // $dateEnd   = "2018-09-28";
?>

<div class="the-box no-border">

    <div class="panel panel-danger mb-10">
        <div class="panel-heading">
            <div class="right-content">
                <button class="btn btn-danger btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-3"><i class="fa fa-chevron-up"></i></button>
            </div>
            <h3 class="panel-title">IMPORT DATA</h3>
        </div>
        <div id="panel-collapse-3" class="collapse in" style="height: auto;">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-8">
                        <form id="formUpload" action="<?php echo $own_links.'/upload'?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <select name="orders_source_id" class="form-control" required>
                                    <option value="" selected>--- Pilih ---</option>
                                    <?php
                                    $arr = array('3','8','2','11');
                                    foreach ($arr as $k) {
                                        $v = get_orders_source($k);
                                        $selected = (($v->orders_source_id==$val->orders_source_id)?'selected':'');
                                        echo '<option value="'.$v->orders_source_id.'" '.$selected.'>'.$v->orders_source_name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-6" style="padding-left: 0px; padding-right: 5px;">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input type="file" name="files" required />
                                        </span>
                                    </span>
                                    <input type="text" name="text_images" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-sm-2" style="padding-left: 0px; padding-right: 5px;">
                                <input type="hidden" name="thisAction" value="upload" />
                                <input type="submit" class="btn btn-danger" value="Upload" />
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <small>Cara Import Buat Label dari: <a href="https://youtu.be/I2lkOYWcBrE" target="_blank">Tokopedia</a>, <a href="https://youtu.be/iJ2UJ4qmEMI" target="_blank">Shopee</a>, <a href="https://youtu.be/yc-YJ22phSM" target="_blank">Bukalapak</a>, <a href="" target="_blank">Lazada (convert dahulu ke .xls)</a></small>
                <br/>
                <small>Contoh file excel: <a href="<?php echo base_url().'assets/collections/doc/form_order_tokopedia.xls' ?>" target="_blank">Tokopedia</a>, <a href="<?php echo base_url().'assets/collections/doc/form_order_shopee.xls' ?>" target="_blank">Shopee</a>, <a href="<?php echo base_url().'assets/collections/doc/form_order_bukalapak.csv' ?>" target="_blank">Bukalapak</a></small>
            </div>
        </div>
    </div>

    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group">
            <div class="btn btn-success btn-popup" data-id="0"><i class="fa fa-plus"></i> Buat Label</div>
        </div>

        <div class="btn-group pull-right">
            <div class="btn btn-warning btn-cetak" data-url="<?php echo base_url().'print-address' ?>"><i class="fa fa-print"></i> Cetak Alamat ( 0/0 )</div>
        </div>
        <div class="btn-group pull-right">
            <div class="btn btn-primary btn-cetak-simple" data-url="<?php echo base_url().'print-address-simple' ?>"><i class="fa fa-print"></i> Cetak Simple ( 0/0 )</div>
        </div>
        <div class="btn-group pull-right">
            <div class="btn btn-info btn-cetak-booking" data-url="<?php echo base_url().'print-address-booking' ?>"><i class="fa fa-print"></i> Cetak Kode Boking ( 0/0 )</div>
        </div>
    </div>

    <div class="relative clearfix">
        <div class="panel panel-toolbar panel-dark">
            <div class="panel-heading">
                <div class="right-content">
                <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-toolbar"><i class="fa fa-chevron-down"></i></button>
                </div>
                <h3 class="panel-title">Option Search</h3>
            </div>
            <div id="panel-collapse-toolbar" class="collapse" style="height: 0px;">
                <div class="panel-body">
                    <div class="relative clearfix">
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Cetak</label>
                                <div class="btn-group">
                                    <?php $orders_print  = $this->jCfg['search']['orders_print']; ?>
                                    <select name="orders_print" class="form-control" onchange="this.form.submit()">
                                        <option value="" selected >--- Pilih ---</option>
                                        <option value="belum" <?php echo ($orders_print=="belum"?'selected':'') ?> >Belum DiCetak</option>
                                        <option value="sudah" <?php echo ($orders_print=="sudah"?'selected':'') ?> >Sudah DiCetak</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Source</label>
                                <div class="btn-group">
                                    <?php $orders_source_id  = $this->jCfg['search']['orders_source_id']; ?>
                                    <select name="orders_source_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" selected >--- Pilih ---</option>
                                        <?php
                                        $arr = array('3','8','2','11','7','5','6','4','9','10');
                                        foreach ($arr as $k) {
                                            $v = get_orders_source($k);
                                        ?>
                                        <option value="<?php echo $v->orders_source_id ?>" <?php echo ($orders_source_id==$v->orders_source_id?'selected':'') ?> ><?php echo $v->orders_source_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Kurir</label>
                                <div class="btn-group">
                                    <?php $orders_courier_id  = $this->jCfg['search']['orders_courier_id']; ?>
                                    <select name="orders_courier_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" selected >--- Pilih ---</option>
                                        <?php
                                        $arr_orders_courier = get_orders_courier();
                                        foreach ($arr_orders_courier as $k => $v) { ?>
                                        <option value="<?php echo $v->orders_courier_id ?>" <?php echo ($orders_courier_id==$v->orders_courier_id?'selected':'') ?> ><?php echo $v->orders_courier_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable-all-custom-sort table-font-small">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort"><input type="checkbox" name="checkallfiles" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"></th>
                    <th class="nobr text-center">Date</th>
                    <th class="nobr text-center">Source</th>
                    <th class="nobr text-center" width="90">Resi</th>
                    <th class="nobr text-center">Kurir</th>
                    <th class="nobr text-center">Paket</th>
                    <th>Nama Penerima</th>
                    <th>Catatan</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $total_resi = 0;
            if(count($data) > 0){
                foreach($data as $r){
                    $tr_class = '';
                    if($r->orders_shipping_dropship == 1){
                        $tr_class = 'warning';
                    }

                    $orders_ship_name  = $this->store_name;
                    $orders_ship_phone = $this->store_phone;
                    if($r->orders_shipping_dropship == 1){
                        $orders_ship_name  = $r->orders_ship_name;
                        $orders_ship_phone = $r->orders_ship_phone;
                    }

                    $checked_files = "";
                    if($r->orders_shipping_resi != ''){
                        $total_resi += 1;
                        $checked_files = 'checked';
                    }

                ?>
                <tr class="<?php echo $tr_class ?>" data-id="<?php echo $r->orders_id;?>" title="<?php echo 'Nama Pengirim: '.$orders_ship_name ?>">
                    <td class="nobr text-center"><input type="checkbox" name="checked_files[]" value="<?php echo $r->orders_id;?>" <?php echo $checked_files ?> ></td>
                    <td class="nobr text-center">
                        <span class="label <?php echo ($r->orders_print == 0?'label-danger':'label-default') ?>"><?php echo getYearMonthDate($r->orders_date) ?></span>
                        <br/><span class="label label-<?php echo get_orders_status($r->orders_status)['color'] ?>"><?php echo get_orders_status($r->orders_status)['name'] ?></span>
                        <?php if($r->orders_print == 1){ ?>
                            <br/><span class="label label-success">Sudah Dicetak</span>
                        <?php } ?>
                    </td>
                    <td class="nobr text-center">
                        <?php echo get_orders_source($r->orders_source_id)->orders_source_name;?>
                        <br>
                        <?php
                        if($r->orders_source_id == '3'){
                            $exp = explode("/", $r->orders_source_invoice);
                            echo end($exp);
                        } else {
                            echo $r->orders_source_invoice;
                        }
                        ?>
                    </td>
                    <td class="nobr">
                        <div class="shipping_resi"><?php echo ($r->orders_shipping_resi!=''?$r->orders_shipping_resi:'') ?></div>
                        <?php echo ($r->orders_shipping_dropship==1?'(Dropship)':'')?>
                    </td>
                    <td class="nobr">
                        <?php
                            $get_orders_courier = get_orders_courier($r->orders_courier_id);
                            echo $get_orders_courier->orders_courier_name.' - '.$get_orders_courier->orders_courier_service;
                        ?>
                        <button type="button" class="btn btn-warning btn-xs btn-popup-detail-courier" data-id="<?php echo $r->orders_id;?>" data-toggle="tooltip" data-original-title="Detail Courier"><i class="fa fa-clipboard"></i></button>
                        <div class="price_shipping"><?php echo ($r->orders_price_shipping==0?'':'Ongkir: '.convertRp($r->orders_price_shipping)) ?></div>
                        <div class="price_insurance"><?php echo ($r->orders_price_insurance==0?'':'Asuransi: '.convertRp($r->orders_price_insurance)) ?></div>
                        <div class="shipping_weight"><?php echo ($r->orders_shipping_weight<2?'':'Berat: '.$r->orders_shipping_weight) ?></div>
                    </td>
                    <td class="nobr">
                        <div class="product_title"><?php echo $r->orders_product_category_title;?></div>
                        <div class="price_product"><?php echo ($r->orders_price_product==0?'':'Harga: '.convertRp($r->orders_price_product)) ?></div>
                    </td>
                    <td>
                        <?php echo $r->orders_shipping_name;?>
                        <br>
                        <?php echo getFirstParaNumb($r->orders_shipping_address,40) ?>
                    </td>
                    <td><?php echo getFirstParaNumb($r->orders_noted,40) ?></td>
                    <td class="nobr">
                        <button type="button" class="btn btn-info btn-xs btn-popup" data-id="<?php echo $r->orders_id;?>" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></button>

                    </td>
                </tr>
                <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    </form>

</div>

<!-- datatable -->
<?php get_data_table();?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datatable-all-custom-sort').dataTable({
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "columnDefs": [ {"targets": 'no-sort', "orderable": false} ],
            "order": [[ 6, "asc" ]],
            "iDisplayLength": -1
        });
    });
</script>

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

    <?php if(count($data) == $total_resi){ ?>
        $('input[name="checkallfiles"]').prop('checked', true);
    <?php } ?>

    calc_check_files();
    function calc_check_files(){
        var form1 = $('form#form1');
        var checked_files = form1.find('input[name="checked_files[]"]:checked');
        $('.btn-cetak').html('<i class="fa fa-print"></i> Cetak Alamat ( '+checked_files.length+'/<?php echo count($data)?>  )');
        $('.btn-cetak-simple').html('<i class="fa fa-print"></i> Cetak Simple ( '+checked_files.length+'/<?php echo count($data)?> )');
        $('.btn-cetak-booking').html('<i class="fa fa-print"></i> Cetak Kode Booking ( '+checked_files.length+'/<?php echo count($data)?>  )');
        $('.btn-process-multiple').html('<i class="fa fa-send"></i> Proses Pesanan ( '+checked_files.length+'/<?php echo count($data)?> )');
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

        $(document).on('click', '.btn-cetak', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau dicetak.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                window.open(dataUrl+'?id='+thisId, '_blank');
            }
        });

        $(document).on('click', '.btn-cetak-simple', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau dicetak.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                window.open(dataUrl+'?id='+thisId, '_blank');
            }
        });

        $(document).on('click', '.btn-cetak-booking', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau dicetak.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                window.open(dataUrl+'?id='+thisId, '_blank');
            }
        });

        $(document).on('click', '.btn-process-multiple', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau diproses.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                setTimeout(function(){
                    $.ajax({
                        type: 'POST',
                        url: dataUrl,
                        data: {'thisId':thisId,'thisAction':'save'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            if(data.err == false){
                                swal({
                                    title: "Success!",
                                    text: "Berhasil proses pesanan!",
                                    type: "success",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(
                                function () {},
                                function (dismiss) {
                                    setTimeout(function(){
                                        if(data.href != ''){
                                            window.location.href = data.href;
                                        } else {
                                            window.location.reload(true);
                                        }
                                    },300);
                                });
                            } else {
                                var data_msg = '';
                                if(data.msg != ''){
                                    $.each( data.msg, function( key, value ) {
                                        data_msg += '<li>'+value+'</li>';
                                    });
                                }

                                swal({
                                    title: "Error!",
                                    html: "<ul class='text-left'>"+data_msg+"</ul>",
                                    type: "error"
                                });
                            }
                        },
                        error: function(jqXHR){
                            var response = jqXHR.responseText;
                            swal({
                                title: "Error!",
                                html: response,
                                type: "error"
                            });
                        }
                    });
                },400);
            }
        });

    });
</script>

<div class="popup popup-orders">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
    </div>
</div>

<?php js_mask() ?>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.btn-popup', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });
            if($thisVal != ''){
                setTimeout(function(){
                    $.ajax({
                        type: 'POST',
                        url: OWN_LINKS+'/views',
                        data: {'thisVal':$thisVal,'thisAction':'view'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            $('.popup-orders').addClass('active');
                            $('.popup-orders .popup-content').html(data.content);
                            // console.log(data.result);

                            $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                            $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                            $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});

                            $(document).on('click','input[type="number"]',function(){ this.select(); });
                            $(document).on('click','input.moneyRp_masking',function(){ this.select(); });

                            swal({
                                title: "Loading!",
                                text: "",
                                type: "loading",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                customClass: 'swal2-small',
                                timer: 400
                            }).catch(swal.noop);
                        },
                        error: function(jqXHR){
                            var response = jqXHR.responseText;
                            swal({
                                title: "Error!",
                                html: response,
                                type: "error"
                            });
                        }
                    });
                },400);
            }
        });

        $(document).on('click', '.btn-popup-detail-courier', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });
            if($thisVal != ''){
                setTimeout(function(){
                    $.ajax({
                        type: 'POST',
                        url: OWN_LINKS+'/form_detail_courier',
                        data: {'thisVal':$thisVal,'thisAction':'view'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            $('.popup-orders').addClass('active');
                            $('.popup-orders .popup-content').html(data.content);
                            // console.log(data.result);

                            $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                            $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                            $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});

                            $(document).on('click','input[type="text"]',function(){ this.select(); });
                            $(document).on('click','input[type="number"]',function(){ this.select(); });
                            $(document).on('click','input.moneyRp_masking',function(){ this.select(); });
                            swal({
                                title: "Loading!",
                                text: "",
                                type: "loading",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                customClass: 'swal2-small',
                                timer: 400
                            }).catch(swal.noop);
                        },
                        error: function(jqXHR){
                            var response = jqXHR.responseText;
                            swal({
                                title: "Error!",
                                html: response,
                                type: "error"
                            });
                        }
                    });
                },400);
            }
        });

        $(document).on('click', '.btn-delete', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisId = $this.attr('data-id');
            if($thisId != ''){
                swal({
                    title: 'Are you sure?',
                    text: "Yakin ingin membatalkan pesanan!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                    swal({
                        title: "Loading!",
                        text: "",
                        type: "loading",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        customClass: 'swal2-small'
                    });
                    $.ajax({
                        type: 'POST',
                        url: OWN_LINKS+'/save_canceled_orders',
                        data: {'thisId':$thisId,'thisAction':'save'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            if(data.err == false){
                                swal({
                                    title: "Success!",
                                    text: data.msg,
                                    type: "success",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(
                                function () {},
                                function (dismiss) {
                                    setTimeout(function(){
                                        window.location.reload(true);
                                    },300);
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    html: data.msg,
                                    type: "error"
                                });
                            }
                        },
                        error: function(jqXHR){
                            var response = jqXHR.responseText;
                            swal({
                                title: "Error!",
                                html: response,
                                type: "error"
                            });
                        }
                    });
                }).catch(swal.noop);
            }
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('submit', 'form.form_save_new_orders', save_new_orders );

        function save_new_orders(e){
            if (typeof e !== 'undefined') e.preventDefault();
            var $this = $(this);
            var form = $this;

            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });

            setTimeout(function(){
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    async: false,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){

                    },
                    success: function(data){
                        if(data.err == false){

                            swal({
                                title: "Success!",
                                text: data.msg,
                                type: "success",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(
                            function () {},
                            function (dismiss) {
                                // console.log('close');
                                // if (dismiss === 'timer') {
                                //     console.log('I was closed by the timer')
                                // }

                                setTimeout(function(){
                                    $('.popup-orders .popup-close').trigger('click');
                                    window.location.reload(true);
                                },300);
                            });

                        } else {
                            swal({
                                title: "Error!",
                                html: data.msg,
                                type: "error"
                            });
                        }
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        swal({
                            title: "Error!",
                            html: response,
                            type: "error"
                        });
                    }
                });

                return false;
            },400);
        }

        $(document).on('submit', 'form.form_save_detail_courier', save_detail_courier );

        function save_detail_courier(e){
            if (typeof e !== 'undefined') e.preventDefault();
            var $this = $(this);
            var form = $this;

            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });

            setTimeout(function(){
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    async: false,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){

                    },
                    success: function(data){
                        if(data.err == false){

                            swal({
                                title: "Success!",
                                text: data.msg,
                                type: "success",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(
                            function () {},
                            function (dismiss) {
                                // console.log('close');
                                // if (dismiss === 'timer') {
                                //     console.log('I was closed by the timer')
                                // }
                                var orders_id = form.find('input[name="orders_id"]').val();
                                var product_title = form.find('input[name="orders_product_category_title"]').val();
                                var shipping_resi = form.find('input[name="orders_shipping_resi"]').val();
                                var shipping_weight = form.find('input[name="orders_shipping_weight"]').val();

                                if(product_title != ""){
                                    $('#form1 table tr[data-id='+orders_id+'] .product_title').html(""+product_title+"");
                                } else {
                                    $('#form1 table tr[data-id='+orders_id+'] .product_title').html("");
                                }
                                if(shipping_resi != ""){
                                    $('#form1 table tr[data-id='+orders_id+'] .shipping_resi').html(shipping_resi);
                                } else {
                                    $('#form1 table tr[data-id='+orders_id+'] .shipping_resi').html("");
                                }
                                if(shipping_weight > 1){
                                    $('#form1 table tr[data-id='+orders_id+'] .shipping_weight').html("Berat: "+(shipping_weight));
                                } else {
                                    $('#form1 table tr[data-id='+orders_id+'] .shipping_weight').html("");
                                }

                                $('.popup-orders .popup-close').trigger('click');
                            });

                        } else {
                            swal({
                                title: "Error!",
                                html: data.msg,
                                type: "error"
                            });
                        }
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        swal({
                            title: "Error!",
                            html: response,
                            type: "error"
                        });
                    }
                });

                return false;

            },400);
        }

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