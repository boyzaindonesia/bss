<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <span class="magnific-popup-ajax">
            <a href="#form-produk" class="btn btn-primary margin-bottom" data-effect="mfp-zoom-in" data-ajax-url="<?php echo $own_links.'/get_table_add_products/'.$val->product_tags_id?>">Tambah Produk</a>
        </span>
        <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
    </div>

    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable">
            <colgroup>
                <col width="1">
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center no-sort">Images</th>
                    <th>Nama</th>
                    <th class="nobr text-center">Harga</th>
                    <th class="nobr text-center">Kategori</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody class="dragsort">
            <?php
            $product_tagged = get_list_product_tagged($val->product_tags_id);
            if(count($product_tagged) > 0){
                $i = 1;
                foreach($product_tagged as $r){ ?>
                <tr>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id));?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>" class="avatar mfp-fade"></a>
                    </td>
                    <td><?php echo $r->product_name;?></td>
                    <td class="nobr">
                        <span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?>
                    </td>
                    <td class="nobr"><?php echo $r->product_category_title;?></td>
                    <td class="nobr text-center">
                        <a href="<?php echo $own_links.'/delete_detail/'.$val->product_tags_id.'-'.$r->product_id.'-'.changeEnUrl($r->product_name);?>" onclick="return confirm('Are you sure delete ?');" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i></a>
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

    <!-- datatable -->
    <?php get_data_table();?>

    <div id="form-produk" class="white-popup wide mfp-with-anim mfp-hide">
        <form id="form1" action="" method="post" enctype="multipart/form-data">
            <legend><strong>Pilih Produk</strong></legend>
            <div class="btn-toolbar toolbar-btn-action">
                <div class="btn-group pull-right">
                    <div class="btn btn-info btn-save-multi" data-url="<?php echo base_url().'print-address' ?>"><i class="fa fa-plus"></i> Tambahkan ( 0 )</div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-th-block table-dark">
                    <colgroup>
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col>
                        <col width="1">
                        <col width="1">
                        <col width="1">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="nobr text-center no-sort"><input type="checkbox" name="checked_all_files" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"></th>
                            <th class="nobr">ID</th>
                            <th class="nobr text-center no-sort">Images</th>
                            <th class="">Nama</th>
                            <th class="nobr text-center">Kategori</th>
                            <th class="nobr text-center no-sort">Status</th>
                            <th class="nobr text-center no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody class="popup-ajax-result">

                    </tbody>
                </table>
            </div>
        </form>
    </div>

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
        $('.btn-save-multi').html('<i class="fa fa-plus"></i> Tambahkan ( '+checked_files.length+' )');
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
        $(document).on('click', '.btn-save-multi', function(e){
            e.preventDefault();
            var form1 = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau ditambahkan.",
                    type: "error"
                });
            } else {
                var thisId  = "";
                var thisVal = "";
                checked_files.each(function() {
                    thisVal = $(this).val();
                    thisId += (thisId=="" ? thisVal : "-" + thisVal);
                });

                var thisIdTag = '<?php echo $val->product_tags_id ?>';
                $.ajax({
                    type: 'POST',
                    url: OWN_LINKS+'/save_multi_detail',
                    data: {'thisId':thisId,'thisIdTag':thisIdTag,'thisAction':'save'},
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
            }
        });

        $(document).on('click', '.btn-save', function(e){
            e.preventDefault();
            var $this = $(this);
            var thisIdTag = '<?php echo $val->product_tags_id ?>';
            var thisId  = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: OWN_LINKS+'/save_detail',
                data: {'thisId':thisId,'thisIdTag':thisIdTag,'thisAction':'save'},
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
                        // setTimeout(function(){
                        //     window.location.reload(true);
                        // },300);
                        if(data.err == false){
                            var $tr = $this.parents('tr');
                            $tr.addClass('selected');
                            $tr.find('td:eq(0)').html('<i class="fa fa-check-square" aria-hidden="true"></i>');
                            var $tmpTitle = $tr.find('td:eq(3)').html();
                            $tr.find('td:eq(3)').html($tmpTitle+'<br/><small class="text-danger">(Sudah Ditambahkan)</small>');
                            $tr.find('td:eq(6)').html('<div class="btn btn-default btn-xs btn-disabled" disabled>Select</div>');
                            calc_check_files();
                        }
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
        });
    });
</script>