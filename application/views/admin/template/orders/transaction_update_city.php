
<div class="the-box no-border">

    <div class="btn-toolbar toolbar-btn-action">
        <div class="btn-group pull-right">
            <div class="btn btn-danger btn-save-multiple" data-url="<?php echo $own_links.'/save_multiple_city' ?>"><i class="fa fa-pencil"></i> Simpan Update ( 0 )</div>
        </div>
    </div>
    <?php echo 'Total data: '.count($data) ?>
    <form id="form1" action="" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable-all">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center no-sort"><input type="checkbox" name="" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"></th>
                    <th class="text-left">Orders Id</th>
                    <th class="text-left">Alamat</th>
                    <th class="text-left">Kota Lama</th>
                    <th class="nobr text-left">Kota Baru</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            if(count($data) > 0){
                foreach($data as $r){
                ?>
                <tr class="" data-id="<?php echo $r->orders_id; ?>" title="">
                    <td class="nobr text-center"><?php echo $i ?></td>
                    <td class="nobr text-center">
                        <input type="checkbox" name="checked_files[]" value="<?php echo $r->orders_id;?>">
                    </td>
                    <td class="">
                        <?php echo $r->orders_id; ?>
                    </td>
                    <td class="">
                        <?php echo $r->orders_shipping_address; ?>
                    </td>
                    <td class="">
                        <?php echo $r->orders_shipping_city; ?>
                    </td>
                    <td class="nobr text-right">
                            <input type="text" name="new_city[<?php echo $r->orders_id; ?>]" value="" class="form-control new-city input-sm text-uppercase" style="width: 150px;">
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
        $('.btn-save-multiple').html('<i class="fa fa-pencil"></i> Smpan Update ( '+checked_files.length+' )');
    }

    $(document).ready(function() {

        $(document).on('click','input[type="text"]',function(){ this.select(); });

        $(document).on('change', 'input[name="checked_files[]"]', function(e){
            e.preventDefault();
            if($(this).is(':checked')){
                $(this).parents('tr').addClass('selected');
            } else {
                $(this).parents('tr').removeClass('selected');
            }
            calc_check_files();
        });

        $(document).on('keyup change', '.form-control.new-city', function(){
            var $this    = $(this);
            var $val     = $this.val();
            if($val != "" || $val != 0){
                var checkbox = $this.parents('tr').find('input[name="checked_files[]"]');
                checkbox.prop('checked', true);
                checkbox.parents('tr').addClass('selected');
                calc_check_files();
            }
        });

        $(document).on('click', '.btn-save-multiple', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1   = $('form#form1');
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau disimpan.",
                    type: "error"
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: dataUrl,
                    data: form1.serialize(),
                    async: false,
                    cache: false,
                    dataType: 'json',
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
            }
        });

    });
</script>
