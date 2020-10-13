<div class="sidebar-widget-outline product-brand">
    <a href="<?php echo base_url().'brand/'.$product_brand->url; ?>" class="image-link">
        <img src="<?php echo get_image(base_url()."assets/collections/brand/large/".$product_brand->product_brand_image, 'no_image_product.jpg');?>" alt="<?php echo $product_brand->product_brand_name ?>" /></a>
    <a href="<?php echo base_url().'brand/'.$product_brand->url; ?>" class="text-link">View All Product</a>
</div>
<div class="sidebar-widget-outline product-list widget-product">
    <h6 class="widget-title">Popular Product</h6>
    <ul class="widget-content">
        <?php
        $best_seller = get_product_best_selling('3');
        foreach ($best_seller as $key => $val) {
            include('layout_product_sidebar.php');
        }
        ?>
    </ul>
</div>
<div class="sidebar-widget-outline widget-filter-tag">
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