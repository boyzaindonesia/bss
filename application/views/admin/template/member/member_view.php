<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI MEMBER</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <legend>Info Utama</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->member_name)?$val->member_name:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-5">
                            <div class="form-control"><?php echo isset($val->member_email)?$val->member_email:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Jenis kelamin</label>
                        <div class="col-sm-5">
                            <div class="form-control">
                                <?php echo (isset($val->member_jenis_kelamin)&& $val->member_jenis_kelamin=="0")?'Laki laki':'';?>
                                <?php echo (isset($val->member_jenis_kelamin)&& $val->member_jenis_kelamin=="1")?'Perempuan':'';?>
                            </div>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tempat Lahir</label>
                        <div class="col-sm-5">
                            <div class="form-control"><?php echo isset($val->member_tempat_lahir)?$val->member_tempat_lahir:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Lahir</label>
                        <div class="col-sm-3">
                            <div class="form-control"><?php echo isset($val->member_tgl_lahir)?convDatepickerEnc($val->member_tgl_lahir):'' ?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Foto Profile</label>
                        <div class="col-sm-5 magnific-popup-wrap">
                            <div class="form-control">
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/photo/small/".$val->member_photo);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/photo/small/".$val->member_photo);?>" alt="" class="media-object img-responsive">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-3">
                            <div class="form-control"><span class="label <?php echo isset($val->member_status)?get_status_member($val->member_status)['class']:'' ?>"><?php echo isset($val->member_status)?get_status_member($val->member_status)['status']:'' ?></span></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Newsletter</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="onoffswitch no-margin-top">
                                    <input type="checkbox" name="newsletter" class="onoffswitch-checkbox" id="newsletter" <?php echo isset($val->member_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status_newsletter/".$val->member_id."'".')"':''?> value="1" <?php echo (isset($val->newsletter) && $val->newsletter=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="newsletter">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <legend>Login</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-5">
                            <div class="form-control"><?php echo isset($val->member_username)?$val->member_username:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-5">
                            <div class="form-control">******</div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->member_id.'-'.changeEnUrl($val->member_name);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php js_picker() ?>

</div>