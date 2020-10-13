<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Menus extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'List Menu' );
		$this->DATA->table="mt_menus";
		$this->folder_view = "menus/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
			"title"		=> "Menu",
			"url"		=> $this->own_link
		);
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$this->db->order_by('position','asc');
		$data['data'] = $this->db->get_where("mt_menus",array(
			"menus_istrash" => 0
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
				'menus_id'	=> $id
			));
			if(empty($this->data_form->menus_id)){
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
				'menus_id'	=> $id
			));
			if(empty($this->data_form->menus_id)){
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
			$this->db->update("mt_menus",array("menus_istrash"=>1),array("menus_id"=>$id));
		}
		redirect($this->own_link."?msg=".urlencode('Delete data menus success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_menus",array(
			"menus_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->menus_id;
			$this->db->delete("mt_menus",array('menus_id' => $id));
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){

		// if (dbClean($_POST['menus_id']) == ""){
			// $data = $this->db->order_by('position','asc')->get_where("mt_menus",array(
			// 	"menus_istrash !="	=> 1
			// ))->result();
			// $position = 1;
			// foreach($data as $r){
			// 	$id = $r->menus_id;
			// 	$this->db->update("mt_menus",array("position"=>$position),array("menus_id"=>$id));
			// 	$position +=1;
			// }
		// }
		$data = array(
			'menus_title'				=> dbClean(ucwords($_POST['menus_title'])),
			'menus_status'				=> dbClean($_POST['menus_status']),
			'menus_parent_id'			=> dbClean($_POST['menus_parent_id'])
		);

		$data['link_type']  = '';
		$data['link_value'] = '';
		$link_type = dbClean($_POST['link_type']);
		if($link_type != ''){
			$data['link_type'] = $link_type;
			$arr = array(1,3,4,5);
			if(in_array($link_type, $arr)){
				$tmpVal = explode(".",$_POST['link_value'][$link_type]);
				$data['link_value'] = $tmpVal[0];
			} else {
				$data['link_value'] = $_POST['link_value'][$link_type];
			}
		}

		if (dbClean($_POST['menus_id']) == "") {
			$data['menus_date'] = timestamp();

			$title = dbClean($_POST['menus_title']);
			if($title==''){ $title = 'menu'; }
			$data['url'] = generateUniqueURL($title,"mt_menus");

			$position = $this->db->select_max('position')->get_where("mt_menus",array(
				"menus_istrash !="	=> 1
			))->row();
			$data['position'] = $position->position + 1;
		} else {
			$v = $this->db->get_where("mt_menus",array(
				"menus_id"	=> $_POST['menus_id']
			),1,0)->row();
			if($v->menus_title != $data['menus_title']){
				$data['url'] = generateUniqueURL($data['menus_title'],"mt_menus");
			}
		}

		$a = $this->_save_master(
			$data,
			array(
				'menus_id' => dbClean($_POST['menus_id'])
			),
			dbClean($_POST['menus_id'])
		);

		$id = $a['id'];

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['menus_title'])."?msg=".urlencode('Save data category success')."&type_msg=success");
	}

	function change_position(){
		if ($_POST) {
			$temp_position = $_SERVER['REMOTE_ADDR'];
			$ids    = $_POST["ids"];
			for ($idx = 0; $idx < count($ids); $idx+=1) {
				$id = $ids[$idx];
				$ordinal = $idx;
				//...
				$data = array(
					'position'		=> dbClean($idx),
					'temp_position'	=> dbClean($temp_position),
				);

				$a = $this->_save_master(
					$data,
					array(
						'menus_id' => dbClean((int)$id)
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
			$this->db->update("mt_menus",array("menus_status"=>$val),array("menus_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
