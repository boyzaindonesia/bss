<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI MEMBER</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <legend>Info Utama</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="member_name" value="<?php echo isset($val->member_name)?$val->member_name:'';?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email <span class="req">*</span></label>
                        <div class="col-sm-5 has-feedback">
                            <input type="email" name="member_email" value="<?php echo isset($val->member_email)?$val->member_email:'';?>" class="form-control check" data-check-id="<?php echo isset($val->member_id)?$val->member_id:'';?>" data-check-parent="" data-check-rel="member_email" data-check-msg="" data-check-err="Email sudah ada." required>
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Jenis kelamin <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <select name="member_jenis_kelamin" class="form-control" required>
                                <option value="" selected>--- SELECT ---</option>
                                <option value="0" <?php echo (isset($val->member_jenis_kelamin)&& $val->member_jenis_kelamin=="0")?'selected="selected"':'';?>>Laki laki</option>
                                <option value="1" <?php echo (isset($val->member_jenis_kelamin)&& $val->member_jenis_kelamin=="1")?'selected="selected"':'';?>>Perempuan</option>
                            </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tempat Lahir</label>
                        <div class="col-sm-5">
                            <input type="text" name="member_tempat_lahir" value="<?php echo isset($val->member_tempat_lahir)?$val->member_tempat_lahir:'';?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Lahir</label>
                        <div class="col-sm-3">
                            <input type="text" name="pdate" class="form-control datepicker" value="<?php echo isset($val->member_tgl_lahir)?convDatepickerEnc($val->member_tgl_lahir):'' ?>" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Foto Profile</label>
                        <?php if( isset($val->member_id) && trim($val->member_id)!="" ){ ?>
                        <div class="col-sm-5 form-preview-images crop-avatar" data-title-crop="Ganti Foto Profile" data-url-crop="<?php echo $own_links."/change_avatar/".$val->member_id;?>">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->member_photo) && trim($val->member_photo)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <div class="avatar-open avatar-view" data-toggle="tooltip" data-original-title="Ganti Foto Profile" data-placement="bottom">
                                    <img src="<?php echo get_image(base_url()."assets/collections/photo/small/".$val->member_photo);?>" alt="" class="media-object img-responsive return-preview-images">
                                    <input type="file" name="member_photo" style="display: none;" accept="image/*" />
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
                                        Browse… <input type="file" name="member_photo" onChange="previewImages(this);" accept="image/*" />
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
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-5">
                            <div class="form-control"><span class="label <?php echo isset($val->member_status)?get_status_member($val->member_status)['class']:'label-success' ?>"><?php echo isset($val->member_status)?get_status_member($val->member_status)['status']:'Aktif' ?></span></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Newsletter</label>
                        <div class="col-sm-9">
                            <div class="onoffswitch">
                                <input type="checkbox" name="newsletter" class="onoffswitch-checkbox" id="newsletter" <?php echo isset($val->member_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status_newsletter/".$val->member_id."'".')"':''?> value="1" <?php echo (isset($val->newsletter) && $val->newsletter=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="newsletter">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <legend>Login</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-5 has-feedback">
                            <input type="text" name="member_username" value="<?php echo isset($val->member_username)?$val->member_username:'';?>" class="form-control check" data-check-id="<?php echo isset($val->member_id)?$val->member_id:'';?>" data-check-parent="" data-check-rel="member_username" data-check-err="Username sudah digunakan." onkeypress="validateUsername(event);" minlength="4" maxlength="30">
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Password <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <input type="password" name="member_password" id="passwd" class="form-control" minlength="6" <?php echo isset($val->member_password)?'':'required';?>>
                            <span class="help-block">Min 6 Character</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Konfirmasi Password <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <input type="password" name="member_password2" id="confirm_passwd" class="form-control" minlength="6" <?php echo isset($val->member_password)?'':'required';?>>
                            <span class="help-block">Min 6 Character</span>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="member_id" value="<?php echo isset($val->member_id)?$val->member_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->member_id!=''?'/view/'.$val->member_id.'-'.changeEnUrl($val->member_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php js_picker() ?>
    <?php js_cropper() ?>

</div>