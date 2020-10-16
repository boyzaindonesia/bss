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
            <img src="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($val->product_id), 'no_image_product.jpg');?>" alt="">
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
        <?php } else { ?>
        <div class="product-button">
            <a href="javascript:void(0);" class="js_tooltip btn-popup-buy-marketplace modal-view" data-toggle="modal" data-target="#productModal" data-id="<?php echo $val->product_id ?>" data-name="<?php echo $val->product_name ?>" data-image="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($val->product_id), 'no_image_product.jpg');?>" data-mode="top" data-tip="Beli"><i class="fa fa-shopping-bag"></i></a>
            <a href="javascript:void(0);" class="js_tooltip btn-add-like <?php echo ($isWishlist?'active':'') ?>" data-like-id="<?php echo $val->product_id ?>" data-like-type="wishlist" data-mode="top" data-tip="<?php echo ($isWishlist?'Wishlist':'Tambah ke Wishlist') ?>"><i class="small-ajax-loader hide"></i><i class="fa fa-heart"></i></a>
            <a href="<?php echo base_url().$val->url_product_category.'/'.$val->url_product ?>" class="js_tooltip" data-mode="top" data-tip="Lihat"><i class="fa fa-eye"></i></a>
        </div>
        <?php } ?>
    </div>
    <div class="product-detail">
        <a href="<?php echo base_url().$val->url_product_category ?>" class="tag"><?php echo $val->product_category_name ?></a>
        <p class="product-title"><a href="<?php echo base_url().$val->url_product_category.'/'.$val->url_product ?>"><?php echo $val->product_name ?></a></p>
        <div class="product-rating">
            <?php
            $countRating = calcRating($product->product_rating, $product->product_review);
            $countRating = ($countRating > 0?$countRating:4.8);
            ?>
            <div class="star-rating" itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating" title="Rated <?php echo $countRating; ?> out of 5">
                <span style="width: <?php echo calcPercent($countRating, 5) ?>%;"></span>
            </div>
            <a href="javascript:void(0);" class="product-rating-count"><span class="count"><?php echo $val->product_review; ?></span> Reviews</a>
        </div>
        <p class="product-description"><?php echo getFirstParaLg($val->product_description); ?></p>
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