<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <div class="relative clearfix">
        <div class="panel panel-toolbar panel-dark">
            <div class="panel-heading">
                <div class="right-content">
                <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-toolbar"><i class="fa fa-chevron-down"></i></button>
                </div>
                <h3 class="panel-title">Option Search</h3>
            </div>
            <div id="panel-collapse-toolbar" class="collapse" style="height: 0px;">
                <div class="panel-body">
                    <div class="relative clearfix">
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Log Type</label>
                                <div class="btn-group">
                                    <?php $log_type = $this->jCfg['search']['log_type']; ?>
                                    <select name="log_type" class="form-control" onchange="this.form.submit()">
                                        <option value="" <?php echo ($log_type==""?'selected':'') ?> >--- Pilih ---</option>
                                        <option value="1" <?php echo ($log_type==1?'selected':'') ?> >System</option>
                                        <option value="2" <?php echo ($log_type==2?'selected':'') ?> >Product</option>
                                        <option value="3" <?php echo ($log_type==3?'selected':'') ?> >Apps</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Log Title</label>
                                <div class="btn-group">
                                    <?php $log_title_id = $this->jCfg['search']['log_title_id']; ?>
                                    <select name="log_title_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" <?php echo ($log_title_id==""?'selected':'') ?> >--- Pilih ---</option>
                                        <?php
                                        $log_title = log_title();
                                        foreach ($log_title as $key => $value) { ?>
                                        <option value="<?php echo $value['id'] ?>" <?php echo ($log_title_id==$value['id']?'selected':'') ?> ><?php echo $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>User</label>
                                <div class="btn-group">
                                    <?php $log_user_id = $this->jCfg['search']['log_user_id']; ?>
                                    <select name="log_user_id" class="form-control" onchange="this.form.submit()">
                                        <option value="0" selected>--- Pilih ---</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="btn-toolbar toolbar-sort">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Short by</label>
                                <div class="btn-group">
                                    <?php $order_by  = $this->jCfg['search']['order_by']; ?>
                                    <?php $order_dir = $this->jCfg['search']['order_dir']; ?>
                                    <select name="order_by" class="form-control" onchange="this.form.submit()">
                                        <option value="log_date-desc" <?php echo ($order_by=='log_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="log_date-asc" <?php echo ($order_by=='log_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="mt_product.product_name-asc" <?php echo ($order_by=='mt_product.product_name'&&$order_dir=='asc'?'selected':'') ?> >Produk: A to Z</option>
                                        <option value="mt_product.product_name-desc" <?php echo ($order_by=='mt_product.product_name'&&$order_dir=='desc'?'selected':'') ?> >Produk: Z to A</option>
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
                <div class="btn-group">
                    <input type="search" data-reset="true" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
                    <input type="hidden" name="searchAction" value="search">
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive mt-20">
        <table class="table table-th-block table-dark">
            <colgroup>
                <col width="1">
                <col width="1">
                <col>
                <col>
                <col width="1">
                <col width="1">
                <col>
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Tanggal</th>
                    <th>Nama</th>
                    <th>Judul</th>
                    <th class="nobr">User</th>
                    <th class="nobr">Ip Address</th>
                    <th>User Agent</th>
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
                    <td class="nobr text-center"><span class="label label-default"><?php echo getYearMonthDate($r->log_date) ?></span><br/><small><?php echo convTime($r->log_date) ?></small></td>
                    <td><?php echo $r->product_name ?></td>
                    <td><?php echo log_title($r->log_title_id)['name'] ?></td>
                    <td class="nobr"><?php echo ucwords($r->user_name) ?></td>
                    <td class="nobr"><?php echo $r->log_ip ?></td>
                    <td><?php echo $r->log_user_agent ?></td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->log_id,changeEnUrl(log_title($r->log_title_id)['name']));?>
                    </td>
                </tr>
                <?php
                    $i += 1;
                }
            } else {
                echo '<tr><td colspan="8">Tidak ditemukan di database.</td></tr>';
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
    </form>

</div>
