<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {


	function __construct(){  

		parent::__construct(); 		  
		
		$this->jCfg['theme'] = 'admin/template';
		$this->_releaseSession();  
	}

	public function index()
	{
		debugCode($this->jCfg);
		getView("login",array());
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */