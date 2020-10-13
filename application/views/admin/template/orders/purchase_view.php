<div class="the-box no-border">

    <?php js_mask() ?>

    <div class="panel panel-dark panel-block-color">
        <div class="panel-heading">
            <h3 class="panel-title text-uppercase">Informasi Purchase</h3>
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tanggal</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->purchase_date)?convDateTimeTable($val->purchase_date):'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">No Invoice</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->purchase_invoice)?$val->purchase_invoice:'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Supplier</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo get_supplier($val->supplier_id)->supplier_name ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-dark panel-block-color">
        <div class="panel-heading">
            <h3 class="panel-title text-uppercase">INFORMASI PRODUK</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-th-block">
                    <colgroup>
                        <col>
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th class="nobr text-center">Qty</th>
                            <th class="nobr text-center">Harga</th>
                            <th class="nobr text-center">Harga /pcs</th>
                            <th class="nobr text-center">Total</th>
                            <th class="nobr text-center">Reseller</th>
                            <th class="nobr text-center">Harga Reseller</th>
                            <th class="nobr text-center">Total Harga</th>
                            <th class="nobr text-center">Tgl Pembayaran</th>
                            <th class="nobr text-center">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $this_total = 0;
                        $total_reseller_remaining = 0;
                        $get_purchase_detail = get_purchase_detail($val->purchase_id);
                        foreach ($get_purchase_detail as $key => $value){
                            $this_total = ($value->purchase_detail_qty * $value->purchase_detail_price);
                            ?>
                        <tr>
                            <td class=""><?php echo $value->purchase_detail_name ?></td>
                            <td class="nobr text-center">
                                <?php
                                $get_product_satuan = get_product_satuan($value->purchase_detail_satuan);
                                $satuan_name = $get_product_satuan->product_satuan_name;
                                $satuan_qty = $get_product_satuan->product_satuan_qty;
                                echo $value->purchase_detail_qty.' '.$satuan_name;
                                ?>
                            </td>
                            <td class="nobr text-left"><?php echo convertRp($value->purchase_detail_price) ?></td>
                            <td class="nobr text-left"><?php echo convertRp(($value->purchase_detail_price / $satuan_qty)) ?></td>
                            <td class="nobr text-left"><?php echo convertRp($this_total) ?></td>
                            <td class="nobr text-left">
                                <?php if($value->store_id != 1){
                                    echo get_store_name($value->store_id);
                                } ?>
                            </td>
                            <td class="nobr text-left">
                                <?php if($value->store_id != 1){
                                    echo convertRp($value->purchase_reseller_price);
                                } ?>
                            </td>
                            <td class="nobr text-left">
                                <?php if($value->store_id != 1){
                                    echo convertRp(($value->purchase_detail_qty * $value->purchase_reseller_price));
                                } ?>
                            </td>
                            <td class="nobr text-left">
                                <?php if($value->store_id != 1){ ?>
                                    <?php if($value->purchase_reseller_status == 0) { ?>
                                    <div class="btn btn-sm btn-success btn-reseller-payment" data-id="<?php echo $value->purchase_detail_id ?>" data-qty="<?php echo $value->purchase_reseller_remaining ?>">Bayar</div>
                                    <?php } ?>
                                <?php
                                    $purchase_reseller_payment = json_decode($value->purchase_reseller_payment);
                                    foreach ($purchase_reseller_payment as $key => $value2){ ?>
                                        <h6 class="no-margin"><?php echo getDateMonth($value2->date) ?> - <?php echo convertRp($value2->price) ?></h6>
                                <?php }
                                } ?>
                            </td>
                            <td class="nobr text-left">
                                <?php
                                if($value->store_id != 1){
                                    if($value->purchase_reseller_status == 0) {
                                        echo convertRp($value->purchase_reseller_remaining);
                                    } else {
                                        echo "Lunas";
                                    }
                                    $total_reseller_remaining += $value->purchase_reseller_remaining;
                                } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-dark">
                            <td colspan="4" class="nobr text-right">Grand Total Bayar :</td>
                            <td colspan="2"><?php echo convertRp($val->purchase_price_grand_total) ?></td>
                            <td colspan="3" class="nobr text-right">Total Nota Reseller :</td>
                            <td colspan="2" class="nobr text-right"><?php echo convertRp($total_reseller_remaining) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Catatan</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo $val->purchase_noted ?></div>
                        <div class="btn btn-info btn-popup" data-id="<?php echo $val->purchase_id ?>"><i class="fa fa-plus"></i> Tambah Catatan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-group" id="accordion-shipping">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">Informasi Pembayaran</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Total Yang Harus Dibayar</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo convertRp($val->purchase_price_grand_total) ?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Pembayaran</label>
                        <div class="col-sm-9">
                            <div class="form-control"><span class="label <?php echo ($val->purchase_status=='1'?'label-success':'label-danger') ?>"><?php echo ($val->purchase_status=='1'?'Lunas':'Belum') ?></span></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Metode Pembayaran</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo get_purchase_payment_source($val->purchase_payment_source)['name'] ?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Rekening</label>
                        <div class="col-sm-5">
                            <div class="form-control">
                                <?php
                                    $supplier_bank = get_supplier($val->supplier_id)->supplier_bank;
                                    if($supplier_bank != ""){
                                        foreach (json_decode($supplier_bank) as $k1 => $v1) {
                                            echo "<strong>".$v1->name."<strong> - ".$v1->bank." ".$v1->norek."</br>";
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Jumlah Bayar</label>
                        <div class="col-sm-5">
                            <div class="form-control">
                                <div class="table-responsive">
                                    <table class="table table-th-block">
                                        <colgroup>
                                            <col>
                                            <col>
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $purchase_payment_detail = json_decode($val->purchase_payment_detail);
                                            foreach ($purchase_payment_detail as $key => $value){?>
                                            <tr>
                                                <td class=""><?php echo convDateTimeTable($value->date) ?></td>
                                                <td class=""><?php echo convertRp($value->price) ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if($val->purchase_status == '0'){ ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Sisa Pembayaran</label>
                        <div class="col-sm-5">
                            <div class="form-control"><?php echo convertRp($val->purchase_payment_remaining) ?></div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <div class="btn btn-success btn-payment-remaining"><i class="fa fa-plus"></i> Bayar</div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(document).on('click', '.btn-payment-remaining', function(e){
                                e.preventDefault();
                                swal({
                                    title: 'Sisa Pembayaran',
                                    html:
                                    '<div class="swal2-input-group input-group"><span class="input-group-addon">Rp</span><input type="text" id="swal-remaining" class="swal2-input moneyRp_masking" value="<?php echo $val->purchase_payment_remaining ?>" disabled="disabled"></div>'+
                                    '<div class="swal2-input-group input-group"><span class="input-group-addon">Rp</span><input type="text" id="swal-price" class="swal2-input moneyRp_masking" placeholder="Jumlah Bayar" required="required" maxlength="23" autofocus></div>',
                                    showCancelButton: true,
                                    confirmButtonText: 'Submit',
                                    showLoaderOnConfirm: true,
                                    onOpen: function () {
                                        setTimeout(function(){
                                            $(".moneyRp_masking").mask("#.##0", {reverse: true, maxlength: false});
                                        },200);
                                    },
                                    preConfirm: function () {
                                        return new Promise(function (resolve,reject) {
                                            var remaining = $('#swal-remaining').val();
                                            var price     = $('#swal-price').val();
                                            if(price == ''){
                                                $('#swal-price').addClass('swal2-inputerror');
                                                reject('Jumlah bayar harus diisi!');
                                            } else {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '<?php echo $own_links ?>/save_payment_remaining',
                                                    data: 'thisId=<?php echo $val->purchase_id ?>&price='+price+'&thisAction=save',
                                                    async: false,
                                                    cache: false,
                                                    dataType: 'json',
                                                    beforeSend: function(){

                                                    },
                                                    success: function(data){
                                                        if(data.err == false){
                                                            swal({
                                                                title: "Success!",
                                                                text: "Pembayaran <strong>" + convertRp(price) + "</strong> berhasil!",
                                                                type: "success"
                                                            }).then(function () {
                                                                setTimeout(function(){
                                                                    window.location.reload(true);
                                                                },300);
                                                            });
                                                        } else {
                                                            swal({
                                                                title: "Error!",
                                                                text: data.msg,
                                                                type: "error"
                                                            });
                                                        }
                                                    },
                                                    error: function(jqXHR){
                                                        var response = jqXHR.responseText;
                                                        alert(response);
                                                    }
                                                });
                                            }
                                        });
                                    }
                                }).then(function (result) {
                                    swal(JSON.stringify(result));
                                }).catch(swal.noop);
                            });

                        });
                    </script>
                    <?php } ?>

                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(document).on('click', '.btn-reseller-payment', function(e){
                                e.preventDefault();
                                purchase_detail_id = $(this).attr('data-id');
                                reseller_remaining = $(this).attr('data-qty');
                                swal({
                                    title: 'Sisa Pembayaran',
                                    html:
                                    '<div class="swal2-input-group input-group"><span class="input-group-addon">Rp</span><input type="text" id="swal-remaining" class="swal2-input moneyRp_masking" value="'+reseller_remaining+'" disabled="disabled"></div>'+
                                    '<div class="swal2-input-group input-group"><span class="input-group-addon">Rp</span><input type="text" id="swal-price" class="swal2-input moneyRp_masking" placeholder="Jumlah Bayar" required="required" maxlength="23" autofocus></div>',
                                    showCancelButton: true,
                                    confirmButtonText: 'Submit',
                                    showLoaderOnConfirm: true,
                                    onOpen: function () {
                                        setTimeout(function(){
                                            $(".moneyRp_masking").mask("#.##0", {reverse: true, maxlength: false});
                                        },200);
                                    },
                                    preConfirm: function () {
                                        return new Promise(function (resolve,reject) {
                                            var remaining = $('#swal-remaining').val();
                                            var price     = $('#swal-price').val();
                                            if(price == ''){
                                                $('#swal-price').addClass('swal2-inputerror');
                                                reject('Jumlah bayar harus diisi!');
                                            } else {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '<?php echo $own_links ?>/save_reseller_payment_remaining',
                                                    data: 'thisId='+purchase_detail_id+'&price='+price+'&thisAction=save',
                                                    async: false,
                                                    cache: false,
                                                    dataType: 'json',
                                                    beforeSend: function(){

                                                    },
                                                    success: function(data){
                                                        if(data.err == false){
                                                            swal({
                                                                title: "Success!",
                                                                text: "Pembayaran <strong>" + convertRp(price) + "</strong> berhasil!",
                                                                type: "success"
                                                            }).then(function () {
                                                                setTimeout(function(){
                                                                    window.location.reload(true);
                                                                },300);
                                                            });
                                                        } else {
                                                            swal({
                                                                title: "Error!",
                                                                text: data.msg,
                                                                type: "error"
                                                            });
                                                        }
                                                    },
                                                    error: function(jqXHR){
                                                        var response = jqXHR.responseText;
                                                        alert(response);
                                                    }
                                                });
                                            }
                                        });
                                    }
                                }).then(function (result) {
                                    swal(JSON.stringify(result));
                                }).catch(swal.noop);
                            });

                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group form-action clearfix">
        <div class="col-sm-9 col-sm-offset-3">
            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
        </div>
    </div>
</div>

<div class="popup popup-purchase">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $(document).on('click', '.btn-popup', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            if($thisVal != ''){
                $.ajax({
                    type: 'POST',
                    url: OWN_LINKS+'/views_purchase_noted',
                    data: {'thisVal':$thisVal,'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-purchase').addClass('active');
                        $('.popup-purchase .popup-content').html(data.content);
                        // console.log(data.result);
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
            }
        });

        $(document).on('submit', 'form.form_save_label', save_label );
        function save_label(e){
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
                                    $('.popup-print-address .popup-close').trigger('click');
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

            }, 500);

            return false;
        }
    });
</script>
