<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Member extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("edit","delete"),"ITEM");
		$this->_set_title( 'List Data Member' );
		$this->DATA->table="mt_member";
		$this->folder_view = "report/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_data_member","M");
		$this->breadcrumb[] = array(
				"title"		=> "Data Member",
				"url"		=> $this->own_link
			);
			
	}

	
	
	
	
	function index(){
		
		
		$this->breadcrumb[] = array(
				"title"		=> "List"
			);
		
		
		
		$this->per_page = 100;
		$par_filter = array(
				"status"			=> isset($_POST['status'])?$_POST['status']:"",
				"kategori"			=> isset($_POST['kategori'])?$_POST['kategori']:"",
				"iuran"				=> isset($_POST['iuran'])?$_POST['iuran']:"",
				"no_reg_iapi"		=> isset($_POST['no_reg_iapi'])?$_POST['no_reg_iapi']:"",
				"no_reg_negara"		=> isset($_POST['no_reg_negara'])?$_POST['no_reg_negara']:"",
				"offset"	=> $this->uri->segment($this->uri_segment),
				"limit"		=> $this->per_page
			);
		$this->data_table = $this->M->data_article($par_filter);
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));
			
		$data['url'] = base_url()."report/member/index";
		$data['jml'] = $this->data_table[total];
		
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}
	
	function export_data(){
		$data = array();
		
		$par_filter = array(
				"status"			=> isset($_GET['status'])?$_GET['status']:"",
				"kategori"			=> isset($_GET['kategori'])?$_GET['kategori']:"",
				"iuran"				=> isset($_GET['iuran'])?$_GET['iuran']:"",
				"no_reg_iapi"		=> isset($_GET['no_reg_iapi'])?$_GET['no_reg_iapi']:"",
				"no_reg_negara"		=> isset($_GET['no_reg_negara'])?$_GET['no_reg_negara']:""
			);

		$this->data_table = $this->M->data_article($par_filter);
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));
			
	  	$data['jml'] = $this->data_table[total];
		$this->_v($this->folder_view.$this->prefix_view."_export",$data,FALSE);
	}


	
	


	
	

}
