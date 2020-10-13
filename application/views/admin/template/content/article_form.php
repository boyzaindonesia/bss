<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI ARTIKEL</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal <span class="req">*</span></label>
                        <?php
                            $date = isset($val->article_publishdate) ? date("Y-m-d H:i",strtotime($val->article_publishdate)) : date("Y-m-d H:i");
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
                        <label class="col-sm-3 control-label">Penulis <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <select id="article_user_id" name="article_user_id" class="form-control" required>
                                <option value="" selected>--- SELECT ---</option>
                                <?php
                                    $article_user_id = isset($val)?$val->article_user_id:$this->user_id;
                                    $user_list = get_user_list();
                                    foreach ($user_list as $k => $v) {
                                        echo '<option value="'.$v->user_id.'" '.($article_user_id==$v->user_id?'selected':'').'>'.$v->user_fullname.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kategori <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <select id="article_category_id" name="article_category_id" class="form-control" required>
                                <option value="" selected>--- SELECT ---</option>
                                <?php
                                    $parentId = isset($val)?$val->article_category_id:'';
                                    echo get_category_parent($parentId);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="article_title" class="form-control" value="<?php echo isset($val->article_title)?$val->article_title:'';?>" maxlength="255" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ringkasan</label>
                        <div class="col-sm-9">
                            <textarea name="article_lead" class="form-control no-resize" rows="3" maxlength="300"><?php echo isset($val->article_lead)?$val->article_lead:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Isi</label>
                        <div class="col-sm-9">
                            <textarea name="article_content" class="form-control no-resize tinymce" rows="6"><?php echo isset($val->article_content)?$val->article_content:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($val->article_image) && trim($val->article_image)!="" ){ ?>
                                <div class="right-action">
                                    <div class="btn btn-remove-images btn-xs" data-toggle="tooltip" data-original-title="Remove Images"><i class="fa fa-times"></i></div>
                                </div>
                                <?php } ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/article/small/".$val->article_image);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/article/thumb/".$val->article_image);?>" alt="" class="media-object img-responsive return-preview-images">
                                </a>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="article_image" onChange="previewImages(this);" accept="image/*" />
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
                                    <input type="text" name="article_video" class="form-control" value="<?php echo isset($val->article_video)?$val->article_video:'';?>" placeholder="anMYu17aZT4" />
                                </div>
                            </div>
                            <p class="help-block">Example: Get url address your video youtube <br/><del>https://www.youtube.com/watch?v=</del> <strong style="color: #E9573F;">anMYu17aZT4</strong></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">File</label>
                        <div class="col-sm-5">
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
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tagged</label>
                        <div class="col-sm-9">
                            <?php
                            $result_tags  = "";
                            $article_tags = isset($val->article_tags)?$val->article_tags:'';
                            $exp_tags     = explode(",", $article_tags);
                            foreach ($exp_tags as $n) { if($n != ""){ $result_tags .= "#".$n." "; } }
                            ?>
                            <input type="text" name="hashtags" id="hashtags" class="form-control" value="<?php echo rtrim($result_tags," ") ?>">
                            <script type="text/javascript">
                                var a = document.getElementById('hashtags');
                                a.addEventListener('keydown', addHash, false);

                                function addHash(event) {
                                    if (event.keyCode === 32 && event.target.value.length) {
                                        event.preventDefault();

                                        var elem = event.target,
                                            val = elem.value;

                                        if (val.slice(-1) !== '#') {
                                            elem.value += ' #';
                                        }
                                    } else if (!event.target.value.length) {
                                        if (event.keyCode === 32) {
                                            event.preventDefault();
                                        }
                                        event.target.value = '#';
                                    }
                                }
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Keywords</label>
                        <div class="col-sm-9">
                            <input type="text" name="article_meta_keywords" class="form-control" value="<?php echo isset($val->article_meta_keywords)?$val->article_meta_keywords:'';?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Description</label>
                        <div class="col-sm-9">
                            <textarea name="article_meta_description" class="form-control no-resize" rows="3" maxlength="300"><?php echo isset($val->article_meta_description)?$val->article_meta_description:'';?></textarea>
                        </div>
                    </div>
                    <?php if (isset($val->article_count)) {?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Total Dilihat</label>
                        <div class="col-sm-9">
                            <div class="form-control"><strong><?php echo $val->article_count; ?> viewers</strong></div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if (isset($val->short_url)) {?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Short URL</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo $val->short_url; ?></div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <div class="onoffswitch">
                                <input type="checkbox" name="article_status" class="onoffswitch-checkbox" id="article_status" <?php echo isset($val->article_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->article_id."'".')"':''?> value="1" <?php echo (isset($val->article_status) && $val->article_status=="0")?'':'checked';?> >
                                <label class="onoffswitch-label" for="article_status">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="article_id" value="<?php echo isset($val->article_id)?$val->article_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <input type="reset" name="reset" class="btn btn-default" value="Reset" />
                            <a href="<?php echo $own_links.($val->article_id!=''?'/view/'.$val->article_id.'-'.changeEnUrl($val->article_title):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        // $('#selecttags').click(function() {
        //      $('#disptags').show('slow');
        //     return false;
        // });
        // $('#article_section_id').change(function(){
        //     $.post("<?php echo base_url();?>toko/article/category/"+$('#article_section_id').val(),{},function(obj){
        //         $('#article_category_id').html(obj);
        //     });
        // });
    </script>

    <?php js_picker() ?>
    <?php js_timepicker() ?>
    <?php getTinymce() ?>

</div>