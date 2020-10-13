<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class transaction_peripheral extends AdminController {
    function __construct()
    {
        ini_set('precision', '15');
        parent::__construct();
        $this->_set_action();
        $this->_set_action(array("view","edit","delete"),"ITEM");
        $this->_set_title( 'Orderan' );
        $this->DATA->table="mt_orders";
        $this->folder_view = "orders/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_orders","M");
        $this->load->model("mdl_report","MR");
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

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        $this->detail_store     = get_detail_store($this->store_id);
        $this->store_name       = $this->detail_store->store_name;
        $this->store_phone      = $this->detail_store->store_phone;
        $this->store_product    = $this->detail_store->store_product;
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
    function _reset_backup(){
        $this->jCfg['search'] = array(
            'class'     => $this->_getClass(),
            'name'      => 'transaction',
            'date_start'=> '',
            'date_end'  => '',
            'status'    => '',
            'order_by'  => 'mt_orders_bk.orders_date',
            'order_dir' => 'desc',
            'filter'    => '25',
            'colum'     => '',
            'keyword'   => '',
            'orders_courier_id' => NULL
        );
        $this->_releaseSession();
    }

    // INJECT
    function recalc_orders_report(){ // JANGAN DIBUANG
        echo 'Begin<br>';
        $this->db->select("mt_orders.*");
        $this->db->group_by('DATE(mt_orders.orders_date)');
        $this->db->order_by("orders_date", "ASC");
        $m = $this->db->get("mt_orders")->result();
        if(count($m) > 0){
            foreach ($m as $k => $v) {
                $date_start = $v->orders_date;
                echo $date_start."<br>";
                insert_orders_report($date_start);
            }
        } else {
            echo 'Tidak ditemukan..';
        }
    }

    function recalc_price_orders(){ // JANGAN DIBUANG
        echo 'Begin<br>';
        $r = $this->db->order_by('orders_id','desc')->get_where("mt_orders",array(
            "status_recalc"   => 0,
            "orders_status <"   => 8
        ),1000,0)->result();
        if(count($r) > 0){
            foreach ($r as $k => $v) {
                $data2 = array();
                $orders_id = $v->orders_id;

                $r2 = $this->db->get_where("mt_orders_detail",array(
                    "orders_id"   => $orders_id
                ))->result();
                if(count($r2) > 0){
                    $totalBuy   = 0;
                    $totalPrice = 0;
                    foreach ($r2 as $k2 => $v2) {
                        $totalBuy   = $totalBuy + ($v2->product_price_buy * $v2->orders_detail_qty);
                        $totalPrice = $totalPrice + ($v2->orders_detail_price * $v2->orders_detail_qty);
                    }

                    // $grandTotal = ($totalPrice + $v->orders_price_shipping + $v->orders_price_insurance) - ($v->orders_price_debet_ship + $v->orders_voucher_price);
                    $grandTotal = ($totalPrice + $v->orders_price_shipping + $v->orders_price_insurance) - ($v->orders_voucher_price + $v->orders_price_return);

                    $orders_price_debet_ship = 0;
                    $isPriceDebetCourier = isPriceDebetCourier($v->orders_source_id, $v->orders_courier_id);
                    if($isPriceDebetCourier){
                        $orders_price_debet_ship  = $v->orders_price_shipping;
                        $grandTotal = $totalPrice - ($v->orders_voucher_price + $v->orders_price_return);
                    }

                    $data2 = array(
                        'orders_price_buy_total'   => $totalBuy,
                        'orders_price_product'     => $totalPrice,
                        'orders_price_grand_total' => $grandTotal,
                        'orders_price_debet_ship'  => $orders_price_debet_ship
                    );

                    $data3 = array(
                        'orders_payment_price'       => 0,
                        'orders_payment_grand_total' => $grandTotal
                    );
                    $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
                }

                $data2['status_recalc'] = 1;
                $this->db->update("mt_orders",$data2,array("orders_id"=>$orders_id));
                echo $orders_id.'<br>';
            }
        } else {
            echo 'Tidak ditemukan..';
        }
    }

    function cek_again_claim(){
        $r = $this->db->get_where("mt_orders",array(
            "store_id"              => $this->store_id,
            "orders_claim_status"   => 1
        ),500,0)->result();
        if(count($r) > 0){
            foreach ($r as $k => $v) {
                $orders_id = $v->orders_id;
                $arrPriceToCourier = array('8');
                if(in_array($v->orders_courier_id, $arrPriceToCourier)){
                    $orders_payment_grand_total = $v->orders_price_grand_total - $v->orders_shipping_price;

                    $data1['orders_price_shipping']      = $v->orders_shipping_price;
                    $data1['orders_price_debet_ship']    = $v->orders_shipping_price;
                    $data1['orders_price_grand_total']   = $orders_payment_grand_total;
                    $data3['orders_payment_grand_total'] = $orders_payment_grand_total;
                    $data3['orders_payment_price']       = $orders_payment_grand_total;
                    $data1['orders_claim_status'] = 0;
                    $data1['orders_claim_price']  = 0;

                    $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));
                    $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));
                    $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
                }
            }
        } else {
            echo 'Tidak ditemukan..';
        }
    }

    function inject_status_kirim_ke_selesai(){
        $r = $this->db->get_where("mt_orders",array(
            "store_id"           => $this->store_id,
            "orders_status"      => 5,
            "orders_source_id"   => 8,
            "orders_date <= "   => '2018-04-31 23:59:59'
        ),500,0)->result();
        if(count($r) > 0){
            foreach ($r as $k => $v) {
                $orders_id = $v->orders_id;
                $orders_payment_grand_total = $v->orders_price_grand_total;

                $data1 = array();
                $data2 = array();
                $data3 = array();

                $data1['orders_price_debet_ship'] = 0;
                $isPriceDebetCourier = isPriceDebetCourier($v->orders_source_id, $v->orders_courier_id);
                if($isPriceDebetCourier){
                    $data1['orders_price_debet_ship'] = $v->orders_price_shipping;
                    $orders_payment_grand_total = $v->orders_price_product;
                }

                $data1['orders_status']       = 8;
                $data2['orders_shipping_status'] = 8;
                $data3['orders_payment_status'] = 3;
                $data3['orders_payment_price']  = $orders_payment_grand_total;
                $data3['orders_payment_date']   = timestamp();


                $data2['orders_shipping_price']      = $v->orders_shipping_price;
                $data1['orders_price_shipping']      = $v->orders_price_shipping;
                $data1['orders_price_grand_total']   = $orders_payment_grand_total;
                $data3['orders_payment_grand_total'] = $orders_payment_grand_total;

                $data1['orders_claim_status'] = 0;
                $data1['orders_claim_price']  = 0;

                $data1['date_notify'] = timestamp();
                $data2['date_notify'] = timestamp();
                $data3['date_notify'] = timestamp();

                $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));
                $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));
                $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));

                insert_orders_timestamp($orders_id, 8);
            }
        } else {
            echo 'Tidak ditemukan..';
        }
    }

    function inject_delete_orders(){
        echo "inject_delete_orders </br>";
        $result = "";

        // $this->db->delete("mt_orders_bk",array('orders_id >=' => "9960"));

        $this->store_id = 1;
        $m = $this->db->get_where("mt_orders",array(
                // "store_id"           => $this->store_id,
                "orders_date <= "    => '2018-12-31 23:59:59'
            ),500,0)->result();

        debugCode($m);
        if(count($m) > 0){
            $orders_id = NULL;
            foreach ($m as $key => $val) {
                $orders_id = $val->orders_id;

                // HAPUS mt_orders_detail
                $this->db->delete("mt_orders_detail",array('orders_id' => $orders_id));
                // HAPUS mt_orders_payment
                $this->db->delete("mt_orders_payment",array('orders_id' => $orders_id));
                // HAPUS mt_orders_shipping
                $this->db->delete("mt_orders_shipping",array('orders_id' => $orders_id));
                // HAPUS mt_orders_timestamp
                $this->db->delete("mt_orders_timestamp",array('orders_id' => $orders_id));
                // HAPUS mt_orders
                $this->db->delete("mt_orders",array('orders_id' => $orders_id));

                echo "- ".$orders_id."</br>";
            }
        }

        // debugCode($m);
    }

    function test_get_package(){
        echo "test_get_package";
        $result = "";

        $par_filter = array(
            "noted_type"        => "3,4",
            "order_dir"         => "DESC",
            "offset"            => "0",
            "limit"             => "",
            "param"             => ""
        );
        $result = $this->M->data_courier_payment($par_filter);

        debugCode($result);
    }

    function test_orders_list(){
        $i = 0;
        // $colum = "";
        // $param = array(
        //     ''                                         => 'Semua Pencarian...',
        //     'mt_orders.orders_code'                    => 'No Order',
        //     'mt_orders.orders_invoice'                 => 'Invoice',
        //     'mt_orders.orders_source_invoice'          => 'Marketplace Invoice',
        //     'mt_orders_shipping.orders_shipping_name'  => 'Nama Customer',
        //     'mt_orders_shipping.orders_shipping_email' => 'Email Customer',
        //     'mt_orders_shipping.orders_shipping_phone' => 'Hp Customer',
        // );

        $orders_id = "";
        // $orders_id = "6248";
        $store_id = 1;
        $orders_print = "0";
        $orders_status_id = "3";
        $orders_source_id = "";
        $orders_child_courier_id = "";
        $type_result = "list_app";
        $date_start = "";
        $date_end = "";
        $order_by = "orders_date";
        $order_dir = "desc";
        $offset = "0";
        $limit = "5";
        $keyword = "";

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
        debugCode($data);
    }

    // function inject_price_to_courier(){
    //     $i = 0;
    //     $this->db->select("mt_orders_shipping.*");
    //     $this->db->where("mt_orders_shipping.orders_shipping_price_to_courier >", 0);
    //     $this->db->order_by("mt_orders_shipping.orders_id", "asc");
    //     // $this->db->limit(500);
    //     $m = $this->db->get("mt_orders_shipping")->result();
    //     if(count($m) > 0){
    //         foreach ($m as $k => $r) {
    //             $m2 = $this->db->get_where("mt_orders",array(
    //                 "orders_id" => $r->orders_id
    //             ),1,0)->row();

    //             $data1['orders_price_debet_ship'] = $r->orders_shipping_price_to_courier;
    //             // $isPriceDebetCourier = isPriceDebetCourier($m2->orders_source_id, $m2->orders_courier_id);
    //             // if($isPriceDebetCourier){
    //             //     $data1['orders_price_debet_ship'] = $r->orders_shipping_price;
    //             // }
    //             // debugCode($data1['orders_price_debet_ship']);
    //             // $data1['inject_status'] = 1;
    //             $this->db->update("mt_orders",$data1,array("orders_id"=>$r->orders_id));
    //             $this->db->update("mt_orders_shipping",array("orders_shipping_price_to_courier"=>0),array("orders_shipping_id"=>$r->orders_shipping_id));

    //             $i += 1;
    //             echo $i.' '.$r->orders_id.'<br>';
    //         }
    //     }
    // }

    function test_orders_booked(){
        $i = 0;
        $result = array();
        $orders_id = "";
        // $orders_id = "6248";
        $store_id = 1;
        $orders_status = "";
        $orders_source_id = "";
        $orders_child_courier_id = "";
        $type_result = "simpleview";
        $date_start = "";
        $date_end = "";
        $order_by = "orders_date";
        $order_dir = "desc";
        $offset = "0";
        $limit = "5";
        $keyword = "";
        $par_filter = array(
            "store_id"            => ($store_id!=""?$store_id:'1'),
            "orders_status"       => ($orders_status!=""?$orders_status:NULL),
            "order_by"            => ($order_by!=""?$order_by:''),
            "order_dir"           => ($order_dir!=""?$order_dir:''),
            "offset"              => ($offset!=0?$offset:0),
            "limit"               => ($limit!=0?$limit:1000),
            "param"               => NULL
        );
        $data_orders = $this->M->data_orders($par_filter);
        foreach ($data_orders['data'] as $key => $val) {
            $result[$key] = array(
                'orders_id'             => $val->orders_id,
                'orders_code'           => $val->orders_code,
                'orders_invoice'        => $val->orders_invoice,
                'orders_date'           => $val->orders_date,
                'orders_status'         => $val->orders_status,
                'orders_source_id'      => $val->orders_source_id,
                'orders_source_name'    => get_orders_source($val->orders_source_id)->orders_source_name,
                'orders_source_invoice' => $val->orders_source_invoice,
                'orders_noted'          => $val->orders_noted,
                'orders_shipping_name'  => $val->orders_shipping_name
            );
        }
        debugCode($result);

        // $orders_id = "";
        // // $orders_id = "6248";
        // $store_id = 1;
        // $orders_status_id = "";
        // $orders_source_id = "";
        // $orders_child_courier_id = "";
        // $date_start = "";
        // $date_end = "";
        // $order_by = "";
        // $order_dir = "desc";
        // $offset = "0";
        // $limit = "";
        // $keyword = "";
        // $colum = "";
        // $param = array(
        //     ''                             => 'Semua Pencarian...',
        //     'orders_source_invoice'        => 'Marketplace Invoice'
        // );

        // $par_filter = array(
        //     "member_type"         => ($member_type!=""?$member_type:''),
        //     "store_id"            => ($store_id!=""?$store_id:''),
        //     "orders_source_id"    => $orders_source_id,
        //     "get_all"             => TRUE,
        //     "type_result"         => "",
        //     "date_start"          => ($date_start!=""?$date_start:''),
        //     "date_end"            => ($date_end!=""?$date_end:''),
        //     "order_by"            => ($order_by!=""?$order_by:''),
        //     "order_dir"           => ($order_dir!=""?$order_dir:''),
        //     "offset"              => ($offset!=0?$offset:0),
        //     "limit"               => ($limit!=0?$limit:1000),
        //     "colum"               => ($colum!=""?$colum:''),
        //     "keyword"             => ($keyword!=""?$keyword:''),
        //     "param"               => ($param!=""?$param:NULL)
        // );

        // // $data_orders = $this->M->data_orders_booked($par_filter);

        // $data_orders = get_orders_product_detail($store_id, 0);
        // debugCode($data_orders);
    }

    // function cek_city_province(){
    //     $i = 0;
    //     $this->db->select("mt_orders_shipping.*");

    //     // $this->db->where("mt_orders.orders_source_id", 8);
    //     $this->db->where("mt_orders_shipping.orders_id !=", 0);
    //     $this->db->order_by("mt_orders_shipping.orders_id", "asc");
    //     // $this->db->limit(500);
    //     $this->db->group_by("mt_orders_shipping.orders_shipping_city");
    //     $m = $this->db->get("mt_orders_shipping")->result();
    //     if(count($m) > 0){
    //         foreach ($m as $k => $r) {
    //             // $data1 = array(
    //             //     'orders_shipping_city'           => ltrim(strtoupper($r->orders_shipping_city)," "),
    //             //     'orders_shipping_province'       => ltrim(strtoupper($r->orders_shipping_province)," ")
    //             // );
    //             // $this->db->update("mt_orders_shipping",$data1,array("orders_shipping_id"=>$r->orders_shipping_id));

    //             $i += 1;
    //             echo $i.' '.$r->orders_id.' '.$r->orders_shipping_city.'<br>';
    //         }
    //     }
    // }
    // function update_city_province(){
    //     $i = 0;
    //     $this->db->select("mt_orders_shipping.*");

    //     // $this->db->where("mt_orders.orders_source_id", 8);
    //     $this->db->where("mt_orders_shipping.orders_id !=", 0);
    //     $this->db->order_by("mt_orders_shipping.orders_id", "asc");
    //     // $this->db->limit(500);
    //     $m = $this->db->get("mt_orders_shipping")->result();
    //     if(count($m) > 0){
    //         foreach ($m as $k => $r) {
    //             $data1 = array(
    //                 'orders_shipping_city'           => ltrim(strtoupper($r->orders_shipping_city)," "),
    //                 'orders_shipping_province'       => ltrim(strtoupper($r->orders_shipping_province)," ")
    //             );
    //             $this->db->update("mt_orders_shipping",$data1,array("orders_shipping_id"=>$r->orders_shipping_id));

    //             $i += 1;
    //             echo $i.' '.$r->orders_id.'<br>';
    //         }
    //     }
    // }
    // function update_city_province(){
    //     $i = 0;
    //     $this->db->select("mt_orders.*, mt_orders_shipping.*");
    //     $this->db->join("mt_orders_shipping","mt_orders_shipping.orders_id = mt_orders.orders_id",'left');

    //     // $this->db->where("mt_orders.orders_source_id", 8);
    //     $this->db->where("mt_orders_shipping.orders_shipping_province", NULL);
    //     $this->db->order_by("mt_orders.orders_id", "asc");
    //     // $this->db->limit(500);
    //     $m = $this->db->get("mt_orders")->result();
    //     if(count($m) > 0){
    //         foreach ($m as $k => $r) {
    //             $address = $r->orders_shipping_address;
    //             if( $address != ""){
    //                 if($r->orders_source_id == 3 || $r->orders_source_id == 2 || $r->orders_source_id == 11 || $r->orders_source_id == 8){
    //                     $get_split_address = get_split_address($r->orders_source_id, $address);

    //                     $data1 = array(
    //                         'orders_shipping_city'           => strtoupper($get_split_address['city']),
    //                         'orders_shipping_province'       => strtoupper($get_split_address['province']),
    //                         'orders_shipping_postal_code'    => $get_split_address['postal_code']
    //                     );
    //                     $this->db->update("mt_orders_shipping",$data1,array("orders_shipping_id"=>$r->orders_shipping_id));

    //                     $i += 1;
    //                     echo $i.' '.$r->orders_id.'<br>';
    //                 }
    //             }
    //         }
    //     }
    // }

    // function inject_temp_address(){
    //     $i = 0;
    //     $m = $this->db->order_by('print_address_id','asc')->get_where("_mt_print_address",array(
    //         "print_address_istrash"      => 0,
    //         "orders_courier_id !="       => 0
    //     ),500,0)->result();
    //     if(count($m) > 0){
    //         foreach ($m as $k => $r) {

    //             $m2 = $this->db->get_where("mt_orders",array(
    //                 "store_id"              => $r->store_id,
    //                 "orders_source_id"      => $r->orders_source_id,
    //                 "orders_source_invoice" => strtoupper($r->orders_invoice)
    //             ),1,0)->result();
    //                 // debugCode(count($m2));
    //             if(empty($m2)){
    //                     switch ($r->orders_courier_id) {
    //                         case '1': $orders_courier_id = '3'; break;
    //                         case '2': $orders_courier_id = '4'; break;
    //                         case '3': $orders_courier_id = '17'; break;
    //                         case '4': $orders_courier_id = '18'; break;
    //                         case '5': $orders_courier_id = '20'; break;
    //                         case '6': $orders_courier_id = '21'; break;
    //                         case '7': $orders_courier_id = '10'; break;
    //                         case '8': $orders_courier_id = '6'; break;
    //                         case '9': $orders_courier_id = '8'; break;
    //                         case '10': $orders_courier_id = '2'; break;
    //                         default: break;
    //                     }
    //                 $create_orders_code = create_orders_code($r->print_address_date);
    //                 $data2 = array(
    //                     'orders_code'                   => $create_orders_code['orders_code'],
    //                     'orders_invoice'                => $create_orders_code['orders_invoice'],
    //                     'member_type'                   => 2,
    //                     'member_id'                     => ($r->store_id=='2'?'4':'1'),
    //                     'store_id'                      => $r->store_id,
    //                     'orders_status'                 => 5,
    //                     'orders_source_id'              => $r->orders_source_id,
    //                     'orders_source_invoice'         => strtoupper($r->orders_invoice),
    //                     'orders_price_buy_total'        => 0,
    //                     'orders_price_product'          => 0,
    //                     'orders_price_shipping'         => $r->orders_shipping_price,
    //                     'orders_price_insurance'        => $r->orders_insurance,
    //                     'orders_price_ppn'              => 0,
    //                     'orders_price_grand_total'      => 0,
    //                     'orders_voucher_price'          => 0,
    //                     'orders_voucher_code'           => NULL,
//                         'orders_courier_id'             => $orders_courier_id,
    //                     'orders_print'                  => 1,
    //                     'orders_product_detail'         => 0,
    //                     'ip_address'                    => $_SERVER['REMOTE_ADDR'],
    //                     'user_agent'                    => $_SERVER['HTTP_USER_AGENT'],
    //                     'notify'                        => 0,
    //                     'orders_noted'                  => $r->orders_noted,
    //                     'date_notify'                   => $r->print_address_date,
    //                     'orders_date'                   => $r->print_address_date
    //                 );

    //                 $this->DATA->table="mt_orders";
    //                 $a2 = $this->_save_master(
    //                     $data2,
    //                     array(
    //                         'orders_id' => ''
    //                         ),
    //                     ''
    //                     );

    //                 $id = $a2['id'];
    //                 if($id != ""){

    //                     // SAVE MT_ORDERS_PAYMENT
    //                     $create_payment_code = create_payment_code($r->print_address_date);
    //                     $data4 = array(
    //                         'orders_id'                     => $id,
    //                         'orders_payment_code'           => $create_payment_code['payment_code'],
    //                         'orders_payment_method'         => 3,
    //                         'orders_payment_price'          => 0,
    //                         'orders_payment_grand_total'    => 0,
    //                         'orders_payment_status'         => 2,
    //                         'notify'                        => 0,
    //                         'date_notify'                   => $r->print_address_date,
    //                         'orders_payment_date'           => $r->print_address_date
    //                     );

    //                     $this->DATA->table="mt_orders_payment";
    //                     $a4 = $this->_save_master(
    //                         $data4,
    //                         array(
    //                             'orders_payment_id' => ''
    //                         ),
    //                         ''
    //                     );

    //                     // SAVE MT_ORDERS_SHIPPING
    //                     $data5 = array(
    //                         'orders_id'                     => $id,
    //                         'orders_shipping_status'        => 5,
    //                         'orders_shipping_method'        => 1,
    //                         'orders_shipping_dropship'      => $r->orders_dropship,
    //                         'orders_ship_name'              => ($r->orders_dropship==1?ucwords($r->orders_ship_name):NULL),
    //                         'orders_ship_phone'             => ($r->orders_dropship==1?ucwords($r->orders_ship_phone):NULL),
    //                         'orders_shipping_username'      => strtolower($r->orders_shipping_name),
    //                         'orders_shipping_name'          => ucwords($r->orders_shipping_name),
    //                         'orders_shipping_phone'         => $r->orders_shipping_phone,
    //                         'orders_shipping_email'         => NULL,
    //                         'orders_shipping_address'       => $r->orders_shipping_address,
    //                         'orders_shipping_city'          => NULL,
    //                         'orders_shipping_province'      => NULL,
    //                         'orders_shipping_postal_code'   => 0,
    //                         'orders_product_category_title' => $r->orders_product_category_title,
    //                         'orders_shipping_price'         => $r->orders_shipping_price,
    //                         'orders_shipping_weight'        => $r->orders_shipping_weight,
    //                         'orders_shipping_resi'          => strtoupper($r->orders_resi),
    //                         'notify'                        => 0,
    //                         'date_notify'                   => $r->print_address_date,
    //                         'orders_shipping_date'          => $r->print_address_date
    //                     );

    //                     $this->DATA->table="mt_orders_shipping";
    //                     $a5 = $this->_save_master(
    //                         $data5,
    //                         array(
    //                             'orders_shipping_id' => ''
    //                         ),
    //                         ''
    //                     );

    //                     // SAVE MT_ORDERS_TIMESTAMP
    //                     $arr_orders_timestamp   = array();
    //                     $arr_orders_timestamp[] = array("id" => "3", "date" => $r->print_address_date );
    //                     $arr_orders_timestamp[] = array("id" => "4", "date" => $r->print_address_date );
    //                     $arr_orders_timestamp[] = array("id" => "5", "date" => $r->print_address_date );

    //                     $data6 = array(
    //                         'orders_id'                 => $id,
    //                         'orders_timestamp_desc'     => json_encode($arr_orders_timestamp)
    //                     );
    //                     $this->DATA->table="mt_orders_timestamp";
    //                     $a6 = $this->_save_master(
    //                         $data6,
    //                         array(
    //                             'orders_timestamp_id' => ''
    //                         ),
    //                         ''
    //                     );

    //                     $data30 = array(
    //                         'print_address_istrash'       => 2
    //                     );
    //                     $this->db->update("_mt_print_address",$data30,array("print_address_id"=>$r->print_address_id));

    //                 }
    //             }
    //         }
    //     }
    //     echo 'Sukses';
    // }


}
