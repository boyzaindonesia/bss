<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Store extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_title( 'List Transaksi Store' );
		$this->folder_view = "report/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_order_ppl","M");
		$this->breadcrumb[] = array(
				"title"		=> "Data Transaksi Store",
				"url"		=> $this->own_link
			);
	}

	
	
	function index(){
	//debugCode($this->own_link);

		$this->breadcrumb[] = array(
				"title"		=> "List"
			);
		
		
		
		$this->per_page = 20;
		$par_filter = array(
				"order"				=> isset($_POST['status'])?$_POST['status']:"",
				"date_start"		=> isset($_POST['date_start'])?$_POST['date_start']:"",
				"date_end"			=> isset($_POST['date_end'])?$_POST['date_end']:"",
				"offset"			=> $this->uri->segment($this->uri_segment),
				"limit"				=> $this->per_page
			);
		$this->data_table = $this->M->data_article_store($par_filter);
		
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));
			
			//debugCode($this->data_table);
			
		//debugCode($_POST);	
		$data['url'] = base_url()."report/store/index";
		$data['jml'] = $this->data_table[total];
		
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}
	
	function export_data(){
		$data = array();
		//debugCode($_GET);

		$par_filter = array(
				"order"				=> isset($_GET['status'])?$_GET['status']:"",
				"date_start"		=> isset($_GET['date_start'])?$_GET['date_start']:"",
				"date_end"			=> isset($_GET['date_end'])?$_GET['date_end']:""
			);
		$this->data_table = $this->M->data_article_store($par_filter);
		
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));

		$data['jml'] = $this->data_table[total];	
	  	
		$this->_v($this->folder_view.$this->prefix_view."_export",$data,FALSE);
	}


	

	
	

	
	
	

}
