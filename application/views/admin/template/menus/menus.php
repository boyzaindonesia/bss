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
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort"><i class="fa fa-sort-amount-asc"></i></th>
                    <th class="nobr text-center no-sort">No</th>
                    <th class="nobr">Nama Menu</th>
                    <th class="nobr">Sub Menu</th>
                    <th class="nobr text-center no-sort">Link</th>
                    <th class="nobr text-center no-sort">Status</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody class="dragsort">
            <?php
            if(count($data) > 0){
                $i = 1;
                foreach($data as $r){ ?>
                <tr class="dragsortitem" data-itemid="<?php echo $r->menus_id;?>">
                    <td class="nobr text-center"><div class="btn btn-drag bg-dark btn-xs" title="Drag to move position"><i class="fa fa-sort"></i></div></td>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td><?php echo $r->menus_title;?></td>
                    <td><?php echo get_menus_name($r->menus_parent_id);?></td>
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
                    <td class="nobr text-center">
                        <div class="onoffswitch">
                            <input type="checkbox" name="switch_sidebar_<?php echo $r->menus_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->menus_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->menus_id;?>')" value="1" <?php if($r->menus_status == "1"){ echo 'checked'; } ?> >
                            <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->menus_id ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->menus_id,changeEnUrl($r->menus_title));?>
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