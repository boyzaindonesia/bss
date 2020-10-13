<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class User extends AdminController {  
	function __construct()   
	{
		parent::__construct();     
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title('Pengguna');
		$this->DATA->table="mt_app_user";
		$this->folder_view = "config/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->upload_path="./assets/collections/photo/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 50, 'quality'	=> '85%'),
			array('name'	=> 'small','width'	=> 200, 'quality'	=> '85%')
		);
		$this->image_size_str = "Size: 200px x 200px";

		$this->breadcrumb[] = array(
			"title"		=> "Pengguna",
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
		
	function cek_user(){
		echo _ajax_cek(array(
			"field" => "user_name",
			"table"	=> "iapi_user"
		));
	}
	
	function add(){
		$this->breadcrumb[] = array(
			"title"		=> "Tambah"
		);
		$this->_v($this->folder_view.$this->prefix_view."_form",array(
			'group'		=> $this->db->get_where("mt_app_acl_group",array(
				"ag_group_status"	=> "1",
				"is_trash <>" 		=> "1"
			))->result()
		));
	}

	function view($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'user_id'	=> $id
			));
			if(empty($this->data_form->user_id)){
				redirect($this->own_link."?msg=".urlencode('Data user tidak ditemukan')."&type_msg=error");
			}
					
			$this->_v($this->folder_view.$this->prefix_view."_view",array(
					'group'		=> $this->db->get_where("mt_app_acl_group",array(
					"ag_group_status"	=>	"1",
					"is_trash <>" => "1"	
				))->result()
			));

		} else {
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
				'user_id'	=> $id
			));
			if(empty($this->data_form->user_id)){
				redirect($this->own_link."?msg=".urlencode('Data user tidak ditemukan')."&type_msg=error");
			}
					
			$this->_v($this->folder_view.$this->prefix_view."_form",array(
				'group'		=> $this->db->get_where("mt_app_acl_group",array(
					"ag_group_status"	=>	"1",
					"is_trash <>" => "1"	
				))->result()
			));
		} else {
			redirect($this->own_link);
		}
	}
	
	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			$this->_delte_old_files(
				array(
					'field' => 'user_photo', 
					'par'	=> array('user_id' => $id)
			));
			$this->DATA->_delete(array("user_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_app_user",array(
			"is_trash"	=> 1
		))->result();
		foreach($data as $r){ 
			$id = $r->user_id;
			$this->_delte_old_files(
				array(
					'field' => 'user_photo', 
					'par'	=> array('user_id' => $id)
			));
			$this->DATA->_delete(array("user_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'user_fullname'		=> dbClean(ucwords($_POST['user_fullname'])),
			'user_email'		=> dbClean($_POST['user_email']),
			'user_status'		=> isset($_POST['user_status'])?1:0,
			'is_show_all'		=> isset($_POST['is_show_all'])?1:0,
			'user_group'		=> dbClean($_POST['user_group']),
			'store_id'			=> dbClean($_POST['store_id'])
		);		
		
		if( isset($_POST['user_name']) && trim($_POST['user_name']) != ''){
			$data['user_name'] = dbClean($_POST['user_name']);
		}
		if( isset($_POST['user_password']) && trim($_POST['user_password']) != ''){
			$data['user_password'] = md5(dbClean($_POST['user_password']));
		}
		
		$a = $this->_save_master( 
			$data,
			array(
				'user_id' => dbClean($_POST['user_id'])
			),
			dbClean($_POST['user_id'])			
		);

		$id = $a['id'];
		if(dbClean($_POST['remove_images']) == 1){
			$this->_delte_old_files(
			array(
				'field' => 'user_photo', 
				'par'	=> array('user_id' => $id)
			));

			$this->db->update("mt_app_user",array("user_photo"=>NULL),array("user_id"=>$id));
		} else {
			$this->_uploaded(
			array(
				'id'		=> $id ,
				'input'		=> 'user_photo',
				'param'		=> array(
								'field' => 'user_photo', 
								'par'	=> array('user_id' => $id)
							)
			));
		}
	
		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['user_fullname'])."?msg=".urlencode('Save data user success')."&type_msg=success");
	}

	function change_avatar($id=''){

		if(isset($_POST['avatar_src'])){
			$this->DATA->table = "mt_app_user";
			// $avatar_data = json_decode('['.$_POST['avatar_data'].']', true);
			// foreach($avatar_data as $k => $v){
			// 	$x = $v['x'];
			// 	$y = $v['y'];
			// 	$w = $v['width'];
			// 	$h = $v['height'];
			// 	$r = $v['rotate'];
			// }

			$data = $_POST['avatar_src'];
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
			 file_put_contents($temp_file_path, $data);
			 $image_info = getimagesize($temp_file_path); 
			 $_FILES['user_photo'] = array(
			     'name' => 'avatar.jpg',
			     'tmp_name' => $temp_file_path,
			     'size'  => filesize($temp_file_path),
			     'error' => UPLOAD_ERR_OK,
			     'type'  => 'jpg',
			 );
			
			$this->upload_path="./assets/collections/photo/";
			$this->upload_resize  = array(
				array('name'	=> 'thumb','width'	=> 50, 'quality'	=> '85%'),
				array('name'	=> 'small','width'	=> 200, 'quality'	=> '85%')
			);
			$this->image_size_str = "Size: 200px x 200px";

			$id  = dbClean(trim($id));
			$this->_uploaded(
			array(
				'id'		=> $id ,
				'input'		=> 'user_photo',
				'param'		=> array(
								'field' => 'user_photo', 
								'par'	=> array('user_id' => $id)
							)
			));

			$response = array(
			  'state'  => 200,
			  'message' => '',
			  'result' => get_image(base_url()."assets/collections/photo/small/".get_user_photo($id))
			);
			die(json_encode($response));
			exit();
		}
	}
	
	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_app_user",array("user_status"=>$val),array("user_id"=>$id));
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
			
			// $this->DATA->table="mt_app_user";
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

}
