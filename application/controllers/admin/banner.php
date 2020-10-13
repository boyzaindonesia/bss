<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class banner extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Banner' );
		$this->DATA->table="mt_banner";
		$this->folder_view = "content/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
			"title"		=> "Banner",
			"url"		=> $this->own_link
		);

		$this->upload_path="./assets/collections/banner/";
		$this->upload_resize  = array(
			array('name'	=> 'large')
		);
		// $this->image_size_str = "Size: 1920px x 659px";
		// $this->image_size_str_mobile = "Size: 720px x 247px";
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		// $this->db->order_by('position','asc');
		// $data['data'] = $this->db->get_where("mt_banner",array(
		// 	"banner_istrash" => 0
		// ))->result();

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function add(){
		$this->breadcrumb[] = array(
			"title"		=> "Add"
		);

		if(isset($_GET['banner_category']) && $_GET['banner_category'] != ''){
			$id = explode("-", $_GET['banner_category']);
			$id = dbClean(trim($id[0]));

			$this->DATA->table="mt_banner_category";
			$category = $this->DATA->data_id(array(
				'banner_category_id'	=> $id
			));
			if(empty($category->banner_category_id)){
				redirect($this->own_link."?msg=".urlencode('Category banner tidak ditemukan')."&type_msg=error");
			}

			$this->data_form->banner_category_id   = $category->banner_category_id;
			$this->data_form->banner_category_name = $category->banner_category_name;
			$this->data_form->banner_category_size = $category->banner_category_size;

			$this->_v($this->folder_view.$this->prefix_view."_form");
		} else {
			redirect($this->own_link);
		}
	}

	function view($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'banner_id'	=> $id
			));
			if(empty($this->data_form->banner_id)){
				redirect($this->own_link."?msg=".urlencode('Data banner tidak ditemukan')."&type_msg=error");
			}

			$this->DATA->table="mt_banner_category";
			$category = $this->DATA->data_id(array(
				'banner_category_id'	=> $this->data_form->banner_category_id
			));
			$this->data_form->banner_category_name = $category->banner_category_name;
			$this->data_form->banner_category_size = $category->banner_category_size;

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
				'banner_id'	=> $id
			));
			if(empty($this->data_form->banner_id)){
				redirect($this->own_link."?msg=".urlencode('Data banner tidak ditemukan')."&type_msg=error");
			}

			$this->DATA->table="mt_banner_category";
			$category = $this->DATA->data_id(array(
				'banner_category_id'	=> $this->data_form->banner_category_id
			));
			$this->data_form->banner_category_name = $category->banner_category_name;
			$this->data_form->banner_category_size = $category->banner_category_size;

			$this->_v($this->folder_view.$this->prefix_view."_form");
		}else{
			redirect($this->own_link);
		}
	}

	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			$this->_delte_old_files(
			array(
				'field' => 'banner_images',
				'par'	=> array('banner_id' => $id)
			));

			$this->_delte_old_files(
			array(
				'field' => 'banner_images_mobile',
				'par'	=> array('banner_id' => $id)
			));

		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_banner",array(
			"banner_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->banner_id;
			$this->_delte_old_files(
			array(
				'field' => 'banner_images',
				'par'	=> array('banner_id' => $id)
			));

			$this->_delte_old_files(
			array(
				'field' => 'banner_images_mobile',
				'par'	=> array('banner_id' => $id)
			));
			$this->DATA->_delete(array("banner_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		if (dbClean($_POST['banner_title']) != ""){

			if (dbClean($_POST['banner_id']) == ""){
				$data = $this->db->order_by('position','asc')->get_where("mt_banner",array(
					"banner_category_id" => dbClean($_POST['banner_category_id']),
					"banner_istrash"	 => 0
				))->result();
				$position = 1;
				foreach($data as $r){
					$id = $r->banner_id;
					$this->db->update("mt_banner",array("position"=>$position),array("banner_id"=>$id));
					$position += 1;
				}
			}

			$data = array(
				'banner_category_id' => dbClean($_POST['banner_category_id']),
				'banner_title'		 => dbClean($_POST['banner_title']),
				'banner_desc'		 => dbClean($_POST['banner_desc']),
				'banner_status'		 => dbClean($_POST['banner_status'])
			);

			$link_type  = dbClean($_POST['link_type']);
			$link_value = dbClean($_POST['link_value'][$link_type]);
			$write_link = get_write_link($link_type,$link_value);
			$data['link_type']  = $write_link['link_type'];
			$data['link_value'] = $write_link['link_value'];

			if (dbClean($_POST['banner_id']) == "") {
				$data['banner_date'] = timestamp();
			}

			$a = $this->_save_master(
				$data,
				array(
					'banner_id' => dbClean($_POST['banner_id'])
				),
				dbClean($_POST['banner_id'])
			);

			$id = $a['id'];

			if(dbClean($_POST['remove_images']) == 1){
				$this->_delte_old_files(
				array(
					'field' => 'banner_images',
					'par'	=> array('banner_id' => $id)
				));

				$this->db->update("mt_banner",array("banner_images"=>NULL),array("banner_id"=>$id));
			} else {
				$this->_uploaded(
				array(
					'id'		=> $id ,
					'input'		=> 'banner_images',
					'param'		=> array(
									'field' => 'banner_images',
									'par'	=> array('banner_id' => $id)
								)
				));
			}

			if(dbClean($_POST['remove_images_mobile']) == 1){
				$this->_delte_old_files(
				array(
					'field' => 'banner_images_mobile',
					'par'	=> array('banner_id' => $id)
				));
				$this->db->update("mt_banner",array("banner_images_mobile"=>NULL),array("banner_id"=>$id));
			} else {
				$this->_uploaded(
				array(
					'id'		=> $id ,
					'input'		=> 'banner_images_mobile',
					'param'		=> array(
									'field' => 'banner_images_mobile',
									'par'	=> array('banner_id' => $id)
								)
				));
			}

			redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['banner_title'])."?msg=".urlencode('Berhasil simpan data banner.')."&type_msg=success");
		} else {
			redirect($this->own_link."?msg=".urlencode('Kolom harus diisi.')."&type_msg=error");
		}
	}

	function change_position(){
		if ($_POST) {
			$temp_position = $_SERVER['REMOTE_ADDR'];
			$ids    = $_POST["ids"];
			for ($idx = 0; $idx < count($ids); $idx+=1) {
				$id = $ids[$idx];
				//...
				$data = array(
					'position'		=> dbClean($idx),
					'temp_position'	=> dbClean($temp_position),
				);

				$a = $this->_save_master(
					$data,
					array(
						'banner_id' => dbClean((int)$id)
					),
					dbClean((int)$id)
				);
			}
			return;
		}
	}

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_banner",array("banner_status"=>$val),array("banner_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
