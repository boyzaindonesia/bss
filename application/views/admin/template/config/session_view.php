<div class="the-box no-border">
    <div class="panel panel-dark panel-block-color">
        <div class="panel-heading">
            <h3 class="panel-title text-uppercase">INFORMASI</h3>
        </div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Last Aktivity</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->last_activity)?$val->last_activity:'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ip Address</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->ip_address)?$val->ip_address:'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">User Agent</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->user_agent)?$val->user_agent:'';?></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">User Data</label>
                    <div class="col-sm-9">
                        <div class="form-control"><?php echo isset($val->user_data)?$val->user_data:'';?></div>
                    </div>
                </div>

                <div class="form-group form-action clearfix">
                    <div class="col-sm-9 col-sm-offset-3">
                        <a href="<?php echo $own_links."/delete/".$val->session_id."-".$val->ip_address;?>"><div class="btn btn-danger">Hapus</div></a>
                        <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>