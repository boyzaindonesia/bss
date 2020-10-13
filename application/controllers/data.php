<?php
include_once(APPPATH."libraries/AdminController.php");
class Data extends AdminController {

	function __construct()  
	{
		parent::__construct(); 		
	}

	function get_duedate_vendor(){
		$location = isset($_POST['location'])?$_POST['location']:'L';
		$tanggal = isset($_POST['tanggal'])?$_POST['tanggal']:date("Y-m-d");
		$m = get_duedate_vendor(array(
				"location" 	=> $location,
				"tanggal"	=> $tanggal		
		));
		die($m);
	}

	function get_card_type(){
		$number = isset($_POST['card_no'])?$_POST['card_no']:'';
		$type = isset($_POST['type'])?$_POST['type']:'rr';
		$m = get_card_type($number,$type);
		die($m);
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */