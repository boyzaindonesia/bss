<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <!-- Basic -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>Print Stock</title>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/images/favicon.ico">
    
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    
    <style type="text/css">
        body { position: relative;width:100%; padding: 0px; margin: 0px; }
        .clearfix:after {
    content:".";
    display:block;
    clear:both;
    visibility:hidden;
    line-height:0;
    height:0;
}
.clearfix {
    display:block;
}
        .row{position:relative;width:100%}
        .row .col { position: relative; width: 100%; border-bottom: 1px solid #ddd; }
        .item { position: relative; padding: 10px; padding-bottom: 0px; margin-bottom:10px; }
        .item .item-top { position: relative; width: 100%; }
        .item .item-bottom { position: relative; width: 100%; }

        .item .item-top .item-img { position: absolute; top: 0px; left: 0px; width: 80px; }
        .item .item-top .item-img img { position: relative; top: 0px; left: 0px; width: 100%; display: block; }
        .item .item-top .item-text { position: relative; top: 0px; left: 0px; padding-left: 90px; }
        .item .item-top .item-text h4 { position: relative; top: 0px; left: 0px; margin-top: 0px; margin-bottom: 3px; font-size: 14px; }
        .item .item-top .item-text h5 { position: relative; top: 0px; left: 0px; margin-top: 0px; margin-bottom: 0px; font-size: 10px; line-height: 14px; font-weight: normal; }
        .item p { font-size: 12px; line-height: 16px; margin: 0px; }
        .item .item-stock { position: relative; list-style: none; padding: 0px; margin: 0px; width: 100%; }
        .item .item-stock li { position: relative; float: left; width: 33.3%; }
    </style>
</head>
<body>

<a href="<?php echo base_url()?>print-address" target="_blank"><button style="margin: 10px 0px;">Cetak Label Pengiriman</button></a>
<!-- <a href="<?php echo base_url()?>print-shipping-address"><button style="margin: 10px 0px;">Buat Label Pengiriman</button></a> -->

<div class="row">

<?php 
// debugCode($data);
if(count($data)>0){ 
    foreach ($data as $r) { ?>
        <div class="col">
            <div class="item">
                <div class="item-top">
                    <div class="item-img">
                        <img src="<?php echo get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));?>">
                    </div>
                    <div class="item-text">
                        <h4><?php echo $r->product_name;?></h4>
                        <h5><span style="<?php echo ($r->product_price_discount!='0'?'text-decoration:line-through':'');?>"><?php echo convertRp($r->product_price_sale);?></span> <?php echo ($r->product_price_discount!='0'?'<span class="label label-danger">'.convertRp($r->product_price_discount).'</span>':'');?></h5>

                        <?php if(isset($r->product_price_grosir) && $r->product_price_grosir != ''){ ?>
                        <table border="0" style="font-size: 10px; line-height: 14px;">
                            <colgroup>
                                <col>
                                <col>
                            </colgroup>
                            <tbody>
                                <?php 
                                $product_price_grosir = json_decode($r->product_price_grosir);
                                foreach ($product_price_grosir as $key => $value) { ?>
                                <tr>
                                    <td style="padding: 0px;">
                                        <?php echo $value->name ?> barang
                                    </td>
                                    <td style="padding: 0px;">
                                        <?php echo convertRP($value->price) ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
                <div class="item-bottom">
                    <p>Stok: <?php echo $r->product_stock;?></p>
                    <?php if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){ ?>
                    <ul class="item-stock clearfix">
                        <?php 
                        $product_stock_detail = json_decode($r->product_stock_detail);
                        foreach ($product_stock_detail as $key => $value) { 
                            if($value->status==1){ ?>
                            <li><p><?php echo $value->name ?> = <?php echo $value->qty ?></p></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php
    }
}
?>
</div>
</body>
</html>