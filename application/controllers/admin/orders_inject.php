<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class orders_inject extends FrontController {
    function __construct()
    {
        ini_set('precision', '15');
        parent::__construct();
        // $this->_set_action();
        // $this->_set_title( 'Orderan' );
        // $this->DATA->table="mt_orders";
        // $this->folder_view = "orders/";
        // $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_orders","M");
        // $this->load->model("mdl_report","MR");

        $this->own_link = base_url()."admin/orders_inject";
        $this->breadcrumb[] = array(
                "title"     => "Orderan",
                "url"       => $this->own_link
            );

        $this->cat_search = array(
            ''                                         => 'Semua Pencarian...',
            'mt_orders.orders_code'                    => 'No Order',
            'mt_orders.orders_invoice'                 => 'Invoice',
            'mt_orders.orders_source_invoice'          => 'Marketplace Invoice',
            'mt_orders_shipping.orders_shipping_username'  => 'Username',
            'mt_orders_shipping.orders_shipping_name'  => 'Nama Customer',
            'mt_orders_shipping.orders_shipping_email' => 'Email Customer',
            'mt_orders_shipping.orders_shipping_phone' => 'Hp Customer',
        );

        $this->user_id          = isset($this->jCfg['user']['id'])&&$this->jCfg['user']['id']!=''?$this->jCfg['user']['id']:'1';
        $this->store_id         = get_user_store($this->user_id);
        $this->detail_store     = get_detail_store($this->store_id);
        $this->store_name       = $this->detail_store->store_name;
        $this->store_phone      = $this->detail_store->store_phone;
        $this->store_product    = $this->detail_store->store_product;
    }

    function _reset(){
        $this->jCfg['search'] = array(
            'class'     => $this->_getClass(),
            'name'      => 'orders',
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

    function index(){
        // $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        // if($hal != 'orders'){
        //     $this->_reset();
        // }
        $data = array();
        debugCode("Inject");

        // $this->load->view('admin/template/orders/orders_new_orders_inject',$data);
        // redirect($this->own_link.'/new_orders');
    }

    function new_orders(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'orders_new_orders'){
            $this->_reset();
            $this->jCfg['search']['name'] = 'orders_new_orders';
            $this->_releaseSession();
        }

        check_product_by_group();
        // autoClearAppSessions();

        $data = array();

        $this->orders_status = 3;
        $orders_status       = get_orders_status($this->orders_status);
        $name                = $orders_status['name'];
        $url                 = $orders_status['url'];

        $this->breadcrumb[] = array(
            "title"     => $name
        );

        $order_by = $this->jCfg['search']['order_by'];
        $_POST['order_by'] = 'mt_orders.orders_date - desc';
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
            $explode_order_by = explode("-", $_POST['order_by']);
            $this->jCfg['search']['order_by'] = $explode_order_by[0];
            $this->jCfg['search']['order_dir'] = $explode_order_by[1];
            $this->_releaseSession();
        }

        if(!isset($this->jCfg['search']['orders_source_id'])||$this->jCfg['search']['orders_source_id']==''){
            $this->jCfg['search']['orders_source_id'] = '2,3,4,5,6,7,8,9,10,11';
            $this->_releaseSession();
        }
        if(isset($_POST['orders_source_id'])){
            if($_POST['orders_source_id'] == ""){
                $this->jCfg['search']['orders_source_id'] = '2,3,4,5,6,7,8,9,10,11';
            } else {
                $this->jCfg['search']['orders_source_id'] = $_POST['orders_source_id'];
            }
            $this->_releaseSession();
        }
        $this->orders_source_id = $this->jCfg['search']['orders_source_id'];

        if(!isset($this->jCfg['search']['orders_courier_id'])){
            $this->jCfg['search']['orders_courier_id'] = NULL;
            $this->jCfg['search']['orders_child_courier_id'] = NULL;
            $this->_releaseSession();
        }
        if(isset($_POST['orders_courier_id'])){
            if($_POST['orders_courier_id'] == ""){
                $this->jCfg['search']['orders_courier_id'] = NULL;
                $this->jCfg['search']['orders_child_courier_id'] = NULL;
            } else {
                $this->jCfg['search']['orders_courier_id'] = $_POST['orders_courier_id'];
                $get_orders_courier = get_orders_courier($_POST['orders_courier_id'], true);
                $orders_child_courier_id = "";
                $i = 0;
                foreach ($get_orders_courier as $key => $value) {
                    $orders_child_courier_id .= ($i>0?',':'').$value->orders_courier_id;
                    $i += 1;
                }
                $this->jCfg['search']['orders_child_courier_id'] = $orders_child_courier_id;
            }
            $this->_releaseSession();
        }
        $this->orders_child_courier_id = $this->jCfg['search']['orders_child_courier_id'];

        if(!isset($this->jCfg['search']['orders_print'])||$this->jCfg['search']['orders_print']==''){
            $this->jCfg['search']['orders_print'] = '';
            $this->_releaseSession();
        }
        if(isset($_POST['orders_print'])){
            if($_POST['orders_print'] == ""){
                $this->jCfg['search']['orders_print'] = '';
            } else {
                $this->jCfg['search']['orders_print'] = $_POST['orders_print'];
            }
            $this->_releaseSession();
        }
        $this->orders_print = "";
        if($this->jCfg['search']['orders_print'] == "belum"){
            $this->orders_print  = "0";
            $this->orders_status = NULL;
        } else if($this->jCfg['search']['orders_print'] == "sudah"){
            $this->orders_print = "1";
        }

        $par_filter = array(
            "store_id"            => $this->store_id,
            "orders_print"        => $this->orders_print,
            "orders_status"       => $this->orders_status,
            "orders_source_id"    => $this->orders_source_id,
            "orders_courier_id"   => $this->orders_child_courier_id,
            "get_all"             => TRUE,
            "type_result"         => "list",
            "date_start"          => NULL,
            "date_end"            => NULL,
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => NULL,
            "limit"               => NULL,
            "keyword"             => NULL,
            "colum"               => NULL,
            "param"               => NULL
        );

        $data = $this->M->data_orders($par_filter);
        // debugCode($data);

        $data['title'] = "Pesanan Baru";

        $data['own_links']      = $this->own_link;
        $data['url']            = $this->own_link."/new_orders";
        $data['url_form']       = $this->own_link."/new_orders";
        $data['tab']            = 'tab'.$this->orders_status;
        // $data['content_layout'] = $this->prefix_view."_new_orders.php";
        // $this->_v($this->folder_view.$this->prefix_view."_new_orders.php",$data);

        $this->load->view('admin/template/orders/orders_new_orders_inject',$data);
    }

    function empty_tmp_file(){
        $files = glob('./assets/collections/tmp_files/*');
        foreach($files as $file){
            if(is_file($file))
            unlink($file);
        }
        redirect($this->own_link."?msg=".urlencode("Empty file excel successfully...")."&type_msg=success");
    }

    function upload(){
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

            redirect($this->own_link."/new_orders?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));

        }
    }

    function views(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));

            if(trim($thisVal)==''){ $thisVal = 0; }
            $r = $this->db->get_where("mt_orders",array(
                'orders_id '    => $thisVal,
                'store_id '     => $this->store_id
            ),1,0)->row();

            $i = 0;
            $orders_source = '';
            $arr_orders_source = get_orders_source();
            foreach ($arr_orders_source as $k => $v) {
                $selected = (($i=='0')||($v->orders_source_id==$r->orders_source_id)?'selected':'');
                $orders_source .= '<option value="'.$v->orders_source_id.'" '.$selected.'>'.$v->orders_source_name.'</option>';
                $i += 1;
            }

            $rs = get_detail_orders_shipping($r->orders_id);

            $i = 0;
            $shipping_courier = '';
            $get_orders_courier = get_orders_courier();
            foreach ($get_orders_courier as $k => $v) {
                $get_orders_courier2 = get_orders_courier($v->orders_courier_id, true);
                foreach ($get_orders_courier2 as $k2 => $v2) {
                    $shipping_courier .= '<option value="'.$v2->orders_courier_id.'" '.(($i=='0')||($r->orders_courier_id==$v2->orders_courier_id)?'selected':'').'>'.$v->orders_courier_name.' - '.$v2->orders_courier_service.'</option>';
                    $i += 1;
                }
            }

            $data['content'] = '
            <form class="form_save_new_orders" action="'.$this->own_link.'/save_new_orders" method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <legend>Form Alamat Pengiriman</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Orderan Dari</label>
                        <div class="col-sm-4">
                            <select name="orders_source_id" class="form-control" required>
                                '.$orders_source.'
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="orders_source_invoice" value="'.$r->orders_source_invoice.'" class="form-control text-uppercase" placeholder="No Invoice Bukalapak / Tokopedia">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kurir</label>
                        <div class="col-sm-4">
                            <select name="orders_courier_id" class="form-control">
                                <option value="" selected>--- Pilih ----</option>
                                '.$shipping_courier.'
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="orders_product_category_title" value="'.($rs->orders_product_category_title!=''?$rs->orders_product_category_title:$this->store_product).'" class="form-control" placeholder="Isi Paket">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Pengirim</label>
                        <div class="col-sm-4">
                            <input type="text" name="orders_ship_name" value="'.($rs->orders_ship_name!=''?$rs->orders_ship_name:$this->store_name).'" class="form-control" placeholder="'.$this->store_name.'">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="orders_ship_phone" value="'.($rs->orders_ship_phone!=''?$rs->orders_ship_phone:$this->store_phone).'" class="form-control" placeholder="'.$this->store_phone.'">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Penerima</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_name" value="'.$rs->orders_shipping_name.'" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Hp</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_phone" value="'.$rs->orders_shipping_phone.'" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat Lengkap</label>
                        <div class="col-sm-9">
                            <textarea name="orders_shipping_address" class="form-control no-resize" rows="3" maxlength="300" >'.$rs->orders_shipping_address.'</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kode Booking</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_resi" value="'.$rs->orders_shipping_resi.'" class="form-control text-uppercase" placeholder="Kode Booking" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Harga Jual di MP</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_source_price" value="'.$r->orders_source_price.'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                        <label class="col-sm-2 control-label">Asuransi</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_insurance" value="'.$r->orders_price_insurance.'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Estimasi Ongkir</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_shipping" value="'.$r->orders_price_shipping.'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                        <label class="col-sm-2 control-label">Berat</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="orders_shipping_weight" value="'.$rs->orders_shipping_weight.'" class="form-control" maxlength="2" placeholder="1">
                                <span class="input-group-addon">Kg</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Catatan Order (optional)</label>
                        <div class="col-sm-9">
                            <textarea name="orders_noted" class="form-control no-resize" rows="3" maxlength="300" >'.$r->orders_noted.'</textarea>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="hidden" name="orders_id" value="'.$r->orders_id.'" />
                            <input type="hidden" name="orders_voucher_price" value="'.($r->orders_voucher_price!=""?$r->orders_voucher_price:0).'" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                        </div>
                    </div>
                </div>
            </form>
            ';

        }

        die(json_encode($data));
        exit();
    }

    function form_detail_courier(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));

            if(trim($thisVal)==''){ $thisVal = 0; }
            $r = $this->db->get_where("mt_orders",array(
                'orders_id '    => $thisVal,
                'store_id '     => $this->store_id
            ),1,0)->row();

            $rs = get_detail_orders_shipping($r->orders_id);

            $data['content'] = '
            <form class="form_save_detail_courier" action="'.$this->own_link.'/save_detail_courier" method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <legend>'.$r->orders_source_invoice.'</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Pengirim</label>
                        <div class="col-sm-4">
                            <input type="text" name="orders_ship_name" value="'.($rs->orders_ship_name!=''?$rs->orders_ship_name:$this->store_name).'" class="form-control" placeholder="'.$this->store_name.'">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="orders_ship_phone" value="'.($rs->orders_ship_phone!=''?$rs->orders_ship_phone:$this->store_phone).'" class="form-control" placeholder="'.$this->store_phone.'">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Penerima</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_name" value="'.$rs->orders_shipping_name.'" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Isi Paket</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_product_category_title" value="'.($rs->orders_product_category_title!=''?$rs->orders_product_category_title:$this->store_product).'" class="form-control" placeholder="Isi Paket">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kode Booking</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_resi" value="'.$rs->orders_shipping_resi.'" class="form-control text-uppercase" placeholder="Kode Booking" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Berat</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="orders_shipping_weight" value="'.$rs->orders_shipping_weight.'" class="form-control" maxlength="2" placeholder="1">
                                <span class="input-group-addon">Kg</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="hidden" name="orders_id" value="'.$r->orders_id.'" />
                            <input type="hidden" name="orders_source_id" value="'.$r->orders_source_id.'" />
                            <input type="hidden" name="orders_courier_id" value="'.$r->orders_courier_id.'" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                        </div>
                    </div>
                </div>
            </form>
            ';

        }

        die(json_encode($data));
        exit();
    }


    function save_new_orders(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){

            $orders_shipping_dropship = 0;
            if($this->store_name != dbClean($_POST['orders_ship_name']) ){
                $orders_shipping_dropship = 1;
                // if($this->store_id == 1){
                //     $param = array('member_phone' => dbClean($_POST['orders_ship_phone']));
                //     $search_isreseller = get_search_data_member($param);
                //     if(count($search_isreseller) == 0){
                //         $dataMember = array(
                //             'member_name'           => dbClean(ucwords($_POST['orders_ship_name'])),
                //             'member_username'       => generateUniqueUsername(dbClean($_POST['orders_ship_name'])),
                //             'member_phone'          => dbClean($_POST['orders_ship_phone']),
                //             'member_status'         => 0,
                //             'member_date'           => timestamp(),
                //             'member_isreseller'     => 1,
                //             'member_istrash'        => 0
                //         );

                //         $this->DATA->table="mt_member";
                //         $a = $this->_save_master(
                //             $dataMember,
                //             array(
                //                 'member_id' => ''
                //                 ),
                //             ''
                //             );
                //     }
                // }
            }

            $orders_price_grand_total = 0;
            $data2 = array(
                'orders_source_id'              => dbClean($_POST['orders_source_id']),
                'orders_source_invoice'         => dbClean(strtoupper($_POST['orders_source_invoice'])),
                'orders_source_payment'         => 0,
                'orders_source_price'           => dbClean(convertRpToInt($_POST['orders_source_price'])),
                'orders_price_product'          => dbClean(convertRpToInt($_POST['orders_source_price'])),
                'orders_price_insurance'        => dbClean(convertRpToInt($_POST['orders_price_insurance'])),
                'orders_price_shipping'         => dbClean(convertRpToInt($_POST['orders_price_shipping'])),
                'orders_voucher_price'          => dbClean(convertRpToInt($_POST['orders_voucher_price'])),
                'orders_price_grand_total'      => $orders_price_grand_total,
                'orders_courier_id'             => dbClean($_POST['orders_courier_id']),
                'orders_noted'                  => $_POST['orders_noted']
            );
            if($data2['orders_price_product'] > 0){
                $orders_price_grand_total = ($data2['orders_price_product'] + $data2['orders_price_shipping'] + $data2['orders_price_insurance']) - $data2['orders_voucher_price'];
                $isPriceDebetCourier= isPriceDebetCourier($data2['orders_source_id'], $data2['orders_courier_id']);
                if($isPriceDebetCourier){ $orders_price_grand_total = $data2['orders_price_product'] - $data2['orders_voucher_price']; }
                $data2['orders_price_grand_total'] = $orders_price_grand_total;
            }

            if(isset($_POST['orders_id']) && $_POST['orders_id'] == ''){
                $create_orders_code      = create_orders_code();
                $data2['orders_code']    = $create_orders_code['orders_code'];
                $data2['orders_invoice'] = $create_orders_code['orders_invoice'];
                $data2['member_type']    = 2;
                $data2['member_id']      = $this->user_id;
                $data2['store_id']       = $this->store_id;
                $data2['orders_status']  = 3;
                $data2['orders_print']   = 0;
                if($data2['orders_source_id'] == 11){ $data2['orders_print'] = 1; }
                $data2['orders_product_detail'] = 0;
                $data2['ip_address']     = $_SERVER['REMOTE_ADDR'];
                $data2['user_agent']     = $_SERVER['HTTP_USER_AGENT'];
                $data2['notify']         = 0;
                $data2['date_notify']    = timestamp();
                $data2['orders_date']    = timestamp();
            }

            $this->DATA->table="mt_orders";
            $a2 = $this->_save_master(
                $data2,
                array(
                    'orders_id' => dbClean($_POST['orders_id'])
                    ),
                dbClean($_POST['orders_id'])
                );

            $id = $a2['id'];

            $data4 = array(
                'orders_payment_grand_total'    => $orders_price_grand_total
            );
            if(isset($_POST['orders_id']) && $_POST['orders_id'] == ''){
                $create_payment_code = create_payment_code();
                $data4['orders_payment_code']   = $create_payment_code['payment_code'];
                $data4['orders_payment_method'] = 3;
                $data4['orders_id']             = $id;
                $data4['orders_payment_price']  = 0;
                $data4['orders_payment_status'] = 2;
                $data4['notify']                = 0;
                $data4['date_notify']           = timestamp();
                $data4['orders_payment_date']   = timestamp();
            }
            $this->DATA->table="mt_orders_payment";
            $a4 = $this->_save_master(
                $data4,
                array(
                    'orders_id' => dbClean($_POST['orders_id'])
                    ),
                dbClean($_POST['orders_id'])
                );

            $data5 = array(
                'orders_product_category_title' => dbClean($_POST['orders_product_category_title']),
                'orders_shipping_name'          => dbClean(strtoupper($_POST['orders_shipping_name'])),
                'orders_shipping_address'       => dbClean(ucwords($_POST['orders_shipping_address'])),
                'orders_shipping_phone'         => dbClean($_POST['orders_shipping_phone']),
                'orders_shipping_resi'          => dbClean(strtoupper($_POST['orders_shipping_resi'])),
                'orders_shipping_weight'        => dbClean($_POST['orders_shipping_weight'])
            );
            if($orders_shipping_dropship == 1){
                $data5['orders_shipping_dropship'] = 1;
                $data5['orders_ship_name']         = dbClean(ucwords($_POST['orders_ship_name']));
                $data5['orders_ship_phone']        = dbClean($_POST['orders_ship_phone']);
            }
            if(isset($_POST['orders_id']) && $_POST['orders_id'] == ''){
                $data5['orders_id']              = $id;
                $data5['orders_shipping_status'] = 3;
                $data5['orders_shipping_method'] = 1;
                $data5['orders_shipping_price']  = NULL;
                $data5['notify']                 = 0;
                $data5['date_notify']            = timestamp();
                $data5['orders_shipping_date']   = timestamp();
            }

            $this->DATA->table="mt_orders_shipping";
            $a5 = $this->_save_master(
                $data5,
                array(
                    'orders_id' => dbClean($_POST['orders_id'])
                    ),
                dbClean($_POST['orders_id'])
                );

            if(isset($_POST['orders_id']) && $_POST['orders_id'] == ''){
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
                        'orders_id' => dbClean($_POST['orders_id'])
                        ),
                    dbClean($_POST['orders_id'])
                );
            }

            $data['err'] = false;
            $data['msg'] = 'Sukses menyimpan data.';

        }

        die(json_encode($data));
        exit();
    }

    function save_detail_courier(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){

            $orders_shipping_dropship = 0;
            if($this->store_name != dbClean($_POST['orders_ship_name']) ){
                $orders_shipping_dropship = 1;
            }

            $data2 = array(
                'orders_source_id'              => dbClean($_POST['orders_source_id']),
                'orders_courier_id'             => dbClean($_POST['orders_courier_id']),
                'orders_print'                  => 0,
                'date_notify'                   => timestamp()
            );

            $this->DATA->table="mt_orders";
            $a2 = $this->_save_master(
                $data2,
                array(
                    'orders_id' => dbClean($_POST['orders_id'])
                    ),
                dbClean($_POST['orders_id'])
                );

            $id = $a2['id'];

            $data5 = array(
                'orders_product_category_title' => dbClean($_POST['orders_product_category_title']),
                'orders_shipping_name'          => dbClean(strtoupper($_POST['orders_shipping_name'])),
                'orders_shipping_resi'          => dbClean(strtoupper($_POST['orders_shipping_resi'])),
                'orders_shipping_weight'        => dbClean($_POST['orders_shipping_weight']),
                'date_notify'                   => timestamp()
            );
            if($orders_shipping_dropship == 1){
                $data5['orders_shipping_dropship'] = 1;
                $data5['orders_ship_name']         = dbClean(ucwords($_POST['orders_ship_name']));
                $data5['orders_ship_phone']        = dbClean($_POST['orders_ship_phone']);
            }
            $this->DATA->table="mt_orders_shipping";
            $a5 = $this->_save_master(
                $data5,
                array(
                    'orders_id' => dbClean($_POST['orders_id'])
                    ),
                dbClean($_POST['orders_id'])
                );

            $data['err'] = false;
            $data['msg'] = 'Sukses menyimpan data.';

        }

        die(json_encode($data));
        exit();
    }

}
