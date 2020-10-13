
<div class="row">
    <?php
    for ($i=0; $i <= 4; $i++) {
        switch ($i) {
            case 0: $name = "Jumlah Orderan"; $bg = "warning"; $value = count($data_mp_payment); break;
            case 1: $name = "Tidak Ditemukan"; $bg = "primary"; $value = count($data_not_found); break;
            case 2: $name = "Belum Dibayarkan"; $bg = "info"; $value = count($data_orders); break;
            case 3: $name = "Dibayarkan"; $bg = "success"; $value = count($data_done_pay); break;
            case 4: $name = "Perlu Claim"; $bg = "danger"; $value = count($data_claim); break;
            default: $name = ""; $bg = ""; $value = "0"; break;
        }
    ?>
    <div class="col-sm-25">
        <div class="the-box no-border bg-<?php echo $bg ?> tiles-information mb-10">
            <a href="javascript:void(0);" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;"><?php echo $name ?></p>
                    <h1 id="tiles-value-<?php echo $i ?>" class="bolded mt-5 mb-5"><?php echo $value ?></h1>
                </div>
            </a>
        </div>
    </div>
    <?php } ?>
</div>

<?php js_mask() ?>

<div class="the-box no-border">

    <?php if(count($data_not_found) > 0){ ?>
    <h3 class="panel-title text-uppercase text-danger">Data Invoice Tidak Ditemukan</h3>
    <ol class="clearfix">
    <?php foreach($data_not_found as $key => $r){ ?>
        <li>Row ke: <?php echo $r->mp_row ?>, Invoice: <?php echo $r->mp_source_invoice ?>, User: <?php echo $r->mp_username ?></li>
    <?php } ?>
    </ol>
    <hr class="no-margin mt-10 mb-10">
    <?php } ?>

    <?php if(count($data_orders) > 0){ ?>
    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group pull-right">
            <div class="btn btn-success btn-save-multiple" data-url="<?php echo base_url().'admin/transaction_process/save_verifikasi_payment' ?>"><i class="fa fa-check"></i> Confirm Terima Pembayaran ( 0/0 )</div>
        </div>
    </div>
    <?php } ?>
    <h3 class="panel-title text-uppercase">Data Pembayaran</h3>
    <div class="relative clearfix mt-20">
        <div class="row">
            <div class="col-sm-4" style="padding:0 3px;">
                <ul class="list-group">
                    <li class="list-group-item">Total Qty<span class="badge badge-success"><?php echo $data_details["total_qty_product"] ?> item</span></li>
                    <li class="list-group-item">Total Price Product<span class="badge badge-success"><?php echo convertRp($data_details["total_price_product"]) ?></span></li>
                    <li class="list-group-item">Total Price Discount<span class="badge badge-success"><?php echo convertRp($data_details["total_price_discount"]) ?></span></li>
                    <li class="list-group-item">Total Cashback Seller<span class="badge badge-success"><?php echo convertRp($data_details["total_cashback_seller"]) ?></span></li>
                </ul>
            </div>
            <div class="col-sm-4" style="padding:0 3px;">
                <ul class="list-group">
                    <li class="list-group-item">Total Price Shipping<span class="badge badge-success"><?php echo convertRp($data_details["total_price_shipping"]) ?></span></li>
                    <li class="list-group-item">Total Price Subsidi<span class="badge badge-success"><?php echo convertRp($data_details["total_price_subsidi"]) ?></span></li>
                    <li class="list-group-item">Total Price Debet Ship<span class="badge badge-success"><?php echo convertRp($data_details["total_price_debet_ship"]) ?></span></li>
                    <li class="list-group-item">Total Price Insurance<span class="badge badge-success"><?php echo convertRp($data_details["total_price_insurance"]) ?></span></li>
                </ul>
            </div>
            <div class="col-sm-4" style="padding:0 3px;">
                <ul class="list-group">
                    <li class="list-group-item">Total Price Return<span class="badge badge-success"><?php echo convertRp($data_details["total_price_return"]) ?></span></li>
                    <li class="list-group-item">Total Price Fee<span class="badge badge-success"><?php echo convertRp($data_details["total_price_fee"]) ?></span></li>
                    <li class="list-group-item">Total Voucher Price<span class="badge badge-success"><?php echo convertRp($data_details["total_voucher_price"]) ?></span></li>
                    <li class="list-group-item">Total Price Grand Total<span class="badge badge-success"><?php echo convertRp($data_details["total_price_grand_total"]) ?></span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="btn-group pull-right" style="min-height: 50px;">
        <a href="<?php echo base_url().'admin/transaction_process/clear_verifikasi_payment' ?>"><div class="btn btn-default">Clear All</div></a>
    </div>
    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="relative no-border mt-20">
        <?php if(count($data_orders) > 0){ ?>
        <div class="checkbox">
            <label><input type="checkbox" name="checkallfiles" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"> Check All</label>
        </div>
        <?php } ?>
        <div class="cart-list cart-list-small mb-0">
            <?php
            // debugCode($data_not_found);
            // debugCode($data_orders);
            $count = 0;
            if(count($data_orders) > 0){
                foreach($data_orders as $key => $r){
                    if($count < 100){
                        $count += 1;

                        if($r->orders_source_price != ($r->mp_price_product - $r->mp_price_discount)){
                            $r->mp_claim_status = TRUE;
                        }
            ?>
            <div class="cart-list-item with-checkbox" style="<?php echo ($r->mp_claim_status==TRUE?"background-color:#F6BB42;":"") ?>" data-id="<?php echo $r->orders_id ?>">
                <div class="checkbox">
                    <label><input type="checkbox" name="checked_files[]" value="<?php echo $r->orders_id;?>" title="<?php echo $r->orders_id;?>" <?php echo ($r->mp_claim_status==FALSE?"checked":"") ?> > &nbsp;</label>
                </div>
                <div class="cart-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/images/logo-".get_orders_source_image($r->orders_source_id));?>);"></div>

                <div class="cart-list-content">
                    <div class="row">
                        <div class="col-sm-4">
                            <p class="no-margin no-padding mb-5"><strong><?php echo $r->orders_shipping_name ?></strong></p>
                            <?php $orders_status  = get_orders_status($r->orders_status); ?>
                            <div class="label label-<?php echo $orders_status['color']; ?>"><?php echo $orders_status['name']; ?></div>
                            <h6 class="no-margin no-padding">Daftar Produk</h6>
                            <div class="relative cart-list cart-list-small no-margin mb-5">
                                <?php foreach ($r->orders_detail as $key2 => $val2) { ?>
                                <div class="cart-list-item" style="<?php echo ($r->mp_claim_status==TRUE?"background-color:#F6BB42;":"") ?>">
                                    <div class="cart-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".$val2->product_images);?>); left:0px;"></div>
                                    <div class="cart-list-content" style="padding-left:50px;">
                                        <h6 class="no-margin no-padding"><strong><?php echo $val2->product_code ?></strong></h6>
                                        <p class="no-margin no-padding"><strong><?php echo $val2->product_name ?></strong></p>
                                        <h6 class="no-margin no-padding mb-5"><?php echo convertRp($val2->orders_detail_price) ?> x <?php echo $val2->orders_detail_qty ?> = <strong><?php echo convertRp(($val2->orders_detail_price * $val2->orders_detail_qty)) ?></strong></h6>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="relative">
                                <h6 class="no-margin no-padding">Jumlah Produk: <?php echo count($r->orders_detail) ?></h6>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <p class="no-margin no-padding mb-5"><?php echo $r->orders_source_name;?> <span class="label label-default"><?php echo convDateTable($r->orders_date) ?></span></p>
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
                        </div>
                        <div class="col-sm-4">
                            <p class="no-margin no-padding mb-5">CAIR PEMBAYARAN</p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Item: <?php echo $r->mp_qty_product; ?> pcs</h6>
                                    <h6 class="no-margin no-padding mb-5">Harga: <?php echo convertRp($r->mp_price_product); ?></h6>
                                    <h6 class="no-margin no-padding mb-5">Diskon: <?php echo convertRp($r->mp_price_discount); ?></h6>
                                    <h6 class="no-margin no-padding mb-5">Jadi: <?php echo convertRp($r->mp_price_product - $r->mp_price_discount); ?></h6>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Ongkir: <?php echo convertRp($r->mp_price_shipping); ?></h6>
                                    <h6 class="no-margin no-padding mb-5">Subsidi: <?php echo convertRp($r->mp_price_subsidi); ?></h6>
                                    <h6 class="no-margin no-padding mb-5">Asuransi: <?php echo convertRp($r->mp_price_insurance); ?></h6>
                                </div>
                            </div>
                            <hr class="no-margin mt-5 mb-5">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Pengembalian Dana</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_price_return[<?php echo $r->orders_id; ?>]" value="<?php echo $r->mp_price_return; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Pengembalian Dana" maxlength="23">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Perlu Claim</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <?php
                                        $mp_claim_price = 0;
                                        if($r->orders_source_price > $r->mp_price_grand_total){
                                            $mp_claim_price = $r->mp_claim_price;
                                        }
                                        ?>
                                        <input type="text" name="orders_claim_price[<?php echo $r->orders_id; ?>]" value="<?php echo $mp_claim_price; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Jumlah Claim" maxlength="23">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Auto Debet Ongkir</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_price_debet_ship[<?php echo $r->orders_id; ?>]" value="<?php echo $r->mp_price_debet_ship; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Ongkos Kirim yang diteruskan ke Kurir" maxlength="23">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Cashback</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_voucher_price[<?php echo $r->orders_id; ?>]" value="<?php echo $r->mp_voucher_price; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Voucher / Cashback" maxlength="23">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Cashback Seller</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_cashback_seller[<?php echo $r->orders_id; ?>]" value="<?php echo $r->mp_cashback_seller; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Cashback dari Kurir" maxlength="23">
                                    </div>
                                </div>
                                <div class="col-sm-6">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Total Cair</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_payment_price[<?php echo $r->orders_id; ?>]" value="<?php echo $r->mp_price_grand_total; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Pembayaran dari marketplace" maxlength="23">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Fee Admin</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_price_fee[<?php echo $r->orders_id; ?>]" value="<?php echo $r->mp_price_fee; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Biaya Admin" maxlength="23">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="orders_payment_date[<?php echo $r->orders_id; ?>]" value="<?php echo $r->mp_date_payment ?>">
                            <button type="button" class="btn btn-success btn-xs btn-save"><i class="fa fa-check"></i> Confirm Terima Pembayaran</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    }
                }
            } else {
                echo 'Data tidak ditemukan...';
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
        $('.btn-save-multiple').html('<i class="fa fa-check"></i> Confirm Terima Pembayaran ( '+checked_files.length+'/<?php echo count($data_orders)?> )');
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
                    var thisId  = "";
                    checked_files.each(function() {
                        thisId  = $(this).val();
                        // thisId += (thisId=="" ? thisVal : "-" + thisVal);
                        var cartListItem = $('.cart-list-item').attr("data-id", thisId);
                        var orders_price_grand_total = cartListItem.find('input[name="orders_price_grand_total['+thisId+']"]').val();
                        var orders_payment_price     = cartListItem.find('input[name="orders_payment_price['+thisId+']"]').val();
                        var orders_voucher_price     = cartListItem.find('input[name="orders_voucher_price['+thisId+']"]').val();
                        var orders_price_debet_ship  = cartListItem.find('input[name="orders_price_debet_ship['+thisId+']"]').val();
                        var orders_claim_price       = cartListItem.find('input[name="orders_claim_price['+thisId+']"]').val();
                        var orders_price_return      = cartListItem.find('input[name="orders_price_return['+thisId+']"]').val();
                        var orders_payment_date      = cartListItem.find('input[name="orders_payment_date['+thisId+']"]').val();

                        $.ajax({
                            type: 'POST',
                            url: "<?php echo base_url().'admin/transaction_process/save_verifikasi_payment' ?>",
                            data: {'thisId':thisId,'orders_price_grand_total':orders_price_grand_total,'orders_payment_price':orders_payment_price,'orders_voucher_price':orders_voucher_price,'orders_price_debet_ship':orders_price_debet_ship,'orders_claim_price':orders_claim_price,'orders_price_return':orders_price_return,'orders_payment_date':orders_payment_date,'thisAction':'save'},
                            async: false,
                            cache: false,
                            dataType: 'json',
                            beforeSend: function(){

                            },
                            success: function(data){
                                if(data.err == false){
                                    cartListItem.remove();
                                    calc_check_files();

                                    // swal({
                                    //     title: "Success!",
                                    //     text: data.msg,
                                    //     type: "success",
                                    //     showConfirmButton: false,
                                    //     timer: 800
                                    // }).then(
                                    // function () {},
                                    // function (dismiss) {
                                    //     // console.log('close');
                                    //     // if (dismiss === 'timer') {
                                    //     //     console.log('I was closed by the timer')
                                    //     // }
                                    // });

                                } else {
                                    // swal({
                                    //     title: "Error!",
                                    //     html: data.msg,
                                    //     type: "error"
                                    // });
                                }
                            },
                            error: function(jqXHR){
                                // var response = jqXHR.responseText;
                                // swal({
                                //     title: "Error!",
                                //     html: response,
                                //     type: "error"
                                // });
                            }
                        });

                    });

                    swal({
                        title: "Success!",
                        text: "Berhasil simpan pembayaran...",
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
                            window.location.href = "<?php echo $own_links.'/verifikasi_payment' ?>";
                        },1000);
                    });
                    notifyMessage('Berhasil simpan pembayaran...', 'success');
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
            var orders_cashback_seller   = cartListItem.find('input[name="orders_cashback_seller['+thisId+']"]').val();
            var orders_price_debet_ship  = cartListItem.find('input[name="orders_price_debet_ship['+thisId+']"]').val();
            var orders_claim_price       = cartListItem.find('input[name="orders_claim_price['+thisId+']"]').val();
            var orders_price_return      = cartListItem.find('input[name="orders_price_return['+thisId+']"]').val();
            var orders_payment_date      = cartListItem.find('input[name="orders_payment_date['+thisId+']"]').val();

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
                    url: "<?php echo base_url().'admin/transaction_process/save_verifikasi_payment' ?>",
                    data: {'thisId':thisId,'orders_price_grand_total':orders_price_grand_total,'orders_payment_price':orders_payment_price,'orders_voucher_price':orders_voucher_price,'orders_cashback_seller':orders_cashback_seller,'orders_price_debet_ship':orders_price_debet_ship,'orders_claim_price':orders_claim_price,'orders_price_return':orders_price_return,'orders_payment_date':orders_payment_date,'thisAction':'save'},
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

    });
</script>
