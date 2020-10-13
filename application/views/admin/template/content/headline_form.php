<?php get_data_table() ?>

<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI BERITA UTAMA</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul 1</label>
                        <div class="col-sm-9">
                            <input type="text" name="headline_title" value="<?php echo isset($val->headline_title)?$val->headline_title:'';?>" class="form-control" maxlength="255" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul 2</label>
                        <div class="col-sm-9">
                            <input type="text" name="headline_title_2" value="<?php echo isset($val->headline_title_2)?$val->headline_title_2:'';?>" class="form-control" maxlength="255" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul 3</label>
                        <div class="col-sm-9">
                            <input type="text" name="headline_title_3" value="<?php echo isset($val->headline_title_3)?$val->headline_title_3:'';?>" class="form-control" maxlength="255" >
                        </div>
                    </div>
                    <div class="form-group hide">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="headline_desc" class="form-control no-resize" rows="6"><?php echo isset($val->headline_desc)?$val->headline_desc:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images Desktop <span class="req">*</span></label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->headline_images) && trim($val->headline_images)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/headline/large/".$val->headline_images);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/headline/large/".$val->headline_images);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="headline_images" onChange="previewImages(this);" accept="image/*" <?php if(trim($val->headline_id)=="" || trim($val->headline_images)==""){ echo 'required'; } ?> />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                            <p class="help-block"><?php echo $this->image_size_str;?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images Mobile <span class="req">*</span></label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->headline_images_mobile) && trim($val->headline_images_mobile)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/headline/large/".$val->headline_images_mobile);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/headline/large/".$val->headline_images_mobile);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="headline_images_mobile" onChange="previewImages(this);" accept="image/*" <?php if(trim($val->headline_id)=="" || trim($val->headline_images_mobile)==""){ echo 'required'; } ?> />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images_mobile" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                            <p class="help-block"><?php echo $this->image_size_str_mobile;?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images Tambahan</label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->headline_images_child) && trim($val->headline_images_child)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/headline/large/".$val->headline_images_child);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/headline/large/".$val->headline_images_child);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="headline_images_child" onChange="previewImages(this);" accept="image/*" />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images_child" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                        </div>
                    </div>

                    <!-- GENERATE LINK -->
                    <?php
                    $get_link_type = get_link_type();
                    $link_type  = isset($val->link_type)?$val->link_type:'';
                    ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Link</label>
                        <div class="col-sm-9">
                            <select name="link_type" class="form-control">
                                <?php foreach ($get_link_type as $k => $v){ ?>
                                <option value="<?php echo $v['id'] ?>" <?php echo ($link_type == $v['id'])?" selected='selected'":""; ?> data-type="<?php echo $v['type'] ?>"><?php echo $v['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <?php foreach ($get_link_type as $k => $v){ ?>
                    <div class="form-group link_type_item" id="<?php echo $v['type'] ?>">
                        <label class="col-sm-3 control-label"><?php echo $v['name'] ?></label>
                        <div class="col-sm-9">
                        <?php $link_value = isset($val->link_value)&&($link_type==$v['id'])?$val->link_value:''; ?>
                        <?php if($v['id'] == 1 || $v['id'] == 3 || $v['id'] == 4 || $v['id'] == 5){ ?>
                            <?php
                            switch ($v['id']) {
                                case 1: $link_value = ($link_value!=''?$link_value.'. '.get_title_article($link_value):''); break;
                                case 3: $link_value = ($link_value!=''?$link_value.'. '.get_title_product($link_value):''); break;
                                case 4: $link_value = ($link_value!=''?$link_value.'. '.get_product_category_name($link_value):''); break;
                                case 5: $link_value = ($link_value!=''?$link_value.'. '.get_gallery_name($link_value):''); break;
                                default: $link_value = ''; break;
                            }
                            ?>
                            <div class="input-group">
                                <input type="text" name="link_value[<?php echo $v['id'] ?>]" value="<?php echo $link_value; ?>" class="form-control" readonly>
                                <span class="input-group-btn magnific-popup-ajax">
                                    <a href="#<?php echo $v['form'] ?>" class="btn btn-primary" data-effect="mfp-zoom-in" data-ajax-url="<?php echo base_url().'ajax-function/get_ajax_link/'.$v['id']?>">Pilih <?php echo $v['name'] ?></a>
                                </span>
                            </div>
                        <?php } else if($v['id'] == 2){ ?>
                            <select name="link_value[<?php echo $v['id'] ?>]" class="form-control">
                                <option value="0" selected>No Category Parent</option>
                                <?php echo get_category_parent($link_value); ?>
                            </select>
                        <?php } else if($v['id'] == 9){ ?>
                            <input type="text" name="link_value[<?php echo $v['id'] ?>]" value="<?php echo $link_value; ?>" class="form-control">
                        <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- /GENERATE LINK -->

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="onoffswitch">
                                <input type="checkbox" name="headline_status" class="onoffswitch-checkbox" id="headline_status" <?php echo isset($val->headline_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->headline_id."'".')"':''?> value="1" <?php echo (isset($val->headline_status) && $val->headline_status=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="headline_status">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <legend>Pengaturan</legend>
                            <?php
                            $headline_setting = json_decode($val->headline_setting);
                            ?>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Posisi Teks</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_textposition" class="form-control" value="<?php echo $headline_setting->textposition ?>" placeholder="left">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Teks Align</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_textalign" class="form-control" value="<?php echo $headline_setting->textalign ?>" placeholder="text-left">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 1 Data-x</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title1datax" class="form-control" value="<?php echo $headline_setting->title1datax ?>" placeholder="150">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 1 Data-y</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title1datay" class="form-control" value="<?php echo $headline_setting->title1datay ?>" placeholder="center">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 1 Data-hoffset</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title1datahoffset" class="form-control" value="<?php echo $headline_setting->title1datahoffset ?>" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 1 Data-voffset</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title1datavoffset" class="form-control" value="<?php echo $headline_setting->title1datavoffset ?>" placeholder="-150">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 2 Data-x</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title2datax" class="form-control" value="<?php echo $headline_setting->title2datax ?>" placeholder="150">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 2 Data-y</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title2datay" class="form-control" value="<?php echo $headline_setting->title2datay ?>" placeholder="center">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 2 Data-hoffset</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title2datahoffset" class="form-control" value="<?php echo $headline_setting->title2datahoffset ?>" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 2 Data-voffset</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title2datavoffset" class="form-control" value="<?php echo $headline_setting->title2datavoffset ?>" placeholder="-100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 3 Data-x</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title3datax" class="form-control" value="<?php echo $headline_setting->title3datax ?>" placeholder="150">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 3 Data-y</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title3datay" class="form-control" value="<?php echo $headline_setting->title3datay ?>" placeholder="center">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 3 Data-hoffset</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title3datahoffset" class="form-control" value="<?php echo $headline_setting->title3datahoffset ?>" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Title 3 Data-voffset</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_title3datavoffset" class="form-control" value="<?php echo $headline_setting->title3datavoffset ?>" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Btn Data-x</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_buttondatax" class="form-control" value="<?php echo $headline_setting->buttondatax ?>" placeholder="150">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Btn Data-y</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_buttondatay" class="form-control" value="<?php echo $headline_setting->buttondatay ?>" placeholder="center">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Btn Data-hoffset</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_buttondatahoffset" class="form-control" value="<?php echo $headline_setting->buttondatahoffset ?>" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-sm-12">Btn Data-voffset</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="headline_setting_buttondatavoffset" class="form-control" value="<?php echo $headline_setting->buttondatavoffset ?>" placeholder="75">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="headline_id" value="<?php echo isset($val->headline_id)?$val->headline_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->headline_id!=''?'/view/'.$val->headline_id.'-'.changeEnUrl($val->headline_title):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- GENERATE POPUP LINK -->
    <div id="form-article" class="white-popup wide mfp-with-anim mfp-hide">
        <legend><strong>Pilih Artikel</strong></legend>
        <div class="table-responsive">
            <table class="table table-th-block table-dark">
                <colgroup>
                    <col width="1">
                    <col>
                    <col width="1">
                    <col width="1">
                    <col width="1">
                </colgroup>
                <thead>
                    <tr>
                        <th class="nobr">ID</th>
                        <th class="nobr">Judul</th>
                        <th class="nobr">Kategori</th>
                        <th class="nobr">Status</th>
                        <th class="nobr text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="popup-ajax-result">

                </tbody>
            </table>
        </div>
    </div>

    <div id="form-produk" class="white-popup wide mfp-with-anim mfp-hide">
        <legend><strong>Pilih Produk</strong></legend>
        <div class="table-responsive">
            <table class="table table-th-block table-dark">
                <colgroup>
                    <col width="1">
                    <col>
                    <col width="1">
                    <col width="1">
                    <col width="1">
                </colgroup>
                <thead>
                    <tr>
                        <th class="nobr">ID</th>
                        <th class="nobr">Judul</th>
                        <th class="nobr">Kategori</th>
                        <th class="nobr">Status</th>
                        <th class="nobr text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="popup-ajax-result">

                </tbody>
            </table>
        </div>
    </div>

    <div id="form-category-produk" class="white-popup wide mfp-with-anim mfp-hide">
        <legend><strong>Pilih Category Produk</strong></legend>
        <div class="table-responsive">
            <table class="table table-th-block table-dark">
                <colgroup>
                    <col width="1">
                    <col>
                    <col width="1">
                    <col width="1">
                </colgroup>
                <thead>
                    <tr>
                        <th class="nobr">ID</th>
                        <th class="nobr">Judul</th>
                        <th class="nobr">Status</th>
                        <th class="nobr text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="popup-ajax-result">

                </tbody>
            </table>
        </div>
    </div>

    <div id="form-gallery" class="white-popup wide mfp-with-anim mfp-hide">
        <legend><strong>Pilih Gallery</strong></legend>
        <div class="table-responsive">
            <table class="table table-th-block table-dark">
                <colgroup>
                    <col width="1">
                    <col width="1">
                    <col>
                    <col width="1">
                    <col width="1">
                </colgroup>
                <thead>
                    <tr>
                        <th class="nobr">ID</th>
                        <th class="nobr">Images</th>
                        <th>Judul</th>
                        <th class="nobr">Status</th>
                        <th class="nobr text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="popup-ajax-result">

                </tbody>
            </table>
        </div>
    </div>

    <style type="text/css">
        .link_type_item { display: none; }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('change', 'select[name="link_type"]', function(e){
                e.preventDefault();
                var $this = $(this);
                var val = $this.find(':selected').val();
                var type = $this.find(':selected').data('type');

                $('.link_type_item').hide();
                $('#'+type).show();
            });
            $('select[name="link_type"]').change();

        });

        function setArticle(val,id,form){
            $('input[name="link_value['+id+']"]').attr('value',val);
            $('#'+form+' .mfp-close').trigger('click');
        };
    </script>
    <!-- GENERATE POPUP LINK -->

</div>