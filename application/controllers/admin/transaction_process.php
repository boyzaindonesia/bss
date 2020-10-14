<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class transaction_process extends AdminController {
    function __construct()
    {
        ini_set('precision', '15');
        parent::__construct();
        $this->_set_action();
        $this->DATA->table="mt_orders";
        $this->folder_view = "orders/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_transaction_process","MTP");

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        // $this->detail_store     = get_detail_store($this->store_id);
        // $this->store_name       = $this->detail_store->store_name;
        // $this->store_phone      = $this->detail_store->store_phone;
        // $this->store_product    = $this->detail_store->store_product;
    }

    function _reset(){
        $this->jCfg['search'] = array(
            'class'     => $this->_getClass(),
            'name'      => 'transaction',
            'date_start'=> '',
            'date_end'  => '',
            'status'    => '',
            'order_by'  => 'mt_orders.orders_date',
            'order_dir' => 'desc',
            'filter'    => '25',
            'colum'     => '',
            'keyword'   => '',
            'orders_courier_id' => NULL
        );
        $this->_releaseSession();
    }

    function clear_verifikasi_payment(){
        $this->_reset();
        $this->jCfg['search']['name']   = 'orders_shipping';
        $this->jCfg['search']['filter'] = 25;
        unset($this->jCfg['marketplace_payment']);
        unset($this->jCfg['marketplace_payment_details']);
        $this->_releaseSession();
        redirect(base_url().'admin/transaction/shipping');
    }


    function save_multiple_confirm_orders(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['href']    = '';
        $data['content'] = '';

        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $thisId = dbClean(trim($_POST['thisId']));
            if($thisId != ''){
                $expId = explode('-', $thisId);
                foreach ($expId as $k => $v) {
                    $orders_id = $v;
                    $par_filter = array(
                        "user_id"               => $this->user_id,
                        "store_id"              => $this->store_id,
                        "orders_id"             => $orders_id
                    );

                    $this->load->model("mdl_transaction_process","MTP");
                    $result = $this->MTP->save_confirm_orders($par_filter);
                    $data['content'] = $result['data'];
                }
                $data['err']    = false;
                $data['msg']    = 'Berhasil simpan data...';
            }
        }

        die(json_encode($data));
        exit();
    }

    function save_shipping(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['href']    = '';
        $data['content'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
            $orders_id = $_POST['thisId'];
            $chkPriceDebetCourier  = $_POST['chkPriceDebetCourier'];
            $orders_shipping_price = convertRpToInt2($_POST['orders_shipping_price']);
            $orders_shipping_resi  = strtoupper($_POST['orders_shipping_resi']);
            $par_filter = array(
                "user_id"               => $this->user_id,
                "store_id"              => $this->store_id,
                "orders_id"             => $orders_id,
                "chkPriceDebetCourier"  => $chkPriceDebetCourier,
                "orders_shipping_price" => $orders_shipping_price,
                "orders_shipping_resi"  => $orders_shipping_resi
            );

            $result = $this->MTP->save_process_shipping($par_filter);
            $data['content'] = $result['data'];
            $data['err']     = $result['error'];
            $data['msg']     = $result['msg'];
        }

        die(json_encode($data));
        exit();
    }

    function save_multiple_shipping(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['href']    = '';
        $data['content'] = '';
        if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
            $checked_files = $_POST['checked_files'];
            foreach ($checked_files as $k => $v) {
                $orders_id = $v;
                $chkPriceDebetCourier  = $_POST['chkPriceDebetCourier'][$orders_id];
                $orders_shipping_price = convertRpToInt2($_POST['orders_shipping_price'][$orders_id]);
                $orders_shipping_resi  = strtoupper($_POST['orders_shipping_resi'][$orders_id]);
                $par_filter = array(
                    "user_id"               => $this->user_id,
                    "store_id"              => $this->store_id,
                    "orders_id"             => $orders_id,
                    "chkPriceDebetCourier"  => $chkPriceDebetCourier,
                    "orders_shipping_price" => $orders_shipping_price,
                    "orders_shipping_resi"  => $orders_shipping_resi
                );

                $result = $this->MTP->save_process_shipping($par_filter);
                $data['content'] = $result['data'];
            }

            $data['err']    = false;
            $data['msg']    = 'Berhasil simpan data...';
        }

        die(json_encode($data));
        exit();
    }

    function save_payment(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['href']   = '';

        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $orders_id = dbClean(trim($_POST['thisId']));
            $orders_price_grand_total = dbClean(convertRpToInt($_POST['orders_price_grand_total']));
            $orders_payment_price     = dbClean(convertRpToInt($_POST['orders_payment_price']));
            $orders_voucher_price     = dbClean(convertRpToInt($_POST['orders_voucher_price']));
            $orders_price_debet_ship  = dbClean(convertRpToInt($_POST['orders_price_debet_ship']));
            $orders_claim_price   = dbClean(convertRpToInt($_POST['orders_claim_price']));
            $orders_price_return  = dbClean(convertRpToInt($_POST['orders_price_return']));
            $par_filter = array(
                "user_id"               => $this->user_id,
                "store_id"              => $this->store_id,
                "orders_id"             => $orders_id,
                "orders_price_grand_total"  => $orders_price_grand_total,
                "orders_payment_price"      => $orders_payment_price,
                "orders_voucher_price"      => $orders_voucher_price,
                "orders_price_debet_ship"   => $orders_price_debet_ship,
                "orders_claim_price"        => $orders_claim_price,
                "orders_price_return"       => $orders_price_return
            );

            $result = $this->MTP->save_payment($par_filter);
            $data['content'] = $result['data'];
            $data['err']     = $result['error'];
            $data['msg']     = $result['msg'];
        }

        die(json_encode($data));
        exit();
    }

    function save_multiple_payment(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['href']   = '';

        if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
            $checked_files = $_POST['checked_files'];
            foreach ($checked_files as $k => $v) {
                $orders_id = $v;
                $orders_price_grand_total = dbClean(convertRpToInt($_POST['orders_price_grand_total'][$orders_id]));
                $orders_payment_price     = dbClean(convertRpToInt($_POST['orders_payment_price'][$orders_id]));
                $orders_voucher_price     = dbClean(convertRpToInt($_POST['orders_voucher_price'][$orders_id]));
                $orders_price_debet_ship  = dbClean(convertRpToInt($_POST['orders_price_debet_ship'][$orders_id]));
                $orders_claim_price   = dbClean(convertRpToInt($_POST['orders_claim_price'][$orders_id]));
                $orders_price_return  = dbClean(convertRpToInt($_POST['orders_price_return'][$orders_id]));
                $par_filter = array(
                    "user_id"               => $this->user_id,
                    "store_id"              => $this->store_id,
                    "orders_id"             => $orders_id,
                    "orders_price_grand_total"  => $orders_price_grand_total,
                    "orders_payment_price"      => $orders_payment_price,
                    "orders_voucher_price"      => $orders_voucher_price,
                    "orders_price_debet_ship"   => $orders_price_debet_ship,
                    "orders_claim_price"        => $orders_claim_price,
                    "orders_price_return"       => $orders_price_return
                );

                $result = $this->MTP->save_payment($par_filter);
                $data['content'] = $result['data'];

            }
            $data['err']    = false;
            $data['msg']    = 'Berhasil simpan data...';
        }

        die(json_encode($data));
        exit();
    }

    function save_verifikasi_payment(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['href']   = '';

        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $orders_id = dbClean(trim($_POST['thisId']));
            $orders_price_grand_total = dbClean(convertRpToInt($_POST['orders_price_grand_total']));
            $orders_payment_price     = dbClean(convertRpToInt($_POST['orders_payment_price']));
            $orders_voucher_price     = dbClean(convertRpToInt($_POST['orders_voucher_price']));
            $orders_cashback_seller   = dbClean(convertRpToInt($_POST['orders_cashback_seller']));
            $orders_price_debet_ship  = dbClean(convertRpToInt($_POST['orders_price_debet_ship']));
            $orders_claim_price   = dbClean(convertRpToInt($_POST['orders_claim_price']));
            $orders_price_return  = dbClean(convertRpToInt($_POST['orders_price_return']));
            $orders_payment_date  = dbClean($_POST['orders_payment_date']);
            $par_filter = array(
                "user_id"               => $this->user_id,
                "store_id"              => $this->store_id,
                "orders_id"             => $orders_id,
                "orders_price_grand_total" => $orders_price_grand_total,
                "orders_payment_price"     => $orders_payment_price,
                "orders_voucher_price"     => $orders_voucher_price,
                "orders_cashback_seller"   => $orders_cashback_seller,
                "orders_price_debet_ship"  => $orders_price_debet_ship,
                "orders_claim_price"       => $orders_claim_price,
                "orders_price_return"      => $orders_price_return,
                "orders_payment_date"      => $orders_payment_date
            );

            $result = $this->MTP->save_verifikasi_payment($par_filter);
            $data['content'] = $result['data'];
            $data['err']     = $result['error'];
            $data['msg']     = $result['msg'];
        }

        die(json_encode($data));
        exit();
    }

    function save_claim(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['href']   = '';

        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $orders_id    = dbClean(trim($_POST['thisId']));
            $claim_status = dbClean(trim($_POST['thisStatus']));
            $claim_price  = dbClean(trim($_POST['thisPrice']));
            $par_filter = array(
                "user_id"               => $this->user_id,
                "store_id"              => $this->store_id,
                "orders_id"             => $orders_id,
                "claim_status"          => $claim_status,
                "claim_price"           => $claim_price
            );

            $result = $this->MTP->save_claim($par_filter);
            $data['content'] = $result['data'];
            $data['err']     = $result['error'];
            $data['msg']     = $result['msg'];
        }

        die(json_encode($data));
        exit();
    }

    function save_canceled(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['href']   = '';

        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $orders_id  = dbClean(trim($_POST['thisId']));
            $thisStatus = dbClean(trim($_POST['thisStatus']));

            $orders_status = 9;
            if($thisStatus == "return"){ $orders_status = 11; }

            $par_filter = array(
                "user_id"               => $this->user_id,
                "store_id"              => $this->store_id,
                "orders_id"             => $orders_id,
                "orders_status"         => $orders_status
            );

            $result = $this->MTP->save_canceled($par_filter);
            $data['content'] = $result['data'];
            $data['err']     = $result['error'];
            $data['msg']     = $result['msg'];
        }

        die(json_encode($data));
        exit();
    }

    function move_to_backup(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $r = $this->db->order_by("orders_id", "asc")->get_where("mt_orders",array(
                "orders_status >"  => 7
            ),1000,0)->result();
            if(count($r) > 0){
                $orders = array();
                $orders_detail = array();
                $orders_shipping = array();
                $orders_payment = array();
                $orders_timestamp = array();
                foreach ($r as $key => $val) {
                    $orders_id = $val->orders_id;

                    // SAVE ORDER
                    foreach ($val as $k => $v) { $orders[$k] = $v; }
                    $this->DATA->table="mt_orders_bk";
                    $a = $this->_save_master($orders, array('orders_id' => $orders_id),'');

                    // SAVE ORDER DETAIL
                    $r2 = $this->db->get_where("mt_orders_detail",array(
                        "orders_id "  => $orders_id,
                    ))->result();
                    if(count($r2) > 0){
                        foreach ($r2 as $key2 => $val2) {
                            $orders_detail_id = $val2->orders_detail_id;
                            foreach ($val2 as $k2 => $v2) { $orders_detail[$k2] = $v2; }
                            $this->DATA->table="mt_orders_detail_bk";
                            $a2 = $this->_save_master($orders_detail, array('orders_detail_id' => $orders_detail_id),'');
                            $this->db->delete("mt_orders_detail",array('orders_detail_id' => $orders_detail_id));
                        }
                    }

                    // SAVE ORDER SHIPPING
                    $r3 = $this->db->get_where("mt_orders_shipping",array(
                        "orders_id "  => $orders_id
                    ),1,0)->result();
                    if(count($r3) > 0){
                        foreach ($r3 as $key3 => $val3) {
                            $orders_shipping_id = $val3->orders_shipping_id;
                            foreach ($val3 as $k3 => $v3) { $orders_shipping[$k3] = $v3; }
                            $this->DATA->table="mt_orders_shipping_bk";
                            $a3 = $this->_save_master($orders_shipping, array('orders_shipping_id' => $orders_shipping_id),'');
                            $this->db->delete("mt_orders_shipping",array('orders_shipping_id' => $orders_shipping_id));
                        }
                    }

                    // SAVE ORDER PAYMENT
                    $r4 = $this->db->get_where("mt_orders_payment",array(
                        "orders_id "  => $orders_id
                    ),1,0)->result();
                    if(count($r4) > 0){
                        foreach ($r4 as $key4 => $val4) {
                            $orders_payment_id = $val4->orders_payment_id;
                            foreach ($val4 as $k4 => $v4) { $orders_payment[$k4] = $v4; }
                            $this->DATA->table="mt_orders_payment_bk";
                            $a4 = $this->_save_master($orders_payment, array('orders_payment_id' => $orders_payment_id),'');
                            $this->db->delete("mt_orders_payment",array('orders_payment_id' => $orders_payment_id));
                        }
                    }

                    // SAVE ORDER TIMESTAMP
                    $r5 = $this->db->get_where("mt_orders_timestamp",array(
                        "orders_id "  => $orders_id
                    ),1,0)->result();
                    if(count($r5) > 0){
                        foreach ($r5 as $key5 => $val5) {
                            $orders_timestamp_id = $val5->orders_timestamp_id;
                            foreach ($val5 as $k5 => $v5) { $orders_timestamp[$k5] = $v5; }
                            $this->DATA->table="mt_orders_timestamp_bk";
                            $a5 = $this->_save_master($orders_timestamp, array('orders_timestamp_id' => $orders_timestamp_id),'');
                            $this->db->delete("mt_orders_timestamp",array('orders_timestamp_id' => $orders_timestamp_id));
                        }
                    }

                    $this->db->delete("mt_orders",array('orders_id' => $orders_id));
                    // echo $orders_id."<br>";
                }

                $data['err'] = false;
                $data['msg'] = 'Berhasil pindah ke backup..';
            } else {
                $data['err'] = false;
                $data['msg'] = 'Data kosong...';
            }
        }

        die(json_encode($data));
        exit();
    }

    function copy_backup_to_table_primary(){ // JANGAN DIBUANG
        echo 'Begin backup to table primary<br>';
        $r = $this->db->order_by("orders_id", "asc")->get_where("mt_orders_bk2",array(
            "orders_status <"  => 8,
            "orders_istrash"   => 0
        ),500,0)->result();
        if(count($r) > 0){
            $orders = array();
            $orders_detail = array();
            $orders_shipping = array();
            $orders_payment = array();
            $orders_timestamp = array();
            foreach ($r as $key => $val) {
                $orders_id = $val->orders_id;

                // SAVE ORDER
                foreach ($val as $k => $v) { $orders[$k] = $v; }
                $this->DATA->table="mt_orders";
                $a = $this->_save_master($orders, array('orders_id' => $orders_id),'');

                // SAVE ORDER DETAIL
                $r2 = $this->db->get_where("mt_orders_detail_bk2",array(
                    "orders_id "  => $orders_id,
                ))->result();
                if(count($r2) > 0){
                    foreach ($r2 as $key2 => $val2) {
                        $orders_detail_id = $val2->orders_detail_id;
                        foreach ($val2 as $k2 => $v2) { $orders_detail[$k2] = $v2; }
                        $this->DATA->table="mt_orders_detail";
                        $a2 = $this->_save_master($orders_detail, array('orders_detail_id' => $orders_detail_id),'');
                        $this->db->delete("mt_orders_detail_bk2",array('orders_detail_id' => $orders_detail_id));
                    }
                }

                // SAVE ORDER SHIPPING
                $r3 = $this->db->get_where("mt_orders_shipping_bk2",array(
                    "orders_id "  => $orders_id,
                ),1,0)->result();
                if(count($r3) > 0){
                    foreach ($r3 as $key3 => $val3) {
                        $orders_shipping_id = $val3->orders_shipping_id;
                        foreach ($val3 as $k3 => $v3) { $orders_shipping[$k3] = $v3; }
                        $this->DATA->table="mt_orders_shipping";
                        $a3 = $this->_save_master($orders_shipping, array('orders_shipping_id' => $orders_shipping_id),'');
                        $this->db->delete("mt_orders_shipping_bk2",array('orders_shipping_id' => $orders_shipping_id));
                    }
                }

                // SAVE ORDER PAYMENT
                $r4 = $this->db->get_where("mt_orders_payment_bk2",array(
                    "orders_id "  => $orders_id,
                ),1,0)->result();
                if(count($r4) > 0){
                    foreach ($r4 as $key4 => $val4) {
                        $orders_payment_id = $val4->orders_payment_id;
                        foreach ($val4 as $k4 => $v4) { $orders_payment[$k4] = $v4; }
                        $this->DATA->table="mt_orders_payment";
                        $a4 = $this->_save_master($orders_payment, array('orders_payment_id' => $orders_payment_id),'');
                        $this->db->delete("mt_orders_payment_bk2",array('orders_payment_id' => $orders_payment_id));
                    }
                }

                // SAVE ORDER TIMESTAMP
                $r5 = $this->db->get_where("mt_orders_timestamp_bk2",array(
                    "orders_id "  => $orders_id,
                ),1,0)->result();
                if(count($r5) > 0){
                    foreach ($r5 as $key5 => $val5) {
                        $orders_timestamp_id = $val5->orders_timestamp_id;
                        foreach ($val5 as $k5 => $v5) { $orders_timestamp[$k5] = $v5; }
                        $this->DATA->table="mt_orders_timestamp";
                        $a5 = $this->_save_master($orders_timestamp, array('orders_timestamp_id' => $orders_timestamp_id),'');
                        $this->db->delete("mt_orders_timestamp_bk2",array('orders_timestamp_id' => $orders_timestamp_id));
                    }
                }

                $this->db->delete("mt_orders_bk2",array('orders_id' => $orders_id));
                echo $orders_id."<br>";
            }
        }
    }

    function upload_new_orders(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['items'] = array();
        $count = 0;

        $this->load->model("mdl_marketplace_excel","MME");
        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'upload'){

            $orders_source_id = $_POST['orders_source_id'];
            $files = $_FILES['files'];
            if (!empty($files['name'])){
                $upload_path    = './assets/collections/tmp_files/';
                $filename       = $files['name'];
                $rand           = strtolower(changeEnUrl($filename)).'_'.convDatetoString(timestamp());
                $ext            = substr($filename, strpos($filename,'.'), strlen($filename)-1);
                $imgname        = $rand.$ext;
                $imgPath        = $upload_path.$imgname;
                if(isset($files) && $files['error'] == 0){
                    if(move_uploaded_file($files['tmp_name'], $imgPath)){

                        $objPHPExcel = PHPExcel_IOFactory::load($imgPath);
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            $worksheetTitle     = $worksheet->getTitle();
                            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                            // $nrColumns = ord($highestColumn) - 64;

                            $paramExcel = array();
                            for ($row = 0; $row < $highestRow; $row++) {
                                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                    $cellVal = "";
                                    $cell = $worksheet->getCellByColumnAndRow($col, ($row + 1));
                                    if(strstr($cell->getValue(),'=')==true){
                                        $cellVal = $worksheet->getCellByColumnAndRow($col, ($row + 1))->getCalculatedValue();
                                    } else {
                                        $cellVal = $cell->getValue();
                                    }
                                    $colName = "col_".$col;
                                    $paramExcel[$row]->$colName = $cellVal;
                                }
                            }

                            if($orders_source_id == 3){ // TOKOPEDIA
                                $dataExcel     = $this->MME->data_tokopedia_new_orders($paramExcel, $orders_source_id);
                            } else if($orders_source_id == 2){ // BUKALAPAK
                                $dataExcel     = $this->MME->data_bukalapak_new_orders($paramExcel, $orders_source_id);
                            } else if($orders_source_id == 8){ // SHOPEE
                                $dataExcel     = $this->MME->data_shopee_new_orders($paramExcel, $orders_source_id);
                            } else if($orders_source_id == 11){ // LAZADA
                                $dataExcel     = $this->MME->data_lazada_new_orders($paramExcel, $orders_source_id);
                                // debugCode($dataExcel);
                            }
                            $data['err']   = $dataExcel['err'];
                            $data['msg']   = $dataExcel['msg'];
                            $data['items'] = $dataExcel['items'];
                        }

                        if($data['err'] == 1){
                            debugCode($data['msg']);
                        } else {
                            if(count($data['items']) > 0){
                                foreach ($data['items'] as $key => $val) {
                                    $m = $this->db->get_where("mt_orders",array(
                                        "store_id"              => $this->store_id,
                                        "orders_source_id"      => $val->orders_source_id,
                                        "orders_source_invoice" => strtoupper($val->orders_source_invoice)
                                    ),1,0)->row();
                                    if(count($m) == 0){
                                        $create_orders_code = create_orders_code();
                                        $data2 = array(
                                            'orders_code'                   => $create_orders_code['orders_code'],
                                            'orders_invoice'                => $create_orders_code['orders_invoice'],
                                            'member_type'                   => 2,
                                            'member_id'                     => $this->user_id,
                                            'store_id'                      => $this->store_id,
                                            'orders_status'                 => $val->orders_status,
                                            'orders_source_id'              => $val->orders_source_id,
                                            'orders_source_invoice'         => strtoupper($val->orders_source_invoice),
                                            'orders_source_price'           => $val->orders_price_product,
                                            'orders_source_payment'         => 0,
                                            'orders_price_buy_total'        => 0,
                                            'orders_price_product'          => $val->orders_price_product,
                                            'orders_price_shipping'         => $val->orders_price_shipping,
                                            'orders_price_insurance'        => $val->orders_price_insurance,
                                            'orders_price_ppn'              => 0,
                                            'orders_price_grand_total'      => $val->orders_price_grand_total,
                                            'orders_voucher_price'          => $val->orders_voucher_price,
                                            'orders_voucher_code'           => NULL,
                                            'orders_courier_id'             => $val->orders_courier_id,
                                            'orders_noted'                  => $val->orders_noted,
                                            'orders_print'                  => $val->orders_print,
                                            'orders_product_detail'         => 0,
                                            'ip_address'                    => $_SERVER['REMOTE_ADDR'],
                                            'user_agent'                    => $_SERVER['HTTP_USER_AGENT'],
                                            'notify'                        => 0,
                                            'date_notify'                   => timestamp(),
                                            'orders_date'                   => timestamp()
                                        );

                                        $isPriceDebetCourier= isPriceDebetCourier($val->orders_source_id, $val->orders_courier_id);
                                        if($isPriceDebetCourier){$data2['orders_price_grand_total']=$val->orders_price_product;}

                                        $this->DATA->table="mt_orders";
                                        $a2 = $this->_save_master(
                                            $data2,
                                            array(
                                                'orders_id' => ''
                                                ),
                                            ''
                                            );

                                        $count += 1;

                                        $id = $a2['id'];
                                        if($id != ""){

                                            // SAVE MT_ORDERS_PAYMENT
                                            $create_payment_code = create_payment_code();
                                            $data4 = array(
                                                'orders_id'                     => $id,
                                                'orders_payment_code'           => $create_payment_code['payment_code'],
                                                'orders_payment_method'         => 3,
                                                'orders_payment_price'          => 0,
                                                'orders_payment_grand_total'    => $data2['orders_price_grand_total'],
                                                'orders_payment_status'         => 2,
                                                'notify'                        => 0,
                                                'date_notify'                   => timestamp(),
                                                'orders_payment_date'           => timestamp()
                                            );

                                            $this->DATA->table="mt_orders_payment";
                                            $a4 = $this->_save_master(
                                                $data4,
                                                array(
                                                    'orders_payment_id' => ''
                                                ),
                                                ''
                                            );

                                            // SAVE MT_ORDERS_SHIPPING
                                            $data5 = array(
                                                'orders_id'                     => $id,
                                                'orders_shipping_status'        => 3,
                                                'orders_shipping_method'        => 1,
                                                'orders_shipping_dropship'      => $val->orders_dropship,
                                                'orders_ship_name'              => ucwords($val->orders_ship_name),
                                                'orders_ship_phone'             => $val->orders_ship_phone,
                                                'orders_shipping_username'      => strtolower($val->orders_username),
                                                'orders_shipping_name'          => strtoupper($val->orders_shipping_name),
                                                'orders_shipping_phone'         => $val->orders_shipping_phone,
                                                'orders_shipping_email'         => $val->orders_shipping_email,
                                                'orders_shipping_address'       => ucwords($val->orders_shipping_address),
                                                'orders_shipping_city'          => strtoupper($val->orders_shipping_city),
                                                'orders_shipping_province'      => strtoupper($val->orders_shipping_province),
                                                'orders_shipping_postal_code'   => $val->orders_shipping_postal_code,
                                                'orders_product_category_title' => $val->orders_product_category_title,
                                                'orders_shipping_price'         => NULL,
                                                'orders_shipping_weight'        => 1,
                                                'notify'                        => 0,
                                                'date_notify'                   => timestamp(),
                                                'orders_shipping_date'          => timestamp()
                                            );

                                            if($val->orders_resi != ""){
                                                $data5["orders_shipping_resi"] = strtoupper($val->orders_resi);
                                            }

                                            $this->DATA->table="mt_orders_shipping";
                                            $a5 = $this->_save_master(
                                                $data5,
                                                array(
                                                    'orders_shipping_id' => ''
                                                ),
                                                ''
                                            );

                                            // SAVE MT_ORDERS_TIMESTAMP
                                            $arr_orders_timestamp   = array();
                                            $arr_orders_timestamp[] = array("id" => "3", "date" => timestamp() );

                                            $data6 = array(
                                                'orders_id'                 => $id,
                                                'orders_timestamp_desc'     => json_encode($arr_orders_timestamp)
                                            );
                                            $this->DATA->table="mt_orders_timestamp";
                                            $a6 = $this->_save_master(
                                                $data6,
                                                array(
                                                    'orders_timestamp_id' => ''
                                                ),
                                                ''
                                            );

                                        }
                                    } else {
                                        // $detail_shipping = get_detail_orders_shipping($m->orders_id);
                                        if($val->orders_resi != ""){
                                            $data5 = array('orders_shipping_resi' => strtoupper($val->orders_resi) );
                                            $this->db->update("mt_orders_shipping",$data5,array("orders_id"=>$m->orders_id));
                                        }
                                        if($m->orders_source_price == 0 && $val->orders_source_price > 0){
                                            $data2 = array('orders_source_price' => $val->orders_source_price);
                                            $this->db->update("mt_orders",$data2,array("orders_id"=>$m->orders_id));
                                        }

                                        if($orders_source_id == 3){ // TOKOPEDIA
                                            // if($m->orders_status < 8){

                                            // }
                                        } else if($orders_source_id == 8){ // SHOPEE
                                            // if($m->orders_status < 8){
                                                // $data2 = array(
                                                //     'orders_price_product'          => $val->orders_price_product,
                                                //     'orders_price_shipping'         => $val->orders_price_shipping,
                                                //     'orders_price_insurance'        => $val->orders_price_insurance,
                                                //     'orders_price_ppn'              => 0,
                                                //     'orders_price_grand_total'      => $val->orders_price_grand_total,
                                                //     'orders_voucher_price'          => $val->orders_voucher_price
                                                // );
                                                // $this->db->update("mt_orders",$data2,array("orders_id"=>$m->orders_id));
                                                // $data4 = array(
                                                //     'orders_payment_grand_total'    => $val->orders_price_grand_total
                                                // );
                                                // $this->db->update("mt_orders_payment",$data4,array("orders_id"=>$m->orders_id));
                                                // $data['msg'] .= strtoupper($val->orders_source_invoice).' sudah diupdate'.'<br>';
                                            // }
                                        } else {
                                            // $data['err']  = true;
                                            // $data['msg'] .= strtoupper($val->orders_source_invoice).' sudah ada'.'<br>';
                                        }
                                    }
                                }

                                $data['err']  = false;
                                $data['msg'] .= 'Upload success.. <br/>Total: '.$count;
                            }
                        }
                    }
                } else {
                    $data['err']  = true;
                    $data['msg'] .= 'Error: ' . $files['error'] . '<br>';
                }
            }

            $upload_files = glob('./assets/collections/tmp_files/*');
            foreach($upload_files as $file){
                if(is_file($file))
                unlink($file);
            }

            redirect(base_url()."admin/transaction/new_orders?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));
        }
    }

    function upload_payment(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['items'] = array();
        $count = 0;

        $this->jCfg['marketplace_payment']  = array();
        $this->jCfg['marketplace_payment_details'] = array();
        $this->_releaseSession();

        $this->load->model("mdl_marketplace_excel","MME");
        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'upload'){

            $orders_source_id = $_POST['orders_source_id'];
            $files = $_FILES['files'];
            if (!empty($files['name'])){
                $upload_path    = './assets/collections/tmp_files/';
                $filename       = $files['name'];
                $rand           = strtolower(changeEnUrl($filename)).'_'.convDatetoString(timestamp());
                $ext            = substr($filename, strpos($filename,'.'), strlen($filename)-1);
                $imgname        = $rand.$ext;
                $imgPath        = $upload_path.$imgname;
                if(isset($files) && $files['error'] == 0){
                    if(move_uploaded_file($files['tmp_name'], $imgPath)){

                        $objPHPExcel = PHPExcel_IOFactory::load($imgPath);
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            $worksheetTitle     = $worksheet->getTitle();
                            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                            // $nrColumns = ord($highestColumn) - 64;

                            $paramExcel = array();
                            for ($row = 0; $row < $highestRow; $row++) {
                                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                    $cellVal = "";
                                    $cell = $worksheet->getCellByColumnAndRow($col, ($row + 1));
                                    if(strstr($cell->getValue(),'=')==true){
                                        $cellVal = $worksheet->getCellByColumnAndRow($col, ($row + 1))->getCalculatedValue();
                                    } else {
                                        $cellVal = $cell->getValue();
                                    }
                                    $colName = "col_".$col;
                                    $paramExcel[$row]->$colName = $cellVal;
                                }
                            }

                            if($orders_source_id == 3){ // TOKOPEDIA
                                $dataExcel     = $this->MME->data_tokopedia_payment($paramExcel, $orders_source_id);
                            } else if($orders_source_id == 2){ // BUKALAPAK
                                $dataExcel     = $this->MME->data_bukalapak_payment($paramExcel, $orders_source_id);
                            } else if($orders_source_id == 8){ // SHOPEE
                                $dataExcel     = $this->MME->data_shopee_payment($paramExcel, $orders_source_id);
                            } else if($orders_source_id == 11){ // LAZADA
                                $dataExcel     = $this->MME->data_lazada_payment($paramExcel, $orders_source_id);
                            } else if($orders_source_id == "lazada-claim"){ // LAZADA CLAIM
                                $dataExcel     = $this->MME->data_lazada_payment_claim($paramExcel, $orders_source_id);
                            }
                            $data['err']     = $dataExcel['err'];
                            $data['msg']     = $dataExcel['msg'];
                            $data['items']   = $dataExcel['items'];
                            $data['details'] = $dataExcel['details'];

                            // if($orders_source_id == "lazada-claim"){
                            //     debugCode($dataExcel);
                            // }
                        }

                        if($data['err'] == 1){
                            debugCode($data['msg']);
                        } else {
                            if(count($data['items']) > 0){
                                $this->jCfg['marketplace_payment'] = $data['items'];
                                $this->jCfg['marketplace_payment_details'] = $data['details'];
                                $this->_releaseSession();

                                $this->db->update("mt_orders_source",array("date_upload_payment"=>timestamp()),array("orders_source_id"=>$orders_source_id));

                                $data['err']  = false;
                                $data['msg'] .= 'Upload success.. <br/>Total: '.count($data['items']);
                                if($orders_source_id=="lazada-claim"){
                                    redirect(base_url()."admin/transaction/verifikasi_payment_claim?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));
                                } else {
                                    redirect(base_url()."admin/transaction/verifikasi_payment?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));
                                }
                            }
                        }
                    }
                } else {
                    $data['err']  = true;
                    $data['msg'] .= 'Error: ' . $files['error'] . '<br>';
                    redirect(base_url()."admin/transaction/shipping?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));
                }
            }

            $upload_files = glob('./assets/collections/tmp_files/*');
            foreach($upload_files as $file){
                if(is_file($file))
                unlink($file);
            }
        } else {
            redirect(base_url()."admin/transaction/shipping?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));
        }
    }

}
