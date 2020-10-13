
<?php js_mask() ?>
<?php js_chosen() ?>

<div class="the-box no-border">

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
                                <label>Status</label>
                                <div class="btn-group">
                                    <?php $orders_claim_status  = $this->jCfg['search']['orders_claim_status']; ?>
                                    <select name="orders_claim_status" class="form-control" onchange="this.form.submit()">
                                        <option value="" selected >--- Pilih ---</option>
                                        <?php
                                        $arr_orders_claim = get_orders_claim();
                                        foreach ($arr_orders_claim as $k => $v) { ?>
                                        <option value="<?php echo $v['id'] ?>" <?php echo ($orders_claim_status==$v['id']?'selected':'') ?> ><?php echo $v['name'] ?></option>
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
                                        <!-- <option value="" <?php echo ($filter==''?'selected':'') ?> >ALL</option> -->
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-toolbar toolbar-search">
            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" autocomplete="off" enctype="multipart/form-data" accept-charset="UTF-8">
                <label>Search:</label>
                <div class="btn-group">
                    <input type="search" data-reset="true" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
                    <input type="hidden" name="searchAction" value="search">
                </div>
            </form>
        </div>
    </div>

    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="relative no-border mt-20">
        <div class="cart-list mb-0">
            <?php
            // debugCode($data);
            if(count($data) > 0){
                foreach($data as $r){
                    $payment = get_detail_orders_payment($r->orders_id);
            ?>
            <div class="cart-list-item magnific-popup-wrap" data-id="<?php echo $r->orders_id ?>">
                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/large/".$r->orders_detail[0]->product_images);?>">
                    <div class="cart-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".$r->orders_detail[0]->product_images);?>);"></div>
                </a>

                <div class="cart-list-content">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="label label-default mb-5"><?php echo convDateTimeTable($r->orders_date) ?></div>
                            <span style="color: #f00;"><?php echo xTimeAgo($r->orders_date, timestamp(), "d"); ?></span>
                            <h6 class="no-margin no-padding"><?php echo $r->orders_invoice ?></h6>
                            <p class="no-margin no-padding"><strong><?php echo $r->orders_shipping_name ?></strong></p>
                            <h6 class="no-margin no-padding"><?php echo $r->orders_shipping_phone ?></h6>
                            <h6 class="no-margin no-padding mb-5"><?php echo $r->orders_shipping_address ?></h6>
                            <?php $orders_status  = get_orders_status($r->orders_status); ?>
                        </div>
                        <div class="col-sm-4">
                            <p class="no-margin no-padding mb-5"><?php echo $r->orders_source_name;?> <span class="label label-<?php echo $orders_status['color']; ?>"><?php echo $orders_status['name']; ?></span></p>
                            <h6 class="no-margin no-padding mb-5"><?php echo $r->orders_source_invoice;?></h6>
                            <h6 class="no-margin no-padding mb-5">Total Item: <?php echo $r->orders_total_qty ?> pcs</h6>
                            <h6 class="no-margin no-padding mb-5">Kurir: <?php echo $r->orders_courier_name." ".$r->orders_courier_service ?></h6>

                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Total Harga Beli</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="" value="<?php echo $r->orders_price_buy_total; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Total harga beli" maxlength="23" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Total Harga Jual</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="" value="<?php echo $r->orders_price_product; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Total harga jual" maxlength="23" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Estimasi Ongkir</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="" value="<?php echo $r->orders_price_shipping; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Estimasi Ongkir" maxlength="23" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Total Bayar</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="" value="<?php echo $r->orders_price_grand_total; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Total Bayar" maxlength="23" disabled>
                                    </div>
                                </div>
                            </div>
                            <?php echo ($r->orders_price_insurance!=0?'<h6 class="no-margin no-padding">Asuransi: '.convertRp($r->orders_price_insurance).'</h6>':''); ?>
                            <?php if($r->orders_claim_status > 0){
                                $orders_claim = get_orders_claim($r->orders_claim_status);
                            ?>
                            <span class="label label-<?php echo $orders_claim['color']?>"><?php echo $orders_claim['name']?></span>
                            <h6 class="no-margin no-padding mb-5">Jumlah Claim: <?php echo convertRp($r->orders_claim_price); ?></h6>
                            <h6 class="no-margin no-padding mb-5">Tanggal Claim: <?php echo convDate($r->orders_claim_date); ?></h6>
                            <h6 class="no-margin no-padding">Jumlah hari dari awal order: <span style="color: #f00;"><?php echo xTimeAgo($r->orders_date, timestamp(), "d"); ?></span></h6>
                            <?php } ?>
                        </div>
                        <div class="col-sm-4">
                            <p class="no-margin no-padding mb-5">CAIR PEMBAYARAN</p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Pengembalian Dana</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="" value="<?php echo $r->orders_price_return; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Pengembalian Dana" maxlength="23" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Perlu Claim</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="orders_claim_price" value="<?php echo $r->orders_claim_price; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Jumlah Claim" maxlength="23" <?php echo ($r->orders_claim_status == 3?'disabled':''); ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Auto Debet Ongkir</h6>
                                    <div class="input-group mb-5">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="" value="<?php echo $r->orders_price_debet_ship; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Ongkos Kirim yang diteruskan ke Kurir" maxlength="23" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Cashback</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="" value="<?php echo $r->orders_voucher_price; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Voucher / Cashback" maxlength="23" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="no-margin no-padding mb-5">Total Cair</h6>
                                    <div class="input-group mb-10">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="" value="<?php echo $payment->orders_payment_price; ?>" class="form-control input-sm moneyRp_masking" style="width: 70px;" placeholder="Pembayaran dari marketplace" maxlength="23" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($r->orders_claim_status < 3){ ?>
                    <div class="btn-group pull-right" style="position: absolute; top: 5px; right: 5px;">
                        <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript:void(0);" class="btn-claim btn-claim-update-price" data-id="<?php echo $r->orders_id;?>" data-status="2" >Konfirmasi Claim Price</a></li>
                            <li><a href="javascript:void(0);" class="btn-claim" data-id="<?php echo $r->orders_id;?>" data-status="3" data-price="">Claim Selesai</a></li>
                        </ul>
                    </div>
                    <?php } ?>
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
    $(document).ready(function() {

        $(document).on('click', '.btn-claim', function(e){
            e.preventDefault();
            var $this       = $(this);
            var $thisId     = $this.attr('data-id');
            var $thisStatus = $this.attr('data-status');
            var $thisPrice  = $this.attr('data-price');
            var $cartListItem = $this.parents('.cart-list-item');
                if($this.hasClass('btn-claim-update-price')){
                    $thisPrice = $cartListItem.find('input[name="orders_claim_price"]').val();
                }
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

    });
</script>