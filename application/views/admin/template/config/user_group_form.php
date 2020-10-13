<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI GRUP PENGGUNA</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Grup Pengguna <span class="req">*</span></label>
                        <div class="col-sm-9 has-feedback">
                            <input type="text" name="ag_group_name" value="<?php echo isset($val->ag_group_name)?$val->ag_group_name:'';?>" class="form-control check" data-check-id="<?php echo isset($val->ag_id)?$val->ag_id:'';?>" data-check-parent="" data-check-rel="ag_group_name" maxlength="255" required>
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="ag_group_desc" class="form-control no-resize" rows="6"><?php echo isset($val->ag_group_desc)?$val->ag_group_desc:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="onoffswitch">
                                <input type="checkbox" name="ag_group_status" class="onoffswitch-checkbox" id="ag_group_status" <?php echo isset($val->ag_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->ag_id."'".')"':''?> value="1" <?php echo (isset($val->ag_group_status) && $val->ag_group_status=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="ag_group_status">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>                                        
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="ag_id" value="<?php echo isset($val->ag_id)?$val->ag_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->ag_id!=''?'/view/'.$val->ag_id.'-'.changeEnUrl($val->ag_group_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>