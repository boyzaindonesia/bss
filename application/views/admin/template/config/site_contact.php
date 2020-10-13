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
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr no-sort">No</th>
                    <th class="nobr">Nama</th>
                    <th>Deskripsi</th>
                    <th>Longitude dan latitude</th>
                    <th class="nobr text-center">Tanggal</th>
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
                    <td class="nobr"><?php echo $r->contact_name;?></td>
                    <td><?php echo getFirstParaSm($r->contact_desc);?></td>
                    <td><?php echo $r->contact_lang.' , '.$r->contact_lat;?></td>
                    <td class="nobr text-right"><span class="label label-default"><?php echo convDateTimeTable($r->contact_date) ?></span></td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->contact_id,changeEnUrl($r->contact_name));?>
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