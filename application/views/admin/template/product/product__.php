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
                                        <option value="product_date_push-desc" <?php echo ($order_by=='product_date_push'&&$order_dir=='desc'?'selected':'') ?> >Date: New to Old</option>
                                        <option value="product_date_push-asc" <?php echo ($order_by=='product_date_push'&&$order_dir=='asc'?'selected':'') ?> >Date: Old to New</option>
                                        <option value="product_name-asc" <?php echo ($order_by=='product_name'&&$order_dir=='asc'?'selected':'') ?> >Name: A to Z</option>
                                        <option value="product_name-desc" <?php echo ($order_by=='product_name'&&$order_dir=='desc'?'selected':'') ?> >Name: Z to A</option>
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
                                        <option value="40" <?php echo ($filter=='40'?'selected':'') ?> >40</option>
                                        <option value="80" <?php echo ($filter=='80'?'selected':'') ?> >80</option>
                                        <option value="120" <?php echo ($filter=='120'?'selected':'') ?> >120</option>
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

</div>

<div class="relative no-border">
    <?php js_picker() ?>

    <div class="row isotope mb-20 clearfix">
        <?php
        if(count($data) > 0){
            $i = 1;
            $pageNumber = $this->uri->segment(4);
            if(!empty($pageNumber)){
                $i = $pageNumber + 1;
            }
            foreach($data as $r){ ?>
            <div class="col-sm-3 isotope-item">
                <div class="the-box no-border full store-item featured-post-wide mb-20">
                    <a href="<?php echo $own_links.'/view/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>">
                        <img src="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id));?>" class="item-image" alt="Image">
                    </a>
                    <!-- <i class="fa fa-arrows icon-danger icon-square icon-xs icon-drag" style="cursor:move;"></i> -->

                    <div class="icon-awards">
                    <?php
                    $chk_awards_product = chk_awards_product($r->product_awards, 1);
                    if($chk_awards_product){ ?>
                    <i class="fa fa-star icon-info icon-square icon-xs" data-toggle="tooltip" data-original-title="<?php echo get_product_awards(1)->product_awards_name ?>" ></i>
                    <?php } ?>
                    </div>

                    <?php if($r->product_price_grosir != ''){ ?>
                    <div class="right-action"><button class="btn btn-warning btn-square btn-xs">GROSIR</button></div>
                    <?php } ?>

                    <div class="featured-text relative">
                        <h6 class="clearfix mt-0 mb-5">
                            <div class="pull-left"><?php echo get_product_category_name($r->product_category_id) ?></div>
                            <div class="pull-right">Stok: <?php echo $r->product_stock;?></div>
                        </h6>
                        <h4 class="mb-10"><a href="<?php echo $own_links.'/view/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>"><?php echo $r->product_name;?></a></h4>
                        <h5 class="mb-10"><span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?></h5>
                        <div class="featured-action text-center mt mb">
                            <div class="btn-group">
                                <?php link_action($links_table_item,$r->product_id,changeEnUrl($r->product_name)); ?>
                                <a href="<?php echo $own_links.'/duplicate/'.$r->product_id.'-'.changeEnUrl($r->product_name);?>" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Duplicate"><i class="fa fa-copy"></i></a>
                            </div>
                        </div>
                        <p class="comment-action mt-10 text-center">
                            <a data-toggle="tooltip" title="" class="btn btn-xs btn-default btn-square" data-original-title="<?php echo ($r->product_sold==''?'0':$r->product_sold); ?> sold"><i class="fa fa-shopping-cart"></i> <?php echo ($r->product_sold==''?'0':$r->product_sold); ?></a>
                            <a data-toggle="tooltip" title="" class="btn btn-xs btn-default btn-square" data-original-title="<?php echo ($r->product_view==''?'0':$r->product_view); ?> views"><i class="fa fa-eye"></i> <?php echo ($r->product_view==''?'0':$r->product_view); ?></a>
                            <a data-toggle="tooltip" title="" class="btn btn-xs btn-default btn-square" data-original-title="<?php echo ($r->product_like==''?'0':$r->product_like); ?> likes"><i class="fa fa-thumbs-up"></i> <?php echo ($r->product_like==''?'0':$r->product_like); ?></a>
                            <a data-toggle="tooltip" title="" class="btn btn-xs btn-default btn-square" data-original-title="<?php echo ($r->product_wishlist==''?'0':$r->product_wishlist); ?> favorite"><i class="fa fa-heart"></i> <?php echo ($r->product_wishlist==''?'0':$r->product_wishlist); ?></a>
                        </p>
                        <h6 class="mb-0"><span class="additional-post"><i class="fa fa-clock-o"></i>Last Update: <?php echo timeAgo($r->product_date_update) ?></span></h6>
                        <h6 class="mb-0"><span class="additional-post"><i class="fa fa-clock-o"></i>Last Push: <?php echo timeAgo($r->product_date_push) ?></span></h6>
                    </div>

                </div>
            </div>
            <?php
                $i += 1;
            }
        } else {
            echo '<p>Tidak ditemukan di database.</p>';
        }
        ?>
    </div>

    <?php js_isotope() ?>

    <?php if(isset($paging) && $paging!=''){ ?>
    <div class="btn-toolbar mt-20 mb-20" role="toolbar">
        <div class="btn-group pull-right">
            <?php echo $paging ?>
        </div>
    </div>
    <?php } ?>

</div>