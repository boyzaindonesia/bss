<li>
    <a class="product-img" href="<?php echo base_url().$val->url_product_category.'/'.$val->url_product ?>">
        <img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($val->product_id), 'no_image_product.jpg');?>" alt="">
    </a>
    <div class="product-content">
        <a class="product-link" href="<?php echo base_url().$val->url_product_category.'/'.$val->url_product ?>"><?php echo $val->product_name ?></a>
        <div class="star-rating">
            <?php
            $countRating = calcRating($product->product_rating, $product->product_review);
            $countRating = ($countRating > 0?$countRating:4.8);
            ?>
            <span style="width: <?php echo calcPercent($countRating, 5) ?>%;"></span>
        </div>
        <span class="product-amount">
            <?php
            if($val->product_price_discount > 0){
                echo '<del>'.convertRp($val->product_price_sale).'</del>';
                echo convertRp($val->product_price_discount);
            } else {
                echo convertRp($val->product_price_sale);
            } ?>
        </span>
    </div>
</li>