<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI WEBSITE</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Website <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="configuration_name" value="<?php echo isset($configuration->configuration_name)?$configuration->configuration_name:""?>" class="form-control" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Logo</label>
                        <div class="col-sm-5 form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($configuration->configuration_logo) && trim($configuration->configuration_logo)!="" ){?>
                                <img src="<?php echo get_image(base_url()."assets/images/".$configuration->configuration_logo);?>" class="item-gallery img-responsive return-preview-images">
                                <?php } ?>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="configuration_logo" onChange="previewImages(this);" accept="image/*" value="<?php echo isset($configuration->configuration_id)?$configuration->configuration_id:""?>" />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                            </div>
                            <p class="help-block"><?php echo "Size : ".$this->image_size_str;?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tentang Kami</label>
                        <div class="col-sm-9">
                            <textarea name="configuration_about" class="form-control no-resize" rows="6"><?php echo isset($configuration->configuration_about)?$configuration->configuration_about:""?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea name="configuration_alamat" class="form-control no-resize" rows="6"><?php echo isset($configuration->configuration_alamat)?$configuration->configuration_alamat:""?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" name="configuration_email" value="<?php echo isset($configuration->configuration_email)?$configuration->configuration_email:""?>" class="form-control" maxlength="255">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email cc</label>
                        <div class="col-sm-9">
                            <select name="configuration_email_cc[]" class="select2-tokenizer form-control select2-hidden-accessible" multiple>
                                <?php
                                if(isset($configuration->configuration_email_cc)&&$configuration->configuration_email_cc!=''){
                                    foreach (explode(',', $configuration->configuration_email_cc) as $n){
                                        echo '<option value="'.$n.'" selected>'.$n.'</option>';
                                    }
                                }
                                ?>
                            </select>
                            <!-- <p class="help-block">jika email lebih dari 1, maka pisahkan dengan tanda koma,<br/> contoh: admin@<?php echo $_SERVER['HTTP_HOST'] ?>, info@<?php echo $_SERVER['HTTP_HOST'] ?>, contact@<?php echo $_SERVER['HTTP_HOST'] ?></p> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Telepon</label>
                        <div class="col-sm-9">
                            <input type="text" name="configuration_telp" value="<?php echo isset($configuration->configuration_telp)?$configuration->configuration_telp:""?>" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Fax</label>
                        <div class="col-sm-9">
                            <input type="text" name="configuration_fax" value="<?php echo isset($configuration->configuration_fax)?$configuration->configuration_fax:""?>" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="configuration_id" value="<?php echo isset($configuration->configuration_id)?$configuration->configuration_id:""?>">
                            <input type="submit" class="btn btn-danger" value="Update" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="<?php echo $own_links;?>/save_sosmed" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">SOSIAL MEDIA</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Website</label>
                        <div class="col-sm-9">
                            <input type="text" name="configuration_website" value="<?php echo isset($configuration->configuration_website)?$configuration->configuration_website:""?>" placeholder="Link" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Facebook</label>
                        <div class="col-sm-3">
                            <input type="text" name="configuration_fb_name" value="<?php echo isset($configuration->configuration_fb_name)?$configuration->configuration_fb_name:""?>" placeholder="Nama" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="configuration_fb_link" value="<?php echo isset($configuration->configuration_fb_link)?$configuration->configuration_fb_link:""?>" placeholder="Link" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Twitter</label>
                        <div class="col-sm-3">
                            <input type="text" name="configuration_tw_name" value="<?php echo isset($configuration->configuration_tw_name)?$configuration->configuration_tw_name:""?>" placeholder="Nama" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="configuration_tw_link" value="<?php echo isset($configuration->configuration_tw_link)?$configuration->configuration_tw_link:""?>" placeholder="Link" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Instagram</label>
                        <div class="col-sm-3">
                            <input type="text" name="configuration_ig_name" value="<?php echo isset($configuration->configuration_ig_name)?$configuration->configuration_ig_name:""?>" placeholder="Nama" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="configuration_ig_link" value="<?php echo isset($configuration->configuration_ig_link)?$configuration->configuration_ig_link:""?>" placeholder="Link" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Youtube</label>
                        <div class="col-sm-3">
                            <input type="text" name="configuration_yt_name" value="<?php echo isset($configuration->configuration_yt_name)?$configuration->configuration_yt_name:""?>" placeholder="Nama" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="configuration_yt_link" value="<?php echo isset($configuration->configuration_yt_link)?$configuration->configuration_yt_link:""?>" placeholder="Link" class="form-control">
                        </div>
                    </div>

                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="configuration_id" value="<?php echo isset($configuration->configuration_id)?$configuration->configuration_id:""?>">
                            <input type="submit" class="btn btn-danger" value="Update" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="<?php echo $own_links;?>/save_meta" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI META NAME</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Title</label>
                        <div class="col-sm-9">
                            <input type="text" name="configuration_meta_title" value="<?php echo isset($configuration->configuration_meta_title)?$configuration->configuration_meta_title:""?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Keyword</label>
                        <div class="col-sm-9">
                            <input type="text" name="configuration_meta_keyword" value="<?php echo isset($configuration->configuration_meta_keyword)?$configuration->configuration_meta_keyword:""?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Description</label>
                        <div class="col-sm-9">
                            <textarea name="configuration_meta_desc" class="form-control no-resize" rows="6"><?php echo isset($configuration->configuration_meta_desc)?$configuration->configuration_meta_desc:""?></textarea>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="configuration_id" value="<?php echo isset($configuration->configuration_id)?$configuration->configuration_id:""?>">
                            <input type="submit" class="btn btn-danger" value="Update" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
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