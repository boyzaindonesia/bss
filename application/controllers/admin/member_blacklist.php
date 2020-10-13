<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class member_blacklist extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'List Data Member Blacklist' );
		$this->DATA->table="mt_member_blacklist";
		$this->folder_view = "member/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_member_blacklist","M");
		$this->breadcrumb[] = array(
				"title"		=> "Data Member Blacklist",
				"url"		=> $this->own_link
			);
			
		$this->cat_search = array(
			''									=> 'Semua Pencarian...',
			'mt_member_blacklist.member_blacklist_email'	=> 'Email'
		); 	
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'member_blacklist',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_member_blacklist.member_blacklist_date',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}
	
	
	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'member_blacklist'){
			$this->_reset();
		}
		
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
			if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
				$this->jCfg['search']['date_start'] = convDatepickerDec($this->input->post('date_start'));

			if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
				$this->jCfg['search']['date_end'] = convDatepickerDec($this->input->post('date_end'));

			
			if($this->input->post('colum') && trim($this->input->post('colum'))!="")
				$this->jCfg['search']['colum'] = $this->input->post('colum');
			else
				$this->jCfg['search']['colum'] = "";	

			if($this->input->post('keyword') && trim($this->input->post('keyword'))!="")
				$this->jCfg['search']['keyword'] = $this->input->post('keyword');
			else
				$this->jCfg['search']['keyword'] = "";

			$this->_releaseSession();
        }

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'reset'){
            $this->_reset();
        }

		$order_by = $this->jCfg['search']['order_by'];
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
			$explode_order_by = explode("-", $_POST['order_by']);
			$this->jCfg['search']['order_by'] = $explode_order_by[0];
			$this->jCfg['search']['order_dir'] = $explode_order_by[1];
			$this->_releaseSession();
		}
		if(isset($_POST['filter'])){
			$this->jCfg['search']['filter'] = $_POST['filter'];
			$this->_releaseSession();
		}

		$this->per_page = $this->jCfg['search']['filter'];
		$par_filter = array(
			"offset"	=> $this->uri->segment($this->uri_segment),
			"limit"		=> $this->per_page,
			"param"		=> $this->cat_search
		);
		$this->data_table = $this->M->data_member_blacklist($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));
			
		$data['url'] = base_url()."admin/member_blacklist/index";
		
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}
	
	function add(){	
		$this->breadcrumb[] = array(
			"title"		=> "Add"
		);		
		$this->_v($this->folder_view.$this->prefix_view."_form");
	}
	
	function view($id=''){
		$data = array();
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'member_blacklist_id'	=> $id
			));
			if(empty($this->data_form->member_blacklist_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_view",$data);
		}else{
			redirect($this->own_link);
		}
	}
	
	function edit($id=''){
		$data = array();
		$this->breadcrumb[] = array(
			"title"		=> "Edit"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'member_blacklist_id'	=> $id
			));
			if(empty($this->data_form->member_blacklist_id)){
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
		if(trim($id) != ''){
			$this->db->update("mt_member_blacklist",array("member_blacklist_istrash"=>1),array("member_blacklist_id"=>$id));
			// $this->DATA->_delete(array("member_blacklist_id"	=> idClean($id)),true);	

			$m = $this->db->get_where("mt_member_blacklist",array(
				"member_blacklist_id"	=> $id,
			))->row();
			if(count($m)>0){
				if($m->member_id!=0){
					$this->db->update("mt_member",array("member_status"=>1),array("member_id"=>$m->member_id));
				}
			}
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_member_blacklist",array(
			"member_blacklist_istrash"	=> 1
		))->result();
		foreach($data as $r){ 
			$id = $r->member_blacklist_id;
			$this->DATA->_delete(array("member_blacklist_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'member_blacklist_desc'			=> dbClean($_POST['member_blacklist_desc']),
			'member_blacklist_status'		=> dbClean($_POST['member_blacklist_status']),
			'member_blacklist_date'			=> timestamp()
		);

		$member_blacklist_id = dbClean($_POST['member_blacklist_id']);

		$email = explode("-", $_POST['member_blacklist_email'])[0];
		$email = dbClean(trim($email));
		$r = $this->db->get_where("mt_member",array(
			"member_email"	=> $email
		),1,0)->row();
		if(count($r)>0){
			$data['member_id']    = $r->member_id;
		} else {
			$data['member_id']    = 0;
		}

		$data['member_blacklist_email']    = $email;

		// $v = $this->db->get_where("mt_member_blacklist",array(
		// 	'member_blacklist_email' => $email
		// ),1,0)->row();
		// if(count($v)>0){
		// 	$member_blacklist_id = $v->member_blacklist_id;
		// }
		
		if($data['member_id']!=0){
			$this->db->update("mt_member",array("member_status"=>dbClean($data['member_blacklist_status'])),array("member_id"=>dbClean($data['member_id'])));
		}

		$a = $this->_save_master( 
			$data,
			array(
				'member_blacklist_id' => $member_blacklist_id
			),
			$member_blacklist_id
		);
		
		$id = $a['id'];
		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($email)."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function check_autocomplete(){
		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_autocomplete' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$bold_thisVal  = '<strong>'.$thisVal.'</strong>';
			
			// $this->DATA->table="mt_member";
			if(trim($thisVal)!=''){
				$v = $this->db->get_where("mt_member",array(
					// 'member_name LIKE' => '%'.$thisVal.'%',
					'member_email LIKE' => '%'.$thisVal.'%'
				))->result();
				if(!empty($v)){
					foreach($v as $r => $v){
						$err = false;
						$msg .= '<div class="feedback text-left"><span class="name" data-id="'.$v->member_id.'">'.str_ireplace($thisVal,$bold_thisVal,$v->member_email.' - '.$v->member_name).'</span></div>'; 
					}
				} else {
					$err = true;
					// $msg = '<div class="feedback text-left">No matching records.</div>';
				}

			}
		}

		$return = array('msg' => $msg,'err' => $err);
		die(json_encode($return));
		exit();
	}

}
