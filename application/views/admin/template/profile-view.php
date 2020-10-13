<div class="the-box no-border">
	<div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

	<form action="" method="post" enctype="multipart/form-data">
	    <div class="panel panel-dark panel-block-color">
	        <div class="panel-heading">
	            <h3 class="panel-title text-uppercase">INFORMASI PROFILE</h3>
	        </div>
	        <div class="panel-body">
	            <div class="form-horizontal">
					<legend>Info Utama</legend>
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama Lengkap</label>
						<div class="col-sm-8">
							<div class="form-control"><?php echo isset($data->user_fullname)?$data->user_fullname:'';?></div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Email</label>
						<div class="col-sm-8">
							<div class="form-control"><?php echo isset($data->user_email)?$data->user_email:'';?></div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Grup Pengguna</label>
						<div class="col-sm-8">
							<div class="form-control"><?php echo isset($data->user_group)?get_group($data->user_group):'';?></div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Username</label>
						<div class="col-sm-8">
							<div class="form-control"><?php echo $data->user_name;?></div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Password</label>
						<div class="col-sm-5">
							<div class="form-control"><a href="<?php echo site_url("admin/me/change_password");?>">Ganti Password</a></div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Foto Profile</label>
						<div class="col-sm-5 crop-avatar" data-title-crop="Ganti Foto Profile" data-url-crop="<?php echo site_url("admin/me/change_avatar");?>">
							<div class="form-control">
								<div class="avatar-open avatar-view" data-toggle="tooltip" data-original-title="Ganti Foto Profile" data-placement="bottom">
									<img src="<?php echo get_image(base_url()."assets/collections/photo/small/".$data->user_photo);?>" alt="<?php echo isset($data->user_fullname)?$data->user_fullname:'';?>" class="media-object img-responsive">
								</div>
							</div>
						</div>
					</div>
					<div class="form-group form-action clearfix">
						<div class="col-sm-9 col-sm-offset-3">
				            <a href="<?php echo site_url("admin/me/edit_profile");?>"><div class="btn btn-danger">Edit Profile</div></a>
				            <a href="<?php echo site_url("admin/me");?>"><div class="btn btn-default pull-right">Back</div></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<?php js_cropper() ?>
	
</div>