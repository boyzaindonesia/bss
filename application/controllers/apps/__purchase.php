<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class purchase extends FrontController {
	var $cur_menu = '';

	function __construct()  
	{
		parent::__construct(); 

		$this->DATA->table = "mt_purchase";
		$this->load->model("mdl_purchase","M");

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
				$this->data_table = $this->M->data_purchase_front($par_filter);
				$rows['purchase']  = $this->data_table;
			}
		}
		
		die(json_encode($rows));
		exit();
	}

}
