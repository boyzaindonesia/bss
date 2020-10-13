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
                        <div class="btn-toolbar toolbar-sort">
                            <form action="<?php echo !empty($url_form)?$url_form:$own_links;?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                <label>Short by</label>
                                <div class="btn-group">
                                    <?php $order_by  = $this->jCfg['search']['order_by']; ?>
                                    <?php $order_dir = $this->jCfg['search']['order_dir']; ?>
                                    <select name="order_by" class="form-control" onchange="this.form.submit()">
                                        <option value="mt_gallery.gallery_date-desc" <?php echo ($order_by=='mt_gallery.gallery_date'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="mt_gallery.gallery_date-asc" <?php echo ($order_by=='mt_gallery.gallery_date'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="mt_gallery.gallery_name-asc" <?php echo ($order_by=='mt_gallery.gallery_name'&&$order_dir=='asc'?'selected':'') ?> >Judul: A to Z</option>
                                        <option value="mt_gallery.gallery_name-desc" <?php echo ($order_by=='mt_gallery.gallery_name'&&$order_dir=='desc'?'selected':'') ?> >Judul: Z to A</option>
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

    <div class="table-responsive">
        <table class="table table-th-block table-dark">
            <colgroup>
                <col width="1">
                <col width="1">
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center"><i class="fa fa-sort-amount-asc"></i></th>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center">Images</th>
                    <th>Judul</th>
                    <th class="nobr text-center">Tanggal</th>
                    <th class="nobr text-center">Status</th>
                    <th class="nobr text-center">Action</th>
                </tr>
            </thead>
            <tbody class="dragsort">
            <?php 
            if(count($data) > 0){
                $i = 1;
                $pageNumber = $this->uri->segment(4);
                if(!empty($pageNumber)){
                    $i = $pageNumber + 1;
                }
                foreach($data as $r){ ?>
                <tr class="dragsortitem" data-itemid="<?php echo $r->gallery_id;?>">
                    <td class="nobr text-center"><div class="btn btn-drag bg-dark btn-xs" title="Drag to move position"><i class="fa fa-sort"></i></div></td>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="<?php echo $r->gallery_video==''?'magnific-popup-wrap':'magnific-popup-youtube';?>">
                        <?php if($r->gallery_video==''){ ?>
                            <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/gallery/large/".$r->gallery_images);?>"><img src="<?php echo get_image(base_url()."assets/collections/gallery/thumb/".$r->gallery_images);?>" class="avatar mfp-fade">
                            </a>
                        <?php } else { ?>
                        <a class="zooming" href="https://www.youtube.com/watch?v=<?php echo $r->gallery_video;?>"><img src="<?php echo get_image(base_url()."assets/collections/gallery/thumb/".$r->gallery_images);?>" class="avatar mfp-fade">
                            <div class="play-video"></div>
                        </a>
                        <?php } ?>
                    </td>
                    <td><?php echo $r->gallery_name;?></td>
                    <td class="nobr text-right"><span class="label label-default"><?php echo convDateTimeTable($r->gallery_date) ?></span></td>
                    <td class="nobr text-center">
                        <div class="onoffswitch">
                            <input type="checkbox" name="switch_sidebar_<?php echo $r->gallery_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->gallery_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->gallery_id;?>')" value="1" <?php if($r->gallery_status == "1"){ echo 'checked'; } ?> >
                            <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->gallery_id ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </td>
                    <td class="nobr">
                        <?php link_action($links_table_item,$r->gallery_id,changeEnUrl($r->gallery_name));?>
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

    <!-- dragsort -->
    <?php js_dragsort() ?>

</div>