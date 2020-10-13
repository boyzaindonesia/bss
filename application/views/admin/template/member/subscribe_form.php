<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI SUBSCRIBE</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email <span class="req">*</span></label>
                        <div class="col-sm-5 has-feedback">
                            <input type="email" name="subscribe_email" value="<?php echo isset($val->subscribe_email)?$val->subscribe_email:'';?>" class="form-control check" data-check-id="<?php echo isset($val->subscribe_id)?$val->subscribe_id:'';?>" data-check-parent="" data-check-rel="subscribe_email" required>
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="subscribe_id" value="<?php echo isset($val->subscribe_id)?$val->subscribe_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->subscribe_id!=''?'/view/'.$val->subscribe_id.'-'.changeEnUrl($val->subscribe_email):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>