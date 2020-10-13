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
        <section class="content-page content-about">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="img-logo-about">
                            <img src="<?php echo base_url()."assets/front/images/logo_2_big.png";?>" alt="">
                        </div>
                        <p>Butik Sasha adalah Toko Online Hijab yang menjual bermacam-macam jilbab berkualitas premium dengan harga terjangkau. Yuk lengkapi koleksi hijabmu dengan belanja di Butik Sasha.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Page Content -->
        <style type="text/css">
            .content-about .img-logo-about { text-align:center; }
        </style>

    </div>
    <!-- End Page Content Wraper -->

<?php include('footer.php'); ?>