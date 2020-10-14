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
                                        <option value="product_date_update-desc" <?php echo ($order_by=='product_date_update'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="product_date_update-asc" <?php echo ($order_by=='product_date_update'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="product_name-asc" <?php echo ($order_by=='product_name'&&$order_dir=='asc'?'selected':'') ?> >Name: A to Z</option>
                                        <option value="product_name-desc" <?php echo ($order_by=='product_name'&&$order_dir=='desc'?'selected':'') ?> >Name: Z to A</option>
                                        <option value="product_stock-desc" <?php echo ($order_by=='product_stock'&&$order_dir=='desc'?'selected':'') ?> >Stok: Terbanyak</option>
                                        <option value="product_stock-asc" <?php echo ($order_by=='product_stock'&&$order_dir=='asc'?'selected':'') ?> >Stok: Sedikit</option>
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
                                        <option value="40" <?php echo ($filter=='40'?'selected':'') ?> >40</option>
                                        <option value="80" <?php echo ($filter=='80'?'selected':'') ?> >80</option>
                                        <option value="120" <?php echo ($filter=='120'?'selected':'') ?> >120</option>
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
    <h4 class="no-margin">Total Produk: <?php echo $total_data ?></h4>

    <div class="btn-toolbar mb-10">
        <div class="btn-group pull-right">
            <div class="btn btn-danger btn-delete-multiple" data-url="<?php echo $own_links.'/delete_permanent_multi?next='.current_url(); ?>"><i class="fa fa-times"></i> Hapus ( 0 )</div>
        </div>
    </div>

    <form id="form1" action="#" method="post" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-th-block table-dark">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort"><input type="checkbox" name="checkallfiles" onclick="check_all_files(this);" value="" data-toggle="tooltip" title="Check All" style="z-index: 999;"></th>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Images</th>
                    <th>Nama</th>
                    <th class="text-center">Kode</th>
                    <th class="nobr text-center">Harga</th>
                    <th class="nobr text-center">Kategori</th>
                    <th class="nobr text-center">Stok</th>
                    <th class="nobr text-center">Tanggal</th>
                    <th class="nobr text-center">Archive</th>
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
                    <td class="nobr text-center"><input type="checkbox" name="checked_files[]" value="<?php echo $r->product_id;?>"></td>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail_archive($r->product_id));?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail_archive($r->product_id));?>" class="avatar mfp-fade"></a>
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
                    </td>
                    <td><?php echo $r->product_name ?></td>
                    <td class="text-center"><?php echo $r->product_code ?></td>
                    <td class="nobr">
                        <span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?>
                    </td>
                    <td class="nobr"><?php echo $r->product_category_name ?></td>
                    <td class="nobr text-center"><?php echo $r->product_stock ?></td>
                    <td class="nobr text-center"><span class="label label-success"><?php echo convDateTimeTable($r->product_date_update) ?></span></td>
                    <td class="nobr text-center"><span class="label label-danger"><?php echo convDateTimeTable($r->product_date_archive) ?></span></td>
                    <td class="nobr">
                        <?php if(check_action($links_table_item,'view')){ ?>
                        <a href="<?php echo $own_links.'/view/'.$r->product_id.'-'.changeEnUrl($r->product_name).'?next='.current_url();?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Lihat"><i class="fa fa-share"></i></a>
                        <?php } ?>
                        <?php if(check_action($links_table_item,'delete')){ ?>
                        <a href="<?php echo $own_links.'/delete_permanent/'.$r->product_id.'-'.changeEnUrl($r->product_name).'?next='.current_url();?>" onclick="return confirm('Are you sure delete ?');" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
                    $i += 1;
                }
            } else {
                echo '<tr><td colspan="11">Tidak ditemukan di database.</td></tr>';
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

<div class="popup popup-product">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">

        </div>
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
        $('.btn-delete-multiple').html('<i class="fa fa-times"></i> Hapus ( '+checked_files.length+' )');
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

        $(document).on('click', '.btn-delete-multiple', function(e){
            e.preventDefault();
            var $this   = $(this);
            var dataUrl = $this.attr('data-url');
            var form1 = $('form#form1');
                form1.attr("action", dataUrl);
            var checked_files = form1.find('input[name="checked_files[]"]:checked');
            if(checked_files.length == 0){
                swal({
                    title: "Error!",
                    text: "Ceklis yang mau dihapus.",
                    type: "error"
                });
            } else {
                var swalText = "Yakin ingin menghapus?";
                swal({
                    title: 'Are you sure?',
                    text: swalText,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                    swal({
                        title: "Loading!",
                        text: "",
                        type: "loading",
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        customClass: 'swal2-small'
                    });
                    form1.submit();
                }).catch(swal.noop);

                // var thisId  = "";
                // var thisVal = "";
                // checked_files.each(function() {
                //     thisVal = $(this).val();
                //     thisId += (thisId=="" ? thisVal : "-" + thisVal);
                // });

                // var url   = $(this).attr('data-url');
                // window.open(url+'?id='+thisId, '_blank');
            }
        });

    });
</script>
