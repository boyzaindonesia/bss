<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <form action="<?php echo $own_links;?>/save_apps" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">Android</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Download</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <a href="<?php echo base_url().'assets/collections/files/'.$device_config->device_android_apk; ?>" target="_blank"><?php echo $device_config->device_android_apk; ?></a> ( Last update: Version: <?php echo $device_config->device_android_version; ?> - Tanggal: <?php echo convDateTimeTable($device_config->device_android_date) ?> )
                            </div>
                        </div>
                    </div>
                    <?php if($this->usergroup == "1"){ ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Upload Update</label>
                        <div class="col-sm-6">
                            <div class="form-control">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input type="file" name="files" />
                                        </span>
                                    </span>
                                    <input type="text" name="text_images" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Version</label>
                        <div class="col-sm-6">
                            <input type="text" name="device_android_version" value="" placeholder="version" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Atau Injek Nama File</label>
                        <div class="col-sm-6">
                            <input type="text" name="device_android_apk" value="" placeholder="nama file" class="form-control">
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="device_config_id" value="<?php echo $device_config->device_config_id ?>">
                            <input type="hidden" name="device_name" value="android">
                            <input type="hidden" name="thisAction" value="upload" />
                            <input type="submit" class="btn btn-danger" value="Update" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <a href="<?php echo site_url('admin/me');?>" class="btn btn-default pull-right"><div>Back</div></a>
            </div>
        </div>
    </div>

    <?php js_select2() ?>

</div>