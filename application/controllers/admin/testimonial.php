<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class testimonial extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Testimonial' );
		$this->DATA->table ="mt_testimonial";
		$this->folder_view = "message/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_testimonial","M");
		$this->breadcrumb[] = array(
			"title"		=> "Testimonial",
			"url"		=> $this->own_link
		);
		$this->cat_search = array(
			''									=> 'All Search...',
			'mt_testimonial.testimonial_name'	=> 'Nama',
			'mt_testimonial.testimonial_email'	=> 'Email',
			'mt_testimonial.testimonial_desc'	=> 'Deskripsi'
		);

		$this->content_top  = 'mail';
		$this->content_icon = 'fa-comment';
		$this->content_bg   = 'bg-success';
	}


	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'testimonial',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'testimonial_date',
			'order_dir' => 'desc',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){

		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'testimonial'){
			$this->_reset();
		}

		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		if($this->input->post('keyword') && trim($this->input->post('keyword'))!=""){
			$this->jCfg['search']['keyword'] = $this->input->post('keyword');
			$this->jCfg['search']['colum'] = "";
			$this->_releaseSession();
		} else {
			$this->jCfg['search']['keyword'] = "";
			$this->jCfg['search']['colum'] = "";
			$this->_releaseSession();
		}

		if($this->input->post('btn_reset')){
			$this->_reset();
		}

		$this->per_page = 20;
		$par_filter = array(
			"offset"	=> $this->uri->segment($this->uri_segment),
			"limit"		=> $this->per_page,
			"param"		=> $this->cat_search
		);
		$this->data_table = $this->M->data_testimonial($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));
			
		$data['url'] = base_url()."admin/testimonial/index"; 

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
			$this->db->update("mt_testimonial",array("notify"=>0),array("testimonial_id"=>$id));
			$this->data_form = $this->DATA->data_id(array(
				'testimonial_id'	=> $id
			));
			if(empty($this->data_form->testimonial_id)){
				redirect($this->own_link."?msg=".urlencode('Data menu tidak ditemukan')."&type_msg=error");
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
				'testimonial_id'	=> $id
			));
			if(empty($this->data_form->testimonial_id)){
				redirect($this->own_link."?msg=".urlencode('Data menu tidak ditemukan')."&type_msg=error");
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
			$this->DATA->_delete(array("testimonial_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function filesToDel(){
		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'deleteSelected'){
			$i = 0;
			$count = count($_POST['filesToDel']);
			foreach ($_POST['filesToDel'] as $id){ 
				$this->DATA->_delete(array("testimonial_id"	=> idClean($id)),true);
				$i += 1;
				if($i == $count){
					redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
					exit();
				}
			}
		}
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_testimonial",array(
			"testimonial_istrash"	=> 1
		))->result();
		foreach($data as $r){ 
			$id = $r->testimonial_id;
			$this->DATA->_delete(array("testimonial_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'testimonial_name'		=> dbClean(ucwords($_POST['testimonial_name'])),
			'testimonial_email'		=> dbClean($_POST['testimonial_email']),
			'testimonial_desc'		=> dbClean($_POST['testimonial_desc']),
			'testimonial_status'	=> dbClean($_POST['testimonial_status'])
		);

		if (dbClean($_POST['testimonial_id']) == "") {
			$data['testimonial_date'] = timestamp();
			$data['notify'] = 3;
		}

		$a = $this->_save_master( 
			$data,
			array(
				'testimonial_id' => dbClean($_POST['testimonial_id'])
			),
			dbClean($_POST['testimonial_id'])			
		);

		$id = $a['id'];

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['testimonial_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function approved(){
		$error		= true;
		$msg		  = '';
		$href		 = '';
		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
			$id = dbClean($_POST['thisId']);
			$this->db->update("mt_testimonial",array("testimonial_status"=>1),array("testimonial_id"=>$id));
			$error   = false;
			$msg     = 'Successfully approved testimonial.';
		}

		$return = array('error' => $error, 'msg' => $msg, 'href' => $href);
		die(json_encode($return));
		exit();
	}       

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			$this->db->update("mt_testimonial",array("notify"=>0),array("testimonial_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
