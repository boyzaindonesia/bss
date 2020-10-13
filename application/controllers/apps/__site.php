<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class Site extends FrontController {
	var $cur_menu = '';
	
	function __construct()  
	{
		parent::__construct(); 

		$this->DATA->table = "mt_orders";
		$this->load->model("mdl_orders","M");

		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
	        header('Access-Control-Max-Age: 86400');    // cache for 1 day
	    }

	    // Access-Control headers are received during OPTIONS requests
	    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
	    		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
	    		header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	    	exit(0);
	    }
	}

	function index(){
		
	}

	function get_info(){
		$error 	= true;
	    $msg 	= '';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'getTopInfo'){
				$m = $this->db->order_by('orders_date','desc')->get_where("mt_orders",array(
					"orders_istrash"	=> '0'
				),1,0)->row();
				if(!empty($m)){
					$rows['topInfo']  = 'Terakhir update '.convDateTimeTable($m->orders_date);
				}
			}
		}
		
		die(json_encode($rows));
		exit();
	}

	function get_orders_status(){
		$error 	= true;
	    $msg 	= '';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'getdata'){
				$rows['ordersStatus'] = get_orders_status($request->thisVal);
			}
		}
		
		die(json_encode($rows));
		exit();
	}
	
	function get_orders_source(){
		$error 	= true;
	    $msg 	= '';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'getdata'){
				$rows['ordersSource'] = get_orders_source($request->thisVal);
			}
		}
		
		die(json_encode($rows));
		exit();
	}
	
	function get_orders_courier(){
		$error 	= true;
	    $msg 	= '';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'getdata'){
				$rows['ordersCourier'] = get_orders_shipping_courier($request->thisVal);
			}
		}
		
		die(json_encode($rows));
		exit();
	}

	// function get_print_orders_shipping_name(){
	// 	$error 	= true;
	//     $msg 	= '';
	// 	$rows 	= array();
	//     $postdata = file_get_contents("php://input");
	//     if (isset($postdata)) {
	//     	$request 	= json_decode($postdata);
	// 		$thisAction = mysql_real_escape_string($request->thisAction);
	// 		if($thisAction == 'getdata'){
	// 			$rows['printOrdersShippingName'] = get_print_orders_shipping_name($request->thisVal);
	// 		}
	// 	}
		
	// 	die(json_encode($rows));
	// 	exit();
	// }
	function get_print_orders_shipping_name(){
	    $error 	= true;
	    $msg 	= '';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'getdata'){
				$par_filter = array();
				foreach ($request as $key => $value) {
					$par_filter[$key] = $value;
				}
				$this->data_table = $this->M->data_temp_print_address($par_filter);
				$rows['printOrdersShippingName']  = $this->data_table;
			}
		}
		
		die(json_encode($rows));
		exit();
	}
	
}
