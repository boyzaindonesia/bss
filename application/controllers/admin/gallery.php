<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class gallery extends AdminController {  
	function __construct()     
	{
		parent::__construct();  
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'List Gallery' );
		$this->DATA->table = "mt_gallery";
		$this->folder_view = "gallery/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_gallery","M");
		$this->breadcrumb[] = array(
				"title"		=> "List Gallery",
				"url"		=> $this->own_link
			);
		$this->cat_search = array(
			''						=> 'All Search...',
			'mt_gallery.gallery_name'	=> 'Title',
		); 
		
		$this->upload_path="./assets/collections/gallery/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 450, 'quality'	=> '85%'),
			array('name'	=> 'small','width'	=> 1024, 'quality'	=> '85%'),
			array('name'	=> 'large','width'	=> 1920, 'quality'	=> '85%')
		);
		$this->image_size_str = "Size: 1920px x 1080px";
	}
	
	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'gallery',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_gallery.position',
			'order_dir' => 'asc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'gallery'){
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
		$this->data_table = $this->M->data_gallery($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));
			
		$data['url'] = base_url()."admin/gallery/index"; 

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
				'gallery_id'	=> $id
			));
			if(empty($this->data_form->gallery_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$this->db->order_by('position','asc');
			$data['gallery_detail'] = $this->db->get_where("mt_gallery_detail",array(
				'gallery_id'	=> $id
			))->result();
			
			$this->_v($this->folder_view.$this->prefix_view."_view",$data);
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
				'gallery_id'	=> $id
			));
			if(empty($this->data_form->gallery_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$this->db->order_by('position','asc');
			$data['gallery_detail'] = $this->db->get_where("mt_gallery_detail",array(
				'gallery_id'	=> $id
			))->result();
			
			$this->_v($this->folder_view.$this->prefix_view."_form",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			$this->DATA->table = "mt_gallery";
			$this->_delte_old_files(
				array(
					'field' => 'gallery_images', 
					'par'	=> array('gallery_id' => $id)
			));
			$this->DATA->_delete(array("gallery_id"	=> idClean($id)),true);	

			$dataDetail = $this->db->get_where("mt_gallery_detail",array(
				"gallery_id"	=> $id
			))->result();
			foreach($dataDetail as $rD){ 
				$idD = $rD->gallery_detail_id;
				$this->DATA->table = 'mt_gallery_detail';
				$this->_delte_old_files(
					array(
						'field' => 'gallery_detail_images', 
						'par'	=> array('gallery_detail_id' => $idD)
				));
				$this->db->delete("mt_gallery_detail",array('gallery_detail_id' => $idD));
			}
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_gallery",array(
			"gallery_istrash"	=> 1
		))->result();
		foreach($data as $r){ 
			$id = $r->gallery_id;
			$this->DATA->table = "mt_gallery";
			$this->_delte_old_files(
				array(
					'field' => 'gallery_images', 
					'par'	=> array('gallery_id' => $id)
			));
			$this->DATA->_delete(array("gallery_id"	=> idClean($id)),true);	

			$dataDetail = $this->db->get_where("mt_gallery_detail",array(
				"gallery_id"	=> $id
			))->result();
			foreach($dataDetail as $rD){ 
				$idD = $rD->gallery_detail_id;
				$this->DATA->table = 'mt_gallery_detail';
				$this->_delte_old_files(
					array(
						'field' => 'gallery_detail_images', 
						'par'	=> array('gallery_detail_id' => $idD)
				));
				$this->db->delete("mt_gallery_detail",array('gallery_detail_id' => $idD));
			}

		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		if (dbClean($_POST['gallery_id']) == ""){
			$data = $this->db->order_by('position','asc')->get_where("mt_gallery",array(
				"gallery_istrash !=" => 1
			))->result();	
			$position = 1;
			foreach($data as $r){ 
				$id = $r->gallery_id;
				$this->db->update("mt_gallery",array("position"=>$position),array("gallery_id"=>$id));
				$position +=1;
			}
		}

		$data = array(
			'gallery_name'	 => dbClean(ucwords($_POST['gallery_name'])),
			'gallery_desc'	 => dbClean($_POST['gallery_desc']),
			'gallery_video'	 => dbClean($_POST['gallery_video']),
			'gallery_status' => dbClean($_POST['gallery_status']),
			'gallery_date'	 => dbClean(convDatepickerDec($_POST['pdate'])).' '.dbClean(convTimepickerDec($_POST['phour'])).':00'
		);	

		if (dbClean($_POST['gallery_id']) == "") {
			$title = dbClean($_POST['gallery_name']);
			if($title==''){ $title = 'gallery'; }
			$data['url'] = generateUniqueURL($title,"mt_gallery");
		}	
		
		$a = $this->_save_master( 
			$data,
			array(
				'gallery_id' => dbClean($_POST['gallery_id'])
			),
			dbClean($_POST['gallery_id'])			
		);
		
		$id = $a['id'];
		if(dbClean($_POST['remove_images']) == 1){
			$this->_delte_old_files(
			array(
				'field' => 'gallery_images', 
				'par'	=> array('gallery_id' => $id)
			));

			$this->db->update("mt_gallery",array("gallery_images"=>NULL),array("gallery_id"=>$id));
		} else {
			$this->_uploaded(
			array(
				'id'		=> $id ,
				'input'		=> 'gallery_images',
				'param'		=> array(
								'field' => 'gallery_images', 
								'par'	=> array('gallery_id' => $id)
							)
			));
		}
		
		if ( isset($_POST['desc']) && count($_POST['desc']) > 0) {
			
			$cek = $this->db->get_where("mt_gallery_detail",array(
				'gallery_id' => $id
			))->result();
			
			$id_current_arr = array();
			if( count($cek) > 0 ){
				foreach($cek as $mm=>$vv){
					$id_current_arr[] = $vv->gallery_detail_id ;
				}
			} 			
			
			$item_del_arr = array();
			$position = 0;
			foreach ($_POST['desc'] as $kd => $vd) {
				
				$cek_pd = $this->db->get_where("mt_gallery_detail",array(
					'gallery_detail_id' => $kd
				))->row();
				
				if( count($cek_pd) == 0 ){	
					//inser item..
					$this->db->insert("mt_gallery_detail",array(
						"gallery_id" 			=> $id,
						"gallery_detail_name"	=> dbClean($vd),
						"position"				=> $position,
					));
					$item_id = $this->db->insert_id();
					
				}else{
					$this->db->update("mt_gallery_detail",array(
						"gallery_id" 			=> $id,
						"gallery_detail_name"	=> dbClean($vd),
						"position"				=> $position,
					),array(
						"gallery_detail_id"		=> $kd
					));
					
					$item_id = $kd;
				}
				$position +=1;
				
				$item_del_arr[] = $item_id;
				
				//upload item gallery..
				$this->DATA->table = 'mt_gallery_detail';
				if($_FILES['file_'.$kd]['error']!=4){
					$this->_uploaded(
					array(
						'id'		=> $item_id,
						'input'		=> 'file_'.$kd,
						'param'		=> array(
										'field' => 'gallery_detail_images', 
										'par'	=> array('gallery_detail_id' => $item_id )
									)
					));
				}
			}
			
			//delete item..
			if( count($id_current_arr) > 0 ){
				foreach ($id_current_arr as $ov) {
					if( !in_array($ov,$item_del_arr) ){
						
						$this->db->delete("mt_gallery_detail",array(
							'gallery_detail_id' => $ov
						));
						
					}
				}
			}
		}else{
			$this->db->delete("mt_gallery_detail",array(
				'gallery_id' => $id
			));
		}

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['gallery_name'])."?msg=".urlencode('Save data gallery success')."&type_msg=success");
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
						'gallery_id' => dbClean((int)$id)
					),
					dbClean((int)$id)			
				);
			}
			return;
		}
	}

	function change_position_images_detail(){
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

				$this->DATA->table = 'mt_gallery_detail';
				$a = $this->_save_master( 
					$data,
					array(
						'gallery_detail_id' => dbClean((int)$id)
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
			$this->db->update("mt_gallery",array("gallery_status"=>$val),array("gallery_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}

