<div class="row">
    <?php

        $bg1            = 'warning';
        $link_progress1 = $own_links.'/list_orders';;
        $count_orders1  = get_store_count_detail_orders($this->store_id);
        $calcPercent1   = 0;

        $bg2            = 'success';
        $link_progress2 = $own_links.'/list_payment';
        $count_orders2  = get_saldo_store($this->store_id);
        $calcPercent2   = 0;
    ?>
    <div class="col-sm-4">
        <div class="the-box no-border bg-<?php echo $bg1 ?> tiles-information">
            <a href="<?php echo $link_progress1 ?>" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;">Total Order</p>
                    <h1 class="bolded"><?php echo count($count_orders1) ?></h1>
                    <div class="progress no-rounded progress-xs">
                      <div class="progress-bar progress-bar-<?php echo $bg1 ?>" role="progressbar" aria-valuenow="<?php echo $calcPercent1 ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $calcPercent1.'%' ?>">
                      </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="the-box no-border bg-<?php echo $bg2 ?> tiles-information">
            <a href="<?php echo $link_progress2 ?>" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;">Saldo</p>
                    <h1 class="bolded"><?php echo convertRp($count_orders2) ?></h1>
                    <div class="progress no-rounded progress-xs">
                      <div class="progress-bar progress-bar-<?php echo $bg2 ?>" role="progressbar" aria-valuenow="<?php echo $calcPercent2 ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $calcPercent2.'%' ?>">
                      </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>

<ul class="nav nav-tabs item-color">
    <li class="<?php echo ($tab=='tab1'?'active':'') ?>"><a href="<?php echo $own_links ?>/list_orders">List Pesanan</a></li>
    <li class="<?php echo ($tab=='tab2'?'active':'') ?>"><a href="<?php echo $own_links ?>/list_payment">List Pembayaran</a></li>
</ul>

<?php include($content_layout) ?>