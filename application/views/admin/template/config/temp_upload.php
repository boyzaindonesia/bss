
<div class="the-box no-border">

    <div class="panel panel-danger mb-10">
        <div class="panel-heading">
            <div class="right-content">
                <button class="btn btn-danger btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-3"><i class="fa fa-chevron-up"></i></button>
            </div>
            <h3 class="panel-title">IMPORT DATA</h3>
        </div>
        <div id="panel-collapse-3" class="collapse in" style="height: auto;">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-8">
                        <form id="formUpload" action="<?php echo $own_links.'/upload'?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <select name="upload_type" class="form-control" required>
                                    <option value="" selected>--- Pilih ---</option>
                                    <option value="ongkir">Ongkir</option>
                                </select>
                            </div>
                            <div class="col-sm-6" style="padding-left: 0px; padding-right: 5px;">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input type="file" name="files" required />
                                        </span>
                                    </span>
                                    <input type="text" name="text_images" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-sm-2" style="padding-left: 0px; padding-right: 5px;">
                                <input type="hidden" name="thisAction" value="upload" />
                                <input type="submit" class="btn btn-danger" value="Upload" />
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
