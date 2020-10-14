<script src="<?php echo base_url()?>assets/plugins/playsound/playSound.js"></script>
<script type="text/javascript">
    function playSuccess(){ $.playSound('<?php echo base_url()?>assets/collections/audio/success.wav'); }
    function playError(){ $.playSound('<?php echo base_url()?>assets/collections/audio/error.wav'); }
</script>

<?php js_mask() ?>

<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>

        <div class="btn-group pull-right">
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-pencil"></i> Edit <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0);" class="btn-popup-product-group" data-url="<?php echo base_url().'admin/product/view_popup_product_group' ?>">Group Produk</a></li>
                <li><a href="javascript:void(0);" class="btn-edit-multiple" data-url="<?php echo base_url().'admin/product/edit_sku' ?>">SKU Marketplace</a></li>
                <li><a href="javascript:void(0);" class="btn-popup-update-product-multiple">Update Harga</a></li>
            </ul>
        </div>

        <div class="btn-group pull-right">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-download"></i> Download <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo base_url().'admin/product_process/download_all_list_stock' ?>" target="_blank">All Stok Produk</a></li>
                <li><a href="<?php echo base_url().'admin/product_process/download_all_data_product' ?>" target="_blank">All Data Produk</a></li>
                <li><a href="javascript:void(0);" class="btn-download" data-url="<?php echo base_url().'admin/product_process/download_list_stock' ?>">List Stock</a></li>
                <li><a href="javascript:void(0);" class="btn-download" data-url="<?php echo base_url().'admin/product_process/download_list_price' ?>">List Harga</a></li>
            </ul>
        </div>
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-warning btn-upload">
                <i class="fa fa-upload"></i> Upload
            </button>
        </div>
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-danger btn-update-stock-barcode" data-url="<?php echo base_url().'admin/product/form_update_stock_barcode' ?>">
                <i class="fa fa-exchange"></i> Pengurangan Stok
            </button>
        </div>
    </div>

<?php
    $check_double_orders_detail = check_double_orders_detail($this->store_id);
    if(count($check_double_orders_detail) > 0 ){
        echo '<div class="alert alert-info square fade in alert-dismissable mt-10">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Info! </strong> Terdapat jumlah order detail yang double <a href="'.base_url().'admin/product_checked" target="_blank">LIHAT</a>
            </div>';
    }
?>

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
                                <label>Status</label>
                                <div class="btn-group">
                                    <?php $product_status_id = $this->jCfg['search']['product_status_id']; ?>
                                    <select name="product_status_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" <?php echo ($product_status_id==""?'selected':'') ?> >Semua Status</option>
                                        <?php
                                        $product_status = get_product_status();
                                        foreach ($product_status as $k => $v) { ?>
                                        <option value="<?php echo $v->product_status_id; ?>" <?php echo ($v->product_status_id==$product_status_id?'selected':'') ?> ><?php echo $v->product_status_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Group</label>
                                <div class="btn-group">
                                    <?php $product_group_id = $this->jCfg['search']['product_group_id']; ?>
                                    <select name="product_group_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" <?php echo ($product_group_id==""?'selected':'') ?> >--- Pilih ---</option>
                                        <option value="not_group" <?php echo ($product_group_id=="not_group"?'selected':'') ?> >Belum Ada Group</option>
                                        <?php
                                        $product_group = get_product_group();
                                        foreach ($product_group as $k => $v) { ?>
                                        <option value="<?php echo $v->product_group_id; ?>" <?php echo ($v->product_group_id==$product_group_id?'selected':'') ?> ><?php echo $v->product_group_name; ?></option>
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
                                        <option value="product_date_push-desc" <?php echo ($order_by=='product_date_push'&&$order_dir=='desc'?'selected':'') ?> >Date Push: New to Old</option>
                                        <option value="product_date_push-asc" <?php echo ($order_by=='product_date_push'&&$order_dir=='asc'?'selected':'') ?> >Date Push: Old to New</option>
                                        <option value="product_date_update-desc" <?php echo ($order_by=='product_date_update'&&$order_dir=='desc'?'selected':'') ?> >Date Update: New to Old</option>
                                        <option value="product_date_update-asc" <?php echo ($order_by=='product_date_update'&&$order_dir=='asc'?'selected':'') ?> >Date Update: Old to New</option>
                                        <option value="product_date-desc" <?php echo ($order_by=='product_date'&&$order_dir=='desc'?'selected':'') ?> >Date Created: New to Old</option>
                                        <option value="product_date-asc" <?php echo ($order_by=='product_date'&&$order_dir=='asc'?'selected':'') ?> >Date Created: Old to New</option>
                                        <option value="product_name-asc" <?php echo ($order_by=='product_name'&&$order_dir=='asc'?'selected':'') ?> >Name: A to Z</option>
                                        <option value="product_name-desc" <?php echo ($order_by=='product_name'&&$order_dir=='desc'?'selected':'') ?> >Name: Z to A</option>
                                        <option value="product_stock-desc" <?php echo ($order_by=='product_stock'&&$order_dir=='desc'?'selected':'') ?> >Stok: Terbanyak</option>
                                        <option value="product_stock-asc" <?php echo ($order_by=='product_stock'&&$order_dir=='asc'?'selected':'') ?> >Stok: Sedikit</option>
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
            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" autocomplete="off" enctype="multipart/form-data" accept-charset="UTF-8">
                <label>Search:</label>
                <div class="btn-group">
                    <input type="search" data-reset="true" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
                    <input type="hidden" name="searchAction" value="search">
                </div>
            </form>
        </div>
    </div>

    <?php
    $get_product_stock_no_sync = get_product_stock_no_sync($this->store_id);
    $found_product_stock_no_sync = $get_product_stock_no_sync['founds'];
    if($found_product_stock_no_sync){
        echo '<div class="relative no-border mt-20">';
        foreach ($get_product_stock_no_sync['item'] as $key => $val) {
        ?>
            <div>
                Id: <?php echo $val->id; ?> <?php echo $val->name; ?>, Total Stok: <?php echo $val->qty_stock; ?>, Total Varian: <?php echo $val->qty_varian; ?>
            </div>
        <?php
        }
        echo "</div>";
    }
    ?>

    <h4 class="no-margin">Total Produk: <?php echo $total_data ?></h4>
    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="relative no-border mt-20">
        <div class="checkbox">
            <label><input type="checkbox" name="checkallfiles" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"> Check All</label>
        </div>

        <div class="product-list mb-0">
            <?php
            $timestamp = timestamp();
            // debugCode($data);
            if(count($data) > 0){
                foreach($data as $r){
            ?>
            <div class="product-list-item with-checkbox magnific-popup-wrap" data-id="<?php echo $r->product_id ?>">
                <div class="checkbox">
                    <label><input type="checkbox" name="checked_files[]" value="<?php echo $r->product_id;?>" title="<?php echo $r->product_id;?>"> &nbsp;</label>
                </div>

                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/large/".get_cover_image_detail($r->product_id));?>">
                    <div class="product-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>);"></div>
                </a>
                <div class="hide">
                    <?php
                    $get_image_detail = get_image_detail($r->product_id);
                    $ii = 0;
                    foreach ($get_image_detail as $key) {
                        if($ii > 0){
                        ?>
                    <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/large/".$key->image_filename);?>">&nbsp;</a>
                    <?php
                        }
                        $ii += 1;
                    } ?>
                </div>

                <div class="product-list-content">
                    <p class="product-list-title no-margin no-padding <?php echo ($r->product_group_id != 0?'':'mb-5') ?>"><strong><a href="<?php echo base_url().'admin/product/view/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>"><?php echo $r->product_name ?></a> - <?php echo $r->product_code;?></strong></p>
                    <?php if($r->product_group_id != 0){ ?>
                    <p class="no-margin no-padding mb-5">[Group: <?php echo get_product_group_name($r->product_group_id) ?>]</p>
                    <?php } ?>
                    <h6 class="no-margin no-padding mb-5">Harga Beli <span style=""><?php echo convertRp($r->product_price_buy);?></span></h6>
                    <h6 class="no-margin no-padding mb-5">Harga Jual <span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?></h6>

                    <?php if(isset($r->product_price_grosir) && $r->product_price_grosir != ''){ ?>
                    <table border="0" style="font-size: 10px; line-height: 14px; margin-bottom: 5px;">
                        <colgroup>
                            <col>
                            <col>
                        </colgroup>
                        <tbody>
                            <?php
                            foreach ($r->product_price_grosir as $key => $value) { ?>
                            <tr>
                                <td style="padding: 0px; padding-right: 10px;">
                                    <?php echo $value->name ?> barang
                                </td>
                                <td style="padding: 0px;">
                                    <?php echo convertRP($value->price) ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                    <div class="product-list-sku">
                        <?php $mp = get_product_mp($r->product_id); ?>
                        <div class="">Tokopedia: <?php echo $mp->product_mp_tokped; ?></div>
                        <div class="">Bukalapak: <?php echo $mp->product_mp_bulap; ?></div>
                        <div class="">Shopee: <?php echo $mp->product_mp_shopee; ?></div>
                        <div class="">Lazada: <?php echo $mp->product_mp_lazada; ?></div>
                    </div>
                    <div class="product-list-action">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-cog"></i> Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <?php
                                if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){
                                    $varian_text = $r->product_name_simple." update ready stok terakhir tanggal: ".convDateTable($timestamp)."<br>";
                                    foreach ($r->product_stock_detail as $key3 => $val3) {
                                        if($r->product_stock_copy == 1){
                                            $varian_text .= "- No ".ltrim($val3->id,"0")." ".($val3->qty > 0?"":"(HABIS)")."<br>";
                                        } else if($r->product_stock_copy == 2){
                                            $varian_text .= "- ".$val3->name." ".($val3->qty > 0?"":"(HABIS)")."<br>";
                                        }
                                    }
                                    ?>
                                    <li><a href="javascript:void(0);" class="btn-copy-clipboard" data-text-clipboard="<?php echo $varian_text ?>">Copy Last Stock</a></li>
                                <?php } ?>

                                <li><a href="javascript:void(0);" class="btn-push" data-id="<?php echo $r->product_id ?>">Push</a></li>
                                <?php if(check_action($links_table_item,'view')){ ?>
                                <li><a href="<?php echo base_url().'admin/product/view/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>">Lihat</a></li>
                                <?php } ?>
                                <?php if(check_action($links_table_item,'edit')){ ?>
                                <li><a href="<?php echo base_url().'admin/product/edit/'.$r->product_id.'-'.changeEnUrl($r->product_name).'?next='.$own_links;?>">Edit</a></li>
                                <?php } ?>
                                <?php if(check_action($links_table_item,'delete')){ ?>
                                <li><a href="<?php echo base_url().'admin/product/delete/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>"onclick="return confirm('Ingin dihapus ?');" >Hapus</a></li>
                                <?php } ?>
                                <li class="divider"></li>
                                <li><a href="javascript:void(0);" class="btn-popup-product-stock" data-id="<?php echo $r->product_id ?>">Update Stok</a></li>
                                <li><a href="javascript:void(0);" class="btn-popup-product-add-stock" data-id="<?php echo $r->product_id ?>">Tambah Stok</a></li>
                                <?php if($r->product_status_id == 1){ ?>
                                <li><a href="<?php echo base_url().'admin/product/setnotsale/'.$r->product_id.'-'.changeEnUrl($r->product_name).'?sendnotif=1&next='.$own_links;?>" onclick="return confirm('Are you sure set not sale ?');">SoldOut</a></li>
                                <?php } ?>
                                <li><a href="<?php echo base_url().'admin/product/duplicate/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>">Duplicate</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <p class="no-margin no-padding">Stok: <?php echo $r->product_stock;?> pcs (<?php echo $r->product_status_name ?>) <?php echo ($r->product_show_id!=1?"(".$r->product_show_name.")":"") ?></p>
                    <?php if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){
                        $foundSold = false;
                        ?>
                    <ul class="product-list-item-stock clearfix mb-5">
                        <?php
                        foreach ($r->product_stock_detail as $key => $value) {
                            if($value->status==1){ ?>
                            <li><p><?php echo $value->name ?> = <?php echo $value->qty ?></p></li>
                        <?php } else { $foundSold = true; } ?>
                        <?php } ?>
                    </ul>
                    <?php if($r->product_status_id==1 && $foundSold){ ?>
                    <p class="no-margin no-padding text-danger">Variasi Sold:</p>
                    <ul class="product-list-item-stock clearfix mb-0">
                        <?php
                        foreach ($r->product_stock_detail as $key => $value) {
                            if($value->status==2){ ?>
                            <li><p class="text-danger"><?php echo $value->name ?> = <?php echo $value->qty ?></p></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <?php
                }
            } else {
                echo 'Produk tidak ditemukan...';
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

<script type="text/javascript">
    function check_all_files(e){
        var checkbox = $('input[name="checked_files[]"]');
        if($(e).is(':checked')){
            checkbox.prop('checked', true);
            checkbox.parents('.with-checkbox').addClass('checked');
        } else {
            checkbox.prop('checked', false);
            checkbox.parents('.with-checkbox').removeClass('checked');
        }
        calc_check_files();
    }

    calc_check_files();
    function calc_check_files(){
        var form1 = $('form#form1');
        var checked_files = form1.find('input[name="checked_files[]"]:checked');
    }

    $(document).ready(function() {
        $(document).on('change', 'input[name="checked_files[]"]', function(e){
            e.preventDefault();
            if($(this).is(':checked')){
                $(this).parents('.with-checkbox').addClass('checked');
            } else {
                $(this).parents('.with-checkbox').removeClass('checked');
            }
            calc_check_files();
        });

        $(document).on('click', '.btn-edit-multiple', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau diupdate.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                // window.open(dataUrl+'?id='+thisId+'', '_blank');
                window.location.href = dataUrl+'?id='+thisId+'';
            }
        });

        $(document).on('click', '.btn-download', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau didownload.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                window.open(dataUrl+'?id='+thisId+'', '_blank');
            }
        });

        $(document).on('click', '.btn-upload', function(e){
            e.preventDefault();
            $('html, body').css('overflow','hidden');
            $('.popup-upload').addClass('active');
            $('#formUpload')[0].reset();
        });

        $(document).on('click', '.btn-update-stock-barcode', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
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
                    data: {'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-product').addClass('active');
                        $('.popup-product .popup-content').html(data.content);
                        // console.log(data.content);

                        setTimeout(function(){
                            document.getElementById("product_barcode").focus();
                        }, 500);

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
            }, 500);
        });

        $(document).on('blur', '#form_save_update_stock_barcode #product_barcode', function(e){
            e.preventDefault();
            var $this   = $(this);
            setTimeout(function(){
                if($this.val() != "" && !$this.parents('form').hasClass("loading")){
                    $this.parents('form').find('button[type="submit"]').trigger('click');
                }
            }, 500);
        });

        $(document).on('submit', 'form.form_save_update_stock_barcode', save_update_stock_barcode );
        function save_update_stock_barcode(e){
            if (typeof e !== 'undefined') e.preventDefault();
            var $this = $(this);
            var form = $this;

            form.addClass("loading");

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
                                console.log(data.result_last_update);

                        if(data.err == false){
                            playSuccess();
                            swal({
                                title: "Success!",
                                text: data.msg,
                                type: "success",
                                showConfirmButton: false,
                                timer: 1000
                            }).then(
                            function () {},
                            function (dismiss) {
                                // console.log('close');
                                // if (dismiss === 'timer') {
                                //     console.log('I was closed by the timer')
                                // }
                                var li_item = "";
                                $(data.result_last_update).each(function(i, item){
                                    li_item += "<li>"+item+"</li>";
                                });
                                $('#result_update_stock_barcode').html(li_item);
                                $("#form_save_update_stock_barcode")[0].reset();
                                setTimeout(function(){
                                    form.removeClass("loading");
                                    document.getElementById("product_barcode").focus();
                                }, 500);
                            });
                        } else {
                            playError();
                            swal({
                                title: "Error!",
                                text: data.msg,
                                type: "error"
                            }).then(function () {
                                setTimeout(function(){
                                    $("#form_save_update_stock_barcode")[0].reset();
                                    form.removeClass("loading");
                                }, 500);
                            });
                        }
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        playError();
                        swal({
                            title: "Error!",
                            text: response,
                            type: "error"
                        }).then(function () {
                            setTimeout(function(){
                                $("#form_save_update_stock_barcode")[0].reset();
                                form.removeClass("loading");
                            }, 500);
                        });
                    }
                });

                return false;
            }, 300);
        }

        $(document).on('click', '.btn-push', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });
            setTimeout(function(){
                if($thisVal != ''){
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $own_links ?>/ajax_push',
                        data: {'thisVal':$thisVal,'thisAction':'save'},
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
                                    timer: 500
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
                }
            }, 500);
        });

        // $(document).on('submit', 'form#formUpload', function(e){
        //     e.preventDefault();
        //     var $this    = $(this);
        //     swal({
        //         title: "Loading!",
        //         text: "",
        //         type: "loading",
        //         showConfirmButton: false,
        //         allowOutsideClick: false,
        //         customClass: 'swal2-small'
        //     });

        //     return true;
        // });

        <?php if(isset($_GET['download']) && $_GET['download'] != ""){ ?>
            window.open("<?php echo $_GET['download'] ?>", '_blank');
        <?php } ?>

    });
</script>

<div class="popup popup-upload">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="false"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">
            <div class="popup-content">

                <form id="formUpload" action="<?php echo base_url() ?>admin/product_process/save_upload_product" method="post" autocomplete="off" enctype="multipart/form-data">
                    <legend>Upload</legend>
                    <div class="row popup-content-product mb-20">
                        <div class="col-sm-4" style="padding-right: 5px;">
                            <select name="type_name" class="form-control" required>
                                <option value="" selected>--- Pilih ---</option>
                                <option value="insert_sku_shopee">Insert SKU Shopee</option>
                                <option value="insert_sku_tokopedia">Insert SKU Tokopedia</option>
                                <option value="insert_sku_bukalapak">Insert SKU Bukalapak</option>
                                <!-- <option value="update_stock_website">Update Stok Website</option> -->
                                <option value="update_stock_shopee">Update Stok Shopee</option>
                                <option value="update_stock_tokopedia">Update Stok Tokopedia</option>
                                <option value="update_stock_lazada">Update Stok Lazada</option>
                                <option value="update_price">Update Harga</option>
                            </select>
                        </div>
                        <div class="col-sm-8" style="padding-left: 0px; padding-right: 5px;">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="files" required />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div>Stok Belum Dibayar (optional)</div>
                    <div class="row popup-content-product mb-20">
                        <div class="col-sm-8" style="padding-right: 5px;">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="files_temp" />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <input type="hidden" name="thisAction" value="upload" />
                        <input type="hidden" name="thisUrl" value="<?php echo $own_links ?>" />
                        <button type="submit" name="save_update" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-default popup-close" data-remove-content="false">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="popup popup-product">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">
            <div class="popup-content">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click','input[type="text"]',function(){ this.select(); });

        $(document).on('click', '.btn-popup-product-stock', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            if($thisVal != ''){
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $own_links ?>/view_stock_detail',
                    data: {'thisVal':$thisVal,'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-product').addClass('active');
                        $('.popup-product .popup-content').html(data.content);
                        console.log(data.result);

                        $('.magnific-popup-wrap').magnificPopup({
                            delegate: 'a.zooming',
                            type: 'image',
                            removalDelay: 300,
                            mainClass: 'mfp-fade',
                            gallery: {
                              enabled:false
                            }
                        });

                        $(document).on('click','input[type="text"]',function(){ this.select(); });
                        $(document).on('click','input[type="number"]',function(){ this.select(); });
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

        $(document).on('click', '.btn-popup-product-add-stock', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            if($thisVal != ''){
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $own_links ?>/view_add_stock_detail',
                    data: {'thisVal':$thisVal,'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-product').addClass('active');
                        $('.popup-product .popup-content').html(data.content);
                        console.log(data.result);

                        $('.magnific-popup-wrap').magnificPopup({
                            delegate: 'a.zooming',
                            type: 'image',
                            removalDelay: 300,
                            mainClass: 'mfp-fade',
                            gallery: {
                              enabled:false
                            }
                        });

                        $(document).on('click','input[type="text"]',function(){ this.select(); });
                        $(document).on('click','input[type="number"]',function(){ this.select(); });
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

        $(document).on('click', '.btn-popup-product-group', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });

            setTimeout(function(){
                var checked_files = form1.find('input[name="checked_files[]"]:checked');
                if(checked_files.length == 0){
                    swal({
                        title: "Error!",
                        text: "Ceklis yang mau diupdate.",
                        type: "error"
                    });
                } else {
                    var thisId  = "";
                    var thisVal = "";
                    checked_files.each(function() {
                        thisVal = $(this).val();
                        thisId += (thisId=="" ? thisVal : "-" + thisVal);
                    });
                    $.ajax({
                        type: 'POST',
                        url: dataUrl,
                        data: {'thisVal':thisId,'thisAction':'view'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            $('.popup-product').addClass('active');
                            $('.popup-product .popup-content').html(data.content);
                            console.log(data.result);

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
                }
            }, 500);
        });

        $(document).on('click', '.btn-popup-update-product-multiple', function(e){
            e.preventDefault();
            var $this   = $(this);
            var form1   = $('form#form1');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });

            setTimeout(function(){
                var checked_files = form1.find('input[name="checked_files[]"]:checked');
                if(checked_files.length == 0){
                    swal({
                        title: "Error!",
                        text: "Ceklis yang mau diupdate.",
                        type: "error"
                    });
                } else {
                    var thisId  = "";
                    var thisVal = "";
                    checked_files.each(function() {
                        thisVal = $(this).val();
                        thisId += (thisId=="" ? thisVal : "-" + thisVal);
                    });
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url() ?>admin/product/form_update_price_multiple',
                        data: {'thisVal':thisId,'thisAction':'view'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            $('.popup-product').addClass('active');
                            $('.popup-product .popup-content').html(data.content);
                            console.log(data.result);

                            $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                            $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                            $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});

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
                }
            }, 500);
        });

        $(document).on('submit', 'form.form_save_product', save_product );
        function save_product(e){
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

                                if(data.url != ""){
                                    window.open(data.url, '_blank');
                                }

                                setTimeout(function(){
                                    $('.popup-product .popup-close').trigger('click');
                                    window.location.reload(true);
                                },1000);
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
            }, 500);
        }

    });

    function enabledPriceGrosir(){
        if($('input[name="chk_product_price_grosir"]').is(':checked')){
            $('.PriceGrosir').show();
        } else {
            $('.PriceGrosir').hide();
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', 'form.form_save_stock_detail', save_stock_detail );
        function save_stock_detail(e){
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

                                if(data.url != ""){
                                    window.open(data.url, '_blank');
                                }

                                setTimeout(function(){
                                    $('.popup-product .popup-close').trigger('click');
                                    window.location.reload(true);
                                },1000);
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
            }, 500);
        }

    });
</script>

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

<script type="text/javascript">
    $(document).ready(function() {
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

        $(document).on('change', 'select[name="product_status_id"]', function(e){
            e.preventDefault();
            var $this = $(this);
            var id    = $this.val();
            var item_stok = $('.result-stok-array').find('.item-stok-array');
            if(id == "1"){
                $(item_stok).each(function(i){
                    var this_val = item_stok.eq(i).find('.calc-count-qty').val();
                    if(!$.isNumeric(this_val)) { this_val = 0; }
                    var setVal = "2";
                    if(this_val > 0){ setVal = "1"; }
                    item_stok.eq(i).find('select[name="product_stock_status[]"]').val(setVal);
                });
            } else {
                $(item_stok).each(function(i){
                    var setVal = "2";
                    item_stok.eq(i).find('select[name="product_stock_status[]"]').val(setVal);
                });
            }
        });

    });

    function js_calc_item_total(){
        var item_stok = $('.result-stok-array').find('.item-stok-array');
        var countItems = 0;
        var count_item_stok = 0;
        var last_name = "";
        $(item_stok).each(function(i){
            countItems = countItems + 1;
            var this_val = item_stok.eq(i).find('.calc-count-qty').val();
            if(!$.isNumeric(this_val)) { this_val = 0; }
            count_item_stok = count_item_stok + parseInt(this_val);
            last_name = item_stok.eq(i).find('input[name="product_stock_name[]"]').val();
        });
        $('.result-stok-array').attr("data-last-name", last_name);
        $('#count-item-stok').html(count_item_stok+" pcs");
        $('.groupStock').val(count_item_stok);
        if(countItems < 1){
            $('.chkVariant').prop( "checked", false );
            $('.groupStock').removeAttr('disabled');
            $('.groupVariant').addClass('hide');
        }
    }

    js_calc_item_total();
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.btn-remove-stok-array', function(e){
            e.preventDefault();
            $(this).parents('.item-stok-array').remove();
            js_calc_item_total();
        });
        $(document).on('click', '.btn-add-stok-array', addStokArray );
    });

    function addStokArray() {
        var next_name  = $('.result-stok-array').attr("data-last-name");

        var count_item = 1 + parseInt($('.result-stok-array').find('.item-stok-array').length);
        if(count_item.toString().length == 1){ count_item = '0'+count_item; }
        var $stok_layout = '<tr class="item-stok-array">';
                $stok_layout += '<td>';
                    $stok_layout += '<input type="text" name="product_stock_id[]" value="'+count_item+'" class="form-control form-no text-uppercase text-center" maxlength="2" required>';
                $stok_layout += '</td>';
                $stok_layout += '<td>';
                    $stok_layout += '<input type="text" name="product_stock_name[]" value="'+next_name+'" class="form-control">';
                $stok_layout += '</td>';
                $stok_layout += '<td class="nobr text-center">';
                    $stok_layout += '<input type="hidden" name="product_stock_old[]" value="0">';
                    $stok_layout += '<input type="text" name="product_stock_qty[]" value="0" class="form-control calc-count-qty" maxlength="5">';
                $stok_layout += '</td>';
                $stok_layout += '<td>';
                    $stok_layout += '<select name="product_stock_status[]" class="form-control">';
                        $stok_layout += '<option value="1">Ready</option>';
                        $stok_layout += '<option value="2" selected>Terjual</option>';
                    $stok_layout += '</select>';
                $stok_layout += '</td>';
                $stok_layout += '<td class="nobr text-center">';
                    $stok_layout += '<input type="hidden" name="product_stock_color[]" value=""/>';
                    $stok_layout += '<a href="javascript:void(0);" class="btn btn-danger btn-remove-stok-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>';
                $stok_layout += '</td>';
            $stok_layout += '</tr>';

        $('.result-stok-array').append($stok_layout);
        $('.result-stok-array').attr("data-last-name", last_name);

    }
    <?php if(count($arr_product_stok) == 0){ ?>
        // addStokArray();
    <?php } ?>
</script>
