<?php js_picker() ?>

<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>

        <div class="btn-group pull-right">
            <?php
                $total_nota = get_purchase_payment_remaining();
                $total_nota_isan = get_purchase_reseller_payment_remaining(3);
            ?>
            <h3 class="no-margin">Nota: <?php echo convertRp($total_nota); ?> | Sasha: <?php echo convertRp($total_nota - $total_nota_isan); ?> | Isan: <?php echo convertRp($total_nota_isan); ?></h3>
            <h3 class="no-margin" style="color: #d9534f;">
                <?php
                $get_purchase_supplier_remaining = get_purchase_supplier_remaining();
                $i = 0;
                foreach ($get_purchase_supplier_remaining as $k1 => $v1) {
                    echo ($i>0?" | ":"").$v1->supplier_name.": ".convertRp($v1->purchase_payment_remaining);
                    $i++;
                }
                ?>
            </h3>
        </div>
    </div>

    <div class="relative clearfix">
        <div class="panel panel-toolbar panel-dark">
            <div class="panel-heading">
                <div class="right-content">
                <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-toolbar"><i class="fa fa-chevron-down"></i></button>
                </div>
                <h3 class="panel-title">Option Search</h3>
            </div>
            <div id="panel-collapse-toolbar" class="collapse" style="height: 0px;">
                <div class="panel-body">
                    <div class="relative clearfix">
                        <div class="btn-toolbar toolbar-sort">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Short by</label>
                                <div class="btn-group">
                                    <?php $order_by  = $this->jCfg['search']['order_by']; ?>
                                    <?php $order_dir = $this->jCfg['search']['order_dir']; ?>
                                    <select name="order_by" class="form-control" onchange="this.form.submit()">
                                        <option value="mt_purchase.purchase_date-desc" <?php echo ($order_by=='mt_purchase.purchase_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="mt_purchase.purchase_date-asc" <?php echo ($order_by=='mt_purchase.purchase_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="mt_purchase.purchase_price_grand_total-asc" <?php echo ($order_by=='mt_purchase.purchase_price_grand_total'&&$order_dir=='asc'?'selected':'') ?> >Pembayaran: Termurah</option>
                                        <option value="mt_purchase.purchase_price_grand_total-desc" <?php echo ($order_by=='mt_purchase.purchase_price_grand_total'&&$order_dir=='desc'?'selected':'') ?> >Pembayaran: Temahal</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar toolbar-filter">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Per Page</label>
                                <div class="btn-group">
                                    <?php $filter = $this->jCfg['search']['filter']; ?>
                                    <select name="filter" class="form-control" style="min-width: 50px;" onchange="this.form.submit()">
                                        <option value="25" <?php echo ($filter=='25'?'selected':'') ?> >25</option>
                                        <option value="50" <?php echo ($filter=='50'?'selected':'') ?> >50</option>
                                        <option value="100" <?php echo ($filter=='100'?'selected':'') ?> >100</option>
                                        <option value="" <?php echo ($filter==''?'selected':'') ?> >ALL</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-toolbar toolbar-search">
            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                <label>Search:</label>
                <div class="btn-group">
                    <input type="search" data-reset="true" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
                    <input type="hidden" name="searchAction" value="search">
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-th-block table-dark top-align">
            <colgroup>
                <col width="1">
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Tanggal</th>
                    <th>Nama</th>
                    <th class="nobr text-center">Qty</th>
                    <th class="nobr text-center">Harga</th>
                    <th class="nobr text-center">Harga Satuan</th>
                    <th class="nobr text-center">Total</th>
                    <th class="nobr text-center">Pembayaran</th>
                    <th class="nobr text-center">Status</th>
                    <th class="nobr text-center">Detail</th>
                    <th class="nobr text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(count($data) > 0){
                // debugCode($data);
                $i = 1;
                $pageNumber = $this->uri->segment(4);
                if(!empty($pageNumber)){
                    $i = $pageNumber + 1;
                }
                foreach($data as $r){
                    $get_purchase_detail = get_purchase_detail($r->purchase_id);
                ?>
                <tr>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="nobr text-center"><span class="label label-default"><?php echo getYearMonthDate($r->purchase_date) ?></span><br/><small><?php echo convTime($r->purchase_date) ?></small></td>
                    <td>
                        <?php
                        foreach ($get_purchase_detail as $key => $value) {
                            echo '<div>'.$value->purchase_detail_name.'</div>';
                        }
                        ?>
                    </td>
                    <td class="nobr text-center">
                        <?php
                        foreach ($get_purchase_detail as $key => $value) {
                            $get_product_satuan = get_product_satuan($value->purchase_detail_satuan);
                            $satuan_name = $get_product_satuan->product_satuan_name;
                            echo '<div>'.$value->purchase_detail_qty.' '.$satuan_name.'</div>';
                        }
                        ?>
                    </td>
                    <td class="nobr text-left">
                        <?php
                        foreach ($get_purchase_detail as $key => $value) {
                            echo '<div>'.convertRp($value->purchase_detail_price).'</div>';
                        }
                        ?>
                    </td>
                    <td class="nobr text-left">
                        <?php
                        foreach ($get_purchase_detail as $key => $value) {
                            $get_product_satuan = get_product_satuan($value->purchase_detail_satuan);
                            $satuan_qty = $get_product_satuan->product_satuan_qty;

                            echo '<div>'.convertRp(($value->purchase_detail_price / $satuan_qty)).'</div>';
                        }
                        ?>
                    </td>
                    <td class="nobr"><?php echo convertRp($r->purchase_price_grand_total);?></td>
                    <td class="nobr text-center">
                        <div><small><?php echo strtoupper($r->supplier_name); ?></small></div>
                        <div><small><?php foreach (json_decode($r->supplier_bank) as $k1 => $v1) { echo $v1->name; } ?></small></div>
                        <div><small>(<?php echo get_purchase_payment_source($r->purchase_payment_source)['name'] ?>)</small></div>
                    </td>
                    <td class="nobr text-center">
                        <span class="label <?php echo ($r->purchase_status=='1'?'label-success':'label-danger') ?>"><?php echo ($r->purchase_status=='1'?'Lunas':'Belum') ?></span>
                        <?php if($r->purchase_status == '0'){ ?>
                            <div><?php echo convertRp($r->purchase_payment_remaining) ?></div>
                            <div><?php echo xTimeAgo($r->purchase_date, timestamp(), "d"); ?></div>
                        <?php } ?>
                    </td>
                    <td class="nobr text-right">
                        <?php
                        $get_purchase_reseller_remaining = get_purchase_reseller_remaining($r->purchase_id);
                        if($get_purchase_reseller_remaining > 0){
                            echo '<h6 class="no-margin">Sasha: '.convertRp($r->purchase_payment_remaining - $get_purchase_reseller_remaining).'</h6>';
                            echo '<h6 class="no-margin">Isan: '.convertRp($get_purchase_reseller_remaining).'</h6>';
                        } ?>
                    </td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->purchase_id,changeEnUrl($r->purchase_invoice));?>
                    </td>
                </tr>
                <?php
                    $i += 1;
                }
            } else {
                echo '<tr><td colspan="8">Tidak ditemukan di database.</td></tr>';
            }
            ?>
            </tbody>
        </table>
        <?php if(isset($paging) && $paging!=''){ ?>
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group pull-right">
                <?php echo $paging ?>
            </div>
        </div>
        <?php } ?>
    </div>

</div>