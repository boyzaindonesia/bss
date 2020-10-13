<div class="the-box no-border">

    <?php js_mask() ?>

    <form class="form-final-checkout" action="<?php echo $own_links;?>/checkout" autocomplete="off" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI</h3>
            </div>
            <div class="panel-body">
                <div class="relative mb-10">
                    <div class="form-group">
                        <label>Nama Customer <span class="req">*</span></label>
                        <div class="row">
                            <div class="col-sm-5">
                                <input type="text" name="orders_shipping_name" value="" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Catatan (optional)</label>
                        <div class="row">
                            <div class="col-sm-5">
                                <textarea name="orders_noted" class="form-control no-resize" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative mb-20">
                    <label>Cari Produk</label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="search" id="search_product_array" name="search_product" value="" class="form-control" placeholder="Cari Produk..." />
                            <div id="search_product_array_feedback"></div>
                            <script type="text/javascript">
                                $(document).ready(function() {

                                    var productParams   = {
                                        'product_show'          : '1',
                                        'product_approved'      : '1',
                                        'product_status_id'     : '!= 3',
                                        'reseller_orders'       : true,
                                        'thisAction'            : 'getdata'
                                    };
                                    var cg = new customGeneral();
                                    var ProductArray = cg.getProduct(productParams);
                                    console.log(ProductArray);
                                    cg.autoCompleteProduct(ProductArray, "#search_product_array", 0);

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
                                            variasi.find('.select .form-control').val('0');
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
                                                data: {'thisVal':$thisVal, 'reseller_orders':true, 'thisAction':'get_product'},
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
                </div>

                <label id="keranjang-belanja">Keranjang Belanja</label>
                <div class="cart-list"></div>

                <div class="form-horizontal">
                    <?php if($store_id == 1){ ?>
                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">Sub Total Harga</label>
                        <div class="col-sm-5">
                            <div class="form-control cart-subgrandtotal-price-span"></div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">Total Biaya Kurir</label>
                        <div class="col-sm-3">
                            <div class="input-group danger-color">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders-shipping-price" value="" class="form-control moneyRp_masking" maxlength="23" required>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group mb-0">
                        <label class="col-sm-3 control-label">Grand Total Bayar</label>
                        <div class="col-sm-5">
                            <strong><div class="form-control cart-grandtotal-price-span"></div></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <?php if($store_id != 1){ ?>
                <input type="hidden" name="orders-shipping-price" value="" />
                <input type="hidden" name="orders-voucher-price" value="" />
                <input type="hidden" name="orders-voucher-code" value="" />
                <?php } ?>
                <input type="hidden" name="cart-grandtotal-qty" value="" />

                <input type="hidden" name="orders_id" value="" />
                <input type="hidden" name="reseller_orders" value="true" />
                <input type="hidden" name="thisAction" value="checkout" />
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
                                        $this.find('input[name="cart-qty['+value.id+']"]').parents('.input-group').addClass('has-error');
                                        data_msg += '<li>'+value.msg+'</li>';
                                    });
                                }

                                swal({
                                    title: "Error!",
                                    html: "<ul style='text-align:left;'>"+data_msg+"</ul>",
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
                        title: 'HAPUS?',
                        text: "Yakin ingin menghapus dari keranjang!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Tidak, batalkan!',
                        confirmButtonClass: 'btn btn-success mr-10',
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
                            url: OWN_LINKS+'/cart_remove',
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

                    });
                });

                $(document).on('submit', 'form.form-final-checkout', function(e){
                    e.preventDefault();
                    var $this   = $(this);
                    var form    = $this;

                    var countItem = $('input[name="cart-grandtotal-qty"]').val();
                    if(countItem > 0){

                        swal({
                            title: 'CHECKOUT?',
                            text: "Yakin ingin melakukan Checkout?",
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, checkout!',
                            cancelButtonText: 'Tidak, batalkan!',
                            confirmButtonClass: 'btn btn-success mr-10',
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
                                            text: "Berhasil Checkout!",
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
                    } else {
                        swal({
                            title: "Error!",
                            html: "Anda belum memilih produk.",
                            type: "error"
                        });
                        return false;
                    }
                });

            });

            function js_cart_load(e){
                if (typeof e !== 'undefined') e.preventDefault();

                $('.cart-list, .cart-table').addClass('loading');
                $.ajax({
                    type: 'POST',
                    url: OWN_LINKS+'/cart_load',
                    data: {'thisVal':'1', 'reseller_orders':true, 'thisAction':'load'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){

                    },
                    success: function(data){
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

                            if(data.cart_count > 0){
                                $('#keranjang-belanja').html('Keranjang Belanja <span class="btn btn-danger btn-xs cart-empty-btn"><i class="fa fa-times"></i> Kosongkan Keranjang</span>');
                            } else {
                                $('#keranjang-belanja').html('Keranjang Belanja');
                            }
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

            $(document).on('click', '.cart-empty-btn', function(e){
                e.preventDefault();
                var $this   = $(this);

                swal({
                    title: 'KOSONGKAN?',
                    text: "Yakin ingin kosongkan keranjang!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, kosongkan!',
                    cancelButtonText: 'Tidak, batalkan!',
                    confirmButtonClass: 'btn btn-success mr-10',
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
                        url: OWN_LINKS+'/cart_empty',
                        data: {'thisVal':'1','thisAction':'remove'},
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

                });
            });
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
