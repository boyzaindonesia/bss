<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable">
            <colgroup>
                <col width="1">
                <col width="1">
                <col>
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
                    <th class="nobr text-center no-sort"><i class="fa fa-sort-amount-asc"></i></th>
                    <th class="nobr text-center no-sort">No</th>
                    <th>Judul 1</th>
                    <th>Judul 2</th>
                    <th>Judul 3</th>
                    <th class="nobr no-sort">Img Desktop</th>
                    <th class="nobr no-sort">Img Mobile</th>
                    <th class="nobr no-sort text-center">Link</th>
                    <th class="nobr text-center">Tanggal</th>
                    <th class="nobr text-center no-sort">Status</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody class="dragsort">
            <?php
            if(count($data) > 0){
                $i = 1;
                foreach($data as $r){ ?>
                <tr class="dragsortitem" data-itemid="<?php echo $r->headline_id;?>">
                    <td class="nobr text-center"><div class="btn btn-drag bg-dark btn-xs" title="Drag to move position"><i class="fa fa-sort"></i></div></td>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td><?php echo $r->headline_title;?></td>
                    <td><?php echo $r->headline_title_2;?></td>
                    <td><?php echo $r->headline_title_3;?></td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/headline/large/".$r->headline_images);?>"><img src="<?php echo get_image(base_url()."assets/collections/headline/large/".$r->headline_images);?>" class="avatar mfp-fade"></a>
                    </td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/headline/large/".$r->headline_images_mobile);?>"><img src="<?php echo get_image(base_url()."assets/collections/headline/large/".$r->headline_images_mobile);?>" class="avatar mfp-fade"></a>
                    </td>
                    <td class="nobr text-center">
                        <?php
                        $get_read_link = get_read_link($r->link_type, $r->link_value);
                        if($get_read_link == ""){
                            echo '-';
                        } else {
                            echo '<a href="'.$get_read_link.'" title="'.$get_read_link.'" target="_blank">Link</a>';
                        }
                        ?>
                    </td>
                    <td class="nobr text-right"><span class="label label-default"><?php echo convDateTable($r->headline_date) ?></span></td>
                    <td class="nobr text-center">
                        <div class="onoffswitch">
                            <input type="checkbox" name="switch_sidebar_<?php echo $r->headline_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->headline_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->headline_id;?>')" value="1" <?php if($r->headline_status == "1"){ echo 'checked'; } ?> >
                            <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->headline_id ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </td>
                    <td class="nobr">
                        <a href="<?php echo base_url().'admin/headline/duplicate/'.$r->headline_id.'-'.changeEnUrl($r->headline_title_3);?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-original-title="Duplicate"><i class="fa fa-copy"></i></a>
                        <?php link_action($links_table_item,$r->headline_id,changeEnUrl($r->headline_title_3));?>
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