<?php
class AppsController extends My_Controller{

	function __construct(){
		parent::__construct();
		$this->jCfg['theme'] = "apps/";
		$this->_releaseSession();
	}

	function _v($file,$data=array(),$single=true){
        if(!$single)
            $this->load->view($this->jCfg['theme'].'/header',$data);

        $this->load->view($this->jCfg['theme'].'/'.$file,$data);

        if(!$single)
            $this->load->view($this->jCfg['theme'].'/footer',$data);
	}
}