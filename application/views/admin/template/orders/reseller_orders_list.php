
<?php js_picker() ?>
<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group"><a href="<?php echo $own_links ?>/add" class="btn btn-success"><i class="fa fa-plus"></i> Buat Pesanan</a></div>
        <div class="btn-group">
            <div class="btn btn-info btn-payment"><i class="fa fa-paper-plane"></i> Bayar</div>
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
                                        <option value="mt_store_orders.store_orders_date-desc" <?php echo ($order_by=='mt_store_orders.store_orders_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="mt_store_orders.store_orders_date-asc" <?php echo ($order_by=='mt_store_orders.store_orders_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="mt_store_orders.store_orders_name-asc" <?php echo ($order_by=='mt_store_orders.store_orders_name'&&$order_dir=='asc'?'selected':'') ?> >Nama Customer: A to Z</option>
                                        <option value="mt_store_orders.store_orders_name-desc" <?php echo ($order_by=='mt_store_orders.store_orders_name'&&$order_dir=='desc'?'selected':'') ?> >Nama Customer: Z to A</option>
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
        <form id="form1" action="" method="post" enctype="multipart/form-data">
            <table class="table table-th-block table-dark">
                <colgroup>
                    <col width="1">
                    <col width="1">
                    <col width="1">
                    <col>
                    <col width="1">
                </colgroup>
                <thead>
                    <tr>
                        <th class="nobr text-center no-sort">No</th>
                        <th colspan="2" class="nobr text-center">No Order</th>
                        <th>Nama</th>
                        <th class="nobr text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if(count($data) > 0){
                    $i = 1;
                    $pageNumber = $this->uri->segment(4);
                    if(!empty($pageNumber)){
                        $i = $pageNumber + 1;
                    }
                    foreach($data as $r){ ?>
                    <tr class="odd">
                        <td class="nobr text-center"><?php echo $i ?>.</td>
                        <td colspan="2" class="nobr">
                            <div><?php echo $r->store_orders_code; ?></div>
                            <small><?php echo convertRp($r->orders_price_grand_total);?></small>
                        </td>
                        <td title="<?php echo $r->store_orders_noted; ?>">
                            <div><?php echo $r->store_orders_name;?></div>
                            <span class="label label-default"><small><?php echo convDateTimeTable($r->store_orders_date) ?></small></span>
                        </td>
                        <td class="nobr">

                        </td>
                    </tr>

                    <?php
                        $store_orders_detail = $r->orders_detail;
                        foreach ($store_orders_detail as $k2 => $v2) {
                    ?>
                    <tr>
                        <td></td>
                        <td class="nobr text-left" style="padding-left: 0px; padding-right: 0px">

                        </td>
                        <td colspan="3" class="nobr">
                            <div><?php echo $v2->product_name; ?></div>
                            <small><?php echo convertRp($v2->orders_detail_price); ?> x <?php echo $v2->orders_detail_qty; ?> = <?php echo convertRp(($v2->orders_detail_price * $v2->orders_detail_qty )); ?> </small>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>

                    <?php
                        $i += 1;
                    }
                } else {
                    echo '<tr><td colspan="5">Tidak ditemukan di database.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </form>
        <?php if(isset($paging) && $paging!=''){ ?>
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group pull-right">
                <?php echo $paging ?>
            </div>
        </div>
        <?php } ?>
    </div>

</div>

<div class="popup popup-payment">
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
    </div>
</div>

<?php js_mask() ?>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.btn-payment', function(e){
            e.preventDefault();
            var $this = $(this);
            var form1 = $('form#form1');
            $('html, body').css('overflow','hidden');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });
            $.ajax({
                type: 'POST',
                url: OWN_LINKS+'/form_payment',
                data: {'thisAction':'payment'},
                async: false,
                cache: false,
                dataType: 'json',
                success: function(data){
                    if(data.err == false){

                        $('.popup-payment').addClass('active');
                        $('.popup-payment .popup-content').html(data.content);
                        // console.log(data.result);

                        $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                        $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                        $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});

                        swal({
                            title: "Loading!",
                            text: "",
                            type: "loading",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            customClass: 'swal2-small',
                            timer: 400
                        }).catch(swal.noop);
                    } else {
                        var data_msg = '';
                        if(data.msg != ''){
                            $.each( data.msg, function( key, value ) {
                                data_msg += '<li>'+value+'</li>';
                            });
                        }

                        swal({
                            title: "Error!",
                            html: "<ul class='text-left'>"+data_msg+"</ul>",
                            type: "error"
                        });
                    }
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

        $(document).on('submit', 'form.form_save_payment', function(e){
            e.preventDefault();
            var $this   = $(this);
            var form    = $this;

            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                async: false,
                cache: false,
                dataType: 'json',
                beforeSend: function(){

                },
                success: function(data){
                    if(data.err == false){
                        $('html,body').animate({ scrollTop: 0}, 200);
                        swal({
                            title: "Success!",
                            text: "Berhasil simpan pembayaran!",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(
                        function () {},
                        function (dismiss) {
                            setTimeout(function(){
                                if(data.href != ''){
                                    window.location.href = data.href;
                                } else {
                                    window.location.reload(true);
                                }
                            },300);
                        });
                    } else {
                        var data_msg = '';
                        if(data.msg != ''){
                            $.each( data.msg, function( key, value ) {
                                data_msg += '<li>'+value+'</li>';
                            });
                        }
                        swal({
                            title: "Error!",
                            html: "<ul class='text-left'>"+data_msg+"</ul>",
                            type: "error"
                        });
                    }
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

    });

</script>
