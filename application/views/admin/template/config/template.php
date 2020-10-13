<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="container-masonry">
            <ol>
              <?php 
              if(!empty($templatelist)){
                  foreach($templatelist as $key => $t){ ?>
                <li class="item-masonry">
                    <div class="the-box no-border full text-center mansory-inner">
                        <div class="magnific-popup-wrap">
                            <a class="zooming" href="<?php echo themeDefaultUrl().$key?>/large.png"><img src="<?php echo themeDefaultUrl().$key?>/thumb.png" class="item-image mfp-fade" alt="<?php echo $key?>"></a>
                        </div>
                        <div class="the-box text-left no-margin no-border">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="template_name" value="<?php echo $key?>" <?php echo (isset($template->template_name)&& $template->template_name==$key)?'checked="checked"':'';?> > <?php echo $key?>
                                </label>
                            </div>
                        </div>
                    </div>
                </li>
                <?php 
                    }
                } 
                ?>
            </ol>
        </div>

        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="hidden" name="template_id" value="<?php echo isset($template->template_id)?$template->template_id:""?>">
                <input type="submit" class="btn btn-danger" value="Save" />
                <input type="reset" class="btn btn-default" value="Reset" />
                <a href="<?php echo base_url("admin/me");?>"><div class="btn btn-default pull-right">Back</div></a>
            </div>
        </div>
    </form>

</div>