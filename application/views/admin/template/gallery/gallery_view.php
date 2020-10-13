<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI GALERI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->gallery_date)?convDateTimeTable($val->gallery_date):'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->gallery_name)?$val->gallery_name:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->gallery_desc)?$val->gallery_desc:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-5 magnific-popup-wrap">
                            <div class="form-control">
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/gallery/small/".$val->gallery_images);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/gallery/thumb/".$val->gallery_images);?>" alt="" class="media-object img-responsive">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Video Youtube</label>
                        <div class="col-sm-5 ">
                            <div class="form-control">
                                <?php $gallery_video = isset($val->gallery_video)?$val->gallery_video:'';?>
                                <?php if($gallery_video!=''){ ?>
                                <iframe width="386" height="216" src="https://www.youtube.com/embed/<?php echo $gallery_video ?>?rel=0" frameborder="0"></iframe>
                                <?php } else { ?>
                                -
                                <?php } ?> 
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="onoffswitch no-margin-top">
                                    <input type="checkbox" name="gallery_status" class="onoffswitch-checkbox" id="gallery_status" <?php echo isset($val->gallery_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->gallery_id."'".')"':''?> value="1" <?php echo (isset($val->gallery_status) && $val->gallery_status=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="gallery_status">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images Detail</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <div class="container-masonry mansory-float">
                                    <ol id="items-masonry" class="magnific-popup-wrap dragsort" data-url-dragsort="<?php echo $own_links?>/change_position_images_detail">

                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->gallery_id.'-'.changeEnUrl($val->gallery_name);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
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
            idx=Math.round(Math.random()*10000000000000000000);
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
                        html += '<a href="'+gallery_URL+'small/'+o.url+'" class="zooming">';
                            html += '<div class="btn btn-info btn-file">';
                                html += '<i class="fa fa-search-plus"></i>';
                            html += '</div>';
                        html += '</a>';
                    html += '</div>';
                    if($.trim(o.url) != ''){
                    html += '<div class="the-box bg-dark no-margin no-border btn-drag" title="Drag to Move position">';
                    } else {
                    html += '<div class="the-box bg-dark no-margin no-border">';
                    }
                        html += '<div class="form-control">'+o.desc+'</div>';
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

    <!-- dragsort -->
    <?php js_dragsort() ?>

</div>