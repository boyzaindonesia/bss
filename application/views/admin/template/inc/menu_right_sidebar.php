
<!-- BEGIN SIDEBAR RIGHT HEADING -->
<div class="sidebar-right-heading">
    <ul class="nav nav-tabs square nav-justified">
      <li class="active"><a href="#online-user-sidebar" data-toggle="tab"><i class="fa fa-comments"></i> CHAT</a></li>
    </ul>
</div>
<!-- END SIDEBAR RIGHT HEADING -->

<!-- BEGIN SIDEBAR RIGHT -->
<div class="sidebar-right sidebar-nicescroller">
    <div class="tab-content">
        <div class="tab-pane fade in active" id="online-user-sidebar">
            <ul class="sidebar-menu online-user">
                <li class="static">ONLINE USERS</li>
                <?php
                $err_online = false;
                $user_admin = array('admin',$this->jCfg['user']['name']);

                $user_online = array();
                if( count($this->user_online) > 0){
                    foreach($this->user_online as $k=>$v){
                        $user_online[] = $k;

                        if($store_id != 1){
                            $err_online = true;
                            if(in_array($k, $user_admin))
                            $err_online = false;
                        }

                        if($err_online == false){
                        ?>
                        <li><a href="javascript:void(0);" <?php echo ($this->jCfg['user']['name'] != $k?'onclick="javascript:chatWith('."'".$k."'".');"':'style="cursor: default;"') ?> >
                            <span class="user-status success"></span>
                            <img src="<?php echo get_image(base_url()."assets/collections/photo/thumb/".get_user_photo_chat($k)) ?>" class="ava-sidebar img-circle" alt="<?php echo $k ?>">
                            <?php echo ucwords($k) ?> <?php echo ($this->jCfg['user']['name'] == $k?' <span class="label label-success span-sidebar">You</span>':'') ?>
                            <span class="small-caps">I'm available</span>
                        </a></li>
                    <?php
                        }
                    }
                }
                ?>

                <li class="static">OFFLINE USERS</li>
                <?php
                $err_online = false;

                if( count(get_user_list()) > 0){
                    foreach(get_user_list() as $k=>$v){
                        if(!in_array($v->user_name,$user_online)){

                        if($store_id != 1){
                            $err_online = true;
                            if(in_array($v->user_name, $user_admin))
                            $err_online = false;
                        }

                        if($err_online == false){
                        ?>
                        <li><a href="javascript:void(0);" style="cursor: default;">
                            <span class="user-status danger"></span>
                            <img src="<?php echo get_image(base_url()."assets/collections/photo/thumb/".$v->user_photo) ?>" class="ava-sidebar img-circle" alt="Avatar">
                            <?php echo ucwords($v->user_name) ?>
                            <span class="small-caps"><?php echo timeAgo($v->user_logindate) ?></span>
                        </a></li>
                    <?php
                        }
                        }
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<!-- BEGIN SIDEBAR RIGHT -->
