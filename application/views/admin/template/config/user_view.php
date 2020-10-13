<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI PENGGUNA</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <legend>Info Utama</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Toko</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                            <?php 
                            $detail_store = get_detail_store($val->store_id);
                            echo $detail_store->store_name;
                            ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->user_fullname)?$val->user_fullname:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->user_email)?$val->user_email:'';?></div>
                        </div>
                    </div>
                    <legend>Login</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Akses Grup Pengguna</label>
                        <div class="col-sm-9">
                            <?php
                            if(count($group)>0){
                                foreach($group as $r){
                                    $t = isset($val->user_group)?$val->user_group:""; 
                                    if($r->ag_id == $t){ 
                                    ?>
                                    <div class="form-control"><?php echo $r->ag_group_name ?></div>
                                    <?php
                                    }
                                }
                            } 
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->user_name)?$val->user_name:''; ?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Password </label>
                        <div class="col-sm-5">
                            <div class="form-control">***************</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Foto Profile</label>
                        <div class="col-sm-5 form-preview-images crop-avatar" data-title-crop="Ganti Foto Profile" data-url-crop="<?php echo $own_links."/change_avatar/".$val->user_id;?>">
                            <div style="position: relative; display: inline-block;">
                                <div class="avatar-open avatar-view" data-toggle="tooltip" data-original-title="Ganti Foto Profile" data-placement="bottom">
                                    <img src="<?php echo get_image(base_url()."assets/collections/photo/small/".$val->user_photo);?>" alt="" class="media-object img-responsive return-preview-images">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Bisa Lihat Semua Data ?</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="checkbox no-padding-top">
                                  <label><input type="checkbox" name="is_show_all" value="1" <?php echo isset($val) && $val->is_show_all=="1"?'checked':'';?> disabled="disabled"> Yes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="onoffswitch no-margin-top">
                                    <input type="checkbox" name="user_status" class="onoffswitch-checkbox" id="user_status" <?php echo isset($val->user_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->user_id."'".')"':''?> value="1" <?php echo (isset($val->user_status) && $val->user_status=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="user_status">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->user_id.'-'.changeEnUrl($val->user_fullname);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php js_cropper() ?>

</div>