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
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort">No</th>
                    <th class="nobr text-center">Nama</th>
                    <th>Alamat</th>
                    <th class="nobr text-center">No Hp</th>
                    <th class="nobr text-center">Date</th>
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
                    <td class="nobr"><?php echo ucwords($r->supplier_name) ?></td>
                    <td><?php echo $r->supplier_address ?>, <?php echo getCitySet($r->supplier_city_id) ?>, <?php echo getProvSet($r->supplier_province_id) ?> - <?php echo $r->supplier_postal_code ?></td>
                    <td class="nobr"><?php echo $r->supplier_phone ?></td>
                    <td class="nobr text-right"><span class="label label-default"><?php echo convDateTimeTable($r->date_created) ?></span></td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->supplier_id,changeEnUrl($r->supplier_name));?>
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