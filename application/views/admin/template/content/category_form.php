<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI KATEGORI ARTIKEL</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="category_title" value="<?php echo isset($val->category_title)?$val->category_title:'';?>" class="form-control" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="category_desc" class="form-control no-resize" rows="6"><?php echo isset($val->category_desc)?$val->category_desc:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Sub Kategori</label>
                        <div class="col-sm-9">
                            <select name="category_parent_id" class="form-control" >
                                <option value="0" selected>No Category Parent</option>
                                <?php 
                                $parentId = isset($val)?$val->category_parent_id:'';
                                echo get_category_parent($parentId);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->category_image) && trim($val->category_image)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/article/small/".$val->category_image);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/article/thumb/".$val->category_image);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="category_image" onChange="previewImages(this);" accept="image/*" />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                            <p class="help-block"><?php echo $this->image_size_str;?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="onoffswitch">
                                <input type="checkbox" name="category_status" class="onoffswitch-checkbox" id="category_status" <?php echo isset($val->category_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->category_id."'".')"':''?> value="1" <?php echo (isset($val->category_status) && $val->category_status=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="category_status">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="category_id" value="<?php echo isset($val->category_id)?$val->category_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <input type="reset" name="reset" class="btn btn-default" value="Reset" />
                            <a href="<?php echo $own_links.($val->category_id!=''?'/view/'.$val->category_id.'-'.changeEnUrl($val->category_title):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>