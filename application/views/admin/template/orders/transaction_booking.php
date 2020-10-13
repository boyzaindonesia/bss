
<?php js_mask() ?>

<div class="the-box no-border">

    <div class="btn-toolbar toolbar-btn-action">

    </div>

    <div class="panel panel-danger mb-10">
        <div class="panel-heading">
            <div class="right-content">
                <button class="btn btn-danger btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-3"><i class="fa fa-chevron-up"></i></button>
            </div>
            <h3 class="panel-title">Update Orders Product Detail</h3>
        </div>
        <div id="panel-collapse-3" class="collapse in" style="height: auto;">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-8">
                        <form id="formUpload" action="<?php echo $own_links.'/update_orders_product_detail'?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <input type="text" id="datepickerend" name="date_end" class="form-control" value="<?php echo (timestamp()!=''?convDatepickerEnc(timestamp()):'') ?>" data-date-format="dd-mm-yyyy" placeholder="Tanggal Akhir...">
                            </div>
                            <div class="col-sm-2" style="padding-left: 0px; padding-right: 5px;">
                                <input type="hidden" name="thisAction" value="update" />
                                <input type="submit" class="btn btn-danger" value="Update" />
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable-all table-font-small">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Tanggal</th>
                    <th class="nobr">User</th>
                    <th class="nobr">Source</th>
                    <th class="nobr">Invoice</th>
                    <th>Produk</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            if(count($data) > 0){
                foreach($data as $r){
                ?>
                <tr class="" data-id="<?php echo $r->temp_orders_id; ?>" title="">
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="nobr text-center"><span class="label label-default"><?php echo getYearMonthDate($r->temp_orders_date) ?></span><br/><small><?php echo convTime($r->temp_orders_date) ?></small></td>
                    <td class="nobr"><?php echo get_user_name($r->member_id); ?></td>
                    <td class="nobr"><?php echo get_orders_source($r->orders_source_id)->orders_source_name; ?></td>
                    <td class="nobr"><?php echo $r->orders_source_invoice; ?> </td>
                    <td class="nobr">
                        <div class="product-list small no-margin">
                        <?php
                        $product_detail_item = json_decode($r->product_detail_item);
                        foreach ($product_detail_item as $key1 => $val1) {
                        ?>
                        <div class="product-list-item no-bg magnific-popup-wrap" data-id="<?php echo $val1->id ?>">
                            <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($val1->id));?>">
                                <div class="product-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($val1->id));?>);"></div>
                            </a>
                            <div class="product-list-content">
                                <h5 class="no-margin no-padding"><?php echo $val1->code ?> - <?php echo $val1->name;?></h5>
                                <div><?php echo convertRp($val1->price_sale) ?> Total: <?php echo $val1->qty;?> pcs</div>
                            </div>
                            <div class="relative">
                                <?php if($val1->stock_detail != 'null'){ ?>
                                <ul class="product-list-item-stock clearfix mt-5 mb-0">
                                    <?php
                                    $stock_detail = $val1->stock_detail;
                                    foreach ($stock_detail as $key => $value) { ?>
                                        <li><p><?php echo $value->id ?>. <?php echo $value->name ?> = <?php echo $value->qty ?></p></li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    </td>
                    <td class="nobr text-center">
                        <div class="btn btn-info btn-xs btn-sinkron" data-id="<?php echo $r->temp_orders_id ?>"><i class="fa fa-recycle"></i> Sinkron</div>

                        <a href="<?php echo $own_links.'/canceled_booked/'.$r->temp_orders_id ?>" class="btn btn-warning btn-xs btn-delete"  onclick="return confirm('Yakin ingin dibatalkan ? Stok akan dikembalikan');" data-toggle="tooltip" data-original-title="Batalkan Booking"><i class="fa fa-times"></i></a>
                        <a href="<?php echo $own_links.'/deleted_booked/'.$r->temp_orders_id ?>" class="btn btn-danger btn-xs btn-delete"  onclick="return confirm('Yakin ingin dihapus ? Stok tidak dikembalikan');" data-toggle="tooltip" data-original-title="Hapus Booking"><i class="fa fa-times"></i></a>
                    </td>
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


<div class="popup popup-orders">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
    </div>
</div>

<?php js_chosen() ?>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.btn-sinkron', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });
            if($thisVal != ''){
                setTimeout(function(){
                    $.ajax({
                        type: 'POST',
                        url: OWN_LINKS+'/form_sinkron',
                        data: {'thisVal':$thisVal,'thisAction':'view'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            $('.popup-orders').addClass('active');
                            $('.popup-orders .popup-content').html(data.content);
                            // console.log(data.result);
                            $(function () {
                                "use strict";
                                var configChosen = {
                                  '.chosen-select'           : {},
                                  '.chosen-select-deselect'  : {allow_single_deselect:true},
                                  '.chosen-select-no-single' : {disable_search_threshold:10},
                                  '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                                  '.chosen-select-width'     : {width:"100%"}
                                }
                                for (var selector in configChosen) {
                                  $(selector).chosen(configChosen[selector]);
                                }
                            });
                            swal({
                                title: "Loading!",
                                text: "",
                                type: "loading",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                customClass: 'swal2-small',
                                timer: 400
                            }).catch(swal.noop);
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
                },400);
            }
        });

    });
</script>