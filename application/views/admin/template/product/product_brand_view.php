<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->product_brand_name)?$val->product_brand_name:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->product_brand_desc)?$val->product_brand_desc:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-4 magnific-popup-wrap">
                            <div class="form-control">
                                <div style="position: relative; display: inline-block;">
                                    <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/brand/large/".$val->product_brand_image);?>">
                                        <img src="<?php echo get_image(base_url()."assets/collections/brand/large/".$val->product_brand_image);?>" alt="" class="media-object img-responsive">
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
                                    <input type="checkbox" name="product_brand_status" class="onoffswitch-checkbox" id="product_brand_status" <?php echo isset($val->product_brand_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->product_brand_id."'".')"':''?> value="1" <?php echo (isset($val->product_brand_status) && $val->product_brand_status=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="product_brand_status">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Url</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->url)?$val->url:'';?></div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->product_brand_id.'-'.changeEnUrl($val->product_brand_name);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>