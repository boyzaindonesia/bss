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
        #page,.butiksasha-transaction-slip{position:relative;width:100%}blockquote,img,pre,tr{page-break-inside:avoid}body,dd,dl{margin:0;}.hide{display:none !important;}.border-bottom{border-bottom:1px solid #000}.print-only,.show-for-print{display:none!important}*,:after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}html{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;font-weight:400;font-size:14px;line-height:1.4;background-color:#fff;color:#333;overflow-y:scroll;min-height:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{padding:0}h1,h2,h3,h4,h5,h6,p{margin-top:0;margin-bottom:24px}th>h1,th>h2,th>h3,th>h4,th>h5,th>h6,th>p{margin-bottom:0}h1,h2,h3,h4,h5,h6{line-height:1.2}@media print{*{background:0 0!important;color:#000!important;-webkit-box-shadow:none!important;box-shadow:none!important;text-shadow:none!important;-webkit-filter:none!important;filter:none!important;-ms-filter:none!important}html{font-family:"Lucida Grande","Lucida Sans Unicode","Lucida Sans",Arial,sans-serif;overflow-x:visible;font-size:12px;line-height:1.5}body{margin:0;padding:0}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}abbr[title]:after{content:" (" attr(title) ")"}.ir a:after,a[href^="javascript:"]:after,a[href^="#"]:after{content:""}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}.hide-on-print{display:none!important}.print-only{display:block!important}.hide-for-print{display:none!important}.show-for-print{display:inherit!important}.break-page-after{page-break-after:always;page-break-inside:avoid}}.butiksasha-transaction-slip-buyer--label:after,.butiksasha-transaction-slip-seller--label:after{content:""}@page{margin:1.25cm .5cm}blockquote,pre{border:1px solid #999}thead{display:table-header-group}table{border-collapse:collapse}td,th{border:1px solid #333;padding:6px;vertical-align:top}h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{font-weight:inherit}h2{font-size:2em}h3{font-size:1.5em}h4{font-size:1.25em}h5{font-size:1.2em}h6{font-size:1.1em}.butiksasha-transaction-container{position:relative;padding:0 4px}.butiksasha-transaction-slip{border:1px solid #000;height:331px;padding:0;margin-bottom:8px}.butiksasha-transaction-slip-date{position:absolute;top:0px;left:0px;width:100%;text-align:center;}.butiksasha-transaction-slip-date h2,.butiksasha-transaction-slip-date p{margin:0px;}.butiksasha-transaction-slip-date h2{font-size:24px;line-height:26px;margin-top:3px;}.butiksasha-transaction-slip-date p{font-size:13px;line-height:14px;margin-top:-1px;}.butiksasha-transaction-slip-header{position:relative;top:0;left:0;width:100%;height:auto;padding:0 8px;border-bottom:1px solid #000}.butiksasha-transaction-slip-brand--left,.butiksasha-transaction-slip-brand--right{display:block;float:left;width:50%;padding:5px 0}.butiksasha-transaction-slip-brand--center{position:absolute;top:5px;left: 50%;margin-left:-40px;}.butiksasha-transaction-slip-brand--left img,.butiksasha-transaction-slip-brand--center img,.butiksasha-transaction-slip-brand--right img{height:21px;width:auto;display:block}.butiksasha-transaction-slip-brand--right img{float:right}.butiksasha-transaction-slip-title{position:absolute;top:0;left:0;text-align:center;width:100%;margin-top:5px}.butiksasha-transaction-slip-buyer{position:relative;top:0;left:0;width:100%;height:137px;padding:5px 10px}.butiksasha-transaction-slip-buyer--heading{font-weight:700;margin-top:0}.butiksasha-transaction-slip-buyer--label{display:block;float:left;clear:both;width:58px}.butiksasha-transaction-slip-buyer--name{position:relative;top:0px;left:0px;font-size:12px;line-height:18px;font-weight:700;}.butiksasha-transaction-slip-buyer--address{position:relative;top:0;left:0;font-size:11px;line-height:16px;display:block;width:100%}.butiksasha-transaction-slip-seller{position:relative;display:block;float:left;width:50%;padding:5px 0;text-align:right;z-index:9;}.butiksasha-transaction-slip-seller--label{display:block;float:left;clear:both;width:62px}.butiksasha-transaction-slip-seller--name,.butiksasha-transaction-slip-seller--phone{font-weight:500}.butiksasha-transaction-slip-courier{position:relative;float:left;width:50%;display:block;text-align:left;padding:5px 0;font-size:11px;line-height:18px;z-index:9;}.butiksasha-transaction-slip-barcode{position:relative;top:0;left:0;height:64px;padding:5px 10px;text-align:center;border-bottom:1px solid #000}.butiksasha-transaction-slip-barcode h3,.butiksasha-transaction-slip-barcode h4{margin:0}.butiksasha-transaction-slip-barcode h4.small{font-size:12px;line-height:22px;}.butiksasha-transaction-slip--footer{display:block;width:100%;clear:both;border-top:solid 1px #000;padding-top:4px;padding-left:10px;padding-right:10px;font-size:9px}.butiksasha-transaction-product{clear:both;position:relative;width:100%}.butiksasha-transaction-product-item{width:80%}.butiksasha-transaction-product-quantity{width:20%}img{vertical-align:middle;max-width:100%;height:auto}.clearfix:after{content:".";display:block;clear:both;visibility:hidden;line-height:0;height:0}.clearfix{display:block}.row{position:relative}.row div[class*=span]{position:relative;top:0;left:0;float:left;display:block}.row .span1{width:10%}.row .span2{width:20%}.row .span3{width:30%}.row .span4{width:40%}.row .span5{width:50%}.row .span6{width:60%}.row .span7{width:70%}.row .span8{width:80%}.row .span9{width:90%}.row .span10{width:100%}
            .butiksasha-transaction-label .span10 .date {font-size:11px;line-height:15px;padding:0px 7px;margin: 5px 0px;}
            .butiksasha-transaction-label .span3 {overflow:hidden;}
            .butiksasha-transaction-label .span3 p {font-size:11px;line-height:15px;padding:0px 7px;margin:0px 0px 5px 0px; white-space: nowrap;}
            .sosmed,.sosmed .sosmed-item,.sosmed .sosmed-item .sosmed-img{position:relative;top:0;left:0}.sosmed{width:100%;font-size:8px;line-height:10px;margin-top:3px}.sosmed .sosmed-item{float:left}.sosmed .sosmed-item .sosmed-img{float:left;width:10px;height:10px}.sosmed .sosmed-item .sosmed-text{position:relative;float:left;top:0;left:0;padding-left:5px;margin-right:5px;margin-bottom:3px}.sosmed .sosmed-item.item-1{width:32%;}.sosmed .sosmed-item.item-2{width:27.5%;}.sosmed .sosmed-item.item-3{width:20.5%;}.sosmed .sosmed-item.item-4{width:20%;}
            .table-sosmed,.table-sosmed .sosmed-item,.table-sosmed .sosmed-item .sosmed-img{position:relative;top:0;left:0}.table-sosmed{width:100%;font-size:8px;line-height:10px;margin-top:3px;border:0;}.table-sosmed .sosmed-item{padding: 0px; border:0;}.table-sosmed .sosmed-item .sosmed-img{float:left;width:10px;height:10px}.table-sosmed .sosmed-item .sosmed-text{position:relative;float:left;top:0;left:0;padding-left:5px;margin-right:5px;margin-bottom:3px}


            .butiksasha-transaction-slip-seller--name.small{font-size:10px;}
            .butiksasha-transaction-slip-buyer--name {padding-right:105px;}
            .butiksasha-transaction-slip-buyer-left {  }
            .butiksasha-transaction-slip-buyer-right { position:absolute; top: 0px; right: 0px; padding:5px; left: auto; width: 105px; font-size: 9px; line-height: 14px; border-left:1px solid #000; border-bottom:1px solid #000; }
            .butiksasha-transaction-slip-buyer-noted { position: absolute; bottom:5px; left:10px; width:100%; font-size:9px; line-height:13px; }
            .butiksasha-transaction-slip-barcode-title { font-size: 9px; line-height: 13px; }
            .butiksasha-transaction-slip-barcode-image { height: 27px; }

            .butiksasha-transaction-slip-barcode { border-bottom: none; }

            #page { width: 250px; }

    </style>
    <script>
        <?php if(count($data)>0){ ?>
        print();
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

            $shipping_resi    = $r->orders_shipping_resi;
            $shipping_name    = $r->orders_shipping_name;
            ?>

            <div class='butiksasha-transaction-slip-barcode'>
                <div class="butiksasha-transaction-slip-barcode-title">Nama Penerima: <?php echo $shipping_name ?></div>
                <?php if($shipping_resi != ""){ ?>
                <div class="butiksasha-transaction-slip-barcode-image">
                    <img src="<?php echo base_url() ?>application/libraries/barcodegen.1d/barcode_print_address.php?text=<?php echo $shipping_resi ?>" alt="" />
                </div>
                <div class="butiksasha-transaction-slip-barcode-title">Kode Booking: <?php echo $shipping_resi ?></div>
                <?php } ?>
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