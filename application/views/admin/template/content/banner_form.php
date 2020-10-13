<?php get_data_table() ?>

<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI <?php echo $val->banner_category_name ?></h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul</label>
                        <div class="col-sm-9">
                            <input type="text" name="banner_title" value="<?php echo isset($val->banner_title)?$val->banner_title:'';?>" class="form-control" maxlength="255" >
                        </div>
                    </div>
                    <div class="form-group hide">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="banner_desc" class="form-control no-resize" rows="6"><?php echo isset($val->banner_desc)?$val->banner_desc:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images Desktop <span class="req">*</span></label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->banner_images) && trim($val->banner_images)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/banner/large/".$val->banner_images);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/banner/large/".$val->banner_images);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="banner_images" onChange="previewImages(this);" accept="image/*" <?php if(trim($val->banner_id)=="" || trim($val->banner_images)==""){ echo 'required'; } ?> />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                            <p class="help-block"><?php echo $this->banner_category_size;?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images Mobile</label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->banner_images_mobile) && trim($val->banner_images_mobile)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/banner/large/".$val->banner_images_mobile);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/banner/large/".$val->banner_images_mobile);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="banner_images_mobile" onChange="previewImages(this);" accept="image/*" />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images_mobile" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                            <p class="help-block"><?php echo $this->banner_category_size;?></p>
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
                                <input type="checkbox" name="banner_status" class="onoffswitch-checkbox" id="banner_status" <?php echo isset($val->banner_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->banner_id."'".')"':''?> value="1" <?php echo (isset($val->banner_status) && $val->banner_status=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="banner_status">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="banner_id" value="<?php echo isset($val->banner_id)?$val->banner_id:'';?>" />
                            <input type="hidden" name="banner_category_id" value="<?php echo isset($val->banner_category_id)?$val->banner_category_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->banner_id!=''?'/view/'.$val->banner_id.'-'.changeEnUrl($val->banner_title):'');?>"><div class="btn btn-default pull-right">Back</div></a>
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