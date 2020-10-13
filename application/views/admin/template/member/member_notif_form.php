<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">SEND MEMBER NOTIF</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->member_notif_date)?convDateTimeTable($val->member_notif_date):convDateTimeTable(timestamp());?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email Member</label>
                        <div class="col-sm-5 has-feedback">
                            <input type="search" name="member_id" value="<?php echo isset($val->member_id)&&$val->member_id!=''?get_member_email($val->member_id).' - '.get_member_name($val->member_id):'';?>" class="form-control check-autocomplete" required />
                            <div class="form-autocomplete-feedback"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Member</label>
                        <div class="col-sm-5">
                            <select name="member_notif_action" class="form-control">
                                <option value="" selected>--- No Status ---</option>
                                <option value="2" <?php echo (isset($val->member_notif_action)&& $val->member_notif_action=="2")?'selected="selected"':'';?>>Dibekukan</option>
                                <option value="9" <?php echo (isset($val->member_notif_action)&& $val->member_notif_action=="9")?'selected="selected"':'';?>>Banned</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Pesan</label>
                        <div class="col-sm-9">
                            <textarea name="member_notif_desc" class="form-control no-resize" rows="6"><?php echo isset($val->member_notif_desc)?$val->member_notif_desc:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Berulang</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="checkbox no-padding-top">
                                  <label><input type="checkbox" name="member_notif_repeat" value="1" <?php echo (isset($val->member_notif_repeat) && $val->member_notif_repeat=="1")?'checked':'';?> > Yes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Paksa keluar</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="checkbox no-padding-top">
                                  <label><input type="checkbox" name="member_notif_logout" value="1" <?php echo (isset($val->member_notif_logout) && $val->member_notif_logout=="1")?'checked':'';?> > Yes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="onoffswitch no-margin-top">
                                    <input type="checkbox" name="member_notif_status" class="onoffswitch-checkbox" id="member_notif_status" value="1" <?php echo (isset($val->member_notif_status) && $val->member_notif_status=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="member_notif_status">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="member_notif_id" value="<?php echo isset($val->member_notif_id)?$val->member_notif_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->member_notif_id!=''?'/view/'.$val->member_notif_id.'-'.changeEnUrl(get_member_name($val->member_id)):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>