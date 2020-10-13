
<div class="the-box no-border">

    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group">
            <div class="btn btn-success btn-popup" data-id="0"><i class="fa fa-plus"></i> Buat Label</div>
        </div>

        <div class="btn-group pull-right">
            <div class="btn btn-warning btn-cetak" data-url="<?php echo base_url().'print-address' ?>"><i class="fa fa-print"></i> Cetak Alamat ( 0/0 )</div>
        </div>
        <div class="btn-group pull-right">
            <div class="btn btn-primary btn-cetak-simple" data-url="<?php echo base_url().'print-address-simple' ?>"><i class="fa fa-plus"></i> Cetak Simple ( 0/0 )</div>
        </div>
    </div>

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

    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable-all table-font-small">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col>
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort"><input type="checkbox" name="checkallfiles" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"></th>
                    <th class="nobr text-center">Source</th>
                    <th class="nobr text-center" width="90">Resi</th>
                    <th class="nobr text-center">Kurir</th>
                    <th class="nobr text-center">Paket</th>
                    <th class="nobr">Nama Penerima</th>
                    <th>Alamat</th>
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
                    // if($r->orders_shipping_resi != ''){
                        $total_resi += 1;
                        $checked_files = 'checked';
                    // }
                ?>
                <tr class="<?php echo $tr_class ?>" data-id="<?php echo $r->print_address_id;?>" title="<?php echo 'Nama Pengirim: '.$orders_ship_name ?>">
                    <td class="nobr text-center"><input type="checkbox" name="checked_files[]" value="<?php echo $r->print_address_id;?>" <?php echo $checked_files ?> ></td>
                    <td class="nobr text-center">
                        <?php echo get_orders_source($r->orders_source_id)->orders_source_name;?>
                        <br>
                        <span class="label <?php echo ($r->print_address_status == 0?'label-danger':'label-default') ?>"><?php echo getYearMonthDate($r->print_address_date) ?></span>
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
                        <button type="button" class="btn btn-warning btn-xs btn-popup-detail-courier" data-id="<?php echo $r->print_address_id;?>" data-toggle="tooltip" data-original-title="Detail Courier"><i class="fa fa-clipboard"></i></button>
                        <div class="price_shipping"><?php echo ($r->orders_price_shipping==0?'':'Ongkir: '.convertRp($r->orders_price_shipping)) ?></div>
                        <div class="price_insurance"><?php echo ($r->orders_price_insurance==0?'':'Asuransi: '.convertRp($r->orders_price_insurance)) ?></div>
                        <div class="shipping_weight"><?php echo ($r->orders_shipping_weight<2?'':'Berat: '.$r->orders_shipping_weight) ?></div>
                    </td>
                    <td class="nobr">
                        <div class="product_title"><?php echo $r->orders_product_category_title;?></div>
                        <div class="price_product"><?php echo ($r->orders_price_product==0?'':'Harga: '.convertRp($r->orders_price_product)) ?></div>
                    </td>
                    <td><?php echo $r->orders_shipping_name;?></td>
                    <td><?php echo getFirstParaNumb($r->orders_shipping_address,40) ?></td>
                    <td><?php echo getFirstParaNumb($r->orders_noted,40) ?></td>
                    <td class="nobr">
                        <button type="button" class="btn btn-info btn-xs btn-popup" data-id="<?php echo $r->print_address_id;?>" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?php echo $r->print_address_id;?>" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i></button>
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

                window.open(dataUrl+'?id='+thisId+"&from=print_address", '_blank');
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

                window.open(dataUrl+'?id='+thisId+"&from=print_address", '_blank');
            }
        });

    });
</script>

<div class="popup popup-print-address">
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
            if($thisVal != ''){
                $.ajax({
                    type: 'POST',
                    url: OWN_LINKS+'/views',
                    data: {'thisVal':$thisVal,'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-print-address').addClass('active');
                        $('.popup-print-address .popup-content').html(data.content);
                        // console.log(data.result);

                        $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                        $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                        $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});

                        $(document).on('click','input[type="number"]',function(){ this.select(); });
                        $(document).on('click','input.moneyRp_masking',function(){ this.select(); });
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

        $(document).on('click', '.btn-popup-detail-courier', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            if($thisVal != ''){
                $.ajax({
                    type: 'POST',
                    url: OWN_LINKS+'/form_detail_courier',
                    data: {'thisVal':$thisVal,'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-print-address').addClass('active');
                        $('.popup-print-address .popup-content').html(data.content);
                        // console.log(data.result);

                        $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                        $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                        $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});

                        $(document).on('click','input[type="text"]',function(){ this.select(); });
                        $(document).on('click','input[type="number"]',function(){ this.select(); });
                        $(document).on('click','input.moneyRp_masking',function(){ this.select(); });
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
            var $this    = $(this);
            var $thisId = $this.attr('data-id');
            if($thisId != ''){
                swal({
                    title: 'Are you sure?',
                    text: "Yakin ingin menghapus!",
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
                        url: OWN_LINKS+'/deletes',
                        data: {'thisId':$thisId,'thisAction':'delete'},
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
                });
            }
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('submit', 'form.form_save_new_label', save_new_label );

        function save_new_label(e){
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

            return false;
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
                            var print_address_id = form.find('input[name="print_address_id"]').val();
                            var product_title = form.find('input[name="orders_product_category_title"]').val();
                            var shipping_resi = form.find('input[name="orders_shipping_resi"]').val();
                            var price_product = form.find('input[name="orders_price_product"]').val();
                            var price_shipping = form.find('input[name="orders_price_shipping"]').val();
                            var price_insurance = form.find('input[name="orders_price_insurance"]').val();
                            var shipping_weight = form.find('input[name="orders_shipping_weight"]').val();

                            if(product_title != ""){
                                $('#form1 table tr[data-id='+print_address_id+'] .product_title').html(""+product_title+"");
                            } else {
                                $('#form1 table tr[data-id='+print_address_id+'] .product_title').html("");
                            }
                            if(shipping_resi != ""){
                                $('#form1 table tr[data-id='+print_address_id+'] .shipping_resi').html(shipping_resi);
                            } else {
                                $('#form1 table tr[data-id='+print_address_id+'] .shipping_resi').html("");
                            }
                            if(price_product > 0){
                                $('#form1 table tr[data-id='+print_address_id+'] .price_product').html("Harga: "+convertRp(price_product));
                            } else {
                                $('#form1 table tr[data-id='+print_address_id+'] .price_product').html("");
                            }
                            if(price_shipping > 0){
                                $('#form1 table tr[data-id='+print_address_id+'] .price_shipping').html("Ongkir: "+convertRp(price_shipping));
                            } else {
                                $('#form1 table tr[data-id='+print_address_id+'] .price_shipping').html("");
                            }
                            if(price_insurance > 0){
                                $('#form1 table tr[data-id='+print_address_id+'] .price_insurance').html("Asuransi: "+convertRp(price_insurance));
                            } else {
                                $('#form1 table tr[data-id='+print_address_id+'] .price_insurance').html("");
                            }
                            if(shipping_weight > 1){
                                $('#form1 table tr[data-id='+print_address_id+'] .shipping_weight').html("Berat: "+(shipping_weight));
                            } else {
                                $('#form1 table tr[data-id='+print_address_id+'] .shipping_weight').html("");
                            }

                            $('.popup-print-address .popup-close').trigger('click');
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
        }

    });
</script>