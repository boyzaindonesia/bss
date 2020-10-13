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
                <col width="1">
                <col>
                <col>
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr no-sort">No</th>
                    <th class="nobr no-sort">Images</th>
                    <th class="nobr">Toko</th>
                    <th class="nobr">Username</th>
                    <th>Nama</th>
                    <th>Email</th>
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
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/photo/small/".$r->user_photo);?>"><img src="<?php echo get_image(base_url()."assets/collections/photo/thumb/".$r->user_photo);?>" class="avatar img-circle mfp-fade"></a>
                    </td>
                    <td class="nobr">
                        <?php 
                        $detail_store = get_detail_store($r->store_id);
                        echo $detail_store->store_name;
                        ?>
                    </td>
                    <td class="nobr"><?php echo $r->user_name;?></td>
                    <td><?php echo $r->user_fullname;?></td>
                    <td><?php echo $r->user_email;?></td>
                    <td class="nobr text-center">
                        <div class="onoffswitch">
                            <input type="checkbox" name="switch_sidebar_<?php echo $r->user_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->user_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->user_id;?>')" value="1" <?php if($r->user_status == "1"){ echo 'checked'; } ?> >
                            <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->user_id ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->user_id,changeEnUrl($r->user_fullname));?>
                    </td>
                </tr>
                <?php
                    $i += 1;
                } 
            } else {
                // echo '<tr><td colspan="7">Tidak ditemukan di database.</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- datatable -->
    <?php get_data_table();?>

</div>