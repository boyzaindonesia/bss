<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class user_group extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("access","view","edit","delete"),"ITEM");
		$this->_set_title( 'Grup Pengguna' );
		$this->DATA->table="mt_app_acl_group";
		$this->folder_view = "config/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
			"title"		=> "Grup Pengguna",
			"url"		=> $this->own_link
		);
 
	}

	function index(){
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
		$this->_v($this->folder_view.$this->prefix_view."_form",array());
	}
	
	function view($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'ag_id'	=> $id
			));
			if(empty($this->data_form->ag_id)){
				redirect($this->own_link."?msg=".urlencode('Data user group tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_view",array());
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
				'ag_id'	=> $id
			));
			if(empty($this->data_form->ag_id)){
				redirect($this->own_link."?msg=".urlencode('Data user group tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_form",array());
		}else{
			redirect($this->own_link);
		}
	}
	
	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!= ''){
			if(trim($id)==1){
				redirect($this->own_link."?msg=".urlencode('Administrator tidak dapat dihapus')."&type_msg=error");
			} else {
				$o = $this->DATA->_delete(
					array("ag_id"	=> idClean($id)),
					true
				);
				$this->db->delete("mt_app_acl_group_accesses",array('aga_group_id'=>$id));	
				redirect($this->own_link."?msg=".urlencode('Delete data group success')."&type_msg=success");
			}
		}
	}

	function save(){
		$data = array(
			'ag_group_name'		=> dbClean(ucwords($_POST['ag_group_name'])),
			'ag_group_desc'		=> dbClean($_POST['ag_group_desc']),
			'ag_group_status'	=> dbClean($_POST['ag_group_status'])
		);		
		$a = $this->_save_master( 
			$data,
			array(
				'ag_id' => dbClean($_POST['ag_id'])
			),
			dbClean($_POST['ag_id'])			
		);

		$id = $a['id'];

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['ag_group_name'])."?msg=".urlencode('Save data group success')."&type_msg=success");
	}
	
	function access($id=''){

		$this->breadcrumb[] = array(
			"title"		=> "List Module"
		);

		$id = explode("-", $id);
		$name = dbClean(trim($id[1]));
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			if(isset($_POST['simpan'])){	
				$this->db->delete("mt_app_acl_group_accesses",array('aga_group_id'=>$id));	
				
				if(isset($_POST['acc_name']) && count($_POST['acc_name']) > 0){
					foreach($_POST['acc_name'] as $id_access=>$v){
						$this->DATA->table = "mt_app_acl_group_accesses";
						if(count($v)>0){
							foreach($v as $id_action){
								$data_actions = array(
									'aga_access_id' => $id_access,
									'aga_group_id'	=> $id,
									'aga_action_id'	=> $id_action
								);
								$this->DATA->_add($data_actions);
							}
						}
						
					}
				}
				redirect($this->own_link."/access/".$id."-".$name."?msg=".urlencode('Update for Access Control List Success')."&type_msg=success");
					
			}else{
				$this->DATA->table="mt_app_acl_group";
				$group=$this->DATA->data_id(array("ag_id"=>$id));
				$this->DATA->table="mt_app_acl_actions";
				$actions = $this->DATA->_getall();
				$this->_set_title('List Akses '.ucwords($group->ag_group_name));	
			
				$m_tbl=array();
				$this->DATA->table="mt_app_acl_accesses";
				$access_mod = $this->db->query("select * from ".$this->DATA->table." where acc_istrash = 0 order by acc_by_order asc")->result();
				
				foreach($access_mod as $m){
					$action_module = array();
					foreach($actions as $o){
						$this->DATA->table="mt_app_acl_group_accesses";
						$val = $this->DATA->data_id(array(
											"aga_access_id"	=> $m->acc_id,
											"aga_group_id"	=> $id,
											"aga_action_id"	=> $o->ac_id
									));
						$this->DATA->table="mt_app_acl_access_actions";
						$obj = $this->DATA->data_id(array(
											"aca_access_id"	=> $m->acc_id,
											"aca_action_id"	=> $o->ac_id
									));
						
						$action_module[]=array(
							'id'	=> $o->ac_id,
							'name'	=> $o->ac_action,
							'show'	=> count($obj),
							'value'	=> count($val)
						);
					}
					$m_tbl[$m->acc_id] = array(
						'id_module'		=> $m->acc_id,
						'module_name'	=> $m->acc_access_name,
						'action'		=> $action_module
					);
				}			
				
				$this->_v($this->folder_view.$this->prefix_view."_access",array(
						"actions"	=> $actions,
						"access"	=> $m_tbl 
					)								 	
				);
			}	
		}
		
	}

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_app_acl_group",array("ag_group_status"=>$val),array("ag_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

	function check_form(){
		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_form' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$thisChkId     = dbClean(trim($_POST['thisChkId']));
			$thisChkParent = dbClean(trim($_POST['thisChkParent']));
			$thisChkRel    = dbClean(trim($_POST['thisChkRel']));
			
			// $this->DATA->table="mt_app_acl_group";
			if(trim($thisVal)!=''){
				if(trim($thisChkId)!=''){
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	   => $thisVal,
						'ag_id !='     => $thisChkId
					));
				} else {
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	=> $thisVal
					));
				}
				if(empty($this->data_form->$thisChkRel)){
					$err = false;
					$msg = '';
				} else {
					$err = true;
					$msg = 'Data sudah ada...';
				}
			}
		}

		$return = array('msg' => $msg,'err' => $err);
		die(json_encode($return));
		exit();
	}

}
