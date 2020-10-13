<div class="top-navbar-inner">

    <div class="logo-brand <?php echo ($this->jCfg['user']['color']!=''?$this->jCfg['user']['color'].'-color':'') ?>">
        <h3><a href="<?php echo site_url();?>" target="_blank"><?php echo get_store_name($store_id);?></a></h3>
    </div>

    <div class="top-nav-content">

        <!-- Begin button sidebar left toggle -->
        <div class="btn-collapse-sidebar-left">
            <i class="fa fa-long-arrow-right icon-dinamic"></i>
        </div>
        <!-- End button sidebar left toggle -->

        <!-- Begin button sidebar right toggle -->
        <div class="btn-collapse-sidebar-right">
            <i class="fa fa-comments"></i>
        </div>
        <!-- End button sidebar right toggle -->

        <div class="collapse navbar-collapse" id="main-fixed-nav">
            <!-- <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
            </form> -->
            <ul class="nav navbar-nav navbar-left">
                <!-- Begin nav notification -->
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                        $count_notif_update_product = count(get_notif_update_product('1',$this->store_id));
                        if($count_notif_update_product > 0){ ?>
                        <span class="badge badge-danger icon-count"><?php echo $count_notif_update_product ?></span>
                        <?php } ?>
                        <i class="fa fa-bell"></i>
                    </a>
                    <ul class="dropdown-menu square with-triangle">
                        <li>
                            <div class="nav-dropdown-heading">
                            Notif Update Produk
                            </div>
                            <div class="nav-dropdown-content scroll-nav-dropdown">
                                <ul>
                                    <?php
                                    $notif_update_product = get_notif_update_product('1',$this->store_id);
                                    foreach ($notif_update_product as $key => $val) { ?>
                                    <li class=""><a href="<?php echo base_url().'admin/notif_update_produk/view/'.$val->notif_id.'-'.$val->product_id.'-'.get_product_name_simple($val->product_id) ?>" target="_blank">
                                        <img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($val->product_id));?>" class="absolute-left-content img-circle" alt="">
                                        <strong><?php echo get_product_name_simple($val->product_id); ?></strong> <?php echo $val->notif_title ?>
                                        <span class="small-caps"><?php echo timeAgo($val->notif_date) ?></span>
                                    </a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <a href="<?php echo base_url() ?>admin/notif_update_produk" target="_blank" style="padding:0;"><button class="btn btn-primary btn-square btn-block">Lihat Semuanya</button></a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                        $count_notif_temp_orders = count(get_notif_temp_orders($this->store_id,'1'));
                        if($count_notif_temp_orders > 0){ ?>
                        <span class="badge badge-danger icon-count"><?php echo $count_notif_temp_orders ?></span>
                        <?php } ?>
                        <i class="fa fa-book"></i>
                    </a>
                    <ul class="dropdown-menu square with-triangle">
                        <li>
                            <div class="nav-dropdown-heading">
                            Notif Order Booking
                            </div>
                            <div class="nav-dropdown-content scroll-nav-dropdown">
                                <ul>
                                    <?php
                                    $notif_temp_orders = get_notif_temp_orders($this->store_id,'1');
                                    foreach ($notif_temp_orders as $key => $val) { ?>
                                    <li class=""><a href="<?php echo base_url() ?>admin/transaction/booking" target="_blank">
                                        <?php
                                        $ii = 0;
                                        $product_detail_item = json_decode($val->product_detail_item);
                                        foreach ($product_detail_item as $key1 => $val1) {
                                            if($ii == 0){?>
                                        <img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($val1->id));?>" class="absolute-left-content img-circle" alt="">
                                        <?php $ii += 1; } } ?>
                                        <strong><?php echo get_orders_source($val->orders_source_id)->orders_source_name; ?></strong> - <?php echo $val->orders_source_invoice; ?>
                                        <span class="small-caps"><?php echo timeAgo($val->temp_orders_date) ?></span>
                                    </a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <a href="<?php echo base_url() ?>admin/transaction/booking" target="_blank" style="padding:0;"><button class="btn btn-primary btn-square btn-block">Lihat Semuanya</button></a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                        Last Produk: <?php echo convDateTimeTable($product_setup->product_setup_last_update) ?>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Begin button nav toggle -->
        <div class="btn-collapse-nav" data-toggle="collapse" data-target="#main-fixed-nav">
            <i class="fa fa-plus icon-plus"></i>
        </div>
        <!-- End button nav toggle -->

        <!-- Begin user session nav -->
        <ul class="nav-user navbar-right clearfix">
            <li class="nav-info-login">
                <h4><?php echo $this->jCfg['user']['level'];?></h4>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="<?php echo get_image(base_url()."assets/collections/photo/thumb/".get_user_photo($this->jCfg['user']['id']));?>" class="avatar img-circle" alt="">
                    Hi, <strong><?php echo $this->jCfg['user']['fullname'];?></strong>
                </a>
                <ul class="dropdown-menu square <?php echo ($this->jCfg['user']['color']!=''?$this->jCfg['user']['color']:'') ?> margin-list-rounded with-triangle">
                    <li><a href="<?php echo site_url("admin/me/profile");?>">Profile Saya</a></li>
                    <?php if($this->jCfg['user']['level']!="grader"){?>
                    <li><a href="<?php echo site_url("admin/me/change_password");?>">Ubah Password</a></li>
                    <?php } ?>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url("auth/out");?>">Log out</a></li>
                </ul>
            </li>
        </ul>
        <!-- End user session nav -->

        <div class="collapse navbar-collapse" id="main-fixed-nav">

        </div>
        <!-- End Collapse menu nav -->

    </div>
</div>