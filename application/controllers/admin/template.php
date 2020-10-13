<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Template extends AdminController {  
	function __construct()    
	{
		parent::__construct();
			    
		$this->_set_action();
		$this->_set_action(array("detail"),"ITEM");
		$this->_set_title( 'Template' );
		$this->DATA->table = "mt_template";
		$this->folder_view = "config/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->breadcrumb[] = array(
			"title"		=> "Template",
			"url"		=> $this->own_link
		);
	}
	
	function index(){
		$this->load->helper('directory');
		$data['templatelist'] 	= directory_map('./application/views/front', FALSE, TRUE);
		$data['template'] 		= $this->db->get("mt_template")->row();
		
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function save(){
		$data = array(
			'template_name'			=> dbClean($_POST['template_name']),	
		);		

		if (dbClean($_POST['template_id']) == "") {
			$data['template_date'] = timestamp();
		}
		$a = $this->_save_master( 
			$data,
			array(
				'template_id' => dbClean($_POST['template_id'])
			),
			dbClean($_POST['template_id'])			
		);
		
		redirect($this->own_link."/index?msg=".urlencode('Save data success')."&type_msg=success");
	}
}



