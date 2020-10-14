<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Headline extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Berita Utama' );
		$this->DATA->table="mt_headline";
		$this->folder_view = "content/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
			"title"		=> "Berita Utama",
			"url"		=> $this->own_link
		);

		$this->upload_path="./assets/collections/headline/";
		$this->upload_resize  = array(
			array('name'	=> 'large')
		);
		$this->image_size_str = "Size: 1920px x 659px";
		$this->image_size_str_mobile = "Size: 720px x 247px";
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$this->db->order_by('position','asc');
		$data['data'] = $this->db->get_where("mt_headline",array(
			"headline_istrash" => 0
		))->result();

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
			$this->data_form = $this->DATA->data_id(array(
				'headline_id'	=> $id
			));
			if(empty($this->data_form->headline_id)){
				redirect($this->own_link."?msg=".urlencode('Data headline tidak ditemukan')."&type_msg=error");
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
				'headline_id'	=> $id
			));
			if(empty($this->data_form->headline_id)){
				redirect($this->own_link."?msg=".urlencode('Data headline tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_form");
		}else{
			redirect($this->own_link);
		}
	}

	function duplicate($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "Duplicate"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				"headline_id"	=> $id
			));
			if(empty($this->data_form->headline_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$this->data_form->headline_id 				= "";
			$this->data_form->headline_images 			= "";
			$this->data_form->headline_images_mobile 	= "";
			$this->_v($this->folder_view.$this->prefix_view."_form",$data);
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
				'field' => 'headline_images',
				'par'	=> array('headline_id' => $id)
			));

			$this->upload_path="./assets/collections/headline/";
			$this->upload_resize  = array(
				array('name'	=> 'large')
			);
			$this->_delte_old_files(
			array(
				'field' => 'headline_images_mobile',
				'par'	=> array('headline_id' => $id)
			));

		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_headline",array(
			"headline_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->headline_id;
			$this->_delte_old_files(
			array(
				'field' => 'headline_images',
				'par'	=> array('headline_id' => $id)
			));

			$this->upload_path="./assets/collections/headline/";
			$this->upload_resize  = array(
				array('name'	=> 'large')
			);
			$this->_delte_old_files(
			array(
				'field' => 'headline_images_mobile',
				'par'	=> array('headline_id' => $id)
			));
			$this->DATA->_delete(array("headline_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		if (dbClean($_POST['headline_title']) != ""){

			if (dbClean($_POST['headline_id']) == ""){
				$data = $this->db->order_by('position','asc')->get_where("mt_headline",array(
					"headline_istrash !="	=> 1
				))->result();
				$position = 1;
				foreach($data as $r){
					$id = $r->headline_id;
					$this->db->update("mt_headline",array("position"=>$position),array("headline_id"=>$id));
					$position +=1;
				}
			}

			$setting = array(
				'textposition' 		=> dbClean($_POST['headline_setting_textposition']),
				'textalign' 		=> dbClean($_POST['headline_setting_textalign']),
				'title1datax' 		=> dbClean($_POST['headline_setting_title1datax']),
				'title1datay' 		=> dbClean($_POST['headline_setting_title1datay']),
				'title1datahoffset' => dbClean($_POST['headline_setting_title1datahoffset']),
				'title1datavoffset' => dbClean($_POST['headline_setting_title1datavoffset']),
				'title2datax' 		=> dbClean($_POST['headline_setting_title2datax']),
				'title2datay' 		=> dbClean($_POST['headline_setting_title2datay']),
				'title2datahoffset' => dbClean($_POST['headline_setting_title2datahoffset']),
				'title2datavoffset' => dbClean($_POST['headline_setting_title2datavoffset']),
				'title3datax' 		=> dbClean($_POST['headline_setting_title3datax']),
				'title3datay' 		=> dbClean($_POST['headline_setting_title3datay']),
				'title3datahoffset' => dbClean($_POST['headline_setting_title3datahoffset']),
				'title3datavoffset' => dbClean($_POST['headline_setting_title3datavoffset']),
				'buttondatax' 		=> dbClean($_POST['headline_setting_buttondatax']),
				'buttondatay' 		=> dbClean($_POST['headline_setting_buttondatay']),
				'buttondatahoffset' => dbClean($_POST['headline_setting_buttondatahoffset']),
				'buttondatavoffset' => dbClean($_POST['headline_setting_buttondatavoffset'])
			);

			$data = array(
				'headline_title'				=> dbClean($_POST['headline_title']),
				'headline_title_2'				=> dbClean($_POST['headline_title_2']),
				'headline_title_3'				=> dbClean($_POST['headline_title_3']),
				'headline_status'				=> dbClean($_POST['headline_status']),
				'headline_desc'					=> dbClean($_POST['headline_desc']),
				'headline_setting'			    => json_encode($setting)
			);

			$link_type  = dbClean($_POST['link_type']);
			$link_value = dbClean($_POST['link_value'][$link_type]);
			$write_link = get_write_link($link_type,$link_value);
			$data['link_type']  = $write_link['link_type'];
			$data['link_value'] = $write_link['link_value'];

			if (dbClean($_POST['headline_id']) == "") {
				$data['headline_date'] = timestamp();
			}

			///debugCode($data);
			$a = $this->_save_master(
				$data,
				array(
					'headline_id' => dbClean($_POST['headline_id'])
				),
				dbClean($_POST['headline_id'])
			);

			$id = $a['id'];
			if(dbClean($_POST['remove_images']) == 1){
				$this->_delte_old_files(
				array(
					'field' => 'headline_images',
					'par'	=> array('headline_id' => $id)
				));

				$this->db->update("mt_headline",array("headline_images"=>NULL),array("headline_id"=>$id));
			} else {
				$this->_uploaded(
				array(
					'id'		=> $id ,
					'input'		=> 'headline_images',
					'param'		=> array(
									'field' => 'headline_images',
									'par'	=> array('headline_id' => $id)
								)
				));
			}

			if(dbClean($_POST['remove_images_mobile']) == 1){
				$this->_delte_old_files(
				array(
					'field' => 'headline_images_mobile',
					'par'	=> array('headline_id' => $id)
				));
				$this->db->update("mt_headline",array("headline_images_mobile"=>NULL),array("headline_id"=>$id));
			} else {
				$this->_uploaded(
				array(
					'id'		=> $id ,
					'input'		=> 'headline_images_mobile',
					'param'		=> array(
									'field' => 'headline_images_mobile',
									'par'	=> array('headline_id' => $id)
								)
				));
			}

			if(dbClean($_POST['remove_images_child']) == 1){
				$this->_delte_old_files(
				array(
					'field' => 'headline_images_child',
					'par'	=> array('headline_id' => $id)
				));
				$this->db->update("mt_headline",array("headline_images_child"=>NULL),array("headline_id"=>$id));
			} else {
				$this->_uploaded(
				array(
					'id'		=> $id ,
					'input'		=> 'headline_images_child',
					'param'		=> array(
									'field' => 'headline_images_child',
									'par'	=> array('headline_id' => $id)
								)
				));
			}

			redirect($this->own_link."?msg=".urlencode('Save data category success')."&type_msg=success");
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
						'headline_id' => dbClean((int)$id)
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
			$this->db->update("mt_headline",array("headline_status"=>$val),array("headline_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
