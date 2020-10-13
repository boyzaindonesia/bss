<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI WARNA</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama <span class="req">*</span></label>
                        <div class="col-sm-9 has-feedback">
                            <input type="text" name="color_name" value="<?php echo isset($val->color_name)?$val->color_name:'';?>" class="form-control check" data-check-id="" data-check-parent="" data-check-rel="color_name" data-check-msg="" data-check-err="Nama Warna sudah ada." maxlength="255" required />
                            <span class="fa form-control-feedback"></span>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Color <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <input type="text" name="color_hex" class="form-control jscolor" value="<?php echo isset($val->color_hex)?$val->color_hex:'';?>" maxlength="255" required />
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="color_id" value="<?php echo isset($val->color_id)?$val->color_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <input type="reset" name="reset" class="btn btn-default" value="Reset" />
                            <a href="<?php echo $own_links.($val->color_id!=''?'/view/'.$val->color_id.'-'.changeEnUrl($val->color_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="<?php echo base_url();?>assets/plugins/jscolor/jscolor.min.js"></script>

</div>
