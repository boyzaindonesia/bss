<div class="the-box no-border">
    <div class="btn-toolbar">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
        <legend><?php echo isset($title)?$title:'';?></legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">Password Lama <span class="req">*</span></label>
            <div class="col-sm-5">
                <input type="password" name="old_pass" class="form-control margin-bottom" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Password Baru <span class="req">*</span></label>
            <div class="col-sm-5">
                <input type="password" name="new_pass" id="passwd" class="form-control" minlength="6" required>
                <span class="help-block">Min 6 Character</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Konfirmasi Password Baru <span class="req">*</span></label>
            <div class="col-sm-5">
                <input type="password" name="conf_new_pass" id="confirm_passwd" class="form-control" minlength="6" required>
                <span class="help-block">Min 6 Character</span>
            </div>
        </div>
        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="submit" name="btn_simpan" class="btn btn-danger" value="Update Password" />
                <a href="<?php echo site_url("admin/me/profile");?>"><div class="btn btn-default pull-right">Back</div></a>
            </div>
        </div>
    </form>

</div>