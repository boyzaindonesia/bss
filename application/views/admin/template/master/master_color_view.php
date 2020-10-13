<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI WARNA</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->color_name)?$val->color_name:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Color</label>
                        <div class="col-sm-5">
                            <div class="form-control" style="<?php echo isset($val->color_hex)?'background-color: #'.$val->color_hex.';':'';?>"><?php echo isset($val->color_hex)?$val->color_hex:'';?></div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->color_id.'-'.changeEnUrl($val->color_name);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>