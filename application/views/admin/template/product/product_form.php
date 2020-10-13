<div class="the-box no-border">

    <?php js_picker() ?>
    <?php js_timepicker() ?>
    <?php getTinymce() ?>

    <?php js_chosen() ?>
    <?php js_mask() ?>

    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI PRODUK</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <!-- <div class="form-group">
                        <label class="col-sm-3 control-label">Kode <span class="req">*</span></label>
                        <div class="col-sm-9 has-feedback">
                            <input type="text" name="product_code" value="<?php echo isset($val->product_code)?$val->product_code:'';?>" class="form-control check" data-check-id="<?php echo isset($val->product_id)?$val->product_id:'';?>" data-check-parent="" data-check-rel="product_code" data-check-msg="Kode Produk belum ada." data-check-err="Kode Produk sudah ada." minlength="4" maxlength="25" required style="text-transform: uppercase;">
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kategori <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <select name="product_category_id" class="form-control" required >
                                <option value="" data-code="" selected disabled>--- SELECT ---</option>
                                <?php
                                $parentId = isset($val)?$val->product_category_id:'';
                                echo get_product_category_parent($parentId);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-product-code">
                        <label class="col-sm-3 control-label">Kode / SKU <span class="req">*</span></label>
                    <?php if($val->product_code == "" || $val->product_id == ""){ ?>
                        <?php
                        $code1 = "";
                        $code2 = "";
                        $code3 = "";
                        if($val->product_code != ""){
                            $code1 = "";
                            $code2 = substr($val->product_code, 2, -2);
                            $code3 = "";
                        }
                        ?>
                        <div class="col-sm-1">
                            <input type="text" name="product_code[]" value="" class="form-control" maxlength="3">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="product_code[]" value="<?php echo $code2 ?>" class="form-control" minlength="1" maxlength="3" style="text-transform: uppercase;" title="Max 3 Character " required>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="product_code[]" value="" class="form-control">
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-3">
                            <input type="text" name="product_code" value="<?php echo $val->product_code ?>" class="form-control">
                        </div>
                        <?php } ?>
                    </div>
                    <?php if($val->product_code == "" || $val->product_id == ""){ ?>
                    <script type="text/javascript">
                        $(document).ready(function() {

                            var product_category = $('select[name="product_category_id"]');
                            var code = $('option:selected', product_category).attr('data-code');
                            $('.form-product-code input[name="product_code[]"]:eq(0)').val(code);
                            create_product_code();

                            $(document).on('change', 'select[name="product_category_id"]', function(e){
                                if (typeof e !== 'undefined') e.preventDefault();
                                var $this = $(this);
                                var code = $('option:selected', $this).attr('data-code');
                                $('.form-product-code input[name="product_code[]"]:eq(0)').val(code);
                                create_product_code();
                            });

                            $(document).on('keyup change', '.form-product-code input[name="product_code[]"]:eq(1)', function(){
                                var product_category = $('select[name="product_category_id"]');
                                var product_category_select = $('option:selected', product_category).val();
                                if(product_category_select == ""){
                                    alert('Harap pilih Kategori terlebih dahulu.');
                                }
                            });
                            $(document).on('blur', '.form-product-code input[name="product_code[]"]:eq(1)', function(){
                                create_product_code();
                            });

                            function create_product_code(){
                                var code1 = $('.form-product-code input[name="product_code[]"]:eq(0)').val().toUpperCase();
                                var code2 = $('.form-product-code input[name="product_code[]"]:eq(1)').val().toUpperCase();
                                var $thisVal = code1+""+code2;

                                if(code1 != "" && code2 != ""){
                                    $.ajax({
                                        type: 'POST',
                                        url: OWN_LINKS+'/create_product_code',
                                        data: {'thisVal':$thisVal,'thisAction':'create'},
                                        async: false,
                                        cache: false,
                                        dataType: 'json',
                                        success: function(data){
                                            $('.form-product-code input[name="product_code[]"]:eq(2)').val(data.code)
                                        },
                                        error: function(jqXHR){
                                            var response = jqXHR.responseText;
                                            alert('error ajax');
                                        }
                                    });
                                }
                            }
                        });
                    </script>
                    <?php } ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Lengkap <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="product_name" value="<?php echo isset($val->product_name)?$val->product_name:'';?>" class="form-control" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Singkat <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="product_name_simple" value="<?php echo isset($val->product_name_simple)?$val->product_name_simple:'';?>" class="form-control" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Motif</label>
                        <div class="col-sm-9">
                            <input type="text" name="product_type_motif" value="<?php echo isset($val->product_type_motif)?$val->product_type_motif:'';?>" class="form-control" maxlength="255">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Group</label>
                        <div class="col-sm-9">
                            <select name="product_group_id" data-allows-new-values="true" data-placeholder="--- Select ---" class="form-control chosen-select" >
                                <option value="0" selected>--- Pilih ---</option>
                                <?php
                                $product_group = get_product_group();
                                foreach ($product_group as $key) {
                                    echo '<option value="'.$key->product_group_id.'" '.(($key->product_group_id==$val->product_group_id)?'selected':'').'>'.$key->product_group_name.'</option>';
                                }
                                ?>
                                <option value="addnew">--- Buat Baru ---</option>
                            </select>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#product_group_name').hide();
                                    $(document).on('change', 'select[name="product_group_id"]', function(e){
                                        if (typeof e !== 'undefined') e.preventDefault();
                                        var $this = $(this);
                                        var $val = $('option:selected', $this).val();
                                        if($val == "addnew"){
                                            $('#product_group_name').show();
                                            $('input[name="product_group_name"]')[0].focus();
                                        } else {
                                            $('#product_group_name').hide();
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="form-group" id="product_group_name">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="text" name="product_group_name" placeholder="Nama Group Baru" value="" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Brand</label>
                        <div class="col-sm-9">
                            <select name="product_brand_id" data-placeholder="--- Select ---" class="form-control chosen-select" >
                                <option value="" selected>--- Pilih ---</option>
                                <?php
                                $product_brand = get_product_brand();
                                foreach ($product_brand as $key) {
                                    echo '<option value="'.$key->product_brand_id.'" '.(($key->product_brand_id==$val->product_brand_id)?'selected':'').'>'.$key->product_brand_name.'</option>';
                                }
                                ?>
                                <option value="addnew">--- Buat Baru ---</option>
                            </select>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#product_brand_name').hide();
                                    $(document).on('change', 'select[name="product_brand_id"]', function(e){
                                        if (typeof e !== 'undefined') e.preventDefault();
                                        var $this = $(this);
                                        var $val = $('option:selected', $this).val();
                                        if($val == "addnew"){
                                            $('#product_brand_name').show();
                                            $('input[name="product_brand_name"]')[0].focus();
                                        } else {
                                            $('#product_brand_name').hide();
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="form-group" id="product_brand_name">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="text" name="product_brand_name" placeholder="Nama Brand Baru" value="" class="form-control" maxlength="255">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tagged</label>
                        <div class="col-sm-9">
                            <select name="product_tags[]" data-placeholder="--- Pilih ---" class="form-control chosen-select" multiple >
                                <?php
                                $arrTags = array();
                                $product_tags = isset($val->product_tags)?$val->product_tags:'';
                                if($product_tags != ''){
                                    $expTags = explode(',', $product_tags);
                                    foreach ($expTags as $n) { $arrTags[] = $n; }
                                }

                                $get_product_tags = get_product_tags(9999999,0);
                                foreach ($get_product_tags as $key => $value) {
                                    $selected = (in_array($value->product_tags_id, $arrTags)?'selected':'');
                                    echo '<option value="'.$value->product_tags_id.'" '.$selected.'>'.$value->product_tags_name.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Supplier</label>
                        <div class="col-sm-9">
                            <select name="supplier_id" data-placeholder="--- Select ---" class="form-control chosen-select" >
                                <option value="" selected>--- Pilih ---</option>
                                <?php
                                $get_supplier = get_supplier();
                                foreach ($get_supplier as $key) {
                                    echo '<option value="'.$key->supplier_id.'" '.(($key->supplier_id==$val->supplier_id)?'selected':'').'>'.$key->supplier_name.'</option>';
                                }
                                ?>
                                <option value="addnew">--- Buat Baru ---</option>
                            </select>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#supplier_name').hide();
                                    $(document).on('change', 'select[name="supplier_id"]', function(e){
                                        if (typeof e !== 'undefined') e.preventDefault();
                                        var $this = $(this);
                                        var $val = $('option:selected', $this).val();
                                        if($val == "addnew"){
                                            $('#supplier_name').show();
                                            $('input[name="supplier_name"]')[0].focus();
                                        } else {
                                            $('#supplier_name').hide();
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="form-group" id="supplier_name">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="text" name="supplier_name" placeholder="Nama Supplier Baru" value="" class="form-control" maxlength="255">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Show</label>
                        <div class="col-sm-9">
                            <div class="onoffswitch">
                                <input type="checkbox" name="product_show_id" class="onoffswitch-checkbox" id="product_show_id" <?php echo isset($val->product_id)&&$val->product_id!=''?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->product_id."'".')"':''?> value="1" <?php echo (isset($val->product_show_id) && $val->product_show_id=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="product_show_id">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <p class="additional-post-wrap pull-right">
                        <span class="additional-post"><i class="fa fa-shopping-cart"></i><?php echo ($val->product_sold==''?'0':$val->product_sold); ?> sold</span>
                        <span class="additional-post"><i class="fa fa-eye"></i><?php echo ($val->product_view==''?'0':$val->product_view); ?> views</span>
                        <span class="additional-post"><i class="fa fa fa-thumbs-up"></i><?php echo ($val->product_like==''?'0':$val->product_like); ?> likes</span>
                        <span class="additional-post"><i class="fa fa-heart"></i><?php echo ($val->product_wishlist==''?'0':$val->product_wishlist); ?> favorite</span>
                        <span class="additional-post">Last Push: <?php echo timeAgo($val->product_date_push) ?></span>
                        <span class="additional-post">Dibuat oleh: <?php echo get_user_name($val->product_user_id); ?></span>
                    </p>

                </div>
            </div>
        </div>

        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">DESKRIPSI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ringkasan</label>
                        <div class="col-sm-9">
                            <textarea name="product_lead" class="form-control no-resize" rows="3" maxlength="300"><?php echo isset($description->product_lead)?$description->product_lead:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="product_description" class="form-control tinymce" rows="6"><?php echo isset($description->product_description)?$description->product_description:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Keyword</label>
                        <div class="col-sm-9">
                            <input type="text" name="product_meta_keyword" value="<?php echo isset($description->product_meta_keyword)?$description->product_meta_keyword:""?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Description</label>
                        <div class="col-sm-9">
                            <textarea name="product_meta_description" class="form-control no-resize" rows="6"><?php echo isset($description->product_meta_description)?$description->product_meta_description:""?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">DETAIL</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Minimum Order <span class="req">*</span></label>
                        <div class="col-sm-3 ">
                            <input type="number" name="product_min_order" value="<?php echo isset($detail->product_min_order)?$detail->product_min_order:"1"?>" class="form-control" min="1" maxlength="12" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Price Buy</label>
                        <div class="col-sm-3 ">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="product_price_buy" value="<?php echo isset($detail->product_price_buy)?$detail->product_price_buy:""?>" class="form-control moneyRp_masking" maxlength="23">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Price Sale</label>
                        <div class="col-sm-3 ">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="product_price_sale" value="<?php echo isset($detail->product_price_sale)?$detail->product_price_sale:""?>" class="form-control moneyRp_masking" maxlength="23">

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Discount</label>
                        <div class="col-sm-3">
                            <div class="checkbox">
                              <label><input type="checkbox" name="chk_product_price_discount" onclick="enabledProductDiscount();" value="1" <?php echo isset($detail->product_price_discount)&&$detail->product_price_discount!='0'?'checked':''?> > Yes</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ProductDiscount" style="<?php echo isset($detail->product_price_discount)&&$detail->product_price_discount!='0'?'':'display: none;' ?>">
                        <label class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-5 ">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="product_price_discount" value="<?php echo isset($detail->product_price_discount)?$detail->product_price_discount:"0"?>" class="form-control moneyRp_masking" maxlength="23">
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        function enabledProductDiscount(){
                            if($('input[name="chk_product_price_discount"]').is(':checked')){
                                $('.ProductDiscount').show();
                                $('input[name="product_price_discount"]').attr('required','required').focus();
                            } else {
                                $('.ProductDiscount').hide();
                                $('input[name="product_price_discount"]').removeAttr('required');
                            }
                        }
                    </script>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Grosir</label>
                        <div class="col-sm-3">
                            <div class="checkbox">
                              <label><input type="checkbox" name="chk_product_price_grosir" onclick="enabledPriceGrosir();" value="1" <?php echo isset($detail->product_price_grosir) && $detail->product_price_grosir != ''?'checked':''?> > Yes</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group PriceGrosir" style="<?php echo isset($detail->product_price_grosir) && $detail->product_price_grosir != ''?'':'display: none;'?>">
                        <label class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-9 content-PriceGrosir">
                            <div class="table-responsive">
                                <table class="table table-th-block">
                                    <colgroup>
                                        <col width="1">
                                        <col>
                                        <col width="1">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="nobr text-center" width="130">Qty</th>
                                            <th>Price Grosir</th>
                                            <th class="nobr text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="result-grosir-array">
                                        <?php
                                        $product_price_grosir = json_decode($detail->product_price_grosir);
                                        foreach ($product_price_grosir as $key => $value) { ?>
                                        <tr class="item-grosir-array">
                                            <td class="nobr">
                                                <input type="text" name="product_qty_grosir[]" value="<?php echo $value->qty ?>" class="form-control" min="1" maxlength="5" required>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp</span>
                                                    <input type="text" name="product_price_grosir[]" value="<?php echo $value->price ?>" class="form-control moneyRp_masking" maxlength="23" required>
                                                </div>
                                            </td>
                                            <td class="nobr text-center">
                                                <a href="javascript:void(0);" class="btn btn-danger btn-remove-grosir-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <div class="btn btn-info btn-add-grosir-array"><i class="fa fa-plus"></i> Tambah</div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $(document).on('click', '.btn-remove-grosir-array', function(e){
                                        e.preventDefault();
                                        $(this).parents('.item-grosir-array').remove();
                                    });
                                    $(document).on('click', '.btn-add-grosir-array', addGrosirArray );
                                });

                                function addGrosirArray() {
                                    var $grosir_layout = '<tr class="item-grosir-array">';
                                            $grosir_layout += '<td class="nobr">';
                                                $grosir_layout += '<input type="text" name="product_qty_grosir[]" value="" class="form-control" min="1" maxlength="5" required>';
                                            $grosir_layout += '</td>';
                                            $grosir_layout += '<td>';
                                                $grosir_layout += '<div class="input-group">';
                                                    $grosir_layout += '<span class="input-group-addon">Rp</span>';
                                                    $grosir_layout += '<input type="text" name="product_price_grosir[]" value="" class="form-control moneyRp_masking" maxlength="23" required>';
                                                $grosir_layout += '</div>';
                                            $grosir_layout += '</td>';
                                            $grosir_layout += '<td class="nobr text-center">';
                                                $grosir_layout += '<a href="javascript:void(0);" class="btn btn-danger btn-remove-grosir-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>';
                                            $grosir_layout += '</td>';
                                        $grosir_layout += '</tr>';

                                    $('.result-grosir-array').append($grosir_layout);

                                    $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                                    $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                                    $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});
                                }
                                <?php if(count($product_price_grosir)==0){ ?>
                                    // addGrosirArray();
                                <?php } ?>

                                function enabledPriceGrosir(){
                                    if($('input[name="chk_product_price_grosir"]').is(':checked')){
                                        $('.PriceGrosir').show();
                                        addGrosirArray();
                                        // $('input[name="product_price_discount"]').attr('required','required').focus();
                                    } else {
                                        $('.PriceGrosir').hide();
                                        $('.result-grosir-array').html("");
                                        // $('input[name="product_price_discount"]').removeAttr('required');
                                    }
                                }
                            </script>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Size</label>
                        <div class="col-sm-3 ">
                            <input type="text" name="product_size" value="<?php echo isset($detail->product_size)?$detail->product_size:""?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Berat</label>
                        <div class="col-sm-4 ">
                            <div class="input-group">
                                <input type="number" name="product_weight" value="<?php echo isset($detail->product_weight)?$detail->product_weight:"1"?>" min="0" class="form-control">
                                <span class="input-group-addon">Gram</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Stock Status <span class="req">*</span></label>
                        <div class="col-sm-3">
                            <select name="product_status_id" class="form-control" required>
                                <?php
                                $product_status_id = isset($detail->product_status_id)?$detail->product_status_id:"";
                                $get_product_status = get_product_status();
                                if(count($get_product_status)>0){
                                    $i = 0;
                                    foreach ($get_product_status as $key) {
                                        echo '<option value="'.$key->product_status_id.'" '.(($i=='0')||($product_status_id==$key->product_status_id)?'selected':'').'>'.$key->product_status_name.'</option>';
                                        $i += 1;
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Stok Awal</label>
                        <div class="col-sm-3">
                            <input type="text" name="product_stock_first" value="<?php echo isset($detail->product_stock_first)&&$val->product_id!=''?$detail->product_stock_first:"0"?>" min="0" class="form-control" required >
                        </div>
                    </div>
                    <?php
                    $chkVariant   = false;
                    $groupStock   = "";
                    $groupVariant = "hide";
                    if($detail->product_stock_detail != '' && $detail->product_stock_detail != NULL){
                        $chkVariant   = true;
                        $groupStock   = "disabled";
                        $groupVariant = "";
                    }
                    ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Stok Saat Ini</label>
                        <div class="col-sm-3">
                            <input type="text" name="product_stock" value="<?php echo isset($detail->product_stock)?$detail->product_stock:"0"?>" min="0" class="form-control groupStock" <?php echo $groupStock ?> required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <div class="checkbox">
                                <label><input type="checkbox" class="chkVariant" name="chkVariant" value="1" <?php echo ($chkVariant?'checked':'') ?> onclick="checkProductVariant();"> Ada Variasi</label>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        function checkProductVariant(){
                            if($('.chkVariant').is(':checked')){
                                $('.groupStock').attr('disabled', 'disabled');
                                $('.groupVariant').removeClass('hide');
                                var groupStockQty = $('.groupStock').val();
                                var countItems = $('.result-stok-array').find('.item-stok-array').length;
                                if(countItems == 0){ addStokArray(); }
                            } else {
                                $('.groupStock').removeAttr('disabled');
                                $('.groupVariant').addClass('hide');
                            }
                        }
                    </script>
                    <div class="form-group groupVariant <?php echo $groupStock ?>">
                        <label class="col-sm-3 control-label">Copy Variasi (Apps)</label>
                        <div class="col-sm-9 ">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="product_stock_copy" value="1" <?php echo ($detail->product_stock_copy==1?"checked":"") ?> >
                                    Copy berdasarkan id varian
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="product_stock_copy" value="2" <?php echo ($detail->product_stock_copy==2?"checked":"") ?>>
                                    Copy berdasarkan nama varian
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group groupVariant <?php echo $groupStock ?>">
                        <label class="col-sm-3 control-label">Variasi</label>
                        <div class="col-sm-9 ">
                            <div class="table-responsive">
                                <table class="table table-th-block ">
                                    <colgroup>
                                        <col width="1">
                                        <col>
                                        <col>
                                        <col width="1">
                                        <col width="1">
                                        <col width="1">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="nobr text-center" width="70">ID</th>
                                            <th width="200">Nama</th>
                                            <th>Warna</th>
                                            <th class="nobr text-center" width="120">QTY</th>
                                            <th class="nobr text-center" width="120">Status</th>
                                            <th class="nobr text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="result-stok-array">
                                        <?php
                                        $product_stock_detail = json_decode($detail->product_stock_detail);
                                        foreach ($product_stock_detail as $key => $value) { ?>
                                        <tr class="item-stok-array">
                                            <td>
                                                <input type="text" name="product_stock_id[]" value="<?php echo $value->id ?>" class="form-control form-no text-uppercase text-center" maxlength="2" required>
                                            </td>
                                            <td>
                                                <input type="text" name="product_stock_name[]" value="<?php echo $value->name ?>" class="form-control">
                                            </td>
                                            <td class="has-color-feedback">
                                                <input type="text" name="product_stock_color[]" value="<?php echo strtoupper($value->color) ?>" class="form-control click-color-feedback" <?php echo ($value->color!=''?'style="background-color:#'.$value->color.' ;"':'') ?> required>
                                                <div class="form-color-feedback">
                                                    <?php
                                                    $get_master_color = get_master_color();
                                                    foreach ($get_master_color as $mc) {
                                                        echo '<div class="feedback" data-value="'.$mc->color_hex.'" style="background-color:#'.$mc->color_hex.';">'.$mc->color_name.'</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td class="nobr text-center">
                                                <input type="hidden" name="product_stock_old[]" value="<?php echo $value->qty ?>">
                                                <div class="input-group small">
                                                    <span class="input-group-btn">
                                                        <span class="btn btn-default btn-sm btn-calc-number minus">
                                                            <i class="fa fa-minus small"></i>
                                                        </span>
                                                    </span>
                                                    <input type="text" name="product_stock_qty[]" value="<?php echo ($val->product_id!=""?$value->qty:0) ?>" class="form-control input-sm form-qty-sm result-calc-number text-center calc-count-qty" maxlength="5">
                                                    <span class="input-group-btn">
                                                        <span class="btn btn-default btn-sm btn-calc-number plus">
                                                            <i class="fa fa-plus small"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <select name="product_stock_status[]" class="form-control">
                                                    <option value="1" selected>Ready</option>
                                                    <option value="2" <?php echo ($val->product_id==''||$value->status==2?'selected':'') ?> >Terjual</option>
                                                </select>
                                            </td>
                                            <td class="nobr text-center">
                                                <a href="javascript:void(0);" class="btn btn-danger btn-remove-stok-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">
                                                <div class="btn btn-info btn-add-stok-array"><i class="fa fa-plus"></i> Tambah</div>
                                            </td>
                                            <td align="right">
                                                Total
                                            </td>
                                            <td colspan="3">
                                                <div id="count-item-stok" class="form-control"></div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $(document).on('change keyup', '.click-color-feedback', function(e){
                                        e.preventDefault();
                                        var $this    = $(this);
                                        var $val     = $this.val();
                                        $this.css("background-color","#"+$val);
                                        $('.form-color-feedback').fadeOut();
                                    });
                                    $(document).on('click', '.click-color-feedback', function(e){
                                        e.preventDefault();
                                        var $this    = $(this);
                                        $this.parents('.has-color-feedback').find('.form-color-feedback').fadeIn();
                                    });

                                    $(document).on('click', '.form-color-feedback .feedback', function(e){
                                        e.preventDefault();
                                        var $this    = $(this);
                                        var $val     = $this.attr('data-value');
                                        $this.parents('.has-color-feedback').find('.click-color-feedback').val($val);
                                        $this.parents('.has-color-feedback').find('.click-color-feedback').css("background-color","#"+$val);
                                        $this.parents('.form-color-feedback').fadeOut();
                                    });
                                    $(document).on('click', function(e){
                                        var $clicked = $(e.target);
                                        if (!$clicked.hasClass('click-color-feedback')){
                                            $('.form-color-feedback').fadeOut();
                                        }
                                    });
                                });
                            </script>

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
                                                // var min_qty = result.attr('min');
                                                // if(min_qty==''||min_qty==undefined||min_qty=='undefined'){ min_qty = 0; }
                                                // if(new_qty<=min_qty){ new_qty = min_qty; }
                                            }
                                            if(new_qty >= 0){
                                                $(result).val(new_qty);
                                                js_calc_item_total();

                                                if(new_qty > 0){
                                                    $this.parents('tr.item-stok-array').find('select[name="product_stock_status[]"]').val("1");
                                                } else {
                                                    $this.parents('tr.item-stok-array').find('select[name="product_stock_status[]"]').val("2");
                                                }
                                            }
                                        }
                                    });

                                    $(document).on('keyup change', '.calc-count-qty', function(e){
                                        e.preventDefault();
                                        var $this = $(this);
                                        var qty   = $this.val();
                                        if(qty > 0){
                                            $this.parents('tr.item-stok-array').find('select[name="product_stock_status[]"]').val("1");
                                        } else {
                                            $this.parents('tr.item-stok-array').find('select[name="product_stock_status[]"]').val("2");
                                        }
                                        js_calc_item_total();
                                    });
                                    $(document).on('blur', '.calc-count-qty', function(e){
                                        e.preventDefault();
                                        var $this = $(this);
                                        var qty   = $this.val();
                                        if(qty == ""){ $this.val("0"); }
                                    });
                                });

                                function js_calc_item_total(){
                                    var item_stok = $('.result-stok-array').find('.item-stok-array');
                                    var countItems = 0;
                                    var count_item_stok = 0;
                                    $(item_stok).each(function(i){
                                        countItems = countItems + 1;
                                        var this_val = item_stok.eq(i).find('.calc-count-qty').val();
                                        if(!$.isNumeric(this_val)) { this_val = 0; }
                                        count_item_stok = count_item_stok + parseInt(this_val);
                                    });
                                    $('input[name="product_stock"').attr({'min': count_item_stok}).val(count_item_stok);
                                    <?php if($val->product_id == ""){ ?>
                                    $('input[name="product_stock_first"').attr({'min': count_item_stok}).val(count_item_stok);
                                    <?php } ?>
                                    var total_item_stok = $('input[name="product_stock"').val();
                                    $('#count-item-stok').html(count_item_stok+' item dari total stok '+total_item_stok);
                                    if(countItems < 1){
                                        $('.chkVariant').prop( "checked", false );
                                        $('.groupStock').removeAttr('disabled');
                                        $('.groupVariant').addClass('hide');
                                    }
                                }

                                js_calc_item_total();
                            </script>

                            <script type="text/javascript">
                                $(document).on('click','input[name="product_stock_qty[]"]',function(){ this.select(); });

                                $(document).ready(function() {
                                    $(document).on('click', '.btn-remove-stok-array', function(e){
                                        e.preventDefault();
                                        $(this).parents('.item-stok-array').remove();
                                        js_calc_item_total();
                                    });
                                    $(document).on('click', '.btn-add-stok-array', addStokArray );
                                });

                                function addStokArray() {
                                    var count_item = 1 + parseInt($('.result-stok-array').find('.item-stok-array').length);
                                    if(count_item.toString().length == 1){ count_item = '0'+count_item; }
                                    var $stok_layout = '<tr class="item-stok-array">';
                                            $stok_layout += '<td>';
                                                $stok_layout += '<input type="text" name="product_stock_id[]" value="'+count_item+'" class="form-control form-no text-uppercase text-center" maxlength="2" required>';
                                            $stok_layout += '</td>';
                                            $stok_layout += '<td>';
                                                $stok_layout += '<input type="text" name="product_stock_name[]" value="" class="form-control">';
                                            $stok_layout += '</td>';
                                            $stok_layout += '<td class="has-color-feedback">';
                                                $stok_layout += '<input type="text" name="product_stock_color[]" value="EEEFF1" class="form-control click-color-feedback" style="background-color:#EEEFF1;" required>';
                                                $stok_layout += '<div class="form-color-feedback">';
                                                    <?php
                                                    $get_master_color = get_master_color();
                                                    foreach ($get_master_color as $mc) { ?>
                                                        $stok_layout += '<div class="feedback" data-value="<?php echo $mc->color_hex ?>" style="background-color:#<?php echo $mc->color_hex ?>;"><?php echo $mc->color_name ?></div>';
                                                    <?php } ?>
                                                $stok_layout += '</div>';
                                            $stok_layout += '</td>';
                                            $stok_layout += '<td class="nobr text-center">';
                                            $stok_layout += '<input type="hidden" name="product_stock_old[]" value="">';
                                                $stok_layout += '<div class="input-group small">';
                                                    $stok_layout += '<span class="input-group-btn">';
                                                        $stok_layout += '<span class="btn btn-default btn-sm btn-calc-number minus">';
                                                            $stok_layout += '<i class="fa fa-minus small"></i>';
                                                        $stok_layout += '</span>';
                                                    $stok_layout += '</span>';
                                                    $stok_layout += '<input type="text" name="product_stock_qty[]" value="0" class="form-control input-sm form-qty-sm result-calc-number text-center calc-count-qty" maxlength="5">';
                                                    $stok_layout += '<span class="input-group-btn">';
                                                        $stok_layout += '<span class="btn btn-default btn-sm btn-calc-number plus">';
                                                            $stok_layout += '<i class="fa fa-plus small"></i>';
                                                        $stok_layout += '</span>';
                                                    $stok_layout += '</span>';
                                                $stok_layout += '</div>';
                                            $stok_layout += '</td>';
                                            $stok_layout += '<td>';
                                                $stok_layout += '<select name="product_stock_status[]" class="form-control">';
                                                    $stok_layout += '<option value="1">Ready</option>';
                                                    $stok_layout += '<option value="2" selected>Terjual</option>';
                                                $stok_layout += '</select>';
                                            $stok_layout += '</td>';
                                            $stok_layout += '<td class="nobr text-center">';
                                                $stok_layout += '<a href="javascript:void(0);" class="btn btn-danger btn-remove-stok-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>';
                                            $stok_layout += '</td>';
                                        $stok_layout += '</tr>';

                                    $('.result-stok-array').append($stok_layout);

                                }
                                <?php if(count($arr_product_stok) == 0){ ?>
                                    // addStokArray();
                                <?php } ?>
                            </script>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">IMAGES</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-9">
                            <div class="fullwidth">
                                <div class="btn btn-info" onclick="_addItem();"><i class="fa fa-plus"></i> Tambah</div>
                            </div>
                            <div class="container-masonry mansory-float">
                                <ol id="items-masonry" class="magnific-popup-wrap dragsort" data-url-dragsort="<?php echo $own_links?>/change_position_images_detail">

                                    <?php if(!isset($image_product)||$image_product==''){
                                        $tempToken = generateUniqueToken('20','mt_product','product_id');
                                    ?>
                                    <li class="item-masonry dragsortitem" id="item_<?php echo $tempToken?>" data-itemid="<?php echo $tempToken?>">
                                        <div class="the-box no-border full text-center mansory-inner">
                                            <div class="item-image form-preview-images">
                                                <img src="<?php echo get_image(base_url()."assets/collections/product/","no_image_200x200.jpg");?>" class="media-object img-responsive return-preview-images">
                                                <div class="btn btn-info btn-file">
                                                    <i class="fa fa-upload"></i>
                                                    <input type="file" name="file_<?php echo $tempToken?>" onChange="previewImages(this);" accept="image/*" required />
                                                </div>
                                            </div>
                                            <div class="the-box bg-dark no-margin no-border">
                                                <input type="hidden" name="desc[<?php echo $tempToken?>]" class="form-control" value="" placeholder="Caption">
                                                <a href="javascript:void(0);" onClick=_hapusItem("<?php echo $tempToken?>") class="btn btn-danger btn-xs" title="Hapus"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } ?>

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="hidden" name="product_id" value="<?php echo isset($val->product_id)?$val->product_id:'';?>" />
                <input type="hidden" name="product_detail_id" value="<?php echo isset($detail->product_detail_id)?$detail->product_detail_id:'';?>" />
                <input type="hidden" name="product_description_id" value="<?php echo isset($description->product_description_id)?$description->product_description_id:'';?>" />
                <input type="hidden" name="next" value="<?php echo $this->input->get("next"); ?>" />
                <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                <input type="reset" name="reset" class="btn btn-default" value="Reset" />
                <?php if($this->input->get("next")!=""){ ?>
                <a href="<?php echo $this->input->get("next") ?>"><div class="btn btn-default pull-right">Back</div></a>
                <?php } else { ?>
                <a href="<?php echo $own_links.($val->product_id!=''?'/view/'.$val->product_id.'-'.changeEnUrl($val->product_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                <?php } ?>
            </div>
        </div>

    </form>

    <script type="text/javascript">
        var product_image = [];
        $(document).ready(function(){
            <?php if(isset($image_product)){ ?>
            product_image = eval('<?php echo json_encode($image_product);?>');
            <?php } ?>
            if(product_image.length > 0){
                for(i=0;i<product_image.length;i++){
                    _addItem({
                        id:product_image[i].image_id,
                        desc:product_image[i].image_desc,
                        url:product_image[i].image_filename
                    });
                }
            }
        });

        function _addItem(val){
            idx='A'+Math.round(Math.random()*10000000000000000000);
            if(typeof val == 'undefined'){
                val = {id:idx,desc:'',url:''};
            }
            html = _createHtml(val);
            $('#items-masonry').append(html);
            $('#item_'+idx+' input[type="file"]').trigger('click');
        }

        function _createHtml(o){
            required = 'required';
            product_url = '<?php echo base_url()."assets/collections/product/";?>';
            photo_thumb = '<?php echo get_image(base_url()."/none","no_image_200x200.jpg");?>';
            if($.trim(o.url) != ''){
                photo_thumb = product_url+'small/'+o.url;
                required = '';
            }

            html  = '<li class="item-masonry dragsortitem" id="item_'+o.id+'" data-itemid="'+o.id+'">';
                html += '<div class="the-box no-border full text-center mansory-inner">';
                    html += '<div class="item-image form-preview-images">';
                        html += '<img src="'+photo_thumb+'" class="media-object img-responsive return-preview-images">';
                        html += '<div class="btn btn-info btn-file">';
                            html += '<i class="fa fa-upload"></i>';
                            html += '<input type="file" name="file_'+o.id+'" onChange="previewImages(this);" accept="image/*" '+required+' />';
                        html += '</div>';
                    html += '</div>';
                    if($.trim(o.url) != ''){
                    html += '<div class="the-box bg-dark no-margin no-border btn-drag" title="Drag to Move position">';
                    } else {
                    html += '<div class="the-box bg-dark no-margin no-border">';
                    }
                        html += '<input type="hidden" name="desc['+o.id+']" class="form-control" value="'+o.desc+'" placeholder="Caption">';
                        html += '<div class="btn-group">';
                            if($.trim(o.url) != ''){
                            html += '<a href="'+product_url+'large/'+o.url+'" class="zooming btn btn-info btn-xs" title="'+o.desc+'"><i class="fa fa-search-plus" title="Zoom"></i></a>';
                            }
                            html += '<a href="javascript:void(0);" onClick=_hapusItem("'+o.id+'") class="btn btn-danger btn-xs" title="Hapus"><i class="fa fa-times"></i></a>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            html += '</li>';
            return html;
        }

        function _hapusItem(id){
            $('#item_'+id).fadeOut("medium",function(){
                $(this).remove();
            });
        }

    </script>

    <!-- dragsort -->
    <?php js_dragsort() ?>

</div>