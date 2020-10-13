<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");

class product_checked extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        $this->_set_title( 'Produk Check' );
        $this->DATA->table="mt_product";
        $this->folder_view = "product/";
        $this->prefix_view = strtolower($this->_getClass());
        // $this->load->model("mdl_product","M");
        $this->breadcrumb[] = array(
                "title"     => "Produk Check",
                "url"       => $this->own_link
            );

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        // $this->detail_store     = get_detail_store($this->store_id);
        // $this->store_name       = $this->detail_store->store_name;
        // $this->store_phone      = $this->detail_store->store_phone;
        // $this->store_product    = $this->detail_store->store_product;
    }

    function index(){
        $this->breadcrumb[] = array(
            "title"     => "List"
        );
        $data = array();

        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function delete_double_orders_detail($id=''){
        if(trim($id) != ''){
            $this->DATA->table = 'mt_orders_detail';
            $this->DATA->_delete(array("orders_detail_id"  => idClean($id)),true);
        }
        redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
    }

}
