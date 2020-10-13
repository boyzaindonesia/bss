<div class="the-box no-border">

    <?php
    $banner_category = get_banner_category();
    foreach ($banner_category as $key => $val) {
    ?>
    <div class="panel panel-dark panel-block-color">
        <div class="panel-heading">
            <h3 class="panel-title text-uppercase"><?php echo strtoupper($val->banner_category_name); ?></h3>
        </div>
        <div class="panel-body">
            <div class="btn-toolbar toolbar-btn-action mb-10">
                <a href="<?php echo $own_links.'/add?banner_category='.$val->banner_category_id.'-'.changeEnUrl($val->banner_category_name) ?>" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</a>
            </div>

            <div class="table-responsive">
                <table class="table table-th-block table-dark datatable-<?php echo $val->banner_category_id ?>">
                    <colgroup>
                        <col width="1">
                        <col width="1">
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
                            <th>Judul</th>
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

                    $banner = get_banner_by_category($val->banner_category_id);
                    if(count($banner) > 0){
                        $i = 1;
                        foreach ($banner as $key => $r) { ?>
                        <tr class="dragsortitem" data-itemid="<?php echo $r->banner_id;?>">
                            <td class="nobr text-center"><div class="btn btn-drag bg-dark btn-xs" title="Drag to move position"><i class="fa fa-sort"></i></div></td>
                            <td class="nobr text-center"><?php echo $i ?>.</td>
                            <td><?php echo $r->banner_title;?></td>
                            <td class="magnific-popup-wrap">
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/banner/large/".$r->banner_images);?>"><img src="<?php echo get_image(base_url()."assets/collections/banner/large/".$r->banner_images);?>" class="avatar mfp-fade"></a>
                            </td>
                            <td class="magnific-popup-wrap">
                                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/banner/large/".$r->banner_images_mobile);?>"><img src="<?php echo get_image(base_url()."assets/collections/banner/large/".$r->banner_images_mobile);?>" class="avatar mfp-fade"></a>
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
                            <td class="nobr text-right"><span class="label label-default"><?php echo convDateTable($r->banner_date) ?></span></td>
                            <td class="nobr text-center">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="switch_sidebar_<?php echo $r->banner_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->banner_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->banner_id;?>')" value="1" <?php if($r->banner_status == "1"){ echo 'checked'; } ?> >
                                    <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->banner_id ?>">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </td>
                            <td class="nobr">
                                <?php link_action($links_table_item,$r->banner_id,changeEnUrl($r->banner_title));?>
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
        </div>
    </div>
    <?php } ?>

    <!-- datatable -->
    <?php get_data_table();?>
    <script text="javascript">
        $(document).ready(function() {
            <?php foreach ($banner_category as $key => $val) { ?>
            $('.datatable-<?php echo $val->banner_category_id ?>').dataTable({
                "lengthMenu": [ [10,25, 50, 100, -1], [10,25, 50, 100, "All"] ],
                "columnDefs": [ {"targets": 'no-sort', "orderable": false} ]
            });
            <?php } ?>
        });
    </script>

    <!-- dragsort -->
    <?php js_dragsort() ?>

</div>