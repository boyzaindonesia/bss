<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class product_tags extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete","detail"),"ITEM");
		$this->_set_title( 'Tagged' );
		$this->DATA->table="mt_product_tags";
		$this->folder_view = "product/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
				"title"		=> "Tagged",
				"url"		=> $this->own_link
			);
		$this->cat_search = array(
			''					=> 'All Search...',
			'product_tags_name'	=> 'Title',
		);

		$this->upload_path="./assets/collections/tag/";
		$this->upload_resize  = array(
			array('name'	=> 'large','width'	=> 450)
		);
		$this->image_size_str = "Size: Big:450px x 450px, Small: 450px x 310px";
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'product_tags',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'position',
			'order_dir' => 'ASC',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$this->db->order_by('position','asc');
		$data['data'] = $this->db->get_where("mt_product_tags",array(
			"product_tags_istrash" => 0
		))->result();

		foreach ($data['data'] as $k => $v) {
			if(get_count_product_tagged($v->product_tags_id) == 0 && $v->product_tags_status == 1){
				$this->db->update("mt_product_tags",array("product_tags_status"=>0,"position"=>99999),array("product_tags_id"=>$v->product_tags_id));
			} else if(get_count_product_tagged($v->product_tags_id) > 0 && $v->product_tags_status == 0){
				$this->db->update("mt_product_tags",array("product_tags_status"=>1),array("product_tags_id"=>$v->product_tags_id));
			}
		}

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
				'product_tags_id'	=> $id
			));
			if(empty($this->data_form->product_tags_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
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
				'product_tags_id'	=> $id
			));
			if(empty($this->data_form->product_tags_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
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
			$this->db->update("mt_product_tags",array("product_tags_istrash"=>1),array("product_tags_id"=>$id));

			// $this->_delte_old_files(
			// 	array(
			// 		'field' => 'product_tags_image',
			// 		'par'	=> array('product_tags_id' => $id)
			// ));
			// $this->DATA->_delete(array("product_tags_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_product_tags",array(
			"product_tags_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->product_tags_id;
			$this->_delte_old_files(
				array(
					'field' => 'product_tags_image',
					'par'	=> array('product_tags_id' => $id)
			));
			$this->DATA->_delete(array("product_tags_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		if (dbClean($_POST['product_tags_id']) == ""){
			$data = $this->db->order_by('position','asc')->get_where("mt_product_tags",array(
				"product_tags_istrash !="	=> 1
			))->result();
			$position = 1;
			foreach($data as $r){
				$id = $r->product_tags_id;
				$this->db->update("mt_product_tags",array("position"=>$position),array("product_tags_id"=>$id));
				$position +=1;
			}
		}

		$data = array(
			'product_tags_name'			=> dbClean(ucwords($_POST['product_tags_name'])),
			'product_tags_desc'			=> dbClean($_POST['product_tags_desc']),
			'product_tags_status'		=> dbClean($_POST['product_tags_status'])
		);

		if (dbClean($_POST['product_tags_id']) == "") {
			$data['product_tags_date'] = timestamp();
		}

		if(isset($_POST['url'])&&$_POST['url']!=""){
			$data['url'] = dbClean($_POST['url']);
		} else {
			$title = dbClean($_POST['product_tags_name']);
			if($title==''){ $title = 'product_tags'; }
			$data['url'] = generateUniqueURL($title,"mt_product_tags");
		}

		$a = $this->_save_master(
			$data,
			array(
				'product_tags_id' => dbClean($_POST['product_tags_id'])
			),
			dbClean($_POST['product_tags_id'])
		);

		$id = $a['id'];
		if(dbClean($_POST['remove_images']) == 1){
			$this->_delte_old_files(
				array(
					'field' => 'product_tags_image',
					'par'	=> array('product_tags_id' => $id)
					));

			$this->db->update("mt_product_tags",array("product_tags_image"=>NULL),array("product_tags_id"=>$id));
		} else {
			$this->_uploaded(
				array(
					'id'		=> $id ,
					'input'		=> 'product_tags_image',
					'param'		=> array(
						'field' => 'product_tags_image',
						'par'	=> array('product_tags_id' => $id)
						)
					));
		}

		redirect($this->own_link."/detail/".$id.'-'.changeEnUrl($_POST['product_tags_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function detail($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "Detail"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'product_tags_id'	=> $id
			));
			if(empty($this->data_form->product_tags_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}
			$this->_set_title( 'Tag "'.$this->data_form->product_tags_name.'"' );

			$this->_v($this->folder_view.$this->prefix_view."_detail",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function delete_detail($id=''){
		$id = explode("-", $id);
		$idTag = dbClean(trim($id[0]));
		$idProduk = dbClean(trim($id[1]));
		if(trim($idTag) != '' && trim($idProduk) != ''){
			$d = $this->db->get_where("mt_product",array(
				'product_id' => $idProduk
			))->row();
			if(count($d)>0){
				$product_id   = $d->product_id;
				$product_tags = $d->product_tags;

				$i = 0; $arr = NULL;
				$exp = explode(",", $product_tags);
				foreach ($exp as $key) {
					if($idTag != $key){ $arr .= ($i==0?'':',').$key; $i += 1; }
				}

				$this->db->update("mt_product",array("product_tags"=>$arr),array("product_id"=>$product_id));

				redirect($this->own_link."/detail/".$idTag."-".changeEnUrl(get_detail_product_tags($idTag)->product_tags_name)."?msg=".urlencode('Delete data success')."&type_msg=success");
			}
		}
	}
	function save_detail(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
			$id = '';
			$exp = '';
			$data['data'] = array();
			if(isset($_POST['thisId']) && $_POST['thisId'] !=''){
				$idTag = $_POST['thisIdTag'];
				$id = $_POST['thisId'];
				$detail_product = get_detail_product($id);
				if(count($detail_product) > 0){
					$product_id = $detail_product->product_id;
					$product_tags = $detail_product->product_tags;
					$i = 0; $arr = $idTag;
					if($product_tags != ""){
						$exp = explode(",", $product_tags);
						$i = 1;
						foreach ($exp as $key) {
							if($idTag != $key){ $arr .= ($i==0?'':',').$key; $i += 1; }
						}
					}

					$this->db->update("mt_product",array("product_tags"=>$arr),array("product_id"=>$product_id));
				}
			}

			$data['err'] = false;
			$data['msg'] = 'Sukses menambahkan produk.';
		}

		die(json_encode($data));
		exit();
	}
	function save_multi_detail(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
			$id = '';
			$exp = '';
			$data['data'] = array();
			if(isset($_POST['thisId']) && $_POST['thisId'] !=''){

				$this->DATA->table="mt_product";
				$idTag = $_POST['thisIdTag'];

				$id  = $_POST['thisId'];
				$exp = explode("-", $id);
				foreach ($exp as $key) {
					$detail_product = get_detail_product($key);
					if(count($detail_product) > 0){
						$product_id = $detail_product->product_id;
						$product_tags = $detail_product->product_tags;
						$i = 0; $arr = $idTag;
						if($product_tags != ""){
							$exp = explode(",", $product_tags);
							$i = 1;
							foreach ($exp as $key) {
								if($idTag != $key){ $arr .= ($i==0?'':',').$key; $i += 1; }
							}
						}

						$this->db->update("mt_product",array("product_tags"=>$arr),array("product_id"=>$product_id));
					}
				}
			}

			$data['err'] = false;
			$data['msg'] = 'Sukses menambahkan produk.';
		}

		die(json_encode($data));
		exit();
	}

	function get_table_add_products($id=""){
		$msg     = '';
		$content = '';
		$dataProduk = get_table_products();
	    if(count($dataProduk) > 0){
			foreach($dataProduk as $r){
			$isFound = false;
			$chkdisabled = "";
			$product_tags = $r->product_tags;
			$exp = explode(",", $product_tags);
			foreach ($exp as $key) {
				if($id == $key){
					$chkdisabled="disabled checked";
					$isFound = true;
				}
			}

			$content .= '<tr class="'.($isFound?' selected':'').'">
				<td class="nobr text-center">
					'.($isFound?
					'<i class="fa fa-check-square" aria-hidden="true"></i>':
                    '<input type="checkbox" name="checked_files[]" data-id="'.$r->product_id.'" value="'.$r->product_id.'" >'
                	).'
                </td>
			    <td class="nobr text-center">'.$r->product_id.'.</td>
			    <td>
                    <img src="'.get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id)).'" class="avatar">
                </td>
			    <td>'.$r->product_name.($isFound?'<br/><small class="text-danger">(Sudah Ditambahkan)</small>':'').'</td>
			    <td class="nobr">'.get_product_category_name($r->product_category_id).'</td>
			    <td class="nobr text-center">'.($r->product_show_id=='1'?'<span class="label label-success">Tampil</span>':'<span class="label label-danger">Tidak Tampil</span>').'</td>
			    <td class="nobr text-center">
				    '.($isFound?
					'<div class="btn btn-default btn-xs btn-disabled" disabled>Select</div>':
					'<div class="btn btn-danger btn-xs btn-save" data-id="'.$r->product_id.'">Select</div>'
                	).'

			    </td>
			</tr>';
			}
			$msg = 'success';
		}

		$return = array('msg' => $msg,'content' => $content);
		die(json_encode($return));
		exit();
	}

	function change_position(){
		if ($_POST) {
			$temp_position = $_SERVER['REMOTE_ADDR'];
			$ids    = $_POST["ids"];
			for ($idx = 0; $idx < count($ids); $idx+=1) {
				$id = $ids[$idx];
				//...
				$data = array(
					'position'		=> dbClean($idx),
					'temp_position'	=> dbClean($temp_position),
				);

				$a = $this->_save_master(
					$data,
					array(
						'product_tags_id' => dbClean((int)$id)
					),
					dbClean((int)$id)
				);
			}
			return;
		}
	}

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_product_tags",array("product_tags_status"=>$val),array("product_tags_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
