<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI SHORT LINK</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->short_link_name)?$val->short_link_name:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kode</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->short_link_code)?$this->short_url.$val->short_link_code:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Link</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->short_link_url)?$val->short_link_url:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Total View</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->short_link_view)?$val->short_link_view:'0';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Date Created</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->short_link_date)?convDateTable($val->short_link_date):'';?></div>
                        </div>
                    </div>

                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->short_link_id.'-'.changeEnUrl($val->short_link_name);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>