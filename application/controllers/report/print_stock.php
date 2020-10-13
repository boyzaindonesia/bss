<?php
include_once(APPPATH."libraries/FrontController.php");
class print_stock extends FrontController {
	var $cur_menu = '';

	function __construct()  
	{
		parent::__construct();
		$this->load->model("mdl_product","M");

	}
	
	function index(){
		$data = array();

		$par_filter = array(
			"product_status_id"	=> '1'
		);
		$this->data_table = $this->M->data_product_report($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		$this->load->view('admin/template/report/print_stock',$data);
	}

}
