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
                            <span><?php echo $this->page ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!-- Bread Crumb -->

        <!-- Page Content -->
        <section class="content-page">
            <div class="container">
                <ul class="product-content-tabs nav nav-tabs" role="tablist">
                    <?php foreach ($article_group as $key => $val) { ?>
                    <li class="nav-item"><a class="<?php echo ($this->cur_menu == $val->url?'active':'')?>" href="<?php echo base_url().$article_category->url.'/'.$val->url ?>"><?php echo $val->article_title ?></a></li>
                    <?php } ?>
                </ul>
                <div class="product-content-Tabs_wraper tab-content container">
                    <!-- <?php echo $legal->article_content ?> -->
                    Belum ada deskripsi.
                </div>
            </div>

        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

<?php include('footer.php'); ?>