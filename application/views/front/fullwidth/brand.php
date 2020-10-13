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
        <section class="content-page brand-logo">
            <div class="container">
                <div class="row">
                    <?php
                    $product_brand = get_product_brand();
                    foreach ($product_brand as $key => $val) { ?>
                    <div class="col-sm-2">
                        <div class="brand-item">
                            <a href="<?php echo base_url().'brand/'.$val->url; ?>">
                                <img src="<?php echo get_image(base_url()."assets/collections/brand/large/".$val->product_brand_image, 'no_image_product.jpg');?>" alt="<?php echo $val->product_brand_name ?>" />
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

<?php include('footer.php'); ?>