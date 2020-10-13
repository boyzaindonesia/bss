<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI KATEGORI ARTIKEL</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->category_title)?$val->category_title:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->category_desc)?$val->category_desc:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Sub Kategori</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <?php 
                                    $parentId = isset($val)?$val->category_parent_id:'';
                                    echo get_root_category_parent($parentId);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div class="form-control">
                                <?php if( isset($val->category_image) && trim($val->category_image)!="" ){ ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/article/small/".$val->category_image);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/article/thumb/".$val->category_image);?>" alt="" class="mfp-fade item-gallery img-responsive">
                                </a>
                                <?php } else { ?>
                                <div style="position: relative;">
                                    <img src="<?php echo get_image(base_url()."/none");?>" alt="" class="media-object img-responsive return-preview-images">
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="onoffswitch no-margin-top">
                                    <input type="checkbox" name="category_status" class="onoffswitch-checkbox" id="category_status" <?php echo isset($val->category_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->category_id."'".')"':''?> value="1" <?php echo (isset($val->category_status) && $val->category_status=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="category_status">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->category_id.'-'.changeEnUrl($val->category_title);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>