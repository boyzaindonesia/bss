<?php
class FrontController extends My_Controller{
	var $meta_keyword 		= "";
	var $meta_title 		= "Home";
	var $meta_desc 			= "";

	function __construct(){
		parent::__construct();
		$m = $this->db->get("mt_template")->row();
		$this->jCfg['theme'] = 'front/'.$m->template_name;
		$this->_releaseSession();
	}

	function _v($file,$data=array(),$single=true){
		$data["meta_desc"]		= $this->meta_desc;
		$data["meta_title"]		= $this->meta_title;
		$data["meta_keyword"] 	= $this->meta_keyword;

		if(!$single)
			$this->load->view($this->jCfg['theme'].'/header',$data);

		$this->load->view($this->jCfg['theme'].'/'.$file,$data);

		if(!$single)
			$this->load->view($this->jCfg['theme'].'/footer',$data);
	}

	function _save_master($data=array(),$par=array(),$vid=0){
		$id 	= 0;
		$act	= FALSE;
		$o = $this->DATA->_cek($par);
		if( $o == 0 ){
			$act = $this->DATA->_add($data);
			$id = $this->db->insert_id();
		}else{
			$act = $this->DATA->_update($par,$data);
			$id = $vid;
		}
		return array(
			'id'	=> $id,
			'msg'	=> ($act)?'Success...':'Fail...'
		);
	}
}