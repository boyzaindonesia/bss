<?php
class mdl_marketplace_excel extends CI_Model{

    var $tabel = '';

    function __construct(){
        parent::__construct();

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        $this->detail_store     = get_detail_store($this->store_id);
        $this->store_name       = $this->detail_store->store_name;
        $this->store_phone      = $this->detail_store->store_phone;
        $this->store_product    = $this->detail_store->store_product;
//
    }

    function data_tokopedia_new_orders($p=array(), $orders_source_id="3"){
        $err   = false;
        $msg   = "";
        $items = array();
        if(count($p) == 0){
            $err   = true;
            $msg   = "Error: Data excel kosong..";
        } else {
            if(trim($p[0]->col_0) != trim("Nama Toko:")){
                $err   = true;
                $msg   = "Error: Bukan upload file excel TOKOPEDIA";
            }
            $titleCol = array();
            $titleCol[0] = "Count";
            $titleCol[1] = "Order ID";
            $titleCol[2] = "Invoice";
            $titleCol[3] = "Payment Date";
            $titleCol[4] = "Order Status";
            $titleCol[5] = "Product ID";
            $titleCol[6] = "Product Name";
            $titleCol[7] = "Quantity";
            $titleCol[8] = "Stock Keeping Unit (SKU)";
            $titleCol[9] = "Notes";
            $titleCol[10] = "Price (Rp.)";
            $titleCol[11] = "Discount Amount (Rp.)";
            $titleCol[12] = "Subsidi Amount (Rp.)";
            $titleCol[13] = "Customer Name";
            $titleCol[14] = "Customer Phone";
            $titleCol[15] = "Recipient";
            $titleCol[16] = "Recipient Number";
            $titleCol[17] = "Recipient Address";
            $titleCol[18] = "Courier";
            $titleCol[19] = "Shipping Price + fee (Rp.)";
            $titleCol[20] = "Insurance (Rp.)";
            $titleCol[21] = "Total Shipping Fee (Rp.)";
            $titleCol[22] = "Total Amount (Rp.)";
            $titleCol[23] = "AWB";
            $titleCol[24] = "Jenis Layanan";
            $titleCol[25] = "Bebas Ongkir";
            $titleCol[26] = "Warehouse Origin";
            $titleCol[27] = "Campaign Name";

            $err_check = false;
            $msg_check = "";
            $titleRow  = 3;
            foreach ($titleCol as $key => $val) {
                $colName = "col_".$key;
                if(trim($val) != trim($p[$titleRow]->$colName)){
                    $err_check  = true;
                    $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                }
            }
            if($err_check){
                $err  = true;
                $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
            }

            if(!$err){
                $i       = 0;
                $iRow    = 1;
                $fromRow = 5;
                foreach ($p as $key => $val) {
                    if($iRow >= $fromRow){
                        $exp_invoice         = explode('/', $val->col_2);
                        $mp_invoice          = $exp_invoice[3];
                        $mp_sku              = trim($val->col_8);
                        $mp_dropship         = 0;
                        $mp_ship_name        = NULL;
                        $mp_ship_phone       = NULL;
                        $mp_shipping_user    = trim($val->col_13);
                        $mp_shipping_name    = trim($val->col_15);
                        $mp_shipping_phone   = trim($val->col_16);
                        $mp_shipping_address = trim($val->col_17);
                        $split_address           = get_split_address(3, $mp_shipping_address);
                        $mp_shipping_city        = trim(strtoupper($split_address['city']));
                        $mp_shipping_province    = trim(strtoupper($split_address['province']));
                        $mp_shipping_postal_code = trim($split_address['postal_code']);
                        // $mp_shipping_city        = NULL;
                        // $mp_shipping_province    = NULL;
                        // $mp_shipping_postal_code = NULL;
                        $mp_courier          = $val->col_18;
                        $mp_price_insurance  = 0;
                        $mp_grand_total      = (convertRpToInt2($val->col_22) - convertRpToInt2($val->col_20));
                        $mp_price_shipping   = convertRpToInt2($val->col_19);
                        $mp_price_product    = ($mp_grand_total - $mp_price_shipping);
                        $mp_resi             = trim($val->col_23);
                        $mp_status           = trim($val->col_4);
                        $orders_noted        = NULL;
                        switch ($mp_courier) {
                            case 'JNE(Reguler)': $orders_courier_id = 3; break;
                            case 'JNE(OKE)': $orders_courier_id = 2; break;
                            case 'JNE(YES)': $orders_courier_id = 4; break;
                            case 'Wahana(Service Normal)': $orders_courier_id = 6; break;
                            case 'TIKI(Reguler)': $orders_courier_id = 17; break;
                            case 'TIKI(Over Night Service)': $orders_courier_id = 18; break;
                            case 'J&T(Reguler)': $orders_courier_id = 8; break;
                            case 'Pos Indonesia(Pos Kilat Khusus)': $orders_courier_id = 20; break;
                            case 'GO-JEK(Same Day)': $orders_courier_id = 10; break;
                            case 'Go-Send(Instant Courier)': $orders_courier_id = 11; break;
                            case 'GrabExpress(Same Day)': $orders_courier_id = 13; break;
                            case 'GrabExpress(Next Day)': $orders_courier_id = 14; break;
                            case 'GrabExpress(Instant)': $orders_courier_id = 15; break;
                            case 'Ninja Xpress(Reguler)': $orders_courier_id = 27; break;
                            case 'SiCepat(Regular Package)': $orders_courier_id = 23; break;
                            case 'SiCepat(BEST)': $orders_courier_id = 24; break;
                            case 'SiCepat(HALU)': $orders_courier_id = 35; break;
                            case 'AnterAja(Reguler)': $orders_courier_id = 32; break;
                            case 'AnterAja(Next Day)': $orders_courier_id = 34; break;
                            default: $orders_courier_id = 0; break;
                        }
// debugCode($mp_invoice);
                        if(($mp_invoice != '' && $mp_shipping_name != '') && $mp_status != 'Transaksi ditolak.' && $mp_status != 'Pesanan dibatalkan pembeli.'){
                            $orders_status    = 3;
                            $orders_print     = 0;
                            if($mp_resi != ""){ $orders_status = 5; $orders_print = 1; }
                            $items[$i]->orders_status         = $orders_status;
                            $items[$i]->orders_source_id      = $orders_source_id;
                            $items[$i]->orders_source_invoice = $mp_invoice;
                            $items[$i]->orders_resi           = $mp_resi;
                            $items[$i]->orders_dropship       = $mp_dropship;
                            $items[$i]->orders_ship_name      = $mp_ship_name;
                            $items[$i]->orders_ship_phone     = $mp_ship_phone;
                            $items[$i]->orders_username       = $mp_shipping_user;
                            $items[$i]->orders_shipping_name  = $mp_shipping_name;
                            $items[$i]->orders_shipping_address  = $mp_shipping_address;
                            $items[$i]->orders_shipping_city     = $mp_shipping_city;
                            $items[$i]->orders_shipping_province = $mp_shipping_province;
                            $items[$i]->orders_shipping_postal_code = $mp_shipping_postal_code;
                            $items[$i]->orders_shipping_phone = $mp_shipping_phone;
                            $items[$i]->orders_shipping_email = NULL;
                            $items[$i]->orders_courier_id     = $orders_courier_id;
                            $items[$i]->orders_product_category_title = $this->store_product;
                            $items[$i]->orders_price_product  = $mp_price_product;
                            $items[$i]->orders_price_shipping = $mp_price_shipping;
                            $items[$i]->orders_price_insurance   = $mp_price_insurance;
                            $items[$i]->orders_voucher_price     = 0;
                            $items[$i]->orders_price_grand_total = $mp_grand_total;
                            $items[$i]->orders_product_sku    = $mp_sku;
                            $items[$i]->orders_print          = $orders_print;
                            $items[$i]->orders_noted          = $orders_noted;
                            $i += 1;
                        }
                    }
                    $iRow += 1;
                }

                $err   = false;
                $msg   = "";
            }
        }

        return array(
                "err"   => $err,
                "msg"   => $msg,
                "items" => $items
            );
    }

    function data_bukalapak_new_orders($p=array(), $orders_source_id="2"){
        $err   = false;
        $msg   = "";
        $items = array();
        if(count($p) == 0){
            $err   = true;
            $msg   = "Error: Data excel kosong..";
        } else {
            if(trim($p[0]->col_0) != trim("Tanggal")){
                $err   = true;
                $msg   = "Error: Bukan upload file excel BUKALAPAK";
            }
            $titleCol = array();
            $titleCol[0] = "Tanggal";
            $titleCol[1] = "ID Transaksi";
            $titleCol[2] = "Transaksi Dropshipper";
            $titleCol[3] = "Nama Dropshipper";
            $titleCol[4] = "Detail Dropshipper";
            $titleCol[5] = "Penjual";
            $titleCol[6] = "Pembeli";
            $titleCol[7] = "Username Pembeli";
            $titleCol[8] = "HP Pembeli";
            $titleCol[9] = "Alamat Pembeli";
            $titleCol[10] = "Kecamatan Pembeli";
            $titleCol[11] = "Kota Pembeli";
            $titleCol[12] = "Propinsi Pembeli";
            $titleCol[13] = "Kode Pos Pembeli";
            $titleCol[14] = "Nama Produk";
            $titleCol[15] = "Harga Produk";
            $titleCol[16] = "Biaya Pengiriman";
            $titleCol[17] = "Biaya Asuransi";
            $titleCol[18] = "Total Terbayar";
            $titleCol[19] = "Jumlah Produk";
            $titleCol[20] = "SKU";
            $titleCol[21] = "Varian";
            $titleCol[22] = "Kurir";
            $titleCol[23] = "Kode Tracking";
            $titleCol[24] = "Status";

            $err_check = false;
            $msg_check = "";
            $titleRow  = 0;
            foreach ($titleCol as $key => $val) {
                $colName = "col_".$key;
                if(trim($val) != trim($p[$titleRow]->$colName)){
                    $err_check  = true;
                    $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                }
            }
            if($err_check){
                $err  = true;
                $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
            }

            if(!$err){
                $i       = 0;
                $iRow    = 1;
                $fromRow = 2;
                $tmpInvoice = array();
                foreach ($p as $key => $val) {
                    if($iRow >= $fromRow){
                        $mp_invoice          = trim($val->col_1);
                        $mp_sku              = NULL;
                        $mp_dropship         = ($val->col_2=='Tidak'?0:1);
                        $mp_ship_name        = ($val->col_2=='Tidak'?NULL:$val->col_3);
                        $mp_ship_phone       = ($val->col_2=='Tidak'||$val->col_4==''?NULL:$val->col_4);
                        $mp_shipping_user    = trim($val->col_7);
                        $mp_shipping_name    = trim($val->col_6);
                        $mp_shipping_phone   = trim($val->col_8);
                        $mp_shipping_address = $val->col_9.', '.$val->col_10.', '.$val->col_11.', '.$val->col_12.' '.$val->col_13;
                        $mp_shipping_city        = trim(strtoupper($val->col_11));
                        $mp_shipping_province    = trim(strtoupper($val->col_12));
                        $mp_shipping_postal_code = trim($val->col_13);
                        $mp_courier          = $val->col_22;
                        $mp_grand_total      = convertRpToInt2($val->col_18);
                        $mp_price_shipping   = convertRpToInt2($val->col_16);
                        $mp_price_insurance  = convertRpToInt2($val->col_17);
                        $mp_price_product    = ($mp_grand_total - $mp_price_shipping - $mp_price_insurance);
                        $mp_resi             = trim($val->col_23);
                        $mp_status           = trim($val->col_24);
                        $orders_noted        = NULL;
                        switch ($mp_courier) {
                            case 'JNE REG': $orders_courier_id = 3; break;
                            case 'JNE OKE': $orders_courier_id = 2; break;
                            case 'JNE YES': $orders_courier_id = 4; break;
                            case 'Wahana Tarif Normal': $orders_courier_id = 6; break;
                            case 'TIKI Reg': $orders_courier_id = 17; break;
                            case 'J&T REG': $orders_courier_id = 8; break;
                            case 'GO-SEND Same Day': $orders_courier_id = 10; break;
                            case 'Grab Instant': $orders_courier_id = 15; break;
                            case 'NINJA REG': $orders_courier_id = 27; break;
                            case 'SiCepat(Regular Package)': $orders_courier_id = 23; break;
                            case 'SiCepat BEST': $orders_courier_id = 24; break;
                            default: $orders_courier_id = 0; break;
                        }
                        if($mp_shipping_name != '' && $mp_status != 'Ditolak'){
                            if(!in_array($mp_invoice, $tmpInvoice)){
                                $tmpInvoice[] = $mp_invoice;
                                $orders_status    = 3;
                                $orders_print     = 0;
                                $items[$i]->orders_status         = $orders_status;
                                $items[$i]->orders_source_id      = $orders_source_id;
                                $items[$i]->orders_source_invoice = $mp_invoice;
                                $items[$i]->orders_resi           = $mp_resi;
                                $items[$i]->orders_dropship       = $mp_dropship;
                                $items[$i]->orders_ship_name      = $mp_ship_name;
                                $items[$i]->orders_ship_phone     = $mp_ship_phone;
                                $items[$i]->orders_username       = $mp_shipping_user;
                                $items[$i]->orders_shipping_name  = $mp_shipping_name;
                                $items[$i]->orders_shipping_address  = $mp_shipping_address;
                                $items[$i]->orders_shipping_city     = $mp_shipping_city;
                                $items[$i]->orders_shipping_province = $mp_shipping_province;
                                $items[$i]->orders_shipping_postal_code = $mp_shipping_postal_code;
                                $items[$i]->orders_shipping_phone = $mp_shipping_phone;
                                $items[$i]->orders_shipping_email = NULL;
                                $items[$i]->orders_courier_id     = $orders_courier_id;
                                $items[$i]->orders_product_category_title = $this->store_product;
                                $items[$i]->orders_price_product  = $mp_price_product;
                                $items[$i]->orders_price_shipping = $mp_price_shipping;
                                $items[$i]->orders_price_insurance   = $mp_price_insurance;
                                $items[$i]->orders_voucher_price     = 0;
                                $items[$i]->orders_price_grand_total = $mp_grand_total;
                                $items[$i]->orders_product_sku    = $mp_sku;
                                $items[$i]->orders_print          = $orders_print;
                                $items[$i]->orders_noted          = $orders_noted;
                                $i += 1;
                            }
                        }
                    }
                    $iRow += 1;
                }

                $err   = false;
                $msg   = "";
            }
        }

        return array(
                "err"   => $err,
                "msg"   => $msg,
                "items" => $items
            );
    }

    function data_shopee_new_orders($p=array(), $orders_source_id="8"){
        $err   = false;
        $msg   = "";
        $items = array();
        if(count($p) == 0){
            $err   = true;
            $msg   = "Error: Data excel kosong..";
        } else {
            if(trim($p[0]->col_0) == trim("No. Resi")){
                $titleCol = array();
                $titleCol[0] = "No. Resi";
                $titleCol[1] = "No. Pesanan";
                $titleCol[2] = "Waktu Pesanan Dibuat";
                $titleCol[3] = "Status Pesanan";
                $titleCol[4] = "Status Pembatalan/ Pengembalian";
                $titleCol[5] = "Username (Pembeli)";
                $titleCol[6] = "Waktu Pesanan Dibuat";
                $titleCol[7] = "Pesanan Harus Dikirimkan Sebelum (Menghindari keterlambatan)";
                $titleCol[8] = "Informasi Produk";
                $titleCol[9] = "Opsi Pengiriman";
                $titleCol[10] = "Nama Penerima";
                $titleCol[11] = "No. Telepon";
                $titleCol[12] = "Alamat Pengiriman";
                $titleCol[13] = "Kota/Kabupaten";
                $titleCol[14] = "Provinsi";
                $titleCol[15] = "Kode Pos";

                $err_check = false;
                $msg_check = "";
                $titleRow  = 0;
                foreach ($titleCol as $key => $val) {
                    $colName = "col_".$key;
                    if(trim($val) != trim($p[$titleRow]->$colName)){
                        $err_check  = true;
                        $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                    }
                }
                if($err_check){
                    $err  = true;
                    $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
                } else {
                    $err  = false;
                }

                if(!$err){
                    $i       = 0;
                    $iRow    = 1;
                    $fromRow = 2;
                    foreach ($p as $key => $val) {
                        if($iRow >= $fromRow){
                            $mp_invoice          = trim($val->col_1);
                            $mp_sku              = NULL;
                            $mp_dropship         = 0;
                            $mp_ship_name        = NULL;
                            $mp_ship_phone       = NULL;
                            $mp_shipping_user    = trim($val->col_5);
                            $mp_shipping_name    = trim($val->col_10);
                            $mp_shipping_phone   = trim($val->col_11);
                            $mp_shipping_address = $val->col_12;
                            $mp_shipping_city        = trim(strtoupper($val->col_13));
                            $mp_shipping_province    = trim(strtoupper($val->col_14));
                            $mp_shipping_postal_code = trim($val->col_15);
                            $mp_courier          = $val->col_9;
                            $mp_grand_total      = convertRpToInt2(0);
                            $mp_price_shipping   = convertRpToInt2(0);
                            $mp_price_insurance  = convertRpToInt2(0);
                            $mp_price_product    = ($mp_grand_total - $mp_price_shipping - $mp_price_insurance);
                            $mp_resi             = trim($val->col_0);
                            $mp_status           = trim($val->col_3);
                            $orders_noted        = NULL;
                            switch ($mp_courier) {
                                case 'JNE Reguler': $orders_courier_id = 28; break;
                                case 'JNE Reguler (Cashless)': $orders_courier_id = 28; break;
                                case 'JNE YES (Cashless)': $orders_courier_id = 33; break;
                                case 'JNE JTR (Cashless)': $orders_courier_id = 38; break;
                                case 'JNE REG': $orders_courier_id = 3; break;
                                case 'JNE OKE': $orders_courier_id = 2; break;
                                case 'J&T Express': $orders_courier_id = 8; break;
                                case 'SiCepat REG': $orders_courier_id = 23; break;
                                case 'SiCepat Halu': $orders_courier_id = 35; break;
                                case 'SiCepat Cargo': $orders_courier_id = 25; break;
                                case 'Ninja Xpress': $orders_courier_id = 27; break;
                                case 'GoSend Same Day': $orders_courier_id = 10; break;
                                case 'GO-SEND INSTANT': $orders_courier_id = 11; break;
                                case 'GrabExpress Sameday': $orders_courier_id = 13; break;
                                case 'GrabExpress Instant': $orders_courier_id = 15; break;
                                case 'ID Express': $orders_courier_id = 37; break;
                                case 'Shopee Express Standard': $orders_courier_id = 39; break;
                                default: $orders_courier_id = 0; break;
                            }

                            if($mp_shipping_name != ''){
                                $orders_status    = 3;
                                $orders_print     = 0;
                                $items[$i]->orders_status         = $orders_status;
                                $items[$i]->orders_source_id      = $orders_source_id;
                                $items[$i]->orders_source_invoice = $mp_invoice;
                                $items[$i]->orders_resi           = $mp_resi;
                                $items[$i]->orders_dropship       = $mp_dropship;
                                $items[$i]->orders_ship_name      = $mp_ship_name;
                                $items[$i]->orders_ship_phone     = $mp_ship_phone;
                                $items[$i]->orders_username       = $mp_shipping_user;
                                $items[$i]->orders_shipping_name  = $mp_shipping_name;
                                $items[$i]->orders_shipping_address  = $mp_shipping_address;
                                $items[$i]->orders_shipping_city     = $mp_shipping_city;
                                $items[$i]->orders_shipping_province = $mp_shipping_province;
                                $items[$i]->orders_shipping_postal_code = $mp_shipping_postal_code;
                                $items[$i]->orders_shipping_phone = $mp_shipping_phone;
                                $items[$i]->orders_shipping_email = NULL;
                                $items[$i]->orders_courier_id     = $orders_courier_id;
                                $items[$i]->orders_product_category_title = $this->store_product;
                                $items[$i]->orders_price_product  = $mp_price_product;
                                $items[$i]->orders_price_shipping = $mp_price_shipping;
                                $items[$i]->orders_price_insurance   = $mp_price_insurance;
                                $items[$i]->orders_voucher_price     = 0;
                                $items[$i]->orders_price_grand_total = $mp_grand_total;
                                $items[$i]->orders_product_sku    = $mp_sku;
                                $items[$i]->orders_print          = $orders_print;
                                $items[$i]->orders_noted          = $orders_noted;
                                $i += 1;
                            }
                        }
                        $iRow += 1;
                    }

                    $err   = false;
                    $msg   = "";
                }
            } else if(trim($p[0]->col_0) == trim("No. Pesanan")){
                $titleCol = array();
                $titleCol[0] = "No. Pesanan";
                $titleCol[1] = "Status Pesanan";
                $titleCol[2] = "Status Pembatalan/ Pengembalian";
                $titleCol[3] = "No. Resi";
                $titleCol[4] = "Opsi Pengiriman";
                $titleCol[5] = "Antar ke counter/ pick-up";
                $titleCol[6] = "Pesanan Harus Dikirimkan Sebelum (Menghindari keterlambatan)";
                $titleCol[7] = "Waktu Pengiriman Diatur";
                $titleCol[8] = "Waktu Pesanan Dibuat";
                $titleCol[9] = "Waktu Pembayaran Dilakukan";
                $titleCol[10] = "SKU Induk";
                $titleCol[11] = "Nama Produk";
                $titleCol[12] = "Nomor Referensi SKU";
                $titleCol[13] = "Nama Variasi";
                $titleCol[14] = "Harga Awal";
                $titleCol[15] = "Harga Setelah Diskon";
                $titleCol[16] = "Jumlah";
                $titleCol[17] = "Total Harga Produk";
                $titleCol[18] = "Total Diskon";
                $titleCol[19] = "Diskon Dari Penjual";
                $titleCol[20] = "Diskon Dari Shopee";
                $titleCol[21] = "Berat Produk";
                $titleCol[22] = "Jumlah Produk di Pesan";
                $titleCol[23] = "Total Berat";
                $titleCol[24] = "Voucher Ditanggung Penjual";
                $titleCol[25] = "Cashback Koin";
                $titleCol[26] = "Voucher Ditanggung Shopee";
                $titleCol[27] = "Paket Diskon";
                $titleCol[28] = "Paket Diskon (Diskon dari Shopee)";
                $titleCol[29] = "Paket Diskon (Diskon dari Penjual)";
                $titleCol[30] = "Potongan Koin Shopee";
                $titleCol[31] = "Diskon Kartu Kredit";
                $titleCol[32] = "Ongkos Kirim Dibayar oleh Pembeli";
                // $titleCol[33] = "Biaya Layanan";
                // $titleCol[33] = "Biaya Layanan (termasuk PPN 10% & PPh 23)";
                $titleCol[33] = "Total Pembayaran";
                $titleCol[34] = "Perkiraan Ongkos Kirim";
                $titleCol[35] = "Catatan dari Pembeli";
                $titleCol[36] = "Catatan";
                $titleCol[37] = "Username (Pembeli)";
                $titleCol[38] = "Nama Penerima";
                $titleCol[39] = "No. Telepon";
                $titleCol[40] = "Alamat Pengiriman";
                $titleCol[41] = "Kota/Kabupaten";
                $titleCol[42] = "Provinsi";
                $titleCol[43] = "Waktu Pesanan Selesai";

                $err_check = false;
                $msg_check = "";
                $titleRow  = 0;
                foreach ($titleCol as $key => $val) {
                    $colName = "col_".$key;
                    if(trim($val) != trim($p[$titleRow]->$colName)){
                        if($key == 33){
                            if("Biaya Layanan (termasuk PPN 10% & PPh 23)" != trim($p[$titleRow]->$colName)){
                                $err_check  = true;
                                $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                            }
                        } else {
                            $err_check  = true;
                            $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                        }
                    }
                }
                if($err_check){
                    $err  = true;
                    $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
                } else {
                    $err  = false;
                }

                if(!$err){
                    $i       = 0;
                    $iRow    = 1;
                    $fromRow = 2;
                    $found   = false;
                    $insert  = true;
                    $tmpInvoice = array();
                    $orders_source_invoice = "";
                    foreach ($p as $key => $val) {
                        if($iRow >= $fromRow){
                            $mp_invoice          = trim($val->col_0);
                            $mp_sku              = trim($val->col_10);
                            $mp_dropship         = 0;
                            $mp_ship_name        = NULL;
                            $mp_ship_phone       = NULL;
                            $mp_shipping_user    = trim($val->col_37);
                            $mp_shipping_name    = trim($val->col_38);
                            $mp_shipping_phone   = trim($val->col_39);
                            $mp_shipping_address = $val->col_40;
                            $mp_shipping_city        = trim(strtoupper($val->col_41));
                            $mp_shipping_province    = trim(strtoupper($val->col_42));
                            $get_split_address       = get_split_address($orders_source_id, $mp_shipping_address);
                            $mp_shipping_postal_code = $get_split_address['postal_code'];
                            $mp_courier          = $val->col_4;
                            $mp_grand_total      = (convertRpToInt2($val->col_17) + convertRpToInt2($val->col_34)) - convertRpToInt2($val->col_24);
                            $mp_price_shipping   = convertRpToInt2($val->col_34);
                            $mp_voucher_price    = convertRpToInt2($val->col_24);
                            $mp_price_insurance  = convertRpToInt2(0);
                            $mp_price_product    = convertRpToInt2($val->col_17);
                            $mp_resi             = trim($val->col_3);
                            $mp_status           = trim($val->col_1);
                            $orders_noted        = NULL;
                            switch (str_replace(array("\n", "\r"), ' ', $mp_courier)) {
                                case 'JNE Reguler': $orders_courier_id = 28; break;
                                case 'JNE Reguler (Cashless)': $orders_courier_id = 28; break;
                                case 'JNE YES (Cashless)': $orders_courier_id = 33; break;
                                case 'JNE JTR (Cashless)': $orders_courier_id = 38; break;
                                case 'JNE REG': $orders_courier_id = 3; break;
                                case 'JNE OKE': $orders_courier_id = 2; break;
                                case 'J&T Express': $orders_courier_id = 8; break;
                                case 'J&T Economy': $orders_courier_id = 41; break;
                                case 'SiCepat REG': $orders_courier_id = 23; break;
                                case 'SiCepat Halu': $orders_courier_id = 35; break;
                                case 'SiCepat Cargo': $orders_courier_id = 25; break;
                                case 'Ninja Xpress': $orders_courier_id = 27; break;
                                case 'GoSend Same Day': $orders_courier_id = 10; break;
                                case 'GoSend Instant': $orders_courier_id = 11; break;
                                case 'GrabExpress Sameday': $orders_courier_id = 13; break;
                                case 'GrabExpress Instant': $orders_courier_id = 15; break;
                                case 'Reguler ID Express': $orders_courier_id = 37; break;
                                case 'Shopee Express Standard': $orders_courier_id = 39; break;
                                case 'Anteraja': $orders_courier_id = 32; break;
                                default: $orders_courier_id = 0; break;
                            }

                            // debugCode(str_replace(array("\n", "\r"), ' ', $mp_courier).' '.$orders_courier_id);

                            if($mp_invoice != "" && ($mp_status == "Perlu Dikirim" || $mp_status == "Sedang Dikirim" || $mp_status == "Selesai")){
                                if(!in_array($mp_invoice, $tmpInvoice)){
                                    $orders_status    = 3;
                                    $orders_print     = 0;
                                    if($mp_resi != "" && ($orders_courier_id == 10 || $orders_courier_id == 11 || $orders_courier_id == 13 || $orders_courier_id == 15)){
                                        $orders_status     = 5;
                                        $orders_print      = 1;
                                    }
                                    if($orders_courier_id == 39){
                                        $orders_print      = 1;
                                    }
                                    $tmpInvoice[] = $mp_invoice;
                                    $items[$i]->orders_status         = $orders_status;
                                    $items[$i]->orders_source_id      = $orders_source_id;
                                    $items[$i]->orders_source_invoice = $mp_invoice;
                                    $items[$i]->orders_resi           = $mp_resi;
                                    $items[$i]->orders_dropship       = $mp_dropship;
                                    $items[$i]->orders_ship_name      = $mp_ship_name;
                                    $items[$i]->orders_ship_phone     = $mp_ship_phone;
                                    $items[$i]->orders_username       = $mp_shipping_user;
                                    $items[$i]->orders_shipping_name  = $mp_shipping_name;
                                    $items[$i]->orders_shipping_address  = $mp_shipping_address;
                                    $items[$i]->orders_shipping_city     = $mp_shipping_city;
                                    $items[$i]->orders_shipping_province = $mp_shipping_province;
                                    $items[$i]->orders_shipping_postal_code = $mp_shipping_postal_code;
                                    $items[$i]->orders_shipping_phone = $mp_shipping_phone;
                                    $items[$i]->orders_shipping_email = NULL;
                                    $items[$i]->orders_courier_id     = $orders_courier_id;
                                    $items[$i]->orders_product_category_title = $this->store_product;
                                    $items[$i]->orders_price_product  = $mp_price_product;
                                    $items[$i]->orders_price_shipping = $mp_price_shipping;
                                    $items[$i]->orders_price_insurance   = $mp_price_insurance;
                                    $items[$i]->orders_voucher_price     = 0;
                                    $items[$i]->orders_price_grand_total = $mp_grand_total;
                                    $items[$i]->orders_product_sku    = $mp_sku;
                                    $items[$i]->orders_print          = $orders_print;
                                    $items[$i]->orders_noted          = $orders_noted;
                                    $i += 1;
                                } else {
                                    foreach ($items as $k2 => $v2) {
                                        if($v2->orders_source_invoice == $mp_invoice){
                                            $items[$k2]->orders_price_product     += $mp_price_product;
                                            $items[$k2]->orders_price_grand_total += $mp_price_product;
                                        }
                                    }
                                }
                            }

                            // $mp_product_name = trim($val->col_11);
                            // if($mp_product_name != ""){
                            //     if($mp_invoice != "" && ($mp_status == "Perlu Dikirim" || $mp_status == "Sedang Dikirim" || $mp_status == "Selesai")){
                            //         $found  = true;
                            //         $insert = true;
                            //         $orders_source_invoice = $mp_invoice;
                            //     } else if ($mp_invoice == "" && $mp_status == "" && $found == true ) {
                            //         $found  = true;
                            //         $insert = false;
                            //     } else {
                            //         $found  = false;
                            //         $insert = true;
                            //         $orders_source_invoice = "";
                            //     }
                            // }

                            // if($found == true){
                            //     if($insert == true){

                            //     } else if($insert == false){

                            //     }
                            // }
                        }
                        $iRow += 1;
                    }

                    $err   = false;
                    $msg   = "";
                }
            } else {
                $err   = true;
                $msg   = "Error: Bukan upload file excel SHOPEE";
            }

        }

        return array(
                "err"   => $err,
                "msg"   => $msg,
                "items" => $items
            );
    }

    function data_lazada_new_orders($p=array(), $orders_source_id="11"){
        $err   = false;
        $msg   = "";
        $items = array();
        if(count($p) == 0){
            $err   = true;
            $msg   = "Error: Data excel kosong..";
        } else {
            // BELUM DI BETULKAN
            if(trim($p[0]->col_0) != trim("Order Item Id")){
                $err   = true;
                $msg   = "Error: Bukan upload file excel LAZADA";
            }
            $titleCol = array();
            $titleCol[0] = "Order Item Id";
            $titleCol[1] = "Order Type";
            $titleCol[2] = "Order Flag";
            $titleCol[3] = "Lazada Id";
            $titleCol[4] = "Seller SKU";
            $titleCol[5] = "Lazada SKU";
            $titleCol[6] = "Created at";
            $titleCol[7] = "Updated at";
            $titleCol[8] = "Order Number";
            $titleCol[9] = "Invoice Required";
            $titleCol[10] = "Customer Name";
            $titleCol[11] = "Customer Email";
            $titleCol[12] = "National Registration Number";
            $titleCol[13] = "Shipping Name";
            $titleCol[14] = "Shipping Address";
            $titleCol[15] = "Shipping Address2";
            $titleCol[16] = "Shipping Address3";
            $titleCol[17] = "Shipping Address4";
            $titleCol[18] = "Shipping Address5";
            $titleCol[19] = "Shipping Phone Number";
            $titleCol[20] = "Shipping Phone Number2";
            $titleCol[21] = "Shipping City";
            $titleCol[22] = "Shipping Postcode";
            $titleCol[23] = "Shipping Country";
            $titleCol[24] = "Shipping Region";
            $titleCol[25] = "Billing Name";
            $titleCol[26] = "Billing Address";
            $titleCol[27] = "Billing Address2";
            $titleCol[28] = "Billing Address3";
            $titleCol[29] = "Billing Address4";
            $titleCol[30] = "Billing Address5";
            $titleCol[31] = "Billing Phone Number";
            $titleCol[32] = "Billing Phone Number2";
            $titleCol[33] = "Billing City";
            $titleCol[34] = "Billing Postcode";
            $titleCol[35] = "Billing Country";
            $titleCol[36] = "Payment Method";
            $titleCol[37] = "Paid Price";
            $titleCol[38] = "Unit Price";
            $titleCol[39] = "Shipping Fee";
            $titleCol[40] = "Wallet Credits";
            $titleCol[41] = "Item Name";
            $titleCol[42] = "Variation";
            $titleCol[43] = "CD Shipping Provider";
            $titleCol[44] = "Shipping Provider";
            $titleCol[45] = "Shipment Type Name";
            $titleCol[46] = "Shipping Provider Type";
            $titleCol[47] = "CD Tracking Code";
            $titleCol[48] = "Tracking Code";
            $titleCol[49] = "Tracking URL";
            $titleCol[50] = "Shipping Provider (first mile)";
            $titleCol[51] = "Tracking Code (first mile)";
            $titleCol[52] = "Tracking URL (first mile)";
            $titleCol[53] = "Promised shipping time";
            $titleCol[54] = "Premium";
            $titleCol[55] = "Status";
            $titleCol[56] = "Cancel / Return Initiator";
            $titleCol[57] = "Reason";
            $titleCol[58] = "Reason Detail";
            $titleCol[59] = "Editor";
            $titleCol[60] = "Bundle ID";
            $titleCol[61] = "Bundle Discount";
            $titleCol[62] = "Refund Amount";

            $err_check = false;
            $msg_check = "";
            $titleRow  = 0;
            foreach ($titleCol as $key => $val) {
                $colName = "col_".$key;
                if(trim($val) != trim($p[$titleRow]->$colName)){
                    $err_check  = true;
                    $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                }
            }
            if($err_check){
                $err  = true;
                $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
            }

            if(!$err){
                $i       = 0;
                $iRow    = 1;
                $fromRow = 2;
                $tmpInvoice = array();
                foreach ($p as $key => $val) {
                    if($iRow >= $fromRow){
                        $mp_invoice          = trim($val->col_8);
                        $mp_sku              = NULL;
                        $mp_dropship         = 0;
                        $mp_ship_name        = NULL;
                        $mp_ship_phone       = NULL;
                        $mp_shipping_user    = trim($val->col_10);
                        $mp_shipping_name    = trim($val->col_13);
                        $mp_shipping_phone   = trim($val->col_19);
                        $mp_shipping_address = $val->col_14.' '.$val->col_15.' '.$val->col_18.', '.$val->col_17.' '.$val->col_16;
                        $mp_shipping_city        = trim(strtoupper($val->col_21));
                        $mp_shipping_province    = NULL;
                        $mp_shipping_postal_code = trim($val->col_22);
                        $mp_courier          = $val->col_44;
                        $mp_price_shipping   = 0;
                        $mp_price_insurance  = 0;
                        $mp_price_product    = trim($val->col_38);
                        $mp_grand_total      = $mp_price_product;
                        $mp_resi             = trim($val->col_48);
                        $mp_status           = trim($val->col_55);
                        $orders_noted        = NULL;
                        switch ($mp_courier) {
                            case 'JNE': $orders_courier_id = 28; break;
                            default: $orders_courier_id = 30; break;
                        }
                        if($mp_shipping_name != '' && $mp_status != 'canceled'){
                            if(!in_array($mp_invoice, $tmpInvoice)){
                                $tmpInvoice[] = $mp_invoice;
                                $orders_status    = 3;
                                $orders_print     = 1;
                                $items[$i]->orders_status         = $orders_status;
                                $items[$i]->orders_source_id      = $orders_source_id;
                                $items[$i]->orders_source_invoice = $mp_invoice;
                                $items[$i]->orders_resi           = $mp_resi;
                                $items[$i]->orders_dropship       = $mp_dropship;
                                $items[$i]->orders_ship_name      = $mp_ship_name;
                                $items[$i]->orders_ship_phone     = $mp_ship_phone;
                                $items[$i]->orders_username       = $mp_shipping_user;
                                $items[$i]->orders_shipping_name  = $mp_shipping_name;
                                $items[$i]->orders_shipping_address  = $mp_shipping_address;
                                $items[$i]->orders_shipping_city     = $mp_shipping_city;
                                $items[$i]->orders_shipping_province = $mp_shipping_province;
                                $items[$i]->orders_shipping_postal_code = $mp_shipping_postal_code;
                                $items[$i]->orders_shipping_phone = $mp_shipping_phone;
                                $items[$i]->orders_shipping_email = NULL;
                                $items[$i]->orders_courier_id     = $orders_courier_id;
                                $items[$i]->orders_product_category_title = $this->store_product;
                                $items[$i]->orders_price_product  = $mp_price_product;
                                $items[$i]->orders_price_shipping = $mp_price_shipping;
                                $items[$i]->orders_price_insurance   = $mp_price_insurance;
                                $items[$i]->orders_voucher_price     = 0;
                                $items[$i]->orders_price_grand_total = $mp_grand_total;
                                $items[$i]->orders_product_sku    = $mp_sku;
                                $items[$i]->orders_print          = $orders_print;
                                $items[$i]->orders_noted          = $orders_noted;
                                $i += 1;
                            } else {
                                foreach ($items as $k2 => $v2) {
                                    if($v2->orders_source_invoice == $mp_invoice){
                                        $items[$k2]->orders_price_product     += $mp_price_product;
                                        $items[$k2]->orders_price_grand_total += $mp_price_product;
                                    }
                                }
                            }
                        }
                    }
                    $iRow += 1;
                }

                $err   = false;
                $msg   = "";
            }
        }

        return array(
                "err"   => $err,
                "msg"   => $msg,
                "items" => $items
            );
    }

    function data_tokopedia_payment($p=array(), $orders_source_id="8"){
        $err   = false;
        $msg   = "";
        $items = array();
        $total_qty_product    = 0;
        $total_price_product  = 0;
        $total_price_discount = 0;
        $total_price_shipping = 0;
        $total_price_subsidi  = 0;
        $total_price_debet_ship = 0;
        $total_price_insurance  = 0;
        $total_price_return     = 0;
        $total_price_fee        = 0;
        $total_voucher_price    = 0;
        $total_price_grand_total = 0;
        if(count($p) == 0){
            $err   = true;
            $msg   = "Error: Data excel kosong..";
        } else {
            if(trim($p[0]->col_0) != trim("Date")){
                $err   = true;
                $msg   = "Error: Bukan upload file excel TOKOPEDIA.";
            }
            $titleCol = array();
            $titleCol[0] = "Date";
            $titleCol[1] = "Description";
            $titleCol[2] = "Nominal (Rp)";
            $titleCol[3] = "Balance (Rp)";

            $err_check = false;
            $msg_check = "";
            $titleRow  = 0;
            foreach ($titleCol as $key => $val) {
                $colName = "col_".$key;
                if(trim($val) != trim($p[$titleRow]->$colName)){
                    $err_check  = true;
                    $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                }
            }
            if($err_check){
                $err  = true;
                $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
            }

            if(!$err){
                $i       = 0;
                $iRow    = 1;
                $fromRow = 2;
                foreach ($p as $key => $val) {
                    if($iRow >= $fromRow){
                        $desc = explode('-', $val->col_1);
                        $title   = trim($desc[0]);
                        $invoice = trim($desc[1]);
                        if($title == "Transaksi Penjualan Berhasil"){
                            // $invoice = explode("/", $invoice);
                            $mp_invoice          = trim($invoice);
                            $mp_shipping_user    = NULL;
                            $mp_shipping_name    = NULL;
                            $mp_product_item     = array();
                            $mp_qty_product      = 0;
                            $mp_price_product    = convertRpToInt2($val->col_2);
                            $mp_price_discount   = 0;
                            $mp_price_shipping   = 0;
                            $mp_price_subsidi    = 0;
                            $mp_price_debet_ship = 0;
                            $mp_price_insurance  = 0;
                            $mp_price_return     = 0;
                            $mp_price_fee        = 0;
                            $mp_price_voucher    = 0;
                            $mp_grand_total      = convertRpToInt2($val->col_2);
                            $mp_data_payment     = convDatepickerDec($val->col_0);
                            $mp_claim_status     = FALSE;
                            $mp_claim_price      = 0;

                            $total_qty_product    += $mp_qty_product;
                            $total_price_product  += $mp_price_product;
                            $total_price_discount += $mp_price_discount;
                            $total_price_shipping += $mp_price_shipping;
                            $total_price_subsidi  += $mp_price_subsidi;
                            $total_price_debet_ship += $mp_price_debet_ship;
                            $total_price_insurance  += $mp_price_insurance;
                            $total_price_return     += $mp_price_return;
                            $total_price_fee        += $mp_price_fee;
                            $total_voucher_price    += $mp_price_voucher;
                            $total_price_grand_total += $mp_grand_total;

                            $items[$i]->mp_row            = $iRow;
                            $items[$i]->mp_source_id      = $orders_source_id;
                            $items[$i]->mp_source_invoice = $mp_invoice;
                            $items[$i]->mp_username       = $mp_shipping_user;
                            $items[$i]->mp_shipping_name  = $mp_shipping_name;
                            $items[$i]->mp_product_item   = $mp_product_item;
                            $items[$i]->mp_qty_product    = $mp_qty_product;
                            $items[$i]->mp_price_product  = $mp_price_product;
                            $items[$i]->mp_price_discount = $mp_price_discount;
                            $items[$i]->mp_price_shipping = $mp_price_shipping;
                            $items[$i]->mp_price_subsidi  = $mp_price_subsidi;
                            $items[$i]->mp_price_debet_ship  = $mp_price_debet_ship;
                            $items[$i]->mp_price_insurance   = $mp_price_insurance;
                            $items[$i]->mp_price_return      = $mp_price_return;
                            $items[$i]->mp_price_fee         = $mp_price_fee;
                            $items[$i]->mp_voucher_price     = $mp_price_voucher;
                            $items[$i]->mp_price_grand_total = $mp_grand_total;
                            $items[$i]->mp_date_payment   = $mp_data_payment;
                            $items[$i]->mp_claim_status   = $mp_claim_status;
                            $items[$i]->mp_claim_price    = $mp_claim_price;
                            $i += 1;
                        } else if($title == "Pemotongan untuk Cashback"){
                            // $invoice = explode("/", $invoice);
                            $mp_invoice          = trim($invoice);
                            $mp_price_voucher    = convertRpToInt2($val->col_2);
                            foreach ($items as $key2 => $val2) {
                                if($val2->mp_source_invoice == $mp_invoice){
                                    $items[$key2]->mp_voucher_price += $mp_price_voucher;
                                    $total_voucher_price    += $mp_price_voucher;
                                }
                            }
                        } else if($title == "Pemotongan untuk Asuransi dengan Tokopedia Insurance"){
                            // $invoice = explode("/", $invoice);
                            $mp_invoice          = trim($invoice);
                            $mp_price_insurance  = convertRpToInt2($val->col_2);
                            foreach ($items as $key2 => $val2) {
                                if($val2->mp_source_invoice == $mp_invoice){
                                    $items[$key2]->mp_price_insurance  += $mp_price_insurance;
                                    $items[$key2]->mp_price_grand_total -= $mp_price_insurance;

                                    $total_price_insurance += $mp_price_insurance;
                                    $total_price_grand_total -= $mp_price_insurance;
                                }
                            }
                        } else if($title == "Pemotongan Ongkir via Gojek" || $title == "Pemotongan Ongkir via Grab" || $title == "Pemotongan Ongkir via Ninja Express" || $title == "Pemotongan Ongkir via J&T Express"){
                            // $invoice = explode("/", $invoice);
                            $mp_invoice          = trim($invoice);
                            $mp_price_debet_ship = convertRpToInt2($val->col_2);
                            foreach ($items as $key2 => $val2) {
                                if($val2->mp_source_invoice == $mp_invoice){
                                    $items[$key2]->mp_price_debet_ship  += $mp_price_debet_ship;
                                    $items[$key2]->mp_price_grand_total -= $mp_price_debet_ship;

                                    $total_price_debet_ship += $mp_price_debet_ship;
                                    $total_price_grand_total -= $mp_price_debet_ship;
                                }
                            }
                        }
                    }
                    $iRow += 1;
                }

                $err   = false;
                $msg   = "";
            }
        }

        $details = array(
                "total_qty_product"       => $total_qty_product,
                "total_price_product"     => $total_price_product,
                "total_price_discount"    => $total_price_discount,
                "total_price_shipping"    => $total_price_shipping,
                "total_price_subsidi"     => $total_price_subsidi,
                "total_price_debet_ship"  => $total_price_debet_ship,
                "total_price_insurance"   => $total_price_insurance,
                "total_price_return"      => $total_price_return,
                "total_price_fee"         => $total_price_fee,
                "total_voucher_price"     => $total_voucher_price,
                "total_price_grand_total" => $total_price_grand_total
            );
        return array(
                "err"   => $err,
                "msg"   => $msg,
                "items" => $items,
                "details" => $details
            );
    }

    function data_shopee_payment($p=array(), $orders_source_id="8"){
        $err   = false;
        $msg   = "";
        $items = array();
        $total_qty_product    = 0;
        $total_price_product  = 0;
        $total_price_discount = 0;
        $total_price_shipping = 0;
        $total_price_subsidi  = 0;
        $total_cashback_seller  = 0;
        $total_price_debet_ship = 0;
        $total_price_insurance  = 0;
        $total_price_return     = 0;
        $total_price_fee        = 0;
        $total_voucher_price    = 0;
        $total_price_grand_total = 0;
        if(count($p) == 0){
            $err   = true;
            $msg   = "Error: Data excel kosong..";
        } else {
            if(trim($p[0]->col_0) != trim("Username (Penjual)")){
                $err   = true;
                $msg   = "Error: Bukan upload file excel SHOPEE.";
            }
            $titleCol = array();
            $titleCol[0] = "No.";
            $titleCol[1] = "No. Pesanan";
            $titleCol[2] = "No. Pengajuan";
            $titleCol[3] = "Username (Pembeli)";
            $titleCol[4] = "Waktu Pesanan Dibuat";
            $titleCol[5] = "Tanggal Dana Dilepaskan";
            $titleCol[6] = "Harga Asli Produk";
            $titleCol[7] = "Total Diskon Produk";
            $titleCol[8] = "Jumlah Pengembalian Dana ke Pembeli";
            $titleCol[9] = "Diskon Produk dari Shopee";
            $titleCol[10] = "Diskon Voucher Ditanggung Penjual";
            $titleCol[11] = "Cashback Koin yang Ditanggung Penjual";
            $titleCol[12] = "Ongkir Dibayar Pembeli";
            $titleCol[13] = "Diskon Ongkir Ditanggung Jasa Kirim";
            $titleCol[14] = "Gratis Ongkir dari Shopee";
            $titleCol[15] = "Ongkir yang Diteruskan oleh Shopee ke Jasa Kirim";
            $titleCol[16] = "Biaya Administrasi";
            $titleCol[17] = "Biaya Layanan";
            $titleCol[18] = "Biaya Transaksi";
            $titleCol[19] = "Bea Masuk, PPN & PPh";
            $titleCol[20] = "Total Penghasilan";
            $titleCol[21] = "Kode Voucher";
            $titleCol[22] = "Kompensasi";
            $titleCol[23] = "";
            $titleCol[24] = "Promo Gratis Ongkir dari Penjual";
            $titleCol[25] = "Jasa Kirim";

            $err_check = false;
            $msg_check = "";
            $titleRow  = 5;

            foreach ($titleCol as $key => $val) {
                $colName = "col_".$key;
                if(trim($val) != trim($p[$titleRow]->$colName)){
                    $err_check  = true;
                    $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                }
            }
            if($err_check){
                $err  = true;
                $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
            }

            if(!$err){
                $i       = 0;
                $iRow    = 1;
                $fromRow = 6;
                foreach ($p as $key => $val) {
                    if($iRow >= $fromRow){
                        $mp_invoice          = ltrim($val->col_1,"'");
                        // $mp_invoice          = trim($val->col_1);
                        $mp_shipping_user    = trim($val->col_3);
                        $mp_shipping_name    = NULL;
                        $mp_product_item     = array();
                        $mp_qty_product      = 0;
                        $mp_price_product    = convertRpToInt2($val->col_6);
                        $mp_price_discount   = convertRpToInt2($val->col_7);
                        $mp_price_shipping   = convertRpToInt2($val->col_12);
                        $mp_cashback_seller  = convertRpToInt2($val->col_13);
                        $mp_price_subsidi    = convertRpToInt2($val->col_14);
                        $mp_price_debet_ship = convertRpToInt2($val->col_15);
                        $mp_price_insurance  = 0;
                        $mp_price_return     = convertRpToInt2($val->col_8);
                        $mp_price_fee        = convertRpToInt2($val->col_16) + convertRpToInt2($val->col_17) + convertRpToInt2($val->col_18);
                        $mp_price_voucher    = convertRpToInt2($val->col_10) + convertRpToInt2($val->col_11);
                        $mp_grand_total      = convertRpToInt2($val->col_20);

                        // $mp_price_fee        = 0;
                        // $mp_price_voucher    = 0;
                        // $mp_grand_total      = convertRpToInt2($val->col_10);

                        $mp_data_payment     = convDatepickerDec($val->col_5);
                        $mp_claim_status     = FALSE;
                        $mp_claim_price      = 0;

                        $recalc_total = $mp_grand_total;
                        if($mp_price_debet_ship > 0){
                            $recalc_total = ($mp_price_product - $mp_price_discount) - $mp_price_voucher;
                        } else {
                            $recalc_total = (($mp_price_product - $mp_price_discount) + ($mp_price_shipping + $mp_price_subsidi) - $mp_price_voucher);
                        }
                        if($recalc_total != (($mp_grand_total - $mp_cashback_seller) + $mp_price_fee)){
                            $mp_claim_status     = TRUE;
                            $mp_claim_price      = (($mp_grand_total + $mp_price_fee) - $recalc_total);
                        }

                        $total_qty_product    += $mp_qty_product;
                        $total_price_product  += $mp_price_product;
                        $total_price_discount += $mp_price_discount;
                        $total_price_shipping += $mp_price_shipping;
                        $total_price_subsidi  += $mp_price_subsidi;
                        $total_cashback_seller  += $mp_cashback_seller;
                        $total_price_debet_ship += $mp_price_debet_ship;
                        $total_price_insurance  += $mp_price_insurance;
                        $total_price_return     += $mp_price_return;
                        $total_price_fee        += $mp_price_fee;
                        $total_voucher_price    += $mp_price_voucher;
                        $total_price_grand_total += $mp_grand_total;

                        $items[$i]->mp_row            = $iRow;
                        $items[$i]->mp_source_id      = $orders_source_id;
                        $items[$i]->mp_source_invoice = $mp_invoice;
                        $items[$i]->mp_username       = $mp_shipping_user;
                        $items[$i]->mp_shipping_name  = $mp_shipping_name;
                        $items[$i]->mp_product_item   = $mp_product_item;
                        $items[$i]->mp_qty_product    = $mp_qty_product;
                        $items[$i]->mp_price_product  = $mp_price_product;
                        $items[$i]->mp_price_discount = $mp_price_discount;
                        $items[$i]->mp_price_shipping = $mp_price_shipping;
                        $items[$i]->mp_price_subsidi  = $mp_price_subsidi;
                        $items[$i]->mp_cashback_seller   = $mp_cashback_seller;
                        $items[$i]->mp_price_debet_ship  = $mp_price_debet_ship;
                        $items[$i]->mp_price_insurance   = $mp_price_insurance;
                        $items[$i]->mp_price_return      = $mp_price_return;
                        $items[$i]->mp_price_fee         = $mp_price_fee;
                        $items[$i]->mp_voucher_price     = $mp_price_voucher;
                        $items[$i]->mp_price_grand_total = $mp_grand_total;
                        $items[$i]->mp_date_payment   = $mp_data_payment;
                        $items[$i]->mp_claim_status   = $mp_claim_status;
                        $items[$i]->mp_claim_price    = $mp_claim_price;
                        $i += 1;
                    }
                    $iRow += 1;
                }

                $err   = false;
                $msg   = "";
            }
        }

        $details = array(
                "total_qty_product"       => $total_qty_product,
                "total_price_product"     => $total_price_product,
                "total_price_discount"    => $total_price_discount,
                "total_price_shipping"    => $total_price_shipping,
                "total_price_subsidi"     => $total_price_subsidi,
                "total_cashback_seller"   => $total_cashback_seller,
                "total_price_debet_ship"  => $total_price_debet_ship,
                "total_price_insurance"   => $total_price_insurance,
                "total_price_return"      => $total_price_return,
                "total_price_fee"         => $total_price_fee,
                "total_voucher_price"     => $total_voucher_price,
                "total_price_grand_total" => $total_price_grand_total
            );
        return array(
                "err"   => $err,
                "msg"   => $msg,
                "items" => $items,
                "details" => $details
            );
    }

    function data_lazada_payment($p=array(), $orders_source_id="11"){
        $err   = false;
        $msg   = "";
        $items = array();
        $total_qty_product    = 0;
        $total_price_product  = 0;
        $total_price_discount = 0;
        $total_price_shipping = 0;
        $total_price_subsidi  = 0;
        $total_cashback_seller  = 0;
        $total_price_debet_ship = 0;
        $total_price_insurance  = 0;
        $total_price_return     = 0;
        $total_price_fee        = 0;
        $total_voucher_price    = 0;
        $total_price_grand_total = 0;
        if(count($p) == 0){
            $err   = true;
            $msg   = "Error: Data excel kosong..";
        } else {
            if(trim($p[0]->col_0) != trim("Transaction Date")){
                $err   = true;
                $msg   = "Error: Bukan upload file excel LAZADA.";
            }
            $titleCol = array();
            $titleCol[0] = "Transaction Date";
            $titleCol[1] = "Transaction Type";
            $titleCol[2] = "Fee Name";
            $titleCol[3] = "Transaction Number";
            $titleCol[4] = "Details";
            $titleCol[5] = "Seller SKU";
            $titleCol[6] = "Lazada SKU";
            $titleCol[7] = "Amount";
            $titleCol[8] = "VAT in Amount";
            $titleCol[9] = "WHT Amount";
            $titleCol[10] = "WHT included in Amount";
            $titleCol[11] = "Statement";
            $titleCol[12] = "Paid Status";
            $titleCol[13] = "Order No.";
            $titleCol[14] = "Order Item No.";
            $titleCol[15] = "Order Item Status";
            $titleCol[16] = "Shipping Provider";
            $titleCol[17] = "Shipping Speed";
            $titleCol[18] = "Shipment Type";
            $titleCol[19] = "Reference";
            $titleCol[20] = "Comment";
            $titleCol[21] = "PaymentRefId";

            $err_check = false;
            $msg_check = "";
            $titleRow  = 0;
            foreach ($titleCol as $key => $val) {
                $colName = "col_".$key;
                if(trim($val) != trim($p[$titleRow]->$colName)){
                    $err_check  = true;
                    $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                }
            }
            if($err_check){
                $err  = true;
                $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
            }

            if(!$err){
                $i       = 0;
                $iRow    = 1;
                $fromRow = 2;
                $tmpInvoice = array();
                foreach ($p as $key => $val) {
                    if($iRow >= $fromRow){
                        $mp_invoice          = (String)trim($val->col_13);
                        $mp_shipping_user    = NULL;
                        $mp_shipping_name    = NULL;
                        $trans_type          = trim($val->col_1);
                        $fee_name            = trim($val->col_2);

                        if($trans_type == "Orders-Item Charges" && $fee_name == "Item Price Credit"){
                            if(!in_array($mp_invoice, $tmpInvoice)){
                                $tmpInvoice[] = $mp_invoice;
                                $mp_product_item     = array();
                                $mp_qty_product      = 1;
                                $mp_price_product    = $val->col_7;
                                $mp_price_discount   = 0;
                                $mp_price_shipping   = 0;
                                $mp_price_subsidi    = 0;
                                $mp_cashback_seller  = 0;
                                $mp_price_debet_ship = 0;
                                $mp_price_insurance  = 0;
                                $mp_price_return     = 0;
                                $mp_price_voucher    = 0;
                                $mp_price_fee        = 0;
                                $mp_grand_total      = $mp_price_product;

                                $exp_date = explode("-", $val->col_11);
                                $mp_data_payment     = getAddDay(convDateINtoEN2($exp_date[1]),1);
                                $mp_claim_status     = FALSE;
                                $mp_claim_price      = 0;

                                $total_qty_product    += $mp_qty_product;
                                $total_price_product  += $mp_price_product;
                                $total_price_discount += $mp_price_discount;
                                $total_price_shipping += $mp_price_shipping;
                                $total_price_subsidi  += $mp_price_subsidi;
                                $total_cashback_seller  += $mp_cashback_seller;
                                $total_price_debet_ship += $mp_price_debet_ship;
                                $total_price_insurance  += $mp_price_insurance;
                                $total_price_return     += $mp_price_return;
                                $total_price_fee        += $mp_price_fee;
                                $total_voucher_price    += $mp_price_voucher;
                                $total_price_grand_total += $mp_grand_total;

                                $items[$i]->mp_row            = $iRow;
                                $items[$i]->mp_source_id      = $orders_source_id;
                                $items[$i]->mp_source_invoice = $mp_invoice;
                                $items[$i]->mp_username       = $mp_shipping_user;
                                $items[$i]->mp_shipping_name  = $mp_shipping_name;
                                $items[$i]->mp_product_item   = $mp_product_item;
                                $items[$i]->mp_qty_product    = $mp_qty_product;
                                $items[$i]->mp_price_product  = $mp_price_product;
                                $items[$i]->mp_price_discount = $mp_price_discount;
                                $items[$i]->mp_price_shipping = $mp_price_shipping;
                                $items[$i]->mp_price_subsidi  = $mp_price_subsidi;
                                $items[$i]->mp_cashback_seller = $mp_cashback_seller;
                                $items[$i]->mp_price_debet_ship  = $mp_price_debet_ship;
                                $items[$i]->mp_price_insurance   = $mp_price_insurance;
                                $items[$i]->mp_price_return      = $mp_price_return;
                                $items[$i]->mp_price_fee         = $mp_price_fee;
                                $items[$i]->mp_voucher_price     = $mp_price_voucher;
                                $items[$i]->mp_price_grand_total = $mp_grand_total;
                                $items[$i]->mp_date_payment   = $mp_data_payment;
                                $items[$i]->mp_claim_status   = $mp_claim_status;
                                $items[$i]->mp_claim_price    = $mp_claim_price;
                                $i += 1;
                            } else {
                                $mp_qty_product     = 1;
                                $mp_price_product   = $val->col_7;
                                foreach ($items as $key2 => $val2) {
                                    if($val2->mp_source_invoice == $mp_invoice){
                                        $items[$key2]->mp_qty_product       += $mp_qty_product;
                                        $items[$key2]->mp_price_product     += $mp_price_product;
                                        $items[$key2]->mp_price_grand_total += $mp_price_product;

                                        $total_qty_product    += $mp_qty_product;
                                        $total_price_product  += $mp_price_product;
                                        $total_price_grand_total += $mp_price_product;
                                    }
                                }
                            }

                        } else if($trans_type == "Orders-Lazada Fees" && $fee_name == "Payment Fee"){
                            if(!in_array($mp_invoice, $tmpInvoice)){
                                $tmpInvoice[] = $mp_invoice;
                                $mp_product_item     = array();
                                $mp_qty_product      = 0;
                                $mp_price_product    = 0;
                                $mp_price_discount   = 0;
                                $mp_price_shipping   = 0;
                                $mp_price_subsidi    = 0;
                                $mp_cashback_seller  = 0;
                                $mp_price_debet_ship = 0;
                                $mp_price_insurance  = 0;
                                $mp_price_return     = 0;
                                $mp_price_voucher    = 0;
                                $mp_price_fee        = convertRpToInt3($val->col_7 + $val->col_8);
                                $mp_grand_total      = $mp_price_product - $mp_price_fee;

                                $exp_date = explode("-", $val->col_11);
                                $mp_data_payment     = getAddDay(convDateINtoEN2($exp_date[1]),1);
                                $mp_claim_status     = FALSE;
                                $mp_claim_price      = 0;

                                $total_qty_product    += $mp_qty_product;
                                $total_price_product  += $mp_price_product;
                                $total_price_discount += $mp_price_discount;
                                $total_price_shipping += $mp_price_shipping;
                                $total_price_subsidi  += $mp_price_subsidi;
                                $total_cashback_seller  += $mp_cashback_seller;
                                $total_price_debet_ship += $mp_price_debet_ship;
                                $total_price_insurance  += $mp_price_insurance;
                                $total_price_return     += $mp_price_return;
                                $total_price_fee        += $mp_price_fee;
                                $total_voucher_price    += $mp_price_voucher;
                                $total_price_grand_total += $mp_grand_total;

                                $items[$i]->mp_row            = $iRow;
                                $items[$i]->mp_source_id      = $orders_source_id;
                                $items[$i]->mp_source_invoice = $mp_invoice;
                                $items[$i]->mp_username       = $mp_shipping_user;
                                $items[$i]->mp_shipping_name  = $mp_shipping_name;
                                $items[$i]->mp_product_item   = $mp_product_item;
                                $items[$i]->mp_qty_product    = $mp_qty_product;
                                $items[$i]->mp_price_product  = $mp_price_product;
                                $items[$i]->mp_price_discount = $mp_price_discount;
                                $items[$i]->mp_price_shipping = $mp_price_shipping;
                                $items[$i]->mp_price_subsidi  = $mp_price_subsidi;
                                $items[$i]->mp_cashback_seller   = $mp_cashback_seller;
                                $items[$i]->mp_price_debet_ship  = $mp_price_debet_ship;
                                $items[$i]->mp_price_insurance   = $mp_price_insurance;
                                $items[$i]->mp_price_return      = $mp_price_return;
                                $items[$i]->mp_price_fee         = $mp_price_fee;
                                $items[$i]->mp_voucher_price     = $mp_price_voucher;
                                $items[$i]->mp_price_grand_total = $mp_grand_total;
                                $items[$i]->mp_date_payment   = $mp_data_payment;
                                $items[$i]->mp_claim_status   = $mp_claim_status;
                                $items[$i]->mp_claim_price    = $mp_claim_price;
                                $i += 1;
                            } else {
                                $mp_qty_product     = 0;
                                $mp_price_fee       = convertRpToInt3($val->col_7 + $val->col_8);
                                foreach ($items as $key2 => $val2) {
                                    if($val2->mp_source_invoice == $mp_invoice){
                                        $items[$key2]->mp_qty_product       += $mp_qty_product;
                                        $items[$key2]->mp_price_fee         += $mp_price_fee;
                                        $items[$key2]->mp_price_grand_total -= $mp_price_fee;

                                        $total_qty_product      += $mp_qty_product;
                                        $total_price_fee        += $mp_price_fee;
                                        $total_price_grand_total -= $mp_price_fee;
                                    }
                                }
                            }
                        } else if($trans_type == "Orders-Item Charges" && $fee_name == "Promotional Charges Vouchers"){
                            if(!in_array($mp_invoice, $tmpInvoice)){
                                $tmpInvoice[] = $mp_invoice;
                                $mp_product_item     = array();
                                $mp_qty_product      = 0;
                                $mp_price_product    = 0;
                                $mp_price_discount   = 0;
                                $mp_price_shipping   = 0;
                                $mp_price_subsidi    = 0;
                                $mp_cashback_seller  = 0;
                                $mp_price_debet_ship = 0;
                                $mp_price_insurance  = 0;
                                $mp_price_return     = 0;
                                $mp_price_voucher    = convertRpToInt3($val->col_7);
                                $mp_price_fee        = 0;
                                $mp_grand_total      = $mp_price_product + $mp_price_voucher;

                                $exp_date = explode("-", $val->col_11);
                                $mp_data_payment     = getAddDay(convDateINtoEN2($exp_date[1]),1);
                                $mp_claim_status     = FALSE;
                                $mp_claim_price      = 0;

                                $total_qty_product    += $mp_qty_product;
                                $total_price_product  += $mp_price_product;
                                $total_price_discount += $mp_price_discount;
                                $total_price_shipping += $mp_price_shipping;
                                $total_price_subsidi  += $mp_price_subsidi;
                                $total_cashback_seller  += $mp_cashback_seller;
                                $total_price_debet_ship += $mp_price_debet_ship;
                                $total_price_insurance  += $mp_price_insurance;
                                $total_price_return     += $mp_price_return;
                                $total_price_fee        += $mp_price_fee;
                                $total_voucher_price    += $mp_price_voucher;
                                $total_price_grand_total += $mp_grand_total;

                                $items[$i]->mp_row            = $iRow;
                                $items[$i]->mp_source_id      = $orders_source_id;
                                $items[$i]->mp_source_invoice = $mp_invoice;
                                $items[$i]->mp_username       = $mp_shipping_user;
                                $items[$i]->mp_shipping_name  = $mp_shipping_name;
                                $items[$i]->mp_product_item   = $mp_product_item;
                                $items[$i]->mp_qty_product    = $mp_qty_product;
                                $items[$i]->mp_price_product  = $mp_price_product;
                                $items[$i]->mp_price_discount = $mp_price_discount;
                                $items[$i]->mp_price_shipping = $mp_price_shipping;
                                $items[$i]->mp_price_subsidi  = $mp_price_subsidi;
                                $items[$i]->mp_cashback_seller   = $mp_cashback_seller;
                                $items[$i]->mp_price_debet_ship  = $mp_price_debet_ship;
                                $items[$i]->mp_price_insurance   = $mp_price_insurance;
                                $items[$i]->mp_price_return      = $mp_price_return;
                                $items[$i]->mp_price_fee         = $mp_price_fee;
                                $items[$i]->mp_voucher_price     = $mp_price_voucher;
                                $items[$i]->mp_price_grand_total = $mp_grand_total;
                                $items[$i]->mp_date_payment   = $mp_data_payment;
                                $items[$i]->mp_claim_status   = $mp_claim_status;
                                $items[$i]->mp_claim_price    = $mp_claim_price;
                                $i += 1;
                            } else {
                                $mp_qty_product      = 0;
                                $mp_price_voucher    = convertRpToInt3($val->col_7);
                                foreach ($items as $key2 => $val2) {
                                    if($val2->mp_source_invoice == $mp_invoice){
                                        $items[$key2]->mp_qty_product       += $mp_qty_product;
                                        $items[$key2]->mp_voucher_price     += $mp_price_voucher;
                                        $items[$key2]->mp_price_grand_total -= $mp_price_voucher;

                                        $total_qty_product      += $mp_qty_product;
                                        $total_voucher_price    += $mp_price_voucher;
                                        $total_price_grand_total -= $mp_price_voucher;
                                    }
                                }
                            }
                        }

                    }
                    $iRow += 1;
                }

                $err   = false;
                $msg   = "";
            }
        }

        $details = array(
                "total_qty_product"       => $total_qty_product,
                "total_price_product"     => $total_price_product,
                "total_price_discount"    => $total_price_discount,
                "total_price_shipping"    => $total_price_shipping,
                "total_price_subsidi"     => $total_price_subsidi,
                "total_cashback_seller"   => $total_cashback_seller,
                "total_price_debet_ship"  => $total_price_debet_ship,
                "total_price_insurance"   => $total_price_insurance,
                "total_price_return"      => $total_price_return,
                "total_price_fee"         => $total_price_fee,
                "total_voucher_price"     => $total_voucher_price,
                "total_price_grand_total" => $total_price_grand_total
            );
        return array(
                "err"   => $err,
                "msg"   => $msg,
                "items" => $items,
                "details" => $details
            );
    }

    function data_lazada_payment_claim($p=array(), $orders_source_id="lazada-claim"){
        $err   = false;
        $msg   = "";
        $items = array();
        $total_qty_product    = 0;
        $total_price_product  = 0;
        $total_price_grand_total = 0;
        if(count($p) == 0){
            $err   = true;
            $msg   = "Error: Data excel kosong..";
        } else {
            if(trim($p[0]->col_0) != trim("Transaction Date")){
                $err   = true;
                $msg   = "Error: Bukan upload file excel LAZADA.";
            }
            $titleCol = array();
            $titleCol[0] = "Transaction Date";
            $titleCol[1] = "Transaction Type";
            $titleCol[2] = "Fee Name";
            $titleCol[3] = "Transaction Number";
            $titleCol[4] = "Details";
            $titleCol[5] = "Seller SKU";
            $titleCol[6] = "Lazada SKU";
            $titleCol[7] = "Amount";
            $titleCol[8] = "VAT in Amount";
            $titleCol[9] = "WHT Amount";
            $titleCol[10] = "WHT included in Amount";
            $titleCol[11] = "Statement";
            $titleCol[12] = "Paid Status";
            $titleCol[13] = "Order No.";
            $titleCol[14] = "Order Item No.";
            $titleCol[15] = "Order Item Status";
            $titleCol[16] = "Shipping Provider";
            $titleCol[17] = "Shipping Speed";
            $titleCol[18] = "Shipment Type";
            $titleCol[19] = "Reference";
            $titleCol[20] = "Comment";
            $titleCol[21] = "PaymentRefId";

            $err_check = false;
            $msg_check = "";
            $titleRow  = 0;
            foreach ($titleCol as $key => $val) {
                $colName = "col_".$key;
                if(trim($val) != trim($p[$titleRow]->$colName)){
                    $err_check  = true;
                    $msg_check .= ($msg_check!=""?",":"").$key.". ".$val;
                }
            }
            if($err_check){
                $err  = true;
                $msg .= "Error: Column mengalami perubahan, yaitu: ".$msg_check;
            }

            if(!$err){
                $i       = 0;
                $iRow    = 1;
                $fromRow = 2;
                $tmpInvoice = array();
                foreach ($p as $key => $val) {
                    if($iRow >= $fromRow){
                        $mp_invoice          = (String)trim($val->col_13);
                        $trans_type          = trim($val->col_1);
                        $fee_name            = trim($val->col_2);

                        if($trans_type == "Refunds-Claims" && $fee_name == "Lost Claim"){ // KALAU ERROR KOSONG, CHECK!!!
                            if(!in_array($mp_invoice, $tmpInvoice)){
                                $tmpInvoice[] = $mp_invoice;
                                $mp_qty_product      = 1;
                                $mp_price_product    = $val->col_7;
                                $mp_grand_total      = $mp_price_product;


                                $total_qty_product    += $mp_qty_product;
                                $total_price_product  += $mp_price_product;
                                $total_price_grand_total += $mp_grand_total;

                                $items[$i]->mp_row            = $iRow;
                                $items[$i]->mp_source_id      = $orders_source_id;
                                $items[$i]->mp_source_invoice = $mp_invoice;
                                $items[$i]->mp_qty_product    = $mp_qty_product;
                                $items[$i]->mp_price_product  = $mp_price_product;
                                $items[$i]->mp_price_grand_total = $mp_grand_total;
                                $i += 1;
                            } else {
                                $mp_qty_product     = 1;
                                $mp_price_product   = $val->col_7;
                                foreach ($items as $key2 => $val2) {
                                    if($val2->mp_source_invoice == $mp_invoice){
                                        $items[$key2]->mp_qty_product       += $mp_qty_product;
                                        $items[$key2]->mp_price_product     += $mp_price_product;
                                        $items[$key2]->mp_price_grand_total += $mp_price_product;

                                        $total_qty_product    += $mp_qty_product;
                                        $total_price_product  += $mp_price_product;
                                        $total_price_grand_total += $mp_price_product;
                                    }
                                }
                            }
                        }

                    }
                    $iRow += 1;
                }

                $err   = false;
                $msg   = "";
            }
        }

        $details = array(
                "total_qty_product"       => $total_qty_product,
                "total_price_product"     => $total_price_product,
                "total_price_grand_total" => $total_price_grand_total
            );
        return array(
                "err"   => $err,
                "msg"   => $msg,
                "items" => $items,
                "details" => $details
            );
    }

}