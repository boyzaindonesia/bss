<div class="the-box no-border">

    <?php js_picker() ?>
    <?php js_chosen() ?>
    <?php js_mask() ?>

    <form class="form_save" action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">Informasi Purchase</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <!-- <legend>Info Utama</legend> -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal <span class="req">*</span></label>
                        <?php
                            $date = isset($val->purchase_date) ? date("Y-m-d H:i",strtotime($val->purchase_date)) : date("Y-m-d H:i");
                            $explode_date = explode(" ",$date);
                        ?>
                        <div class="col-sm-3">
                            <input type="text" name="pdate" class="form-control datepicker" value="<?php echo convDatepickerEnc($explode_date[0]) ?>" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                        </div>
                        <div class="col-sm-2" style="padding-left: 0px;">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input type="text" name="phour" class="form-control timepicker" value="<?php echo convTimepickerEnc($explode_date[1]) ?>">
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Invoice</label>
                        <div class="col-sm-5 has-feedback">
                            <input type="text" name="purchase_invoice" value="<?php echo isset($val->purchase_invoice)?$val->purchase_invoice:'';?>" class="form-control text-uppercase check" data-check-id="" data-check-parent="" data-check-rel="purchase_invoice" data-check-msg="" data-check-err="No invoice sudah diinput.">
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Supplier</label>
                        <div class="col-sm-5">
                            <select name="supplier_id" class="form-control" >
                                <option value="" selected>--- Pilih ---</option>
                                <?php
                                $get_supplier = get_supplier();
                                foreach ($get_supplier as $key) {
                                    echo '<option value="'.$key->supplier_id.'" '.(($key->supplier_id==$val->supplier_id)?'selected':'').'>'.$key->supplier_name.'</option>';
                                }
                                ?>
                            </select>
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
                <div class="btn btn-info btn-add-product-array mb-5"><i class="fa fa-plus"></i> Tambah</div>
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
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th class="nobr text-center">Qty</th>
                                <th class="nobr text-center">Satuan</th>
                                <th class="nobr text-center">Harga</th>
                                <th class="nobr text-center">Harga /pcs</th>
                                <th class="nobr text-center">Total</th>
                                <th class="nobr text-center">Reseller</th>
                                <th class="nobr text-center">Harga Reseller</th>
                                <th class="nobr text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="result-product-array">
                            <?php
                            $get_purchase_detail = array(1,2,3);
                            foreach ($get_purchase_detail as $key => $value){ ?>
                            <tr class="item-product-array">
                                <td class="">
                                    <input type="text" name="product_name[]" value="" class="form-control" <?php echo ($key==0?'required':'') ?> />
                                </td>
                                <td class="nobr text-center">
                                    <input type="number" name="product_qty[]" value="1" class="form-control form-qty-sm result-calc-number text-center calc-count-qty" min="1" maxlength="5">
                                </td>
                                <td class="nobr">
                                    <select name="product_satuan_id[]" class="form-control" >
                                        <?php
                                        $i=0;
                                        $get_product_satuan = get_product_satuan();
                                        foreach ($get_product_satuan as $key) {
                                            echo '<option value="'.$key->product_satuan_id.'" data-qty="'.$key->product_satuan_qty.'" '.(($i==0)||($key->product_satuan_id==$value->purchase_detail_satuan)?'selected':'').'>'.$key->product_satuan_name.'</option>';
                                            $i += 1;
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="nobr">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="product_price[]" value="0" class="form-control form-price-sm moneyRp_masking" maxlength="23" required>
                                    </div>
                                </td>
                                <td class="nobr text-left">
                                    <div class="product_price_satuan form-price-sm">Rp 0</div>
                                </td>
                                <td class="nobr text-left">
                                    <div class="product_price_total form-price-sm">Rp 0</div>
                                </td>
                                <td class="nobr text-left">
                                    <select name="store_id[]" class="form-control" >
                                        <?php
                                        $i=0;
                                        $get_store = get_store();
                                        foreach ($get_store as $key) {
                                            echo '<option value="'.$key->store_id.'" '.(($i==0)||($key->store_id==$value->store_id)?'selected':'').'>'.$key->store_name.'</option>';
                                            $i += 1;
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="nobr">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" name="purchase_reseller_price[]" value="0" class="form-control form-price-sm moneyRp_masking" maxlength="23">
                                    </div>
                                </td>
                                <td class="nobr text-center">
                                    <a href="javascript:void(0);" class="btn btn-danger btn-remove-product-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-dark">
                                <td colspan="5" class="nobr text-right">Grand Total Bayar :</td>
                                <td colspan="2"><div class="purchase_price_grand_total_span">Rp 0</div></td>
                                <td colspan="3" class="nobr text-right">&nbsp;</td>
                            </tr>
                        </tfoot>
                    </table>
                    <input type="hidden" name="count_purchase_detail" value="0">

                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(document).on('click', '.btn-calc-number', function(e){
                                e.preventDefault();
                                var $this    = $(this);
                                var new_qty  = '';
                                var result   = $this.parents('.input-group').find('.result-calc-number');
                                if(!$(result).is(':disabled') && $.isNumeric(parseInt($(result).val()))){
                                    new_qty = parseInt($(result).val()) + 1;
                                    if($this.hasClass('minus')){
                                        new_qty = parseInt($(result).val()) - 1;
                                        var min_qty = result.attr('min');
                                        if(min_qty==''||min_qty==undefined||min_qty=='undefined'){ min_qty = 0; }
                                        if(new_qty<=min_qty){ new_qty = min_qty; }
                                    }
                                    $(result).val(new_qty);
                                    js_calc_grand_total();
                                }
                            });


                            $(document).on('blur', 'input[name="product_price[]"]', function(){
                                var $this = $(this);
                                if($this.val() == ''){ $this.val('0'); }
                            });

                            $(document).on('keyup change', 'input[name="product_name[]"]', js_calc_grand_total );
                            $(document).on('keyup change', '.calc-count-qty', js_calc_grand_total );
                            $(document).on('change', 'select[name="product_satuan_id[]"]', js_calc_grand_total );
                            $(document).on('keyup change', 'input[name="product_price[]"]', js_calc_grand_total );

                            $(document).on('click','input[type="number"]',function(){ this.select(); });
                            $(document).on('click','input.moneyRp_masking',function(){ this.select(); });
                        });

                        function js_calc_grand_total(e){
                            if (typeof e !== 'undefined') e.preventDefault();
                            var item_array = $('.result-product-array').find('.item-product-array');
                            var count_item_array = 0;
                            var count_purchase_detail = 0;
                            var grand_total = 0;
                            $(item_array).each(function(i){
                                var this_val = item_array.eq(i).find('.calc-count-qty').val();
                                if(!$.isNumeric(this_val)) { this_val = 0; }
                                count_item_array = count_item_array + parseInt(this_val);

                                var this_name  = item_array.eq(i).find('input[name="product_name[]"]').val();
                                var this_qty   = parseInt(item_array.eq(i).find('input[name="product_qty[]"]').val());
                                var this_price = item_array.eq(i).find('input[name="product_price[]"]').val();

                                var product_satuan_id  = $('option:selected', item_array.eq(i).find('select[name="product_satuan_id[]"]')).val();
                                var product_satuan_qty = $('option:selected', item_array.eq(i).find('select[name="product_satuan_id[]"]')).attr('data-qty');

                                var this_total = 0;
                                var total_qty  = 0;
                                var total_price_satuan = item_array.eq(i).find('.product_price_satuan');
                                var total_price_span   = item_array.eq(i).find('.product_price_total');

                                if(!$.isNumeric(this_qty)){ this_qty = 0; }
                                if(this_price == ''){ this_price = 0; } else { this_price = convertRpToInt(this_price); }
                                if(!$.isNumeric(this_price)){ this_price = 0; }

                                total_qty   = (product_satuan_qty * this_qty);

                                this_satuan = Math.ceil(this_price / product_satuan_qty);
                                this_total  = (this_qty * this_price);
                                grand_total = (grand_total + this_total);

                                total_price_satuan.html(convertRp(this_satuan));
                                total_price_span.html(convertRp(this_total));


                                if(parseInt(this_total) > 0){
                                    item_array.eq(i).find('input[name="product_name[]"]').attr("required","required");
                                } else {
                                    item_array.eq(i).find('input[name="product_name[]"]').removeAttr("required");
                                }
                                if(this_name != ''){
                                    count_purchase_detail = count_purchase_detail + 1;
                                }
                            });

                            if(count_purchase_detail < 1){
                                $('.result-product-array .item-product-array:eq(0)').find('input[name="product_name[]"]').attr("required","required");
                            }

                            $('input[name="count_purchase_detail"]').val(count_purchase_detail);
                            $('.purchase_price_grand_total_span').html(convertRp(grand_total));
                            // $('input[name="purchase_payment_price"]').val(grand_total);

                            $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});
                        }
                    </script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(document).on('click', '.btn-remove-product-array', function(e){
                                e.preventDefault();
                                $(this).parents('.item-product-array').remove();
                                js_calc_grand_total();
                            });
                            $(document).on('click', '.btn-add-product-array', addProductArray );
                        });

                        function addProductArray() {
                            var count_item = 1 + parseInt($('.result-product-array').find('.item-product-array').length);

                            var $product_layout = '<tr class="item-product-array">';
                                    $product_layout += '<td class="">';
                                        $product_layout += '<input type="text" name="product_name[]" value="" class="form-control" />';
                                    $product_layout += '</td>';
                                    $product_layout += '<td class="nobr text-center">';
                                        $product_layout += '<div class="input-group small">';
                                            $product_layout += '<input type="number" name="product_qty[]" value="1" class="form-control form-qty-sm result-calc-number text-center calc-count-qty" min="1" maxlength="5">';
                                        $product_layout += '</div>';
                                    $product_layout += '</td>';
                                    $product_layout += '<td class="nobr">';
                                        $product_layout += '<select name="product_satuan_id[]" class="form-control" >';
                                            <?php
                                            $i = 0;
                                            $get_product_satuan = get_product_satuan();
                                            foreach ($get_product_satuan as $key) { ?>
                                                $product_layout += '<option value="<?php echo $key->product_satuan_id ?>" data-qty="<?php echo $key->product_satuan_qty ?>" <?php echo ($i==0?'selected':'') ?> ><?php echo $key->product_satuan_name ?></option>';
                                            <?php $i += 1; } ?>
                                        $product_layout += '</select>';
                                    $product_layout += '</td>';
                                    $product_layout += '<td class="nobr">';
                                        $product_layout += '<div class="input-group">';
                                            $product_layout += '<span class="input-group-addon">Rp</span>';
                                            $product_layout += '<input type="text" name="product_price[]" value="0" class="form-control form-price-sm moneyRp_masking" maxlength="23" required>';
                                        $product_layout += '</div>';
                                    $product_layout += '</td>';
                                    $product_layout += '<td class="nobr text-left">';
                                        $product_layout += '<div class="product_price_satuan form-price-sm">Rp 0</div>';
                                    $product_layout += '</td>';
                                    $product_layout += '<td class="nobr text-left">';
                                        $product_layout += '<div class="product_price_total form-price-sm">Rp 0</div>';
                                    $product_layout += '</td>';
                                    $product_layout += '<td class="nobr text-left">';
                                        $product_layout += '<select name="store_id[]" class="form-control" >';
                                        <?php
                                        $i=0;
                                        $get_store = get_store();
                                        foreach ($get_store as $key) { ?>
                                            $product_layout += '<option value="<?php echo $key->store_id ?>" ><?php echo $key->store_name ?></option>';
                                        <?php $i += 1; } ?>
                                        $product_layout += '</select>';
                                    $product_layout += '</td>';
                                    $product_layout += '<td class="nobr">';
                                        $product_layout += '<div class="input-group">';
                                            $product_layout += '<span class="input-group-addon">Rp</span>';
                                            $product_layout += '<input type="text" name="purchase_reseller_price[]" value="0" class="form-control form-price-sm moneyRp_masking" maxlength="23">';
                                        $product_layout += '</div>';
                                    $product_layout += '</td>';
                                    $product_layout += '<td class="nobr text-center">';
                                        $product_layout += '<a href="javascript:void(0);" class="btn btn-danger btn-remove-product-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>';
                                    $product_layout += '</td>';
                                $product_layout += '</tr>';

                            $('.result-product-array').append($product_layout);

                        }
                        <?php if(count($arr_product_product) == 0){ ?>
                            // addProductArray();
                        <?php } ?>
                    </script>
                </div>

                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Catatan</label>
                        <div class="col-sm-9">
                            <textarea name="purchase_noted" class="form-control no-resize" rows="3" maxlength="300"></textarea>
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
                            <div class="col-sm-5"><div class="form-control purchase_price_grand_total_span">Rp 0</div></div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-sm-3 control-label">Status Pembayaran</label>
                            <div class="col-sm-5">
                                <select name="purchase_status" class="form-control" required>
                                    <option value="1" selected>Lunas</option>
                                    <option value="0">Belum Lunas</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Metode Pembayaran</label>
                            <div class="col-sm-5">
                                <select name="purchase_payment_source" class="form-control">
                                    <?php
                                    $i = 0;
                                    $get_purchase_payment_source = get_purchase_payment_source();
                                    foreach ($get_purchase_payment_source as $key) {
                                        echo '<option value="'.$key['id'].'" '.(($i==0||$key['id']==$val->purchase_payment_source)?'selected':'').'>'.$key['name'].'</option>';
                                        $i += 1;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Jumlah Bayar</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" name="purchase_payment_price" value="0" class="form-control moneyRp_masking" maxlength="23">
                                    <input type="hidden" name="purchase_payment_date" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="hidden" name="purchase_id" value="<?php echo isset($val->purchase_id)?$val->purchase_id:'';?>" />
                <input type="submit" name="simpan" class="btn btn-danger" value="Checkout & Save" />
                <a href="<?php echo $own_links.($val->purchase_id!=''?'/view/'.$val->purchase_id.'-'.changeEnUrl($val->purchase_invoice):'');?>"><div class="btn btn-default pull-right">Back</div></a>
            </div>
        </div>
    </form>
</div>
