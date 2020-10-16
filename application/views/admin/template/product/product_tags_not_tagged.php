<ul class="nav nav-tabs item-color">
    <li class=""><a href="<?php echo $own_links ?>">Master</a></li>
    <li class="active"><a href="<?php echo $own_links ?>/product_not_tagged">Product Not Tagged</a></li>
</ul>

<div class="the-box no-border">
    <div class="table-responsive">
        <table class="table table-th-block table-dark datatable">
            <colgroup>
                <col width="1">
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr>
                    <th class="nobr text-center">No</th>
                    <th class="nobr text-center no-sort">Images</th>
                    <th>Nama</th>
                    <th class="nobr text-center">Harga</th>
                    <th class="nobr text-center">Kategori</th>
                    <th class="nobr text-center no-sort">Action</th>
                </tr>
            </thead>
            <tbody class="dragsort">
            <?php
            $product_tagged = get_list_product_not_tagged();
            if(count($product_tagged) > 0){
                $i = 1;
                foreach($product_tagged as $r){ ?>
                <tr>
                    <td class="nobr text-center"><?php echo $i ?>.</td>
                    <td class="magnific-popup-wrap">
                        <a class="zooming" href="<?php echo get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id));?>"><img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>" class="avatar mfp-fade"></a>
                    </td>
                    <td><?php echo $r->product_name;?></td>
                    <td class="nobr">
                        <span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?>
                    </td>
                    <td class="nobr"><?php echo $r->product_category_title;?></td>
                    <td class="nobr text-center">
                        &nbsp;
                    </td>
                </tr>
                <?php
                    $i += 1;
                }
            }
            ?>
            </tbody>
        </table>
    </div>

</div>