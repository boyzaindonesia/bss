<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <!-- Basic -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Cetak Alamat Simple</title>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/images/favicon.ico">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <style type="text/css">
        #page,.butiksasha-transaction-slip{position:relative;width:100%}blockquote,img,pre,tr{page-break-inside:avoid}body,dd,dl{margin:0;}.hide{display:none !important;}.border-bottom{border-bottom:1px solid #000}.print-only,.show-for-print{display:none!important}*,:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}html{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-weight:400;font-size:14px;line-height:1.4;background-color:#fff;color:#333;overflow-y:scroll;min-height:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{padding:0}h1,h2,h3,h4,h5,h6,p{margin-top:0;margin-bottom:24px}th>h1,th>h2,th>h3,th>h4,th>h5,th>h6,th>p{margin-bottom:0}h1,h2,h3,h4,h5,h6{line-height:1.2}@media print{*{background:0 0!important;color:#000!important;-webkit-box-shadow:none!important;box-shadow:none!important;text-shadow:none!important;-webkit-filter:none!important;filter:none!important;-ms-filter:none!important}html{font-family:"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Arial,sans-serif;overflow-x:visible;font-size:12px;line-height:1.5}body{margin:0;padding:0}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}abbr[title]:after{content:" (" attr(title) ")"}.ir a:after,a[href^="javascript:"]:after,a[href^="#"]:after{content:""}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}.hide-on-print{display:none!important}.print-only{display:block!important}.hide-for-print{display:none!important}.show-for-print{display:inherit!important}.break-page-after{page-break-after:always;page-break-inside:avoid}}
    </style>
    <script>
        <?php if(count($data)>0){ ?>
        // print();
        <?php } ?>
    </script>
</head>
<body>

<div id="page">
    <div class="row clearfix">

<?php
if(count($data)>0){

    $i = 1;
    foreach ($data as $v => $r) {
        $print_id = $r->orders_id;
        if(isset($_GET['from']) && $_GET['from']=="print_address"){ $print_id = $r->print_address_id; }
        if($print_id){

            $store_id           = $r->store_id;
            $detail_store       = get_detail_store($store_id);
            $store_name         = $detail_store->store_name;
            $store_phone        = $detail_store->store_phone;
            $store_logo         = $detail_store->store_logo;
            $store_product      = $detail_store->store_product;
            $store_noted_thanks = $detail_store->store_noted_thanks;
            $store_jlc          = $detail_store->store_jlc;

            if(isset($_GET['from']) && $_GET['from']=="print_address"){
                $orders_date      = convDate2($r->print_address_date);
            } else {
                $orders_date      = convDate2($r->orders_date);
            }

            $dropship         = $r->orders_shipping_dropship;
            $source_id        = $r->orders_source_id;
            $source_invoice   = $r->orders_source_invoice;
            $source_image     = get_orders_source($r->orders_source_id)->orders_source_image;
            $isi_paket        = $r->orders_product_category_title;
            $shipping_courier = $r->orders_courier_id;
            $orders_courier   = get_orders_courier($r->orders_courier_id);
            $courier_name     = $orders_courier->orders_courier_name;
            $courier_service  = $orders_courier->orders_courier_service;
            $courier_image    = $orders_courier->orders_courier_image;
            $ship_name        = $r->orders_ship_name;
            $ship_phone       = $r->orders_ship_phone;
            $shipping_resi    = $r->orders_shipping_resi;
            $shipping_name    = $r->orders_shipping_name;
            $shipping_phone   = $r->orders_shipping_phone;
            $shipping_address = $r->orders_shipping_address;
            $price_shipping   = $r->orders_price_shipping;
            $price_insurance  = $r->orders_price_insurance;
            $shipping_weight  = $r->orders_shipping_weight;
            $orders_noted     = $r->orders_noted;

            $code_ship_courier = '';
            if($store_id == 1 || $store_id == 2){
                switch ($shipping_courier) {
                    case '2': $code_ship_courier = '#10 '; break;
                    case '3': $code_ship_courier = '#10 '; break;
                    case '4': $code_ship_courier = '#10 '; break;
                    default: $code_ship_courier = ''; break;
                }
            }
        ?>
        <div>Kurir: <?php echo $courier_name ?> <?php echo $courier_service ?> <?php echo ($courier_name == "JNE" && $store_jlc != ""?'(JLC:'.$store_jlc.')':'') ?></div>
        <?php if($shipping_resi != ''){ ?>
        <div>Kode Booking: <?php echo $shipping_resi ?></div>
        <?php } ?>
        <div>Isi Paket: <?php echo $isi_paket ?></div>
        <div>Nama Pengirim: <?php echo $code_ship_courier ?> <?php echo ($ship_name!=''?$ship_name:$store_name) ?></div>
        <div>Hp Pengirim: <?php echo ($ship_phone!=''?$ship_phone:$store_phone) ?></div>
        <div>Nama Penerima: <?php echo $shipping_name ?></div>
        <div>Hp: <?php echo $shipping_phone ?></div>
        <div>Alamat: <?php echo $shipping_address ?></div>
        <div>Ongkir: <?php echo ($price_shipping==0?'-':convertRp($price_shipping)) ?></div>
        <?php if($price_insurance != 0){ ?>
        <div>Asuransi: <?php echo convertRp($price_insurance) ?></div>
        <?php } ?>
        <div>Berat: <?php echo $shipping_weight ?></div>
        <?php if($orders_noted != ''){ ?>
        <div>Catatan: <?php echo $orders_noted ?></div>
        <?php } ?>
        <div style="height: 20px;">&nbsp;</div>
    <?php
        $i += 1;
        }
    }
} else {
    if(!isset($_GET['from']) || $_GET['from']==""){
        $get_ready = get_ready_print_orders_shipping_name();
        if(count($get_ready)>0){ ?>
        <form id="form1" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="thisAction" value="save" />
            <?php foreach ($get_ready as $key => $value) { ?>
            <input type="hidden" name="id[]" value="<?php echo $value->orders_id; ?>" />
            <?php } ?>
        </form>
        <script type="text/javascript">
            document.getElementById('form1').submit();
        </script>
        <?php
        } else {
            echo 'Tidak ada label yg ingin dicetak.';
        }
    }
}
?>
    </div>
</div>
</body>
</html>