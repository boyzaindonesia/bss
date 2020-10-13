<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class example_jquery extends AdminController {  
	function __construct()    
	{
		parent::__construct();
			    
		$this->_set_action();
		$this->_set_action(array("detail"),"ITEM");
		$this->_set_title( 'Example Jquery' );
		// $this->DATA->table = "mt_app_user";
		$this->folder_view = "example/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->breadcrumb[] = array(
			"title"		=> "Example Jquery",
			"url"		=> $this->own_link
		);
		$this->upload_path="./assets/images/";
		$this->image_size_str = "300px x 60px";
	}
	
	function index(){
		$data['configuration'] = $this->db->get("mt_configuration")->row();

		$this->DATA->table = "mt_app_user";
		$data['user'] = $this->DATA->data_id(array(
			'user_id'	=> 1
		));

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function save(){
		// $data = array(
		// 	'configuration_name'			=> dbClean($_POST['configuration_name']),
		// 	'configuration_about'			=> dbClean($_POST['configuration_about']),
		// 	'configuration_alamat'			=> dbClean($_POST['configuration_alamat']),
		// 	'configuration_email'			=> dbClean($_POST['configuration_email']),
		// 	'configuration_email_cc'		=> dbClean(''),
		// 	'configuration_telp'			=> dbClean($_POST['configuration_telp']),
		// 	'configuration_fax'				=> dbClean($_POST['configuration_fax']),		
		// );

		// if($_POST['configuration_email_cc']){
		// 	$i = 0; $arr = '';
		// 	foreach ($_POST['configuration_email_cc'] as $key){ $arr .= ($i==0?'':',').$key; $i += 1; }
		// 	$data['configuration_email_cc'] = dbClean($arr);
		// }

		// if (dbClean($_POST['configuration_id']) == "") {
		// 	$data['configuration_date'] = timestamp();
		// }
		// $a = $this->_save_master( 
		// 	$data,
		// 	array(
		// 		'configuration_id' => dbClean($_POST['configuration_id'])
		// 	),
		// 	dbClean($_POST['configuration_id'])			
		// );
		// $id = $a['id'];
		// $this->_uploaded(
		// array(
		// 	'id'		=> $id ,
		// 	'input'		=> 'configuration_logo',
		// 	'param'		=> array(
		// 					'field' => 'configuration_logo', 
		// 					'par'	=> array('configuration_id' => $id)
		// 				)
		// ));
		
		redirect($this->own_link."?msg=".urlencode('Save data success')."&type_msg=success");
	}


	function check_form(){
		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_form' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$thisChkId     = dbClean(trim($_POST['thisChkId']));
			$thisChkParent = dbClean(trim($_POST['thisChkParent']));
			$thisChkRel    = dbClean(trim($_POST['thisChkRel']));
			
			$this->DATA->table="mt_app_user";
			if(trim($thisVal)!=''){
				if(trim($thisChkId)!=''){
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	   => $thisVal,
						'user_id !='   => $thisChkId
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

	function check_autocomplete(){
		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_autocomplete' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$bold_thisVal  = '<strong>'.$thisVal.'</strong>';
			
			// $this->DATA->table="mt_app_user";
			if(trim($thisVal)!=''){
				$v = $this->db->get_where("mt_app_city",array(
					'city_title LIKE' => '%'.$thisVal.'%'
				))->result();
				if(!empty($v)){
					foreach($v as $r => $v){
						$err = false;
						$msg .= '<div class="feedback text-left"><span class="name">'.str_ireplace($thisVal,$bold_thisVal,$v->city_title).'</span></div>'; 
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