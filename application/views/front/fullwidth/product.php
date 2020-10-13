<?php include('header.php'); ?>

    <!-- Page Content Wraper -->
    <div class="page-content-wraper">
        <!-- Bread Crumb -->
        <section class="breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="breadcrumb-link">
                            <a href="<?php echo base_url()?>">Home</a>
                            <span><?php echo $this->page; ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!-- Bread Crumb -->

        <!-- Page Content -->
        <section class="content-page">
            <div class="container">
                <div class="row">

                    <!-- Product Content -->
                    <div class="col-md-9 push-md-3">
                        <!-- Title -->
                        <div class="list-page-title">
                            <h2 class=""><?php echo $this->title; ?> <small><?php echo $products['cRec'] ?> Produk</small></h2>
                        </div>
                        <!-- End Title -->

                        <!-- Product Filter -->
                        <div class="product-filter-content">
                            <div class="product-filter-content-inner">

                                <!--Product Filter Button-->
                                <div class="product-filter-dropdown-btn "><a href="javascript:void(0)" data-toggle-target="filter-slide-toggle" class="btn btn-sm btn-gray slide-toggle-btn"><i class="fa fa-bars left" aria-hidden="true"></i>Filter</a></div>

                                <!--Product Sort By-->
                                <form action="<?php echo remove_url_query($own_link,'from,page') ?>" class="product-sort-by" method="post" enctype="multipart/form-data">
                                    <label for="short-by">Short By</label>
                                    <select name="short_by" id="short-by" class="nice-select-box" onchange="this.form.submit()">
                                        <option value="1" <?php echo ($this->short_by=='1'?'selected':'') ?>>Terbaru</option>
                                        <option value="2" <?php echo ($this->short_by=='2'?'selected':'') ?>>Penjualan</option>
                                        <option value="3" <?php echo ($this->short_by=='3'?'selected':'') ?>>Termurah</option>
                                        <option value="4" <?php echo ($this->short_by=='4'?'selected':'') ?>>Termahal</option>
                                        <option value="5" <?php echo ($this->short_by=='5'?'selected':'') ?>>Nama: A ke Z</option>
                                        <option value="6" <?php echo ($this->short_by=='6'?'selected':'') ?>>Nama: Z ke A</option>
                                    </select>
                                    <?php if(isset($_GET['keyword'])){ ?>
                                        <input type="hidden" name="keyword" value="<?php echo $_GET['keyword'] ?>">
                                    <?php } ?>
                                </form>

                                <!--Product Show-->
                                <form action="<?php echo remove_url_query($own_link,'from,page') ?>" class="product-show" method="post" enctype="multipart/form-data">
                                    <label for="product-show">Show</label>
                                    <select name="per_page" id="product-show" class="nice-select-box" onchange="this.form.submit()">
                                        <option value="30" <?php echo ($this->per_page=='30'?'selected':'') ?>>30</option>
                                        <option value="45" <?php echo ($this->per_page=='45'?'selected':'') ?>>45</option>
                                        <option value="60" <?php echo ($this->per_page=='60'?'selected':'') ?>>60</option>
                                    </select>
                                </form>

                                <!--Product List/Grid Icon-->
                                <div class="product-view-switcher">
                                    <label>View</label>
                                    <div class="product-view-icon product-grid-switcher product-view-icon-active">
                                        <a class="" href="javascript:void(0);"><i class="fa fa-th" aria-hidden="true"></i></a>
                                    </div>
                                    <div class="product-view-icon product-list-switcher">
                                        <a class="" href="javascript:void(0);"><i class="fa fa-th-list" aria-hidden="true"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End Product Filter -->

                        <!-- Product filters Toggle-->
                        <div class="container product-filter-dropdown toggle-content" id="filter-slide-toggle">
                            <div class="row col mlr-0">
                                <!-- Filter Price -->
                                <div class="widget-sidebar widget-filter-price col-sm-12 col-md-6 col-lg-3">
                                    <h6 class="widget-title">Harga</h6>
                                    <ul class="widget-content">
                                        <?php $url_filter_price = remove_url_query($own_link,'from,page,min-range,max-range'); ?>
                                        <li>
                                            <a href="<?php echo $url_filter_price ?>">All</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $url_filter_price ?>&min-range=0&max-range=25000">
                                                <span class="amount"><span class="currencySymbol">Rp </span>0</span> - <span class="amount"><span class="currencySymbol">Rp </span>25.000</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $url_filter_price ?>&min-range=25000&max-range=50000">
                                                <span class="amount"><span class="currencySymbol">Rp </span>25.000</span> - <span class="amount"><span class="currencySymbol">Rp </span>50.000</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $url_filter_price ?>&min-range=50000&max-range=75000">
                                                <span class="amount"><span class="currencySymbol">Rp </span>50.000</span> - <span class="amount"><span class="currencySymbol">Rp </span>75.000</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $url_filter_price ?>&min-range=75000&max-range=100000">
                                                <span class="amount"><span class="currencySymbol">Rp </span>75.000</span> - <span class="amount"><span class="currencySymbol">Rp </span>100.000</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $url_filter_price ?>&min-range=100000">
                                                <span class="amount"><span class="currencySymbol">Rp </span>100.000</span> +
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Product filters Toggle-->

                        <?php
                        if(isset($_GET['msg']) || isset($_GET['type_msg']) ){
                            $class = ($_GET['type_msg']=='error'?'alert-danger':'alert-success');
                            $alert = ($_GET['type_msg']=='error'?'Error ! ':'Success ! ');
                            $msg   = $_GET['msg'];
                            echo notifyMessage($class,$alert,$msg);
                        }
                        ?>
                        <?php if($this->not_found){ ?>
                        <div class="alert alert-danger square fade in alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $this->msg_error ?>
                        </div>
                        <div class="section-title"><h2>Kamu Mungkin Juga Suka</h2></div>
                        <?php } ?>

                        <!-- Product Grid -->
                        <div class="row product-list-item">
                            <?php foreach ($products['data'] as $key => $val) { ?>
                            <div class="product-item-element col-sm-6 col-md-6 col-lg-4">
                                <?php include('inc/layout_product.php'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- End Product Grid -->

                        <?php if(isset($products['paging']) && $products['paging']!=''){ ?>
                        <div class="pagination-wraper">
                            <p><?php echo 'Showing '.$products['noPage'].' - '.$products['endPage'].' of '.$products['cRec'].' results'; ?></p>
                            <div class="pagination">
                                <?php echo $products['paging']; ?>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                    <!-- End Product Content -->

                    <!-- Sidebar -->
                    <div class="sidebar-container col-md-3 pull-md-9">
                        <?php include('inc/menu_product_left.php'); ?>
                    </div>
                    <!-- End Sidebar -->

                </div>
            </div>
        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

<?php include('footer.php'); ?>