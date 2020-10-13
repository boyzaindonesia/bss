<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <div class="row mail-apps-wrap">
        <div class="col-sm-4 col-md-3">
            <div class="list-group danger square no-border">
                <?php $notify = get_count_testimonial() ?>
                <a href="<?php echo $own_links ?>" class="list-group-item active">Inbox <?php echo ($notify>0?'<span class="badge badge-success">'.$notify.'</span>':'') ?></a>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">
            <div class="the-box full no-border">
                <form action="<?php echo !empty($url_form)?$url_form:"";?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                    <div class="form-group has-feedback no-label">
                        <input type="search" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
                        <span class="fa fa-search form-control-feedback"></span>
                    </div>
                </form>
            </div>
            <div class="list-group success square no-side-border">

                <?php if(check_action($links_table_item,'delete')){ ?>
                <form id="form1" action="<?php echo $own_links.'/filesToDel'?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="thisAction" value="deleteSelected" />
                <?php } ?>
                    <?php 
                    if(count($data) > 0){
                        $i = 1;
                        foreach($data as $r){ 
                        if ($i%2==0){ } else { } ?>
                        <a href="<?php echo $own_links.'/view/'.$r->testimonial_id.'-'.changeEnUrl($r->testimonial_name);?>" class="list-group-item mail-list <?php echo ($r->notify!='0'?'unread':'') ?>">
                            <?php if(check_action($links_table_item,'delete')){ ?>
                            <input type="checkbox" name="filesToDel[]" value="<?php echo $r->testimonial_id ?>">
                            <?php } ?>
                            <span class="avatar avatar-mail <?php echo getAvatarMail($r->testimonial_name) ?>"><?php echo getCharMail($r->testimonial_name) ?></span>
                            <span class="name"><?php echo ucwords($r->testimonial_name) ?></span>
                            <span class="subject"><?php echo ($r->testimonial_status=='0'?'<span class="label label-danger">Waiting Approved</span> ':'') ?><?php echo getFirstParaSm($r->testimonial_desc) ?></span>
                            <span class="time"><?php echo convDateTimeTable($r->testimonial_date) ?></span>
                        </a>
                        <?php
                            $i += 1;
                        } 
                    } else {
                        echo '<div>Tidak ditemukan di database.</div>';
                    }
                    ?>

                <?php if(check_action($links_table_item,'delete')){ ?>
                </form>
                <?php } ?>
            </div>
            
            <div class="the-box toolbar no-border no-margin">
                <div class="btn-toolbar" role="toolbar">
                    <?php if(check_action($links_table_item,'delete') && (count($data) > 0)){ ?>
                    <div class="btn-group">
                        <button type="button" onClick="filesToDel()" class="btn btn-warning"><i class="fa fa-trash-o"></i> Delete Selected</button>
                    </div>
                    <?php } ?>
                    <div class="btn-group pull-right">
                        <?php echo isset($paging)?$paging:'';?>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <?php if(check_action($links_table_item,'delete')){ ?>
    <script type="text/javascript">
        function filesToDel() {
            var form1 = $('form#form1');
            var countChecked = form1.find('input[type=checkbox]:checked').length;
            if(countChecked == 0){
                alert('Please checked the item you want to delete.');
            } else {
                form1.submit();
            }
        }
    </script>
    <?php } ?>

</div>