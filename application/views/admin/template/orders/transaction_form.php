<div class="the-box no-border">

    <?php js_picker() ?>
    <?php js_chosen() ?>
    <?php js_chosen_city() ?>
    <?php js_mask() ?>

    <form class="form-final-checkout" action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI ORDER</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <legend>Info Utama</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Order</label>
                        <div class="col-sm-5">
                            <select name="orders_status" class="form-control" required>
                                <?php
                                $i = 0;
                                $arr_orders_status = array(8,1,2,3,4,5,9,10);
                                foreach ($arr_orders_status as $key) {
                                    $v = get_orders_status($key);
                                    $selected = (($i=='0')||($v['id']==8)||($v['id']==$val->orders_status)?'selected':'');
                                    echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['name'].'</option>';
                                    $i += 1;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Orderan Dari</label>
                        <div class="col-sm-5">
                            <select name="orders_source_id" class="form-control">
                                <?php
                                $i = 0;
                                $arr_orders_source = get_orders_source();
                                foreach ($arr_orders_source as $k => $v) {
                                    $selected = (($i=='0')||($v->orders_source_id==3)||($v->orders_source_id==$val->orders_source_id)?'selected':'');
                                    echo '<option value="'.$v->orders_source_id.'" '.$selected.'>'.$v->orders_source_name.'</option>';
                                    $i += 1;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Invoice</label>
                        <div class="col-sm-5 has-feedback">
                            <input type="text" name="orders_source_invoice" value="<?php echo $val->orders_source_invoice ?>" class="form-control check" data-check-id="" data-check-parent="" data-check-rel="orders_source_invoice" data-check-msg="" data-check-err="No invoice sudah diinput.">
                            <span class="fa form-control-feedback"></span>
                            <p class="help-block">Khusus Bukalapak / Tokopedia</p>
                        </div>
                    </div>

                    <legend>Info Customer</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama<span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="orders_shipping_name" name="orders_shipping_name" data-autocomplete-name value="<?php echo $orders_shipping->orders_shipping_name ?>" class="form-control" required />
                            <div id="orders_shipping_name_feedback"></div>
                        </div>
                        <script type="text/javascript">
                            var MemberArray = [];
                            var cg = new customGeneral();
                            cg.autoCompleteMember(MemberArray, "#orders_shipping_name");
                        </script>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" name="orders_shipping_email" data-autocomplete-email value="<?php echo $orders_shipping->orders_shipping_email ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Hp</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_phone" data-autocomplete-phone value="<?php echo $orders_shipping->orders_shipping_phone ?>" class="form-control" maxlength="25" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea name="orders_shipping_address" data-autocomplete-address class="form-control no-resize" rows="3" maxlength="300"><?php echo $orders_shipping->orders_shipping_address ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kota</label>
                        <div class="col-sm-5">
                            <select name="city" data-autocomplete-city data-placeholder="--- Select ---" class="form-control chosen-city chosen-select">
                                <option value=""></option>
                                <?php $city = isset($orders_shipping->orders_shipping_city)?$orders_shipping->orders_shipping_city:'';?>
                                <?php echo option_city_chosen($city); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Propinsi</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="province" data-autocomplete-province value="<?php echo $orders_shipping->orders_shipping_province ?>" />
                            <input type="text" name="province_name" data-autocomplete-province-name value="<?php echo getProvSet($orders_shipping->orders_shipping_province) ?>" class="form-control" disabled />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kode Pos</label>
                        <div class="col-sm-3">
                            <input type="text" name="orders_shipping_postal_code" data-autocomplete-postal-code value="<?php echo $orders_shipping->orders_shipping_postal_code ?>" class="form-control" maxlength="10" />
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
                <div class="relative mb-20">
                    <label>Cari Produk</label>
                    <div class="btn-toolbar">
                        <input type="search" id="search_product_array" name="search_product" value="" class="form-control" placeholder="Cari Produk..." />
                        <div id="search_product_array_feedback"></div>
                        <script type="text/javascript">
                            $(document).ready(function() {

                                var productParams   = {
                                    'product_show'          : '1',
                                    'product_approved'      : '1',
                                    'product_status_id'     : '!= 3',
                                    'thisAction'            : 'getdata'
                                };
                                var cg = new customGeneral();
                                var ProductArray = cg.getProduct(productParams);
                                cg.autoCompleteProduct(ProductArray, "#search_product_array");

                                /** ENABLED VARIASI **/
                                $(document).on('change', '.product-variasi-item .form-cart-checkbox', function(e){
                                    e.preventDefault();
                                    var variasi = $(this).parents('.product-variasi-item');
                                    if($(this).is(':checked')){
                                        variasi.addClass('checked');
                                        variasi.find('.select .form-control').attr('required','required');
                                        variasi.find('.select .form-control').removeAttr('disabled');
                                        variasi.find('.select .form-control').val('1');
                                        variasi.find('.select .form-control').focus();

                                    } else {
                                        variasi.removeClass('checked');
                                        variasi.find('.select .form-control').removeAttr('required');
                                        variasi.find('.select .form-control').attr('disabled','disabled');
                                        variasi.find('.select .form-control').val('');
                                    }
                                });

                                $(document).on('click', '.btn-popup-product', function(e){
                                    e.preventDefault();
                                    var $this    = $(this);
                                    var $thisVal = $this.attr('data-id');
                                    $('html, body').css('overflow','hidden');
                                    if($thisVal != ''){
                                        $.ajax({
                                            type: 'POST',
                                            url: OWN_LINKS+'/cart_get_product',
                                            data: {'thisVal':$thisVal,'thisAction':'get_product'},
                                            async: false,
                                            cache: false,
                                            dataType: 'json',
                                            success: function(data){
                                                $('.popup-product').addClass('active');
                                                $('.popup-product .popup-content').focus();
                                                $('.popup-product .popup-content').html(data.content);
                                                setTimeout(function(){
                                                    $('.popup-product .popup-content').focus();
                                                }, 300);
                                            },
                                            error: function(jqXHR){
                                                var response = jqXHR.responseText;
                                                alert('error ajax');
                                            }
                                        });
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>

                <label>Keranjang Belanja <a href="<?php echo $own_links.'/cart_empty' ?>" onclick="return confirm('Empty Cart ?');" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i> Empty Cart</a></label>
                <?php // debugCode($this->jCfg['cart']) ?>
                <div class="cart-list">

                </div>
                <div class="form-horizontal">
                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">Sub Total Harga</label>
                        <div class="col-sm-5">
                            <div class="form-control cart-subgrandtotal-price-span"></div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">PPN</label>
                        <div class="col-sm-5">
                            <div class="form-control cart-grandtotal-ppn-price-span"><?php echo convertRp('5000') ?></div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">Total Biaya Kurir</label>
                        <div class="col-sm-5">
                            <div class="input-group danger-color">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders-shipping-price" value="" class="form-control moneyRp_masking" maxlength="23" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">Berat</label>
                        <div class="col-sm-5">
                            <div class="form-control cart-grandtotal-weight-span"></div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">Voucher</label>
                        <div class="col-sm-3">
                            <div class="checkbox">
                                <label><input type="checkbox" name="chk_orders_voucher_price" onclick="enabledOrderVoucher();" <?php echo ($val->orders_voucher_price!=0?'checked':'') ?> value="1"> Yes</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 voucherPrice" style=" <?php echo ($val->orders_voucher_price!=0?'display: block;':'display: none;') ?>">
                        <label class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders-voucher-price" value="" class="form-control moneyRp_masking" maxlength="23">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="orders-voucher-code" value="<?php echo $val->orders_voucher_code ?>" placeholder="Kode Voucher" class="form-control">
                        </div>
                    </div>
                    <script type="text/javascript">
                        function enabledOrderVoucher(){
                            if($('input[name="chk_orders_voucher_price"]').is(':checked')){
                                $('.voucherPrice').show();
                            } else {
                                $('.voucherPrice').hide();
                            }
                        }
                    </script>

                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">Grand Total Bayar</label>
                        <div class="col-sm-5">
                            <strong><div class="form-control cart-grandtotal-price-span"></div></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-group" id="accordion-shipping">
            <div class="panel panel-dark panel-block-color">
                <div class="panel-heading">
                    <h3 class="panel-title text-uppercase">
                        <a class="block-collapse collapsed" data-parent="#accordion-shipping" data-toggle="collapse" href="#accordion-shipping-child-1">
                            Informasi Pengiriman & Pembayaran
                            <span class="right-content">
                                <span class="right-icon">
                                    <i class="glyphicon glyphicon-plus icon-collapse"></i>
                                </span>
                            </span>
                        </a>
                    </h3>
                </div>
                <div id="accordion-shipping-child-1" class="collapse">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <legend>Info Pengiriman</legend>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Metode Pengiriman</label>
                                    <div class="col-sm-7">
                                        <select name="orders_shipping_method" class="form-control">
                                            <?php
                                            $get_shipping_method = get_shipping_method();
                                            $i = 0;
                                            foreach ($get_shipping_method as $key => $v){
                                                $selected = (($i=='0')||($v['id']==$orders_shipping->orders_shipping_method)?'selected':'');
                                                echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['name'].'</option>';
                                                $i += 1;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Kurir</label>
                                    <div class="col-sm-7">
                                        <select name="orders_courier_id" class="form-control">
                                            <option value="" selected>--- Pilih ----</option>
                                            <?php
                                            $i = 0;
                                            $get_orders_courier = get_orders_courier();
                                            foreach ($get_orders_courier as $k => $v) {
                                                $get_orders_courier2 = get_orders_courier($v->orders_courier_id, true);
                                                foreach ($get_orders_courier2 as $k2 => $v2) {
                                                    echo '<option value="'.$v2->orders_courier_id.'" '.(($i=='0')||($val->orders_courier_id==$v2->orders_courier_id)?'selected':'').'>'.$v->orders_courier_name.' - '.$v2->orders_courier_service.'</option>';
                                                    $i += 1;
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">No Resi</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="orders_shipping_resi" value="<?php echo $orders_shipping->orders_shipping_resi ?>" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Catatan Orders</label>
                                    <div class="col-sm-7">
                                        <textarea name="orders_noted" class="form-control no-resize" rows="3" maxlength="300"><?php echo $val->orders_noted ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <legend>Info Pembayaran</legend>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Metode Pembayaran</label>
                                    <div class="col-sm-7">
                                        <select name="orders_payment_method" class="form-control">
                                        <?php
                                        $i = 0;
                                        $payment_method = '';
                                        $arr_payment_method = get_payment_method();
                                        foreach ($arr_payment_method as $k => $v) {
                                            $payment_method .= '<option value="" disabled>'.$v->payment_method_name.'</option>';

                                            $arr_payment_method2 = get_payment_method($v->payment_method_id, true);
                                            foreach ($arr_payment_method2 as $k2 => $v2) {
                                                $selected = (($i=='0')||($v2->payment_method_id==$orders_payment->orders_payment_method)?'selected':'');
                                                $name_account = '';
                                                if($v2->payment_method_name_account != ''){
                                                    $name_account = ' ('.$v2->payment_method_name_account.' - '.$v2->payment_method_no_account.')';
                                                }
                                                $payment_method .= '<option value="'.$v2->payment_method_id.'" '.$selected.'>&nbsp; &nbsp; &nbsp;'.$v2->payment_method_name.$name_account.'</option>';
                                                $i += 1;
                                            }
                                        }
                                        echo $payment_method;
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Nama Rekening</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="orders_payment_name_account" value="<?php echo $orders_payment->orders_payment_name_account ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">No Rekening</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="orders_payment_no_account" value="<?php echo $orders_payment->orders_payment_no_account ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Jumlah Bayar</label>
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" name="cart-grandtotal-price" value="<?php echo $orders_payment->orders_payment_grand_total ?>" class="form-control moneyRp_masking" maxlength="23">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="hidden" name="orders_id" value="<?php echo isset($val->orders_id)?$val->orders_id:'';?>" />
                <input type="submit" name="simpan" class="btn btn-danger" value="Checkout & Save" />
                <a href="<?php echo $own_links.($val->orders_id!=''?'/view/'.$val->orders_id.'-'.changeEnUrl($orders_shipping->orders_shipping_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
            </div>
        </div>

    </form>
</div>

<div class="popup popup-product">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
        <script type="text/javascript">
            $(document).ready(function() {

                // $(document).on('click', '.btn-calc-number', function(e){
                //     e.preventDefault();
                //     var $this    = $(this);
                //     var new_qty  = '';
                //     var result   = $this.parents('.input-group').find('.result-calc-number');
                //     var thisVal  = $(result).val();
                //     if(thisVal == ''){ thisVal = 0; }
                //     if(!$(result).is(':disabled') && $.isNumeric(parseInt(thisVal))){
                //         new_qty = parseInt($(result).val()) + 1;
                //         if($this.hasClass('minus')){
                //             new_qty = parseInt($(result).val()) - 1;
                //             var min_qty = result.attr('min');
                //             if(min_qty==''||min_qty==undefined||min_qty=='undefined'){ min_qty = 0; }
                //             if(new_qty<=min_qty){ new_qty = min_qty; }
                //         }
                //         $(result).val(new_qty);
                //         $this.parents('.input-group').removeClass('has-error');
                //     }
                // });

                // $('.result-calc-number').keyup(function(e) {
                //     e.preventDefault();
                //     var $this   = $(this);
                //     var qty     = $this.val();

                //     if($.isNumeric(qty)) {
                //         var min_qty = $this.attr('min');
                //         if(min_qty==''||min_qty==undefined||min_qty=='undefined'){ min_qty = 0; }
                //         if(new_qty<=min_qty){ new_qty = min_qty; }
                //         if(qty <= min_qty && qty !=""){
                //             qty = min_qty;
                //             $this.val(qty);
                //         }
                //         if(qty > min_qty){
                //             var new_qty = qty;
                //         }
                //     }
                // });

                $(document).on('change', '.form-cart-checkbox', js_calc_this_total );
                $(document).on('click', '.btn-cart-update-qty', js_calc_this_total );
                $(document).on('keyup change', '.cart-update-qty', js_calc_this_total );

                $(document).on('blur', 'input[name="orders-shipping-price"]', function(){
                    var $this = $(this);
                    if($this.val() == ''){ $this.val('0'); }
                });
                $(document).on('keyup change', 'input[name="orders-shipping-price"]', function(){
                    var $this = $(this);
                    var thisVal = $this.val();
                    var temp_cart_shipping_price = 0;
                    var temp_cart_grandtotal_weight = parseInt($('input[name="cart-grandtotal-weight"').val());
                    var temp_grandtotal_cart_shipping_price = 0;
                    if(thisVal != ''){
                        temp_cart_shipping_price = convertRpToInt(thisVal);
                        // temp_grandtotal_cart_shipping_price = parseInt(temp_cart_shipping_price * convertGrToKg(temp_cart_grandtotal_weight)); // Jika dihitung per 1kg
                        temp_grandtotal_cart_shipping_price = temp_cart_shipping_price;
                    }

                    $('input[name="cart-shipping-price"').val(temp_cart_shipping_price);
                    $('input[name="cart-grandtotal-shipping-price"').val(temp_grandtotal_cart_shipping_price);
                    $('.cart-grandtotal-shipping-price-span').html(convertRp(temp_grandtotal_cart_shipping_price));

                    js_calc_grand_total();
                });

                $(document).on('blur', 'input[name="orders-voucher-price"]', function(){
                    var $this = $(this);
                    if($this.val() == ''){ $this.val('0'); }
                });
                $(document).on('keyup change', 'input[name="orders-voucher-price"]', function(){
                    var $this = $(this);
                    var thisVal = $this.val();
                    var temp_cart_voucher_price = 0;
                    if(thisVal != ''){
                        temp_cart_voucher_price = convertRpToInt(thisVal);
                    }

                    $('input[name="cart-voucher-price"').val(temp_cart_voucher_price);
                    $('.cart-voucher-price-span').html(convertRp(temp_cart_voucher_price));

                    js_calc_grand_total();
                });

                $(document).on('click', '.popup-product .popup-close', function(){
                    js_cart_load();
                });
            });

            function js_calc_this_total(e){
                if (typeof e !== 'undefined') e.preventDefault();
                var $this           = $(this);
                var cart_item       = $this.parents('.cart-item');
                var cart_id         = cart_item.attr('data-id');

                // HITUNG TOTAL QUANTITY
                var temp_cart_total_qty = 0;
                // var cart_qty        = cart_item.find('input[name="cart-qty[]"]');
                var variasi_item        = cart_item.find('.product-variasi-item');
                var cart_update_qty     = '';
                $(variasi_item).each(function(i){
                    cart_update_qty = variasi_item.eq(i).find('.cart-update-qty').val();
                    if(cart_update_qty == 'null' || cart_update_qty == null){ cart_update_qty = 0; }
                    temp_cart_total_qty = temp_cart_total_qty + parseInt(cart_update_qty);
                });
                $('.cart-item-'+cart_id).find('input[name="cart-total-qty"]').val(temp_cart_total_qty);
                $('.cart-item-'+cart_id).find('.cart-total-qty-span').html(temp_cart_total_qty+' barang');
                cart_item.find('input[name="cart-total-qty"]').val(temp_cart_total_qty);
                cart_item.find('.cart-total-qty-span').html(temp_cart_total_qty+' barang');
                // END HITUNG TOTAL QUANTITY

                // HITUNG TOTAL BERAT
                var temp_cart_total_weight = 0;
                var cart_weight            = cart_item.find('input[name="cart-weight"]');
                if(temp_cart_total_qty > 0){
                    temp_cart_total_weight = parseInt(temp_cart_total_qty) * parseInt(cart_weight.val());
                }
                $('.cart-item-'+cart_id).find('input[name="cart-total-weight"]').val(temp_cart_total_weight);
                $('.cart-item-'+cart_id).find('.cart-total-weight-span').html(temp_cart_total_weight);
                cart_item.find('input[name="cart-total-weight"]').val(temp_cart_total_weight);
                cart_item.find('.cart-total-weight-span').html(temp_cart_total_weight);
                // END HITUNG TOTAL BERAT

                // HITUNG HARGA SATUAN JIKA ADA HARGA GROSIR
                var temp_cart_price        = 0;
                var temp_cart_price_span   = 0;
                var cart_grosir_price_span = cart_item.find('.cart-grosir-price-span').html();
                if(cart_grosir_price_span != ''){
                    var json = $.parseJSON(cart_grosir_price_span);
                    $(json).each(function(i,val){
                        if(val.qty <= temp_cart_total_qty){
                            temp_cart_price      = val.price;
                            temp_cart_price_span = convertRp(val.price);
                        }
                    });
                    if(temp_cart_price == 0){
                        temp_cart_price      = cart_item.find('.data-normal-price').html();
                        temp_cart_price_span = cart_item.find('.data-normal-price-span').html();
                    }
                    $('.cart-item-'+cart_id).find('input[name="cart-price"]').val(temp_cart_price);
                    $('.cart-item-'+cart_id).find('.cart-price-span').html(temp_cart_price_span);
                    cart_item.find('input[name="cart-price"]').val(temp_cart_price);
                    cart_item.find('.cart-price-span').html(temp_cart_price_span);
                }
                // END HITUNG HARGA SATUAN JIKA ADA HARGA GROSIR

                // HITUNG TOTAL HARGA
                var temp_cart_total_price = 0;
                if(temp_cart_total_qty > 0){
                    temp_cart_total_price = parseInt(temp_cart_price) * parseInt(temp_cart_total_qty);
                }
                $('.cart-item-'+cart_id).find('input[name="cart-total-price"]').val(temp_cart_total_price);
                $('.cart-item-'+cart_id).find('.cart-total-price-span').html(convertRp(temp_cart_total_price));
                cart_item.find('input[name="cart-total-price"]').val(temp_cart_total_price);
                cart_item.find('.cart-total-price-span').html(convertRp(temp_cart_total_price));
                // END HITUNG TOTAL HARGA

                // HITUNG ALL TOTAL
                var temp_cart_grandtotal_weight     = 0;
                var temp_cart_grandtotal_qty        = 0;
                var temp_cart_subgrandtotal_price   = 0;
                var temp_cart_ppn_price             = parseInt($('input[name="cart-ppn-price"').val());
                var temp_grandtotal_cart_ppn_price  = 0;
                var temp_cart_shipping_price        = parseInt($('input[name="cart-shipping-price"').val());
                var temp_grandtotal_cart_shipping_price  = 0;
                var temp_cart_voucher_price         = parseInt($('input[name="cart-voucher-price"').val());
                var temp_cart_grandtotal_price      = 0;
                var temp_div_cart_all_total = $('#temp-div-cart-all-total');
                var temp_div_cart_item      = temp_div_cart_all_total.find('.cart-item-container .cart-item');
                if(temp_div_cart_item.length > 0){
                    $(temp_div_cart_item).each(function(i){
                        var temp_div_cart_item_id = $(this).attr('data-id');
                        if(cart_id != temp_div_cart_item_id){
                            var temp_div_cart_item_weight = parseInt($(this).find('input[name="cart-total-weight"]').val());
                            var temp_div_cart_item_qty = parseInt($(this).find('input[name="cart-total-qty"]').val());
                            var temp_div_cart_item_price = parseInt($(this).find('input[name="cart-total-price"]').val());

                            temp_cart_grandtotal_weight = temp_cart_grandtotal_weight + temp_div_cart_item_weight;
                            temp_cart_grandtotal_qty = temp_cart_grandtotal_qty + temp_div_cart_item_qty;
                            temp_cart_subgrandtotal_price = temp_cart_subgrandtotal_price + temp_div_cart_item_price;
                        }
                    });
                }
                temp_cart_grandtotal_weight = temp_cart_grandtotal_weight + temp_cart_total_weight;
                $('input[name="cart-grandtotal-weight"').val(temp_cart_grandtotal_weight);
                $('.cart-grandtotal-weight-span').html(convertGrToKg(temp_cart_grandtotal_weight));

                temp_cart_grandtotal_qty = temp_cart_grandtotal_qty + temp_cart_total_qty;
                $('input[name="cart-grandtotal-qty"').val(temp_cart_grandtotal_qty);
                $('.cart-grandtotal-qty-span').html(temp_cart_grandtotal_qty+' barang');

                temp_cart_subgrandtotal_price = temp_cart_subgrandtotal_price + temp_cart_total_price;
                $('input[name="cart-subgrandtotal-price"').val(temp_cart_subgrandtotal_price);
                $('.cart-subgrandtotal-price-span').html(convertRp(temp_cart_subgrandtotal_price));

                if(temp_cart_ppn_price > 0){
                    temp_grandtotal_cart_ppn_price = parseInt(temp_cart_subgrandtotal_price * temp_cart_ppn_price)/100;
                }
                $('input[name="cart-grandtotal-ppn-price"').val(temp_grandtotal_cart_ppn_price);
                $('.cart-grandtotal-ppn-price-span').html(convertRp(temp_grandtotal_cart_ppn_price));

                if(temp_cart_shipping_price > 0){
                    temp_grandtotal_cart_shipping_price = parseInt(temp_cart_shipping_price * convertGrToKg(temp_cart_grandtotal_weight));
                }
                $('input[name="cart-grandtotal-shipping-price"').val(temp_grandtotal_cart_shipping_price);
                $('.cart-grandtotal-shipping-price-span').html(convertRp(temp_grandtotal_cart_shipping_price));

                $('input[name="cart-voucher-price"').val(temp_cart_voucher_price);
                $('.cart-voucher-price-span').html('<span class="text-danger">- '+convertRp(temp_cart_voucher_price)+'</span>');

                temp_cart_grandtotal_price = ((temp_cart_subgrandtotal_price + temp_grandtotal_cart_ppn_price + temp_grandtotal_cart_shipping_price) - temp_cart_voucher_price);
                $('input[name="cart-grandtotal-price"').val(temp_cart_grandtotal_price);
                $('.cart-grandtotal-price-span').html('<span class="bold">'+convertRp(temp_cart_grandtotal_price)+'</span>');
                // END HITUNG ALL TOTAL
            }

            function js_calc_grand_total(e){
                if (typeof e !== 'undefined') e.preventDefault();
                // HITUNG ALL TOTAL
                var temp_cart_subgrandtotal_price       = parseInt($('input[name="cart-subgrandtotal-price"').val());
                var temp_grandtotal_cart_ppn_price      = parseInt($('input[name="cart-grandtotal-ppn-price"').val());
                var temp_grandtotal_cart_shipping_price = parseInt($('input[name="cart-grandtotal-shipping-price"').val());
                var temp_cart_voucher_price             = parseInt($('input[name="cart-voucher-price"').val());
                var temp_cart_grandtotal_price          = 0;

                temp_cart_grandtotal_price = ((temp_cart_subgrandtotal_price + temp_grandtotal_cart_ppn_price + temp_grandtotal_cart_shipping_price) - temp_cart_voucher_price);
                $('input[name="cart-grandtotal-price"').val(temp_cart_grandtotal_price);
                $('.cart-grandtotal-price-span').html('<span class="bold">'+convertRp(temp_cart_grandtotal_price)+'</span>');
                // END HITUNG ALL TOTAL

                $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});
            }
        </script>

        <script type="text/javascript">
            $(document).ready(function() {

                $(document).on('click', '.cart-add-btn', function(e){
                    e.preventDefault();
                    swal({
                        title: "Loading!",
                        text: "",
                        type: "loading",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        customClass: 'swal2-small'
                    });
                    var $this   = $(this);
                    var form    = $this.parents('form');
                    var countChecked = form.find('input[type=checkbox].form-cart-checkbox:checked').length;
                    if(countChecked == 0){
                        swal({
                            title: "Error!",
                            text: "Ceklis warna dan isi jumlahnya.",
                            type: "error"
                        });
                    } else {
                        form.submit();
                    }
                });

                $(document).on('submit', 'form.form-cart', addToCart );

                function addToCart(e){
                    if (typeof e !== 'undefined') e.preventDefault();
                    var $this = $(this);
                    var form = $this;

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
                            if(data.cart_err == false){

                                swal({
                                    title: "Success!",
                                    text: "Berhasil menambahkan ke keranjang!",
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
                                        $('.popup-product .popup-close').trigger('click');
                                    },300);
                                });

                                js_cart_load();

                            } else {
                                var data_msg = '';
                                if(data.cart_msg != ''){
                                    $.each( data.cart_msg, function( key, value ) {
                                        data_msg += '<li>'+value+'</li>';
                                    });
                                }
                                $this.find('.input-group').removeClass('has-error');
                                if(data.cart_err_stock == true){
                                    $.each( data.cart_msg_stock, function( key, value ) {
                                        // $this.find('input[name="cart-qty['+value.id+']"]').val(value.qty);
                                        $this.find('input[name="cart-qty['+value.id+']"]').parents('.input-group').addClass('has-error');
                                        data_msg += '<li>'+value.msg+'</li>';
                                    });
                                }

                                swal({
                                    title: "Error!",
                                    html: "<ul>"+data_msg+"</ul>",
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
                }

                $(document).on('click', '.cart-remove-btn', function(e){
                    e.preventDefault();
                    var $this   = $(this);
                    var thisVal = $this.attr('data-id');

                    swal({
                        title: 'Are you sure?',
                        text: "Yakin ingin menghapus!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        confirmButtonClass: 'btn btn-success',
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

                        $('.cart-table').addClass('loading');
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo base_url() ?>admin/orders/cart_remove',
                            data: {'thisVal':thisVal,'thisAction':'remove'},
                            async: false,
                            cache: false,
                            dataType: 'json',
                            beforeSend: function(){

                            },
                            success: function(data){
                                if(data.cart_err == false){
                                    swal({
                                        title: "Success!",
                                        text: data.cart_msg,
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

                                        // setTimeout(function(){
                                        //     $('.popup-product .popup-close').trigger('click');
                                        //     $('.btn-reset-autocomplete-product').trigger('click');
                                        // },300);
                                    });

                                    js_cart_load();
                                } else {
                                    swal({
                                        title: "Error!",
                                        html: data.cart_msg,
                                        type: "error"
                                    });
                                    js_cart_load();
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

                    }, function (dismiss) {
                        // dismiss can be 'cancel', 'overlay',
                        // 'close', and 'timer'
                        // if (dismiss === 'cancel') {
                        //     swal(
                        //         'Cancelled',
                        //         'Your imaginary file is safe :)',
                        //         'error'
                        //         )
                        // }
                    });
                });

                $(document).on('submit', 'form.form-final-checkout', function(e){
                    e.preventDefault();
                    var $this   = $(this);
                    var form    = $this;

                    swal({
                        title: 'Are you sure?',
                        text: "Yakin ingin melakukan checkout?",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, checkout it!',
                        cancelButtonText: 'No, cancel!',
                        confirmButtonClass: 'btn btn-success',
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
                            url: form.attr('action'),
                            data: form.serialize(),
                            async: false,
                            cache: false,
                            dataType: 'json',
                            beforeSend: function(){

                            },
                            success: function(data){
                                if(data.cart_err == false){
                                    $('html,body').animate({ scrollTop: 0}, 200);
                                    swal({
                                        title: "Success!",
                                        text: "Berhasil menyimpan data!",
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
                                    if(data.cart_msg != ''){
                                        $.each( data.cart_msg, function( key, value ) {
                                            data_msg += '<li>'+value+'</li>';
                                        });
                                    }
                                    if(data.cart_err_stock == true){
                                        $.each( data.cart_msg_stock, function( key, value ) {
                                            data_msg += '<li>'+value.msg+'</li>';
                                        });
                                    }

                                    swal({
                                        title: "Error!",
                                        html: "<ul>"+data_msg+"</ul>",
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

                    }, function (dismiss) {
                        // dismiss can be 'cancel', 'overlay',
                        // 'close', and 'timer'
                        // if (dismiss === 'cancel') {
                        //     swal(
                        //         'Cancelled',
                        //         'Your imaginary file is safe :)',
                        //         'error'
                        //         )
                        // }
                    });
                });

            });

            function js_cart_load(e){
                if (typeof e !== 'undefined') e.preventDefault();

                $('.cart-list, .cart-table').addClass('loading');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url() ?>admin/orders/cart_load',
                    data: {'thisVal':'1','thisAction':'load'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){

                    },
                    success: function(data){
                                    // alert(data.cart_table);
                                    setTimeout(function(){
                                        $('.cart-list, .cart-table').removeClass('loading');
                                        $('.cart-list').html(data.cart_list);
                                $('.cart-count').html(data.cart_count);

                                        // $('.cart-table .cart-table-body').html(data.cart_table);
                                        $('#temp-div-cart-all-total .cart-item-container').html(data.cart_temp_item);

                                        $('input[name="cart-grandtotal-qty"').val(data.cart_grandtotal_qty);
                                        $('.cart-grandtotal-qty-span').html(data.cart_grandtotal_qty_span+' barang');

                                        $('input[name="cart-grandtotal-weight"').val(data.cart_grandtotal_weight);
                                        $('.cart-grandtotal-weight-span').html(data.cart_grandtotal_weight_span+' kg');

                                        $('input[name="cart-subgrandtotal-price"').val(data.cart_subgrandtotal_price);
                                        $('.cart-subgrandtotal-price-span').html(data.cart_subgrandtotal_price_span);

                                        $('input[name="cart-ppn-price"').val(data.cart_ppn_price);
                                        $('input[name="cart-grandtotal-ppn-price"').val(data.cart_grandtotal_ppn_price);
                                        $('.cart-grandtotal-ppn-price-span').html(data.cart_grandtotal_ppn_price_span);

                                        $('input[name="orders-shipping-price"').val(data.cart_shipping_price);
                                        $('input[name="cart-shipping-price"').val(data.cart_shipping_price);
                                        $('input[name="cart-grandtotal-shipping-price"').val(data.cart_grandtotal_shipping_price);
                                        $('.cart-grandtotal-shipping-price-span').html(data.cart_grandtotal_shipping_price_span);

                                        $('input[name="orders-voucher-price"').val(data.cart_voucher_price);
                                        $('input[name="cart-voucher-price"').val(data.cart_voucher_price);
                                        $('.cart-voucher-price-span').html(data.cart_voucher_price_span);

                                        $('input[name="cart-grandtotal-price"').val(data.cart_grandtotal_price);
                                        $('.cart-grandtotal-price-span').html(data.cart_grandtotal_price_span);

                                        $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                                        $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                                        $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});
                                        $(document).on('click','input.moneyRp_masking',function(){ this.select(); });
                                    }, 400);

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

            js_cart_load();
        </script>
    </div>
</div>

<?php //debugCode(isset($this->jCfg['cart'])?$this->jCfg['cart']:'') ?>

<?php echo get_temp_div_cart_all_total(); ?>
<!-- <div id="temp-div-cart-all-total" style="display: none !important;">
    <div class="cart-item-container">
        <div class="cart-item cart-item-1" data-id="1">
            <input type="hidden" name="cart-total-weight" value="300" />
            <input type="hidden" name="cart-total-qty" value="3" />
            <input type="hidden" name="cart-total-price" value="45000" />
        </div>
        <div class="cart-item cart-item-2" data-id="2">
            <input type="hidden" name="cart-total-weight" value="200" />
            <input type="hidden" name="cart-total-qty" value="2" />
            <input type="hidden" name="cart-total-price" value="3000" />
        </div>
    </div>
    <div class="cart-total-container">
        <input type="hidden" name="cart-grandtotal-weight" value="0" />
        <input type="hidden" name="cart-grandtotal-qty" value="0" />
        <input type="hidden" name="cart-subgrandtotal-price" value="0" />
        <input type="hidden" name="cart-ppn-price" value="0" />
        <input type="hidden" name="cart-shipping-price" value="0" />
        <input type="hidden" name="cart-voucher-price" value="0" />
        <input type="hidden" name="cart-grandtotal-price" value="0" />
    </div>
</div> -->
