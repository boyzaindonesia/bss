<div class="the-box no-border">

    <?php
    $detail = array('0' => 'overview',
                    '1' => 'floor plan',
                    '2' => 'spesifikasi unit',
                    '3' => 'unit booking',
                    '4' => 'term & condition'
                );

    foreach ($detail as $detail_id => $detail_name) { 
    ?>
    <div class="panel panel-dark panel-block-color">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo strtoupper($detail_name) ?></h3>
        </div>
        <div class="panel-body">

            <a href="<?php echo $own_links.'/detail/'.$val->product_id.'-'.changeEnUrl($val->product_name).'/add/'.$detail_id;?>"><div class="btn btn-primary margin-bottom">Add More</div></a>

            <div class="table-responsive">
                <table class="table table-th-block table-dark">
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
                            <th class="nobr text-center"><i class="fa fa-sort-amount-asc"></i></th>
                            <th class="nobr text-center">No</th>
                            <th class="nobr text-center">Images</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th class="nobr text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="dragsort" data-url-dragsort="<?php echo $own_links?>/change_position_detail">
                    <?php 
                    $product_detail = get_data_product_detail($val->product_id,$detail_id);
                    if(count($product_detail) > 0){
                        $i = 1;
                        foreach($product_detail as $r){ ?>
                        <tr class="dragsortitem" data-itemid="<?php echo $r->product_detail_id;?>">
                            <td class="nobr text-center"><div class="btn btn-drag bg-dark btn-xs" title="Drag to move position"><i class="fa fa-sort"></i></div></td>
                            <td class="nobr text-center"><?php echo $i ?>.</td>
                            <td class="magnific-popup-wrap">
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".$r->product_detail_images);?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".$r->product_detail_images);?>" class="avatar mfp-fade"></a>
                            </td>
                            <td><?php echo $r->product_detail_name;?></td>
                            <td><?php echo getFirstPara($r->product_detail_desc);?></td>
                            <td class="nobr">
                                <a href="<?php echo $own_links.'/detail/'.$val->product_id.'-'.changeEnUrl($val->product_name).'/edit/'.$detail_id.'/'.$r->product_detail_id;?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                <a href="<?php echo $own_links.'/delete_detail/'.$r->product_detail_id.'-'.changeEnUrl($r->product_detail_name);?>" onclick="return confirm('Are you sure delete ?');" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>
                        <?php
                            $i += 1;
                        } 
                    } else {
                        echo '<tr><td colspan="6">Tidak ditemukan di database.</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <?php } ?>

    <!-- dragsort -->
    <?php js_dragsort() ?>

</div>