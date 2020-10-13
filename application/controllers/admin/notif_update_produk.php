<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class notif_update_produk extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","delete"),"ITEM");
		$this->_set_title( 'Notif Update Produk' );
		$this->DATA->table = "mt_product_notif";
		$this->folder_view = "notification/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_notification","M");
		$this->breadcrumb[] = array(
			"title"		=> "Notif Update Produk",
			"url"		=> $this->own_link
		);

		$this->cat_search = array(
			''										=> 'Semua Pencarian...',
			'mt_product.product_name'				=> 'Judul',
			'mt_product_notif.notif_title'			=> 'Title'
		);

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'notif_update_produk',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_product_notif.product_id',
			'order_dir' => 'desc',
			'filter' 	=> '100',
			'colum'		=> '',
			'keyword'	=> '',
			'notif_status'	=> '1'
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'notif_update_produk'){
			$this->_reset();
		}

		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
			if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
				$this->jCfg['search']['date_start'] = $this->input->post('date_start');

			if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
				$this->jCfg['search']['date_end'] = $this->input->post('date_end');

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

        if(!isset($this->jCfg['search']['notif_status'])||$this->jCfg['search']['notif_status']==''){
            $this->jCfg['search']['notif_status'] = "";
            $this->_releaseSession();
        }
        if(isset($_POST['notif_status'])){
            if($_POST['notif_status'] == ""){
                $this->jCfg['search']['notif_status'] = "";
            } else {
                $this->jCfg['search']['notif_status'] = $_POST['notif_status'];
            }
            $this->_releaseSession();
        }
        $this->notif_status = $this->jCfg['search']['notif_status'];

		$this->per_page = $this->jCfg['search']['filter'];
		$par_filter = array(
            "store_id"      => $this->store_id,
			"notif_status"	=> $this->notif_status,
			"offset"		=> $this->uri->segment($this->uri_segment),
			"limit"			=> $this->per_page,
			"param"			=> $this->cat_search
		);
		$this->data_table = $this->M->data_notif_product($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		$data['url'] = base_url()."admin/notif_update_produk";

		// $user_id 	  		  = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
		// $store_id     		  = get_user_store($user_id);
		// $data['store_id'] 	  = $store_id;
		// $data['detail_store'] = get_detail_store($store_id);

		// if($this->jCfg['search']['product_status_id'] != 1){
		// 	$this->_set_title( 'List Produk "'.get_name_product_status($this->jCfg['search']['product_status_id']).'"' );
		// }

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function view($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'notif_id'	=> $id
			));
			if(empty($this->data_form->notif_id)){
				redirect($this->own_link."?msg=".urlencode('Data Notif tidak ditemukan')."&type_msg=error");
			}

			$notif_id = $this->data_form->notif_id;
			$this->db->update("mt_product_notif",array("notif_notify"=>0),array("notif_id"=>$notif_id));

			$this->_v($this->folder_view.$this->prefix_view."_view");
		}else{
			redirect($this->own_link);
		}
	}

	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			$this->db->update("mt_product_notif",array("notif_istrash"=>1, "notif_notify"=>0),array("notif_id"=>$id));
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_product_notif",array(
			"notif_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->notif_id;
			$this->DATA->_delete(array("notif_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save_notif_status(){
		$data = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		if(isset($_POST['notif_id']) && $_POST['notif_id'] != ''){
			$notif_id = dbClean(trim($_POST['notif_id']));
			if(trim($notif_id)!=''){

				$dataNotif = $this->db->get_where("mt_product_notif",array(
					'notif_id'	=> $notif_id
				),1,0)->row();
				if(count($dataNotif) > 0){
					$this->db->update("mt_product_notif",array("notif_status"=>2, "notif_notify"=>0),array("notif_id"=>$notif_id));
					$data['err'] 	= false;
					$data['msg'] 	= 'Berhasil simpan data...';
				} else {
					$data['err'] 	= true;
					$data['msg'] 	= 'Data notif tidak ditemukan...';
				}
			} else {
				$data['err'] 	= true;
				$data['msg'] 	= 'Data notif tidak ditemukan...';
			}
		}
		redirect($this->own_link."?msg=".urlencode($data['msg'])."&type_msg=".($data['err']?'error':'success')."");
	}

	function save_multi_notif_status(){
		$data = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
			$checked_files = $_POST['checked_files'];
			foreach ($checked_files as $k => $v) {
				$notif_id = $v;
				$dataNotif = $this->db->get_where("mt_product_notif",array(
					'notif_id'	=> $notif_id
				),1,0)->row();
				if(count($dataNotif) > 0){
					$this->db->update("mt_product_notif",array("notif_status"=>2, "notif_notify"=>0),array("notif_id"=>$notif_id));
				}
			}

			$data['err'] 	= false;
			$data['msg'] 	= 'Berhasil simpan data...';
		}
		redirect($this->own_link."?msg=".urlencode($data['msg'])."&type_msg=".($data['err']?'error':'success')."");
	}

	function get_notif_status(){
		$data = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		$data['count'] 	= 0;
		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'get_count'){
			$dataNotif = $this->db->get_where("mt_product_notif", array(
				'store_id'		=> $this->store_id,
				'notif_status'	=> 1,
				'notif_istrash'	=> 0
			))->result();
			if(count($dataNotif) > 0){
				$data['count'] 	= count($dataNotif);
			}
		}

		die(json_encode($data));
		exit();
	}

}