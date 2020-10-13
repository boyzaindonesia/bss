<div class="the-box no-border">

    <?php
    $detail = array('0' => 'overview',
                    '1' => 'site plan',
                    '2' => 'floor plan',
                    '3' => 'spesifikasi unit',
                    '4' => 'unit booking',
                    '5' => 'term & condition'
                );

    $isShow = array('1' => true, '2' => true, '3' => true, '4' => true );
    switch ($product_detail_type) {
        case '0': $isShow = array('1' => true, '2' => false, '3' => false, '4' => true ); break;
        case '1': $isShow = array('1' => true, '2' => false, '3' => false, '4' => true ); break;
        case '2': $isShow = array('1' => true, '2' => false, '3' => false, '4' => true ); break;
        case '3': $isShow = array('1' => true, '2' => false, '3' => true, '4' => false ); break;
        case '4': $isShow = array('1' => true, '2' => true, '3' => false, '4' => false ); break;
        case '5': $isShow = array('1' => false, '2' => true, '3' => false, '4' => false ); break;
        default: break;
    }
    ?>
    <form action="<?php echo $own_links;?>/save_detail" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase"><?php echo strtoupper($detail[$product_detail_type]) ?></h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">

                    <?php if($isShow[1]){ ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" name="product_detail_name" value="<?php echo isset($product_detail->product_detail_name)?$product_detail->product_detail_name:'';?>" class="form-control" maxlength="255" required>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($isShow[2]){ ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="product_detail_desc" class="form-control" rows="6" required><?php echo isset($product_detail->product_detail_desc)?$product_detail->product_detail_desc:'';?></textarea>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($isShow[3]){ ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div id="arr_desc" class="col-sm-9">
                            <?php 
                            $arr_desc = json_decode($product_detail->product_detail_desc);
                            foreach ($arr_desc as $key => $value) { ?>
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <input type="text" name="arr_name[]" value="<?php echo $value->name ?>" class="form-control" maxlength="255" required>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="arr_desc[]" value="<?php echo $value->desc ?>" class="form-control" maxlength="255" required>
                                </div>
                                <div class="btn btn-remove-desc-array btn-xs" style="position: absolute; top:7px; right: 20px; z-index: 5;"><i class="fa fa-times"></i></div>
                            </div>
                            <?php } ?>
                        </div>
                        <label class="col-sm-3"></label>
                        <div class="col-sm-9">
                            <div class="btn btn-add-desc-array btn-primary">Add More Deskripsi</div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $(document).on('click', '.btn-remove-desc-array', function(e){
                                    e.preventDefault();
                                    $(this).parent().remove();
                                });
                                $(document).on('click', '.btn-add-desc-array', addDescArray );
                            });

                            function addDescArray() {
                                var $layout = '<div class="form-group">';
                                     $layout += '<div class="col-sm-3">';
                                        $layout += '<input type="text" name="arr_name[]" value="" class="form-control" maxlength="255" required>';
                                    $layout += '</div>';
                                    $layout += '<div class="col-sm-9">';
                                        $layout += '<input type="text" name="arr_desc[]" value="" class="form-control" maxlength="255" required>';
                                    $layout += '</div>';
                                    $layout += '<div class="btn btn-remove-desc-array btn-xs" style="position: absolute; top:7px; right: 20px; z-index: 5;"><i class="fa fa-times"></i></div>';
                                $layout += '</div>';

                                $('#arr_desc').append($layout);
                            }
                            <?php if(count($arr_desc)==0){ ?>
                                addDescArray();
                            <?php } ?>
                        </script>
                    </div>
                    <?php } ?>

                    <?php if($isShow[4]){ ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($product_detail->product_detail_images) && trim($product_detail->product_detail_images)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".$product_detail->product_detail_images);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".$product_detail->product_detail_images);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="product_detail_images" onChange="previewImages(this);" accept="image/*" <?php echo isset($product_detail->product_detail_id)&&$product_detail->product_detail_id!=''?'':'required';?> />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                            <p class="help-block"><?php echo $this->image_size_str;?></p>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="product_detail_id" value="<?php echo isset($product_detail->product_detail_id)?$product_detail->product_detail_id:'';?>" />
                            <input type="hidden" name="product_id" value="<?php echo isset($val->product_id)?$val->product_id:'';?>" />
                            <input type="hidden" name="product_detail_type" value="<?php echo isset($product_detail_type)?$product_detail_type:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <input type="reset" name="reset" class="btn btn-default" value="Reset" />
                            <a href="<?php echo $own_links.($val->product_id!=''?'/detail/'.$val->product_id.'-'.changeEnUrl($val->product_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>