<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class product_by_group extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		// $this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Produk berdasarkan group' );
		$this->DATA->table = "mt_product";
		$this->folder_view = "product/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_product","M");
		$this->breadcrumb[] = array(
			"title"		=> "Produk berdasarkan group",
			"url"		=> $this->own_link
		);

		$this->cat_search = array(
			''						=> 'Semua Pencarian...',
			'product_group_name'	=> 'Judul'
		);

        $this->user_id  = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id = get_user_store($this->user_id);
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'product_by_group',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => NULL,
			'order_dir' => NUll,
			'filter' 	=> '',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'product_by_group'){
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

		$par_filter = array(
            "product_group_id"    => NULL,
            "store_id"            => $this->store_id,
            "reseller_id"         => NULL,
            "product_category_id" => NULL,
            "product_status_id"   => $this->jCfg['search']['product_status_id'],
            "product_group_show"  => "1",
            "type_result"         => "list_desktop",
            "date_start"          => $this->jCfg['search']['date_start'],
            "date_end"            => $this->jCfg['search']['date_end'],
            "order_by"            => NULL,
            "order_dir"           => NULL,
            "offset"              => 0,
            "limit"               => NULL,
            "keyword"             => $this->jCfg['search']['keyword'],
            "colum"               => $this->jCfg['search']['colum'],
            "param"               => $this->cat_search
        );

		$data = $this->M->data_product_by_group($par_filter);

		$data['url'] = base_url()."admin/product_list";
        $data['url_parent'] = base_url()."admin/product";

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

}
