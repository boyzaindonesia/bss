<div class="the-box no-border">
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
                    <div class="col-sm-5">
                        <div class="form-control"><?php echo isset($val->member_id)&&$val->member_id!=''?get_member_email($val->member_id).' - '.get_member_name($val->member_id):'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status Member</label>
                    <div class="col-sm-5">
                        <div class="form-control">
                            <?php switch ($val->member_notif_action) {
                                case '2': echo 'Dibekukan'; break;
                                case '9': echo 'Banned'; break;
                                default: break;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Pesan</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->member_notif_desc)?$val->member_notif_desc:'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Berulang</label>
                    <div class="col-sm-9">
                        <div class="form-control">
                            <div class="checkbox no-padding-top">
                              <label><input type="checkbox" name="member_notif_repeat" value="1" <?php echo (isset($val->member_notif_repeat) && $val->member_notif_repeat=="1")?'checked':'';?> disabled> Yes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Paksa keluar</label>
                    <div class="col-sm-9">
                        <div class="form-control">
                            <div class="checkbox no-padding-top">
                              <label><input type="checkbox" name="member_notif_logout" value="1" <?php echo (isset($val->member_notif_logout) && $val->member_notif_logout=="1")?'checked':'';?> disabled> Yes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-9">
                        <div class="form-control">
                            <div class="onoffswitch no-margin-top">
                                <input type="checkbox" name="member_notif_status" class="onoffswitch-checkbox" id="member_notif_status" <?php echo isset($val->member_notif_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->member_notif_id."'".')"':''?> value="1" <?php echo (isset($val->member_notif_status) && $val->member_notif_status=="0")?'':'checked';?> >
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
                        <a href="<?php echo $own_links.'/edit/'.$val->member_notif_id.'-'.changeEnUrl(get_member_name($val->member_id));?>"><div class="btn btn-danger">Edit</div></a>
                        <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>