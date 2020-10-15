<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class transaction_detail_product extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        // $this->DATA->table="mt_orders";
        // $this->folder_view = "orders/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_transaction_detail_product","MTDP");

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        // $this->detail_store     = get_detail_store($this->store_id);
        // $this->store_name       = $this->detail_store->store_name;
        // $this->store_phone      = $this->detail_store->store_phone;
        // $this->store_product    = $this->detail_store->store_product;
    }

    function get_detail_product(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $orders_id  = dbClean(trim($_POST['thisVal']));
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "orders_id"           => ($orders_id!=""?$orders_id:'0')
            );

            $result = $this->MTDP->data_get_detail_product($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

    function form_detail_product(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $orders_id  = dbClean(trim($_POST['thisVal']));
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "orders_id"           => ($orders_id!=""?$orders_id:'0')
            );

            $result = $this->MTDP->data_transaction_detail_product($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

    function form_add_detail_product(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $orders_id  = dbClean(trim($_POST['thisVal']));
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "orders_id"           => ($orders_id!=""?$orders_id:'0')
            );

            $result = $this->MTDP->data_transaction_add_detail_product($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

    function save_add_detail_product(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $orders_id       = dbClean(trim($_POST['orders_id']));
            $product_barcode = dbClean(trim($_POST['product_barcode']));
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "orders_id"           => ($orders_id!=""?$orders_id:''),
                "product_barcode"     => ($product_barcode!=""?$product_barcode:'')
            );

            $result = $this->MTDP->data_transaction_save_detail_product($par_filter);
            $data['content'] = $result['data'];
            $data['err']     = $result['error'];
            $data['msg']     = $result['msg'];
            set_last_date_product_setup();
        }

        die(json_encode($data));
        exit();
    }

    function form_delete_detail_product(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $orders_id  = dbClean(trim($_POST['thisVal']));
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "orders_id"           => ($orders_id!=""?$orders_id:'')
            );

            $result = $this->MTDP->data_transaction_del_detail_product($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

    function save_delete_detail_product(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['href']   = '';

        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'delete' ){
            $orders_id    = dbClean($_POST['thisId']);
            $thisDetailId = dbClean($_POST['thisDetailId']);
            $thisItemId   = dbClean($_POST['thisItemId']);
            $thisItemName = dbClean($_POST['thisItemName']);
            $thisItemQty  = dbClean($_POST['thisItemQty']);
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "orders_id"           => ($orders_id!=""?$orders_id:''),
                "thisDetailId"        => ($thisDetailId!=""?$thisDetailId:''),
                "thisItemId"          => ($thisItemId!=""?$thisItemId:''),
                "thisItemName"        => ($thisItemName!=""?$thisItemName:''),
                "thisItemQty"         => ($thisItemQty!=""?$thisItemQty:'')
            );

            $result = $this->MTDP->data_transaction_save_del_detail_product($par_filter);
            $data['content'] = $result['data'];
            $data['err']     = $result['error'];
            $data['msg']     = $result['msg'];
        }

        die(json_encode($data));
        exit();
    }

    function form_detail_product_archive(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $orders_id  = dbClean(trim($_POST['thisVal']));
            $par_filter = array(
                "user_id"             => $this->user_id,
                "store_id"            => $this->store_id,
                "orders_id"           => ($orders_id!=""?$orders_id:'')
            );

            $result = $this->MTDP->data_transaction_detail_product_archive($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

}
