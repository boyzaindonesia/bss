<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action" role="toolbar">
        <div class="btn-group">
            <a href="<?php echo $own_links ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back to inbox</a>
        </div>

        <?php if(check_action($links_table_item,'delete')){ ?>
            <div class="btn-group pull-right">
                <a href="<?php echo $own_links.'/delete/'.$val->testimonial_id.'-'.changeEnUrl($val->testimonial_name);?>" data-toggle="tooltip" title="Delete" onClick="return confirm('Are you sure you want to delete this data?');" class="btn btn-warning"><i class="fa fa-trash-o"></i></a>
            </div>
        <?php } ?>
        <?php if(check_action($links_table_item,'edit')){ ?>
            <div class="btn-group pull-right">
                <a href="<?php echo $own_links.'/edit/'.$val->testimonial_id.'-'.changeEnUrl($val->testimonial_name);?>" data-toggle="tooltip" title="Edit" class="btn btn-success"><i class="fa fa-pencil"></i></a>
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
            <!-- <h4>SUBJECT : -</h4> -->
            <div class="panel panel-transparent panel-square">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="block-collapse" data-toggle="collapse" href="#read-mail-example-1">
                            <span class="avatar avatar-mail <?php echo getAvatarMail($val->testimonial_name) ?>"><?php echo getCharMail($val->testimonial_name) ?></span>
                            <strong><?php echo ucwords($val->testimonial_name) ?></strong> to me
                            <span class="right-content">
                                <span class="time"><?php echo convDateTimeTable($val->testimonial_date) ?></span>
                            </span>
                        </a>
                    </h3>
                </div>
                <div id="read-mail-example-1" class="collapse in">
                    <div class="panel-body">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                Detail
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <ul class="mail-info-detail">
                                        <li>From : <strong><?php echo ucwords($val->testimonial_name) ?></strong></li>
                                        <li>Email : <strong class="text-danger"><a href="mailto:<?php echo $val->testimonial_email ?>"><?php echo $val->testimonial_email ?></a></strong></li>
                                        <li>Sent : <?php echo convDateTimeTable($val->testimonial_date) ?></li>
                                        <!-- <li>Subject :  <strong class="text-info">-</strong></li> -->
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <br />
                        <br />
                        <?php echo $val->testimonial_desc ?>
                    </div>
                    <div class="panel-footer">
                        <p><strong>Status : <?php echo ($val->testimonial_status=='0'?'<span class="text-danger">Waiting Approved</span>':'Approved') ?></strong></p>

                        <?php if($val->testimonial_status=='0'){ ?>
                        <form class="ajax_default" method="post" action="<?php echo $own_links?>/approved" enctype="multipart/form-data" accept-charset="UTF-8" >
                            <input type="hidden" name="thisId" value="<?php echo $val->testimonial_id ?>" />
                            <input type="hidden" name="thisIdDetail" value="" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" class="btn btn-primary">Approved</button>
                        </form>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>