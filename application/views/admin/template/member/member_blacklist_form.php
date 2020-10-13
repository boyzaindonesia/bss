<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">MEMBER BLACKLIST/BANNED</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->member_blacklist_date)?convDateTimeTable($val->member_blacklist_date):convDateTimeTable(timestamp());?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-5 has-feedback">
                            <?php if(isset($val->member_blacklist_id)){ ?>
                            <input type="hidden" name="member_blacklist_email" value="<?php echo $val->member_blacklist_email ?><?php echo isset($val->member_id)&&$val->member_id!='0'?' - '.get_member_name($val->member_id):'';?>" class="form-control" />
                            <div class="form-control"><?php echo $val->member_blacklist_email ?><?php echo isset($val->member_id)&&$val->member_id!='0'?' - '.get_member_name($val->member_id):'';?></div>
                            <?php } else { ?>
                            <input type="search" name="member_blacklist_email" value="<?php echo isset($val->member_blacklist_email)?$val->member_blacklist_email:'';?><?php echo isset($val->member_id)&&$val->member_id!='0'?' - '.get_member_name($val->member_id):'';?>" class="form-control check-autocomplete" <?php echo isset($val->member_blacklist_id)?'readonly':'';?> />
                            <div class="form-autocomplete-feedback"></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-5">
                            <select name="member_blacklist_status" class="form-control" required>
                                <option value="" selected disabled>--- Pilih ---</option>
                                <option value="2" <?php echo (isset($val->member_blacklist_status)&& $val->member_blacklist_status=="2")?'selected="selected"':'';?>>Dibekukan</option>
                                <option value="9" <?php echo (isset($val->member_blacklist_status)&& $val->member_blacklist_status=="9")?'selected="selected"':'';?>>Banned</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alasan</label>
                        <div class="col-sm-9">
                            <textarea name="member_blacklist_desc" class="form-control no-resize" rows="6"><?php echo isset($val->member_blacklist_desc)?$val->member_blacklist_desc:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="member_blacklist_id" value="<?php echo isset($val->member_blacklist_id)?$val->member_blacklist_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->member_blacklist_id!=''?'/view/'.$val->member_blacklist_id.'-'.changeEnUrl($val->member_blacklist_email):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>