<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class Article extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'List Artikel' );
		$this->DATA->table = "mt_article";
		$this->folder_view = "content/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_article","M");
		$this->breadcrumb[] = array(
			"title"		=> "List Artikel",
			"url"		=> $this->own_link
		);
		$this->cat_search = array(
			''										=> 'Semua Pencarian...',
			'mt_article.article_title'				=> 'Judul',
			'mt_article_category.category_title'	=> 'Kategori',
		);
		$this->upload_path="./assets/collections/article/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 450, 'quality'	=> '90%'),
			array('name'	=> 'small','width'	=> 720, 'quality'	=> '90%'),
			array('name'	=> 'large','width'	=> 1200, 'quality'	=> '90%')
		);
		$this->image_size_str = "Maks 1200px";

		$this->user_id	= isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'article',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_article.article_publishdate',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'article'){
			$this->_reset();
		}

		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
			if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
				$this->jCfg['search']['date_start'] = convDatepickerDec($this->input->post('date_start'));

			if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
				$this->jCfg['search']['date_end'] = convDatepickerDec($this->input->post('date_end'));

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

		$this->per_page = $this->jCfg['search']['filter'];
		$par_filter = array(
			"offset"	=> $this->uri->segment($this->uri_segment),
			"limit"		=> $this->per_page,
			"param"		=> $this->cat_search
		);
		$this->data_table = $this->M->data_article($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		$data['url'] = base_url()."admin/article/index";

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function add(){
		$this->breadcrumb[] = array(
			"title"		=> "Add"
		);
		$this->_v($this->folder_view.$this->prefix_view."_form");
	}

	function view($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'article_id'	=> $id
			));
			if(empty($this->data_form->article_id)){
				redirect($this->own_link."?msg=".urlencode('Data artikel tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_view");
		}else{
			redirect($this->own_link);
		}
	}

	function edit($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "Edit"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'article_id'	=> $id
			));
			if(empty($this->data_form->article_id)){
				redirect($this->own_link."?msg=".urlencode('Data artikel tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_form");
		}else{
			redirect($this->own_link);
		}
	}

	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			$this->_delte_old_files(
				array(
					'field' => 'article_image',
					'par'	=> array('article_id' => $id)
			));
			$this->DATA->_delete(array("article_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_article",array(
			"article_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->article_id;
			$this->_delte_old_files(
				array(
					'field' => 'article_image',
					'par'	=> array('article_id' => $id)
			));
			$this->DATA->_delete(array("article_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'article_publishdate'		=> dbClean(convDatepickerDec($_POST['pdate'])).' '.dbClean(convTimepickerDec($_POST['phour'])).':00',
			'article_user_id'			=> dbClean(ucwords($_POST['article_user_id'])),
			'article_title'				=> dbClean(ucwords($_POST['article_title'])),
			'article_lead'				=> dbClean($_POST['article_lead']),
			'article_content'			=> dbClean($_POST['article_content']),
			'article_category_id'		=> dbClean($_POST['article_category_id']),
			'article_video'				=> dbClean($_POST['article_video']),
			'article_meta_description'	=> dbClean($_POST['article_meta_description']),
			'article_meta_keywords'		=> dbClean($_POST['article_meta_keywords']),
			'article_status'			=> dbClean($_POST['article_status']),
		);

		if (dbClean($_POST['article_id']) == "") {
			$data['article_date']    = timestamp();

			$title = dbClean($_POST['article_title']);
			if($title==''){ $title = 'artikel'; }
			$data['url'] = generateUniqueURL($title,"mt_article");
		}

		$hashtags = dbClean($_POST['hashtags']);
		$hashtags = preg_replace('/\s+/', '', $hashtags);
		$expTags  = explode("#", $hashtags);
		$arrTags  = "";
		$iTags = 0;
		foreach ($expTags as $n) {
			if($n != ""){
				$arrTags .= ($iTags==0?"":",").$n;
				$iTags += 1;
			}
		}
		$data['article_tags'] = $arrTags;

		$a = $this->_save_master(
			$data,
			array(
				'article_id' => dbClean($_POST['article_id'])
			),
			dbClean($_POST['article_id'])
		);

		$id = $a['id'];
		if(dbClean($_POST['remove_images']) == 1){
			$this->_delte_old_files(
			array(
				'field' => 'article_image',
				'par'	=> array('article_id' => $id)
			));

			$this->db->update("mt_article",array("article_image"=>NULL),array("article_id"=>$id));
		} else {
			$this->_uploaded(
			array(
				'id'		=> $id ,
				'input'		=> 'article_image',
				'param'		=> array(
								'field' => 'article_image',
								'par'	=> array('article_id' => $id)
							)
			));
		}

		$this->upload_path="./assets/collections/doc/";
		$this->upload_types = 'file';
		$this->_uploaded(
		array(
			'id'		=> $id ,
			'input'		=> 'files',
			'param'		=> array(
							'field' => 'files',
							'par'	=> array('article_id' => $id)
						)
		));

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['article_title'])."?msg=".urlencode('Save data article success')."&type_msg=success");
	}

	function category($id) {
		$tmp    = '';
		$data   = $this->M->get_category_by_section($id);
		if(!empty($data)) {
		    $tmp .= "<option value=''>Select Category</option>";
		    foreach($data as $row){
				 $tmp .= "<option value='".$row->category_id."'>".$row->category_title."</option>";
		    }
		} else {
		    $tmp .= "<option value=''>Select Category</option>";
		}
		die($tmp);
    }

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_article",array("article_status"=>$val),array("article_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
