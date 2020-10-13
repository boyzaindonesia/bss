<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class ds_product extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Produk Dropship' );
		$this->DATA->table="mt_ds_product";
		$this->folder_view = "dropship/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
				"title"		=> "Produk Dropship",
				"url"		=> $this->own_link
			);
		$this->cat_search = array(
			''					=> 'All Search...',
			'ds_product_name'	=> 'Title'
		);

		$this->user_id	= isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
		$this->store_id = get_user_store($this->user_id);
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'ds_product',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'ds_product_date',
			'order_dir' => 'DESC',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$this->db->order_by('ds_product_date','DESC');
		$data['data'] = $this->db->get_where("mt_ds_product",array(
			"ds_product_istrash" => 0
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
				'ds_product_id'	=> $id
			));
			if(empty($this->data_form->ds_product_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
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
				'ds_product_id'	=> $id
			));
			if(empty($this->data_form->ds_product_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
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
			$this->db->update("mt_ds_product",array("ds_product_istrash"=>1),array("ds_product_id"=>$id));
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_ds_product",array(
			"ds_product_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->ds_product_id;
			// $this->_delte_old_files(
			// 	array(
			// 		'field' => 'ds_product_image',
			// 		'par'	=> array('ds_product_id' => $id)
			// ));
			$this->DATA->_delete(array("ds_product_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		if (dbClean($_POST['ds_product_name']) != "") {
			$data = array(
				'ds_product_name'	=> dbClean($_POST['ds_product_name']),
				'ds_product_show'	=> dbClean($_POST['ds_product_show']),
				'ds_product_show'	=> dbClean($_POST['ds_product_show']),
				'ds_product_show'	=> dbClean($_POST['ds_product_show']),
				'ds_product_show'	=> dbClean($_POST['ds_product_show']),
				'ds_product_show'	=> dbClean($_POST['ds_product_show']),
				'ds_product_show'	=> dbClean($_POST['ds_product_show'])
			);

			if (dbClean($_POST['ds_product_id']) == "") {
				$data['ds_product_date'] = timestamp();
			}

			$a = $this->_save_master(
				$data,
				array(
					'ds_product_id' => dbClean($_POST['ds_product_id'])
				),
				dbClean($_POST['ds_product_id'])
			);

			redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['ds_product_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
		} else {
			redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['ds_product_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
		}
	}

	// function change_position(){
	// 	if ($_POST) {
	// 		$temp_position = $_SERVER['REMOTE_ADDR'];
	// 		$ids    = $_POST["ids"];
	// 		for ($idx = 0; $idx < count($ids); $idx+=1) {
	// 			$id = $ids[$idx];
	// 			//...
	// 			$data = array(
	// 				'position'		=> dbClean($idx),
	// 				'temp_position'	=> dbClean($temp_position),
	// 			);

	// 			$a = $this->_save_master(
	// 				$data,
	// 				array(
	// 					'ds_product_id' => dbClean((int)$id)
	// 				),
	// 				dbClean((int)$id)
	// 			);
	// 		}
	// 		return;
	// 	}
	// }

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_ds_product",array("ds_product_show"=>$val),array("ds_product_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
