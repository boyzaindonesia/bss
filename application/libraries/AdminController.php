<?php
class AdminController extends My_Controller{

	var $footer_page 	= array();
	var $header_page	= array();
	var $links			= array();
	var $links_table	= array();
	var $links_table_item = array();
	var $cat_search 	= array();
	var $own_link 		= "";
	var $title			= "";
	var $data_form;
	var $status_timesheet 	= array();
	var $status_image 		= array();
	var $type_timesheet		= array();
	var $breadcrumb			= array();
	var $arr_perpage = array();
	var $arr_status = array();
	var $print_type = "";


	var $arr_actions=array();
	function __construct(){

		parent::__construct();

		$this->jCfg['theme'] = 'admin/template';
		$this->_releaseSession();
		$this->_set_sess_action();
		$this->_set_own_link();

		if( $this->jCfg['is_login_bp'] != 1){
			$this->setReferer($this->uri->uri_string);
			redirect(site_url("auth"));
		}

		if($this->_getClass()!= 'me' ){
			$this->_set_array_actions();
			if(in_array($this->_getMethod(),$this->arr_actions)){
				if(!_ac($this->_getMethod())){
					redirect(base_url()."?msg=".urlencode("Access denied")."&type=error");
					//die('Access Denied, please contact your web Administrator..');
				}
			}
		}
		$this->setMenu(_get_menu($this->_set_sess_menu()));

		if(isset($_GET['msg']) && trim($_GET['msg']) != ""){
			$this->message = dbClean($_GET['msg']);
		}

		$this->breadcrumb[] = array(
				"title"		=> "Home",
				"url"		=> site_url("admin/me")
			);

		$this->arr_perpage = array(20,50,100,500,1000,5000,10000);

		$this->jCfg['search']['class'] = $this->_getClass();
		$this->_releaseSession();

	}

	function _set_array_actions(){
		$q = $this->db->get("mt_app_acl_actions")->result();
		foreach($q as $r){
			$this->arr_actions[]=$r->ac_action;
		}
	}

	function _set_sess_action(){

		$this->db->select("
				maa.ac_action,maa.ac_action_image,maa.ac_action_btn,aac.acc_access_name,maa.ac_action_type,
				aac.acc_group_controller,aac.acc_controller_name,maa.ac_action_name
			");
		$this->db->join('mt_app_acl_group ag', 'ag.ag_id = mu.user_group', 'left');
		$this->db->join('mt_app_acl_group_accesses agc', 'agc.aga_group_id = ag.ag_id', 'left');
		$this->db->join('mt_app_acl_actions maa', 'agc.aga_action_id = maa.ac_id', 'left');
		$this->db->join('mt_app_acl_accesses aac', 'agc.aga_access_id = aac.acc_id', 'left');

		$this->db->where("ag.ag_group_status","1");
		$this->db->where("mu.user_id",$this->jCfg['user']['id']);
		$this->db->where("aac.acc_controller_name",strtolower($this->_getClass()));

		$sql = $this->db->get("mt_app_user mu")->result();

		$acc=array();
		if(count($sql) > 0){
			foreach($sql as $r){
				$acc[$r->ac_action] = $r;
			}
		}
		//debugCode($this->_set_sess_menu());
		$this->setAccess($acc);
	}

	function _set_sess_menu(){

		$this->db->select("
				aac.*
			");
		$this->db->join('mt_app_acl_group ag', 'ag.ag_id = mu.user_group', 'left');
		$this->db->join('mt_app_acl_group_accesses agc', 'agc.aga_group_id = ag.ag_id', 'left');
		$this->db->join('mt_app_acl_actions maa', 'agc.aga_action_id = maa.ac_id', 'left');
		$this->db->join('mt_app_acl_accesses aac', 'agc.aga_access_id = aac.acc_id', 'left');

		$this->db->where("ag.ag_group_status","1");
		$this->db->where("mu.user_id",$this->jCfg['user']['id']);
		$this->db->where("maa.ac_action",'index');
		$this->db->where("aac.acc_isshow",'1');

		//$this->db->group_by("aac.acc_id");
		$this->db->order_by("aac.acc_by_order","ASC");
		$this->db->order_by("aac.acc_access_name","ASC");

		$menu = $this->db->get("mt_app_user mu")->result_array();

		//debugCode($menu);
		return $menu;
	}

	function _set_action($v=array("bug","add","index"),$type='MAIN'){
		if(count($v)>0){
			$acc = $this->jCfg['access'];
			foreach($v as $r){
				if(isset($acc[$r])){
					$arr_action = array(
						'title'	=> $acc[$r]->ac_action_name,
						'action'=> $r,
						'type'	=> $acc[$r]->ac_action_type,
						'link'	=> site_url($acc[$r]->acc_group_controller."/".$acc[$r]->acc_controller_name."/".$r),
						'image'	=> $acc[$r]->ac_action_image,
						'button'=> $acc[$r]->ac_action_btn
					);
					if($type=='MAIN'){
						$this->links[] = $arr_action;
					}elseif($type=='TABLE'){
						$this->links_table[] = $arr_action;
					}elseif($type=='ITEM'){
						$this->links_table_item[] = $arr_action;
					}else{
						$this->links[] = $arr_action;
					}
				}
			}
		}
	}

	function _set_own_link(){
		$tlink=strtolower($this->_getClass());
		if(	isset($this->jCfg['access']['index']) && count($this->jCfg['access']['index']) > 0){
			$tlink = $this->jCfg['access']['index']->acc_group_controller."/".strtolower($this->_getClass())."/";
		} else {
			$tlink = "admin/".strtolower($this->_getClass())."/";
		}
		$this->own_link = site_url($tlink);
	}

	function _v($file,$data=array(),$header=TRUE){
		$this->header_page['val']		= $this->data_form;
		$this->header_page['message']	= $this->message;
		if($header==TRUE){
			$this->header_page['links'] 		= $this->links;
			$this->header_page['links_table']	= $this->links_table;
			$this->header_page['links_table_item']	= $this->links_table_item;
			$this->header_page['own_links']	= $this->own_link;
			$this->load->view($this->jCfg['theme'].'/header',$this->header_page);
			$this->load->view($this->jCfg['theme'].'/'.$file,$data);
			$this->load->view($this->jCfg['theme'].'/footer',$this->footer_page);
		}else{
			$data['val'] = $this->header_page['val'];
			$this->load->view($this->jCfg['theme'].'/'.$file,$data);
		}
	}

	function _save_master($data=array(),$par=array(),$vid=0){
		$id 	= 0;
		$act	= FALSE;
		$o = $this->DATA->_cek($par);
		if( $o == 0 ){
			$act = $this->DATA->_add($data);
			$id = $this->db->insert_id();
		}else{
			$act = $this->DATA->_update($par,$data);
			$id = $vid;
		}
		return array(
			'id'	=> $id,
			'msg'	=> ($act)?'Success...':'Fail...'
		);
	}

	function _set_title($t=""){
		return $this->header_page['title'] = $t;
	}

	function _set_cat_search($arr=array()){
		return $this->header_page['cat_search'] = $arr;
	}

	function sort(){
		$this->jCfg['search']['order_by'] = $this->input->get('sort_by');
		$this->jCfg['search']['order_dir'] = $this->input->get('sort_dir');
		$this->_releaseSession();
		$next = $this->input->get('next')&&trim($this->input->get('next'))!=""?$this->input->get('next'):site_url();
		redirect($next);
	}

	function per_page(){
		$this->jCfg['search']['per_page'] = $this->input->get('per_page');
		$this->_releaseSession();
		$next = $this->input->get('next')&&trim($this->input->get('next'))!=""?$this->input->get('next'):site_url();
		redirect($next);
	}

	function set_status(){
		$this->jCfg['search']['status'] = $this->input->get('status');
		$this->_releaseSession();
		$next = $this->input->get('next')&&trim($this->input->get('next'))!=""?$this->input->get('next'):site_url();
		redirect($next);
	}

	function set_done(){
		$val =  $this->input->get('v')==1?TRUE:FALSE;
		$this->jCfg['search']['is_done'] = $val;
		$this->jCfg['search']['status'] = "";
		$this->jCfg['search']['sla'] = "all";
		$this->_releaseSession();
		$next = $this->input->get('next')&&trim($this->input->get('next'))!=""?$this->input->get('next'):site_url();
		redirect($next);
	}
}