<?php js_mask() ?>

<div class="the-box no-border">

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
                            <form action="<?php echo !empty($url)?$url:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Short by</label>
                                <div class="btn-group">
                                    <?php $order_by  = $this->jCfg['search']['order_by']; ?>
                                    <?php $order_dir = $this->jCfg['search']['order_dir']; ?>
                                    <select name="order_by" class="form-control" onchange="this.form.submit()">
                                        <option value="saldo_date-desc" <?php echo ($order_by=='saldo_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="saldo_date-asc" <?php echo ($order_by=='saldo_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar toolbar-filter">
                            <form action="<?php echo !empty($url)?$url:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Per Page</label>
                                <div class="btn-group">
                                    <?php $filter = $this->jCfg['search']['filter']; ?>
                                    <select name="filter" class="form-control" style="min-width: 50px;" onchange="this.form.submit()">
                                        <option value="25" <?php echo ($filter=='25'?'selected':'') ?> >25</option>
                                        <option value="50" <?php echo ($filter=='50'?'selected':'') ?> >50</option>
                                        <option value="100" <?php echo ($filter=='100'?'selected':'') ?> >100</option>
                                    </select> 
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-toolbar toolbar-search">
            <div class="btn-group">
                <div class="btn btn-success btn-update-saldo" data-action="plus"><i class="fa fa-plus"></i> Tambah</div>
            </div>
            <div class="btn-group">
                <div class="btn btn-danger btn-update-saldo" data-action="minus"><i class="fa fa-minus"></i> Tarik Dana</div>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {

                    $(document).on('click', '.btn-update-saldo', function(e){
                        e.preventDefault();
                        var thisAction = $(this).attr('data-action');
                        var title = 'Penambahan Saldo ';
                        if(thisAction == 'minus'){ title = 'Penarikan Dana '; }
                        swal({
                            title: title+' <?php echo $name ?>',
                            html:
                            '<div class="swal2-input-group input-group"><span class="input-group-addon">Rp</span><input type="text" id="swal-price" class="swal2-input moneyRp_masking" placeholder="Jumlah Dana" required="required" maxlength="23" autofocus></div>' +
                            '<textarea id="swal-noted" class="swal2-textarea" placeholder="Keterangan"></textarea>',
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            showLoaderOnConfirm: true,
                            onOpen: function () {
                                setTimeout(function(){
                                    $(".moneyRp_masking").mask("#.##0", {reverse: true, maxlength: false});
                                },200);
                            },
                            preConfirm: function () {
                                return new Promise(function (resolve,reject) {
                                    var price = $('#swal-price').val();
                                    var noted = $('#swal-noted').val();
                                    if(price == ''){
                                        $('#swal-price').addClass('swal2-inputerror');
                                        reject('Jumlah dana harus diisi!');
                                    } else {
                                        $.ajax({
                                            type: 'POST',
                                            url: '<?php echo $own_links ?>/save',
                                            data: 'thisId=<?php echo $id ?>&price='+price+'&noted='+noted+'&thisAction='+thisAction,
                                            async: false,
                                            cache: false,
                                            dataType: 'json',
                                            beforeSend: function(){

                                            },
                                            success: function(data){
                                                if(data.err == false){
                                                    swal({
                                                        title: "Success!",
                                                        text: title + "<strong>" + convertRp(price) + "</strong> berhasil!",
                                                        type: "success"
                                                    }).then(function () {
                                                        setTimeout(function(){
                                                            window.location.reload(true);
                                                        },300);
                                                    });
                                                } else {
                                                    swal({
                                                        title: "Error!",
                                                        text: data.msg,
                                                        type: "error"
                                                    });
                                                }
                                            },
                                            error: function(jqXHR){
                                                var response = jqXHR.responseText;
                                                alert(response);
                                            }
                                        });
                                    }
                                });
                            }
                        }).then(function (result) {
                            swal(JSON.stringify(result));
                        }).catch(swal.noop);
                    });
                });
            </script>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-th-block table-dark">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">Waktu</th>
                    <th class="nobr text-center">Mutasi</th>
                    <th class="nobr text-center">Saldo</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if(count($data) > 0){
                $i = 1;
                $pageNumber = $this->uri->segment(5);
                if(!empty($pageNumber)){
                    $i = $pageNumber + 1;
                }
                foreach($data as $r){ ?>
                <tr>
                    <td class="nobr"><?php echo convDateTimeTable($r->saldo_date) ?></td>
                    <td class="nobr"><span class="<?php echo ($r->saldo_type == 1?'text-success':'text-danger') ?>"><?php echo ($r->saldo_type == 1?'+':'-') ?><?php echo convertRp($r->saldo_price);?></span></td>
                    <td class="nobr"><strong><?php echo convertRp($r->saldo_total);?></strong></td>
                    <td><?php echo $r->saldo_noted;?></td>
                </tr>
                <?php
                    $i += 1;
                } 
            } else {
                echo '<tr><td colspan="4">Tidak ditemukan di database.</td></tr>';
            }
            ?>
            </tbody>
        </table>
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group pull-right">
                <?php echo isset($paging)?$paging:'';?>
            </div>
        </div>
    </div>

</div>