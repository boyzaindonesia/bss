<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

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
                                    <?php $notif_status = $this->jCfg['search']['notif_status']; ?>
                                    <select name="notif_status" class="form-control" onchange="this.form.submit()">
                                        <option value="1" <?php echo ($notif_status==1?'selected':'') ?> >Butuh Tindakan</option>
                                        <option value="2" <?php echo ($notif_status==2?'selected':'') ?> >Sudah Ditindak</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>User</label>
                                <div class="btn-group">
                                    <?php $user_id = $this->jCfg['search']['user_id']; ?>
                                    <select name="user_id" class="form-control" onchange="this.form.submit()">
                                        <option value="0" selected>--- Pilih ---</option>
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
                                        <option value="mt_product_notif.notif_date-desc" <?php echo ($order_by=='mt_product_notif.notif_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="mt_product_notif.notif_date-asc" <?php echo ($order_by=='mt_product_notif.notif_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="mt_product.product_date-desc" <?php echo ($order_by=='mt_product.product_date'&&$order_dir=='desc'?'selected':'') ?> >Date Upload: New to Old</option>
                                        <option value="mt_product.product_date-asc" <?php echo ($order_by=='mt_product.product_date'&&$order_dir=='asc'?'selected':'') ?> >Date Upload: Old to New</option>
                                        <option value="mt_product_notif.notif_title-asc" <?php echo ($order_by=='mt_product_notif.notif_title'&&$order_dir=='asc'?'selected':'') ?> >Judul: A to Z</option>
                                        <option value="mt_product_notif.notif_title-desc" <?php echo ($order_by=='mt_product_notif.notif_title'&&$order_dir=='desc'?'selected':'') ?> >Judul: Z to A</option>
                                        <option value="mt_product.product_name-asc" <?php echo ($order_by=='mt_product.product_name'&&$order_dir=='asc'?'selected':'') ?> >Produk: A to Z</option>
                                        <option value="mt_product.product_name-desc" <?php echo ($order_by=='mt_product.product_name'&&$order_dir=='desc'?'selected':'') ?> >Produk: Z to A</option>
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
            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                <label>Search:</label>
                <div class="btn-group">
                    <input type="search" data-reset="true" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
                    <input type="hidden" name="searchAction" value="search">
                </div>
            </form>
        </div>
    </div>

    <?php if($notif_status == 1){ ?>
    <div class="btn-toolbar">
        <div class="btn-group">
            <div class="btn btn-info btn-tindakan"><i class="fa fa-plus"></i> Sudah Ditindak ( 0 )</div>
        </div>
    </div>
    <?php } ?>

    <form id="form1" action="<?php echo $own_links.'/save_multi_notif_status' ?>" method="post" enctype="multipart/form-data">
    <div class="relative no-border mt-20">
        <div class="checkbox">
            <label><input type="checkbox" name="checkallfiles" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"> Check All</label>
        </div>

        <div class="product-list mb-0">
            <?php
            $timestamp = timestamp();
            // debugCode($data);
            if(count($data) > 0){
                $i = 1;
                $pageNumber = $this->uri->segment(4);
                if(!empty($pageNumber)){
                    $i = $pageNumber + 1;
                }
                foreach($data as $r){
            ?>
            <div class="product-list-item with-checkbox magnific-popup-wrap" data-id="<?php echo $r->notif_id ?>">
                <div class="checkbox">
                    <label><input type="checkbox" name="checked_files[]" value="<?php echo $r->notif_id;?>" title="<?php echo $r->notif_id;?>"> &nbsp;</label>
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
                    <p class="product-list-title no-margin no-padding mb-5"><strong><?php echo $r->product_name.' - '.$r->product_code;?></strong></p>
                    <h6 class="no-margin no-padding mb-5">Harga Beli <span style=""><?php echo convertRp($r->product_price_buy);?></span></h6>
                    <h6 class="no-margin no-padding mb-5">Harga Jual <span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?></h6>

                    <div class="product-list-action">
                        <?php if(check_action($links_table_item,'delete')){ ?>
                        <!-- <div class="btn-group pull-right mr-5">
                            <a href="<?php echo $own_links.'/delete/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>" onclick="return confirm('Are you sure delete ?');" class="btn btn-danger btn-xs">Hapus Notif</a>
                        </div> -->
                        <?php } ?>
                        <?php if(check_action($links_table_item,'view')){ ?>
                        <div class="btn-group pull-right mr-5">
                            <a href="<?php echo base_url().'/admin/product/edit/'.$r->product_id.'-'.changeEnUrl($r->product_name).'?next='.$own_links;?>" class="btn btn-info btn-xs">Edit</a>
                        </div>
                        <div class="btn-group pull-right mr-5">
                            <a href="<?php echo $own_links.'/view/'.$r->notif_id.'-'.$r->product_id.'-'.changeEnUrl($r->product_name);?>" class="btn btn-primary btn-xs">Lihat</a>
                        </div>
                        <?php } ?>

                        <?php
                        if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){
                            $varian_text = $r->product_name_simple." update ready stok terakhir tanggal: ".convDateTable($timestamp)."<br>";
                            foreach (json_decode($r->product_stock_detail) as $key3 => $val3) {
                                if($r->product_stock_copy == 1){
                                    $varian_text .= "- No ".ltrim($val3->id,"0")." ".($val3->qty > 0?"":"(HABIS)")."<br>";
                                } else if($r->product_stock_copy == 2){
                                    $varian_text .= "- ".$val3->name." ".($val3->qty > 0?"":"(HABIS)")."<br>";
                                }
                            }
                            ?>
                            <div class="btn-group pull-right mr-5">
                                <a href="javascript:void(0);" class="btn btn-success btn-xs btn-copy-clipboard" data-text-clipboard="<?php echo $varian_text ?>"><i class="fa fa-clone"></i> Copy Last Stock</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="relative">
                    <p class="no-margin no-padding">Stok: <?php echo $r->product_stock;?> pcs</p>
                    <?php if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){
                        $foundSold = false;
                        ?>
                    <ul class="product-list-item-stock clearfix mb-5">
                        <?php
                        foreach (json_decode($r->product_stock_detail) as $key => $value) {
                            if($value->status==1){ ?>
                            <li><p><?php echo $value->name ?> = <?php echo $value->qty ?></p></li>
                        <?php } else { $foundSold = true; } ?>
                        <?php } ?>
                    </ul>
                    <?php if($r->product_status_id==1 && $foundSold){ ?>
                    <p class="no-margin no-padding text-danger">Variasi Sold:</p>
                    <ul class="product-list-item-stock clearfix mb-0">
                        <?php
                        foreach (json_decode($r->product_stock_detail) as $key => $value) {
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
        $('.btn-tindakan').html('<i class="fa fa-plus"></i> Sudah Ditindak ( '+checked_files.length+' )');
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
        $(document).on('click', '.btn-tindakan', function(e){
            e.preventDefault();
            var form1 = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau ditindak.",
                    type: "error"
                });
            } else {
                form1.submit();

                // var thisId  = "";
                // var thisVal = "";
                // checked_files.each(function() {
                //     thisVal = $(this).val();
                //     thisId += (thisId=="" ? thisVal : "-" + thisVal);
                // });

                // var url   = $(this).attr('data-url');
                // window.open(url+'?id='+thisId, '_blank');
            }
        });


    });
</script>
