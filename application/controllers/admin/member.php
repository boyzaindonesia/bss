<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Member extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'List Data Member' );
		$this->DATA->table="mt_member";
		$this->folder_view = "member/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_member","M");
		$this->breadcrumb[] = array(
				"title"		=> "Data Member",
				"url"		=> $this->own_link
			);

		$this->upload_path="./assets/collections/photo/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 50, 'quality'	=> '85%'),
			array('name'	=> 'small','width'	=> 200, 'quality'	=> '85%')
		);
		$this->image_size_str = "Size: 200px x 200px";

		$this->cat_search = array(
			''									=> 'Semua Pencarian...',
			'mt_member.member_name'				=> 'Nama Member',
		);
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'member',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_member.member_date',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}


	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'member'){
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
		$this->data_table = $this->M->data_member($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		$data['url'] = base_url()."admin/member/index";

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function export_data(){
		$data = array();

		$this->data_table = $this->M->data_member();
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		$this->_v($this->folder_view.$this->prefix_view."_export",$data,FALSE);
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
				'member_id'	=> $id
			));
			if(empty($this->data_form->member_id)){
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
				'member_id'	=> $id
			));
			if(empty($this->data_form->member_id)){
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
			$this->db->update("mt_member",array("member_istrash"=>1),array("member_id"=>$id));
		}
		redirect($this->own_link."?msg=".urlencode('Delete data member success')."&type_msg=success");
	}

	// function delete($id=''){
	// 	$id=dbClean(trim($id));
	// 	if(trim($id) != ''){
	// 		$this->_delte_old_files(
	// 			array(
	// 				'field' => 'member_photo',
	// 				'par'	=> array('member_id' => $id)
	// 		));
	// 		$this->DATA->_delete(array("member_id"	=> idClean($id)),true);
	// 	}
	// 	redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	// }

	function empty_trash(){
		$data = $this->db->get_where("mt_member",array(
			"member_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->member_id;
			$this->_delte_old_files(
				array(
					'field' => 'member_photo',
					'par'	=> array('member_id' => $id)
			));
			$this->DATA->_delete(array("member_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'member_name'			=> dbClean(ucwords($_POST['member_name'])),
			'member_username'		=> dbClean($_POST['member_username']),
			'member_email'			=> dbClean($_POST['member_email']),
			'member_jenis_kelamin'	=> dbClean($_POST['member_jenis_kelamin']),
			'member_tempat_lahir'	=> dbClean($_POST['member_tempat_lahir']),
			'member_tgl_lahir'		=> dbClean(convDatepickerDec($_POST['pdate'])).' 00 00:00:00',
			'member_phone'			=> dbClean($_POST['member_phone']),
			'newsletter'			=> isset($_POST['newsletter'])?1:0
		);

		if (dbClean($_POST['member_id']) == "") {
			$data['member_status']  = 1;
			$data['member_date']    = timestamp();
		}

		if( isset($_POST['member_password']) && trim($_POST['member_password']) != ''){
			$data['member_password'] = md5(dbClean($_POST['member_password']));
		}

		$a = $this->_save_master(
			$data,
			array(
				'member_id' => dbClean($_POST['member_id'])
			),
			dbClean($_POST['member_id'])
		);

		$id = $a['id'];
		if(dbClean($_POST['remove_images']) == 1){
			$this->_delte_old_files(
			array(
				'field' => 'member_photo',
				'par'	=> array('member_id' => $id)
			));

			$this->db->update("mt_member",array("member_photo"=>NULL),array("member_id"=>$id));
		} else {
			$this->_uploaded(
			array(
				'id'		=> $id ,
				'input'		=> 'member_photo',
				'param'		=> array(
								'field' => 'member_photo',
								'par'	=> array('member_id' => $id)
							)
			));
		}

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['member_name'])."?msg=".urlencode('Save data member success')."&type_msg=success");
	}

	function change_avatar($id=''){

		if(isset($_POST['avatar_src'])){
			$this->DATA->table = "mt_member";
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
			$_FILES['member_photo'] = array(
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
				'input'		=> 'member_photo',
				'param'		=> array(
								'field' => 'member_photo',
								'par'	=> array('member_id' => $id)
							)
			));

			$response = array(
			  'state'  => 200,
			  'message' => '',
			  'result' => get_image(base_url()."assets/collections/photo/small/".get_member_photo($id))
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
			$this->db->update("mt_member",array("member_status"=>$val),array("member_id"=>$id));
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

			if(trim($thisVal)!=''){
				if(trim($thisChkId)!=''){
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	   => $thisVal,
						'member_id !=' => $thisChkId
					));
				} else {
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	=> $thisVal
					));
				}
				if(empty($this->data_form->$thisChkRel)){
					$err = false;
					$msg = '';
					if(($thisChkRel=='member_username')&&(checkIsRoute($thisVal))){
						$err = true;
						$msg = 'Data sudah ada...';
					}
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
