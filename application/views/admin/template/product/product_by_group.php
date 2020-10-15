<script src="<?php echo base_url()?>assets/plugins/playsound/playSound.js"></script>
<script type="text/javascript">
    function playSuccess(){ $.playSound('<?php echo base_url()?>assets/collections/audio/success.wav'); }
    function playError(){ $.playSound('<?php echo base_url()?>assets/collections/audio/error.wav'); }
</script>

<?php js_mask() ?>

<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>

        <div class="btn-group pull-right">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-download"></i> Download <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo base_url().'admin/product_process/download_all_list_stock' ?>" target="_blank">All Stok Produk</a></li>
                <li><a href="<?php echo base_url().'admin/product_process/download_all_data_product' ?>" target="_blank">All Data Produk</a></li>
            </ul>
        </div>
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-warning btn-upload">
                <i class="fa fa-upload"></i> Upload
            </button>
        </div>
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-danger btn-update-stock-barcode" data-url="<?php echo base_url().'admin/product/form_update_stock_barcode' ?>">
                <i class="fa fa-exchange"></i> Pengurangan Stok
            </button>
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
            // debugCode($data);
            if(count($data) > 0){
                foreach($data as $r){
            ?>
            <div class="product-list-item magnific-popup-wrap mb-10" data-id="<?php echo $r->id ?>">
                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/large/".$r->images_cover);?>">
                    <div class="product-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".$r->images_cover);?>);"></div>
                </a>
                <div class="product-list-content">
                    <p class="product-list-title no-margin no-padding" style="max-width: 70%"><strong><?php echo $r->allcode; ?> (<?php echo $r->day_on_going ?>)</strong></p>
                    <p class="product-list-title no-margin no-padding"><strong><?php echo $r->name; ?></strong></p>
                    <p class="no-margin no-padding">Beli: <?php echo convertRp($r->price_buy) ?>, Jual: <?php echo convertRp($r->price_sale) ?>
                        <button type="button" class="btn btn-info btn-xs btn-popup-update-price-product" data-id="<?php echo $r->array_product_id ?>" data-toggle="tooltip" data-original-title="Edit Harga"><i class="fa fa-pencil"></i></button>
                    </p>
                    <p class="no-margin no-padding mb-5">Margin: <?php echo convertRp(($r->price_sale - $r->price_buy)) ?></p>
                    <div class="product-list-action">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-clone"></i> Copy Variasi <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <?php foreach ($r->varian_text as $key => $val) { ?>
                                <li><a href="javascript:void(0);" class="btn-copy-clipboard" data-text-clipboard="<?php echo $val->varian ?>"><?php echo $val->title ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="btn-group pull-right mr-5">
                            <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-clone"></i> Copy Last Stock <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <?php foreach ($r->varian_date_stock as $key => $val) { ?>
                                <li><a href="javascript:void(0);" class="btn-copy-clipboard" data-text-clipboard="<?php echo $val->varian ?>"><?php echo $val->title ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <p class="no-margin no-padding mb-5">Stok: <?php echo $r->total_stock;?> pcs, total beli <?php echo convertRp(($r->price_buy * $r->total_stock)) ?>, total jual <?php echo convertRp(($r->price_sale * $r->total_stock)) ?>, terjual: <?php echo $r->product_sold; ?> pcs</p>
                    <div class="relative">
                        <?php echo $r->item_html ?>
                    </div>
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

</div>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.btn-upload', function(e){
            e.preventDefault();
            $('html, body').css('overflow','hidden');
            $('.popup-upload').addClass('active');
            $('#formUpload')[0].reset();
        });

        $(document).on('click', '.btn-update-stock-barcode', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });

            setTimeout(function(){
                $.ajax({
                    type: 'POST',
                    url: dataUrl,
                    data: {'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-product').addClass('active');
                        $('.popup-product .popup-content').html(data.content);
                        // console.log(data.content);

                        setTimeout(function(){
                            document.getElementById("product_barcode").focus();
                        }, 500);

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
            }, 500);
        });

        $(document).on('blur', '#form_save_update_stock_barcode #product_barcode', function(e){
            e.preventDefault();
            var $this   = $(this);
            setTimeout(function(){
                if($this.val() != "" && !$this.parents('form').hasClass("loading")){
                    $this.parents('form').find('button[type="submit"]').trigger('click');
                }
            }, 500);
        });

        $(document).on('submit', 'form.form_save_update_stock_barcode', save_update_stock_barcode );
        function save_update_stock_barcode(e){
            if (typeof e !== 'undefined') e.preventDefault();
            var $this = $(this);
            var form = $this;

            form.addClass("loading");

            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });

            setTimeout(function(){
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
                                console.log(data.result_last_update);

                        if(data.err == false){
                            playSuccess();
                            swal({
                                title: "Success!",
                                text: data.msg,
                                type: "success",
                                showConfirmButton: false,
                                timer: 1000
                            }).then(
                            function () {},
                            function (dismiss) {
                                // console.log('close');
                                // if (dismiss === 'timer') {
                                //     console.log('I was closed by the timer')
                                // }
                                var li_item = "";
                                $(data.result_last_update).each(function(i, item){
                                    li_item += "<li>"+item+"</li>";
                                });
                                $('#result_update_stock_barcode').html(li_item);
                                $("#form_save_update_stock_barcode")[0].reset();
                                setTimeout(function(){
                                    form.removeClass("loading");
                                    document.getElementById("product_barcode").focus();
                                }, 500);
                            });
                        } else {
                            playError();
                            swal({
                                title: "Error!",
                                text: data.msg,
                                type: "error"
                            }).then(function () {
                                setTimeout(function(){
                                    $("#form_save_update_stock_barcode")[0].reset();
                                    form.removeClass("loading");
                                }, 500);
                            });
                        }
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        playError();
                        swal({
                            title: "Error!",
                            text: response,
                            type: "error"
                        }).then(function () {
                            setTimeout(function(){
                                $("#form_save_update_stock_barcode")[0].reset();
                                form.removeClass("loading");
                            }, 500);
                        });
                    }
                });

                return false;
            }, 300);
        }

        <?php if(isset($_GET['download']) && $_GET['download'] != ""){ ?>
            window.open("<?php echo $_GET['download'] ?>", '_blank');
        <?php } ?>

    });
</script>

<div class="popup popup-upload">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="false"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">
            <div class="popup-content">

                <form id="formUpload" action="<?php echo base_url() ?>admin/product_process/save_upload_product" method="post" autocomplete="off" enctype="multipart/form-data">
                    <legend>Upload</legend>
                    <div class="row popup-content-product mb-20">
                        <div class="col-sm-4" style="padding-right: 5px;">
                            <select name="type_name" class="form-control" required>
                                <option value="" selected>--- Pilih ---</option>
                                <option value="insert_sku_shopee">Insert SKU Shopee</option>
                                <option value="insert_sku_tokopedia">Insert SKU Tokopedia</option>
                                <option value="insert_sku_bukalapak">Insert SKU Bukalapak</option>
                                <!-- <option value="update_stock_website">Update Stok Website</option> -->
                                <option value="update_stock_shopee">Update Stok Shopee</option>
                                <option value="update_stock_tokopedia">Update Stok Tokopedia</option>
                                <option value="update_stock_lazada">Update Stok Lazada</option>
                                <option value="update_price">Update Harga</option>
                            </select>
                        </div>
                        <div class="col-sm-8" style="padding-left: 0px; padding-right: 5px;">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="files" required />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div>Stok Belum Dibayar (optional)</div>
                    <div class="row popup-content-product mb-20">
                        <div class="col-sm-8" style="padding-right: 5px;">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="files_temp" />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <input type="hidden" name="thisAction" value="upload" />
                        <input type="hidden" name="thisUrl" value="<?php echo $own_links ?>" />
                        <button type="submit" name="save_update" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-default popup-close" data-remove-content="false">Close</button>
                    </div>
                </form>

            </div>
        </div>
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

        $(document).on('click', '.btn-popup-update-price-product', function(e){
            e.preventDefault();
            var $this   = $(this);
            swal({
                title: "Loading!",
                text: "",
                type: "loading",
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: 'swal2-small'
            });

            setTimeout(function(){
                var thisId = $this.attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url() ?>admin/product/form_update_price_multiple',
                    data: {'thisVal':thisId,'thisAction':'view'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        $('.popup-product').addClass('active');
                        $('.popup-product .popup-content').html(data.content);
                        console.log(data.result);

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
            }, 500);
        });

        $(document).on('submit', 'form.form_save_product', save_product );
        function save_product(e){
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

            setTimeout(function(){
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
            }, 500);
        }

    });
    function enabledPriceGrosir(){
        if($('input[name="chk_product_price_grosir"]').is(':checked')){
            $('.PriceGrosir').show();
        } else {
            $('.PriceGrosir').hide();
        }
    }
</script>

