<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <div class="panel panel-dark panel-block-color">
        <div class="panel-heading">
            <h3 class="panel-title text-uppercase">FORM</h3>
        </div>
        <div class="panel-body">

            <div class="form-horizontal">
                <form action="" method="post" enctype="multipart/form-data">
                    <legend>Cek ajax</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-5 has-feedback">
                            <input type="text" name="user_name" value="" class="form-control check" data-check-id="" data-check-parent="" data-check-rel="user_name" data-check-msg="Username belum ada." data-check-err="Username sudah ada." minlength="4" maxlength="25" required>
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email belum ada</label>
                        <div class="col-sm-5 has-feedback">
                            <input type="email" name="user_email" value="" class="form-control check" data-check-id="" data-check-parent="" data-check-rel="user_email" data-check-msg="" data-check-err="Email sudah ada." maxlength="255" required>
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email sudah ada</label>
                        <div class="col-sm-5 has-feedback">
                            <input type="email" name="user_email" value="<?php echo isset($user->user_email)?$user->user_email:'';?>" class="form-control check" data-check-id="<?php echo isset($user->user_id)?$user->user_id:'';?>" data-check-parent="" data-check-rel="user_email" data-check-msg="" data-check-err="Email sudah ada." maxlength="255" required>
                            <span class="fa form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="id" value="">
                            <input type="submit" class="btn btn-danger" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="form-horizontal">
                <form action="" method="post" enctype="multipart/form-data">
                    <legend>Cek konfirmasi password</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-5">
                            <input type="password" name="passwd" id="passwd" class="form-control" minlength="6" required>
                            <span class="help-block">Min 6 Character</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Konfirmasi Password</label>
                        <div class="col-sm-5">
                            <input type="password" name="passwd2" id="confirm_passwd" class="form-control" minlength="6" required>
                            <span class="help-block">Min 6 Character</span>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="id" value="">
                            <input type="submit" class="btn btn-danger" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="form-horizontal">
                <form action="" method="post" enctype="multipart/form-data">
                    <legend>Location</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">City chosen select <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <select name="city[]" data-placeholder="--- Select City ---" class="form-control chosen-select" multiple >
                                <?php echo option_city_chosen(); ?>
                            </select>
                            <?php js_chosen() ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">City chosen city <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <select name="city" data-placeholder="--- Select ---" class="form-control chosen-city chosen-select" required>
                                <option value=""></option>
                                <?php echo option_city_chosen(); ?>
                            </select>
                            <?php js_chosen() ?>
                            <?php js_chosen_city() ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Propinsi</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="province" value="" />
                            <input type="text" name="province_name" value="" class="form-control" disabled />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Country</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="country" value="" />
                            <input type="text" name="country_name" value="" class="form-control" disabled />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Propinsi 1</label>
                        <div class="col-sm-5">
                            <select name="province" data-province class="form-control">
                                <option value="" selected>--- Pilih ---</option>
                                <?php $province = isset($val->orders_shipping_province)?$val->orders_shipping_province:'';?>
                                <?php  echo option_province($province); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kota 1</label>
                        <div class="col-sm-5">
                            <select name="city" data-city class="form-control">
                                <option value="" selected>--- Pilih ---</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kota 2</label>
                        <div class="col-sm-5">
                            <input type="text" id="search_city_array" name="city" value="" class="form-control" placeholder="Cari kota..." />
                            <div id="search_city_array_feedback"></div>
                            <script type="text/javascript">
                                <?php
                                $data = get_autoComplete_city();
                                $new_data = array();
                                foreach ($data as $key => $value) {
                                    $new_data[] = array('label'      => $value->city_title,
                                                        'city_id'    => $value->city_id,
                                                        'city_title' => $value->city_title,
                                                        'province_id' => $value->province_id,
                                                        'province_title'  => $value->province_title
                                                  );
                                }
                                ?>
                                var CityArray = <?php echo json_encode($new_data) ?>;
                                var cg = new customGeneral();
                                    cg.autoCompleteCity(CityArray, "#search_city_array");
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Propinsi 2</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="province" data-autocomplete-province-2 value="" />
                            <input type="text" name="province_name" data-autocomplete-province-name-2 value="" class="form-control" disabled />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kota Combobox</label>
                        <div class="col-sm-5">
                            <select name="city" id="search_city_combobox" class="form-control">
                                <option value="" selected>--- Pilih ---</option>
                                <?php echo option_city_chosen(); ?>
                            </select>
                            <link href="<?php echo base_url();?>assets/plugins/jquery-ui/jquery-ui-autocomplete-combobox.css" rel="stylesheet">
                            <script src="<?php echo base_url();?>assets/plugins/jquery-ui/jquery-ui-autocomplete-combobox.js"></script>
                            <script type="text/javascript">
                                $( "#search_city_combobox" ).combobox();
                                $( "#toggle" ).on( "click", function() {
                                    $( "#search_city_combobox" ).toggle();
                                });
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Propinsi Combobox</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="province" data-autocomplete-province-combobox value="" />
                            <input type="text" name="province_name" data-autocomplete-province-name-combobox value="" class="form-control" disabled />
                        </div>
                    </div>

                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="id" value="">
                            <input type="submit" class="btn btn-danger" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="form-horizontal">
                <form action="" method="post" enctype="multipart/form-data">
                    <legend>Location terisi</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">City chosen select <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <select name="city[]" data-placeholder="--- Select City ---" class="form-control chosen-select" multiple >
                                <?php echo option_city_chosen('151,152,153'); ?>
                            </select>
                            <?php js_chosen() ?>
                            <!-- $i = 0; $arr = '';
                            foreach ($_POST['product_services'] as $key){ $arr .= ($i==0?'':',').base64_decode($key); $i += 1; }
                            $product_services = "'".$arr."'"; -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">City chosen city <span class="req">*</span></label>
                        <div class="col-sm-5">
                            <select name="city" data-placeholder="--- Select ---" class="form-control chosen-city chosen-select" required>
                                <option value=""></option>
                                <?php echo option_city_chosen('152'); ?>
                            </select>
                            <?php js_chosen() ?>
                            <?php js_chosen_city() ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Propinsi</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="province" value="" />
                            <input type="text" name="province_name" value="" class="form-control" disabled />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Country</label>
                        <div class="col-sm-5">
                            <input type="hidden" name="country" value="" />
                            <input type="text" name="country_name" value="" class="form-control" disabled />
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="id" value="">
                            <input type="submit" class="btn btn-danger" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="form-horizontal">
                <form action="" method="post" enctype="multipart/form-data">
                    <legend>Search Auto Completed</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Name City <span class="req">*</span></label>
                        <div class="col-sm-5 has-feedback">
                            <input type="search" name="name" value="" class="form-control check-autocomplete" />
                            <div class="form-autocomplete-feedback"></div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="id" value="">
                            <input type="submit" class="btn btn-danger" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="form-horizontal">
                <legend>Search UI Auto Completed Produk Ajax</legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Name Produk <span class="req">*</span></label>
                    <div class="col-sm-9">
                        <input type="search" id="search_product_ajax" name="search_product" value="" class="form-control" placeholder="Cari Produk..." />
                        <div id="search_product_ajax_feedback"></div>
                        <script type="text/javascript">
                            // var params   = {
                            //     'product_status'        : '1',
                            //     'product_approved'      : '1',
                            //     'product_status_id'     : '!= 3',
                            //     'order_by'              : 'product_date_push',
                            //     'order_dir'             : 'desc',
                            //     'thisAction'            : 'getdata'
                            // };
                            // $.ajax({
                            //     type: 'POST',
                            //     url: MOD_URL+'ajax-function/ajax_autocomplete_product',
                            //     data: params,
                            //     async: false,
                            //     cache: false,
                            //     dataType: 'json',
                            //     success: function(data) {
                            //         console.log(data.product.result);
                            //         var ProductArray2 = [];
                            //         $.each(data.product.result, function (key,value) {
                            //             var prod = {};
                            //             prod['id'] = value.product_id;
                            //             prod['label'] = value.product_name;
                            //             prod['category'] = value.product_category_title;
                            //             prod['price_sale'] = value.product_price_sale;
                            //             prod['price_discount'] = value.product_price_discount;
                            //             prod['status_id'] = value.product_status_id;
                            //             prod['status_name'] = value.product_status_name;
                            //             prod['image'] = MOD_URL+"assets/collections/product/thumb/"+value.image_filename;
                            //             prod['href'] = OWN_LINKS+'/view/'+value.product_id+'-'+value.url_product;

                            //             ProductArray2.push(prod);

                            //             var cg = new customGeneral();
                            //             cg.autoCompleteProductOrders(ProductArray2, "#search_product_array");
                            //         });
                            //     }
                            // });
                            
                            var ProductAjax = [];
                            var cg = new customGeneral();
                                cg.autoCompleteProductAjax(ProductAjax, "#search_product_ajax");
                        </script>

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $(document).on('click', '.btn-popup-product', function(e){
                                    e.preventDefault();
                                    var $this    = $(this);
                                    var $thisVal = $this.attr('data-id');
                                    $('html, body').css('overflow','hidden');
                                    if($thisVal != ''){
                                        // alert('Click');
                                        // $.ajax({
                                        //     type: 'POST',
                                        //     url: MOD_URL+'ajax-function/get_product_cart_popup',
                                        //     data: {'thisVal':$thisVal,'thisAction':'get_product'},
                                        //     async: false,
                                        //     cache: false,
                                        //     dataType: 'json',
                                        //     success: function(data){
                                        //         $('.popup-product').addClass('active');
                                        //         $('.popup-product .popup-content').html(data.content);
                                        //         setTimeout(function(){
                                        //             $('.popup-product .popup-content .cart-add-btn').focus();
                                        //         },200);
                                        //     },
                                        //     error: function(jqXHR){
                                        //         var response = jqXHR.responseText;
                                        //         alert('error ajax');
                                        //     }
                                        // });
                                    }
                                });

                                /** ENABLED VARIASI **/
                                // $(document).on('change', '.product-variasi-item .form-cart-checkbox', function(e){
                                //     e.preventDefault();
                                //     var variasi = $(this).parents('.product-variasi-item');
                                //     if($(this).is(':checked')){
                                //         variasi.addClass('checked');
                                //         variasi.find('.select .form-control').attr('required','required');
                                //         variasi.find('.select .form-control').removeAttr('disabled');
                                //         variasi.find('.select .form-control').val('1');
                                //         variasi.find('.select .form-control').focus();

                                //     } else {
                                //         variasi.removeClass('checked');
                                //         variasi.find('.select .form-control').removeAttr('required');
                                //         variasi.find('.select .form-control').attr('disabled','disabled');
                                //         variasi.find('.select .form-control').val('');
                                //     }
                                // });
                            });
                        </script>

                    </div>
                </div>
            </div>

            <div class="form-horizontal">
                <legend>Search UI Auto Completed Produk Array</legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Name Produk <span class="req">*</span></label>
                    <div class="col-sm-9">
                        <input type="search" id="search_product_array" name="search_product" value="" class="form-control" placeholder="Cari Produk..." />
                        <div id="search_product_array_feedback"></div>
                        <script type="text/javascript">
                            <?php
                            $par_filter = array();
                            $par_filter['product_status']  = '1';
                            $par_filter['product_status_id'] = '!= 3';

                            $data = get_product_autocomplete($par_filter);
                            $new_data = array();
                            foreach ($data as $key => $value) {
                                $new_data[] = array('id'        => $value->product_id,
                                                    'label'     => $value->product_name,
                                                    'category'  => $value->product_category_title,
                                                    'price_sale' => $value->product_price_sale,
                                                    'price_discount' => $value->product_price_discount,
                                                    'status_id' => $value->product_status_id,
                                                    'status_name' => get_name_product_status($value->product_status_id),
                                                    'image'     => get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($value->product_id)),
                                                    'href'      => $own_links.'/view/'.$value->product_id.'-'.changeEnUrl($value->product_name)
                                              );
                            }
                            ?>
                            var ProductArray = <?php echo json_encode($new_data) ?>;
                            var cg = new customGeneral();
                                cg.autoCompleteProductOrders(ProductArray, "#search_product_array");
                        </script>

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $(document).on('click', '.btn-popup-product', function(e){
                                    e.preventDefault();
                                    var $this    = $(this);
                                    var $thisVal = $this.attr('data-id');
                                    $('html, body').css('overflow','hidden');
                                    if($thisVal != ''){
                                        // alert('Click');
                                        // $.ajax({
                                        //     type: 'POST',
                                        //     url: OWN_LINKS+'/cart_get_product',
                                        //     data: {'thisVal':$thisVal,'thisAction':'get_product'},
                                        //     async: false,
                                        //     cache: false,
                                        //     dataType: 'json',
                                        //     success: function(data){
                                        //         $('.popup-product').addClass('active');
                                        //         $('.popup-product .popup-content').html(data.content);
                                        //         setTimeout(function(){
                                        //             $('.popup-product .popup-content .cart-add-btn').focus();
                                        //         },200);
                                        //     },
                                        //     error: function(jqXHR){
                                        //         var response = jqXHR.responseText;
                                        //         alert('error ajax');
                                        //     }
                                        // });
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>



        </div>
    </div>

    <div class="row">
        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <a href="<?php echo site_url('admin/me');?>" class="btn btn-default pull-right"><div>Back</div></a>
            </div>
        </div>
    </div>

    <?php js_select2() ?>

</div>