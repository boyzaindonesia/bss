<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI KATEGORI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->product_category_title)?$val->product_category_title:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->product_category_desc)?$val->product_category_desc:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Sub Kategori</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <select name="product_category_parent_id" class="form-control" disabled="disabled">
                                    <option value="0" selected>Tidak ada sub kategori</option>
                                    <?php 
                                    $parentId = isset($val)?$val->product_category_parent_id:'';
                                    echo get_product_category_parent($parentId);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-4 magnific-popup-wrap">
                            <div class="form-control">
                                <div style="position: relative; display: inline-block;">
                                    <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".$val->product_category_image);?>">
                                        <img src="<?php echo get_image(base_url()."assets/collections/product/small/".$val->product_category_image);?>" alt="" class="media-object img-responsive">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="onoffswitch no-margin-top">
                                    <input type="checkbox" name="product_category_status" class="onoffswitch-checkbox" id="product_category_status" <?php echo isset($val->product_category_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->product_category_id."'".')"':''?> value="1" <?php echo (isset($val->product_category_status) && $val->product_category_status=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="product_category_status">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->product_category_id.'-'.changeEnUrl($val->product_category_title);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>