<div class="the-box no-border">

    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group">
            <a href="<?php echo $own_links ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back to inbox</a>
        </div>

        <?php if(check_action($links_table_item,'delete')){ ?>
            <div class="btn-group pull-right">
                <a href="<?php echo $own_links.'/delete/'.$val->message_id.'-'.changeEnUrl($val->message_name);?>" data-toggle="tooltip" title="Delete" onClick="return confirm('Are you sure you want to delete this data?');" class="btn btn-warning"><i class="fa fa-trash-o"></i></a>
            </div>
        <?php } ?>
    </div>

    <div class="row mail-apps-wrap">
        <div class="col-sm-4 col-md-3">
            <div class="list-group danger square no-border">
                <?php $notify = get_count_message() ?>
                <a href="<?php echo $own_links ?>" class="list-group-item active">Inbox <?php echo ($notify>0?'<span class="badge badge-success">'.$notify.'</span>':'') ?></a>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">
            <h4>SUBJECT : <?php echo strtoupper($val->message_subject) ?></h4>
            <div class="panel panel-transparent panel-square">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="block-collapse" data-toggle="collapse" href="#read-mail-example-1">
                            <span class="avatar avatar-mail <?php echo getAvatarMail($val->message_name) ?>"><?php echo getCharMail($val->message_name) ?></span>
                            <strong><?php echo ucwords($val->message_name) ?></strong> to me
                            <span class="right-content">
                                <span class="time"><?php echo convDateTimeTable($val->message_date) ?></span>
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
                                        <li>From : <strong><?php echo ucwords($val->message_name) ?></strong></li>
                                        <li>Email : <strong class="text-danger"><a href="mailto:<?php echo $val->message_email ?>"><?php echo $val->message_email ?></a></strong></li>
                                        <li>Sent : <?php echo convDateTimeTable($val->message_date) ?></li>
                                        <li>Subject :  <strong class="text-info"><?php echo $val->message_subject ?></strong></li>
                                        <li>Phone :  <strong><?php echo $val->message_phone ?></strong></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <br />
                        <br />
                        <?php echo $val->message_desc ?>
                    </div>
                    <div class="panel-footer">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>