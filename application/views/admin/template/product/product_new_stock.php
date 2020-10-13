<?php js_picker() ?>

<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
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
        <table class="table table-th-block table-dark">
            <colgroup>
                <col width="1">
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Images</th>
                    <th>Nama</th>
                    <th class="nobr text-center">Stok Baru</th>
                    <th class="nobr text-center">Status Stok</th>
                    <th class="nobr text-center">Tanggal</th>
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
                <tr>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id));?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>" class="avatar mfp-fade"></a>
                    </td>
                    <td><a href="<?php echo base_url().'admin/product/view/'.$r->product_id.'-'.changeEnUrl($r->product_name) ?>"><?php echo $r->purchase_detail_name ?></a></td>
                    <td class="nobr text-center"><?php echo $r->purchase_detail_restock ?></td>
                    <td class="nobr text-center"><small><?php echo get_name_product_status($r->product_status_id) ?></small></td>
                    <td class="nobr text-center"><span class="label label-default"><?php echo convDateTimeTable($r->purchase_date) ?></span></td>
                    <td class="nobr">
                        <div class="btn btn-danger btn-xs btn-popup-product" data-id="<?php echo $r->purchase_detail_id ?>" data-toggle="tooltip" data-original-title="Upload Stok"><i class="fa fa-plus"></i> Upload Stok Baru</div>
                    </td>
                </tr>
                <?php
                    $i += 1;
                } 
            } else {
                echo '<tr><td colspan="7">Tidak ditemukan di database.</td></tr>';
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

<div class="popup popup-product">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">
            <div class="popup-content">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.btn-popup-product', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            if($thisVal != ''){
                $.ajax({
                    type: 'POST',
                    url: OWN_LINKS+'/get_product',
                    data: {'thisVal':$thisVal,'thisAction':'get_product'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-product').addClass('active');
                        $('.popup-product .popup-content').html(data.content);
                        setTimeout(function(){
                            js_calc_item_total();
                        },300);
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        alert('error ajax');
                    }
                });
            }
        });
        $(document).on('click', '.popup-product .popup-close', function(){
            // js_cart_load();
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('change keyup', '.click-color-feedback', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $val     = $this.val();
            $this.css("background-color","#"+$val);
            $('.form-color-feedback').fadeOut();
        });
        $(document).on('click', '.click-color-feedback', function(e){
            e.preventDefault();
            var $this    = $(this);
            $this.parents('.has-color-feedback').find('.form-color-feedback').fadeIn();
        });

        $(document).on('click', '.form-color-feedback .feedback', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $val     = $this.attr('data-value');
            $this.parents('.has-color-feedback').find('.click-color-feedback').val($val);
            $this.parents('.has-color-feedback').find('.click-color-feedback').css("background-color","#"+$val);
            $this.parents('.form-color-feedback').fadeOut();
        });
        $(document).on('click', function(e){
            var $clicked = $(e.target);
            if (!$clicked.hasClass('click-color-feedback')){
                $('.form-color-feedback').fadeOut(); 
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.btn-calc-number', function(e){
            e.preventDefault();
            var $this    = $(this);
            var new_qty  = '';
            var result   = $this.parents('.input-group').find('.result-calc-number');
            if(!$(result).is(':disabled') && $.isNumeric(parseInt($(result).val()))){
                new_qty = parseInt($(result).val()) + 1;
                if($this.hasClass('minus')){
                    new_qty = parseInt($(result).val()) - 1;
                    var min_qty = result.attr('min');
                    if(min_qty==''||min_qty==undefined||min_qty=='undefined'){ min_qty = 0; }
                    if(new_qty<=min_qty){ new_qty = min_qty; }
                }
                $(result).val(new_qty);
                js_calc_item_total();
            }
        });

        $(document).on('keyup change', '.calc-count-qty', function(e){
            e.preventDefault();
            js_calc_item_total();
        });
    });

    function js_calc_item_total(){
        var item_stok = $('.result-stok-array').find('.item-stok-array');
        var count_item_stok = 0;
        $(item_stok).each(function(i){
            var this_val = item_stok.eq(i).find('.calc-count-qty').val();
            if(!$.isNumeric(this_val)) { this_val = 0; }
            count_item_stok = count_item_stok + parseInt(this_val);
        });

        var total_old_stok = $('input[name="product_old_stock"').val();
        var total_item_stok = $('input[name="purchase_detail_restock"').val();
        if(count_item_stok > total_item_stok){
            $('.form-restock .restock-add-btn').addClass('disabled');
            swal({
                title: "Error!",
                html: 'Penambahan stok melebihi total stok yang mau diupload!',
                type: "error"
            });
        } else {
            $('.form-restock .restock-add-btn').removeClass('disabled');
        }

        var total_new_stok = (parseInt(total_old_stok) + parseInt(count_item_stok));
        $('#count-item-restock').html(count_item_stok+' dari '+total_item_stok);
        $('#count-item-stok').html(total_old_stok+' + '+count_item_stok+' = '+total_new_stok+' item');
        $('input[name="product_stock"').val(total_new_stok);
    }

    js_calc_item_total();
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.btn-remove-stok-array', function(e){
            e.preventDefault();
            $(this).parents('.item-stok-array').remove();
            js_calc_item_total();
        });
        $(document).on('click', '.btn-add-stok-array', addStokArray );
    });

    function addStokArray() {
        var count_item = 1 + parseInt($('.result-stok-array').find('.item-stok-array').length);

        var $stok_layout = '<tr class="item-stok-array">';
                $stok_layout += '<td class="nobr text-center">';
                    $stok_layout += '<input type="text" name="product_stock_id[]" value="'+count_item+'" class="form-control form-no text-uppercase text-center" maxlength="3" required>';
                $stok_layout += '</td>';
                $stok_layout += '<td>';
                    $stok_layout += '<input type="text" name="product_stock_name[]" value="" class="form-control">';
                $stok_layout += '</td>';
                $stok_layout += '<td class="has-color-feedback">';
                    $stok_layout += '<input type="text" name="product_stock_color[]" value="" class="form-control click-color-feedback" required>';
                    $stok_layout += '<div class="form-color-feedback">';
                        <?php 
                        $get_master_color = get_master_color();
                        foreach ($get_master_color as $mc) { ?>
                            $stok_layout += '<div class="feedback" data-value="<?php echo $mc->color_hex ?>" style="background-color:#<?php echo $mc->color_hex ?>;"><?php echo $mc->color_name ?></div>';
                        <?php } ?>
                    $stok_layout += '</div>';
                $stok_layout += '</td>';
                $stok_layout += '<td class="nobr text-center">';
                    $stok_layout += '<input type="hidden" name="product_stock_old[]" value="0">';
                    $stok_layout += '0';
                $stok_layout += '</td>';
                $stok_layout += '<td class="nobr text-center">';
                    $stok_layout += '<div class="input-group small">';
                        $stok_layout += '<span class="input-group-btn">';
                            $stok_layout += '<span class="btn btn-default btn-sm btn-calc-number minus">';
                                $stok_layout += '<i class="fa fa-minus small"></i>';
                            $stok_layout += '</span>';
                        $stok_layout += '</span>';
                        $stok_layout += '<input type="text" name="product_stock_qty[]" value="0" class="form-control input-sm form-qty-sm result-calc-number text-center calc-count-qty" min="0" maxlength="5">';
                        $stok_layout += '<span class="input-group-btn">';
                            $stok_layout += '<span class="btn btn-default btn-sm btn-calc-number plus">';
                                $stok_layout += '<i class="fa fa-plus small"></i>';
                            $stok_layout += '</span>';
                        $stok_layout += '</span>';
                    $stok_layout += '</div>';
                $stok_layout += '</td>';
                $stok_layout += '<td>';
                    $stok_layout += '<select name="product_stock_status[]" class="form-control">';
                        $stok_layout += '<option value="1" selected>Ready</option>';
                        $stok_layout += '<option value="2">Terjual</option>';
                    $stok_layout += '</select>';
                $stok_layout += '</td>';
                $stok_layout += '<td class="nobr text-center">';
                    $stok_layout += '<a href="javascript:void(0);" class="btn btn-danger btn-remove-stok-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>';
                $stok_layout += '</td>';
            $stok_layout += '</tr>';

        $('.result-stok-array').append($stok_layout);

    }
    <?php if(count($arr_product_stok) == 0){ ?>
        // addStokArray();
    <?php } ?>
</script>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.restock-add-btn:not(.disabled)', function(e){
            e.preventDefault();
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });
            var $this   = $(this);
            var form    = $this.parents('form');
            form.submit();
        });

        $(document).on('submit', 'form.form-restock', restock ); 

        function restock(e){
            if (typeof e !== 'undefined') e.preventDefault();
            var $this = $(this);
            var form = $this;

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

                        swal({
                            title: "Success!",
                            text: data.msg,
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(
                        function () {},
                        function (dismiss) {
                            // console.log('close');
                            // if (dismiss === 'timer') {
                            //     console.log('I was closed by the timer')
                            // }

                            setTimeout(function(){
                                $('.popup-product .popup-close').trigger('click');
                                window.location.reload(true);
                            },300);
                        });

                    } else {
                        swal({
                            title: "Error!",
                            html: data.msg,
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

            return false;
        }
    });
</script>

