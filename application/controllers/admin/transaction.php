<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class transaction extends AdminController {
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
    function _reset_archive(){
        $this->jCfg['search'] = array(
            'class'     => $this->_getClass(),
            'name'      => 'transaction',
            'date_start'=> '',
            'date_end'  => '',
            'status'    => '',
            'order_by'  => 'mt_orders_archive.orders_date',
            'order_dir' => 'desc',
            'filter'    => '25',
            'colum'     => '',
            'keyword'   => '',
            'orders_courier_id' => NULL
        );
        $this->_releaseSession();
    }

    function index(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'transaction'){
            $this->_reset();
        }

        // debugCode('aa');
        redirect($this->own_link.'/new_orders');
    }

    function confirm_payment(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'orders_confirm_payment'){
            $this->_reset();
            $this->jCfg['search']['name'] = 'orders_confirm_payment';
            $this->_releaseSession();
        }

        $data = array();

        $this->orders_status = 2;
        $orders_status       = get_orders_status($this->orders_status);
        $name                = $orders_status['name'];
        $url                 = $orders_status['url'];

        $this->_set_title( $name );
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

        $this->orders_source_id = 1;

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

        $par_filter = array(
            "store_id"            => $this->store_id,
            "orders_status"       => $this->orders_status,
            "orders_source_id"    => $this->orders_source_id,
            "orders_courier_id"   => $this->orders_child_courier_id,
            "get_all"             => TRUE,
            "type_result"         => "",
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

        $data['url']            = base_url()."admin/transaction/confirm_payment";
        $data['url_form']       = base_url()."admin/transaction/confirm_payment";
        $data['tab']            = 'tab'.$this->orders_status;
        $data['content_layout'] = $this->prefix_view."_confirm_payment.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function new_orders(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'orders_new_orders'){
            $this->_reset();
            $this->jCfg['search']['name'] = 'orders_new_orders';
            $this->_releaseSession();
        }

        check_product_by_group();
        autoClearAppSessions();

        $data = array();

        $this->orders_status = 3;
        $orders_status       = get_orders_status($this->orders_status);
        $name                = $orders_status['name'];
        $url                 = $orders_status['url'];

        $this->_set_title( $name );
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
            "offset"              => "0",
            "limit"               => "100",
            "keyword"             => NULL,
            "colum"               => NULL,
            "param"               => NULL
        );

        $data = $this->M->data_orders($par_filter);
        // debugCode($data);

        $data['url']            = base_url()."admin/transaction/new_orders";
        $data['url_form']       = base_url()."admin/transaction/new_orders";
        $data['tab']            = 'tab'.$this->orders_status;
        $data['content_layout'] = $this->prefix_view."_new_orders.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
        // $this->_v($this->folder_view.$this->prefix_view."_new_orders.php",$data);
    }

    function confirm_shipping(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'orders_confirm_shipping'){
            $this->_reset();
            $this->jCfg['search']['name']      = 'orders_confirm_shipping';
            $this->jCfg['search']['order_by']  = 'mt_orders.orders_date';
            $this->jCfg['search']['order_dir'] = 'asc';
            $this->jCfg['search']['filter'] = 25;
            $this->_releaseSession();
        }

        $data = array();

        $this->orders_status = 4;
        $orders_status       = get_orders_status($this->orders_status);
        $name                = $orders_status['name'];
        $url                 = $orders_status['url'];

        $this->_set_title( $name );
        $this->breadcrumb[] = array(
            "title"     => $name
        );

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
            if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
                $this->jCfg['search']['date_start'] = $this->input->post('date_start');

            if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
                $this->jCfg['search']['date_end'] = $this->input->post('date_end');

            if($this->input->post('colum') && trim($this->input->post('colum'))!="")
                $this->jCfg['search']['colum'] = $this->input->post('colum');
            else
                $this->jCfg['search']['colum'] = "";

            if($this->input->post('keyword') && trim($this->input->post('keyword'))!="")
                $this->jCfg['search']['keyword'] = $this->input->post('keyword');
            else
                $this->jCfg['search']['keyword'] = "";

            $this->_releaseSession();
        }

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'reset'){
            $this->_reset();
        }

        $order_by = $this->jCfg['search']['order_by'];
        // $_POST['order_by'] = 'mt_orders.orders_date - desc';
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
            $explode_order_by = explode("-", $_POST['order_by']);
            $this->jCfg['search']['order_by'] = $explode_order_by[0];
            $this->jCfg['search']['order_dir'] = $explode_order_by[1];
            $this->_releaseSession();
        }
        if(isset($_POST['filter'])){
            $this->jCfg['search']['filter'] = $_POST['filter'];
            $this->_releaseSession();
        }
        $this->per_page = $this->jCfg['search']['filter'];

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

        $par_filter = array(
            "store_id"            => $this->store_id,
            "orders_status"       => $this->orders_status,
            "orders_source_id"    => $this->orders_source_id,
            "orders_courier_id"   => $this->orders_child_courier_id,
            "get_all"             => TRUE,
            "type_result"         => "list",
            "date_start"          => NULL,
            "date_end"            => NULL,
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => $this->uri->segment($this->uri_segment),
            "limit"               => $this->per_page,
            "keyword"             => $this->jCfg['search']['keyword'],
            "colum"               => $this->jCfg['search']['colum'],
            "param"               => $this->cat_search
        );

        $this->data_table = $this->M->data_orders($par_filter);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/shipping'
        ));
        // debugCode($this->data_table);

        $data['url']            = base_url()."admin/transaction/confirm_shipping";
        $data['url_form']       = base_url()."admin/transaction/confirm_shipping";
        $data['tab']            = 'tab'.$this->orders_status;
        $data['content_layout'] = $this->prefix_view."_confirm_shipping.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function shipping(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'orders_shipping'){
            $this->_reset();
            $this->jCfg['search']['name']      = 'orders_shipping';
            $this->jCfg['search']['order_by']  = 'mt_orders.orders_date';
            $this->jCfg['search']['order_dir'] = 'asc';
            $this->jCfg['search']['filter'] = 25;
            $this->_releaseSession();
        }

        $data = array();

        $this->orders_status = 5;
        $orders_status    = get_orders_status($this->orders_status);
        $name             = $orders_status['name'];
        $url              = $orders_status['url'];

        $this->_set_title( $name );
        $this->breadcrumb[] = array(
            "title"     => $name
        );

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
            if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
                $this->jCfg['search']['date_start'] = convDatepickerDec($this->input->post('date_start'));

            if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
                $this->jCfg['search']['date_end'] = convDatepickerDec($this->input->post('date_end'));


            if($this->input->post('colum') && trim($this->input->post('colum'))!="")
                $this->jCfg['search']['colum'] = $this->input->post('colum');
            else
                $this->jCfg['search']['colum'] = "";

            if($this->input->post('keyword') && trim($this->input->post('keyword'))!="")
                $this->jCfg['search']['keyword'] = $this->input->post('keyword');
            else
                $this->jCfg['search']['keyword'] = "";

            $this->_releaseSession();
        }

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'reset'){
            $this->_reset();
        }

        $order_by = $this->jCfg['search']['order_by'];
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
            $explode_order_by = explode("-", $_POST['order_by']);
            $this->jCfg['search']['order_by'] = $explode_order_by[0];
            $this->jCfg['search']['order_dir'] = $explode_order_by[1];
            $this->_releaseSession();
        }
        if(isset($_POST['filter'])){
            $this->jCfg['search']['filter'] = $_POST['filter'];
            $this->_releaseSession();
        }
        $this->per_page = $this->jCfg['search']['filter'];

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

        $par_filter = array(
            "store_id"            => $this->store_id,
            "orders_status"       => $this->orders_status,
            "orders_source_id"    => $this->orders_source_id,
            "orders_courier_id"   => $this->orders_child_courier_id,
            "get_all"             => TRUE,
            "type_result"         => "list",
            "date_start"          => $this->jCfg['search']['date_start'],
            "date_end"            => $this->jCfg['search']['date_end'],
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => $this->uri->segment($this->uri_segment),
            "limit"               => $this->per_page,
            "keyword"             => $this->jCfg['search']['keyword'],
            "colum"               => $this->jCfg['search']['colum'],
            "param"               => $this->cat_search
        );

        $this->data_table = $this->M->data_orders($par_filter);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/shipping'
        ));
        // debugCode($this->data_table);
        // debugCode($data);

        $data['url']            = base_url()."admin/transaction/shipping";
        $data['url_form']       = base_url()."admin/transaction/shipping";
        $data['tab']            = 'tab'.$this->orders_status;
        $data['content_layout'] = $this->prefix_view."_shipping.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function list_all(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'orders_list_all'){
            $this->_reset();
            $this->jCfg['search']['name'] = 'orders_list_all';
            $this->_releaseSession();
        }

        $this->breadcrumb[] = array(
            "title"     => 'Semua List'
        );

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
            if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
                $this->jCfg['search']['date_start'] = convDatepickerDec($this->input->post('date_start'));

            if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
                $this->jCfg['search']['date_end'] = convDatepickerDec($this->input->post('date_end'));


            if($this->input->post('colum') && trim($this->input->post('colum'))!="")
                $this->jCfg['search']['colum'] = $this->input->post('colum');
            else
                $this->jCfg['search']['colum'] = "";

            if($this->input->post('keyword') && trim($this->input->post('keyword'))!="")
                $this->jCfg['search']['keyword'] = $this->input->post('keyword');
            else
                $this->jCfg['search']['keyword'] = "";

            $this->_releaseSession();
        }

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'reset'){
            $this->_reset();
        }

        $order_by = $this->jCfg['search']['order_by'];
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
            $explode_order_by = explode("-", $_POST['order_by']);
            $this->jCfg['search']['order_by'] = $explode_order_by[0];
            $this->jCfg['search']['order_dir'] = $explode_order_by[1];
            $this->_releaseSession();
        }
        if(isset($_POST['filter'])){
            $this->jCfg['search']['filter'] = $_POST['filter'];
            $this->_releaseSession();
        }
        $this->per_page = $this->jCfg['search']['filter'];

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

        if(!isset($this->jCfg['search']['orders_status'])||$this->jCfg['search']['orders_status']==''){
            $this->jCfg['search']['orders_status'] = '';
            $this->_releaseSession();
        }
        if(isset($_POST['orders_status'])){
            if($_POST['orders_status'] == ""){
                $this->jCfg['search']['orders_status'] = '';
            } else {
                $this->jCfg['search']['orders_status'] = $_POST['orders_status'];
            }
            $this->_releaseSession();
        }

        $this->orders_status = $this->jCfg['search']['orders_status'];
        $this->orders_source_id = $this->jCfg['search']['orders_source_id'];

// $this->per_page = 10;
        $par_filter = array(
            "store_id"            => $this->store_id,
            "orders_status"       => $this->orders_status,
            "orders_source_id"    => $this->orders_source_id,
            "orders_courier_id"   => $this->orders_child_courier_id,
            "get_all"             => TRUE,
            "type_result"         => "list",
            "date_start"          => $this->jCfg['search']['date_start'],
            "date_end"            => $this->jCfg['search']['date_end'],
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => $this->uri->segment($this->uri_segment),
            "limit"               => $this->per_page,
            "keyword"             => $this->jCfg['search']['keyword'],
            "colum"               => $this->jCfg['search']['colum'],
            "param"               => $this->cat_search
        );

        $this->data_table = $this->M->data_orders($par_filter);
        // debugCode($this->data_table);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/list_all'
        ));

        $data['url']            = base_url()."admin/transaction/list_all";
        $data['url_form']       = base_url()."admin/transaction/list_all";
        $data['tab']            = 'tab8';
        $data['content_layout'] = $this->prefix_view."_list_all.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function archive(){
        $this->cat_search = array(
            ''                                         => 'Semua Pencarian...',
            'mt_orders_archive.orders_code'                    => 'No Order',
            'mt_orders_archive.orders_invoice'                 => 'Invoice',
            'mt_orders_archive.orders_source_invoice'          => 'Marketplace Invoice',
            'mt_orders_archive_shipping.orders_shipping_username'  => 'Username',
            'mt_orders_archive_shipping.orders_shipping_name'  => 'Nama Customer',
            'mt_orders_archive_shipping.orders_shipping_email' => 'Email Customer',
            'mt_orders_archive_shipping.orders_shipping_phone' => 'Hp Customer',
        );

        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'orders_list_archive'){
            $this->_reset_archive();
            $this->jCfg['search']['name'] = 'orders_list_archive';
            $this->_releaseSession();
        }

        $this->_set_title("Arsip");
        $this->breadcrumb[] = array(
            "title"     => 'Arsip'
        );

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
            if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
                $this->jCfg['search']['date_start'] = convDatepickerDec($this->input->post('date_start'));

            if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
                $this->jCfg['search']['date_end'] = convDatepickerDec($this->input->post('date_end'));


            if($this->input->post('colum') && trim($this->input->post('colum'))!="")
                $this->jCfg['search']['colum'] = $this->input->post('colum');
            else
                $this->jCfg['search']['colum'] = "";

            if($this->input->post('keyword') && trim($this->input->post('keyword'))!="")
                $this->jCfg['search']['keyword'] = $this->input->post('keyword');
            else
                $this->jCfg['search']['keyword'] = "";

            $this->_releaseSession();
        }

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'reset'){
            $this->_reset();
        }

        $order_by = $this->jCfg['search']['order_by'];
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
            $explode_order_by = explode("-", $_POST['order_by']);
            $this->jCfg['search']['order_by'] = $explode_order_by[0];
            $this->jCfg['search']['order_dir'] = $explode_order_by[1];
            $this->_releaseSession();
        }
        if(isset($_POST['filter'])){
            $this->jCfg['search']['filter'] = $_POST['filter'];
            $this->_releaseSession();
        }
        $this->per_page = $this->jCfg['search']['filter'];

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

        if(!isset($this->jCfg['search']['orders_status'])||$this->jCfg['search']['orders_status']==''){
            $this->jCfg['search']['orders_status'] = '';
            $this->_releaseSession();
        }
        if(isset($_POST['orders_status'])){
            if($_POST['orders_status'] == ""){
                $this->jCfg['search']['orders_status'] = '';
            } else {
                $this->jCfg['search']['orders_status'] = $_POST['orders_status'];
            }
            $this->_releaseSession();
        }

        $this->orders_status = $this->jCfg['search']['orders_status'];
        $this->orders_source_id = $this->jCfg['search']['orders_source_id'];

        // $this->per_page = 10;
        $par_filter = array(
            "store_id"            => $this->store_id,
            "orders_status"       => $this->orders_status,
            "orders_source_id"    => $this->orders_source_id,
            "orders_courier_id"   => $this->orders_child_courier_id,
            "get_all"             => TRUE,
            "type_result"         => "list",
            "date_start"          => $this->jCfg['search']['date_start'],
            "date_end"            => $this->jCfg['search']['date_end'],
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => $this->uri->segment($this->uri_segment),
            "limit"               => $this->per_page,
            "keyword"             => $this->jCfg['search']['keyword'],
            "colum"               => $this->jCfg['search']['colum'],
            "param"               => $this->cat_search
        );

        $this->data_table = $this->M->data_orders_archive($par_filter);
        // debugCode($this->data_table);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/archive'
        ));

        $data['url']            = base_url()."admin/transaction/archive";
        $data['url_form']       = base_url()."admin/transaction/archive";
        $data['tab']            = 'tab17';
        $data['content_layout'] = $this->prefix_view."_archive.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function claim(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'orders_claim'){
            $this->_reset();
            $this->jCfg['search']['name'] = 'orders_claim';
            $this->_releaseSession();
        }

        $data = array();

        $this->_set_title( 'Claim' );
        $this->breadcrumb[] = array(
            "title"     => 'Claim'
        );

        $order_by = $this->jCfg['search']['order_by'];
        $_POST['order_by'] = 'mt_orders.orders_claim_date - desc';
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
            $explode_order_by = explode("-", $_POST['order_by']);
            $this->jCfg['search']['order_by'] = $explode_order_by[0];
            $this->jCfg['search']['order_dir'] = $explode_order_by[1];
            $this->_releaseSession();
        }

        if(isset($_POST['orders_source_id'])){
            if($_POST['orders_source_id'] == ""){
                $this->jCfg['search']['orders_source_id'] = NULL;
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

        if(!isset($this->jCfg['search']['orders_claim_status'])){
            $this->jCfg['search']['orders_claim_status'] = '1,2';
            $this->_releaseSession();
        }
        if(isset($_POST['orders_claim_status'])){
            if($_POST['orders_claim_status'] == ""){
                $this->jCfg['search']['orders_claim_status'] = '1,2';
            } else {
                $this->jCfg['search']['orders_claim_status'] = $_POST['orders_claim_status'];
            }
            $this->_releaseSession();
        }
        $this->orders_claim_status = $this->jCfg['search']['orders_claim_status'];

        $par_filter = array(
            "store_id"            => $this->store_id,
            "orders_status"       => NULL,
            "orders_source_id"    => $this->orders_source_id,
            "orders_courier_id"   => $this->orders_child_courier_id,
            "orders_claim_status" => $this->orders_claim_status,
            "get_all"             => FALSE,
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

        $data['url']            = base_url()."admin/transaction/claim";
        $data['url_form']       = base_url()."admin/transaction/claim";
        $data['tab']            = 'tab11';
        $data['content_layout'] = $this->prefix_view."_claim.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }


    function empty_tmp_file(){
        $files = glob('./assets/collections/tmp_files/*');
        foreach($files as $file){
            if(is_file($file))
            unlink($file);
        }
        redirect($this->own_link."?msg=".urlencode("Empty file excel successfully...")."&type_msg=success");
    }

    function verifikasi_payment(){
        $data = array();
        $name = "Verifikasi Pencairan Pembayaran";

        $this->_set_title( $name );
        $this->breadcrumb[] = array(
            "title"     => "Shipping",
            "url"       => $this->own_link.'/shipping'
        );
        $this->breadcrumb[] = array(
            "title"     => $name
        );

        // $this->orders_status = "3,4,5";
        $this->orders_status = "";

        $data['data_price']      = array();
        $data['data_orders']     = array();
        $data['data_done_pay']   = array();
        $data['data_not_found']  = array();
        $data['data_claim']      = array();
        $data['data_mp_payment'] = array();

        $mp = (isset($this->jCfg['marketplace_payment'])?$this->jCfg['marketplace_payment']:array());
        $data['data_mp_payment'] = $mp;
        $data['data_details']    = $this->jCfg['marketplace_payment_details'];
        if(count($mp) > 0){
            foreach ($mp as $key => $val) {
                $par_filter = array(
                    "store_id"              => $this->store_id,
                    "orders_status"         => $this->orders_status,
                    "orders_source_id"      => $val->mp_source_id,
                    "orders_source_invoice" => $val->mp_source_invoice,
                    "get_all"               => FALSE,
                    "type_result"           => ""
                );

                $data_orders = $this->M->data_orders($par_filter)['data'];
                if(count($data_orders) > 0){
                    $arrDetail = array();
                    foreach ($val as $key2 => $val2) {
                        $arrDetail[0]->$key2 = $val2;
                    }
                    foreach ($data_orders[0] as $key2 => $val2) {
                        $arrDetail[0]->$key2 = $val2;
                    }
                    if($arrDetail[0]->orders_status == 8){
                        $data['data_done_pay'][] = $arrDetail[0];
                    } else {
                        $data['data_orders'][] = $arrDetail[0];
                    }
                    if($arrDetail[0]->mp_claim_status == TRUE){
                        $data['data_claim'][] = $arrDetail[0];
                    }
                } else {
                    $arrDetail = array();
                    foreach ($val as $key2 => $val2) {
                        $arrDetail[0]->$key2 = $val2;
                    }
                    $data['data_not_found'][] = $arrDetail[0];
                }
            }
        }
        $this->_v($this->folder_view.$this->prefix_view."_verifikasi_payment.php",$data);
    }

    function verifikasi_payment_claim(){
        $data = array();
        $name = "Verifikasi Pencairan CLaim Pembayaran";

        $this->_set_title( $name );
        $this->breadcrumb[] = array(
            "title"     => "Shipping",
            "url"       => $this->own_link.'/shipping'
        );
        $this->breadcrumb[] = array(
            "title"     => $name
        );

        $data['data_mp_payment'] = array();

        $mp = (isset($this->jCfg['marketplace_payment'])?$this->jCfg['marketplace_payment']:array());
        $data['data_mp_payment'] = $mp;
        $data['data_details']    = $this->jCfg['marketplace_payment_details'];
        $this->_v($this->folder_view.$this->prefix_view."_verifikasi_payment_claim.php",$data);
    }





    // function update_price_multiple(){
    //     $data = array();
    //     $data['err']    = true;
    //     $data['msg']    = '';
    //     $data['href']   = '';

    //     if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
    //         $checked_files = $_POST['checked_files'];
    //         foreach ($checked_files as $k => $v) {
    //             $orders_id = $v;
    //             $orders_price_product  = convertRpToInt2($_POST['orders_price_product'][$orders_id]);
    //             $orders_shipping_price = convertRpToInt2($_POST['orders_shipping_price'][$orders_id]);
    //             $orders_price_grand_total = convertRpToInt2($_POST['orders_price_grand_total'][$orders_id]);
    //             $orders_claim_price    = convertRpToInt2($_POST['orders_claim_price'][$orders_id]);

    //             $r = $this->db->get_where("mt_orders",array(
    //                 'orders_id'  => $orders_id
    //             ),1,0)->row();
    //             if(count($r) > 0){
    //                 $data1['orders_price_product'] = $orders_price_product;
    //                 $data1['orders_price_shipping'] = $orders_shipping_price;
    //                 $data2['orders_shipping_price'] = $orders_shipping_price;
    //                 $data1['orders_price_grand_total']   = $orders_price_grand_total;
    //                 $data3['orders_payment_grand_total'] = $orders_price_grand_total;
    //                 $data3['orders_payment_price']  = $orders_price_grand_total;

    //                 if($orders_claim_price == 0){
    //                     $data1['orders_claim_status'] = 0;
    //                     $data1['orders_claim_price']  = 0;
    //                 } else {
    //                     $data1['orders_claim_status'] = 2;
    //                     $data1['orders_claim_price']  = $orders_claim_price;
    //                 }

    //                 $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));
    //                 $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));
    //                 $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));

    //             }
    //         }

    //         $data['err']    = false;
    //         $data['msg']    = 'Berhasil simpan data...';
    //     }

    //     die(json_encode($data));
    //     exit();
    // }

    function update_orders_product_detail(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'update' ){
            $date_end       = dbClean($_POST['date_end']);

            // $this->db->where("( orders_date >= '".$date_start." 00:00:00' )");
            $this->db->where("( orders_date <= '".convDatepickerDec($date_end)." 23:59:59' )");
            $this->db->where("store_id", $this->store_id);
            $this->db->where("orders_product_detail",'0');
            $r = $this->db->get("mt_orders")->result();
            if(count($r) > 0){
                foreach ($r as $k => $v) {
                    $orders_id = $v->orders_id;
                    $data1['orders_product_detail'] = 1;
                    $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));
                    // echo $orders_id.'<br>';
                }
                $data['err'] = false;
                $data['msg'] = 'Berhasil update..';
            } else {
                $data['err'] = true;
                $data['msg'] = 'Tidak ditemukan..';
            }

            redirect($this->own_link."/booking?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));
        }
    }


    function booking(){
        $data = array();
        $this->_set_title( "Booking Dari Apps" );
        $this->breadcrumb[] = array(
            "title"     => "Booking Dari Apps"
        );

        $this->db->order_by("temp_orders_date", "desc");
        $data['data'] = $this->db->get_where("mt_temp_orders",array(
            "store_id"        => $this->store_id,
            "member_type"     => 1
        ))->result();

        $data['url']            = base_url()."admin/transaction/booking";
        $data['url_form']       = base_url()."admin/transaction/booking";
        $data['tab']            = 'tab16';
        $data['content_layout'] = $this->prefix_view."_booking.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function form_sinkron(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));

            // if(trim($thisVal)==''){ $thisVal = 0; }
            $r = $this->db->get_where("mt_temp_orders",array(
                'temp_orders_id' => $thisVal,
                'store_id'      => $this->store_id
            ),1,0)->row();
            if(count($r) > 0){
                $data['err']     = false;

                $product_detail = get_orders_product_detail($this->store_id, 0);
                $opt_ship = "";
                foreach ($product_detail as $key => $value) {
                    $opt_ship .= '<option value="'.$value->orders_id.'">'.$value->orders_id.'-'.getDateMonth($value->orders_date).' - '.$value->orders_shipping_name.' ('.substr($value->orders_source_invoice, -3).' '.$value->orders_source_name.')</option>';
                }

                $data['content'] = '
                <form class="ajax_default" action="'.$this->own_link.'/form_save_sinkron" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-horizontal">
                        <legend>'.getDateMonth($r->temp_orders_date).' - '.$r->orders_source_invoice.' ('.get_orders_source($r->orders_source_id)->orders_source_name.')</legend>
                        <div class="form-group">
                            <label class="col-sm-12">Nama Penerima</label>
                            <div class="col-sm-12">
                                <select name="orders_id" data-placeholder="--- Select ---" class="form-control chosen-select" required>
                                    <option value=""></option>
                                    '.$opt_ship.'
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-action mb-0">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-9">
                                <input type="hidden" name="temp_orders_id" value="'.$r->temp_orders_id.'" />
                                <input type="hidden" name="thisAction" value="save" />
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                            </div>
                        </div>
                    </div>
                </form>
                ';
            } else {
                $data['content'] = '';
                $data['err']     = true;
                $data['msg']     = 'Id Booking tidak ditemukan';
            }
        }

        die(json_encode($data));
        exit();
    }

    function form_save_sinkron(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
            $temp_orders_id = $_POST['temp_orders_id'];
            $orders_id      = $_POST['orders_id'];
            if($temp_orders_id != "" && $orders_id != ""){

                $tmp = $this->db->get_where("mt_temp_orders",array(
                    'temp_orders_id'    => $temp_orders_id
                ),1,0)->row();
                if(count($tmp) > 0){
                    $orders = $this->db->get_where("mt_orders",array(
                        'orders_id' => $orders_id
                    ),1,0)->row();
                    if(count($orders) > 0){
                        $product_detail_item = $tmp->product_detail_item;

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
                                $totalBuy      = $totalBuy + ($detail->product_price_buy * $detail_qty);

                                $detail_price = $detail->product_price_sale;
                                // if($detail->product_price_discount > 0){
                                //     $detail_price = $detail->product_price_discount;
                                // }
                                if($detail->product_price_grosir != ''){
                                    $product_price_grosir = json_decode($detail->product_price_grosir);
                                    foreach ($product_price_grosir as $key => $value){
                                        if($value->qty <= $detail_qty){
                                            $detail_price = $value->price;
                                        }
                                    }
                                }
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
                                    'orders_id'             => $orders_id,
                                    'product_id'            => $product->product_id,
                                    'product_name'          => $product->product_name,
                                    'product_images'        => get_cover_image_detail($product->product_id),
                                    'product_price_buy'     => $detail->product_price_buy,
                                    'orders_detail_price'   => $detail_price,
                                    'orders_detail_qty'     => $detail_qty,
                                    'orders_detail_weight'  => $detail_weight,
                                    'orders_detail_item'    => $orders_detail_item
                                );

                                $this->DATA->table="mt_orders_detail";
                                $a0 = $this->_save_master(
                                    $data0,
                                    array(
                                        'orders_detail_id' => ''
                                    ),
                                    ''
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
                            'orders_shipping_status' => 4,
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

                        $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));
                        $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));

                        writeLog(array(
                            'log_user_type'     => "1", // Admin
                            'log_user_id'       => $this->user_id,
                            'log_role'          => NULL,
                            'log_type'          => "4", // Order
                            'log_detail_id'     => $orders_id,
                            'log_detail_item'   => $log_item,
                            'log_detail_qty'    => $log_qty,
                            'log_title_id'      => "30", // Berhasil Checkout
                            'log_desc'          => NULL,
                            'log_status'        => "0"
                        ));

                        $data['err'] = false;
                        $data['msg'] = "Berhasil Sinkron...";

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
                        $data['err'] = true;
                        $data['msg'] = "Nama Penerima tidak ditemukan.";
                    }
                } else {
                    $data['err'] = true;
                    $data['msg'] = "Booking pesanan tidak ditemukan.";
                }
            } else {
                $data['err'] = true;
                $data['msg'] = "Nama penerima belum dipilih.";
            }
        }

        die(json_encode($data));
        exit();
    }

    function canceled_booked($id=""){
        $id = explode("-", $id);
        $id = dbClean(trim($id[0]));
        if(trim($id) != ''){
            $temp_orders_id = $id;
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
                            set_last_date_product_setup();
                        }

                    }
                }

                $this->db->delete("mt_temp_orders",array('temp_orders_id' => $temp_orders_id));
                redirect($this->own_link."/booking?msg=".urlencode('Berhasil hapus data booking pesanan.')."&type_msg=success");
            } else {
                redirect($this->own_link."/booking?msg=".urlencode('No pesanan tidak ditemukan.')."&type_msg=error");
            }
        } else {
            redirect($this->own_link."/booking?msg=".urlencode('No pesanan tidak ditemukan.')."&type_msg=error");
        }
    }

    function deleted_booked($id=""){
        $id = explode("-", $id);
        $id = dbClean(trim($id[0]));
        if(trim($id) != ''){
            $temp_orders_id = $id;
            $r = $this->db->get_where("mt_temp_orders",array(
                'temp_orders_id'  => $temp_orders_id
            ),1,0)->row();
            if(count($r) > 0){
                $this->db->delete("mt_temp_orders",array('temp_orders_id' => $temp_orders_id));
                redirect($this->own_link."/booking?msg=".urlencode('Berhasil hapus data booking pesanan.')."&type_msg=success");
            } else {
                redirect($this->own_link."/booking?msg=".urlencode('No pesanan tidak ditemukan.')."&type_msg=error");
            }
        } else {
            redirect($this->own_link."/booking?msg=".urlencode('No pesanan tidak ditemukan.')."&type_msg=error");
        }
    }

    function form_change_product_orders(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $timestamp  = timestamp();
            $tipe_date  = "periode";
            $date_start = getYearMonthDate(getMinDay($timestamp, 7));
            $date_end   = getYearMonthDate($timestamp);
            $orders     = get_orders_by_date($tipe_date,$date_start,$date_end);
            $opt_from_orders_id = "";
            foreach ($orders as $key => $val) {
                if($val->orders_product_detail == "1"){
                    $shipping = get_detail_orders_shipping($val->orders_id);
                    $opt_from_orders_id .= '<option value="'.$val->orders_id.'">'.$val->orders_id.'-'.getDateMonth($val->orders_date).' - '.$shipping->orders_shipping_name.' ('.substr($val->orders_source_invoice, -3).' '.get_orders_source($val->orders_source_id)->orders_source_name.')</option>';
                }
            }

            $opt_to_orders_id = "";
            $orders2 = get_orders_product_detail($this->store_id, "0");
            foreach ($orders2 as $key => $val) {
                $opt_to_orders_id .= '<option value="'.$val->orders_id.'">'.$val->orders_id.'-'.getDateMonth($val->orders_date).' - '.$val->orders_shipping_name.' ('.substr($val->orders_source_invoice, -3).' '.$val->orders_source_name.')</option>';
            }

            $data['content'] = '
            <form class="ajax_default" action="'.$this->own_link.'/form_save_change_product_orders" method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <legend>Tukar Pesanan (Salah Checkout) Max. 7Hari</legend>
                    <div class="form-group">
                        <label class="col-sm-12">Dari Penerima</label>
                        <div class="col-sm-12">
                            <select name="from_orders_id" data-placeholder="--- Select ---" class="form-control chosen-select" required>
                                <option value=""></option>
                                '.$opt_from_orders_id.'
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12">Ke Penerima</label>
                        <div class="col-sm-12">
                            <select name="to_orders_id" data-placeholder="--- Select ---" class="form-control chosen-select" required>
                                <option value=""></option>
                                '.$opt_to_orders_id.'
                            </select>
                        </div>
                    </div>
                    <p><em>Dari Penerima akan di set seperti order baru.</em></p>
                    <div class="form-group form-action mb-0">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
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

    function form_save_change_product_orders(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
            $from_orders_id = $_POST['from_orders_id'];
            $to_orders_id   = $_POST['to_orders_id'];
            if($from_orders_id != "" && $to_orders_id != ""){
                $totalBuy   = 0;
                $totalPrice = 0;
                $m1 = $this->db->get_where("mt_orders",array(
                    "orders_id" => $from_orders_id
                ),1,0)->row();
                $m2 = $this->db->get_where("mt_orders",array(
                    "orders_id" => $to_orders_id
                ),1,0)->row();

                $m3 = $this->db->order_by("orders_detail_id", "desc")->get_where("mt_orders_detail",array(
                    "orders_id" => $from_orders_id
                ))->result();
                if(count($m3) > 0){
                    foreach ($m3 as $key => $val) {
                        $totalBuy   += ($val->product_price_buy * $val->orders_detail_qty);
                        $totalPrice += ($val->orders_detail_price * $val->orders_detail_qty);
                        $this->db->update("mt_orders_detail",array("orders_id"=>$to_orders_id),array("orders_detail_id"=>$val->orders_detail_id));
                    }

                    $data1 = array(
                        'orders_status'            => 3,
                        'orders_price_buy_total'   => 0,
                        'orders_price_product'     => 0,
                        'orders_product_detail'    => 0
                    );
                    $this->db->update("mt_orders",$data1,array("orders_id"=>$from_orders_id));

                    $data2 = array(
                        'orders_status'            => 4,
                        'orders_price_buy_total'   => $totalBuy,
                        'orders_price_product'     => $totalPrice,
                        'orders_product_detail'    => 1
                    );
                    $this->db->update("mt_orders",$data2,array("orders_id"=>$to_orders_id));
                    insert_orders_timestamp($to_orders_id,4);

                    $this->db->update("mt_orders_shipping",array("orders_shipping_status"=>3),array("orders_id"=>$from_orders_id));
                    $this->db->update("mt_orders_shipping",array("orders_shipping_status"=>4),array("orders_id"=>$to_orders_id));

                    $data['err'] = false;
                    $data['msg'] = "Berhasil tukar pesanan.";
                }
            } else {
                $data['err'] = true;
                $data['msg'] = "Nama penerima belum dipilih.";
            }
        }

        die(json_encode($data));
        exit();
    }

}
