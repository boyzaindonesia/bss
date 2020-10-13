<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <!-- Basic -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Cetak Alamat</title>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/images/favicon.ico">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <style type="text/css">
        #page,.butiksasha-transaction-slip{position:relative;width:100%}blockquote,img,pre,tr{page-break-inside:avoid}body,dd,dl{margin:0;}.hide{display:none !important;}.border-bottom{border-bottom:1px solid #000}.print-only,.show-for-print{display:none!important}*,:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}html{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-weight:400;font-size:14px;line-height:1.4;background-color:#fff;color:#333;overflow-y:scroll;min-height:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{padding:0}h1,h2,h3,h4,h5,h6,p{margin-top:0;margin-bottom:24px}th>h1,th>h2,th>h3,th>h4,th>h5,th>h6,th>p{margin-bottom:0}h1,h2,h3,h4,h5,h6{line-height:1.2}@media print{*{background:0 0!important;color:#000!important;-webkit-box-shadow:none!important;box-shadow:none!important;text-shadow:none!important;-webkit-filter:none!important;filter:none!important;-ms-filter:none!important}html{font-family:"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Arial,sans-serif;overflow-x:visible;font-size:12px;line-height:1.5}body{margin:0;padding:0}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}abbr[title]:after{content:" (" attr(title) ")"}.ir a:after,a[href^="javascript:"]:after,a[href^="#"]:after{content:""}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}.hide-on-print{display:none!important}.print-only{display:block!important}.hide-for-print{display:none!important}.show-for-print{display:inherit!important}.break-page-after{page-break-after:always;page-break-inside:avoid}}.butiksasha-transaction-slip-buyer--label:after,.butiksasha-transaction-slip-seller--label:after{content:""}@page{margin:1.25cm .5cm}blockquote,pre{border:1px solid #999}thead{display:table-header-group}table{border-collapse:collapse}td,th{border:1px solid #333;padding:6px;vertical-align:top}h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{font-weight:inherit}h2{font-size:2em}h3{font-size:1.5em}h4{font-size:1.25em}h5{font-size:1.2em}h6{font-size:1.1em}.butiksasha-transaction-container{position:relative;padding:0 4px}.butiksasha-transaction-slip{border:1px solid #000;height:331px;padding:0;margin-bottom:8px}.butiksasha-transaction-slip-date{position:absolute;top:0px;left:0px;width:100%;text-align:center;}.butiksasha-transaction-slip-date h2,.butiksasha-transaction-slip-date p{margin:0px;}.butiksasha-transaction-slip-date h2{font-size:24px;line-height:26px;margin-top:3px;}.butiksasha-transaction-slip-date p{font-size:13px;line-height:14px;margin-top:-1px;}.butiksasha-transaction-slip-header{position:relative;top:0;left:0;width:100%;height:auto;padding:0 8px;border-bottom:1px solid #000}.butiksasha-transaction-slip-brand--left,.butiksasha-transaction-slip-brand--right{display:block;float:left;width:50%;padding:5px 0}.butiksasha-transaction-slip-brand--left img,.butiksasha-transaction-slip-brand--right img{height:21px;width:auto;display:block}.butiksasha-transaction-slip-brand--left{padding-right:1px;border-right:1px solid #000}.butiksasha-transaction-slip-brand--right img{float:right}.butiksasha-transaction-slip-title{position:absolute;top:0;left:0;text-align:center;width:100%;margin-top:5px}.butiksasha-transaction-slip-buyer{position:relative;top:0;left:0;width:100%;height:151px;padding:10px}.butiksasha-transaction-slip-buyer--heading{font-weight:700;margin-top:0}.butiksasha-transaction-slip-buyer--label{display:block;float:left;clear:both;width:58px}.butiksasha-transaction-slip-buyer--name,.butiksasha-transaction-slip-buyer--phone{font-weight:700}.butiksasha-transaction-slip-buyer--address{position:absolute;top:0;left:0;padding-top:10px;padding-left:64px;padding-right:10px;display:block;font-weight:700;width:100%}.butiksasha-transaction-slip-seller{display:block;float:left;width:50%;padding:5px 0;text-align:right}.butiksasha-transaction-slip-seller--label{display:block;float:left;clear:both;width:62px}.butiksasha-transaction-slip-seller--name,.butiksasha-transaction-slip-seller--phone{font-weight:500}.butiksasha-transaction-slip-courier{position:relative;float:left;width:50%;display:block;text-align:left;padding:5px 0;font-size:11px;line-height:18px}.butiksasha-transaction-slip-barcode{position:relative;top:0;left:0;height:50px;padding:5px 10px;text-align:center;border-top:1px solid #000}.butiksasha-transaction-slip-barcode h3{margin:0}.butiksasha-transaction-slip--footer{display:block;width:100%;clear:both;border-top:solid 1px #000;padding-top:4px;padding-left:10px;padding-right:10px;font-size:9px}.butiksasha-transaction-product{clear:both;position:relative;width:100%}.butiksasha-transaction-product-item{width:80%}.butiksasha-transaction-product-quantity{width:20%}img{vertical-align:middle;max-width:100%;height:auto}.clearfix:after{content:".";display:block;clear:both;visibility:hidden;line-height:0;height:0}.clearfix{display:block}.row{position:relative}.row div[class*=span]{position:relative;top:0;left:0;float:left;display:block}.row .span1{width:10%}.row .span2{width:20%}.row .span3{width:30%}.row .span4{width:40%}.row .span5{width:50%}.row .span6{width:60%}.row .span7{width:70%}.row .span8{width:80%}.row .span9{width:90%}.row .span10{width:100%}.sosmed,.sosmed .sosmed-item,.sosmed .sosmed-item .sosmed-img{position:relative;top:0;left:0}.sosmed{width:100%;font-size:8px;line-height:10px;margin-top:3px}.sosmed .sosmed-item{float:left}.sosmed .sosmed-item .sosmed-img{float:left;width:10px;height:10px}.sosmed .sosmed-item .sosmed-text{position:relative;float:left;top:0;left:0;padding-left:5px;margin-right:5px;margin-bottom:3px}.sosmed .sosmed-item.item-1{width:32%;}.sosmed .sosmed-item.item-2{width:27.5%;}.sosmed .sosmed-item.item-3{width:20.5%;}.sosmed .sosmed-item.item-4{width:20%;}
            .butiksasha-transaction-label .span10 .date {font-size:11px;line-height:15px;padding:0px 7px;margin: 5px 0px;}
            .butiksasha-transaction-label .span3 {overflow:hidden;}
            .butiksasha-transaction-label .span3 p {font-size:11px;line-height:15px;padding:0px 7px;margin:0px 0px 5px 0px; white-space: nowrap;}
    </style>
    <script>
        print();
    </script>
</head>
<body>

<div id="page">
    <div class="row clearfix">

<?php
    if(count($data)){

    $rowsPerPage  = 15;
    $numrows      = count($data);
    $maxPage      = ceil($numrows/($rowsPerPage * 3));
    $no = 1;
    ?>
<!--     <div class="span5 butiksasha-transaction-container">
        <div class="butiksasha-transaction-slip">
            <div class="row butiksasha-transaction-label">
                <div class="span10"><p class="date">Tanggal: <?php echo convDate2(timestamp()) ?></p></div>
                <div class="span3">
                <?php
                $aa = 1;
                $ii = 1;
                foreach ($data as $v => $r) {
                    if($r->print_address_id){
                        if($aa > ($rowsPerPage * 3)){
                            echo '</div>
                                </div>
                            </div>
                        </div>';
                        echo '<div class="span5 butiksasha-transaction-container">
                                <div class="butiksasha-transaction-slip">
                                    <div class="row butiksasha-transaction-label">
                                        <div class="span10"><p class="date">Tanggal: '.convDate2(timestamp()).'</p></div>
                                        <div class="span3">';
                            $aa = 1;
                        }

                        if($ii > $rowsPerPage){ $ii = 1; echo '</div><div class="span3">'; }
                        switch ($r->orders_source_id) {
                            case '2': $source = 'bl'; break;
                            case '3': $source = 'tp'; break;
                            case '7': $source = 'wa'; break;
                            case '8': $source = 'sp'; break;
                            case '10': $source = 'ds'; break;
                            default: $source = ''; break;
                        }
                        echo '<p>'.$no.'. '.getFirstParaNumbNoDot(strtolower($r->orders_shipping_name) ,12).' -'.$source.'</p>';

                        $aa += 1;
                        $ii += 1;
                        $no += 1;
                    }
                }
                ?>
                </div>
            </div>
        </div>
    </div> -->

    <?php
    // $i = $maxPage + 1;
    $i = 1;
    foreach ($data as $v => $r) {
        if($r->print_address_id){
            if($i==7){ $i = 1; echo '<div class="break-page-after"></div>'; }
        ?>
        <div class="span5 butiksasha-transaction-container">
            <div class="butiksasha-transaction-slip <?php echo ($r->orders_ship_name!='Butik Sasha'?'height-auto':'') ?>">
                <div class="butiksasha-transaction-slip-header clearfix <?php echo ($r->orders_ship_name!='Butik Sasha'?'hide':'') ?>">
                    <div class="butiksasha-transaction-slip-brand--left">
                        <img src="<?php echo base_url().'/assets/collections/logo/thumb/dark/print_'.get_orders_source($r->orders_source_id)->orders_source_image ?>" alt="Logo <?php echo get_orders_source($r->orders_source_id)->orders_source_name ?>" />
                    </div>
                    <div class="butiksasha-transaction-slip-brand--right">
                        <img src="<?php echo base_url().'/assets/collections/logo/thumb/dark/print_butiksasha.png' ?>" alt="Logo Butik Sasha" />
                    </div>
                </div>

                <div class="butiksasha-transaction-slip-header clearfix">
                    <div class='butiksasha-transaction-slip-courier'>
                        <div class='butiksasha-transaction-slip-courier--name'>Isi Paket: <?php echo $r->orders_product_category_title ?></div>
                        <div class='butiksasha-transaction-slip-courier--name'>Kurir: <?php echo get_orders_shipping_courier($r->orders_courier_id)['name'] ?> <?php echo get_orders_shipping_courier($r->orders_courier_id)['service'] ?></div>
                    </div>
                    <div class="butiksasha-transaction-slip-date">
                        <?php
                        $print_address_date = convDate2($r->print_address_date);
                        $print_date = explode(' ', $print_address_date);
                        echo '<h2>'.$print_date[0].'</h2>';
                        echo '<p>'.$print_date[1].'</p>';
                        ?>
                    </div>
                    <div class='butiksasha-transaction-slip-seller'>
                        <div class='butiksasha-transaction-slip-seller--name'>Pengirim: <?php echo ($r->orders_ship_name!=''?$r->orders_ship_name:'Butik Sasha') ?></div>
                        <div class='butiksasha-transaction-slip-seller--name'><?php echo ($r->orders_ship_phone!=''?$r->orders_ship_phone:'082111114363') ?></div>
                    </div>
                </div>

                <div class='butiksasha-transaction-slip-buyer clearfix <?php echo ($r->orders_ship_name!='Butik Sasha'?'border-bottom':'') ?>'>
                    <div class='butiksasha-transaction-slip-buyer--name'><strong>Kepada:</strong></div>
                    <div class="butiksasha-transaction-slip-buyer--address">
                        <div class=""><?php echo $r->orders_shipping_name ?></div>
                        <div class=""><?php echo $r->orders_shipping_address ?><?php echo ($r->orders_shipping_city!='0'?', Kota '.getCitySet($r->orders_shipping_city):'') ?></div>
                            <div><?php echo getProvSet($r->orders_shipping_province) ?><?php echo ($r->orders_shipping_postal_code!='0'?' - '.$r->orders_shipping_postal_code:'') ?>
                            </div>
                        <div class=""><?php echo $r->orders_shipping_phone ?></div>
                    </div>
                </div>

                <div class='butiksasha-transaction-slip-barcode <?php echo ($r->orders_ship_name!='Butik Sasha'?'hide':'') ?>'>
                    <small>Transaksi</small>
                    <h3><strong><?php echo $r->orders_invoice ?></strong></h3>
                </div>
                <div class='butiksasha-transaction-slip--footer <?php echo ($r->orders_ship_name!='Butik Sasha'?'hide':'') ?>'>
                    <div>Terima kasih atas kepercayaan Anda berbelanja di <strong>butiksasha</strong>.</div>
                    <div class="sosmed clearfix">
                        <div class="sosmed-item item-1">
                            <img class="sosmed-img" src="<?php echo base_url().'/assets/collections/logo/thumb/dark/sosmed_wa.png' ?>">
                            <div class="sosmed-text">082111114363</div>
                        </div>
                        <div class="sosmed-item item-2">
                            <img class="sosmed-img" src="<?php echo base_url().'/assets/collections/logo/thumb/dark/sosmed_fb.png' ?>">
                            <div class="sosmed-text">butiksashahijab</div>
                        </div>
                        <div class="sosmed-item item-3">
                            <img class="sosmed-img" src="<?php echo base_url().'/assets/collections/logo/thumb/dark/sosmed_line.png' ?>">
                            <div class="sosmed-text">butiksasha</div>
                        </div>
                        <div class="sosmed-item item-4">
                            <img class="sosmed-img" src="<?php echo base_url().'/assets/collections/logo/thumb/dark/sosmed_bl.png' ?>">
                            <div class="sosmed-text">butiksasha</div>
                        </div>
                        <div class="sosmed-item item-1">
                            <img class="sosmed-img" src="<?php echo base_url().'/assets/collections/logo/thumb/dark/sosmed_web.png' ?>">
                            <div class="sosmed-text">www.butiksasha.com</div>
                        </div>
                        <div class="sosmed-item item-2">
                            <img class="sosmed-img" src="<?php echo base_url().'/assets/collections/logo/thumb/dark/sosmed_ig.png' ?>">
                            <div class="sosmed-text">butiksashahijab</div>
                        </div>
                        <div class="sosmed-item item-3">
                            <img class="sosmed-img" src="<?php echo base_url().'/assets/collections/logo/thumb/dark/sosmed_shopee.png' ?>">
                            <div class="sosmed-text">butiksasha</div>
                        </div>
                        <div class="sosmed-item item-4">
                            <img class="sosmed-img" src="<?php echo base_url().'/assets/collections/logo/thumb/dark/sosmed_tokped.png' ?>">
                            <div class="sosmed-text">butiksasha</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        $i += 1;
        }
    }
}
?>
</div>
</div>
</body>
</html>