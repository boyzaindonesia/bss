<?php
include_once(APPPATH."libraries/FrontController.php");
class Site extends FrontController {

	function __construct()  
	{
		parent::__construct(); 	
	}
	
	function index()  
	{
		$data = array();
		$this->_v('index',$data);
	}

	
	// function not_found()  
	// {
	// 	$this->page     = 'ERROR 404 - PAGE NOT FOUND';
	// 	$this->cur_menu = '404';
	// 	$this->header_type = '1';
	// 	$this->footer_type = '1';
	// 	$this->url_back    = '';

	// 	$data = '';
	// 	$data['menu'] = "404";
	// 	$data['menu_id'] = "";

	// 	$this->_v('404',$data);
	// }

	
}
