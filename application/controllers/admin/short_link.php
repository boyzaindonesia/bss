<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Short_link extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Short Link' );
		$this->DATA->table="mt_short_link";
		$this->folder_view = "content/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
			"title"		=> "Short Link",
			"url"		=> $this->own_link
		);

		$this->short_url = "https://link.butiksasha.com/";
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$this->db->order_by('short_link_date','desc');
		$data['data'] = $this->db->get_where("mt_short_link",array(
			"short_link_istrash" => 0
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
				'short_link_id'	=> $id
			));
			if(empty($this->data_form->short_link_id)){
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
				'short_link_id'	=> $id
			));
			if(empty($this->data_form->short_link_id)){
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
			$this->db->update("mt_short_link",array("short_link_istrash"=>1),array("short_link_id"=>$id));
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_short_link",array(
			"short_link_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->short_link_id;
			$this->DATA->_delete(array("short_link_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		if (dbClean($_POST['short_link_name']) != ""){

			$m = $this->db->get_where("mt_short_link",array(
				"short_link_code"	=> dbClean($_POST['short_link_code'])
			),1,0)->row();
			if(count($m) == 0){
				$data = array(
					'short_link_name'				=> dbClean($_POST['short_link_name']),
					'short_link_code'				=> dbClean($_POST['short_link_code']),
					'short_link_url'				=> dbClean($_POST['short_link_url'])
				);

				if (dbClean($_POST['short_link_id']) == "") {
					$data['short_link_view'] = 0;
					$data['short_link_istrash'] = 0;
					$data['short_link_date'] = timestamp();
				}

				$a = $this->_save_master(
					$data,
					array(
						'short_link_id' => dbClean($_POST['short_link_id'])
					),
					dbClean($_POST['short_link_id'])
				);

				redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['short_link_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
			} else {
				redirect($this->own_link."/add?msg=".urlencode('Kode sudah ada.')."&type_msg=error");
			}
		} else {
			redirect($this->own_link."?msg=".urlencode('Kolom harus diisi.')."&type_msg=error");
		}
	}

}
