<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="supplier_name" value="<?php echo isset($val->supplier_name)?$val->supplier_name:'';?>" class="form-control" maxlength="255" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Hp</label>
                        <div class="col-sm-5">
                            <input type="text" name="supplier_phone" value="<?php echo isset($val->supplier_phone)?$val->supplier_phone:'';?>" class="form-control" maxlength="25" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea name="supplier_address" class="form-control no-resize" rows="3" maxlength="300"><?php echo isset($val->supplier_address)?$val->supplier_address:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kota</label>
                        <div class="col-sm-5">
                            <select name="city" data-placeholder="--- Select ---" class="form-control chosen-city chosen-select" required>
                                <option value=""></option>
                                <?php $supplier_city_id = isset($val->supplier_city_id)?$val->supplier_city_id:'';?>
                                <?php echo option_city_chosen($supplier_city_id); ?>
                            </select>
                            <?php js_chosen() ?>
                            <?php js_chosen_city() ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Propinsi</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="province" value="" />
                            <input type="text" name="province_name" value="" class="form-control" disabled />
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-sm-3 control-label">Country</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="country" value="" />
                            <input type="text" name="country_name" value="" class="form-control" disabled />
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kode Pos</label>
                        <div class="col-sm-3">
                            <input type="text" name="supplier_postal_code" value="<?php echo isset($val->supplier_postal_code)?$val->supplier_postal_code:'';?>" class="form-control" maxlength="10" />
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="supplier_id" value="<?php echo isset($val->supplier_id)?$val->supplier_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <input type="reset" name="reset" class="btn btn-default" value="Reset" />
                            <a href="<?php echo $own_links.($val->supplier_id!=''?'/view/'.$val->supplier_id.'-'.changeEnUrl($val->supplier_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
