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
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr no-sort">No</th>
                    <th class="nobr">Nama</th>
                    <th class="nobr">Deskripsi</th>
                    <th class="nobr no-sort">Status</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if(count($data) > 0){
                $i = 1;
                foreach($data as $r){ ?>
                <tr>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="nobr"><?php echo $r->ag_group_name;?></td>
                    <td><?php echo getFirstParaSm($r->ag_group_desc);?></td>
                    <td class="nobr text-center">
                        <div class="onoffswitch">
                            <input type="checkbox" name="switch_sidebar_<?php echo $r->ag_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->ag_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->ag_id;?>')" value="1" <?php if($r->ag_group_status == "1"){ echo 'checked'; } ?> >
                            <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->ag_id ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->ag_id,changeEnUrl($r->ag_group_name));?>
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