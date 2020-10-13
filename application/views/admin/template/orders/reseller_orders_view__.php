<div class="the-box full invoice">
    <div class="the-box no-border">
        <div id="msgInfo"></div>
        <h4><small>Status Order:</small> <span class="label label-<?php echo get_orders_status($val->orders_status)['color'] ?>"><?php echo get_orders_status($val->orders_status)['name'] ?></span></h4>
        <h1 class="text-center">ORDER ID</h1>
        <h3 class="text-muted text-center"><?php echo '#'.$val->orders_code ?></h3>

        <div class="row">
            <div class="col-sm-6">
                <p><strong>ORDER INFORMATION</strong></p>
                <address>
                    Date: <?php echo convDateTimeFullEng($val->orders_date) ?><br>
                    No Invoice: <?php echo $val->orders_invoice ?><br>
                    Order ID: <?php echo '#'.$val->orders_code ?><br>
                    Name: <?php echo $orders_shipping->orders_shipping_name ?><br>
                    Email: <?php echo $orders_shipping->orders_shipping_email ?><br>
                    Phone: <?php echo $orders_shipping->orders_shipping_phone ?>
                </address>
            </div>
            <div class="col-sm-6 text-right">
                <p><strong>SHIPPING INFORMATION</strong></p>
                <address>
                    <?php echo ($orders_shipping->orders_shipping_method==1?'DELIVERY TO : ':'GET PICK UP AT : ').$orders_shipping->orders_shipping_name ?><br>
                    Address: <?php echo $orders_shipping->orders_shipping_address ?><br>
                    <?php echo getCitySet($orders_shipping->orders_shipping_city).', '.getProvSet($orders_shipping->orders_shipping_province) ?> <?php echo $orders_shipping->orders_shipping_postal_code ?><br>
                    Phone: <?php echo str_replace(',', '<br>', $orders_shipping->orders_shipping_phone) ?>
                </address>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-th-block table-dark">
                <colgroup>
                    <col width="1">
                    <col>
                    <col width="1">
                    <col width="1">
                    <col width="1">
                    <col width="1">
                    <col width="1">
                </colgroup>
                <thead>
                    <tr>
                        <th class="nobr text-center">Images</th>
                        <th class="nobr">Nama</th>
                        <th class="nobr">No Series</th>
                        <th class="nobr text-center">Qty</th>
                        <th class="nobr text-center">Sub Qty</th>
                        <th class="nobr text-center">Harga</th>
                        <th class="nobr text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $total_price = 0;
                foreach ($orders_detail as $key => $r) {
                    $sub_total      = ($r->orders_detail_price * $r->orders_detail_qty);
                    $total_price    = ($total_price + $sub_total);

                    $p = get_detail_product($r->product_id);

                    $qty_id    = '';
                    $qty_qty   = '';
                    $orders_detail_item = json_decode($r->orders_detail_item);
                    foreach ($orders_detail_item as $key => $value) {
                        $qty_id   .= '<p class="mb-0">No '.$value->id.'</p>';
                        $qty_qty  .= '<p class="mb-0">'.$value->qty.'</p>';
                    }
                ?>
                    <tr>
                        <td class="magnific-popup-wrap">
                            <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id));?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>" class="avatar mfp-fade"></a>
                        </td>
                        <td><a href="<?php echo base_url().'admin/product/view/'.$r->product_id.'-'.changeEnUrl($p->product_name) ?>"><?php echo $p->product_name ?></a></td>

                        <td class="nobr text-center"><?php echo $qty_id; ?></td>
                        <td class="nobr text-center"><?php echo $qty_qty; ?></td>
                        <td class="nobr text-center"><?php echo $r->orders_detail_qty ?></td>
                        <td class="nobr text-right"><?php echo convertRp($r->orders_detail_price) ?></td>
                        <td class="nobr text-right"><?php echo convertRp($sub_total) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right">Sub Total Harga :</td>
                        <td class="text-right nobr"><?php echo convertRp($total_price) ?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">PPN :</td>
                        <td class="text-right nobr"><?php echo convertRp($val->orders_price_ppn) ?></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right">Biaya Kurir:</td>
                        <td class="text-right nobr"><?php echo convertRp($val->orders_price_shipping) ?></td>
                    </tr>
                    <tr class="border-top bg-dark">
                        <td colspan="6" class="text-right"><strong>Grand Total Bayar :</strong></td>
                        <td class="text-right nobr"><strong><?php echo convertRp($val->orders_price_grand_total) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-3 control-label">Voucher</label>
                <div class="col-sm-5"><div class="form-control danger-color">-<?php echo convertRp($val->orders_voucher_price) ?></div></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Kode Voucher</label>
                <div class="col-sm-5"><div class="form-control"><?php echo $val->orders_voucher_code ?></div></div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Catatan Orders</label>
                <div class="col-sm-5"><div class="form-control"><?php echo $val->orders_noted ?></div></div>
            </div>
        </div>

        <div id="payment_detail" class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <div class="right-content">
                    <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-payment"><i class="fa fa-chevron-up"></i></button>
                </div>
                <h3 class="panel-title">PAYMENT DETAIL</h3>
            </div>
            <div id="panel-payment" class="collapse in">
                <div class="panel-body">
                    <table class="table table-th-block no-margin r-border">
                        <colgroup>
                            <col width="1">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="nobr">No Payment</td>
                                <td class="nobr">: <?php echo $orders_payment->orders_payment_code ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Payment method</td>
                                <td class="nobr">: <?php echo get_payment_method($orders_payment->orders_payment_method)['name'] ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">From Bank</td>
                                <td class="nobr">: <?php echo $orders_payment->orders_payment_from_bank ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Name Account</td>
                                <td class="nobr">: <?php echo $orders_payment->orders_payment_name_account ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Account number</td>
                                <td class="nobr">: <?php echo $orders_payment->orders_payment_no_account ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">IBAN</td>
                                <td class="nobr">: <?php echo $orders_payment->orders_payment_no_iban ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">BIC</td>
                                <td class="nobr">: <?php echo $orders_payment->orders_payment_no_bic ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Payment reference</td>
                                <td class="nobr">: <?php echo $orders_payment->orders_payment_no_reference ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Payment Tagihan</td>
                                <td class="nobr">: <?php echo convertRp($orders_payment->orders_payment_price) ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Payment Dibayar</td>
                                <td class="nobr">: <?php echo convertRp($orders_payment->orders_payment_grand_total) ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Status</td>
                                <td class="nobr">: <span class="label label-<?php echo get_orders_status($orders_payment->orders_payment_status)['color']; ?>"><?php echo get_orders_status($orders_payment->orders_payment_status)['name']; ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="shipping_detail" class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <div class="right-content">
                    <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-shipping"><i class="fa fa-chevron-up"></i></button>
                </div>
                <h3 class="panel-title">SHIPPING DETAIL</h3>
            </div>
            <div id="panel-shipping" class="collapse in">
                <div class="panel-body">
                    <table class="table table-th-block no-margin">
                        <colgroup>
                            <col width="1">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="nobr">Shipping method</td>
                                <td class="">: <?php echo get_shipping_method($orders_shipping->orders_shipping_method)['name'] ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Name</td>
                                <td class="">: <?php echo $orders_shipping->orders_shipping_name ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Email</td>
                                <td class="">: <?php echo $orders_shipping->orders_shipping_email ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Address</td>
                                <td class="">: <?php echo $orders_shipping->orders_shipping_address.' '.getCitySet($orders_shipping->orders_shipping_city).', '.getProvSet($orders_shipping->orders_shipping_province).' '.$orders_shipping->orders_shipping_postal_code ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Phone</td>
                                <td class="">: <?php echo $orders_shipping->orders_shipping_phone ?></td>
                            </tr>
                            <?php if($orders_shipping->orders_shipping_method == 1){ ?>
                            <tr>
                                <td class="nobr">Courier</td>
                                <td class="">: <?php echo get_orders_shipping_courier($val->orders_courier_id)['name'] ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Courier services</td>
                                <td class="">: <?php echo get_orders_shipping_courier($val->orders_courier_id)['service'] ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Courier cost</td>
                                <td class="">: <?php echo convertRp($orders_shipping->orders_shipping_price) ?></td>
                            </tr>
                            <tr>
                                <td class="nobr">Resi number</td>
                                <td class="">: <?php echo $orders_shipping->orders_shipping_resi ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td class="nobr">Status</td>
                                <td class="">: <span class="label label-<?php echo get_orders_status($orders_shipping->orders_shipping_status)['color']; ?>"><?php echo get_orders_status($orders_shipping->orders_shipping_status)['name']; ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <div class="right-content">
                    <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-history"><i class="fa fa-chevron-up"></i></button>
                </div>
                <h3 class="panel-title">HISTORY DATE STATUS ORDERS</h3>
            </div>
            <div id="panel-history" class="collapse in">
                <div class="panel-body">
                    <table class="table table-th-block no-margin">
                        <colgroup>
                            <col width="1">
                            <col>
                        </colgroup>
                        <tbody>
                        <?php
                        $timestamp_desc = json_decode($orders_timestamp->orders_timestamp_desc);
                        foreach ($timestamp_desc as $key => $value) { ?>
                            <tr>
                                <td class="nobr"><span class="label label-<?php echo get_orders_status($value->id)['color'] ?>"><?php echo get_orders_status($value->id)['name'] ?></span></td>
                                <td class="nobr"><span class="form-control"><?php echo convDateTimeFullEng($value->timestamp) ?></span></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-group form-action clearfix">
            <?php if($val->orders_status == 3){ ?>
                <div class="alert alert-block fade in alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Please confirm this orders.
                </div>
                <form class="ajax_process_orders" method="post" action="./func/process_orders.php" enctype="multipart/form-data" accept-charset="UTF-8">
                    <input type="hidden" name="thisId" value="<?php echo $val->orders_id ?>" />
                    <input type="hidden" name="thisIdDetail" value="" />
                    <input type="hidden" name="thisAction" value="process_orders" />
                    <button type="submit" id="process_orders" class="btn btn-danger">Confirm Orders</button>

                    <?php if($msgInfo==''){ ?>
                    <button type="button" class="btn btn-default pull-right" onclick="goBack();">Back</button>
                    <?php } else { ?>
                        <a href="./orders.php"><div class="btn btn-default pull-right">Back</div></a>
                    <?php } ?>
                </form>

            <?php } else if($val->orders_status == 4){  ?>
                <div class="alert alert-block fade in alert-dismissable alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Orders Awaiting Received Product From Supplier
                </div>
                <button type="button" class="btn btn-danger" disabled>Shipping Orders</button>

                <?php if($msgInfo==''){ ?>
                <button type="button" class="btn btn-default pull-right" onclick="goBack();">Back</button>
                <?php } else { ?>
                    <a href="./orders.php"><div class="btn btn-default pull-right">Back</div></a>
                <?php } ?>

            <?php } else if($val->orders_status == 5){  ?>
                <div class="alert alert-block fade in alert-dismissable alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Orders Ready Shipment.
                </div>
                <div id="shipping_orders" class="inline-popups">
                    <a href="#form-shipping" class="btn btn-danger" data-effect="mfp-zoom-in">Shipping Orders</a>

                    <?php if($msgInfo==''){ ?>
                    <button type="button" class="btn btn-default pull-right" onclick="goBack();">Back</button>
                    <?php } else { ?>
                        <a href="./orders.php"><div class="btn btn-default pull-right">Back</div></a>
                    <?php } ?>
                </div>
                <div id="form-shipping" class="white-popup mfp-with-anim mfp-hide">
                    <p><strong><?php echo ($orders_shipping->orders_shipping_method==1?'DELIVERY TO : ':'GET PICK UP AT : ').$ship_name ?></strong></p>
                    <address>
                        <?php echo $orders_shipping->orders_shipping_name ?><br>
                        <?php echo $orders_shipping->orders_shipping_address.' '.getCitySet($orders_shipping->orders_shipping_city).', '.getProvSet($orders_shipping->orders_shipping_province).' '.$orders_shipping->orders_shipping_postal_code ?><br>
                        <?php echo $orders_shipping->orders_shipping_postal_code ?><br>
                        <abbr title="Phone">Phone:</abbr> <?php echo $orders_shipping->orders_shipping_phone ?>
                    </address>
                    <form class="ajax_process_orders" method="post" action="./func/process_orders.php" enctype="multipart/form-data" accept-charset="UTF-8" >
                        <div class="form-group" <?php echo ($orders_shipping->orders_shipping_method==2?'style="display:none;"':'') ?>>
                            <label>Courier</label>
                            <input type="<?php echo ($orders_shipping->orders_shipping_method==1?'text':'hidden') ?>" name="ship_courier" class="form-control" value="">
                        </div>
                        <div class="form-group" <?php echo ($orders_shipping->orders_shipping_method==2?'style="display:none;"':'') ?>>
                            <label>Courier Services</label>
                            <input type="<?php echo ($orders_shipping->orders_shipping_method==1?'text':'hidden') ?>" name="ship_courier_service" class="form-control" value="">
                        </div>
                        <div class="form-group" <?php echo ($orders_shipping->orders_shipping_method==2?'style="display:none;"':'') ?>>
                            <label>Courier Cost</label>
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="<?php echo ($orders_shipping->orders_shipping_method==1?'text':'hidden') ?>" name="ship_cost" class="form-control moneyRp_masking" value="">
                            </div>
                        </div>
                        <div class="form-group" <?php echo ($orders_shipping->orders_shipping_method==2?'style="display:none;"':'') ?>>
                            <label>Resi Number</label>
                            <input type="<?php echo ($orders_shipping->orders_shipping_method==1?'text':'hidden') ?>" name="ship_resi" class="form-control" value="">
                        </div>
                        <input type="hidden" name="thisId" value="<?php echo $val->orders_id ?>" />
                        <input type="hidden" name="thisIdDetail" value="" />
                        <input type="hidden" name="thisAction" value="send_orders" />
                        <button type="submit" class="btn btn-danger">Send Orders</button>
                    </form>
                </div>

            <?php } else if($val->orders_status == 6 ){ ?>
            <form class="ajax_process_orders" method="post" action="./func/process_orders.php" enctype="multipart/form-data" accept-charset="UTF-8">
                <input type="hidden" name="thisId" value="<?php echo $val->orders_id ?>" />
                <input type="hidden" name="thisIdDetail" value="" />
                <input type="hidden" name="thisAction" value="orders_completed" />
                <button type="submit" id="orders_completed" class="btn btn-success" onClick="return confirm('Are you sure you want to completed this orders?');"><i class="fa fa-check-square-o"></i> Orders Completed</button>

                <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
            </form>

            <?php } else if($val->orders_status == 7 || $val->orders_status == 8 ){ ?>
                <div class="jumbotron jumbotron-sm text-center">
                    <h1>Thank you for your business</h1>
                </div><br />
                <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
            <?php } ?>

        </div>

    </div>
</div>