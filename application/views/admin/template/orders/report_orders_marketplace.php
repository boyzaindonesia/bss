<div class="the-box no-border">

    <div class="relative no-border mt-20">
        <div class="product-list mb-0">
            <?php
            // debugCode($data);
            if(count($data) > 0){
                foreach($data as $r){
            ?>
            <div class="product-list-item magnific-popup-wrap mb-10" data-id="<?php echo $r->id ?>">
                <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/large/".$r->images_cover);?>">
                    <div class="product-list-image" style="background-image: url(<?php echo get_image(base_url()."assets/collections/product/thumb/".$r->images_cover);?>);"></div>
                </a>
                <div class="product-list-content">
                    <p class="product-list-title no-margin no-padding"><strong><?php echo $r->allcode; ?> (<?php echo $r->day_on_going ?>)</strong></p>
                    <p class="product-list-title no-margin no-padding"><strong><?php echo $r->name; ?></strong></p>
                    <p class="no-margin no-padding">Beli: <?php echo convertRp($r->price_buy) ?>, Jual: <?php echo convertRp($r->price_sale) ?>
                    </p>
                    <p class="no-margin no-padding mb-5">Margin: <?php echo convertRp(($r->price_sale - $r->price_buy)) ?></p>
                </div>
                <div class="relative">
                    <p class="no-margin no-padding mb-5">Stok: <?php echo $r->total_stock;?> pcs, total beli <?php echo convertRp(($r->price_buy * $r->total_stock)) ?>, total jual <?php echo convertRp(($r->price_sale * $r->total_stock)) ?>, terjual: <?php echo $r->product_sold; ?> pcs</p>
                    <div class="relative">
                        <?php echo $r->item_html ?>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo 'Produk tidak ditemukan...';
            }
            ?>
        </div>

    </div>


</div>