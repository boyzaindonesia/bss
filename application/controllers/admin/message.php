<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class message extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Pesan Kontak' );
		$this->DATA->table="mt_message";
		$this->folder_view = "message/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_message","M");
		$this->breadcrumb[] = array(
			"title"		=> "Pesan Kontak",
			"url"		=> $this->own_link
		);
		$this->cat_search = array(
			''								=> 'All Search...',
			'mt_message.message_name'		=> 'Nama',
			'mt_message.message_email'		=> 'Email',
			'mt_message.message_subject'	=> 'Subject'
		); 

		$this->content_top  = 'mail';
		$this->content_icon = 'fa-envelope';
		$this->content_bg   = 'bg-danger';
	}


	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'message',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'message_date',
			'order_dir' => 'desc',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'message'){
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

		$this->per_page = 10;
		$par_filter = array(
			"offset"	=> $this->uri->segment($this->uri_segment),
			"limit"		=> $this->per_page,
			"param"		=> $this->cat_search
		);
		$this->data_table = $this->M->data_message($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));
			
		$data['url'] = base_url()."admin/message/index"; 

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
			$this->db->update("mt_message",array("notify"=>0),array("message_id"=>$id));
			$this->data_form = $this->DATA->data_id(array(
				'message_id'	=> $id
			));
			if(empty($this->data_form->message_id)){
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
				'message_id'	=> $id
			));
			if(empty($this->data_form->message_id)){
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
			$this->DATA->_delete(array("message_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function filesToDel(){
		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'deleteSelected'){
			$i = 0;
			$count = count($_POST['filesToDel']);
			foreach ($_POST['filesToDel'] as $id){ 
				$this->DATA->_delete(array("message_id"	=> idClean($id)),true);
				$i += 1;
				if($i == $count){
					redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
					exit();
				}
			}
		}
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_message",array(
			"message_istrash"	=> 1
		))->result();
		foreach($data as $r){ 
			$id = $r->message_id;
			$this->DATA->_delete(array("message_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'message_name'		=> dbClean(ucwords($_POST['message_name'])),
			'message_email'		=> dbClean($_POST['message_email']),
			'message_phone'		=> dbClean($_POST['message_phone']),
			'message_subject'	=> dbClean($_POST['message_subject']),
			'message_desc'		=> dbClean($_POST['message_desc']),
			'message_date'		=> timestamp(),
			'notify'			=> 3
		);

		$a = $this->_save_master( 
			$data,
			array(
				'message_id' => dbClean($_POST['message_id'])
			),
			dbClean($_POST['message_id'])			
		);

		$id = $a['id'];

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['message_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			$this->db->update("mt_message",array("notify"=>0),array("message_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
