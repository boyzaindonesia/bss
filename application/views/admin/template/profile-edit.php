<div class="the-box no-border">
	<div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>
	
	<?php
		$val = $data;
	?>
	<form action="" method="post" enctype="multipart/form-data">
	    <div class="panel panel-dark panel-block-color">
	        <div class="panel-heading">
	            <h3 class="panel-title text-uppercase">INFORMASI PROFILE</h3>
	        </div>
	        <div class="panel-body">
	            <div class="form-horizontal">
					<legend>Info Utama</legend>
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama Lengkap <span class="req">*</span></label>
						<div class="col-sm-9">
							<input type="text" name="user_fullname" value="<?php echo isset($val->user_fullname)?$val->user_fullname:'';?>" class="form-control" maxlength="255" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Email <span class="req">*</span></label>
				        <div class="col-sm-5 has-feedback">
				            <input type="email" name="user_email" value="<?php echo isset($val->user_email)?$val->user_email:'';?>" class="form-control check" data-check-id="<?php echo isset($val->user_id)?$val->user_id:'';?>" data-check-parent="" data-check-rel="user_email" required>
				            <span class="fa form-control-feedback"></span>
				        </div>
					</div>
					<!-- <div class="form-group">
						<label class="col-sm-3 control-label">Foto Profile</label>
						<div class="col-sm-5 form-preview-images">
							<div style="position: relative;">
								<img src="<?php echo get_image(base_url()."assets/collections/photo/small/".$val->user_photo);?>" alt="" class="media-object img-responsive return-preview-images">
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
				            <p class="help-block"><?php echo "Size : ".$this->image_size_str;?></p>
						</div>
					</div> -->

					<div class="form-group">
						<label class="col-sm-3 control-label">Foto Profile</label>
						<div class="col-sm-5 form-preview-images crop-avatar" data-title-crop="Ganti Foto Profile" data-url-crop="<?php echo site_url("admin/me/change_avatar");?>">
							<div style="position: relative; display: inline-block;">
								<?php if( isset($val->user_photo) && trim($val->user_photo)!="" ){ ?>
				                <div class="right-action">
				                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
				                </div>
				                <?php } ?>
								<div class="avatar-open avatar-view" data-toggle="tooltip" data-original-title="Ganti Foto Profile" data-placement="bottom">
									<img src="<?php echo get_image(base_url()."assets/collections/photo/small/".$val->user_photo);?>" alt="" class="media-object img-responsive return-preview-images">
					                <input type="hidden" name="remove_images" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
								</div>
							</div>
				            <p class="help-block"><?php echo $this->image_size_str;?></p>
						</div>
					</div>

					<legend>Info Login</legend>
					<div class="form-group">
						<label class="col-sm-3 control-label">Username</label>
						<div class="col-sm-5">
							<?php if(!isset($val->user_name)){ ?>
								<input type="text" name="user_name" value="" class="form-control" maxlength="15" required>
							<?php } else { ?>
								<input type="text" value="<?php echo isset($val->user_name)?$val->user_name:'';?>" class="form-control" disabled>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Password</label>
						<div class="col-sm-5">
							<input type="password" name="user_password" id="passwd" class="form-control" <?php echo isset($val->user_password)?'':'minlength="6" required';?> >
							<span class="help-block">Min 6 Character</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Konfirmasi Password</label>
						<div class="col-sm-5">
							<input type="password" name="user_password2" id="confirm_passwd" class="form-control" <?php echo isset($val->user_password)?'':'minlength="6" required';?> >
				            <span class="help-block">Min 6 Character</span>
						</div>
					</div>
					<div class="form-group form-action clearfix">
						<div class="col-sm-9 col-sm-offset-3">
				            <input type="submit" name="update" class="btn btn-danger" value="Update Profile" />
				            <a href="<?php echo site_url("admin/me/profile");?>"><div class="btn btn-default pull-right">Back</div></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<?php js_cropper() ?>
</div>