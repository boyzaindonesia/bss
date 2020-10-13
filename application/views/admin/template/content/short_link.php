<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable">
            <colgroup>
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center no-sort">No</th>
                    <th>Nama</th>
                    <th class="nobr no-sort">Short Url</th>
                    <th class="nobr no-sort">To Link</th>
                    <th class="nobr text-center">View</th>
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
                    <td><?php echo $r->short_link_name;?></td>
                    <td class="nobr">
                        <a href="<?php echo $this->short_url.$r->short_link_code;?>" title="<?php echo $this->short_url.$r->short_link_code;?>" target="_blank"><small><?php echo $this->short_url.$r->short_link_code;?></small></a>

                        <button type="button" onclick="copyToClipboard('#copy-short-url')" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Copy"><i class="fa fa-clipboard"></i></button>
                        <div id="copy-short-url" class="hide"><?php echo $this->short_url.$r->short_link_code;?></div>
                    </td>
                    <td class="nobr">
                        <a href="<?php echo $r->short_link_url;?>" title="<?php echo $r->short_link_url;?>" target="_blank"><small><?php echo getFirstParaNumb($r->short_link_url, 30);?></small></a>
                    </td>
                    <td class="nobr text-center">
                        <?php echo $r->short_link_view;?>
                    </td>
                    <td class="nobr text-right"><span class="label label-default"><?php echo convDateTable($r->short_link_date) ?></span></td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->short_link_id,changeEnUrl($r->short_link_name));?>
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

    <script type="text/javascript">
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }
    </script>

</div>