
<?php js_mask() ?>

<div class="the-box no-border">

    <h3 class="panel-title text-uppercase">Data Pembayaran</h3>
    <div class="relative clearfix mt-20">
        <div class="row">
            <div class="col-sm-4" style="padding:0 3px;">
                <ul class="list-group">
                    <li class="list-group-item">Total Qty<span class="badge badge-success"><?php echo $data_details["total_qty_product"] ?> item</span></li>
                    <li class="list-group-item">Total Price Product<span class="badge badge-success"><?php echo convertRp($data_details["total_price_product"]) ?></span></li>
                </ul>
            </div>
            <div class="col-sm-4" style="padding:0 3px;">
                <ul class="list-group">
                    <li class="list-group-item">Total Price Grand Total<span class="badge badge-success"><?php echo convertRp($data_details["total_price_grand_total"]) ?></span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="btn-group pull-right" style="min-height: 50px;">
        <a href="<?php echo base_url().'admin/transaction_process/clear_verifikasi_payment' ?>"><div class="btn btn-default">Clear All</div></a>
    </div>
    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark">
            <colgroup>
                <col width="1">
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr">No</th>
                    <th class="">Invoice</th>
                    <th class="">Qty</th>
                    <th class="">Total Harga</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // debugCode($data_mp_payment);
            $i = 0;
            if(count($data_mp_payment) > 0){
                foreach($data_mp_payment as $key => $r){
                    $i += 1;
                ?>
                <tr class="">
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class=""><?php echo $r->mp_source_invoice ?></td>
                    <td class=""><?php echo $r->mp_qty_product ?></td>
                    <td class=""><?php echo convertRp($r->mp_price_grand_total) ?></td>
                </tr>
                <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    </form>

</div>

<div class="popup popup-orders">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
    </div>
</div>
