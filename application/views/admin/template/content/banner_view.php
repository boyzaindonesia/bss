<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->banner_title)?$val->banner_title:'';?></div>
                        </div>
                    </div>
                    <div class="form-group hide">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->banner_desc)?$val->banner_desc:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images Desktop</label>
                        <div class="col-sm-5 magnific-popup-wrap">
                            <div class="form-control">
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/banner/large/".$val->banner_images);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/banner/large/".$val->banner_images);?>" alt="" class="media-object img-responsive">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images Mobile</label>
                        <div class="col-sm-5 magnific-popup-wrap">
                            <div class="form-control">
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/banner/large/".$val->banner_images_mobile);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/banner/large/".$val->banner_images_mobile);?>" alt="" class="media-object img-responsive">
                                </a>
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
                            <select name="link_type" class="form-control" disabled="disabled">
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
                                <input type="text" name="link_value[<?php echo $v['id'] ?>]" value="<?php echo $link_value; ?>" class="form-control"  disabled="disabled">
                                <span class="input-group-btn magnific-popup-ajax">
                                    <a href="#" class="btn btn-primary" disabled="disabled">Pilih <?php echo $v['name'] ?></a>
                                </span>
                            </div>
                        <?php } else if($v['id'] == 2){ ?>
                            <select name="link_value[<?php echo $v['id'] ?>]" class="form-control" disabled="disabled">
                                <option value="0" selected>No Category Parent</option>
                                <?php echo get_category_parent($link_value); ?>
                            </select>
                        <?php } else if($v['id'] == 9){ ?>
                            <input type="text" name="link_value[<?php echo $v['id'] ?>]" value="<?php echo $link_value; ?>" class="form-control" disabled="disabled">
                        <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- /GENERATE LINK -->

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="onoffswitch no-margin-top">
                                    <input type="checkbox" name="banner_status" class="onoffswitch-checkbox" id="banner_status" <?php echo isset($val->banner_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->banner_id."'".')"':''?> value="1" <?php echo (isset($val->banner_status) && $val->banner_status=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="banner_status">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->banner_id.'-'.changeEnUrl($val->banner_title);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- GENERATE POPUP LINK -->
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
    </script>
    <!-- /GENERATE POPUP LINK -->

</div>