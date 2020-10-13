<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI ARTIKEL</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->article_publishdate)?convDateTimeTable($val->article_publishdate):'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Penulis</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <?php
                                    $article_user_id = isset($val)?$val->article_user_id:'';
                                    echo get_user_name($article_user_id);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kategori</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <?php
                                    $parentId = isset($val)?$val->article_category_id:'';
                                    echo get_root_category_parent($parentId);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Judul</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->article_title)?$val->article_title:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ringkasan</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->article_lead)?$val->article_lead:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Isi</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->article_content)?$val->article_content:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Images</label>
                        <div class="col-sm-5 magnific-popup-wrap form-preview-images">
                            <div class="form-control">
                                <?php if( isset($val->article_image) && trim($val->article_image)!="" ){ ?>
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/article/small/".$val->article_image);?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/article/thumb/".$val->article_image);?>" alt="" class="mfp-fade item-gallery img-responsive">
                                </a>
                                <?php } else { ?>
                                <div style="position: relative;">
                                    <img src="<?php echo get_image(base_url()."/none");?>" alt="" class="media-object img-responsive return-preview-images">
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Video Youtube</label>
                        <div class="col-sm-5 ">
                            <div class="form-control">
                                <?php $article_video = isset($val->article_video)?$val->article_video:'';?>
                                <?php if($article_video!=''){ ?>
                                <iframe width="386" height="216" src="https://www.youtube.com/embed/<?php echo $article_video ?>?rel=0" frameborder="0"></iframe>
                                <?php } else { ?>
                                -
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">File</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <?php $files = isset($val->files)?$val->files:'';?>
                                <?php if($files!=''){ ?>
                                <a href="<?php echo base_url().'assets/collections/doc/'.$files ?>" target="_blank"><?php echo $files ?></a>
                                <?php } else { ?>
                                -
                                <?php } ?>
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
                            <div class="form-control"><?php echo rtrim($result_tags," ") ?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Keywords</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->article_meta_keywords)?$val->article_meta_keywords:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Meta Description</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->article_meta_description)?$val->article_meta_description:'';?></div>
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
                            <div class="form-control">
                                <div class="onoffswitch no-margin-top">
                                    <input type="checkbox" name="article_status" class="onoffswitch-checkbox" id="article_status" <?php echo isset($val->article_id)?'onclick="changeStatus(this,'."'".$own_links."/change_status/".$val->article_id."'".')"':''?> value="1" <?php echo (isset($val->article_status) && $val->article_status=="0")?'':'checked';?> >
                                    <label class="onoffswitch-label" for="article_status">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->article_id.'-'.changeEnUrl($val->article_title);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>