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
        <section id="tag" class="content-page ">
            <?php $tags = get_product_tags(90000,0); ?>
            <!-- container -->
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="list-page-title">
                            <h2 class=""><?php echo $this->page; ?> <small><?php echo count($tags) ?> Tag</small></h2>
                        </div>
                        <div class="sidebar-widget-outline widget-filter-tag">
                            <ul class="widget-content">
                                <?php
                                foreach ($tags as $key => $val) { ?>
                                <li>
                                    <a href="<?php echo base_url().'tag/'.$val->url; ?>"><?php echo ucwords($val->product_tags_name) ?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End container -->

        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->


<?php include('footer.php'); ?>