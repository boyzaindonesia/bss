<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class product_group extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete","detail"),"ITEM");
		$this->_set_title( 'Group' );
		$this->DATA->table="mt_product_group";
		$this->folder_view = "product/";
		$this->prefix_view = strtolower($this->_getClass());

		$this->breadcrumb[] = array(
				"title"		=> "Group",
				"url"		=> $this->own_link
			);
		$this->cat_search = array(
			''						=> 'All Search...',
			'product_group_name'	=> 'Title',
		);

		$this->user_id	= isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
		$this->store_id = get_user_store($this->user_id);
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'product_group',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'product_group_date',
			'order_dir' => 'DESC',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$this->db->order_by('product_group_date','DESC');
		$data['data'] = $this->db->get_where("mt_product_group",array(
			"product_group_istrash" => 0
		))->result();

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
				'product_group_id'	=> $id
			));
			if(empty($this->data_form->product_group_id)){
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
				'product_group_id'	=> $id
			));
			if(empty($this->data_form->product_group_id)){
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
			$this->db->update("mt_product_group",array("product_group_istrash"=>1),array("product_group_id"=>$id));

			// $this->_delte_old_files(
			// 	array(
			// 		'field' => 'product_group_image',
			// 		'par'	=> array('product_group_id' => $id)
			// ));
			// $this->DATA->_delete(array("product_group_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_product_group",array(
			"product_group_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->product_group_id;
			// $this->_delte_old_files(
			// 	array(
			// 		'field' => 'product_group_image',
			// 		'par'	=> array('product_group_id' => $id)
			// ));
			$this->DATA->_delete(array("product_group_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function push($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			$this->db->update("mt_product_group",array("product_group_date"=>timestamp()),array("product_group_id"=>$id));
		}
		redirect($this->own_link."?msg=".urlencode('Push product success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'store_id'				=> $this->store_id,
			'product_group_name'	=> dbClean($_POST['product_group_name']),
			'product_group_show'	=> dbClean($_POST['product_group_show'])
		);

		if (dbClean($_POST['product_group_id']) == "") {
			$data['product_group_date'] = timestamp();
		}

		$a = $this->_save_master(
			$data,
			array(
				'product_group_id' => dbClean($_POST['product_group_id'])
			),
			dbClean($_POST['product_group_id'])
		);

		redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['product_group_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function detail($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "Detail"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'product_group_id'	=> $id
			));
			if(empty($this->data_form->product_group_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}
			$this->_set_title( 'Group "'.$this->data_form->product_group_name.'"' );

			$this->_v($this->folder_view.$this->prefix_view."_detail",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function delete_detail($id=''){
		$id = explode("-", $id);
		$idGroup  = dbClean(trim($id[0]));
		$idProduk = dbClean(trim($id[1]));
		if(trim($idGroup) != '' && trim($idProduk) != ''){
			$d = $this->db->get_where("mt_product",array(
				'product_id' => $idProduk
			))->row();
			if(count($d)>0){
				$product_id = $d->product_id;
				$this->db->update("mt_product",array("product_group_id"=>0),array("product_id"=>$product_id));

				redirect($this->own_link."/detail/".$idGroup."-".changeEnUrl(get_product_group_name($idGroup))."?msg=".urlencode('Delete data success')."&type_msg=success");
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
				$idGroup = $_POST['thisIdGroup'];
				$id      = $_POST['thisId'];
				$detail_product = get_detail_product($id);
				if(count($detail_product) > 0){
					$product_id = $detail_product->product_id;
					$this->db->update("mt_product",array("product_group_id"=>$idGroup),array("product_id"=>$product_id));
				}
			}

	        check_product_by_group($this->store_id);
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
				$idGroup = $_POST['thisIdGroup'];

				$id  = $_POST['thisId'];
				$exp = explode("-", $id);
				foreach ($exp as $key) {
					$detail_product = get_detail_product($key);
					if(count($detail_product) > 0){
						$product_id = $detail_product->product_id;
						$this->db->update("mt_product",array("product_group_id"=>$idGroup),array("product_id"=>$product_id));
					}
				}
			}

	        check_product_by_group($this->store_id);
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
			if($id == $r->product_group_id){
				$chkdisabled="disabled checked";
				$isFound = true;
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

	// function change_position(){
	// 	if ($_POST) {
	// 		$temp_position = $_SERVER['REMOTE_ADDR'];
	// 		$ids    = $_POST["ids"];
	// 		for ($idx = 0; $idx < count($ids); $idx+=1) {
	// 			$id = $ids[$idx];
	// 			//...
	// 			$data = array(
	// 				'position'		=> dbClean($idx),
	// 				'temp_position'	=> dbClean($temp_position),
	// 			);

	// 			$a = $this->_save_master(
	// 				$data,
	// 				array(
	// 					'product_group_id' => dbClean((int)$id)
	// 				),
	// 				dbClean((int)$id)
	// 			);
	// 		}
	// 		return;
	// 	}
	// }

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_product_group",array("product_group_show"=>$val),array("product_group_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

}
