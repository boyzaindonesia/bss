<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class supplier extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'List Supplier' );
		$this->DATA->table="mt_supplier";
		$this->folder_view = "orders/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
			"title"		=> "List Supplier",
			"url"		=> $this->own_link
			);
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$this->db->order_by('supplier_name','asc');
		$data['data'] = $this->db->get_where("mt_supplier",array(
			"supplier_istrash" => 0
		))->result();

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function add(){	
		$this->breadcrumb[] = array(
			"title"		=> "Add"
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
				'supplier_id'	=> $id
				));
			if(empty($this->data_form->supplier_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}
			$this->_v($this->folder_view.$this->prefix_view."_view");
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
				'supplier_id'	=> $id
				));
			if(empty($this->data_form->supplier_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}
			$this->_v($this->folder_view.$this->prefix_view."_form");
		}else{
			redirect($this->own_link);
		}
	}

	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			$this->DATA->_delete(array("supplier_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_supplier",array(
			"supplier_istrash"	=> 1
			))->result();
		foreach($data as $r){ 
			$id = $r->supplier_id;
			$this->DATA->_delete(array("supplier_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'supplier_name'				=> dbClean(ucwords($_POST['supplier_name'])),
			'supplier_address'			=> dbClean($_POST['supplier_address']),
			'supplier_city_id'			=> dbClean($_POST['city']),
			'supplier_province_id'		=> dbClean($_POST['province']),
			'supplier_postal_code'		=> dbClean($_POST['supplier_postal_code']),
			'supplier_phone'			=> dbClean($_POST['supplier_phone']),
			'supplier_istrash'			=> 0
			);

		if (dbClean($_POST['supplier_id']) == "") {
			$data['date_created']    = timestamp();
		}

		$a = $this->_save_master( 
			$data,
			array(
				'supplier_id' => dbClean($_POST['supplier_id'])
				),
			dbClean($_POST['supplier_id'])			
			);

		$id = $a['id'];

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['supplier_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
	}

}
