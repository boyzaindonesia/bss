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
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort">No</th>
                    <th class="nobr text-center">Nama</th>
                    <th>Color</th>
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
                    <td class="nobr"><?php echo ucwords($r->color_name) ?></td>
                    <td style="background-color: #<?php echo $r->color_hex ?>;"></td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->color_id,changeEnUrl($r->color_name));?>
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