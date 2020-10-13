<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI GALERI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal</label>
                        <?php
                            $date = isset($val->gallery_date) ? date("Y-m-d H:i",strtotime($val->gallery_date)) : date("Y-m-d H:i");
                            $explode_date = explode(" ",$date);
                        ?>
                        <div class="col-sm-3">
                            <input type="text" name="pdate" class="form-control datepicker" value="<?php echo convDatepickerEnc($explode_date[0]) ?>" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                        </div>
                        <div class="col-sm-2" style="padding-left: 0px;">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input type="text" name="phour" class="form-control timepicker" value="<?php echo convTimepickerEnc($explode_date[1]) ?>">
                                <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul</label>
                        <div class="col-sm-9">
                            <input type="text" name="gallery_name" value="<?php echo isset($val->gallery_name)?$val->gallery_name:'';?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="gallery_desc" class="form-control tinymce" rows="6"><?php echo isset($val->gallery_desc)?$val->gallery_desc:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images <span class="req">*</span></label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->gallery_images) && trim($val->gallery_images)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/gallery/small/".$val->gallery_images);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/gallery/thumb/".$val->gallery_images);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="gallery_images" onChange="previewImages(this);" accept="image/*" <?php if(trim($val->gallery_id)=="" || trim($val->gallery_images)==""){ echo 'required'; } ?> />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                                <input type="hidden" name="remove_images" class="remove_images" value="" data-images-default="<?php echo get_image(base_url()."/none");?>">
                            </div>
                            <p class="help-block"><?php echo $this->image_size_str;?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Video Youtube</label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="text" name="gallery_video" class="form-control" value="<?php echo isset($val->gallery_video)?$val->gallery_video:'';?>" placeholder="anMYu17aZT4" />
                                </div>
                            </div>
                            <p class="help-block">Example: Get url address your video youtube <br/><del>https://www.youtube.com/watch?v=</del> <strong style="color: #E9573F;">anMYu17aZT4</strong></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="onoffswitch">
                                <input type="checkbox" name="gallery_status" class="onoffswitch-checkbox" id="gallery_status" <?php echo isset($val->gallery_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->gallery_id."'".')"':''?> value="1" <?php echo (isset($val->gallery_status) && $val->gallery_status=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="gallery_status">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Image Detail</label>
                        <div class="col-sm-9">
                            <div class="fullwidth">
                                <div class="btn btn-info" onclick="_addItem();"><i class="fa fa-plus"></i> Tambah</div>
                            </div>
                            <div class="container-masonry mansory-float">
                                <ol id="items-masonry" class="magnific-popup-wrap dragsort" data-url-dragsort="<?php echo $own_links?>/change_position_images_detail">

                                    <?php if(!isset($gallery_detail)||$gallery_detail==''){ 
                                        $tempToken = generateUniqueToken('20','mt_gallery','gallery_id');
                                    ?>
                                    <li class="item-masonry dragsortitem" id="item_<?php echo $tempToken?>" data-itemid="<?php echo $tempToken?>">
                                        <div class="the-box no-border full text-center mansory-inner">
                                            <div class="item-image form-preview-images">
                                                <img src="<?php echo get_image(base_url()."assets/collections/gallery/");?>" class="media-object img-responsive return-preview-images">
                                                <div class="btn btn-info btn-file">
                                                    <i class="fa fa-upload"></i>
                                                    <input type="file" name="file_<?php echo $tempToken?>" onChange="previewImages(this);" accept="image/*" required />
                                                </div>
                                            </div>
                                            <div class="the-box bg-dark no-margin no-border">
                                                <input type="text" name="desc[<?php echo $tempToken?>]" class="form-control" value="" placeholder="Caption">
                                                <a href="javascript:void(0);" onClick=_hapusItem("<?php echo $tempToken?>") class="btn btn-danger btn-xs" title="Hapus"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } ?>

                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="gallery_id" value="<?php echo isset($val->gallery_id)?$val->gallery_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <input type="reset" name="btn_reset" class="btn btn-default" value="Reset">
                            <a href="<?php echo $own_links.($val->gallery_id!=''?'/view/'.$val->gallery_id.'-'.changeEnUrl($val->gallery_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        var gallery_images = [];
        $(document).ready(function(){
            <?php if(isset($gallery_detail)){ ?>
            gallery_images = eval('<?php echo json_encode($gallery_detail);?>');
            <?php } ?>
            if(gallery_images.length > 0){
                for(i=0;i<gallery_images.length;i++){
                    _addItem({
                        id:gallery_images[i].gallery_detail_id,
                        desc:gallery_images[i].gallery_detail_name,
                        url:gallery_images[i].gallery_detail_images
                    });
                }
            }
        });

        function _addItem(val){
            idx='A'+Math.round(Math.random()*10000000000000000000);
            if(typeof val == 'undefined'){
                val = {id:idx,desc:'',url:''};
            }
            html = _createHtml(val);
            $('#items-masonry').append(html);
            $('#item_'+idx+' input[type="file"]').trigger('click');
        }

        function _createHtml(o){
            required = 'required';
            gallery_URL = '<?php echo base_url()."assets/collections/gallery/";?>';
            gallery_thumb = '<?php echo get_image(base_url()."/none");?>';
            if($.trim(o.url) != ''){
                gallery_thumb = gallery_URL+'thumb/'+o.url;
                required = '';
            }

            html  = '<li class="item-masonry dragsortitem" id="item_'+o.id+'" data-itemid="'+o.id+'">';
                html += '<div class="the-box no-border full text-center mansory-inner">';
                    html += '<div class="item-image form-preview-images">';
                        html += '<img src="'+gallery_thumb+'" class="media-object img-responsive return-preview-images">';
                        html += '<div class="btn btn-info btn-file">';
                            html += '<i class="fa fa-upload"></i>';
                            html += '<input type="file" name="file_'+o.id+'" onChange="previewImages(this);" accept="image/*" '+required+'/>';
                        html += '</div>';
                    html += '</div>';
                    if($.trim(o.url) != ''){
                    html += '<div class="the-box bg-dark no-margin no-border btn-drag" title="Drag to Move position">';
                    } else {
                    html += '<div class="the-box bg-dark no-margin no-border">';
                    }
                        html += '<input type="text" name="desc['+o.id+']" class="form-control" value="'+o.desc+'" placeholder="Caption">';
                        html += '<div class="btn-group">';
                            if($.trim(o.url) != ''){
                            html += '<a href="'+gallery_URL+'small/'+o.url+'" class="zooming btn btn-info btn-xs" title="'+o.desc+'"><i class="fa fa-search-plus" title="Zoom"></i></a>';
                            }
                            html += '<a href="javascript:void(0);" onClick=_hapusItem("'+o.id+'") class="btn btn-danger btn-xs" title="Hapus"><i class="fa fa-times"></i></a>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            html += '</li>';
            return html;
        }

        function _hapusItem(id){
            $('#item_'+id).fadeOut("medium",function(){
                $(this).remove();
            });
        }

    </script>

    <?php js_picker() ?>
    <?php js_timepicker() ?>
    <?php getTinymce() ?>

    <!-- dragsort -->
    <?php js_dragsort() ?>

</div>