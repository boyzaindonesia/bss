<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class transaction_form extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        // $this->DATA->table="mt_orders";
        // $this->folder_view = "orders/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_transaction_form","MTF");

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        $this->detail_store     = get_detail_store($this->store_id);
        $this->store_name       = $this->detail_store->store_name;
        $this->store_phone      = $this->detail_store->store_phone;
        $this->store_product    = $this->detail_store->store_product;
    }

    function form_label(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $orders_id  = dbClean(trim($_POST['thisVal']));
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "store_name"          => $this->store_name,
                "store_phone"         => $this->store_phone,
                "store_product"       => $this->store_product,
                "orders_id"           => ($orders_id!=""?$orders_id:'')
            );

            $result = $this->MTF->data_transaction_form($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

    function form_detail_courier(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $orders_id  = dbClean(trim($_POST['thisVal']));
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "store_name"          => $this->store_name,
                "store_phone"         => $this->store_phone,
                "store_product"       => $this->store_product,
                "orders_id"           => ($orders_id!=""?$orders_id:'')
            );

            $result = $this->MTF->data_transaction_form_detail_courier($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

    function save_form_label(){
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
