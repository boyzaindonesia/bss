
<div class="the-box no-border">

    <?php
    $check_double_orders_detail = check_double_orders_detail($this->store_id);
    if(count($check_double_orders_detail) > 0 ){
    ?>
    <h3>Orders Detail Double</h3>
    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable-1">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Order Id</th>
                    <th class="nobr text-center no-sort">Images</th>
                    <th>Nama</th>
                    <th>Stock</th>
                    <th class="nobr text-center">QTY</th>
                    <th class="nobr text-center no-sort">Item</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $i = 1;
                foreach($check_double_orders_detail as $key => $r){
                    $detail = get_product_detail($r->product_id);
                    ?>
                <tr>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="nobr text-center"><?php echo $r->orders_id ?></td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".$r->product_images);?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".$r->product_images);?>" class="avatar mfp-fade"></a>
                    </td>
                    <td><?php echo $r->product_name;?></td>
                    <td>
                    <?php if(isset($detail->product_stock_detail) && $detail->product_stock_detail != ''){ ?>
                    <ul class="product-list-item-stock clearfix mb-5">
                        <?php
                        foreach (json_decode($detail->product_stock_detail) as $key2 => $val2) {
                            if($val2->status==1){ ?>
                            <li><p><?php echo $val2->name ?> = <?php echo $val2->qty ?></p></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                    <?php echo $detail->product_stock; ?>
                    <?php } ?>
                    </td>
                    <td class="nobr text-center">
                        <?php echo $r->orders_detail_qty;?>
                    </td>
                    <td class="nobr text-center">
                        <?php
                        $orders_detail_item = json_decode($r->orders_detail_item);
                        foreach ($orders_detail_item as $key2 => $val2) {
                            echo $val2->name.': '.$val2->qty.'<br>';
                        }
                        ?>
                    </td>
                    <td class="nobr text-center">
                        <a href="<?php echo $own_links.'/delete_double_orders_detail/'.$r->orders_detail_id ?>" onclick="return confirm('Are you sure delete ?');" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
                <?php
                    $i += 1;
                }
            ?>
            </tbody>
        </table>
    </div>
    </form>
    <?php } ?>
</div>

    <?php
    $get_product_stock_no_sync = get_product_stock_no_sync($this->store_id);
    if($get_product_stock_no_sync['found']){
    ?>
<div class="the-box no-border">
    <h3>Stok Produk Tidak Sinkron</h3>
    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable-2">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Order Id</th>
                    <th class="nobr text-center no-sort">Images</th>
                    <th>Nama</th>
                    <th>Stock</th>
                    <th class="nobr text-center">QTY</th>
                    <th class="nobr text-center no-sort">Item</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $i = 1;
                foreach($get_product_stock_no_sync['item'] as $key => $r){
                    $detail = get_product_detail($r->product_id);
                    ?>
                <tr>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="nobr text-center"><?php echo $r->orders_id ?></td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".$r->product_images);?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".$r->product_images);?>" class="avatar mfp-fade"></a>
                    </td>
                    <td><?php echo $r->product_name;?></td>
                    <td>
                    <?php if(isset($detail->product_stock_detail) && $detail->product_stock_detail != ''){ ?>
                    <ul class="product-list-item-stock clearfix mb-5">
                        <?php
                        foreach (json_decode($detail->product_stock_detail) as $key2 => $val2) {
                            if($val2->status==1){ ?>
                            <li><p><?php echo $val2->name ?> = <?php echo $val2->qty ?></p></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                    <?php echo $detail->product_stock; ?>
                    <?php } ?>
                    </td>
                    <td class="nobr text-center">
                        <?php echo $r->orders_detail_qty;?>
                    </td>
                    <td class="nobr text-center">
                        <?php
                        $orders_detail_item = json_decode($r->orders_detail_item);
                        foreach ($orders_detail_item as $key2 => $val2) {
                            echo $val2->name.': '.$val2->qty.'<br>';
                        }
                        ?>
                    </td>
                    <td class="nobr text-center">
                        <!-- <a href="<?php echo $own_links.'/delete_double_orders_detail/'.$r->orders_detail_id ?>" onclick="return confirm('Are you sure delete ?');" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i></a> -->
                    </td>
                </tr>
                <?php
                    $i += 1;
                }
            ?>
            </tbody>
        </table>
    </div>
    </form>
</div>
<?php } ?>

<!-- datatable -->
<?php get_data_table();?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.datatable-1').dataTable({
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "columnDefs": [ {"targets": 'no-sort', "orderable": false} ],
            "order": [[ 2, "asc" ]],
            "iDisplayLength": -1
        });
        $('.datatable-2').dataTable({
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "columnDefs": [ {"targets": 'no-sort', "orderable": false} ],
            "order": [[ 2, "asc" ]],
            "iDisplayLength": -1
        });
    });
</script>
