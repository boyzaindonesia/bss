<?php include('header.php'); ?>

    <!-- Page Content Wraper -->
    <div class="page-content-wraper">
        <!-- Bread Crumb -->
        <section class="breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="breadcrumb-link">
                            <a href="<?php echo base_url() ?>">Home</a>
                            <span><?php echo $category_title ?></span>
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
                    <div class="col">
                        <h2 class="page-title"><?php echo $category_title ?></h2>
                    </div>
                </div>
                <div class="row">
                    <?php
                    if(count($article['data']) > 0){
                        foreach ($article['data'] as $key => $val) {
                        ?>
                    <div class="col-md-4 col-sm-6 blog-entry style-1">
                        <div class="blog-box">
                            <div class="blog-img-wrap">
                                <a href="<?php echo base_url().'news/'.$val->url; ?>">
                                <img src="<?php echo get_image(base_url()."assets/collections/article/small/".$val->article_image, 'no_image_720x439.jpg');?>" alt="" />
                                </a>
                            </div>
                            <div class="blog-box-content">
                                <div class="blog-box-content-inner">
                                    <h5 class="blog-title"><a href="<?php echo base_url().'news/'.$val->url; ?>"><?php echo $val->article_title ?></a></h5>
                                    <p class="info"><span>by <a href="javascript:void(0);"><?php echo get_user_name($val->article_user_id) ?></a></span><span><?php echo convDateTable($val->article_publishdate) ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <?php if(isset($article['paging']) && $article['paging']!=''){ ?>
                <div class="pagination-wraper">
                    <p><?php echo 'Showing '.$article['noPage'].' - '.$article['endPage'].' of '.$article['cRec'].' results'; ?></p>
                    <div class="pagination">
                        <?php echo $article['paging']; ?>
                    </div>
                </div>
                <?php } ?>

            </div>
        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

<?php include('footer.php'); ?>