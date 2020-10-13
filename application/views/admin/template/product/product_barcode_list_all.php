
<div class="the-box no-border">

    <div class="btn-toolbar toolbar-btn-action">

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
                                        <option value="mt_print_barcode.print_barcode_date-desc" <?php echo ($order_by=='mt_print_barcode.print_barcode_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="mt_print_barcode.print_barcode_date-asc" <?php echo ($order_by=='mt_print_barcode.print_barcode_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
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
                <col>
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Tanggal</th>
                    <th class="nobr">Nama Produk</th>
                    <th class="no-sort">Detail</th>
                    <th class="nobr text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // debugCode($data);
            if(count($data) > 0){
                $i = 1;
                foreach($data as $r){
                    $tr_class = '';

                ?>
                <tr class="<?php echo $tr_class ?>" data-id="<?php echo $r->print_barcode_id;?>" title="">
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="nobr text-center"><span class="label label-default"><?php echo getYearMonthDate($r->print_barcode_date) ?></span><br/><small><?php echo convTime($r->print_barcode_date) ?></small>

                        <?php if($r->print_barcode_status == 1){ ?>
                            <br/><span class="label label-success"><small>Sudah Dicetak</small></span>
                        <?php } ?>
                    </td>
                    <td class="nobr"><?php echo $r->product_name_simple;?><br><?php echo $r->product_code;?></td>
                    <td>
                        <div class="row no-margin">
                        <?php
                        if($r->product_detail != ''){
                            $ii = 0;
                            $product_detail = json_decode($r->product_detail);
                            foreach ($product_detail as $k => $v) {
                                if($ii == 5){ echo '</div><div class="row no-margin">'; $ii = 0; }
                            ?>
                                <div class="col-sm-25" style="margin-bottom: 3px; padding: 0 0 0 5px;">
                                    <div class="input-group danger-color">
                                        <span class="input-group-addon"><small><?php echo $v->name; ?></small></span>
                                        <input type="text" name="product_qty[<?php echo $r->print_barcode_id; ?>-<?php echo $v->id; ?>]" value="<?php echo $v->qty; ?>" class="form-control" style="font-size: 12px; line-height: 16px;" maxlength="3" disabled readonly>
                                    </div>
                                </div>
                            <?php
                            $ii += 1;
                            }
                        } else {
                        ?>
                        <div class="col-sm-25" style="margin-bottom: 3px; padding: 0 0 0 5px;">
                            <div class="input-group danger-color">
                                <span class="input-group-addon"><small>Jumlah</small></span>
                                <input type="text" name="product_qty[<?php echo $r->print_barcode_id; ?>]" value="<?php echo $r->product_qty; ?>" class="form-control" style="font-size: 12px; line-height: 16px;" maxlength="3" disabled readonly>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        </div>
                    </td>
                    <td class="nobr text-center">
                        <button type="button" class="btn btn-info btn-xs btn-print-again" data-id="<?php echo $r->print_barcode_id;?>" data-toggle="tooltip" data-original-title="Cetak Ulang"><i class="fa fa-print"></i></button>
                        <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?php echo $r->print_barcode_id;?>" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
                <?php
                    $i += 1;
                }
            } else {
                echo '<tr><td colspan="5">Tidak ditemukan di database.</td></tr>';
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

    $(document).ready(function() {

        $(document).on('click', '.btn-print-again', function(e){
            e.preventDefault();
            var thisId  = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: OWN_LINKS+'/print_again',
                data: {'thisId':thisId,'thisAction':'save'},
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

        });

        $(document).on('click', '.btn-delete', function(e){
            e.preventDefault();
            var thisId  = $(this).attr('data-id');

            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {

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
        });

    });
</script>
