<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group">
            <a href="<?php echo $own_links ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back to inbox</a>
        </div>

        <?php if(check_action($links_table_item,'delete') && (isset($val->testimonial_id)&&$val->testimonial_id!='')){ ?>
            <div class="btn-group pull-right">
                <a href="<?php echo $own_links.'/delete/'.$val->testimonial_id.'-'.changeEnUrl($val->testimonial_name);?>" data-toggle="tooltip" title="Delete" onClick="return confirm('Are you sure you want to delete this data?');" class="btn btn-warning"><i class="fa fa-trash-o"></i></a>
            </div>
        <?php } ?>
    </div>

    <div class="row mail-apps-wrap">
        <div class="col-sm-4 col-md-3">
            <div class="list-group danger square no-border">
                <?php $notify = get_count_testimonial() ?>
                <a href="<?php echo $own_links ?>" class="list-group-item active">Inbox <?php echo ($notify>0?'<span class="badge badge-success">'.$notify.'</span>':'') ?></a>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">

            <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="testimonial_name" value="<?php echo isset($val->testimonial_name)?$val->testimonial_name:'';?>" class="form-control input-lg" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="testimonial_email" value="<?php echo isset($val->testimonial_email)?$val->testimonial_email:'';?>" class="form-control input-lg" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="testimonial_desc" class="form-control" rows="6"><?php echo $val->testimonial_desc ?></textarea>
                </div>
                <div class="form-group" <?php echo isset($val) && $val->testimonial_status=="1"?'style="display: none;"':'';?> >
                    <div class="checkbox">
                      <label><input type="checkbox" name="testimonial_status" value="1" <?php echo isset($val) && $val->testimonial_status=="0"?'':'checked';?> > Approved</label>
                    </div>
                </div>
                <div class="form-group form-action clearfix">
                    <input type="hidden" name="testimonial_id" value="<?php echo isset($val->testimonial_id)?$val->testimonial_id:'';?>" />
                    <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                    <a href="<?php echo $own_links.'/view/'.$val->testimonial_id.'-'.changeEnUrl($val->testimonial_name);?>"><div class="btn btn-default">Discard</div></a>
                </div>
            </form>

        </div>
    </div>

</div>