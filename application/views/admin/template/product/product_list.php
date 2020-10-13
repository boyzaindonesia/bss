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
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Status</label>
                                <div class="btn-group">
                                    <?php $product_status_id = $this->jCfg['search']['product_status_id']; ?>
                                    <select name="product_status_id" class="form-control" onchange="this.form.submit()">
                                        <?php
                                        $product_status = get_product_status();
                                        foreach ($product_status as $k => $v) { ?>
                                        <option value="<?php echo $v->product_status_id; ?>" <?php echo ($v->product_status_id==$product_status_id?'selected':'') ?> ><?php echo $v->product_status_name; ?></option>
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
                                        <option value="product_date_push-desc" <?php echo ($order_by=='product_date_push'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="product_date_push-asc" <?php echo ($order_by=='product_date_push'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="product_name-asc" <?php echo ($order_by=='product_name'&&$order_dir=='asc'?'selected':'') ?> >Name: A to Z</option>
                                        <option value="product_name-desc" <?php echo ($order_by=='product_name'&&$order_dir=='desc'?'selected':'') ?> >Name: Z to A</option>
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

    <div class="relative no-border mt-20">
        <div class="product-list mb-0">
            <?php
            if(count($data) > 0){
                foreach($data as $r){
            ?>
            <div class="product-list-item magnific-popup-wrap" data-id="<?php echo $r->product_id ?>">
                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id));?>">
                    <div class="product-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>);"></div>
                </a>
                <div class="hide">
                    <?php
                    $get_image_detail = get_image_detail($r->product_id);
                    $ii = 0;
                    foreach ($get_image_detail as $key) {
                        if($ii > 0){
                        ?>
                    <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".$key->image_filename);?>">&nbsp;</a>
                    <?php
                        }
                        $ii += 1;
                    } ?>
                </div>

                <div class="product-list-content">
                    <p class="product-list-title no-margin no-padding mb-5"><strong><?php echo $r->product_name.' - '.$r->product_code;?></strong></p>

                    <?php if($this->store_id == 1){ ?>
                    <h6 class="no-margin no-padding mb-5"><span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?></h6>

                    <?php if(isset($r->product_price_grosir) && $r->product_price_grosir != ''){ ?>
                    <table border="0" style="font-size: 10px; line-height: 14px; margin-bottom: 5px;">
                        <colgroup>
                            <col>
                            <col>
                        </colgroup>
                        <tbody>
                            <?php
                            foreach ($r->product_price_grosir as $key => $value) { ?>
                            <tr>
                                <td style="padding: 0px;">
                                    <?php echo $value->name ?> barang
                                </td>
                                <td style="padding: 0px;">
                                    <?php echo convertRP($value->price) ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                    <?php } else { ?>
                        <?php $reseller_price = get_reseller_price($this->store_id, $r->product_id); ?>
                        <h6 class="no-margin no-padding mb-5"><?php echo convertRp($reseller_price) ?></h6>
                    <?php } ?>

                    <?php if($this->store_id == 1){ ?>
                    <div class="product-list-action">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-cog"></i> Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:void(0);" class="btn-popup-product-stock" data-id="<?php echo $r->product_id ?>">Update Stok</a></li>
                                <?php if($r->product_status_id == 1){ ?>
                                <li><a href="<?php echo $url_parent.'/setnotsale/'.$r->product_id.'-'.changeEnUrl($r->product_name).'?sendnotif=1&next='.$own_links;?>" onclick="return confirm('Are you sure set not sale ?');">SoldOut</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="relative">
                    <p class="no-margin no-padding">Stok: <?php echo $r->product_stock;?> pcs</p>
                    <?php if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){ ?>
                    <ul class="product-list-item-stock clearfix mb-0">
                        <?php
                        foreach ($r->product_stock_detail as $key => $value) {
                            if($value->status==1){ ?>
                            <li><p><?php echo $value->name ?> = <?php echo $value->qty ?></p></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </div>
            </div>
            <?php
                }
            } else {
                echo 'Produk tidak ditemukan...';
            }
            ?>
        </div>

    </div>

    <?php if(isset($paging) && $paging!=''){ ?>
    <div class="btn-toolbar mt-20 mb-20" role="toolbar">
        <div class="btn-group pull-right">
            <?php echo $paging ?>
        </div>
    </div>
    <?php } ?>

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

<?php if($this->store_id == 1){ ?>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click','input[type="text"]',function(){ this.select(); });

        $(document).on('click', '.btn-popup-product-stock', function(e){
            e.preventDefault();
            var $this    = $(this);
            var $thisVal = $this.attr('data-id');
            $('html, body').css('overflow','hidden');
            if($thisVal != ''){
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $url_parent ?>/view_stock_detail',
                    data: {'thisVal':$thisVal,'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-product').addClass('active');
                        $('.popup-product .popup-content').html(data.content);
                        console.log(data.result);

                        $(document).on('click','input[type="text"]',function(){ this.select(); });
                        $(document).on('click','input[type="number"]',function(){ this.select(); });
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
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('submit', 'form.form_save_stock_detail', save_stock_detail );
        function save_stock_detail(e){
            if (typeof e !== 'undefined') e.preventDefault();
            var $this = $(this);
            var form = $this;

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

                            if(data.url != ""){
                                window.open(data.url, '_blank');
                            }

                            setTimeout(function(){
                                $('.popup-product .popup-close').trigger('click');
                                window.location.reload(true);
                            },1000);
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

<script type="text/javascript">
    function checkProductVariant(){
        if($('.chkVariant').is(':checked')){
            $('.groupStock').attr('disabled', 'disabled');
            $('.groupVariant').removeClass('hide');
            var groupStockQty = $('.groupStock').val();
            var countItems = $('.result-stok-array').find('.item-stok-array').length;
            if(countItems == 0){ addStokArray(); }
        } else {
            $('.groupStock').removeAttr('disabled');
            $('.groupVariant').addClass('hide');
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('keyup change', '.calc-count-qty', function(e){
            e.preventDefault();
            var $this = $(this);
            var qty   = $this.val();
            if(qty > 0){
                $this.parents('tr.item-stok-array').find('select[name="product_stock_status[]"]').val("1");
            } else {
                $this.parents('tr.item-stok-array').find('select[name="product_stock_status[]"]').val("2");
            }
            js_calc_item_total();
        });
        $(document).on('blur', '.calc-count-qty', function(e){
            e.preventDefault();
            var $this = $(this);
            var qty   = $this.val();
            if(qty == ""){ $this.val("0"); }
        });

        $(document).on('change', 'select[name="product_status_id"]', function(e){
            e.preventDefault();
            var $this = $(this);
            var id    = $this.val();
            var item_stok = $('.result-stok-array').find('.item-stok-array');
            if(id == "1"){
                $(item_stok).each(function(i){
                    var this_val = item_stok.eq(i).find('.calc-count-qty').val();
                    if(!$.isNumeric(this_val)) { this_val = 0; }
                    var setVal = "2";
                    if(this_val > 0){ setVal = "1"; }
                    item_stok.eq(i).find('select[name="product_stock_status[]"]').val(setVal);
                });
            } else {
                $(item_stok).each(function(i){
                    var setVal = "2";
                    item_stok.eq(i).find('select[name="product_stock_status[]"]').val(setVal);
                });
            }
        });

    });

    function js_calc_item_total(){
        var item_stok = $('.result-stok-array').find('.item-stok-array');
        var countItems = 0;
        var count_item_stok = 0;
        var last_name = "";
        $(item_stok).each(function(i){
            countItems = countItems + 1;
            var this_val = item_stok.eq(i).find('.calc-count-qty').val();
            if(!$.isNumeric(this_val)) { this_val = 0; }
            count_item_stok = count_item_stok + parseInt(this_val);
            last_name = item_stok.eq(i).find('input[name="product_stock_name[]"]').val();
        });
        $('.result-stok-array').attr("data-last-name", last_name);
        $('#count-item-stok').html(count_item_stok+" item");
        $('.groupStock').val(count_item_stok);
        if(countItems < 1){
            $('.chkVariant').prop( "checked", false );
            $('.groupStock').removeAttr('disabled');
            $('.groupVariant').addClass('hide');
        }
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
        var next_name  = $('.result-stok-array').attr("data-last-name");

        var count_item = 1 + parseInt($('.result-stok-array').find('.item-stok-array').length);
        if(count_item.toString().length == 1){ count_item = '0'+count_item; }
        var $stok_layout = '<tr class="item-stok-array">';
                $stok_layout += '<td>';
                    $stok_layout += '<input type="text" name="product_stock_id[]" value="'+count_item+'" class="form-control form-no text-uppercase text-center" maxlength="2" required>';
                $stok_layout += '</td>';
                $stok_layout += '<td>';
                    $stok_layout += '<input type="text" name="product_stock_name[]" value="'+next_name+'" class="form-control">';
                $stok_layout += '</td>';
                $stok_layout += '<td class="nobr text-center">';
                    $stok_layout += '<input type="hidden" name="product_stock_old[]" value="0">';
                    $stok_layout += '<input type="text" name="product_stock_qty[]" value="0" class="form-control calc-count-qty" maxlength="5">';
                $stok_layout += '</td>';
                $stok_layout += '<td>';
                    $stok_layout += '<select name="product_stock_status[]" class="form-control">';
                        $stok_layout += '<option value="1">Ready</option>';
                        $stok_layout += '<option value="2" selected>Terjual</option>';
                    $stok_layout += '</select>';
                $stok_layout += '</td>';
                $stok_layout += '<td class="nobr text-center">';
                    $stok_layout += '<input type="hidden" name="product_stock_color[]" value=""/>';
                    $stok_layout += '<a href="javascript:void(0);" class="btn btn-danger btn-remove-stok-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>';
                $stok_layout += '</td>';
            $stok_layout += '</tr>';

        $('.result-stok-array').append($stok_layout);
        $('.result-stok-array').attr("data-last-name", last_name);

    }
    <?php if(count($arr_product_stok) == 0){ ?>
        // addStokArray();
    <?php } ?>
</script>

<?php } ?>
