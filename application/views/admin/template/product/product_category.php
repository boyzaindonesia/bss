<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort"><i class="fa fa-sort-amount-asc"></i></th>
                    <th class="nobr text-center no-sort">Images</th>
                    <th class="nobr text-center">ID</th>
                    <th>Judul</th>
                    <th>Sub Kategori</th>
                    <th class="nobr text-center no-sort">Status</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody class="dragsort">
            <?php
            if(count($data) > 0){
                $i = 1;
                foreach($data as $r){ ?>
                <tr class="dragsortitem" data-itemid="<?php echo $r->product_category_id;?>">
                    <td class="nobr text-center"><div class="btn btn-drag bg-dark btn-xs" title="Drag to move position"><i class="fa fa-sort"></i></div></td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".$r->product_category_image);?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".$r->product_category_image);?>" class="avatar mfp-fade"></a>
                    </td>
                    <td class="nobr text-center"><?php echo $r->product_category_id ?></td>
                    <td><?php echo $r->product_category_title;?></td>
                    <td><?php echo get_product_category_name($r->product_category_parent_id);?></td>
                    <td class="nobr text-center">
                        <div class="onoffswitch">
                            <input type="checkbox" name="switch_sidebar_<?php echo $r->product_category_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->product_category_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->product_category_id;?>')" value="1" <?php if($r->product_category_status == "1"){ echo 'checked'; } ?> >
                            <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->product_category_id ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->product_category_id,changeEnUrl($r->product_category_title));?>
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

    <!-- dragsort -->
    <?php js_dragsort() ?>

</div>