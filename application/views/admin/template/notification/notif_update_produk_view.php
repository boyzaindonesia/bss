<div class="the-box no-border">
    <div class="panel panel-dark panel-block-color">
        <div class="panel-heading">
            <h3 class="panel-title text-uppercase">INFORMASI</h3>
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tanggal</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->notif_date)?convDateTimeTable($val->notif_date):'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">User</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->user_id)?get_user_name($val->user_id):'';?></div>
                    </div>
                </div>
                <?php $product = get_detail_product($val->product_id); ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Images</label>
                    <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                        <div class="form-control">
                            <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($product->product_id));?>">
                                <img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($product->product_id));?>" alt="" class="mfp-fade item-gallery img-responsive mb-0">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($product->product_name)?$product->product_name:'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Judul</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->notif_title)?$val->notif_title:'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Stok Terakhir</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->product_qty)?$val->product_qty:'';?> pcs</div>
                    </div>
                </div>

                <?php
                switch ($val->notif_title) {
                    case 'Produk diset Tidak Dijual':
                        $layout_item = "1";
                        break;
                    case 'Variasi produk sudah habis':
                        $layout_item = "2";
                        break;

                    default: $layout_item = "1"; break;
                }
                ?>
                <?php if($val->product_item != ""){ ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Stok Detail</label>
                    <div class="col-sm-9">
                        <div class="table-responsive">
                            <?php if($layout_item == "1"){ ?>
                            <table class="table table-th-block table-dark">
                                <colgroup>
                                    <col width="1">
                                    <col>
                                    <col>
                                    <col width="1">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="nobr text-center">ID</th>
                                        <th class="">Nama</th>
                                        <th class="">Stok</th>
                                        <th class="nobr text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $product_item = json_decode($val->product_item);
                                    foreach ($product_item as $key => $value) { ?>
                                    <tr>
                                        <td class="nobr text-center"><?php echo $value->id ?></td>
                                        <td class=""><?php echo $value->name ?></td>
                                        <td class=""><?php echo $value->qty ?></td>
                                        <td class="nobr text-center"><?php echo ($value->status==1?"Ready":"Terjual") ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } else if($layout_item == "2"){ ?>
                            <table class="table table-th-block table-dark">
                                <colgroup>
                                    <col width="1">
                                    <col>
                                    <col>
                                    <col>
                                    <col width="1">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="nobr text-center">ID</th>
                                        <th class="">Nama</th>
                                        <th class="">Stok Lama</th>
                                        <th class="">Stok Skrg</th>
                                        <th class="nobr text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $product_item = json_decode($val->product_item);
                                    foreach ($product_item as $key => $value) { ?>
                                    <tr>
                                        <td class="nobr text-center"><?php echo $value->id ?></td>
                                        <td class=""><?php echo $value->name ?></td>
                                        <td class=""><?php echo $value->qty_old ?></td>
                                        <td class=""><?php echo $value->qty_new ?></td>
                                        <td class="nobr text-center"><?php echo ($value->status==1?"Ready":"Terjual") ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <div class="form-group form-action clearfix">
                    <div class="col-sm-9 col-sm-offset-3">

                        <?php if($val->notif_status == 1){ ?>
                        <form action="<?php echo $own_links;?>/save_notif_status" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="notif_id" value="<?php echo isset($val->notif_id)?$val->notif_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Sudah Ditindak" />
                        </form>
                        <?php } ?>

                        <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>