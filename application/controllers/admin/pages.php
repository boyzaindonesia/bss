<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class pages extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_title( 'Pages' );
		// $this->DATA->table="mt_print_address";
		$this->folder_view = "pages/";
		$this->prefix_view = strtolower($this->_getClass());
		// $this->load->model("mdl_orders","M");
		$this->breadcrumb[] = array(
				"title"		=> "Pages",
				"url"		=> $this->own_link
			);

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        $this->detail_store     = get_detail_store($this->store_id);
        $this->store_name       = $this->detail_store->store_name;
        $this->store_phone      = $this->detail_store->store_phone;
        $this->store_product    = $this->detail_store->store_product;
	}

	function index(){
		$data = array();

		$this->breadcrumb[] = array(
				"title"		=> "Pages"
			);

        $data['content_layout'] = $this->prefix_view."_new_label.php";
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

    function calculator(){
        $data = array();

        $this->_set_title( 'Kalkulator' );
        $this->breadcrumb[] = array(
                "title"     => "Kalkulator"
            );

        $this->prefix_view = "calculator.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }
}
