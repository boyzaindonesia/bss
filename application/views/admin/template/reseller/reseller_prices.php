<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <?php js_mask() ?>

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

    <?php if(check_action($links_table_item,'edit')){ ?>
    <div class="btn-toolbar">
        <div class="btn btn-info btn-multi-update"><i class="fa fa-pencil"></i> Update Harga ( 0 )</div>
    </div>
    <?php } ?>

    <div class="relative no-border mt-20">
        <div class="product-list mb-0">
            <?php
            if(count($data) > 0){
                foreach($data as $r){
            ?>
            <div class="product-list-item magnific-popup-wrap" data-id="<?php echo $r->product_id ?>">

                <?php if(check_action($links_table_item,'edit')){ ?>
                <input type="checkbox" name="checked_files[]" value="<?php echo $r->product_id;?>" style="position:absolute; top: 10px; left: 10px; z-index: 9;">
                <?php } ?>

                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id));?>">
                    <div class="product-list-image" style="left: 30px; background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>);"></div>
                </a>

                <div class="product-list-content" style="margin-left: 25px;">
                    <p class="product-list-title no-margin no-padding mb-0"><strong><?php echo $r->product_name;?></strong></p>
                    <table border="0" style="font-size: 12px; line-height: 16px; margin-bottom: 5px;">
                        <colgroup>
                            <col>
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td style="padding: 0px; padding-right: 20px;">
                                    Harga Beli
                                </td>
                                <td style="padding: 0px;">
                                    : <?php echo convertRP($r->product_price_buy) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px;">
                                    Harga Jual
                                </td>
                                <td style="padding: 0px;">
                                    : <?php echo convertRP($r->product_price_sale) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px;" colspan="2">
                                    <strong>RESELLER</strong>
                                </td>
                            </tr>
                            <?php
                            $get_store = get_store();
                            foreach ($get_store as $key => $value) {
                                if($value->store_id != 1){
                            ?>
                            <tr>
                                <td style="padding: 0px;">
                                    <?php echo $value->store_name ?>
                                </td>
                                <td style="padding: 0px;">
                                    <?php $reseller_price = get_reseller_price($value->store_id, $r->product_id); ?>
                                    : <span class="<?php echo ($reseller_price==0?'text-danger':'') ?>"><?php echo convertRp($reseller_price) ?></span>
                                </td>
                            </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if(check_action($links_table_item,'edit')){ ?>
                    <div class="product-list-action">
                        <div class="btn btn-info btn-xs btn-popup-product" data-id="<?php echo $r->product_id ?>"><i class="fa fa-pencil"></i> Edit</div>
                    </div>
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

<script type="text/javascript">
<?php if(check_action($links_table_item,'edit')){ ?>

calc_check_files();
function calc_check_files(){
    var product_list = $('.product-list');
    var checked_files = product_list.find('input[name="checked_files[]"]:checked');
    $('.btn-multi-update').html('<i class="fa fa-pencil"></i> Update Harga ( '+checked_files.length+' )');
}

$(document).ready(function() {

    $(document).on('change', 'input[name="checked_files[]"]', function(e){
        e.preventDefault();
        if($(this).is(':checked')){
            $(this).parents('.product-list-item').addClass('selected');
        } else {
            $(this).parents('.product-list-item').removeClass('selected');
        }
        calc_check_files();
    });

    function get_reseller_prices($thisVal){
        $('html, body').css('overflow','hidden');
        $.ajax({
            type: 'POST',
            url: OWN_LINKS+'/get_reseller_prices',
            data: {'thisVal':$thisVal,'thisAction':'get_reseller_prices'},
            async: false,
            cache: false,
            dataType: 'json',
            success: function(data){
                $('.popup-product').addClass('active');
                $('.popup-product .popup-content').html(data.content);
                // setTimeout(function(){
                //     js_calc_item_total();
                // },300);

                $('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
                $('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
                $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});
            },
            error: function(jqXHR){
                var response = jqXHR.responseText;
                alert('error ajax');
            }
        });
    }
    $(document).on('click', '.btn-multi-update', function(e){
        e.preventDefault();
        var product_list = $('.product-list');
        var checked_files = product_list.find('input[name="checked_files[]"]:checked');
        if(checked_files.length == 0){
            swal({
                title: "Error!",
                text: "Ceklis yang mau diupdate.",
                type: "error"
            });
        } else {
            var thisId  = "";
            var thisVal = "";
            checked_files.each(function() {
                thisVal = $(this).val();
                thisId += (thisId=="" ? thisVal : "-" + thisVal);
            });

            var $thisVal = thisId;
            get_reseller_prices($thisVal);

            // var url   = $(this).attr('data-url');
            // window.open(url+'?id='+thisId, '_blank');
        }
    });

    $(document).on('click', '.btn-popup-product', function(e){
        e.preventDefault();
        var $this    = $(this);
        var $thisVal = $this.attr('data-id');
        $('html, body').css('overflow','hidden');
        if($thisVal != ''){
            get_reseller_prices($thisVal);
        }
    });
    $(document).on('click', '.popup-product .popup-close', function(){
        // js_cart_load();
    });

    $(document).on('click', '.update-harga-btn:not(.disabled)', function(e){
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

    $(document).on('submit', 'form.form-update-harga', restock );

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

<?php } ?>
</script>
