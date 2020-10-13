<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <div class="relative clearfix">
        <div class="panel panel-toolbar show-date panel-dark">
            <div class="panel-heading">
                <div class="right-content">
                <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-toolbar"><i class="fa fa-chevron-down"></i></button>
                </div>
                <h3 class="panel-title">Option Search</h3>
            </div>
            <div id="panel-collapse-toolbar" class="collapse" style="height: 0px;">
                <div class="panel-body">
                    <div class="relative clearfix">
                        <div class="btn-toolbar toolbar-sort">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Short by</label>
                                <div class="btn-group">
                                    <?php $order_by  = $this->jCfg['search']['order_by']; ?>
                                    <?php $order_dir = $this->jCfg['search']['order_dir']; ?>
                                    <select name="order_by" class="form-control" onchange="this.form.submit()">
                                        <option value="mt_member.member_date-desc" <?php echo ($order_by=='mt_member.member_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="mt_member.member_date-asc" <?php echo ($order_by=='mt_member.member_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="mt_member.member_name-asc" <?php echo ($order_by=='mt_member.member_name'&&$order_dir=='asc'?'selected':'') ?> >Nama Member: A to Z</option>
                                        <option value="mt_member.member_name-desc" <?php echo ($order_by=='mt_member.member_name'&&$order_dir=='desc'?'selected':'') ?> >Nama Member: Z to A</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar toolbar-filter">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Per Page</label>
                                <div class="btn-group">
                                    <?php $filter = $this->jCfg['search']['filter']; ?>
                                    <select name="filter" class="form-control" style="min-width: 50px;" onchange="this.form.submit()">
                                        <option value="25" <?php echo ($filter=='25'?'selected':'') ?> >25</option>
                                        <option value="50" <?php echo ($filter=='50'?'selected':'') ?> >50</option>
                                        <option value="100" <?php echo ($filter=='100'?'selected':'') ?> >100</option>
                                        <option value="" <?php echo ($filter==''?'selected':'') ?> >ALL</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-toolbar toolbar-search">
            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                <label>Search:</label>
                <div class="btn-group group-date mr-5">
                    <input type="text" id="datepickerstartsearch" name="date_start" class="form-control" value="<?php echo ($this->jCfg['search']['date_start']!=''?convDatepickerEnc($this->jCfg['search']['date_start']):'') ?>" data-date-format="dd-mm-yyyy" placeholder="Tgl Mulai...">
                </div>
                <div class="btn-group group-date mr-5">
                    <input type="text" id="datepickerendsearch" name="date_end" class="form-control" value="<?php echo ($this->jCfg['search']['date_end']!=''?convDatepickerEnc($this->jCfg['search']['date_end']):'') ?>" data-date-format="dd-mm-yyyy" placeholder="Tgl Akhir...">
                </div>
                <div class="btn-group">
                    <input type="search" data-reset="true" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
                    <input type="hidden" name="searchAction" value="search">
                </div>
            </form>
        </div>
    </div>

    <?php js_picker() ?>

    <div class="table-responsive">
        <table class="table table-th-block table-dark">
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
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Images</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th class="nobr text-center">Tanggal</th>
                    <th class="nobr text-center">Status</th>
                    <th class="nobr text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(count($data) > 0){
                $i = 1;
                $pageNumber = $this->uri->segment(4);
                if(!empty($pageNumber)){
                    $i = $pageNumber + 1;
                }
                foreach($data as $r){ ?>
                <tr>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/photo/small/".$r->member_photo);?>"><img src="<?php echo get_image(base_url()."assets/collections/photo/thumb/".$r->member_photo);?>" class="avatar img-circle mfp-fade"></a>
                    </td>
                    <td><?php echo $r->member_name;?></td>
                    <td><?php echo $r->member_email;?></td>
                    <td class="nobr text-right"><span class="label label-default"><?php echo convDateTimeTable($r->member_date) ?></span></td>
                    <td class="nobr text-center">
                        <span class="label <?php echo isset($r->member_status)?get_status_member($r->member_status)['class']:'' ?>"><?php echo isset($r->member_status)?get_status_member($r->member_status)['status']:'' ?></span>
                    </td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->member_id,changeEnUrl($r->member_name));?>
                    </td>
                </tr>
                <?php
                    $i += 1;
                }
            } else {
                echo '<tr><td colspan="7">Tidak ditemukan di database.</td></tr>';
            }
            ?>
            </tbody>
        </table>
        <?php if(isset($paging) && $paging!=''){ ?>
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group pull-right">
                <?php echo $paging ?>
            </div>
        </div>
        <?php } ?>
    </div>

</div>