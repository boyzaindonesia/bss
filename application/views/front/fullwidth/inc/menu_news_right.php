<div class="widget-sidebar widget-product">
    <h6 class="widget-title">Recent Posts</h6>
    <ul class="widget-content">
        <?php
        $latest_news = front_latest_news(3);
        foreach ($latest_news as $key => $val) { ?>
        <li>
            <a class="product-img" href="<?php echo base_url().'news/'.$val->url; ?>">
                <img src="<?php echo get_image(base_url()."assets/collections/article/thumb/".$val->article_image, 'no_image_220x134.jpg');?>" alt="">
            </a>
            <div class="product-content">
                <a class="product-link" href="<?php echo base_url().'news/'.$val->url; ?>"><?php echo $val->article_title ?></a>
                <span class="date-description"><?php echo convDateTable($val->article_publishdate) ?></span>
            </div>
        </li>
        <?php } ?>
    </ul>
</div>

<div class="widget-sidebar widget-filter-tag">
    <h6 class="widget-title">Popular Tag</h6>
    <ul class="widget-content">
        <?php
        $popular_tag = get_product_tags_position(15,0);
        foreach ($popular_tag as $key => $val) { ?>
        <li>
            <a href="<?php echo base_url().'tag/'.$val->url; ?>"><?php echo ucwords($val->product_tags_name) ?></a>
        </li>
        <?php } ?>
    </ul>
</div>