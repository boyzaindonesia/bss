<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class orders extends FrontController {
    var $cur_menu = '';

    function __construct()
    {
        parent::__construct();

        $this->DATA->table = "mt_orders";
        $this->load->model("mdl_orders","M");

        // $this->upload_path="./assets/collections/product/";
        // $this->upload_resize  = array(
        //  array('name'    => 'thumb','width'  => 100, 'quality'   => '90%'),
        //  array('name'    => 'small','width'  => 350, 'quality'   => '90%'),
        //  array('name'    => 'large','width'  => 800, 'quality'   => '90%')
        // );
    }

    function index(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id                = mysql_real_escape_string($request->user_id);
            $store_id               = mysql_real_escape_string($request->store_id);
            $token                  = mysql_real_escape_string($request->token);
            $thisAction             = mysql_real_escape_string($request->thisAction);

            $type_result            = mysql_real_escape_string($request->type_result);
            $date_start             = mysql_real_escape_string($request->date_start);
            $date_end               = mysql_real_escape_string($request->date_end);
            $order_by               = mysql_real_escape_string($request->order_by);
            $order_dir              = mysql_real_escape_string($request->order_dir);
            $offset                 = mysql_real_escape_string($request->offset);
            $limit                  = mysql_real_escape_string($request->limit);
            $keyword                = mysql_real_escape_string($request->keyword);

            $orders_id        = mysql_real_escape_string($request->orders_id);
            $orders_status_id = mysql_real_escape_string($request->orders_status_id);
            $orders_source_id = "";
            $orders_child_courier_id = "";

            if($thisAction == 'getdata'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){

                    $colum = "";
                    $param = array(
                        ''                                         => 'Semua Pencarian...',
                        'mt_orders.orders_code'                    => 'No Order',
                        'mt_orders.orders_invoice'                 => 'Invoice',
                        'mt_orders.orders_source_invoice'          => 'Marketplace Invoice',
                        'mt_orders_shipping.orders_shipping_name'  => 'Nama Customer',
                        'mt_orders_shipping.orders_shipping_email' => 'Email Customer',
                        'mt_orders_shipping.orders_shipping_phone' => 'Hp Customer'
                    );

                    $par_filter = array(
                        "store_id"            => ($store_id!=""?$store_id:''),
                        "orders_id"           => ($orders_id!=""?$orders_id:''),
                        "orders_status"       => ($orders_status_id!=""?$orders_status_id:''),
                        "orders_source_id"    => ($orders_source_id!=""?$orders_source_id:''),
                        "orders_courier_id"   => ($orders_child_courier_id!=""?$orders_child_courier_id:''),
                        "get_all"             => TRUE,
                        "type_result"         => ($type_result!=""?$type_result:''),
                        "date_start"          => ($date_start!=""?$date_start:''),
                        "date_end"            => ($date_end!=""?$date_end:''),
                        "order_by"            => ($order_by!=""?$order_by:''),
                        "order_dir"           => ($order_dir!=""?$order_dir:''),
                        "offset"              => ($offset!=0?$offset:0),
                        "limit"               => ($limit!=0?$limit:1000),
                        "keyword"             => ($keyword!=""?$keyword:''),
                        "colum"               => ($colum!=""?$colum:''),
                        "param"               => ($param!=""?$param:NULL)
                    );

                    $data   = $this->M->data_orders($par_filter);
                    $result = $data['data'];
                    $total  = $data['total'];
                    $error  = false;
                    $msg    = "Transaksi ditemukan..";
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $rows['numrows']  = $total;
        $rows['result']   = $result;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function load_input_new_orders(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $reseller_id = "";
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id            = mysql_real_escape_string($request->user_id);
            $store_id           = mysql_real_escape_string($request->store_id);
            $token              = mysql_real_escape_string($request->token);
            $thisAction         = mysql_real_escape_string($request->thisAction);

            // $orders_status      = mysql_real_escape_string($request->orders_status);
            // $type_result        = mysql_real_escape_string($request->type_result);
            // $order_by           = mysql_real_escape_string($request->order_by);
            // $order_dir          = mysql_real_escape_string($request->order_dir);

            if($thisAction == 'getdata'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){
                    if($cek_user->user_group == 5){ $reseller_id = get_user_store($user_id); }

                    $colum = "";
                    $param = NULL;

                    $i = 0;
                    $resultTotal = 0;
                    $resultData  = array();
                    if($store_id == 1){
                        // BUTIK SASHA
                        $par_filter = array(
                            "store_id"              => "1",
                            "orders_status"         => "3,4,5",
                            "orders_product_detail" => "0",
                            "get_all"               => FALSE,
                            "type_result"           => "list_app_simple",
                            "order_by"              => "orders_shipping_name",
                            "order_dir"             => "asc",
                            "offset"                => 0,
                            "limit"                 => 1000,
                            "param"                 => NULL
                        );
                        $data   = $this->M->data_orders($par_filter);
                        foreach ($data['data'] as $key => $val) {
                            $resultData[$i]->orders_id = $val->orders_id;
                            $resultData[$i]->store_id  = $val->store_id;
                            $resultData[$i]->orders_source_name = $val->orders_source_name;
                            $resultData[$i]->orders_date = $val->orders_date;
                            $resultData[$i]->orders_source_id = $val->orders_source_id;
                            $resultData[$i]->orders_source_invoice = $val->orders_source_invoice;
                            $resultData[$i]->orders_noted = $val->orders_noted;
                            $resultData[$i]->orders_shipping_name = $val->orders_shipping_name;
                            $resultData[$i]->orders_name = ($i + 1).'. '.getDateMonth($val->orders_date).' '.ucwords($val->orders_shipping_name).' ('.substr($val->orders_source_invoice, -3).' '.$val->orders_source_name.' '.$val->orders_courier_name.')';
                            $i += 1;
                        }
                        $resultTotal += $data['total'];

                        // PIXELTEN
                        $store_code = get_detail_store(2)->store_code;
                        $par_filter = array(
                            "store_id"              => "2",
                            "orders_status"         => "3,4,5",
                            "orders_product_detail" => "0",
                            "get_all"               => FALSE,
                            "type_result"           => "list_app_simple",
                            "order_by"              => "orders_shipping_name",
                            "order_dir"             => "asc",
                            "offset"                => 0,
                            "limit"                 => 1000,
                            "param"                 => NULL
                        );
                        $data   = $this->M->data_orders($par_filter);
                        foreach ($data['data'] as $key => $val) {
                            $resultData[$i]->orders_id = $val->orders_id;
                            $resultData[$i]->store_id  = $val->store_id;
                            $resultData[$i]->orders_source_name = $val->orders_source_name;
                            $resultData[$i]->orders_date = $val->orders_date;
                            $resultData[$i]->orders_source_id = $val->orders_source_id;
                            $resultData[$i]->orders_source_invoice = $val->orders_source_invoice;
                            $resultData[$i]->orders_noted = $val->orders_noted;
                            $resultData[$i]->orders_shipping_name = $val->orders_shipping_name;
                            $resultData[$i]->orders_name = ($i + 1).'. '.getDateMonth($val->orders_date).' '.strtoupper($store_code).' '.ucwords($val->orders_shipping_name).' ('.substr($val->orders_source_invoice, -3).' '.$val->orders_source_name.' '.$val->orders_courier_name.')';
                            $i += 1;
                        }
                        $resultTotal += $data['total'];

                        $result = $resultData;
                        $total  = $resultTotal;
                    } else {
                        $par_filter = array(
                            "store_id"            => ($store_id!=""?$store_id:''),
                            "orders_status"         => "3,4,5",
                            "orders_product_detail" => "0",
                            "get_all"               => FALSE,
                            "type_result"           => "list_app_simple",
                            "order_by"              => "orders_shipping_name",
                            "order_dir"             => "asc",
                            "offset"                => 0,
                            "limit"                 => 1000,
                            "param"                 => NULL
                        );
                        $data   = $this->M->data_orders($par_filter);
                        foreach ($data['data'] as $key => $val) {
                            $resultData[$i]->orders_id = $val->orders_id;
                            $resultData[$i]->store_id  = $val->store_id;
                            $resultData[$i]->orders_source_name = $val->orders_source_name;
                            $resultData[$i]->orders_date = $val->orders_date;
                            $resultData[$i]->orders_source_id = $val->orders_source_id;
                            $resultData[$i]->orders_source_invoice = $val->orders_source_invoice;
                            $resultData[$i]->orders_noted = $val->orders_noted;
                            $resultData[$i]->orders_shipping_name = $val->orders_shipping_name;
                            $resultData[$i]->orders_name = ($i + 1).'. '.getDateMonth($val->orders_date).' '.ucwords($val->orders_shipping_name).' ('.substr($val->orders_source_invoice, -3).' '.$val->orders_source_name.' '.$val->orders_courier_name.')';
                            $i += 1;
                        }
                        $resultTotal += $data['total'];

                        $result = $resultData;
                        $total  = $resultTotal;
                    }

                    $error  = false;
                    $msg    = "Orderan ditemukan..";
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $newOrdersSource = array();
        $arrOrdersSource = array('3','8','2','11','7','5','6','4','9','10','1');
        foreach ($arrOrdersSource as $k => $v) {
            $val = get_orders_source($v);
            $newOrdersSource[$k]['orders_source_id']   = $val->orders_source_id;
            $newOrdersSource[$k]['orders_source_name'] = $val->orders_source_name;
        }

        $newOrdersBooked = array();
        $cek_booked = $this->db->get_where("mt_temp_orders",array(
            "member_type"   => 1,
            "store_id"      => $store_id
        ))->result();
        if(count($cek_booked) > 0){
            foreach ($cek_booked as $key => $val) {
                $newOrdersBooked[$key] = array(
                            'temp_orders_id'           => $val->temp_orders_id,
                            'temp_orders_date'         => $val->temp_orders_date,
                            'orders_source_name'       => get_orders_source($val->orders_source_id)->orders_source_name,
                            'orders_source_invoice'    => $val->orders_source_invoice
                            // 'product_detail_item'      => json_decode($val->product_detail_item)
                        );
            }

        }

        $rows['numrows']  = $total;
        $rows['result']   = $result;
        $rows['barcode']  = get_list_barcode_product("", $reseller_id, "1");
        $rows['orders_source'] = $newOrdersSource;
        $rows['orders_booked'] = $newOrdersBooked;

        $count_new_orders_bss  = count(get_detail_orders_by_source('1', '3', '1,2,3,4,5,6,7,8,9,10,11'));
        $count_new_orders_pxl  = count(get_detail_orders_by_source('2', '3', '1,2,3,4,5,6,7,8,9,10,11'));
        $rows['html_count_orders_top'] = '
            <ul style="list-style:none;margin:0;padding:0;width:100%;vertical-align:top;text-align:center;">
                <li style="width: 49%;display:inline-block;">
                    <div style="font-size: 10px;">Butik Sasha</div>
                    <div>Perlu Dikirim</div>
                    <div>'.$count_new_orders_bss.'</div>
                </li>
                <li style="width: 49%;display:inline-block;">
                    <div style="font-size: 10px;">Pixelten</div>
                    <div>Perlu Dikirim</div>
                    <div>'.$count_new_orders_pxl.'</div>
                </li>
            </ul>';

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function get_orders_booked(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id                = mysql_real_escape_string($request->user_id);
            $store_id               = mysql_real_escape_string($request->store_id);
            $token                  = mysql_real_escape_string($request->token);
            $thisAction             = mysql_real_escape_string($request->thisAction);

            $type_result            = mysql_real_escape_string($request->type_result);
            $keyword                = mysql_real_escape_string($request->keyword);
            $date_start             = mysql_real_escape_string($request->date_start);
            $date_end               = mysql_real_escape_string($request->date_end);
            $offset                 = mysql_real_escape_string($request->offset);
            $limit                  = mysql_real_escape_string($request->limit);

            $orders_source_id = "";
            if($thisAction == 'getdata'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){

                    $colum = "";
                    $param = array(
                        ''                             => 'Semua Pencarian...',
                        'orders_source_invoice'        => 'Marketplace Invoice'
                    );

                    $par_filter = array(
                        "store_id"            => ($store_id!=""?$store_id:''),
                        "orders_source_id"    => $orders_source_id,
                        "get_all"             => TRUE,
                        "type_result"         => ($type_result!=""?$type_result:''),
                        "date_start"          => ($date_start!=""?$date_start:''),
                        "date_end"            => ($date_end!=""?$date_end:''),
                        "order_by"            => ($order_by!=""?$order_by:''),
                        "order_dir"           => ($order_dir!=""?$order_dir:''),
                        "offset"              => ($offset!=0?$offset:0),
                        "limit"               => ($limit!=0?$limit:1000),
                        "colum"               => ($colum!=""?$colum:''),
                        "keyword"             => ($keyword!=""?$keyword:''),
                        "param"               => ($param!=""?$param:NULL)
                    );

                    $data   = $this->M->data_orders_booked($par_filter);
                    $result = $data['data'];
                    $total  = $data['total'];
                    $error  = false;
                    $msg    = "Booked ditemukan..";
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $orders_not_product = get_orders_product_detail($store_id, 0);

        $rows['numrows']  = $total;
        $rows['result']   = $result;
        $rows['orders_not_product'] = $orders_not_product;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function checkout(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        $booked_name = "";
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id                = mysql_real_escape_string($request->user_id);
            $store_id               = mysql_real_escape_string($request->store_id);
            $orders_id              = mysql_real_escape_string($request->orders_id);
            $orders_source_id       = mysql_real_escape_string($request->orders_source_id);
            $orders_source_invoice  = mysql_real_escape_string($request->orders_source_invoice);
            $orders_booked_id       = mysql_real_escape_string($request->orders_booked_id);
            // $orders_cart             = mysql_real_escape_string($request->orders_cart);
            $token                  = mysql_real_escape_string($request->token);
            $thisAction             = mysql_real_escape_string($request->thisAction);

            $orders_cart = array();
            foreach ($request as $key => $value) {
                if($key == "orders_cart"){
                    $orders_cart = $value;
                }
            }

            if($thisAction == 'checkout'){
                $insertOrdersDetail = false;
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){
                    if($orders_id != ""){
                        if($orders_booked_id == ""){
                            if($orders_id == "quick_buy"){
                                $create_orders_code = create_orders_code();
                                $data = array(
                                    'orders_code'              => $create_orders_code['orders_code'],
                                    'orders_invoice'           => $create_orders_code['orders_invoice'],
                                    'member_type'              => 2,
                                    'member_id'                => $user_id,
                                    'store_id'                 => $store_id,
                                    'orders_status'            => 8,
                                    'orders_source_id'         => $orders_source_id,
                                    'orders_source_invoice'    => NULL,
                                    'orders_source_payment'    => 0,
                                    'orders_price_product'     => 0,
                                    'orders_price_insurance'   => 0,
                                    'orders_price_shipping'    => 0,
                                    'orders_price_grand_total' => 0,
                                    'orders_courier_id'        => 0,
                                    'orders_noted'             => NULL,
                                    'orders_print'             => 1,
                                    'orders_product_detail'    => 0,
                                    'ip_address'               => $_SERVER['REMOTE_ADDR'],
                                    'user_agent'               => $_SERVER['HTTP_USER_AGENT'],
                                    'notify'                   => 0,
                                    'date_notify'              => timestamp(),
                                    'orders_date'              => timestamp()
                                );

                                $this->DATA->table="mt_orders";
                                $save = $this->_save_master(
                                    $data,
                                    array(
                                        'orders_id' => ""
                                        ),
                                    ""
                                    );

                                $orders_id = $save['id'];
                                if($orders_id != ""){
                                    $create_payment_code = create_payment_code();
                                    $data2 = array(
                                        'orders_payment_code'   => $create_payment_code['payment_code'],
                                        'orders_payment_method' => 3,
                                        'orders_id'             => $orders_id,
                                        'orders_payment_price'  => 0,
                                        'orders_payment_status' => 3,
                                        'notify'                => 0,
                                        'date_notify'           => timestamp(),
                                        'orders_payment_date'   => timestamp()
                                    );

                                    $this->DATA->table="mt_orders_payment";
                                    $save2 = $this->_save_master(
                                        $data2,
                                        array(
                                            'orders_id' => $orders_id
                                            ),
                                        $orders_id
                                        );

                                    $data3 = array(
                                        'orders_id'                     => $orders_id,
                                        'orders_shipping_status'        => 8,
                                        'orders_shipping_method'        => 2,
                                        'orders_shipping_price'         => NULL,
                                        'orders_product_category_title' => NULL,
                                        'orders_shipping_name'          => strtoupper($orders_source_invoice),
                                        'orders_shipping_address'       => NULL,
                                        'orders_shipping_phone'         => NULL,
                                        'orders_shipping_resi'          => NULL,
                                        'orders_shipping_weight'        => 1,
                                        'notify'                        => 0,
                                        'date_notify'                   => timestamp(),
                                        'orders_shipping_date'          => timestamp()
                                    );

                                    $this->DATA->table="mt_orders_shipping";
                                    $save3 = $this->_save_master(
                                        $data3,
                                        array(
                                            'orders_id' => $orders_id
                                            ),
                                        $orders_id
                                        );

                                    $arr_orders_timestamp   = array();
                                    $arr_orders_timestamp[] = array("id" => "3", "date" => timestamp() );
                                    $arr_orders_timestamp[] = array("id" => "8", "date" => timestamp() );

                                    $data4 = array(
                                        'orders_id'                 => $orders_id,
                                        'orders_timestamp_desc'     => json_encode($arr_orders_timestamp)
                                    );
                                    $this->DATA->table="mt_orders_timestamp";
                                    $save4 = $this->_save_master(
                                        $data4,
                                        array(
                                            'orders_id' => $orders_id
                                            ),
                                        $orders_id
                                    );
                                }
                            }

                            $arr_cart  = array();
                            $i = 0;
                            $totalQty  = 0;
                            $json_cart = json_decode($orders_cart);
                            foreach ($json_cart as $key1 => $val1) {
                                $totalQty += $val1->qty;
                                $arr_cart[$i] = array('id'              => $val1->id,
                                                     'code'             => $val1->code,
                                                     'name'             => $val1->name,
                                                     'images'           => $val1->images,
                                                     'price_sale'       => $val1->price_sale,
                                                     'price_discount'   => $val1->price_discount,
                                                     'price_grosir'     => $val1->price_grosir,
                                                     'qty'              => $val1->qty,
                                                     'stock_detail'     => $val1->stock_detail
                                                  );
                                if($val1->stock_detail != "null"){
                                    $arr_detail    = array();
                                    $arr_cart_item = array();
                                    $ii = 0;
                                    foreach ($val1->stock_detail as $key2 => $val2) {
                                        if($val2->qty > 0){
                                            $arr_detail[$ii] = array('id'    => $val2->id,
                                                                     'name'  => $val2->name,
                                                                     'qty'   => $val2->qty
                                                      );

                                            $arr_cart_item[$val2->id] = $val2->qty;
                                            $ii += 1;
                                        }
                                    }
                                    $arr_cart[$i]['stock_detail'] = $arr_detail;
                                }

                                $log_title = NULL;
                                if($orders_id == "booking"){ $log_title = strtoupper($orders_source_invoice); }

                                // PENGURANGAN STOK
                                $product_id = $val1->id;
                                $appLog = check_log_item_stock("2",getYearMonthDate(timestamp()),$product_id,"11",$log_title,$orders_id);
                                if(count($appLog) == 0){
                                    $detail = $this->db->get_where("mt_product_detail",array(
                                        'product_id'    => $product_id
                                    ),1,0)->row();
                                    if(count($detail) > 0){
                                        $dataDetail = array();
                                        $dataDetail['product_status_id'] = $detail->product_status_id;
                                        $new_stock = ($detail->product_stock - $val1->qty);
                                        $dataDetail['product_stock'] = $new_stock;
                                        if($new_stock < 1){
                                            $dataDetail['product_status_id'] = 3;
                                            writeLog(array(
                                                'log_user_type'     => "1", // Admin
                                                'log_user_id'       => $user_id,
                                                'log_role'          => NULL,
                                                'log_type'          => "2", // Produk
                                                'log_detail_id'     => $product_id,
                                                'log_detail_item'   => NULL,
                                                'log_detail_qty'    => $new_stock,
                                                'log_title_id'      => "4", // Produk Soldout Otomatis
                                                'log_desc'          => NULL,
                                                'log_status'        => "0"
                                            ));
                                            sendProductNotif(array(
                                                'user_id'       => $user_id,
                                                'store_id'      => $store_id,
                                                'product_id'    => $product_id,
                                                'product_item'  => NULL,
                                                'product_qty'   => $new_stock,
                                                'notif_title'   => "Produk Soldout Otomatis",
                                                'notif_desc'    => "",
                                                'notif_status'  => 1,
                                                'notif_notify'  => 3
                                            ));
                                        }

                                        $log_item = "";
                                        if($detail->product_stock_detail != ""){
                                            $found_sold = false;
                                            $arr_stock  = array();
                                            $arr_item   = array();
                                            $product_stock_detail = json_decode($detail->product_stock_detail);
                                            foreach ($product_stock_detail as $key3 => $val3) {
                                                $new_qty = $val3->qty;
                                                if(array_key_exists($val3->id, $arr_cart_item)){
                                                    $new_qty = ($new_qty - $arr_cart_item[$val3->id]);
                                                    if($new_qty < 1){
                                                        $found_sold = true;
                                                    }
                                                }
                                                $arr_stock[] = array('id'       => $val3->id,
                                                                     'name'     => $val3->name,
                                                                     'color'    => $val3->color,
                                                                     'qty'      => $new_qty,
                                                                     'status'   => ($new_qty > 0?1:2)
                                                                  );

                                                $arr_item[] = array('id'        => $val3->id,
                                                                    'name'      => $val3->name,
                                                                    'qty_old'   => $val3->qty,
                                                                    'qty_new'   => $new_qty,
                                                                    'status'    => ($new_qty > 0?1:2)
                                                                );

                                            }
                                            $dataDetail['product_stock_detail'] = json_encode($arr_stock);
                                            $log_item  = json_encode($arr_item);

                                            if($found_sold){
                                                sendProductNotif(array(
                                                    'user_id'       => $user_id,
                                                    'store_id'      => $store_id,
                                                    'product_id'    => $product_id,
                                                    'product_item'  => $log_item,
                                                    'product_qty'   => $new_stock,
                                                    'notif_title'   => "Variasi produk sudah habis",
                                                    'notif_desc'    => "",
                                                    'notif_status'  => 1,
                                                    'notif_notify'  => 3
                                                ));
                                            }
                                        }
                                        writeLog(array(
                                            'log_user_type'     => "1", // Admin
                                            'log_user_id'       => $user_id,
                                            'log_role'          => NULL,
                                            'log_type'          => "2", // Produk
                                            'log_detail_id'     => $product_id,
                                            'log_detail_item'   => $log_item,
                                            'log_detail_qty'    => $val1->qty,
                                            'log_title_id'      => "11", // Pengurangan Stok Otomatis
                                            'log_title'         => $log_title,
                                            'log_desc'          => $orders_id,
                                            'log_status'        => "0"
                                        ));
                                        $this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

                                        update_product_sold($product_id, $val1->qty, "plus");
                                    }
                                }
                                $i += 1;
                            }

                            if($totalQty > 0){
                                $product_detail_item = json_encode($arr_cart);
                                if($orders_id == "booking"){ // SIMPAN KE TEMP ORDER
                                    $insertOrdersDetail = false;
                                    $data = array(
                                        'member_type'               => 1,
                                        'member_id'                 => $user_id,
                                        'store_id'                  => $store_id,
                                        'orders_source_id'          => $orders_source_id,
                                        'orders_source_invoice'     => strtoupper($orders_source_invoice),
                                        'orders_booked'             => 1,
                                        'product_detail_item'       => $product_detail_item,
                                        'temp_orders_date'          => timestamp(),
                                        'ip_address'                => $_SERVER['REMOTE_ADDR'],
                                        'user_agent'                => $_SERVER['HTTP_USER_AGENT']
                                    );

                                    $this->DATA->table = "mt_temp_orders";
                                    $save = $this->_save_master(
                                        $data,
                                        array(
                                            'temp_orders_id' => ""
                                        ),
                                        ""
                                    );
                                    $id = $save['id'];
                                    if($id != ''){
                                        writeLog(array(
                                            'log_user_type'     => "1", // Admin
                                            'log_user_id'       => $user_id,
                                            'log_role'          => NULL,
                                            'log_type'          => "4", // Orders
                                            'log_detail_id'     => $id,
                                            'log_detail_item'   => $product_detail_item,
                                            'log_detail_qty'    => $totalQty,
                                            'log_title_id'      => "33", // Berhasil Booking
                                            'log_desc'          => $orders_cart,
                                            'log_status'        => "0"
                                        ));
                                        $error  = false;
                                        $msg    = "Berhasil Simpan ke Booking Pesanan.";
                                    } else {
                                        $error  = true;
                                        $msg    = "Gagal simpan data booking pesanan.";
                                    }
                                } else {
                                    $insertOrdersDetail  = true;
                                    $product_detail_item = $product_detail_item;
                                }
                            } else {
                                $error  = true;
                                $msg    = "Keranjang belanja masih kosong.";
                            }
                        } else { // AMBIL DARI TEMP ORDERS
                            $tmp = $this->db->get_where("mt_temp_orders",array(
                                'temp_orders_id'    => $orders_booked_id
                            ),1,0)->row();
                            if(count($tmp) > 0){
                                $orders = $this->db->get_where("mt_orders",array(
                                    'orders_id' => $orders_id
                                ),1,0)->row();
                                if(count($orders) > 0){
                                    $insertOrdersDetail  = true;
                                    $booked_name         = $tmp->orders_source_invoice;
                                    $product_detail_item = $tmp->product_detail_item;

                                    $log = $this->db->get_where("mt_app_log",array(
                                        'log_type'      => "4",
                                        'log_title_id'  => "33",
                                        'log_detail_id' => $tmp->temp_orders_id
                                    ),1,0)->row();
                                    if(count($log) > 0){
                                        $this->db->update("mt_app_log",array("log_istrash"=>1),array("log_id"=>$log->log_id));
                                    }
                                    $this->db->delete("mt_temp_orders",array('temp_orders_id' => $tmp->temp_orders_id));
                                } else {
                                    $error  = true;
                                    $msg    = "Nama Penerima tidak ditemukan.";
                                }
                            } else {
                                $error  = true;
                                $msg    = "Booking pesanan tidak ditemukan.";
                            }
                        }

                        if($insertOrdersDetail){ // SIMPAN KE ORDER DETAIL
                            $log_item   = $product_detail_item;
                            $log_qty    = 0;
                            $totalBuy   = 0;
                            $totalPrice = 0;
                            $product_detail_item = json_decode($product_detail_item);
                            foreach ($product_detail_item as $key1 => $val1) {
                                $log_qty   += $val1->qty;
                                $product_id = $val1->id;
                                $product = $this->db->get_where("mt_product",array(
                                    'product_id'    => $product_id
                                ),1,0)->row();
                                if(count($product) > 0){

                                    $orders_detail_id = NULL;
                                    $orders_detail = $this->db->get_where("mt_orders_detail",array(
                                        'product_id'   => $product_id,
                                        'orders_id'    => $orders_id
                                    ),1,0)->row();
                                    if(count($orders_detail) > 0){
                                        $orders_detail_id = $orders_detail->orders_detail_id;
                                    }

                                    $detail = $this->db->get_where("mt_product_detail",array(
                                        'product_id'    => $product_id
                                    ),1,0)->row();

                                    $detail_qty    = $val1->qty;
                                    $detail_weight = $detail->product_weight;
                                    $totalBuy      = $totalBuy + ($detail->product_price_buy * $detail_qty);

                                    $detail_price = $detail->product_price_sale;
                                    if($detail->product_price_discount > 0){
                                        $detail_price = $detail->product_price_discount;
                                    } else {
                                        if($detail->product_price_grosir != ''){
                                            $product_price_grosir = json_decode($detail->product_price_grosir);
                                            foreach ($product_price_grosir as $key => $value){
                                                if($value->qty <= $detail_qty){
                                                    $detail_price = $value->price;
                                                }
                                            }
                                        }
                                    }
                                    $totalPrice    = $totalPrice + ($detail_price * $detail_qty);

                                    $orders_detail_item = NULL;
                                    if($val1->stock_detail != "null"){
                                        $arr_detail    = array();
                                        foreach ($val1->stock_detail as $key2 => $val2) {
                                            $arr_detail[] = array('id'    => $val2->id,
                                                                  'name'  => $val2->name,
                                                                  'qty'   => $val2->qty
                                                      );
                                        }
                                        $orders_detail_item = json_encode($arr_detail);
                                    }

                                    $data0 = array(
                                        'orders_id'             => $orders_id,
                                        'product_id'            => $product->product_id,
                                        'product_name'          => $product->product_name,
                                        'product_images'        => get_cover_image_detail($product->product_id),
                                        'product_price_buy'     => $detail->product_price_buy,
                                        'orders_detail_price'   => $detail_price,
                                        'orders_detail_qty'     => $detail_qty,
                                        'orders_detail_weight'  => $detail_weight,
                                        'orders_detail_item'    => $orders_detail_item,
                                        'orders_detail_status'  => 1,
                                        'date_created'          => timestamp()
                                    );

                                    $this->DATA->table="mt_orders_detail";
                                    $a0 = $this->_save_master(
                                        $data0,
                                        array(
                                            'orders_detail_id' => $orders_detail_id
                                        ),
                                        $orders_detail_id
                                    );
                                    $orders_detail_id = $a0['id'];
                                }

                            }

                            $orders_status = 4;
                            $orders = $this->db->get_where("mt_orders",array(
                                'orders_id' => $orders_id
                            ),1,0)->row();
                            if($orders->orders_status > 4){
                                $orders_status = $orders->orders_status;
                            }

                            if($totalPrice == 0){ $totalPrice = $orders->orders_price_product; }

                            $data1 = array(
                                'orders_price_buy_total'   => $totalBuy,
                                'orders_price_product'     => $totalPrice,
                                'orders_status'            => $orders_status,
                                'orders_product_detail'    => 1,
                                'date_notify'              => timestamp()
                            );
                            if($orders->orders_price_grand_total == 0 || $orders->orders_price_grand_total == NULL){
                                $data1['orders_price_grand_total'] = ($totalPrice + $orders->orders_price_shipping + $orders->orders_price_insurance) - $orders->orders_voucher_price;
                                $isPriceDebetCourier = isPriceDebetCourier($orders->orders_source_id, $orders->orders_courier_id);
                                if($isPriceDebetCourier){ $data1['orders_price_grand_total'] = $totalPrice - $orders->orders_voucher_price; }
                                $data3 = array(
                                    'orders_payment_grand_total' => $data1['orders_price_grand_total'],
                                    'date_notify'                => timestamp()
                                );
                                $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
                            }

                            $data2 = array(
                                'orders_shipping_status' => $orders_status,
                                'orders_shipping_date'   => timestamp(),
                                'date_notify'            => timestamp()
                            );

                            insert_orders_timestamp($orders_id, 4);
                            $isPickup = isPickup($orders->orders_courier_id);
                            if($isPickup){
                                $data1['orders_status'] = 5;
                                $data2['orders_shipping_status']   = 5;
                                $data2['orders_shipping_price']    = $orders->orders_price_shipping;
                                $data2['orders_shipping_date']     = timestamp();
                                insert_orders_timestamp($orders_id, 5);

                                $isPriceDebetCourier = isPriceDebetCourier($orders->orders_source_id, $orders->orders_courier_id);
                                if($isPriceDebetCourier){
                                    $data1['orders_price_debet_ship']  = $orders->orders_price_shipping;
                                    $data1['orders_price_grand_total'] = $totalPrice - $orders->orders_voucher_price;
                                    $data3 = array(
                                        'orders_payment_grand_total' => $data1['orders_price_grand_total'],
                                        'date_notify'                => timestamp()
                                    );
                                    $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
                                }
                            }

                            if($orders_status == 8){
                                $data3 = array(
                                    'orders_payment_price'  => $data1['orders_price_grand_total'],
                                    'date_notify'           => timestamp()
                                );
                                $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
                            }

                            $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));
                            $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));

                            writeLog(array(
                                'log_user_type'     => "1", // Admin
                                'log_user_id'       => $user_id,
                                'log_role'          => NULL,
                                'log_type'          => "4", // Order
                                'log_detail_id'     => $orders_id,
                                'log_detail_item'   => $log_item,
                                'log_detail_qty'    => $log_qty,
                                'log_title_id'      => "30", // Berhasil Checkout
                                'log_desc'          => $orders_cart,
                                'log_status'        => "0"
                            ));

                            $error = false;
                            $msg   = "Berhasil Checkout...";
                            if($booked_name != ""){
                                $shipping = get_detail_orders_shipping($orders_id);
                                $msg  = "";
                                $msg .= "Nama Booking: ".$booked_name."\n";
                                $msg .= "Nama Penerima: ".$shipping->orders_shipping_name."\n";
                                $msg .= "Berhasil di sinkron...";
                            }
                        }
                    } else {
                        $error = true;
                        $msg   = "Nama Penerima belum dipilih.";
                    }
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function reseller_checkout(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id                = mysql_real_escape_string($request->user_id);
            $store_id               = mysql_real_escape_string($request->store_id);
            $orders_name            = mysql_real_escape_string($request->orders_name);
            // $orders_cart             = mysql_real_escape_string($request->orders_cart);
            $token                  = mysql_real_escape_string($request->token);
            $thisAction             = mysql_real_escape_string($request->thisAction);

            $orders_cart = array();
            foreach ($request as $key => $value) {
                if($key == "orders_cart"){
                    $orders_cart = $value;
                }
            }

            if($thisAction == 'checkout'){
                $insertOrdersDetail = false;
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){
                    if($store_id != "" && $orders_name != ""){
                        $arr_cart  = array();
                        $json_cart = json_decode($orders_cart);
                        $i = 0;
                        $totalQty = 0;
                        foreach ($json_cart as $key1 => $val1) {
                            $totalQty += $val1->qty;
                            $arr_cart[$i] = array('id'              => $val1->id,
                                                 'code'             => $val1->code,
                                                 'name'             => $val1->name,
                                                 'images'           => $val1->images,
                                                 'price_sale'       => $val1->price_sale,
                                                 'price_discount'   => $val1->price_discount,
                                                 'qty'              => $val1->qty,
                                                 'stock_detail'     => $val1->stock_detail
                                              );
                            if($val1->stock_detail != "null"){
                                $arr_detail    = array();
                                $arr_cart_item = array();
                                $ii = 0;
                                foreach ($val1->stock_detail as $key2 => $val2) {
                                    if($val2->qty > 0){
                                        $arr_detail[$ii] = array('id'    => $val2->id,
                                                                 'name'  => $val2->name,
                                                                 'qty'   => $val2->qty
                                                  );

                                        $arr_cart_item[$val2->id] = $val2->qty;
                                        $ii += 1;
                                    }
                                }
                                $arr_cart[$i]['stock_detail'] = $arr_detail;
                            }

                            // PENGURANGAN STOK
                            $product = $this->db->get_where("mt_product",array(
                                "product_id"    => $val1->id
                            ),1,0)->row();
                            $product_id = $product->product_id;
                            $detail = $this->db->get_where("mt_product_detail",array(
                                'product_id'    => $product_id
                            ),1,0)->row();
                            if(count($detail) > 0){
                                $dataDetail = array();
                                $dataDetail['product_status_id'] = $detail->product_status_id;
                                $new_stock = ($detail->product_stock - $val1->qty);
                                $dataDetail['product_stock'] = $new_stock;
                                if($new_stock < 1){
                                    $dataDetail['product_status_id'] = 3;
                                    writeLog(array(
                                        'log_user_type'     => "1", // Admin
                                        'log_user_id'       => $user_id,
                                        'log_role'          => NULL,
                                        'log_type'          => "2", // Produk
                                        'log_detail_id'     => $product_id,
                                        'log_detail_item'   => NULL,
                                        'log_detail_qty'    => 0,
                                        'log_title_id'      => "4", // Produk Soldout Otomatis
                                        'log_desc'          => NULL,
                                        'log_status'        => "0"
                                    ));
                                    sendProductNotif(array(
                                        'user_id'       => $user_id,
                                        'store_id'      => $product->store_id,
                                        'product_id'    => $product_id,
                                        'product_item'  => NULL,
                                        'product_qty'   => 0,
                                        'notif_title'   => "Produk Soldout Otomatis",
                                        'notif_desc'    => "",
                                        'notif_status'  => 1,
                                        'notif_notify'  => 3
                                    ));
                                }

                                $log_item  = "";
                                if($detail->product_stock_detail != ""){
                                    $found_sold = false;
                                    $arr_stock  = array();
                                    $arr_item   = array();
                                    $product_stock_detail = json_decode($detail->product_stock_detail);
                                    foreach ($product_stock_detail as $key3 => $val3) {
                                        $new_qty = $val3->qty;
                                        if(array_key_exists($val3->id, $arr_cart_item)){
                                            $new_qty = ($new_qty - $arr_cart_item[$val3->id]);
                                            if($new_qty < 1){
                                                $found_sold = true;
                                            }
                                        }
                                        $arr_stock[] = array('id'       => $val3->id,
                                                             'name'     => $val3->name,
                                                             'color'    => $val3->color,
                                                             'qty'      => $new_qty,
                                                             'status'   => ($new_qty > 0?1:2)
                                                          );

                                        $arr_item[] = array('id'        => $val3->id,
                                                            'name'      => $val3->name,
                                                            'qty_old'   => $val3->qty,
                                                            'qty_new'   => $new_qty,
                                                            'status'    => ($new_qty > 0?1:2)
                                                        );


                                    }
                                    $dataDetail['product_stock_detail'] = json_encode($arr_stock);
                                    $log_item  = json_encode($arr_item);
                                    if($found_sold){
                                        sendProductNotif(array(
                                            'user_id'       => $user_id,
                                            'store_id'      => $product->store_id,
                                            'product_id'    => $product_id,
                                            'product_item'  => $log_item,
                                            'product_qty'   => $new_stock,
                                            'notif_title'   => "Variasi produk sudah habis",
                                            'notif_desc'    => "",
                                            'notif_status'  => 1,
                                            'notif_notify'  => 3
                                        ));
                                    }
                                }
                                writeLog(array(
                                    'log_user_type'     => "1", // Admin
                                    'log_user_id'       => $user_id,
                                    'log_role'          => NULL,
                                    'log_type'          => "2", // Produk
                                    'log_detail_id'     => $product_id,
                                    'log_detail_item'   => $log_item,
                                    'log_detail_qty'    => $val1->qty,
                                    'log_title_id'      => "11", // Pengurangan Stok Otomatis
                                    'log_desc'          => NULL,
                                    'log_status'        => "0"
                                ));
                                $this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

                                update_product_sold($product_id, $val1->qty, "plus");
                            }
                            $i += 1;
                        }

                        if($totalQty > 0){
                            $product_detail_item = json_encode($arr_cart);
                            $store_orders_code   = create_store_orders_code();
                            $data = array(
                                'user_id'                   => $user_id,
                                'store_id'                  => $store_id,
                                'store_orders_name'         => strtoupper($orders_name),
                                'store_orders_noted'        => NULL,
                                'store_orders_code'         => $store_orders_code['orders_code'],
                                'store_orders_invoice'      => $store_orders_code['orders_invoice'],
                                'orders_price_buy_total'    => 0,
                                'orders_price_grand_total'  => 0,
                                'store_orders_istrash'      => 0,
                                'store_orders_date'         => timestamp(),
                                'ip_address'                => $_SERVER['REMOTE_ADDR'],
                                'user_agent'                => $_SERVER['HTTP_USER_AGENT']
                            );

                            $this->DATA->table = "mt_store_orders";
                            $save = $this->_save_master(
                                $data,
                                array(
                                    'store_orders_id' => ""
                                ),
                                ""
                            );
                            $store_orders_id = $save['id'];
                            if($store_orders_id != ''){
                                $log_item   = $product_detail_item;
                                $log_qty    = 0;
                                $totalBuy   = 0;
                                $totalPrice = 0;
                                $product_detail_item = json_decode($product_detail_item);
                                foreach ($product_detail_item as $key1 => $val1) {
                                    $log_qty   += $val1->qty;
                                    $product_id = $val1->id;
                                    $product = $this->db->get_where("mt_product",array(
                                        'product_id'    => $product_id
                                    ),1,0)->row();
                                    if(count($product) > 0){
                                        $detail = $this->db->get_where("mt_product_detail",array(
                                            'product_id'    => $product_id
                                        ),1,0)->row();

                                        $detail_qty    = $val1->qty;
                                        $detail_weight = $detail->product_weight;
                                        $detail_price  = get_reseller_price($store_id, $product->product_id);
                                        $totalBuy      = $totalBuy + ($detail->product_price_buy * $detail_qty);
                                        $totalPrice    = $totalPrice + ($detail_price * $detail_qty);

                                        $orders_detail_item = NULL;
                                        if($val1->stock_detail != "null"){
                                            $arr_detail    = array();
                                            foreach ($val1->stock_detail as $key2 => $val2) {
                                                $arr_detail[] = array('name'  => $val2->name,
                                                                      'qty'   => $val2->qty
                                                          );
                                            }
                                            $orders_detail_item = json_encode($arr_detail);
                                        }

                                        $data0 = array(
                                            'store_orders_id'       => $store_orders_id,
                                            'product_id'            => $product->product_id,
                                            'product_name'          => $product->product_name,
                                            'product_images'        => get_cover_image_detail($product->product_id),
                                            'product_price_buy'     => $detail->product_price_buy,
                                            'orders_detail_price'   => $detail_price,
                                            'orders_detail_qty'     => $detail_qty,
                                            'orders_detail_weight'  => $detail_weight,
                                            'orders_detail_item'    => $orders_detail_item
                                        );

                                        $this->DATA->table="mt_store_orders_detail";
                                        $a0 = $this->_save_master(
                                            $data0,
                                            array(
                                                'orders_detail_id' => ''
                                            ),
                                            ''
                                        );

                                    }

                                }
                                $lastsaldo = get_saldo_store($store_id);

                                $data1 = array(
                                    'orders_price_buy_total'    => $totalBuy,
                                    'orders_price_grand_total'  => $totalPrice
                                );
                                $this->db->update("mt_store_orders",$data1,array("store_orders_id"=>$store_orders_id));

                                $data2 = array(
                                    'user_id'           => $user_id,
                                    'store_id'          => $store_id,
                                    'store_orders_id'   => $store_orders_id,
                                    'payment_method_id' => 0,
                                    'payment_saldo'     => $lastsaldo,
                                    'payment_price'     => $totalPrice,
                                    'payment_noted'     => NULL,
                                    'payment_type'      => 1,
                                    'payment_accept'    => 1,
                                    'payment_date'      => timestamp(),
                                    'payment_istrash'   => 0
                                );

                                $this->DATA->table="mt_store_payment";
                                $a2 = $this->_save_master(
                                    $data2,
                                    array(
                                        'store_payment_id' => ''
                                    ),
                                    ''
                                );
                                if($a2['id']){
                                    if($lastsaldo != ""){
                                        $saldo = ($lastsaldo - $totalPrice); // Pemesanan
                                        // $saldo = ($lastsaldo + $totalPrice); // Pembayaran
                                        $this->db->update("mt_store",array("store_saldo"=>$saldo),array("store_id"=>$store_id));
                                    } else {
                                        writeLog(array(
                                            'log_user_type'     => "1", // Admin
                                            'log_user_id'       => $user_id,
                                            'log_role'          => NULL,
                                            'log_type'          => "5", // Pemesanan Reseller
                                            'log_detail_id'     => $store_orders_id,
                                            'log_detail_item'   => NULL,
                                            'log_detail_qty'    => NULL,
                                            'log_title_id'      => "32", // Gagal Update Saldo Pemesanan Reseller
                                            'log_desc'          => NULL,
                                            'log_status'        => "1"
                                        ));
                                    }
                                } else {
                                    writeLog(array(
                                        'log_user_type'     => "1", // Admin
                                        'log_user_id'       => $user_id,
                                        'log_role'          => NULL,
                                        'log_type'          => "5", // Pemesanan Reseller
                                        'log_detail_id'     => $store_orders_id,
                                        'log_detail_item'   => NULL,
                                        'log_detail_qty'    => NULL,
                                        'log_title_id'      => "31", // Gagal Simpan Saldo Pemesanan Reseller
                                        'log_desc'          => NULL,
                                        'log_status'        => "1"
                                    ));
                                }

                                writeLog(array(
                                    'log_user_type'     => "1", // Admin
                                    'log_user_id'       => $user_id,
                                    'log_role'          => NULL,
                                    'log_type'          => "5", // Pemesanan Reseller
                                    'log_detail_id'     => $store_orders_id,
                                    'log_detail_item'   => $log_item,
                                    'log_detail_qty'    => $log_qty,
                                    'log_title_id'      => "30", // Berhasil Checkout
                                    'log_desc'          => NULL,
                                    'log_status'        => "0"
                                ));

                                $error  = false;
                                $msg    = "Berhasil Checkout.";
                            } else {
                                $error  = true;
                                $msg    = "Gagal simpan data pesanan.";
                            }
                        } else {
                            $error  = true;
                            $msg    = "Keranjang belanja masih kosong.";
                        }
                    } else {
                        $error = true;
                        $msg   = "Nama Penerima belum diisi.";
                    }
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function get_store_orders(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id                = mysql_real_escape_string($request->user_id);
            $store_id               = mysql_real_escape_string($request->store_id);
            $store_orders_id        = mysql_real_escape_string($request->store_orders_id);
            $token                  = mysql_real_escape_string($request->token);
            $thisAction             = mysql_real_escape_string($request->thisAction);

            $type_result            = mysql_real_escape_string($request->type_result);
            $keyword                = mysql_real_escape_string($request->keyword);
            $date_start             = mysql_real_escape_string($request->date_start);
            $date_end               = mysql_real_escape_string($request->date_end);
            $offset                 = mysql_real_escape_string($request->offset);
            $limit                  = mysql_real_escape_string($request->limit);

            if($thisAction == 'getdata'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){

                    // $this->DATA->table = "mt_store_orders";
                    $this->load->model("mdl_reseller_orders","MRO");

                    $colum = "";
                    $param = array(
                        ''                      => 'Semua Pencarian...',
                        'store_orders_code'     => 'No Order',
                        'store_orders_invoice'  => 'Invoice',
                        'store_orders_name'     => 'Nama Customer'
                    );

                    $par_filter = array(
                        "store_orders_id"     => ($store_orders_id!=""?$store_orders_id:''),
                        "store_id"            => ($store_id!=""?$store_id:''),
                        "type_result"         => ($type_result!=""?$type_result:''),
                        "date_start"          => ($date_start!=""?$date_start:''),
                        "date_end"            => ($date_end!=""?$date_end:''),
                        "order_by"            => ($order_by!=""?$order_by:''),
                        "order_dir"           => ($order_dir!=""?$order_dir:''),
                        "offset"              => ($offset!=0?$offset:0),
                        "limit"               => ($limit!=0?$limit:1000),
                        "colum"               => ($colum!=""?$colum:''),
                        "keyword"             => ($keyword!=""?$keyword:''),
                        "param"               => ($param!=""?$param:NULL)
                    );

                    $data_orders = $this->MRO->data_orders($par_filter);

                    $result = $data_orders['data'];
                    $total  = $data_orders['total'];
                    $error  = false;
                    $msg    = "Transaksi ditemukan..";
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $i = 0;
        $newStore = array();
        $notId    = array("1","2");
        $store    = get_store();
        foreach ($store as $key => $val) {
            if(!in_array($val->store_id, $notId)){
                $newStore[$i]->id   = $val->store_id;
                $newStore[$i]->name = $val->store_name;
                $i += 1;
            }
        }

        $rows['numrows'] = $total;
        $rows['result']  = $result;
        $rows['store']   = $newStore;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function get_store_payment(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id                = mysql_real_escape_string($request->user_id);
            $store_id               = mysql_real_escape_string($request->store_id);
            $store_payment_id       = mysql_real_escape_string($request->store_payment_id);
            $token                  = mysql_real_escape_string($request->token);
            $keyword                = mysql_real_escape_string($request->keyword);
            $date_start             = mysql_real_escape_string($request->date_start);
            $date_end               = mysql_real_escape_string($request->date_end);
            $offset                 = mysql_real_escape_string($request->offset);
            $limit                  = mysql_real_escape_string($request->limit);
            $thisAction             = mysql_real_escape_string($request->thisAction);

            if($thisAction == 'getdata'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){

                    // $this->DATA->table = "mt_store_orders";
                    $this->load->model("mdl_reseller_orders","MRO");

                    $colum = "";
                    $param = array(
                        ''                      => 'Semua Pencarian...',
                        'store_orders_code'     => 'No Order',
                        'store_orders_invoice'  => 'Invoice',
                        'store_orders_name'     => 'Nama Customer'
                    );

                    $par_filter = array(
                        "store_payment_id"    => ($store_payment_id!=""?$store_payment_id:''),
                        "store_id"            => ($store_id!=""?$store_id:''),
                        "date_start"          => ($date_start!=""?$date_start:''),
                        "date_end"            => ($date_end!=""?$date_end:''),
                        "order_by"            => ($order_by!=""?$order_by:''),
                        "order_dir"           => ($order_dir!=""?$order_dir:''),
                        "offset"              => ($offset!=0?$offset:0),
                        "limit"               => ($limit!=0?$limit:1000),
                        "colum"               => ($colum!=""?$colum:''),
                        "keyword"             => ($keyword!=""?$keyword:''),
                        "param"               => ($param!=""?$param:NULL)
                    );

                    $data_payment = $this->MRO->data_orders_payment($par_filter);

                    $result   = $data_payment['data'];
                    $total    = $data_payment['total'];
                    $saldo    = $data_payment['saldo'];
                    $allsaldo = $data_payment['allsaldo'];
                    $error  = false;
                    $msg    = "Payment ditemukan..";
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $i = 0;
        $newStore = array();
        $notId    = array("1","2");
        $store    = get_store();
        foreach ($store as $key => $val) {
            if(!in_array($val->store_id, $notId)){
                $newStore[$i]->id   = $val->store_id;
                $newStore[$i]->name = $val->store_name;
                $i += 1;
            }
        }

        $rows['numrows']  = $total;
        $rows['result']   = $result;
        $rows['saldo']    = $saldo;
        $rows['allsaldo'] = $allsaldo;
        $rows['store']    = $newStore;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function get_store_list_payment(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     = mysql_real_escape_string($request->user_id);
            $store_id    = mysql_real_escape_string($request->store_id);
            $token       = mysql_real_escape_string($request->token);
            $thisAction  = mysql_real_escape_string($request->thisAction);

            if($thisAction == 'getdata'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){
                    $i = 0;
                    $payment_method = array();
                    $m = $this->db->order_by("position","asc")->get_where("mt_payment_method",array(
                        "parent_id"              => "0",
                        "payment_method_istrash" => "0"
                    ))->result();
                    foreach ($m as $k => $v) {
                        $m2 = $this->db->order_by("position","asc")->get_where("mt_payment_method",array(
                        "parent_id"              => $v->payment_method_id,
                        "payment_method_istrash" => "0"
                        ))->result();
                        foreach ($m2 as $k2 => $v2) {
                            $payment_method[$i]->id           = $v2->payment_method_id;
                            $payment_method[$i]->name         = $v2->payment_method_name;
                            $payment_method[$i]->name_account = $v2->payment_method_name_account;
                            $payment_method[$i]->no_account   = $v2->payment_method_no_account;
                            $payment_method[$i]->cabang       = $v2->payment_method_cabang;

                            $i += 1;
                        }
                    }

                    $result = $payment_method;

                    $error  = false;
                    $msg    = "Payment ditemukan..";
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $i = 0;
        $notId = array("1","2");
        $store = get_store();
        $allsaldo = array();
        foreach ($store as $key => $val) {
            if(!in_array($val->store_id, $notId)){
                $allsaldo[$i]->id    = $val->store_id;
                $allsaldo[$i]->name  = $val->store_name;
                $allsaldo[$i]->saldo = $val->store_saldo;
                $i += 1;
            }
        }

        $rows['result'] = $result;
        $rows['store_saldo'] = $allsaldo;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function save_store_payment(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     = mysql_real_escape_string($request->user_id);
            $store_id    = mysql_real_escape_string($request->store_id);
            $payment_method = mysql_real_escape_string($request->payment_method);
            $payment_price  = mysql_real_escape_string($request->payment_price);
            $token       = mysql_real_escape_string($request->token);
            $thisAction  = mysql_real_escape_string($request->thisAction);

            if($thisAction == 'save'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){
                    $lastsaldo  = get_saldo_store($store_id);
                    $totalPrice = convertRpToInt($payment_price);

                    $data2 = array(
                        'user_id'           => $user_id,
                        'store_id'          => $store_id,
                        'store_orders_id'   => 0,
                        'payment_method_id' => $payment_method,
                        'payment_saldo'     => $lastsaldo,
                        'payment_price'     => $totalPrice,
                        'payment_noted'     => NULL,
                        'payment_type'      => 2,
                        'payment_accept'    => 0,
                        'payment_date'      => timestamp(),
                        'payment_istrash'   => 0
                    );

                    $this->DATA->table="mt_store_payment";
                    $a2 = $this->_save_master(
                        $data2,
                        array(
                            'store_payment_id' => ''
                        ),
                        ''
                    );
                    if($a2['id']){
                        if($lastsaldo != ""){
                            // $saldo = ($lastsaldo - $totalPrice); // Pemesanan
                            $saldo = ($lastsaldo + $totalPrice); // Pembayaran
                            $this->db->update("mt_store",array("store_saldo"=>$saldo),array("store_id"=>$store_id));
                        } else {
                            writeLog(array(
                                'log_user_type'     => "1", // Admin
                                'log_user_id'       => $user_id,
                                'log_role'          => NULL,
                                'log_type'          => "5", // Pemesanan Reseller
                                'log_detail_id'     => $a2['id'],
                                'log_detail_item'   => NULL,
                                'log_detail_qty'    => $totalPrice,
                                'log_title_id'      => "36", // Gagal Update Saldo Pembayaran
                                'log_desc'          => NULL,
                                'log_status'        => "1"
                            ));
                        }
                    } else {
                        writeLog(array(
                            'log_user_type'     => "1", // Admin
                            'log_user_id'       => $user_id,
                            'log_role'          => NULL,
                            'log_type'          => "5", // Pemesanan Reseller
                            'log_detail_id'     => $a2['id'],
                            'log_detail_item'   => NULL,
                            'log_detail_qty'    => $totalPrice,
                            'log_title_id'      => "35", // Gagal Simpan Saldo Pembayaran
                            'log_desc'          => NULL,
                            'log_status'        => "1"
                        ));
                    }

                    writeLog(array(
                        'log_user_type'     => "1", // Admin
                        'log_user_id'       => $user_id,
                        'log_role'          => NULL,
                        'log_type'          => "5", // Pemesanan Reseller
                        'log_detail_id'     => $a2['id'],
                        'log_detail_item'   => NULL,
                        'log_detail_qty'    => $totalPrice,
                        'log_title_id'      => "34", // Berhasil Melakukan Pembayaran
                        'log_desc'          => NULL,
                        'log_status'        => "0"
                    ));

                    $error  = false;
                    $msg    = "Berhasil simpan pembayaran";
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function canceled_booked(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id             = mysql_real_escape_string($request->user_id);
            $orders_id           = mysql_real_escape_string($request->orders_id);
            $token               = mysql_real_escape_string($request->token);
            $thisAction          = mysql_real_escape_string($request->thisAction);

            $cek_user = $this->db->get_where("mt_app_user",array(
                "user_id"       => $user_id,
                "token_apps"    => $token
            ),1,0)->row();
            if(count($cek_user) > 0){
                if($thisAction == 'cancel'){
                    $temp_orders_id = $orders_id;
                    $r = $this->db->get_where("mt_temp_orders",array(
                        'temp_orders_id'  => $temp_orders_id
                    ),1,0)->row();
                    if(count($r) > 0){
                        if($r->orders_booked == 1){
                            $product_detail_item = json_decode($r->product_detail_item);
                            foreach ($product_detail_item as $key1 => $val1) {

                                if($val1->stock_detail != "null"){
                                    $arr_cart_item = array();
                                    foreach ($val1->stock_detail as $key2 => $val2) {
                                        $arr_cart_item[$val2->id] = $val2->qty;
                                    }
                                }

                                $product_id = $val1->id;
                                $detail = $this->db->get_where("mt_product_detail",array(
                                    'product_id'    => $product_id
                                ),1,0)->row();
                                if(count($detail) > 0){
                                    $dataDetail = array();
                                    $dataDetail['product_status_id'] = $detail->product_status_id;
                                    $new_stock = ($detail->product_stock + $val1->qty);
                                    $dataDetail['product_stock'] = $new_stock;

                                    $log_item = "";
                                    if($detail->product_stock_detail != ""){
                                        $arr_stock = array();
                                        $arr_item   = array();
                                        $product_stock_detail = json_decode($detail->product_stock_detail);
                                        foreach ($product_stock_detail as $key3 => $val3) {
                                            $new_qty = $val3->qty;
                                            if(array_key_exists($val3->id, $arr_cart_item)){
                                                $new_qty = ($new_qty + $arr_cart_item[$val3->id]);
                                            }
                                            $arr_stock[] = array('id'       => $val3->id,
                                                                 'name'     => $val3->name,
                                                                 'color'    => $val3->color,
                                                                 'qty'      => $new_qty,
                                                                 'status'   => ($new_qty > 0?1:2)
                                                              );

                                            $arr_item[] = array('id'        => $val3->id,
                                                                'name'      => $val3->name,
                                                                'qty_old'   => $val3->qty,
                                                                'qty_new'   => $new_qty,
                                                                'status'    => ($new_qty > 0?1:2)
                                                            );
                                        }
                                        $dataDetail['product_stock_detail'] = json_encode($arr_stock);
                                        $log_item  = json_encode($arr_item);
                                    }

                                    writeLog(array(
                                        'log_user_type'     => "1", // Admin
                                        'log_user_id'       => $user_id,
                                        'log_role'          => NULL,
                                        'log_type'          => "2", // Produk
                                        'log_detail_id'     => $product_id,
                                        'log_detail_item'   => $log_item,
                                        'log_detail_qty'    => $val1->qty,
                                        'log_title_id'      => "10", // Penambahan Stok
                                        'log_desc'          => NULL,
                                        'log_status'        => "0"
                                    ));
                                    $this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

                                    update_product_sold($product_id, $val1->qty, 'minus');
                                }

                            }
                        }

                        $this->db->delete("mt_temp_orders",array('temp_orders_id' => $temp_orders_id));

                        $error  = false;
                        $msg    = "Berhasil cancel booking pesanan.";
                    } else {
                        $error = true;
                        $msg   = "No pesanan tidak ditemukan.";
                    }
                }
            } else {
                $error = true;
                $msg   = "Anda tidak mempunyai hak akses.";
            }
        }

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function deleted_booked(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id             = mysql_real_escape_string($request->user_id);
            $orders_id           = mysql_real_escape_string($request->orders_id);
            $token               = mysql_real_escape_string($request->token);
            $thisAction          = mysql_real_escape_string($request->thisAction);

            $cek_user = $this->db->get_where("mt_app_user",array(
                "user_id"       => $user_id,
                "token_apps"    => $token
            ),1,0)->row();
            if(count($cek_user) > 0){
                if($thisAction == 'delete'){
                    $temp_orders_id = $orders_id;
                    $r = $this->db->get_where("mt_temp_orders",array(
                        'temp_orders_id'  => $temp_orders_id
                    ),1,0)->row();
                    if(count($r) > 0){

                        $this->db->delete("mt_temp_orders",array('temp_orders_id' => $temp_orders_id));

                        $error  = false;
                        $msg    = "Berhasil hapus booking pesanan.";
                    } else {
                        $error = true;
                        $msg   = "No pesanan tidak ditemukan.";
                    }
                }
            } else {
                $error = true;
                $msg   = "Anda tidak mempunyai hak akses.";
            }
        }

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function courier_package(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id             = mysql_real_escape_string($request->user_id);
            $date_start          = mysql_real_escape_string($request->date_start);
            $date_end            = mysql_real_escape_string($request->date_end);
            $order_by            = mysql_real_escape_string($request->order_by);
            $order_dir           = mysql_real_escape_string($request->order_dir);
            $offset              = mysql_real_escape_string($request->offset);
            $limit               = mysql_real_escape_string($request->limit);
            $token               = mysql_real_escape_string($request->token);
            $thisAction          = mysql_real_escape_string($request->thisAction);

            $cek_user = $this->db->get_where("mt_app_user",array(
                "user_id"       => $user_id,
                "token_apps"    => $token
            ),1,0)->row();
            if(count($cek_user) > 0){
                if($thisAction == 'save'){
                    $package_item = array();
                    foreach ($request as $key => $value) {
                        if($key == "package_item"){
                            $package_item = $value;
                        }
                    }

                    $totalQty = 0;
                    foreach ($package_item as $key1 => $val1) {
                        if($val1->qty > 0){
                            $totalQty  += $val1->qty;

                            $noted_id   = "";
                            $this->db->where("noted_type", "1");
                            $this->db->where("noted_temp_id", $val1->id);
                            $this->db->where("noted_date LIKE", "%".$date_start."%");
                            $this->db->where("noted_istrash", "0");
                            $noted = $this->db->get("mt_noted")->row();
                            if(count($noted) > 0){
                                $noted_id = $noted->noted_id;
                            }

                            $data = array(
                                'noted_type'                => "1",
                                'noted_temp_id'             => $val1->id,
                                'noted_desc'                => NULL,
                                'noted_qty'                 => $val1->qty,
                                'noted_date'                => $date_start." 00:00:00",
                                'noted_istrash'             => 0
                            );

                            $this->DATA->table = "mt_noted";
                            $save = $this->_save_master(
                                $data,
                                array(
                                    'noted_id' => $noted_id
                                ),
                                $noted_id
                            );
                        }
                    }

                    $result = get_count_courier_package($date_start);
                    $total  = count($result);
                    $error  = false;
                    $msg    = "Berhasil simpan jumlah paket..";

                } else if($thisAction == 'getdata'){
                    $result = get_count_courier_package($date_start);
                    $total  = count($result);
                    $error  = false;
                    $msg    = "Package ditemukan..";
                } else if($thisAction == 'getalldata'){

                    $par_filter = array(
                        "order_by"          => $order_by,
                        "order_dir"         => $order_dir,
                        "offset"            => $offset,
                        "limit"             => $limit,
                        "param"             => ""
                    );
                    $package = $this->M->data_courier_package($par_filter);

                    $result = $package["data"];
                    $total  = $package["total"];
                    $error  = false;
                    $msg    = "Package ditemukan..";
                }
            } else {
                $error = true;
                $msg   = "Anda tidak mempunyai hak akses.";
            }
        }
        $rows['numrows']  = $total;
        $rows['result']   = $result;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function courier_payment(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id             = mysql_real_escape_string($request->user_id);
            $payment_type        = mysql_real_escape_string($request->payment_type);
            $date_start          = mysql_real_escape_string($request->date_start);
            $date_end            = mysql_real_escape_string($request->date_end);
            $order_by            = mysql_real_escape_string($request->order_by);
            $order_dir           = mysql_real_escape_string($request->order_dir);
            $offset              = mysql_real_escape_string($request->offset);
            $limit               = mysql_real_escape_string($request->limit);
            $token               = mysql_real_escape_string($request->token);
            $thisAction          = mysql_real_escape_string($request->thisAction);

            $cek_user = $this->db->get_where("mt_app_user",array(
                "user_id"       => $user_id,
                "token_apps"    => $token
            ),1,0)->row();
            if(count($cek_user) > 0){
                if($thisAction == 'save'){
                    $package_item = array();
                    foreach ($request as $key => $value) {
                        if($key == "payment_item"){
                            $package_item = $value;
                        }
                    }

                    $totalPrice = 0;
                    foreach ($package_item as $key1 => $val1) {
                        $tempId    = $val1->id;
                        $tempPrice = convertRpToInt($val1->qty);
                        if($tempPrice > 0){
                            $totalPrice += $tempPrice;

                            $noted_id    = "";
                            $this->db->where("noted_type", $payment_type);
                            $this->db->where("noted_temp_id", $tempId);
                            $this->db->where("noted_date LIKE", "%".$date_start."%");
                            $this->db->where("noted_istrash", "0");
                            $noted = $this->db->get("mt_noted")->row();
                            if(count($noted) > 0){
                                $noted_id = $noted->noted_id;
                            }
                            $data = array(
                                'noted_type'                => $payment_type,
                                'noted_temp_id'             => $tempId,
                                'noted_desc'                => NULL,
                                'noted_qty'                 => $tempPrice,
                                'noted_date'                => $date_start." 00:00:00",
                                'noted_istrash'             => 0
                            );

                            $this->DATA->table = "mt_noted";
                            $save = $this->_save_master(
                                $data,
                                array(
                                    'noted_id' => $noted_id
                                ),
                                $noted_id
                            );
                        }
                    }

                    $result = get_count_courier_payment($date_start, $payment_type);
                    $total  = count($result);
                    $error  = false;
                    $msg    = "Berhasil simpan biaya..";

                } else if($thisAction == 'getdata'){
                    $result = get_count_courier_payment($date_start, $payment_type);
                    $total  = count($result);
                    $error  = false;
                    $msg    = "Payment ditemukan..";
                } else if($thisAction == 'getalldata'){
                    $par_filter = array(
                        "noted_type"        => $payment_type,
                        "order_by"          => $order_by,
                        "order_dir"         => $order_dir,
                        "offset"            => $offset,
                        "limit"             => $limit,
                        "param"             => ""
                    );
                    $payment = $this->M->data_courier_payment($par_filter);

                    $result = $payment["data"];
                    $total  = $payment["total"];
                    $tagihan= $payment["tagihan"];
                    $error  = false;
                    $msg    = "Payment ditemukan..";
                }
            } else {
                $error = true;
                $msg   = "Anda tidak mempunyai hak akses.";
            }
        }
        $rows['numrows'] = $total;
        $rows['result']  = $result;
        $rows['tagihan'] = $tagihan;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function get_print_label(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id                = mysql_real_escape_string($request->user_id);
            $store_id               = mysql_real_escape_string($request->store_id);
            $orders_print           = mysql_real_escape_string($request->orders_print);
            $token                  = mysql_real_escape_string($request->token);
            $keyword                = mysql_real_escape_string($request->keyword);
            $date_start             = mysql_real_escape_string($request->date_start);
            $date_end               = mysql_real_escape_string($request->date_end);
            $order_by               = mysql_real_escape_string($request->order_by);
            $order_dir              = mysql_real_escape_string($request->order_dir);
            $offset                 = mysql_real_escape_string($request->offset);
            $limit                  = mysql_real_escape_string($request->limit);
            $thisAction             = mysql_real_escape_string($request->thisAction);

            if($thisAction == 'getdata'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){

                    $colum = "";
                    if(trim($colum)=="" && trim($keyword) != ""){
                        $param = array(
                            ''                                         => 'Semua Pencarian...',
                            'mt_orders.orders_code'                    => 'No Order',
                            'mt_orders.orders_invoice'                 => 'Invoice',
                            'mt_orders.orders_source_invoice'          => 'Marketplace Invoice',
                            'mt_orders_shipping.orders_shipping_name'  => 'Nama Customer',
                            'mt_orders_shipping.orders_shipping_email' => 'Email Customer',
                            'mt_orders_shipping.orders_shipping_phone' => 'Hp Customer',
                        );
                    } else {
                        $param = array(
                            ''                                         => 'Semua Pencarian...',
                            'mt_orders.orders_code'                    => 'No Order',
                            'mt_orders.orders_invoice'                 => 'Invoice',
                            'mt_orders.orders_source_invoice'          => 'Marketplace Invoice'
                        );
                    }

                    $get_all = TRUE;
                    if(trim($orders_print)=="0"){
                        $get_all = FALSE;
                    }

                    $par_filter = array(
                        "store_id"            => ($store_id!=""?$store_id:''),
                        "orders_print"        => ($orders_print!=""?$orders_print:''),
                        "get_all"             => $get_all,
                        "type_result"         => "list_app",
                        "date_start"          => ($date_start!=""?$date_start:''),
                        "date_end"            => ($date_end!=""?$date_end:''),
                        "order_by"            => ($order_by!=""?$order_by:''),
                        "order_dir"           => ($order_dir!=""?$order_dir:''),
                        "offset"              => ($offset!=0?$offset:0),
                        "limit"               => ($limit!=0?$limit:1000),
                        "keyword"             => ($keyword!=""?$keyword:''),
                        "colum"               => ($colum!=""?$colum:''),
                        "param"               => ($param!=""?$param:NULL)
                    );

                    $data_orders = $this->M->data_orders($par_filter);

                    $result   = $data_orders['data'];
                    $total    = $data_orders['total'];
                    $error  = false;
                    $msg    = "Label ditemukan..";
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $rows['numrows']  = $total;
        $rows['result']   = $result;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function update_print_status(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $reseller_id = "";
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id    = mysql_real_escape_string($request->user_id);
            $store_id   = mysql_real_escape_string($request->store_id);
            $orders_id  = mysql_real_escape_string($request->orders_id);
            $token      = mysql_real_escape_string($request->token);
            $thisAction = mysql_real_escape_string($request->thisAction);

            if($thisAction == 'save'){
                $cek_user = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($cek_user) > 0){
                    // if($cek_user->user_group == "5"){ $reseller_id = get_user_store($user_id); }

                    $newId     = array();
                    $expOrders = explode('-', $orders_id);
                    foreach ($expOrders as $key => $value) {
                        $r = $this->db->get_where("mt_orders",array(
                            'orders_id'     => $value
                        ),1,0)->row();
                        if(count($r) > 0){
                            $this->db->update("mt_orders",array("orders_print"=>1),array("orders_id"=>$r->orders_id));

                            $error  = false;
                            $msg    = "Sukses print label.";
                        }
                    }
                } else {
                    $error = true;
                    $msg   = "Anda tidak mempunyai hak akses.";
                }
            }
        }

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function save_new_orders(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id            = mysql_real_escape_string($request->user_id);
            $token              = mysql_real_escape_string($request->token);

            $orders_id          = mysql_real_escape_string($request->orders_id);
            $orders_source_id   = mysql_real_escape_string($request->orders_source_id);
            $orders_courier_id  = mysql_real_escape_string($request->orders_courier_id);
            $orders_source_invoice   = mysql_real_escape_string($request->orders_source_invoice);
            $orders_product_category_title = mysql_real_escape_string($request->orders_product_category_title);
            $orders_shipping_name    = mysql_real_escape_string($request->orders_shipping_name);
            $orders_shipping_phone   = mysql_real_escape_string($request->orders_shipping_phone);
            $orders_shipping_address = mysql_real_escape_string($request->orders_shipping_address);
            $orders_shipping_resi    = mysql_real_escape_string($request->orders_shipping_resi);
            $orders_price_product    = mysql_real_escape_string($request->orders_price_product);
            $orders_price_shipping   = mysql_real_escape_string($request->orders_price_shipping);
            $orders_price_insurance  = mysql_real_escape_string($request->orders_price_insurance);
            $orders_shipping_weight  = mysql_real_escape_string($request->orders_shipping_weight);
            $orders_ship_name   = mysql_real_escape_string($request->orders_ship_name);
            $orders_ship_phone  = mysql_real_escape_string($request->orders_ship_phone);
            $orders_noted       = mysql_real_escape_string($request->orders_noted);
            $thisAction         = mysql_real_escape_string($request->thisAction);

            // $orders_noted = NULL;
            // foreach ($request as $key => $value) {
            //     if($key == "orders_noted"){
            //         $orders_noted = $value;
            //     }
            // }

            $cek_user = $this->db->get_where("mt_app_user",array(
                "user_id"       => $user_id,
                "token_apps"    => $token
            ),1,0)->row();
            if(count($cek_user) > 0){
                if($thisAction == 'save'){
                    // if($cek_user->user_group == 5){ $reseller_id = get_user_store($user_id); }

                    $store_id = get_user_store($user_id);
                    $detail_store  = get_detail_store($store_id);
                    $store_name    = $detail_store->store_name;
                    $store_phone   = $detail_store->store_phone;
                    $store_product = $detail_store->store_product;

                    $orders_shipping_dropship = 0;
                    if($store_name != $orders_ship_name){
                        $orders_shipping_dropship = 1;
                    }

                    $orders_price_grand_total = 0;
                    $data2 = array(
                        'orders_source_id'              => dbClean($orders_source_id),
                        'orders_source_invoice'         => dbClean(strtoupper($orders_source_invoice)),
                        'orders_source_payment'         => 0,
                        'orders_price_product'          => dbClean(convertRpToInt($orders_price_product)),
                        'orders_price_insurance'        => dbClean(convertRpToInt($orders_price_insurance)),
                        'orders_price_shipping'         => dbClean(convertRpToInt($orders_price_shipping)),
                        'orders_price_grand_total'      => $orders_price_grand_total,
                        'orders_courier_id'             => dbClean($orders_courier_id),
                        'orders_noted'                  => preg_replace("'\r?\n'"," ", $orders_noted)
                    );
                    if(convertRpToInt($orders_price_product) > 0){
                        $orders_price_grand_total = convertRpToInt($orders_price_product) + convertRpToInt($orders_price_shipping) + convertRpToInt($orders_price_insurance);
                        $data2['orders_price_grand_total'] = $orders_price_grand_total;
                    }

                    if(isset($orders_id) && $orders_id == ''){
                        $create_orders_code      = create_orders_code();
                        $data2['orders_code']    = $create_orders_code['orders_code'];
                        $data2['orders_invoice'] = $create_orders_code['orders_invoice'];
                        $data2['member_type']    = 2;
                        $data2['member_id']      = $user_id;
                        $data2['store_id']       = $store_id;
                        $data2['orders_status']  = 3;
                        $data2['orders_print']   = 0;
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
                            'orders_id' => dbClean($orders_id)
                            ),
                        dbClean($orders_id)
                        );

                    $id = $a2['id'];

                    $data4 = array(
                        'orders_payment_grand_total'    => $orders_price_grand_total
                    );
                    if(isset($orders_id) && $orders_id == ''){
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
                            'orders_id' => dbClean($orders_id)
                            ),
                        dbClean($orders_id)
                        );

                    $data5 = array(
                        'orders_product_category_title' => dbClean($orders_product_category_title),
                        'orders_shipping_name'          => dbClean(strtoupper($orders_shipping_name)),
                        'orders_shipping_address'       => dbClean(ucwords($orders_shipping_address)),
                        'orders_shipping_phone'         => dbClean($orders_shipping_phone),
                        'orders_shipping_resi'          => dbClean(strtoupper($orders_shipping_resi)),
                        'orders_shipping_weight'        => dbClean($orders_shipping_weight)
                    );
                    if($orders_shipping_dropship == 1){
                        $data5['orders_shipping_dropship'] = 1;
                        $data5['orders_ship_name']         = dbClean(ucwords($orders_ship_name));
                        $data5['orders_ship_phone']        = dbClean($orders_ship_phone);
                    }
                    if(isset($orders_id) && $orders_id == ''){
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
                            'orders_id' => dbClean($orders_id)
                            ),
                        dbClean($orders_id)
                        );

                    if(isset($orders_id) && $orders_id == ''){
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
                                'orders_id' => dbClean($orders_id)
                                ),
                            dbClean($orders_id)
                        );
                    }

                    $error  = false;
                    $msg    = "Berhasil simpan data..";
                }
            } else {
                $error = true;
                $msg   = "Anda tidak mempunyai hak akses.";
            }
        }

        $rows['result'] = $result;
        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function list_source(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id            = mysql_real_escape_string($request->user_id);
            $token              = mysql_real_escape_string($request->token);
            $thisAction         = mysql_real_escape_string($request->thisAction);

            $cek_user = $this->db->get_where("mt_app_user",array(
                "user_id"       => $user_id,
                "token_apps"    => $token
            ),1,0)->row();
            if(count($cek_user) > 0){
                if($thisAction == 'getdata'){
                    // if($cek_user->user_group == 5){ $reseller_id = get_user_store($user_id); }

                    $i = 0;
                    $result = array();
                    $source = get_orders_source();
                    foreach ($source as $key => $val) {
                        $result[$i]->id      = $val->orders_source_id;
                        $result[$i]->name    = $val->orders_source_name;
                        $i += 1;
                    }

                    $error  = false;
                    $msg    = "Source ditemukan..";
                }
            } else {
                $error = true;
                $msg   = "Anda tidak mempunyai hak akses.";
            }
        }

        $rows['result'] = $result;
        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function list_courier(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id            = mysql_real_escape_string($request->user_id);
            $token              = mysql_real_escape_string($request->token);
            $thisAction         = mysql_real_escape_string($request->thisAction);

            $cek_user = $this->db->get_where("mt_app_user",array(
                "user_id"       => $user_id,
                "token_apps"    => $token
            ),1,0)->row();
            if(count($cek_user) > 0){
                if($thisAction == 'getdata'){
                    // if($cek_user->user_group == 5){ $reseller_id = get_user_store($user_id); }

                    $i = 0;
                    $result = array();
                    $courier = get_orders_courier();
                    foreach ($courier as $key => $val) {
                        $courier2 = get_orders_courier($val->orders_courier_id, true);
                        foreach ($courier2 as $key2 => $val2) {
                            $result[$i]->id      = $val2->orders_courier_id;
                            $result[$i]->name    = $val2->orders_courier_name." ".$val2->orders_courier_service;
                            $i += 1;
                        }
                    }

                    $error  = false;
                    $msg    = "Courier ditemukan..";
                }
            } else {
                $error = true;
                $msg   = "Anda tidak mempunyai hak akses.";
            }
        }

        $rows['result'] = $result;
        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

}
