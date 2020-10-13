<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class site_contact extends AdminController {  
	function __construct()    
	{
		parent::__construct();
			    
		$this->_set_action();
		// $this->_set_action(array("detail"),"ITEM");
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Kontak' );
		$this->DATA->table = "mt_contact";
		$this->folder_view = "config/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->breadcrumb[] = array(
			"title"		=> "Kontak",
			"url"		=> $this->own_link
		);
	}
	
	function index(){
		// $data['contact'] = $this->db->get("mt_contact")->row();
		// $data['page_contact'] = "contact";
		// $this->_v($this->folder_view.$this->prefix_view,$data);
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);
		
		$data['data'] = $this->DATA->_getall(array(
			'is_trash !=' => 1
		));	

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function add(){
		$this->breadcrumb[] = array(
			"title"		=> "Tambah"
		);		
		$this->_v($this->folder_view.$this->prefix_view."_form");
	}

	function view($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'contact_id'	=> $id
			));
			if(empty($this->data_form->contact_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}
			
			$this->_v($this->folder_view.$this->prefix_view."_view",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function edit($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "Edit"
		);
		
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'contact_id'	=> $id
			));
			if(empty($this->data_form->contact_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}
			
			$this->_v($this->folder_view.$this->prefix_view."_form",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!= ''){
			if(trim($id)==1){
				redirect($this->own_link."?msg=".urlencode('Data tidak dapat dihapus')."&type_msg=error");
			} else {
				$this->db->delete("mt_contact",array('contact_id'=>$id));	
				redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
			}
		}
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_contact",array(
			"is_trash"	=> 1
		))->result();	
		foreach($data as $r){ 
			$id = $r->contact_id;
			$this->db->delete("mt_contact",array('contact_id' => $id));
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'contact_name'		=> dbClean(ucwords($_POST['contact_name'])),	
			'contact_lang'		=> dbClean($_POST['contact_lang']),	
			'contact_lat'		=> dbClean($_POST['contact_lat']),	
			'contact_desc'		=> dbClean($_POST['contact_desc']),	
		);		
		
		if (dbClean($_POST['contact_id']) == "") {
			$data['contact_date'] = timestamp();
		}
		$a = $this->_save_master( 
			$data,
			array(
				'contact_id' => dbClean($_POST['contact_id'])
			),
			dbClean($_POST['contact_id'])			
		);

		$id = $a['id'];

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['contact_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
	}
}



