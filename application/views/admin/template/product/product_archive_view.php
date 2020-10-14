<div class="the-box no-border">

    <div class="row mb-20">
        <div class="col-md-4 col-sm-6">

            <?php $get_image_detail = get_image_detail_archive($val->product_id); ?>
            <div id="imagesync1" class="owl-carousel magnific-popup-wrap">
                <?php if(count($get_image_detail)>0){ ?>
                    <?php foreach ($get_image_detail as $key) { ?>
                    <div class="item full"><a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".$key->image_filename);?>"><img src="<?php echo get_image(base_url()."assets/collections/product/small/".$key->image_filename);?>" class="mfp-fade" alt="Image"></a></div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="item full"><img src="<?php echo get_image(base_url()."assets/collections/product/small/");?>" alt="Image"></div>
                <?php } ?>
            </div>
            <?php if(count($get_image_detail)>0){ ?>
            <div id="imagesync2" class="owl-carousel">
                <?php foreach ($get_image_detail as $key) { ?>
                <div class="item"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".$key->image_filename);?>" alt="Image"></div>
                <?php } ?>
            </div>
            <?php } ?>

            <?php js_owl_carousel() ?>
            <script type="text/javascript">
                $(document).ready(function() {

                    var imagesync1 = $("#imagesync1");
                    var imagesync2 = $("#imagesync2");

                    imagesync1.owlCarousel({
                        singleItem : true,
                        slideSpeed : 1000,
                        navigation: false,
                        pagination:false,
                        afterAction : syncPosition,
                        lazyLoad : true,
                        responsiveRefreshRate : 200
                    });

                    imagesync2.owlCarousel({
                        items : 5,
                        itemsDesktop      : [1199,5],
                        itemsDesktopSmall : [979,4],
                        itemsTablet       : [768,3],
                        itemsMobile       : [479,2],
                        pagination:false,
                        responsiveRefreshRate : 100,
                        afterInit : function(el){
                            el.find(".owl-item").eq(0).addClass("synced");
                        }
                    });

                    function syncPosition(el){
                        var current = this.currentItem;
                        $("#imagesync2")
                        .find(".owl-item")
                        .removeClass("synced")
                        .eq(current)
                        .addClass("synced")
                        if($("#imagesync2").data("owlCarousel") !== undefined){
                            center(current)
                        }
                    }
                    if ($('#imagesync2').length > 0){
                        $("#imagesync2").on("click", ".owl-item", function(e){
                            e.preventDefault();
                            var number = $(this).data("owlItem");
                            imagesync1.trigger("owl.goTo",number);
                        });
                    }
                    function center(number){
                        var imagesync2visible = imagesync2.data("owlCarousel").owl.visibleItems;
                        var num = number;
                        var found = false;
                        for(var i in imagesync2visible){
                            if(num === imagesync2visible[i]){
                                var found = true;
                            }
                        }

                        if(found===false){
                            if(num>imagesync2visible[imagesync2visible.length-1]){
                                imagesync2.trigger("owl.goTo", num - imagesync2visible.length+2)
                            }else{
                                if(num - 1 === -1){
                                    num = 0;
                                }
                                imagesync2.trigger("owl.goTo", num);
                            }
                        } else if(num === imagesync2visible[imagesync2visible.length-1]){
                            imagesync2.trigger("owl.goTo", imagesync2visible[1])
                        } else if(num === imagesync2visible[0]){
                            imagesync2.trigger("owl.goTo", num-1)
                        }

                    }
                });

            </script>
        </div>

        <div class="col-md-8 col-sm-6">
            <h2 class="medium-heading text-danger mt-0 mb-10"><?php echo $val->product_name.' - '.$val->product_code; ?></h2>

            <div class="table-responsive">
                <table class="table table-th-block">
                    <colgroup>
                        <col>
                        <col>
                    </colgroup>
                    <tbody>
                        <tr>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-eye"></i> Lihat</div>
                                    <div class="col-sm-6">: <?php echo ($val->product_view==''?'0':$val->product_view); ?></div>
                                </div>
                            </td>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-briefcase"></i> Berat</div>
                                    <div class="col-sm-6">: <?php echo ($detail->product_weight==''?'0':$detail->product_weight); ?> gram</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-shopping-cart"></i> Terjual</div>
                                    <div class="col-sm-6">: <?php echo ($val->product_sold==''?'0':$val->product_sold); ?></div>
                                </div>
                            </td>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-thumbs-up"></i> Likes</div>
                                    <div class="col-sm-6">: <?php echo ($val->product_like==''?'0':$val->product_like); ?></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-heart"></i> Favorite</div>
                                    <div class="col-sm-6">: <?php echo ($val->product_wishlist==''?'0':$val->product_wishlist); ?></div>
                                </div>
                            </td>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-shopping-cart"></i> Pemesanan Min.</div>
                                    <div class="col-sm-6">: <?php echo ($detail->product_min_order==''?'1':$detail->product_min_order); ?></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-clock-o"></i> Last Update</div>
                                    <div class="col-sm-6">: <?php echo timeAgo($val->product_date_update) ?></div>
                                </div>
                            </td>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-clock-o"></i> Last Push</div>
                                    <div class="col-sm-6">: <?php echo timeAgo($val->product_date_push) ?></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <div class="row">
                                    <div class="col-sm-6"><i class="fa fa-user"></i> Dibuat oleh</div>
                                    <div class="col-sm-6">: <?php echo get_user_name($val->product_user_id); ?></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="panel-group" id="accordion-price">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-uppercase">
                            <a class="block-collapse" data-parent="#accordion-price" data-toggle="collapse" href="#accordion-price-child-1">
                                Rincian Harga
                                <span class="right-content">
                                    <span class="right-icon">
                                        <i class="glyphicon glyphicon-minus icon-collapse"></i>
                                    </span>
                                </span>
                            </a>
                        </h3>
                    </div>
                    <div id="accordion-price-child-1" class="collapse in">
                        <div class="panel-body">
                            <div class="form-horizontal">
                                <div class="form-group mb-0">
                                    <label class="col-sm-3 control-label text-left">Harga Beli</label>
                                    <div class="col-sm-9">
                                        <div class="form-control"><?php echo isset($detail->product_price_buy)?convertRP($detail->product_price_buy):""?></div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="col-sm-3 control-label text-left">Harga Jual</label>
                                    <div class="col-sm-9">
                                        <div class="form-control"><?php echo isset($detail->product_price_sale)?convertRP($detail->product_price_sale):""?></div>
                                    </div>
                                </div>
                                <?php if(isset($detail->product_price_discount) && $detail->product_price_discount != '0'){ ?>
                                <div class="form-group mb-0">
                                    <label class="col-sm-3 control-label text-left">Harga Diskon</label>
                                    <div class="col-sm-9">
                                        <div class="form-control"><?php echo isset($detail->product_price_discount)?convertRP($detail->product_price_discount):""?></div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if(isset($detail->product_price_grosir) && $detail->product_price_grosir != ''){ ?>
                                <div class="form-group mb-0">
                                    <label class="col-sm-3 control-label text-left">Harga Grosir</label>
                                    <div class="col-sm-9">
                                        <div class="form-control">
                                            <div class="table-responsive">
                                                <table class="table table-th-block ">
                                                    <colgroup>
                                                        <col>
                                                        <col>
                                                    </colgroup>
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="130">Min Qty</th>
                                                            <th>Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $product_price_grosir = json_decode($detail->product_price_grosir);
                                                        foreach ($product_price_grosir as $key => $value) { ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <?php echo $value->name ?> barang
                                                            </td>
                                                            <td>
                                                                <?php echo convertRP($value->price) ?>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-group" id="accordion-stok">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">
                    <a class="block-collapse" data-parent="#accordion-stok" data-toggle="collapse" href="#accordion-stok-child-1">
                        Rincian Stok
                        <span class="right-content">
                            <span class="right-icon">
                                <i class="glyphicon glyphicon-minus icon-collapse"></i>
                            </span>
                        </span>
                    </a>
                </h3>
            </div>
            <div id="accordion-stok-child-1" class="collapse in">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Status Stok</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($detail->product_status_id)?get_name_product_status($detail->product_status_id):'';?></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Stok Awal</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($detail->product_stock_first)?$detail->product_stock_first:'';?> item</div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Stok Saat Ini</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($detail->product_stock)?$detail->product_stock:'';?> item</div>
                            </div>
                        </div>
                        <?php if($detail->product_stock_detail != ""){ ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Copy Variasi (Apps)</label>
                            <div class="col-sm-9 ">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="product_stock_copy" value="1" <?php echo ($detail->product_stock_copy==1?"checked":"") ?> disabled>
                                        Copy berdasarkan id varian
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="product_stock_copy" value="2" <?php echo ($detail->product_stock_copy==2?"checked":"") ?> disabled>
                                        Copy berdasarkan nama varian
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-th-block ">
                                <colgroup>
                                    <col width="1">
                                    <col>
                                    <col>
                                    <col>
                                    <col width="1">
                                    <col width="1">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="nobr text-center">ID</th>
                                        <th>Nama</th>
                                        <th>Barcode</th>
                                        <th>Warna</th>
                                        <th class="nobr text-center">Status</th>
                                        <th class="nobr text-center">QTY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stok_detail_qty = 0;
                                    $product_stock_detail = json_decode($detail->product_stock_detail);
                                    foreach ($product_stock_detail as $key => $value) { ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo $value->id ?>
                                        </td>
                                        <td>
                                            <?php echo $value->name ?>
                                        </td>
                                        <td>
                                            <?php echo create_product_barcode($val->product_code,$value->id);  ?>
                                        </td>
                                        <td style="background-color: #<?php echo $value->color ?>;">
                                            <?php echo strtoupper($value->color) ?>
                                        </td>
                                        <td class="nobr text-center">
                                            <?php echo ($value->status==1?'Ready':'Terjual') ?>
                                        </td>
                                        <td class="nobr text-center">
                                            <?php echo $value->qty ?>
                                            <?php $stok_detail_qty = ($stok_detail_qty + $value->qty); ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="nobr text-right">Total : <?php echo $stok_detail_qty ?> item dari total stok <?php echo $detail->product_stock ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-group" id="accordion-spek">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">
                    <a class="block-collapse" data-parent="#accordion-spek" data-toggle="collapse" href="#accordion-spek-child-1">
                        Spesifikasi Produk
                        <span class="right-content">
                            <span class="right-icon">
                                <i class="glyphicon glyphicon-minus icon-collapse"></i>
                            </span>
                        </span>
                    </a>
                </h3>
            </div>
            <div id="accordion-spek-child-1" class="collapse in">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Kode</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($val->product_code)&&$val->product_code!=''?$val->product_code:'-';?></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($val->product_name)&&$val->product_name!=''?$val->product_name:'-';?></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Nama Singkat</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($val->product_name_simple)&&$val->product_name_simple!=''?$val->product_name_simple:'-';?></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Nama Motif</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($val->product_type_motif)&&$val->product_type_motif!=''?$val->product_type_motif:'-';?></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Kategori</label>
                            <div class="col-sm-9">
                                <?php $parentId = isset($val)?$val->product_category_id:''; ?>
                                <div class="form-control"><?php echo get_root_product_category_parent($parentId); ?></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Brand</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($val->product_brand_id)?get_product_brand($val->product_brand_id)->product_brand_name:'-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Supplier</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($val->supplier_id)?get_supplier($val->supplier_id)->supplier_name:'-'; ?></div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Tagged</label>
                            <div class="col-sm-9">
                                <div class="form-control">
                                    <?php
                                    $product_tags = isset($val->product_tags)?$val->product_tags:'';
                                    if($product_tags != ''){
                                        $expTags = explode(',', $product_tags);
                                        foreach ($expTags as $n) {
                                            $detail_product_tags = get_detail_product_tags($n);
                                            echo '<span class="label label-info">'.$detail_product_tags->product_tags_name.'</span> ';

                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Awards</label>
                            <div class="col-sm-9">
                                <div class="form-control">
                                    <?php
                                    $chk_awards_product = chk_awards_product($val->product_awards, 1);
                                    if($chk_awards_product){ ?>
                                    <div class="label label-info" style="margin-right:5px;"><?php echo get_product_awards(1)->product_awards_name ?></div>
                                    <?php } ?>
                                    <?php
                                    $chk_awards_product = chk_awards_product($val->product_awards, 2);
                                    if($chk_awards_product){ ?>
                                    <div class="label label-info" style="margin-right:5px;"><?php echo get_product_awards(2)->product_awards_name ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Size</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($detail->product_size)&&$detail->product_size!=''?$detail->product_size:'-'; ?></div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="col-sm-3 control-label text-left">Publish</label>
                            <div class="col-sm-9">
                                <div class="form-control">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="product_show_id" class="onoffswitch-checkbox" id="product_show_id" <?php echo isset($val->product_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->product_id."'".')"':''?> value="1" <?php echo (isset($val->product_show_id) && $val->product_show_id=="0")?'':'checked';?> >
                                        <label class="onoffswitch-label" for="product_show_id">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-group" id="accordion-desc">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">
                    <a class="block-collapse" data-parent="#accordion-desc" data-toggle="collapse" href="#accordion-desc-child-1">
                        Description
                        <span class="right-content">
                            <span class="right-icon">
                                <i class="glyphicon glyphicon-minus icon-collapse"></i>
                            </span>
                        </span>
                    </a>
                </h3>
            </div>
            <div id="accordion-desc-child-1" class="collapse in">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-left">Ringkasan</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($description->product_lead)?$description->product_lead:'';?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-left">Deskripsi</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($description->product_description)?$description->product_description:'';?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-left">Meta Keyword</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($description->product_meta_keyword)?$description->product_meta_keyword:""?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label text-left">Meta Description</label>
                            <div class="col-sm-9">
                                <div class="form-control"><?php echo isset($description->product_meta_description)?$description->product_meta_description:""?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group form-action clearfix">
        <div class="col-sm-9 col-sm-offset-3">
            <?php if($this->input->get("next")!=""){ ?>
            <a href="<?php echo $this->input->get("next");?>"><div class="btn btn-default pull-right">Back</div></a>
            <?php } else { ?>
            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
            <?php } ?>
        </div>
    </div>

</div>

<div class="popup popup-product">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
    </div>
</div>

