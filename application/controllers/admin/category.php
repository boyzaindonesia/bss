<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Category extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Kategori Artikel' );
		$this->DATA->table="mt_article_category";
		$this->folder_view = "content/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
			"title"		=> "Kategori Artikel",
			"url"		=> $this->own_link
		);

		$this->upload_path="./assets/collections/article/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 450, 'quality'	=> '85%'),
			array('name'	=> 'small','width'	=> 1024, 'quality'	=> '85%'),
			array('name'	=> 'large','width'	=> 1920, 'quality'	=> '85%')
		);
		$this->image_size_str = "Maks 1920px";
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$this->db->order_by('position','asc');
		$data['data'] = $this->db->get_where("mt_article_category",array(
			"category_istrash" => 0
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
				'category_id'	=> $id
			));
			if(empty($this->data_form->category_id)){
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
				'category_id'	=> $id
			));
			if(empty($this->data_form->category_id)){
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
			$this->_delte_old_files(
				array(
					'field' => 'category_image', 
					'par'	=> array('category_id' => $id)
			));
			$this->DATA->_delete(array("category_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_article_category",array(
			"category_istrash"	=> 1
		))->result();
		foreach($data as $r){ 
			$id = $r->category_id;
			$this->_delte_old_files(
				array(
					'field' => 'category_image', 
					'par'	=> array('category_id' => $id)
			));
			$this->DATA->_delete(array("category_id"	=> idClean($id)),true);	
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		// if (dbClean($_POST['category_id']) == ""){
		// 	$data = $this->db->order_by('position','asc')->get_where("mt_article_category",array(
		// 		"category_istrash !="	=> 1
		// 	))->result();	
		// 	$position = 1;
		// 	foreach($data as $r){ 
		// 		$id = $r->category_id;
		// 		$this->db->update("mt_article_category",array("position"=>$position),array("category_id"=>$id));
		// 		$position +=1;
		// 	} 
		// }

		$data = array(
			'category_title'		=> dbClean(ucwords($_POST['category_title'])),
			'category_desc'			=> dbClean($_POST['category_desc']),
			'category_status'		=> dbClean($_POST['category_status']),
			'category_parent_id'	=> dbClean($_POST['category_parent_id'])
		);	
		
		if (dbClean($_POST['category_id']) == "") {
			$data['category_date'] = timestamp();

			$title = dbClean($_POST['category_title']);
			if($title==''){ $title = 'category'; }
			$data['url'] = generateUniqueURL($title,"mt_article_category");

			$position = $this->db->select_max('position')->get_where("mt_article_category",array(
				"category_istrash !="	=> 1
			))->row();
			$data['position'] = $position->position + 1;
		} else {
			$v = $this->db->get_where("mt_article_category",array(
				"category_id"	=> $_POST['category_id']
			),1,0)->row();
			if($v->category_title != $data['category_title']){
				$data['url'] = generateUniqueURL($data['category_title'],"mt_article_category");
			}
		}

		$a = $this->_save_master( 
			$data,
			array(
				'category_id' => dbClean($_POST['category_id'])
			),
			dbClean($_POST['category_id'])			
		);

		$id = $a['id'];
		if(dbClean($_POST['remove_images']) == 1){
			$this->_delte_old_files(
			array(
				'field' => 'category_image', 
				'par'	=> array('category_id' => $id)
			));

			$this->db->update("mt_article_category",array("category_image"=>NULL),array("category_id"=>$id));
		} else {
			$this->_uploaded(
			array(
				'id'		=> $id ,
				'input'		=> 'category_image',
				'param'		=> array(
								'field' => 'category_image', 
								'par'	=> array('category_id' => $id)
							)
			));
		}

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['category_title'])."?msg=".urlencode('Save data category success')."&type_msg=success");
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
						'category_id' => dbClean((int)$id)
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
			$this->db->update("mt_article_category",array("category_status"=>$val),array("category_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
