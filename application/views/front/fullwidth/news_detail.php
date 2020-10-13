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
                            <a href="<?php echo base_url() ?>news"><?php echo $category_title ?></a>
                            <span><?php echo $article->article_title ?></span>
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
                    <!-- Blog Content -->
                    <div class="col-md-9 blog-single style-1">
                        <div class="blog-box">
                            <?php if(count($article) == 0){ ?>
                            <div class="alert alert-danger square fade in alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                Artikel tidak ditemukan...
                            </div>
                            <?php } else { ?>
                            <div class="blog-img-wrap">
                                <img src="<?php echo get_image(base_url()."assets/collections/article/large/".$article->article_image, 'no_image_720x439.jpg');?>" alt="" />
                            </div>
                            <div class="blog-box-content">
                                <div class="blog-box-content-inner">
                                    <h4 class="blog-title"><?php echo $article->article_title ?></h4>
                                    <p class="info"><span>by <a href="javascript:void(0);"><?php echo get_user_name($article->article_user_id) ?></a></span><span><?php echo convDateTable($article->article_publishdate) ?></span></p>
                                    <div class="blog-description-content">
                                        <?php echo $article->article_content ?>
                                    </div>
                                    <?php
                                    $tags = "";
                                    if($article->article_tags != ""){
                                        $i = 0;
                                        $expTags = explode(",", $article->article_tags);
                                        foreach ($expTags as $n) {
                                            $tags .= ($i==0?'':',&nbsp;').'<a href="'.base_url().'tag/'.$n.'/">'.ucwords($n).'</a>';
                                            $i += 1;
                                        }
                                    }
                                    ?>
                                    <p class="info"><span>Tags: <?php echo $tags ?></span></p>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- End Blog Content -->

                    <!-- Sidebar -->
                    <div class="sidebar-container col-md-3">
                        <?php include('inc/menu_news_right.php'); ?>
                    </div>
                    <!-- End Sidebar -->

                </div>
                <hr class="mb-30" />
            </div>
            <div class="container">
                <h2 class="page-title">Related Blog & News</h2>
            </div>
            <div class="container">
                <div id="blog-carousel" class="blog-carousel owl-carousel owl-theme nf-carousel-theme1">
                    <?php
                    $related_news = front_related_article($article->article_id,1,6);
                    if(count($related_news) > 0){
                        foreach ($related_news as $key => $val) {
                        ?>
                        <div class="product-item">
                            <div class="blog-box">
                                <div class="blog-img-wrap">
                                    <a href="<?php echo base_url().'news/'.$val->url; ?>">
                                    <img src="<?php echo get_image(base_url()."assets/collections/article/small/".$val->article_image, 'no_image_720x439.jpg');?>" alt="" />
                                    </a>
                                </div>
                                <div class="blog-box-content">
                                    <div class="blog-box-content-inner">
                                        <h4 class="blog-title"><a href="<?php echo base_url().'news/'.$val->url; ?>"><?php echo $val->article_title ?></a></h4>
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
            </div>
        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

<?php include('footer.php'); ?>