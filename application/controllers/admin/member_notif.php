<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class member_notif extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'List Data Member Notification' );
		$this->DATA->table="mt_member_notif";
		$this->folder_view = "member/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_member_notif","M");
		$this->breadcrumb[] = array(
				"title"		=> "Data Member Notification",
				"url"		=> $this->own_link
			);
			
		$this->cat_search = array(
			''									=> 'Semua Pencarian...',
			'mt_member.member_name'				=> 'Nama Member',
			'mt_member.member_email'			=> 'Email Member'
		); 	
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'member_notif',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_member_notif.member_notif_date',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}
	
	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'member_notif'){
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
		$this->data_table = $this->M->data_member_notif($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));
			
		$data['url'] = base_url()."admin/member_notif/index";
		
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
				'member_notif_id'	=> $id
			));
			if(empty($this->data_form->member_notif_id)){
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
				'member_notif_id'	=> $id
			));
			if(empty($this->data_form->member_notif_id)){
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
			$this->db->update("mt_member_notif",array("member_notif_istrash"=>1),array("member_notif_id"=>$id));
			// $this->DATA->_delete(array("member_notif_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_member_notif",array(
			"member_notif_istrash"	=> 1
		))->result();
		foreach($data as $r){ 
			$id = $r->member_notif_id;
			$this->DATA->_delete(array("member_notif_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'member_notif_date'			=> timestamp(),
			'member_notif_desc'			=> dbClean($_POST['member_notif_desc']),
			'member_notif_action'		=> dbClean($_POST['member_notif_action']),
			'member_notif_repeat'		=> isset($_POST['member_notif_repeat'])?1:0,
			'member_notif_logout'		=> isset($_POST['member_notif_logout'])?1:0,
			'member_notif_status'		=> isset($_POST['member_notif_status'])?1:0
		);

		$email = explode("-", $_POST['member_id'])[0];
		$email = dbClean(trim($email));
		$r = $this->db->get_where("mt_member",array(
			"member_email"	=> $email
		),1,0)->row();
		if(count($r)>0){
			$data['member_id']    = $r->member_id;
		}

		if( isset($_POST['member_notif_action']) && trim($_POST['member_notif_action']) != ''){
			$this->db->update("mt_member",array("member_status"=>dbClean($_POST['member_notif_action'])),array("member_id"=>dbClean($data['member_id'])));

			$data2 = array(
				'member_blacklist_desc'			=> dbClean($_POST['member_notif_desc']),
				'member_blacklist_status'		=> dbClean($_POST['member_notif_action']),
				'member_blacklist_date'			=> timestamp(),
				'member_blacklist_istrash'		=> 0
			);
			$member_blacklist_id   = '';
			$data2['member_id']    = $data['member_id'];
			$data2['member_blacklist_email'] = $email;

			$v = $this->db->get_where("mt_member_blacklist",array(
				'member_blacklist_email' => $email
			),1,0)->row();
			if(count($v)>0){
				$member_blacklist_id = $v->member_blacklist_id;
			}

			$this->DATA->table="mt_member_blacklist";
			$b = $this->_save_master( 
				$data2,
				array(
					'member_blacklist_id' => $member_blacklist_id
				),
				$member_blacklist_id			
			);

		}

		$this->DATA->table="mt_member_notif";
		$a = $this->_save_master( 
			$data,
			array(
				'member_notif_id' => dbClean($_POST['member_notif_id'])
			),
			dbClean($_POST['member_notif_id'])			
		);
		
		$id = $a['id'];
		redirect($this->own_link."/view/".$id.'-'.changeEnUrl(get_member_name($data['member_id']))."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_member_notif",array("member_notif_status"=>$val),array("member_notif_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
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
					$msg = '<div class="feedback text-left">No matching records.</div>';
				}

			}
		}

		$return = array('msg' => $msg,'err' => $err);
		die(json_encode($return));
		exit();
	}

}
