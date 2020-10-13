
<div class="the-box no-border">

    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-danger btn-delete-multi"><i class="fa fa-trash-o"></i> Hapus ( 0 )</button>
        </div>
        <div class="btn-group pull-right">
            <div class="btn btn-warning btn-cetak" data-url="<?php echo base_url().'print-address' ?>"><i class="fa fa-print"></i> Cetak Alamat ( 0 )</div>
        </div>
        <div class="btn-group pull-right">
            <div class="btn btn-primary btn-cetak-simple" data-url="<?php echo base_url().'print-address-simple' ?>"><i class="fa fa-plus"></i> Cetak Simple ( 0 )</div>
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
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Source</label>
                                <div class="btn-group">
                                    <?php $orders_source_id  = $this->jCfg['search']['orders_source_id']; ?>
                                    <select name="orders_source_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" selected >--- Pilih ---</option>
                                        <?php
                                        $arr_orders_source = get_orders_source();
                                        foreach ($arr_orders_source as $k => $v) { ?>
                                        <option value="<?php echo $v->orders_source_id ?>" <?php echo ($orders_source_id==$v->orders_source_id?'selected':'') ?> ><?php echo $v->orders_source_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Kurir</label>
                                <div class="btn-group">
                                    <?php $orders_courier_id  = $this->jCfg['search']['orders_courier_id']; ?>
                                    <select name="orders_courier_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" selected >--- Pilih ---</option>
                                        <?php
                                        $arr_orders_courier = get_orders_courier();
                                        foreach ($arr_orders_courier as $k => $v) { ?>
                                        <option value="<?php echo $v->orders_courier_id ?>" <?php echo ($orders_courier_id==$v->orders_courier_id?'selected':'') ?> ><?php echo $v->orders_courier_name ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="btn-toolbar toolbar-sort">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Short by</label>
                                <div class="btn-group">
                                    <?php $order_by  = $this->jCfg['search']['order_by']; ?>
                                    <?php $order_dir = $this->jCfg['search']['order_dir']; ?>
                                    <select name="order_by" class="form-control" onchange="this.form.submit()">
                                        <option value="print_address_date-desc" <?php echo ($order_by=='print_address_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="print_address_date-asc" <?php echo ($order_by=='print_address_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="orders_shipping_name-asc" <?php echo ($order_by=='orders_shipping_name'&&$order_dir=='asc'?'selected':'') ?> >Nama: A to Z</option>
                                        <option value="orders_shipping_name-desc" <?php echo ($order_by=='orders_shipping_name'&&$order_dir=='desc'?'selected':'') ?> >Nama: Z to A</option>
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

    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center"><input type="checkbox" name="" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"></th>
                    <th class="nobr text-center">Tanggal</th>
                    <th class="nobr text-center">Source</th>
                    <th class="nobr text-center">Kurir</th>
                    <th class="nobr text-center">Paket</th>
                    <th class="nobr">Nama Penerima</th>
                    <th>Alamat</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(count($data) > 0){
                foreach($data as $r){
                    $tr_class = '';
                    if($r->orders_shipping_dropship == 1){
                        $tr_class = 'warning';
                    }

                    $orders_ship_name  = $this->store_name;
                    $orders_ship_phone = $this->store_phone;
                    if($r->orders_shipping_dropship == 1){
                        $orders_ship_name  = $r->orders_ship_name;
                        $orders_ship_phone = $r->orders_ship_phone;
                    }

                ?>
                <tr class="<?php echo $tr_class ?>" data-id="<?php echo $r->print_address_id;?>" title="<?php echo 'Nama Pengirim: '.$orders_ship_name ?>">
                    <td class="nobr text-center"><input type="checkbox" name="checked_files[]" value="<?php echo $r->print_address_id;?>" ></td>
                    <td class="nobr text-center"><span class="label <?php echo ($r->print_address_status == 0?'label-danger':'label-default') ?>"><?php echo getYearMonthDate($r->print_address_date) ?></span><br/><small><?php echo convTime($r->print_address_date) ?></small>
                    </td>
                    <td class="nobr">
                        <?php echo get_orders_source($r->orders_source_id)->orders_source_name;?>
                        <?php echo ($r->orders_shipping_dropship==1?'<br/>(Dropship)':'')?>
                        <div class="shipping_resi"><small><?php echo ($r->orders_shipping_resi!=''?$r->orders_shipping_resi:'') ?></small></div>
                    </td>
                    <td class="nobr">
                        <?php
                            $get_orders_courier = get_orders_courier($r->orders_courier_id);
                            echo $get_orders_courier->orders_courier_name.' - '.$get_orders_courier->orders_courier_service;
                        ?>
                        <div class="price_shipping"><small><?php echo ($r->orders_price_shipping==0?'':'Ongkir: '.convertRp($r->orders_price_shipping)) ?></small></div>
                        <div class="price_insurance"><small><?php echo ($r->orders_price_insurance==0?'':'Asuransi: '.convertRp($r->orders_price_insurance)) ?></small></div>
                        <div class="shipping_weight"><small><?php echo ($r->orders_shipping_weight<2?'':'Berat: '.$r->orders_shipping_weight) ?></small></div>
                    </td>
                    <td class="nobr">
                        <div class="product_title"><?php echo $r->orders_product_category_title;?></div>
                        <div class="price_product"><small><?php echo ($r->orders_price_product==0?'':'Harga: '.convertRp($r->orders_price_product)) ?></small></div>
                    </td>
                    <td><?php echo $r->orders_shipping_name;?></td>
                    <td><?php echo getFirstParaNumb($r->orders_shipping_address,40) ?></td>
                    <td><?php echo getFirstParaNumb($r->orders_noted,40) ?></td>
                </tr>
                <?php
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
    </form>

</div>

<script type="text/javascript">
    function check_all_files(e){
        var checkbox = $('input[name="checked_files[]"]');
        if($(e).is(':checked')){
            checkbox.prop('checked', true);
            checkbox.parents('tr').addClass('selected');
        } else {
            checkbox.prop('checked', false);
            checkbox.parents('tr').removeClass('selected');
        }
        calc_check_files();
    }

    calc_check_files();
    function calc_check_files(){
        var form1 = $('form#form1');
        var checked_files = form1.find('input[name="checked_files[]"]:checked');
        $('.btn-cetak').html('<i class="fa fa-print"></i> Cetak Alamat ( '+checked_files.length+' )');
        $('.btn-cetak-simple').html('<i class="fa fa-print"></i> Cetak Simple ( '+checked_files.length+' )');
        $('.btn-delete-multi').html('<i class="fa fa-trash-o"></i> Hapus ( '+checked_files.length+' )');
    }

    $(document).ready(function() {
        $(document).on('change', 'input[name="checked_files[]"]', function(e){
            e.preventDefault();
            if($(this).is(':checked')){
                $(this).parents('tr').addClass('selected');
            } else {
                $(this).parents('tr').removeClass('selected');
            }
            calc_check_files();
        });

        $(document).on('click', '.btn-cetak', function(e){
            e.preventDefault();
            var form1 = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau dicetak.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                var url   = $(this).attr('data-url');
                window.open(url+'?id='+thisId+"&from=print_address", '_blank');
            }
        });

        $(document).on('click', '.btn-cetak-simple', function(e){
            e.preventDefault();
            var form1 = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau dicetak.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                var url   = $(this).attr('data-url');
                window.open(url+'?id='+thisId+"&from=print_address", '_blank');
            }
        });

    });
</script>

<div class="popup popup-print-address">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
    </div>
</div>

<?php js_mask() ?>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.btn-delete-multi', function(e){
            e.preventDefault();
            var form1 = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau dihapus.",
                    type: "error"
                });
            } else {
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function () {

                    var thisId  = "";
                    var thisVal = "";
                    checked_files.each(function() {
                        thisVal = $(this).val();
                        thisId += (thisId=="" ? thisVal : "-" + thisVal);
                    });

                    $.ajax({
                        type: 'POST',
                        url: OWN_LINKS+'/deletes',
                        data: {'thisId':thisId,'thisAction':'delete'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            swal({
                                title: "Success!",
                                text: data.msg,
                                type: "success",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(
                            function () {},
                            function (dismiss) {
                                // if (dismiss === 'timer'){ }
                                setTimeout(function(){
                                    window.location.reload(true);
                                },300);
                            });

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

                }, function (dismiss) {
                  // dismiss can be 'cancel', 'overlay',
                  // 'close', and 'timer'
                  if (dismiss === 'cancel'){ }
                });
            }
        });

    });
</script>