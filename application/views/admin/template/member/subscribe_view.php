<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI SUBSCRIBE</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-5">
                            <div class="form-control"><?php echo isset($val->subscribe_email)?$val->subscribe_email:'';?></div>
                        </div>
                    </div>

                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->subscribe_id.'-'.changeEnUrl($val->subscribe_email);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>