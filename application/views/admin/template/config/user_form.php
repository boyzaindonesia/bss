<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI PENGGUNA</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <legend>Info Utama</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Toko <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <select name="store_id" class="form-control" required >
                                <option value="" selected disabled>--- SELECT ---</option>
                                <?php
                                $i = 0;
                                $store = get_store();
                                foreach($store as $k => $v){
                                    $selected = (($i=='0')||($v->store_id==$val->store_id)?'selected':'');
                                    ?>
                                    <option value="<?php echo $v->store_id ?>" <?php echo $selected ?> ><?php echo $v->store_name ?></option>
                                <?php 
                                $i += 1;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Lengkap <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="user_fullname" value="<?php echo isset($val->user_fullname)?$val->user_fullname:'';?>" class="form-control" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email <span class="req">*</span></label>
                        <div class="col-sm-5 has-feedback">
                            <input type="email" name="user_email" value="<?php echo isset($val->user_email)?$val->user_email:'';?>" class="form-control check" data-check-id="<?php echo isset($val->user_id)?$val->user_id:'';?>" data-check-parent="" data-check-rel="user_email" maxlength="255" required>
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <legend>Login</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Akses Grup Pengguna <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <select name="user_group" class="form-control" required >
                                <option value="" selected disabled>--- SELECT ---</option>
                                <?php
                                if(count($group)>0){
                                    foreach($group as $r){
                                        $t = isset($val->user_group)?$val->user_group:""; ?>
                                        <option value="<?php echo $r->ag_id ?>" <?php if($r->ag_id == $t){ echo 'selected'; } ?> ><?php echo $r->ag_group_name ?></option>
                                <?php 
                                    }
                                } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Username <span class="req">*</span></label>
                        <div class="col-sm-5 has-feedback">
                            <?php if(!isset($val->user_name)){ ?>
                            <input type="text" name="user_name" value="<?php echo isset($val->user_name)?$val->user_name:'';?>" class="form-control check" data-check-id="<?php echo isset($val->user_id)?$val->user_id:'';?>" data-check-parent="" data-check-rel="user_name" maxlength="15" required>
                            <span class="fa form-control-feedback"></span>
                            <?php } else { ?>
                            <input type="text" value="<?php echo isset($val->user_name)?$val->user_name:'';?>" class="form-control" disabled/>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Password <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <input type="password" name="user_password" id="passwd" class="form-control" minlength="6" <?php echo isset($val->user_password)?'':'required';?>>
                            <span class="help-block">Min 6 Character</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Konfirmasi Password <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <input type="password" name="user_password2" id="confirm_passwd" class="form-control" minlength="6" <?php echo isset($val->user_password)?'':'required';?>>
                            <span class="help-block">Min 6 Character</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Foto Profile</label>
                        <?php if( isset($val->user_id) && trim($val->user_id)!="" ){ ?>
                        <div class="col-sm-5 form-preview-images crop-avatar" data-title-crop="Ganti Foto Profile" data-url-crop="<?php echo $own_links."/change_avatar/".$val->user_id;?>">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->user_photo) && trim($val->user_photo)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <div class="avatar-open avatar-view" data-toggle="tooltip" data-original-title="Ganti Foto Profile" data-placement="bottom">
                                    <img src="<?php echo get_image(base_url()."assets/collections/photo/small/".$val->user_photo);?>" alt="" class="media-object img-responsive return-preview-images">
                                    <input type="file" name="user_photo" style="display: none;" accept="image/*" />
                                    <input type="hidden" name="remove_images" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                                </div>
                            </div>
                            <p class="help-block"><?php echo $this->image_size_str;?></p>
                        </div>
                        <?php } else { ?>
                        <div class="col-sm-5 form-preview-images">
                            <div style="position: relative;">
                                <img src="<?php echo get_image(base_url()."/none");?>" alt="" class="media-object img-responsive return-preview-images">
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="user_photo" onChange="previewImages(this);" accept="image/*" />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                            <p class="help-block"><?php echo $this->image_size_str;?></p>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Bisa Lihat Semua Data ?</label>
                        <div class="col-sm-9">
                            <div class="checkbox">
                              <label><input type="checkbox" name="is_show_all" value="1" <?php echo isset($val) && $val->is_show_all=="1"?'checked':'';?> > Yes</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="onoffswitch">
                                <input type="checkbox" name="user_status" class="onoffswitch-checkbox" id="user_status" <?php echo isset($val->user_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->user_id."'".')"':''?> value="1" <?php echo (isset($val->user_status) && $val->user_status=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="user_status">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                        <input type="hidden" name="user_id" value="<?php echo isset($val->user_id)?$val->user_id:'';?>" />
                            <input type="submit" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->user_id!=''?'/view/'.$val->user_id.'-'.changeEnUrl($val->user_fullname):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php js_cropper() ?>

</div>