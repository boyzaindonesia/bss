<div class="row">
    <?php 
    $count_orders_source = 0;
    $total_orders_source = get_saldo();

    $arr_orders_source = get_orders_source();
    foreach ($arr_orders_source as $k => $v) {
        $id   = $v->orders_source_id;
        $name = $v->orders_source_name;
        $bg   = $v->orders_source_color;

        $count_orders_source = get_detail_saldo($id);
        $calcPercent         = (count($total_orders_source)!=0?calcPercent(count($count_orders_source), count($total_orders_source)):'0');
    ?>
    <div class="col-sm-4">
        <div class="the-box no-border bg-<?php echo $bg ?> tiles-information">
            <a href="<?php echo $own_links.'/view/'.$id.'-'.changeEnUrl($name);?>" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;"><?php echo $name ?></p>
                    <h1 class="bolded"><?php echo convertRp(get_last_saldo($id)); ?></h1> 
                    <div class="progress no-rounded progress-xs">
                        <div class="progress-bar progress-bar-<?php echo $bg ?>" role="progressbar" aria-valuenow="<?php echo $calcPercent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $calcPercent.'%' ?>">
                        </div>
                    </div>
                    <p><small>Total transaksi disini <br>( <?php echo $calcPercent.'%' ?> )</small></p>
                </div>
            </a>
        </div>
    </div>
    <?php } ?>
</div>