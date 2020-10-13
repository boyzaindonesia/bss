<?php
include_once(APPPATH."libraries/FrontController.php");
class print_address extends FrontController {
	var $cur_menu = '';

	function __construct()
	{
		parent::__construct();

		$this->DATA->table="mt_orders";

	}

	function index(){
		$data = array();

		$id = '';
		$exp = '';
		$data['data'] = array();
		if(isset($_GET['id']) && $_GET['id']!=''){
			$id = $_GET['id'];
			$exp = explode("-", $id);
			foreach ($exp as $key) {
				if(isset($_GET['from']) && $_GET['from']=='print_address'){
					$print_orders = get_print_address_shipping_name($key);
					$this->DATA->table="mt_print_address";
					$data['data'][] = $this->DATA->data_id(array(
						'print_address_id'	=> $key
					));
					$this->db->update("mt_print_address",array("print_address_status"=>1),array("print_address_id"=>$key));
					// debugCode($data);
				} else {
					$data['data'][] = get_print_orders_shipping_name($key);
					$this->db->update("mt_orders",array("orders_print"=>1),array("orders_id"=>$key));
				}
			}
		} else if(isset($_POST['thisAction']) && $_POST['thisAction']=='save'){
			foreach ($_POST['id'] as $key){
				$data['data'][] = get_print_orders_shipping_name($key);
				$this->db->update("mt_orders",array("orders_print"=>1),array("orders_id"=>$key));
			}
		}

		$version = (isset($_GET['version']) && $_GET['version'] != ''?$_GET['version']:'1');
		switch ($version) {
			case '1':
				$this->load->view('admin/template/report/print_address',$data);
				break;
			case '2':
				$this->load->view('admin/template/report/print_address_v2',$data);
				break;

			default: break;
		}

	}

	function print_again(){
		$data = array();

		$id = '';
		$exp = '';
		$data['data'] = array();
		if(isset($_GET['id']) && $_GET['id']!=''){
			$id = $_GET['id'];
			$exp = explode("-", $id);
			foreach ($exp as $key) {
				if(isset($_GET['from']) && $_GET['from']=='print_address'){
					$print = $this->db->get_where("mt_print_address",array(
						'print_address_id'	=> $key
					),1,0)->row();
					if(count($print) > 0){
						$this->db->update("mt_print_address",array("print_address_status"=>0),array("print_address_id"=>$key));
					}
				} else {
					$orders = $this->db->get_where("mt_orders",array(
						'orders_id'	=> $key
					),1,0)->row();
					if(count($orders) > 0){
						$this->db->update("mt_orders",array("orders_print"=>0),array("orders_id"=>$key));
					}
				}
			}
			if(isset($_GET['from']) && $_GET['from']=='print_address'){
				redirect(base_url()."admin/print_address/?msg=".urlencode("Berhasil ubah status print...")."&type_msg=success");
			} else {
				redirect(base_url()."admin/marketplace/new_orders?msg=".urlencode("Berhasil ubah status print...")."&type_msg=success");
			}
		}
	}

	function print_booking(){
		$data = array();

		$id = '';
		$exp = '';
		$data['data'] = array();
		if(isset($_GET['id']) && $_GET['id']!=''){
			$id = $_GET['id'];
			$exp = explode("-", $id);
			foreach ($exp as $key) {
				if(isset($_GET['from']) && $_GET['from']=='print_address'){
					$print_orders = get_print_address_shipping_name($key);
					$this->DATA->table="mt_print_address";
					$data['data'][] = $this->DATA->data_id(array(
						'print_address_id'	=> $key
					));
				} else {
					$data['data'][] = get_print_orders_shipping_name($key);
				}
			}
		} else if(isset($_POST['thisAction']) && $_POST['thisAction']=='save'){
			foreach ($_POST['id'] as $key){
				$data['data'][] = get_print_orders_shipping_name($key);
				// $this->db->update("mt_orders",array("orders_print"=>1),array("orders_id"=>$key));
			}
		}

		$this->load->view('admin/template/report/print_address_booking',$data);
	}

	function print_simple(){
		$data = array();

		$id = '';
		$exp = '';
		$data['data'] = array();
		if(isset($_GET['id']) && $_GET['id']!=''){
			$id = $_GET['id'];
			$exp = explode("-", $id);
			foreach ($exp as $key) {
				if(isset($_GET['from']) && $_GET['from']=='print_address'){
					$print_orders = get_print_address_shipping_name($key);
					$this->DATA->table="mt_print_address";
					$data['data'][] = $this->DATA->data_id(array(
						'print_address_id'	=> $key
					));
				} else {
					$data['data'][] = get_print_orders_shipping_name($key);
				}
			}
		} else if(isset($_POST['thisAction']) && $_POST['thisAction']=='save'){
			foreach ($_POST['id'] as $key){
				$data['data'][] = get_print_orders_shipping_name($key);
				// $this->db->update("mt_orders",array("orders_print"=>1),array("orders_id"=>$key));
			}
		}

		$this->load->view('admin/template/report/print_address_simple',$data);
	}

}
