<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Piutang extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_title( 'List Rekap Piutang' );
		$this->DATA->table="mt_iurang";
		$this->folder_view = "report/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_piutang","M");
		$this->breadcrumb[] = array(
				"title"		=> "Data Piutang",
				"url"		=> $this->own_link
			);
	}

	
	
	function index(){
	//debugCode($_POST);

		$this->breadcrumb[] = array(
				"title"		=> "List"
			);
		
		
		
		$this->per_page = 20;
		$par_filter = array(
				"status"			=> isset($_POST['status'])?$_POST['status']:"",
				"tahun"				=> isset($_POST['tahun'])?$_POST['tahun']:"",
				"kategori"			=> isset($_POST['kategori'])?$_POST['kategori']:"",
				"iuran"				=> isset($_POST['iuran'])?$_POST['iuran']:"",
				"no_reg_iapi"		=> isset($_POST['no_reg_iapi'])?$_POST['no_reg_iapi']:"",
				"offset"			=> $this->uri->segment($this->uri_segment),
				"limit"				=> $this->per_page
			);
		$this->data_table = $this->M->data_article($par_filter);
		
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));
			
			//debugCode($this->data_table);
			
		//debugCode($_POST);	
		$data['url'] = base_url()."report/piutang/index";
		$data['jml'] = $this->data_table[total];
		
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}
	
	function export_data(){
		$data = array();
		//debugCode($_GET);

		$par_filter = array(
				"status"			=> isset($_GET['status'])?$_GET['status']:"",
				"tahun"				=> isset($_GET['tahun'])?$_GET['tahun']:"",
				"kategori"			=> isset($_GET['kategori'])?$_GET['kategori']:"",
				"iuran"				=> isset($_GET['iuran'])?$_GET['iuran']:"",
				"no_reg_iapi"		=> isset($_GET['no_reg_iapi'])?$_GET['no_reg_iapi']:"",
			);
		$this->data_table = $this->M->data_article($par_filter);
		
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));

		$data['jml'] = $this->data_table[total];	
	  	
		$this->_v($this->folder_view.$this->prefix_view."_export",$data,FALSE);
	}


	

	
	

	
	
	

}
