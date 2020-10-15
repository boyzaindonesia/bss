<div class="row">
    <?php
    $count_orders = 0;
    $total_orders = get_orders_by_source($this->store_id, '2,3,4,5,6,7,8,9,10,11');
    $arr_count_orders = array(2,3,4,5,8);
    foreach ($arr_count_orders as $id) {
        $orders_status  = get_orders_status($id);
        $name           = $orders_status['name'];
        $bg             = $orders_status['color'];
        $url            = $orders_status['url'];
        if($id == 8){
            $url = "list_all";
        }

        $count_orders = get_detail_orders_by_source($this->store_id, $id, '2,3,4,5,6,7,8,9,10,11');
        $calcPercent  = (count($total_orders)!=0?calcPercent(count($count_orders), count($total_orders)):'0');
    ?>
    <div class="col-sm-2">
        <div class="the-box no-border bg-<?php echo $bg ?> tiles-information">
            <a href="<?php echo $own_links.'/'.$url;?>" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;"><?php echo $name ?></p>
                    <h1 class="bolded"><?php echo count($count_orders) ?></h1>
                    <div class="progress no-rounded progress-xs">
                      <div class="progress-bar progress-bar-<?php echo $bg ?>" role="progressbar" aria-valuenow="<?php echo $calcPercent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $calcPercent.'%' ?>">
                      </div>
                    </div>
                    <p><small>Processed in this status <br/>( <?php echo $calcPercent.'%' ?> )</small></p>
                </div>
            </a>
        </div>
    </div>
    <?php } ?>

    <?php
        $name           = 'CLAIM';
        $bg             = 'danger';
        $url            = 'claim';

        $count_orders = get_count_orders_claim($this->store_id, '1,2');
        $calcPercent  = (count($total_orders)!=0?calcPercent(count($count_orders), count($total_orders)):'0');
    ?>
    <div class="col-sm-2">
        <div class="the-box no-border bg-<?php echo $bg ?> tiles-information">
            <a href="<?php echo $own_links.'/'.$url;?>" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;"><?php echo $name ?></p>
                    <h1 class="bolded"><?php echo count($count_orders) ?></h1>
                    <div class="progress no-rounded progress-xs">
                      <div class="progress-bar progress-bar-<?php echo $bg ?>" role="progressbar" aria-valuenow="<?php echo $calcPercent ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $calcPercent.'%' ?>">
                      </div>
                    </div>
                    <p><small>Processed in this status <br/>( <?php echo $calcPercent.'%' ?> )</small></p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php js_picker() ?>
<ul class="nav nav-tabs item-color">
    <?php
    $arr_count_orders = array(2,3,4,5);
    foreach ($arr_count_orders as $id) {
        $orders_status  = get_orders_status($id);
        $name           = $orders_status['name'];
        $url            = $orders_status['url'];
    ?>
    <li class="<?php echo ($tab=='tab'.$id?'active':'') ?>"><a href="<?php echo $own_links.'/'.$url ?>"><?php echo $name ?></a></li>
    <?php } ?>
    <li class="<?php echo ($tab=='tab8'?'active':'') ?>"><a href="<?php echo $own_links ?>/list_all">List Transaksi</a></li>
    <li class="<?php echo ($tab=='tab11'?'active':'') ?>"><a href="<?php echo $own_links ?>/claim">Claim</a></li>
    <li class="<?php echo ($tab=='tab16'?'active':'') ?>"><a href="<?php echo $own_links ?>/booking">Booking</a></li>
    <li class="<?php echo ($tab=='tab17'?'active':'') ?>"><a href="<?php echo $own_links ?>/archive">Archive</a></li>
</ul>

<?php
    $check_double_orders_detail = check_double_orders_detail($this->store_id);
    if(count($check_double_orders_detail) > 0 ){
        echo '<div class="alert alert-info square fade in alert-dismissable mt-10">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Info! </strong> Terdapat jumlah order detail yang double <a href="'.base_url().'admin/product_checked" target="_blank">LIHAT</a>
            </div>';
    }
?>

<?php
    $check_orders_date_long = check_orders_date_long();
    // debugCode($check_orders_date_long);
?>

<!-- <div class="the-box no-border">
    <div class="result-count"></div>
    <div class="result-marketplace"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            $thisVal = "shopee";
            $.ajax({
                type: 'POST',
                url: MOD_URL+'ajax-function/get_marketplace_new_orders',
                data: {'thisVal':$thisVal,'thisAction':'getdata'},
                async: false,
                cache: false,
                dataType: 'json',
                success: function(data){
                    var data_msg = "";
                    $.each( data.result, function( key, value ) {
                        data_msg += '<div>Nama Product: '+value.nama_product+'</div>';
                    });
                    $('.result-marketplace').html(data_msg);

                    $('.result-count').html(data.count);
                    console.log(data.result);
                },
                error: function(jqXHR){
                    var response = jqXHR.responseText;
                    swal({
                        title: "Error!",
                        html: response,
                        type: "error"
                    });
                }
            });

        });
    </script>

</div> -->

<?php include($content_layout) ?>
