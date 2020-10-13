<?php
// $isLike     = get_check_like($val->product_id,'member_like');
$isWishlist = get_check_like($val->product_id,'member_wishlist');
$isNew      = check_is_new_product($val->product_date);
$isSale     = chk_awards_product($val->product_awards, 2);
?>
<div class="product-item">
    <div class="product-item-inner">
        <?php if($isNew){ ?>
        <div class="new-label">New</div>
        <?php } else if($isSale){ ?>
        <div class="sale-label"><?php echo get_product_awards(2)->product_awards_name ?></div>
        <?php } else if($val->product_price_discount > 0){ ?>
        <div class="discount-label"><?php echo calcPercentDiscount($val->product_price_discount,$val->product_price_sale) ?>%<div>off</div></div>
        <?php } ?>

        <div class="product-img-wrap">
            <a class="show" href="<?php echo base_url().$val->url_product_category.'/'.$val->url_product ?>">
            <img src="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($val->product_id), 'no_image_product.jpg');?>" alt="">
            </a>
        </div>

        <?php if($val->product_price_discount == 0 && $val->product_price_grosir != '' && $val->product_price_grosir != '[]'){ ?>
        <div class="wholesale-label">
            <div class="wholesale-label-content">
                Grosir
                <i class="fa fa-angle-down"></i>
                <div class="wholesale-popup">
                    <?php
                    $product_price_grosir = json_decode($val->product_price_grosir);
                    if(is_array($val->product_price_grosir)){ $product_price_grosir = $val->product_price_grosir; }
                    foreach ($product_price_grosir as $key2 => $val2) { ?>
                    <div class="wholesale-popup__row">
                        <span class="wholesale-popup__qty"><?php echo $val2->name ?> item</span>
                        <span class="wholesale-popup__price"><?php echo convertRP($val2->price) ?></span>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if($val->product_status_id == 3){ ?>
        <span class="out-stock"><?php echo get_name_product_status($val->product_status_id) ?></span>
        <?php } ?>
    </div>
    <div class="product-detail">
        <p class="product-title"><a href="<?php echo base_url().$val->url_product_category.'/'.$val->url_product ?>"><?php echo $val->product_name ?></a></p>
        <h5 class="item-price">
            <?php
            if($val->product_price_discount > 0){
                echo '<del>'.convertRp($val->product_price_sale).'</del>';
                echo convertRp($val->product_price_discount);
            } else {
                echo convertRp($val->product_price_sale);
            } ?>
        </h5>
    </div>
</div>