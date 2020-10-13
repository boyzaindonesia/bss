<div class="the-box no-border">
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
                    <div class="col-sm-5">
                        <div class="form-control"><?php echo isset($val->member_blacklist_email)?$val->member_blacklist_email:'';?><?php echo isset($val->member_id)&&$val->member_id!='0'?' - '.get_member_name($val->member_id):'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Status</label>
                    <div class="col-sm-5">
                        <div class="form-control">
                            <?php switch ($val->member_blacklist_status) {
                                case '2': echo 'Dibekukan'; break;
                                case '9': echo 'Banned'; break;
                                default: break;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Alasan</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->member_blacklist_desc)?$val->member_blacklist_desc:'';?></div>
                    </div>
                </div>
                <div class="form-group form-action clearfix">
                    <div class="col-sm-9 col-sm-offset-3">
                        <a href="<?php echo $own_links.'/edit/'.$val->member_blacklist_id.'-'.changeEnUrl($val->member_blacklist_email);?>"><div class="btn btn-danger">Edit</div></a>
                        <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>