<!-- Categories -->
<div class="widget-sidebar">
    <h6 class="widget-title">SHOP CATEGORIES</h6>
    <ul class="widget-content widget-category-menu">
        <li>
            <a href="<?php echo base_url() ?>collections?from=sidebar">Semua Kategori</a>
        </li>
        <?php
        $awards = get_product_awards();
        foreach ($awards as $key => $val) { ?>
        <li>
            <a href="<?php echo base_url().$val->url; ?>?from=sidebar"><?php echo $val->product_awards_name ?></a>
            <span class="count">(<?php echo get_count_product_awards($val->product_awards_id) ?>)</span>
        </li>
        <?php } ?>
        <?php
        $category_menu = front_get_category_menu();
        foreach ($category_menu as $key => $val) { ?>
        <li>
            <a href="<?php echo base_url().$val->url; ?>?from=sidebar"><?php echo $val->product_category_title ?></a>
            <span class="count">(<?php echo get_count_product_category($val->product_category_id) ?>)</span>
        </li>
        <?php } ?>
        <li>
            <a href="<?php echo base_url() ?>sold-out?from=sidebar">Sold Out</a>
            <span class="count">(<?php echo get_count_product_status(3) ?>)</span>
        </li>
    </ul>
</div>

<!-- Widget Brand -->
<div class="widget-sidebar widget-category-menu">
    <h6 class="widget-title">Popular Brand</h6>
    <ul class="widget-content">
        <?php
        $product_brand = get_product_brand();
        foreach ($product_brand as $key => $val) { ?>
        <li>
            <a href="<?php echo base_url().'brand/'.$val->url; ?>?from=sidebar"><?php echo ucwords($val->product_brand_name) ?></a>
            <span class="count">(<?php echo get_count_product_brand($val->product_brand_id) ?>)</span>
        </li>
        <?php } ?>
    </ul>
</div>

<!-- Widget Product -->
<div class="widget-sidebar widget-product">
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

<!-- Widget Tag -->
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

<?php
$get_banner = get_banner_productpage_sideleft();
if(count($get_banner) > 0){
    foreach ($get_banner as $key => $val) {
    $get_read_link = get_read_link($val->link_type, $val->link_value);
?>

<a href="<?php echo ($get_read_link!=''?$get_read_link:'javascript:void(0);') ?>">
    <div class="widget-sidebar widget-banner">
        <div class="banner-image-wrap">
            <img src="<?php echo get_image(base_url().'assets/collections/banner/large/'.$val->banner_images) ?>" alt="" />
        </div>
    </div>
</a>
<?php }
} ?>