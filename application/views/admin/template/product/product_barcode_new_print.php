
<div class="the-box no-border">

    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group">
            <span class="magnific-popup-ajax">
                <a href="#form-produk" class="btn btn-primary margin-bottom" data-effect="mfp-zoom-in" data-ajax-url="<?php echo $own_links.'/get_table_add_products/'?>"><i class="fa fa-plus"></i> Tambah Produk</a>
            </span>
        </div>


        <div class="btn-group pull-right">
            <div class="btn btn-warning btn-cetak" data-url="<?php echo $own_links.'/export_barcode' ?>"><i class="fa fa-print"></i> Export Barcode ( 0 )</div>
        </div>
    </div>

    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable-all">
            <colgroup>
                <col width="1">
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort"><input type="checkbox" name="" onclick="check_all_files(this);" <?php echo ($tab=="tab1"&&count($data)>0?'checked':'') ?> value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"></th>
                    <th class="nobr">Nama</th>
                    <th class="no-sort">Varian</th>
                    <th class="nobr text-center no-sort">Action Stok</th>
                    <th class="nobr text-center no-sort"></th>
                </tr>
            </thead>
            <tbody>
            <?php
            // debugCode($data);
            if(count($data) > 0){
                foreach($data as $r){
                    $tr_class = '';

                ?>
                <tr class="<?php echo $tr_class ?>" data-id="<?php echo $r->print_barcode_id;?>" title="">
                    <td class="nobr text-center"><input type="checkbox" name="checked_files[]" value="<?php echo $r->print_barcode_id;?>" checked ></td>
                    <td class="nobr"><?php echo $r->product_name_simple;?><br><?php echo $r->product_code;?></td>
                    <td>
                        <div class="row no-margin">
                        <?php
                        if($r->product_detail != ''){
                            $ii = 0;
                            $product_detail = json_decode($r->product_detail);
                            foreach ($product_detail as $k => $v) {
                                if($ii == 5){ echo '</div><div class="row no-margin">'; $ii = 0; }
                            ?>
                                <div class="col-sm-25" style="margin-bottom: 3px; padding: 0 0 0 5px;">
                                    <div class="input-group danger-color">
                                        <span class="input-group-addon"><small><?php echo $v->name; ?></small></span>
                                        <input type="text" name="product_qty[<?php echo $r->print_barcode_id; ?>-<?php echo $v->id; ?>]" value="<?php echo $v->qty; ?>" class="form-control" style="font-size: 12px; line-height: 16px;" maxlength="3">
                                    </div>
                                </div>
                            <?php
                            $ii += 1;
                            }
                        } else {
                        ?>
                        <div class="col-sm-25" style="margin-bottom: 3px; padding: 0 0 0 5px;">
                            <div class="input-group danger-color">
                                <span class="input-group-addon"><small>Jumlah</small></span>
                                <input type="text" name="product_qty[<?php echo $r->print_barcode_id; ?>]" value="<?php echo $r->product_qty; ?>" class="form-control" style="font-size: 12px; line-height: 16px;" maxlength="3">
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        </div>
                    </td>
                    <td class="nobr text-left">
                        <div class="radio" style="display: block;">
                            <label>
                                <input type="radio" name="optSaveStock[<?php echo $r->print_barcode_id ?>]" value="0" checked>
                                Tidak Simpan
                            </label>
                        </div>
                        <div class="radio" style="display: block;">
                            <label>
                                <input type="radio" name="optSaveStock[<?php echo $r->print_barcode_id ?>]" value="1">
                                Simpan Stok Baru
                            </label>
                        </div>
                        <div class="radio" style="display: block;">
                            <label>
                                <input type="radio" name="optSaveStock[<?php echo $r->print_barcode_id ?>]" value="2">
                                Tambah Stok
                            </label>
                        </div>
                    </td>
                    <td class="nobr text-center">
                        <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?php echo $r->print_barcode_id;?>" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-times"></i></button>
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

<div id="form-produk" class="white-popup wide mfp-with-anim mfp-hide">
    <form id="formproduk" action="" method="post" enctype="multipart/form-data">
        <legend><strong>Pilih Produk</strong></legend>
        <div class="btn-toolbar toolbar-btn-action">
            <div class="btn-group pull-right">
                <div class="btn btn-info btn-save-multi" data-url="<?php echo $own_links.'/save_multi_detail' ?>"><i class="fa fa-plus"></i> Tambahkan ( 0 )</div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-th-block table-dark">
                <colgroup>
                    <col width="1">
                    <col width="1">
                    <col width="1">
                    <col>
                    <col width="1">
                    <col width="1">
                    <col width="1">
                </colgroup>
                <thead>
                    <tr>
                        <th class="nobr text-center no-sort"><input type="checkbox" name="checked_all_product" onclick="checked_all_product(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"></th>
                        <th class="nobr">ID</th>
                        <th class="nobr text-center no-sort">Images</th>
                        <th class="">Nama</th>
                        <th class="nobr text-center">Kategori</th>
                        <th class="nobr text-center no-sort">Jumlah Pernomer</th>
                        <th class="nobr text-center no-sort">Status</th>
                    </tr>
                </thead>
                <tbody class="popup-ajax-result">

                </tbody>
            </table>
        </div>
    </form>
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
        $('.btn-cetak').html('<i class="fa fa-print"></i> Export Barcode ( '+checked_files.length+' )');
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
            var form1 = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau dicetak.",
                    type: "error"
                });
            } else {
                // var thisId  = "";
                // var thisVal = "";
                // checked_files.each(function() {
                //     thisVal = $(this).val();
                //     thisId += (thisId=="" ? thisVal : "-" + thisVal);
                // });

                var url   = $(this).attr('data-url');
                // window.open(url+'?id='+thisId, '_blank');
                $.ajax({
                    type: 'POST',
                    url: OWN_LINKS+'/generate_export',
                    data: form1.serialize(),
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        window.open(url, '_blank');
                        swal({
                            title: "Success!",
                            text: data.msg,
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(
                        function () {},
                        function (dismiss) {
                            // if (dismiss === 'timer'){ }
                            setTimeout(function(){
                                // window.open(url+'?id='+thisId, '_blank');
                                window.location.reload(true);
                            },1000);
                        });

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

    });
</script>

<script type="text/javascript">
    function checked_all_product(e){
        var checkbox = $('input[name="checked_product[]"]');
        if($(e).is(':checked')){
            checkbox.prop('checked', true);
            checkbox.parents('tr').addClass('selected');
        } else {
            checkbox.prop('checked', false);
            checkbox.parents('tr').removeClass('selected');
        }
        calc_check_product();
    }

    calc_check_product();
    function calc_check_product(){
        var formproduk = $('form#formproduk');
        var checked_product = formproduk.find('input[name="checked_product[]"]:checked');
        $('.btn-save-multi').html('<i class="fa fa-plus"></i> Tambahkan ( '+checked_product.length+' )');
    }

    $(document).ready(function() {
        $(document).on('click','input[type="text"]',function(){ this.select(); });
        $(document).on('click','input[type="number"]',function(){ this.select(); });

        $(document).on('change', 'input[name="checked_product[]"]', function(e){
            e.preventDefault();
            if($(this).is(':checked')){
                $(this).parents('tr').addClass('selected');
            } else {
                $(this).parents('tr').removeClass('selected');
            }
            calc_check_product();
        });

        $(document).on('keyup change', '.form-control.product_qty', function(){
            var $this    = $(this);
            var $val     = $this.val();
            if($val != "" || $val != 0){
                var checkbox = $this.parents('tr').find('input[name="checked_product[]"]');
                checkbox.prop('checked', true);
                checkbox.parents('tr').addClass('selected');
                calc_check_product();
            }
        });

        $(document).on('click', '.btn-save-multi', function(e){
            e.preventDefault();
            var url   = $(this).attr('data-url');
            var formproduk = $('form#formproduk');
            var checked_product = formproduk.find('input[name="checked_product[]"]:checked');
            if(checked_product.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau ditambahkan.",
                    type: "error"
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formproduk.serialize(),
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        swal({
                            title: "Success!",
                            text: data.msg,
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(
                        function () {},
                        function (dismiss) {
                            // if (dismiss === 'timer'){ }
                            setTimeout(function(){
                                window.location.reload(true);
                            },300);
                        });

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

        $(document).on('click', '.btn-delete', function(e){
            e.preventDefault();
            var thisId  = $(this).attr('data-id');

            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {

                $.ajax({
                    type: 'POST',
                    url: OWN_LINKS+'/deletes',
                    data: {'thisId':thisId,'thisAction':'delete'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        swal({
                            title: "Success!",
                            text: data.msg,
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(
                        function () {},
                        function (dismiss) {
                            // if (dismiss === 'timer'){ }
                            setTimeout(function(){
                                window.location.reload(true);
                            },300);
                        });

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
              if (dismiss === 'cancel'){ }
            });
        });

    });
</script>
