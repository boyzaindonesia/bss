
<?php js_mask() ?>

<div class="the-box no-border">

    <div class="row">
        <?php
        $tempCount = 0;
        $arr = array('3','8','2','11');
        foreach ($arr as $k) {
            $v = get_orders_source($k);
            $name  = $v->orders_source_name;
            $bg    = $v->orders_source_color;
            $m = get_detail_orders_by_source($this->store_id, 5, $k);
            $value = count($m);
            $totalprice = 0;
            if($value > 0){
                foreach ($m as $key2 => $val2) {
                    $totalprice += $val2->orders_price_grand_total;
                }
            }
        ?>
        <div class="col-sm-25">
            <div class="the-box no-border bg-<?php echo $bg ?> tiles-information mb-10">
                <a href="javascript:void(0);" class="inherit" style="display: block;">
                    <i class="fa fa-users icon-bg"></i>
                    <div class="tiles-inner text-center">
                        <p style="text-transform: uppercase; min-height: 40px;"><?php echo $name ?></p>
                        <h1 id="tiles-value-<?php echo $i ?>" class="bolded mt-5 mb-5"><?php echo $value ?></h1>
                        <p><small><?php echo convertRp($totalprice) ?></small></p>
                    </div>
                </a>
            </div>
        </div>
        <?php } ?>
        <?php
        $name  = "Other";
        $bg    = "info";
        $m2 = get_detail_orders_by_source($this->store_id, 5, '1,4,5,6,7,9,10,12');
        $value = count($m2);
        $totalprice = 0;
        if($value > 0){
            foreach ($m as $key2 => $val2) {
                // $totalprice += $val2->orders_price_grand_total;
            }
        }
        ?>
        <div class="col-sm-25">
            <div class="the-box no-border bg-<?php echo $bg ?> tiles-information mb-10">
                <a href="javascript:void(0);" class="inherit" style="display: block;">
                    <i class="fa fa-users icon-bg"></i>
                    <div class="tiles-inner text-center">
                        <p style="text-transform: uppercase; min-height: 40px;"><?php echo $name ?></p>
                        <h1 id="tiles-value-<?php echo $i ?>" class="bolded mt-5 mb-5"><?php echo $value ?></h1>
                        <p><small><?php echo convertRp($totalprice) ?></small></p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="panel panel-danger mb-10">
        <div class="panel-heading">
            <div class="right-content">
                <button class="btn btn-danger btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-3"><i class="fa fa-chevron-up"></i></button>
            </div>
            <h3 class="panel-title">IMPORT PEMBAYARAN</h3>
        </div>
        <div id="panel-collapse-3" class="collapse in" style="height: auto;">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form id="formUpload" action="<?php echo base_url().'admin/transaction_process/upload_payment'?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-3" style="padding-right: 5px;">
                                <select name="orders_source_id" class="form-control" required>
                                    <option value="" selected>--- Pilih ---</option>
                                    <?php
                                    $arr = array('3','8','2','11');
                                    foreach ($arr as $k) {
                                        $v = get_orders_source($k);
                                        $selected = (($v->orders_source_id==$val->orders_source_id)?'selected':'');
                                        echo '<option value="'.$v->orders_source_id.'" '.$selected.'>'.$v->orders_source_name.' (Last import:'.getDateMonth(get_date_upload_payment($v->orders_source_id)).')</option>';
                                    }
                                    ?>
                                    <option value="lazada-claim">Lazada Claim</option>
                                </select>
                            </div>
                            <div class="col-sm-4" style="padding-left: 0px; padding-right: 5px;">
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
                <small>Contoh file excel: <a href="<?php echo base_url().'assets/collections/doc/form_claim_shopee.csv' ?>" target="_blank">Shopee</a></small>
            </div>
        </div>
    </div>

    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group pull-right">
            <div class="btn btn-success btn-save-multiple" data-url="<?php echo base_url().'admin/transaction_process/save_multiple_payment' ?>"><i class="fa fa-check"></i> Confirm Terima Pembayaran ( 0/0 )</div>
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
                                <label>Source</label>
                                <div class="btn-group">
                                    <?php $orders_source_id  = $this->jCfg['search']['orders_source_id']; ?>
                                    <select name="orders_source_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" selected >--- Pilih ---</option>
                                        <?php
                                        $get_orders_source = get_orders_source();
                                        foreach ($get_orders_source as $k => $v) { ?>
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

                        <div class="btn-toolbar toolbar-sort">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Short by</label>
                                <div class="btn-group">
                                    <?php $order_by  = $this->jCfg['search']['order_by']; ?>
                                    <?php $order_dir = $this->jCfg['search']['order_dir']; ?>
                                    <select name="order_by" class="form-control" onchange="this.form.submit()">
                                        <option value="mt_orders.orders_date-desc" <?php echo ($order_by=='mt_orders.orders_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="mt_orders.orders_date-asc" <?php echo ($order_by=='mt_orders.orders_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="mt_orders_shipping.orders_shipping_name-asc" <?php echo ($order_by=='mt_orders_shipping.orders_shipping_name'&&$order_dir=='asc'?'selected':'') ?> >Nama: A to Z</option>
                                        <option value="mt_orders_shipping.orders_shipping_name-desc" <?php echo ($order_by=='mt_orders_shipping.orders_shipping_name'&&$order_dir=='desc'?'selected':'') ?> >Nama: Z to A</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar toolbar-filter">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Per Page</label>
                                <div class="btn-group">
                                    <?php $filter = $this->jCfg['search']['filter']; ?>
                                    <select name="filter" class="form-control" style="min-width: 50px;" onchange="this.form.submit()">
                                        <option value="25" <?php echo ($filter=='25'?'selected':'') ?> >25</option>
                                        <option value="50" <?php echo ($filter=='50'?'selected':'') ?> >50</option>
                                        <option value="100" <?php echo ($filter=='100'?'selected':'') ?> >100</option>
                                        <option value="" <?php echo ($filter==''?'selected':'') ?> >ALL</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-toolbar toolbar-search">
            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" autocomplete="off" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                <label>Search:</label>
                <div class="btn-group">
                    <input type="search" data-reset="true" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
                    <input type="hidden" name="searchAction" value="search">
                </div>
            </form>
        </div>
    </div>

    <form id="form1" action="" method="post" autocomplete="false" enctype="multipart/form-data">
    <div class="relative no-border mt-20">
        <?php if(count($data) > 0){ ?>
        <div class="checkbox">
            <label><input type="checkbox" name="checkallfiles" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"> Check All</label>
        </div>
        <?php } ?>
        <div class="cart-list mb-0">
            <?php
            // debugCode($data);
            if(count($data) > 0){
                foreach($data as $r){
            ?>
            <div class="cart-list-item with-checkbox" style="<?php echo ($r->orders_claim_status>0?"background-color:#F6BB42;":"") ?>" data-id="<?php echo $r->orders_id ?>">
                <div class="checkbox">
                    <label><input type="checkbox" name="checked_files[]" value="<?php echo $r->orders_id;?>" title="<?php echo $r->orders_id;?>"> &nbsp;</label>
                </div>
                <div class="cart-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/images/logo-".get_orders_source_image($r->orders_source_id));?>);"></div>

                <div class="cart-list-content">
                    <div class="row">
                        <div class="col-sm-4">
                            <h6 class="no-margin no-padding"><?php echo $r->orders_invoice ?></h6>
                            <p class="no-margin no-padding"><strong><?php echo $r->orders_shipping_name ?></strong></p>
                            <button type="button" class="btn btn-warning btn-xs btn-cetak-again" data-id="<?php echo $r->orders_id;?>"" data-toggle="tooltip" data-original-title="Cetak Lagi"><i class="fa fa-print"></i> Cetak</button>
                            <h6 class="no-margin no-padding mt-5">Daftar Produk</h6>
                            <div class="relative cart-list cart-list-small no-margin mb-5">
                                <?php foreach ($r->orders_detail as $key2 => $val2) { ?>
                                <div class="cart-list-item">
                                    <div class="cart-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".$val2->product_images);?>);"></div>
                                    <div class="cart-list-content">
                                        <h6 class="no-margin no-padding"><strong><?php echo $val2->product_code ?></strong></h6>
                                        <p class="no-margin no-padding"><strong><?php echo $val2->product_name ?></strong></p>
                                        <h6 class="no-margin no-padding mb-5"><?php echo convertRp($val2->orders_detail_price) ?> x <?php echo $val2->orders_detail_qty ?> = <strong><?php echo convertRp(($val2->orders_detail_price * $val2->orders_detail_qty)) ?></strong></h6>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="relative">
                                <h6 class="no-margin no-padding"><strong>Total Produk: <?php echo count($r->orders_detail) ?></strong></h6>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <p class="no-margin no-padding mb-5">
                                <?php echo $r->orders_source_name;?>
                                <span class="label label-default"><?php echo convDateTimeTable($r->orders_date) ?></span>
                                <span style="color: #f00;"><?php echo xTimeAgo($r->orders_date, timestamp(), "d"); ?></span>
                            </p>
                            <h6 class="no-margin no-padding mb-5"><?php echo $r->orders_source_invoice;?></h6>
                            <h6 class="no-margin no-padding mb-5">Total Item: <?php echo $r->orders_total_qty ?> pcs</h6>
                            <h6 class="no-margin no-padding mb-5">Kurir: <?php echo $r->orders_courier_name." ".$r->orders_courier_service ?></h6>
                            <h6 class="no-margin no-padding mb-5">Estimasi Ongkir: <?php echo convertRp($r->orders_price_shipping); ?></h6>
                            <?php if($r->orders_price_debet_ship!=0){ ?>
                            <h6 class="no-margin no-padding mb-5 text-danger">Auto Debet Ongkir: -<?php echo convertRp($r->orders_price_debet_ship); ?></h6>
                            <?php } ?>

                            <h6 class="no-margin no-padding mb-5">Total Harga Beli: <?php echo convertRp($r->orders_price_buy_total); ?></h6>
                            <h6 class="no-margin no-padding mb-5">Total Harga Jual: <?php echo convertRp($r->orders_price_product); ?></h6>
                            <?php echo ($r->orders_price_insurance!=0?'<h6 class="no-margin no-padding">Asuransi: '.convertRp($r->orders_price_insurance).'</h6>':''); ?>

                            <h6 class="no-margin no-padding mb-5 text-info"><strong>Total Bayar: <?php echo convertRp($r->orders_price_grand_total); ?></strong></h6>
                            <h6 class="no-margin no-padding mb-5 text-danger">Total Jual di MP: <?php echo convertRp($r->orders_source_price); ?></h6>
                            <?php if(($r->orders_price_debet_ship!=0) && ($r->orders_price_product!=$r->orders_price_grand_total)){ ?>
                            <hr class="mt-5 mb-5">
                            <h6 class="no-margin no-padding mb-5"><strong>Revisi Total Bayar?</strong></h6>
                            <div class="input-group mb-10">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_grand_total[<?php echo $r->orders_id; ?>]" value="0" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Total Bayar" maxlength="23">
                            </div>
                            <?php } else { ?>
                                <input type="hidden" name="orders_price_grand_total[<?php echo $r->orders_id; ?>]" value="0">
                            <?php } ?>
                            <?php if($r->orders_claim_status > 0){
                                $orders_claim = get_orders_claim($r->orders_claim_status);
                            ?>
                            <span class="label label-<?php echo $orders_claim['color']?>"><?php echo $orders_claim['name']?></span>
                            <h6 class="no-margin no-padding mb-5">Jumlah Claim: <?php echo convertRp($r->orders_claim_price); ?></h6>
                            <h6 class="no-margin no-padding">Tanggal Claim: <?php echo convDate($r->orders_claim_date); ?></h6>
                            <?php } ?>
                        </div>
                        <div class="col-sm-4">
                            <p class="no-margin no-padding mb-5">CAIR PEMBAYARAN</p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Pengembalian Dana</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_price_return[<?php echo $r->orders_id; ?>]" value="0" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Pengembalian Dana" maxlength="23">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Perlu Claim</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_claim_price[<?php echo $r->orders_id; ?>]" value="0" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Jumlah Claim" maxlength="23">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Auto Debet Ongkir</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_price_debet_ship[<?php echo $r->orders_id; ?>]" value="<?php echo $r->orders_price_debet_ship; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Ongkos Kirim yang diteruskan ke Kurir" maxlength="23">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Cashback</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_voucher_price[<?php echo $r->orders_id; ?>]" value="0" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Voucher / Cashback" maxlength="23">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Total Cair</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_payment_price[<?php echo $r->orders_id; ?>]" value="<?php echo ($r->orders_price_debet_ship!=0?$r->orders_price_product:$r->orders_price_grand_total); ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Pembayaran dari marketplace" maxlength="23">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Fee Admin</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_price_fee[<?php echo $r->orders_id; ?>]" value="0" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Biaya Admin" maxlength="23">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-xs btn-save"><i class="fa fa-check"></i> Confirm Terima Pembayaran</button>
                            <?php if($r->orders_claim_status == 0){ ?>
                            <button type="button" class="btn btn-danger btn-xs btn-claim" data-id="<?php echo $r->orders_id;?>" data-status="2" data-price="">Claim</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo 'Orderan tidak ditemukan...';
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

<div class="popup popup-orders">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
    </div>
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
        $('.btn-save-multiple').html('<i class="fa fa-check"></i> Confirm Terima Pembayaran ( '+checked_files.length+'/<?php echo count($data)?> )');
    }

    $(document).ready(function() {
        $(document).on('click','input[type="text"]',function(){ this.select(); });

        $(document).on('change', 'input[name="checked_files[]"]', function(e){
            e.preventDefault();
            if($(this).is(':checked')){
                $(this).parents('tr').addClass('selected');
            } else {
                $(this).parents('tr').removeClass('selected');
            }
            calc_check_files();
        });

        $(document).on('click', '.btn-save-multiple', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau disimpan.",
                    type: "error"
                });
            } else {
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
                        url: dataUrl,
                        data: form1.serialize(),
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

        $(document).on('click', '.btn-save', function(e){
            e.preventDefault();
            var $this = $(this);
            var cartListItem = $this.parents('.cart-list-item');
            var thisId = cartListItem.attr('data-id');

            var orders_price_grand_total = cartListItem.find('input[name="orders_price_grand_total['+thisId+']"]').val();
            var orders_payment_price     = cartListItem.find('input[name="orders_payment_price['+thisId+']"]').val();
            var orders_voucher_price     = cartListItem.find('input[name="orders_voucher_price['+thisId+']"]').val();
            var orders_price_debet_ship  = cartListItem.find('input[name="orders_price_debet_ship['+thisId+']"]').val();
            var orders_claim_price       = cartListItem.find('input[name="orders_claim_price['+thisId+']"]').val();
            var orders_price_return      = cartListItem.find('input[name="orders_price_return['+thisId+']"]').val();

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
                    url: MOD_URL+'admin/transaction_process/save_payment',
                    data: {'thisId':thisId,'orders_price_grand_total':orders_price_grand_total,'orders_payment_price':orders_payment_price,'orders_voucher_price':orders_voucher_price,'orders_price_debet_ship':orders_price_debet_ship,'orders_claim_price':orders_claim_price,'orders_price_return':orders_price_return,'thisAction':'save'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){

                    },
                    success: function(data){
                        if(data.err == false){
                            cartListItem.remove();

                            swal({
                                title: "Success!",
                                text: data.msg,
                                type: "success",
                                showConfirmButton: false,
                                timer: 800
                            }).then(
                            function () {},
                            function (dismiss) {
                                // console.log('close');
                                // if (dismiss === 'timer') {
                                //     console.log('I was closed by the timer')
                                // }
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
        });

        $(document).on('click', '.btn-claim', function(e){
            e.preventDefault();
            var $this       = $(this);
            var $thisId     = $this.attr('data-id');
            var $thisStatus = $this.attr('data-status');
            var $thisPrice  = $this.attr('data-price');
            if($thisId != ''){
                var swalText = "Yakin ingin Claim Pembayaran!";
                swal({
                    title: 'Are you sure?',
                    text: swalText,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Claim it!',
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
                        url: MOD_URL+'admin/transaction_process/save_claim',
                        data: {'thisId':$thisId,'thisStatus':$thisStatus,'thisPrice':$thisPrice,'thisAction':'save'},
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

        $(document).on('click', '.btn-cetak-again', function(e){
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
                        url: MOD_URL+'admin/transaction_form/form_detail_courier',
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

    });

</script>
