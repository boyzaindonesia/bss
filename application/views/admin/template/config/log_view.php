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
                        <div class="form-control"><?php echo isset($val->log_date)?convDateTimeTable($val->log_date):'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">User</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->log_user_id)?get_user_name($val->log_user_id):'';?></div>
                    </div>
                </div>
                <?php $product = get_detail_product($val->log_detail_id); ?>
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
                        <div class="form-control"><?php echo log_title($val->log_title_id)['name']; ?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Deskripsi</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->log_desc)?$val->log_desc:'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Qty</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->log_detail_qty)?$val->log_detail_qty:'';?></div>
                    </div>
                </div>
                <?php if($val->log_detail_item != ""){?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Item</label>
                    <div class="col-sm-9">
                        <div class="table-responsive">
                            <table class="table table-th-block table-dark">
                                <colgroup>
                                    <col width="1">
                                    <col width="1">
                                    <col width="1">
                                    <col width="1">
                                    <col>
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="nobr text-center">ID</th>
                                        <th class="nobr text-center">Nama</th>
                                        <th class="nobr text-center">Stok Awal</th>
                                        <th class="nobr text-center">Stok Skrg</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $log_detail_item = json_decode($val->log_detail_item);
                                    foreach ($log_detail_item as $key => $value) { ?>
                                    <tr>
                                        <td class="nobr text-center"><?php echo $value->id ?></td>
                                        <td class="nobr text-center"><?php echo $value->name ?></td>
                                        <td class="nobr text-center"><?php echo $value->qty_old ?></td>
                                        <td class="nobr text-center"><?php echo $value->qty_new ?></td>
                                        <td class="text-center"><?php echo ($value->status==1?"Ready":"Terjual") ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group form-action clearfix">
                    <div class="col-sm-9 col-sm-offset-3">
                        <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>