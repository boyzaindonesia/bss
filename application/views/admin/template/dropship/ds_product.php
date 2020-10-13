<!-- <ul class="nav nav-tabs item-color">
    <li class="active"><a href="<?php echo $own_links ?>">Master</a></li>
    <?php
    $get_product_group = get_product_group();
    foreach ($get_product_group as $k => $v) {
    ?>
    <li class=""><a href="<?php echo $own_links.'/detail/'.$v->product_group_id.'-'.changeEnUrl($v->product_group_name)?>"><?php echo $v->product_group_name ?></a></li>
    <?php } ?>
</ul> -->

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
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort">No</th>
                    <th class="nobr text-center no-sort">Tanggal</th>
                    <th class="nobr text-center no-sort">Images</th>
                    <th>Nama</th>
                    <th class="nobr text-center">Jumlah Produk</th>
                    <th class="nobr text-center no-sort">Status</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody class="dragsort">
            <?php
            if(count($data) > 0){
                $i = 1;
                foreach($data as $r){ ?>
                <tr data-itemid="<?php echo $r->product_group_id;?>">
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="nobr text-center"><span class="label label-default"><?php echo getYearMonthDate($r->product_group_date) ?></span><br/><small><?php echo convTime($r->product_group_date) ?></small></td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/large/".get_cover_product_group($r->product_group_id));?>"><img src="<?php echo get_image(base_url()."assets/collections/product/large/".get_cover_product_group($r->product_group_id));?>" class="avatar mfp-fade"></a>
                    </td>
                    <td><?php echo $r->product_group_name;?></td>
                    <td class="nobr text-center">
                        <a href="<?php echo $own_links.'/detail/'.$r->product_group_id.'-'.changeEnUrl($r->product_group_name)?>"><?php echo get_count_product_group($r->product_group_id) ?></a>
                    </td>
                    <td class="nobr text-center">
                        <div class="onoffswitch">
                            <input type="checkbox" name="switch_sidebar_<?php echo $r->product_group_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->product_group_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->product_group_id;?>')" value="1" <?php if($r->product_group_show == "1"){ echo 'checked'; } ?> >
                            <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->product_group_id ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->product_group_id,changeEnUrl($r->product_group_name));?>
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

</div>