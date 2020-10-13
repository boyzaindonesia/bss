<?php
    // $timestamp = timestamp();
    // $dateStart = convDatepickerDec(getMinDay($timestamp, 0));
    // $dateEnd   = convDatepickerDec($timestamp);
    // $dateStart = "2018-09-28";
    // $dateEnd   = "2018-09-28";
?>
<div class="the-box no-border">

    <div class="relative clearfix">
        <div class="panel panel-toolbar panel-dark" style="padding-right: 0px;">
            <div class="panel-heading">
                <div class="right-content">
                <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-toolbar"><i class="fa fa-chevron-down"></i></button>
                </div>
                <h3 class="panel-title">Option Search</h3>
            </div>
            <div id="panel-collapse-toolbar" class="collapse" style="height: 0px;">
                <div class="panel-body">
                    <div class="relative clearfix">
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Source</label>
                                <div class="btn-group">
                                    <?php $orders_source_id  = $this->jCfg['search']['orders_source_id']; ?>
                                    <select name="orders_source_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" selected >--- Pilih ---</option>
                                        <?php
                                        $arr = array('3','8','2','11','7','5','6','4','9','10','12');
                                        foreach ($arr as $k) {
                                            $v = get_orders_source($k);
                                        ?>
                                        <option value="<?php echo $v->orders_source_id ?>" <?php echo ($orders_source_id==$v->orders_source_id?'selected':'') ?> ><?php echo $v->orders_source_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar pull-right">
                            <form action="" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <div class="btn-toolbar toolbar-btn-action">
                                    <div class="pull-right">
                                        <div class="btn-group mr-10">
                                            <input type="text" id="datereportstart" name="date_start" class="form-control datepicker" value="<?php echo convDatepickerEnc($dateStart) ?>" data-date-format="dd-mm-yyyy" placeholder="Tanggal Mulai...">
                                        </div>
                                        <div class="btn-group mr-10 mt-5">
                                            -
                                        </div>
                                        <div class="btn-group mr-10">
                                            <input type="text" id="datereportend" name="date_end" class="form-control datepicker" value="<?php echo convDatepickerEnc($dateEnd) ?>" data-date-format="dd-mm-yyyy" placeholder="Tanggal Akhir...">
                                        </div>
                                        <div class="btn-group">
                                            <input type="submit" name="searchAction" class="btn btn-danger" value="Cari">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <link href="<?php echo base_url();?>assets/plugins/datepicker/datepicker.min.css" rel="stylesheet" />
                            <script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $('.datepicker').datepicker();

                                    $(document).on('click', '.btn-ajax-get-report', function(e){
                                        e.preventDefault();
                                        var $this   = $(this);
                                        if(!$this.hasClass('loading')){
                                            $this.addClass('loading');
                                            // getChartOrders();
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable-all-custom-sort table-font-small">
            <colgroup>
                <col>
                <col width="1">
                <?php foreach ($arrDate as $k) { ?>
                <col width="1">
                <?php } ?>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="">Nama</th>
                    <th class="nobr text-center">Stok</th>
                    <?php foreach ($arrDate as $k) { ?>
                    <th class="nobr text-center"><?php echo date("d-m", strtotime($k)); ?></th>
                    <?php } ?>
                    <th class="nobr text-center">Total</th>
                    <th class="nobr text-center">Shp</th>
                    <th class="nobr text-center">Tokpe</th>
                    <th class="nobr text-center">Laz</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            // debugCode($result);
            if(count($list_product) > 0){
                foreach($list_product as $r){
                    $tr_class = '';
                    if($r->product_status_id == 3){
                        $tr_class = 'warning';
                    }
                ?>
                <tr class="<?php echo $tr_class ?>" data-id="<?php echo $r->product_id;?>" title="<?php echo $r->product_name;?>">
                    <td class=""><a href="<?php echo base_url().'admin/product/view/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>" target="_blank"><?php echo $r->product_name ?></a> </td>
                    <td class="nobr text-center"><?php echo $r->product_stock ?></td>
                    <?php foreach ($arrDate as $k => $v) { ?>
                    <td class="nobr text-center"><?php echo ($r->$v >0?$r->$v:"-") ?></td>
                    <?php } ?>
                    <td class="nobr text-center"><strong><?php echo $r->total_qty ?></strong></td>
                    <td class="nobr text-center" style="color: #E9573F;"><?php echo $r->shopee ?></td>
                    <td class="nobr text-center" style="color: #E9573F;"><?php echo $r->tokopedia ?></td>
                    <td class="nobr text-center" style="color: #E9573F;"><?php echo $r->lazada ?></td>
                </tr>
                <?php
                $i += 1;
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    </form>

</div>

<!-- datatable -->
<?php get_data_table();?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datatable-all-custom-sort').dataTable({
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            "columnDefs": [ {"targets": 'no-sort', "orderable": false} ],
            "order": [[ <?php echo (2 + count($arrDate)) ?>, "desc" ]],
            "iDisplayLength": -1
        });
    });
</script>