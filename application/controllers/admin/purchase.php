<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class purchase extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'List Data Pembelian' );
		$this->DATA->table="mt_purchase";
		$this->folder_view = "orders/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_purchase","M");
		$this->breadcrumb[] = array(
				"title"		=> "List Pembelian",
				"url"		=> $this->own_link
			);

		$this->cat_search = array(
			''										=> 'Semua Pencarian...',
			'mt_purchase.purchase_invoice'			=> 'No Invoice',
			'mt_purchase_detail.purchase_detail_name' => 'Nama Produk',
			'mt_supplier.supplier_name' 			=> 'Nama Supplier'
		);
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'purchase',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_purchase.purchase_date',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}


	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'purchase'){
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
			"type"			=> '',
			"detail_status"	=> '',
			"group_by"		=> true,
			"offset"		=> $this->uri->segment($this->uri_segment),
			"limit"			=> $this->per_page,
			"param"			=> $this->cat_search
		);
		$this->data_table = $this->M->data_purchase($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		$data['url'] = base_url()."admin/purchase/index";

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function add(){
		$this->breadcrumb[] = array(
			"title"		=> "Add"
		);

		$this->_v($this->folder_view.$this->prefix_view."_form");
	}

	function view($id=''){
		$data = array();
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'purchase_id'	=> $id
			));
			if(empty($this->data_form->purchase_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_view",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function edit($id=''){
		$data = array();
		$this->breadcrumb[] = array(
			"title"		=> "Edit"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				'purchase_id'	=> $id
			));
			if(empty($this->data_form->purchase_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_form",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
	// 		$this->DATA->_delete(array("purchase_id"	=> idClean($id)),true);
			$this->db->update("mt_purchase",array("purchase_istrash"=>1),array("purchase_id"=>$id));
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_purchase",array(
			"purchase_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->purchase_id;
			$this->DATA->_delete(array("purchase_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	function save(){
		$data = array(
			'purchase_date'				=> dbClean(convDatepickerDec($_POST['pdate'])).' '.dbClean(convTimepickerDec($_POST['phour'])).':00',
			'purchase_invoice'			=> dbClean(strtoupper($_POST['purchase_invoice'])),
			'supplier_id'				=> dbClean($_POST['supplier_id']),
			'purchase_status'			=> 1,
			'purchase_payment_source'	=> dbClean($_POST['purchase_payment_source']),
			'purchase_noted'			=> $_POST['purchase_noted']
		);

		$purchase_payment_price = dbClean(convertRpToInt($_POST['purchase_payment_price']));

		$data['purchase_payment_detail'] = "";
		if(dbClean($_POST['purchase_id']) == ""){
			$data['purchase_user_id']   = $this->jCfg['user']['id'];
			if($data['purchase_invoice'] == ''){ $data['purchase_invoice'] = create_purchase_invoice()['purchase_invoice']; }

			$arr_payment_detail = array();
			$arr_payment_detail[] = array('date' => timestamp(), 'price' => $purchase_payment_price);
			$data['purchase_payment_detail'] = json_encode($arr_payment_detail);
		}

		$a = $this->_save_master(
			$data,
			array(
				'purchase_id' => dbClean($_POST['purchase_id'])
			),
			dbClean($_POST['purchase_id'])
		);

		$id = $a['id'];
		if($id != ''){

			// SAVE DETAIL
			$this_total = 0;
			$grand_total = 0;
			if(isset($_POST['product_name'])){
				$product_name   = $_POST['product_name'];
				$product_satuan = $_POST['product_satuan_id'];
				$product_qty    = $_POST['product_qty'];
				$product_price  = $_POST['product_price'];
				$store_id  		= $_POST['store_id'];
				$purchase_reseller_price = $_POST['purchase_reseller_price'];

				$arr_product_name = array();
				foreach ($product_name as $key => $n) {
					if($n != ''){
						$this_total = ($product_qty[$key] * convertRpToInt($product_price[$key]));
						$grand_total = ($grand_total + $this_total);

						$data2 = array(
							'purchase_id'			=> idClean($id),
							'purchase_detail_name'	=> $n,
							'purchase_detail_satuan'=> $product_satuan[$key],
							'purchase_detail_price'	=> convertRpToInt($product_price[$key]),
							'purchase_detail_qty'	=> $product_qty[$key],
							'store_id'				=> $store_id[$key],
							'purchase_reseller_price'     => 0,
							'purchase_reseller_payment'	  => NULL,
							'purchase_reseller_remaining' => 0
						);
						if($data2['store_id'] > 1){
							$total_reseller_price = ($product_qty[$key] * convertRpToInt($purchase_reseller_price[$key]));
							$data2['purchase_reseller_price']     = convertRpToInt($purchase_reseller_price[$key]);
							$data2['purchase_reseller_remaining'] = $total_reseller_price;
						}

						$arr_product_name[] = $n;

						$this->DATA->table="mt_purchase_detail";
						$a2 = $this->_save_master(
							$data2,
							array(
								'purchase_detail_id' => ''
							),
							''
						);
					}
				}
			}

			$data3['purchase_price_grand_total'] = $grand_total;

			if(dbClean($_POST['purchase_id']) == ""){
				$data3['purchase_payment_remaining'] = ($data3['purchase_price_grand_total'] - $purchase_payment_price);
			} else {
				$m = $this->db->get_where("mt_purchase",array(
					'purchase_id' => dbClean($_POST['purchase_id'])
				),1,0)->row();
				if(count($m)>0){
					if($m->purchase_payment_remaining != 0){
						$data3['purchase_payment_remaining'] = ($m->purchase_payment_remaining - $purchase_payment_price);
					}
				}
			}

			if($data3['purchase_payment_remaining'] == 0){ $data3['purchase_status'] = 1; } else { $data3['purchase_status'] = 0; }

			$this->db->update("mt_purchase",$data3,array("purchase_id"=>$id));

			if($data['purchase_payment_source'] == '2' || $data['purchase_payment_source'] == '3'){
				$purchase_saldo_noted = 'Pembelian '.implode(', ', $arr_product_name).' dengan no invoice '.$data['purchase_invoice'];
				insert_saldo(array(
					'orders_source_id'	=> $data['purchase_payment_source'],
					'orders_id'			=> '',
					'saldo_price'		=> $purchase_payment_price,
					'saldo_noted'		=> $purchase_saldo_noted,
					'saldo_type'		=> 2
				));
			}
		}

		redirect($this->own_link."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function save_reseller_payment_remaining(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if( isset($_POST['thisAction']) && $_POST['thisAction'] != '' ){
			$thisAction  = dbClean(trim($_POST['thisAction']));
			$thisId      = dbClean(trim($_POST['thisId']));
			$price       = dbClean(trim($_POST['price']));

			if(trim($thisId)!=''){
				$v = $this->db->get_where("mt_purchase_detail",array(
					'purchase_detail_id ' => $thisId
				),1,0)->row();
				if(count($v)>0){
					if($v->purchase_reseller_status != 1){
						$price = convertRpToInt($price);
						$data2['purchase_reseller_remaining'] = ($v->purchase_reseller_remaining - $price);
						if($data2['purchase_reseller_remaining'] == 0){ $data2['purchase_reseller_status'] = 1; } else { $data2['purchase_reseller_status'] = 0; }

						$arr_payment_detail = array();
						$temp_purchase_reseller_payment = json_decode($v->purchase_reseller_payment);
						foreach ($temp_purchase_reseller_payment as $key => $value) {
							$arr_payment_detail[] = array('date' => $value->date, 'price' => $value->price);
						}
						$arr_payment_detail[] = array('date' => timestamp(), 'price' => $price);
						$data2['purchase_reseller_payment'] = json_encode($arr_payment_detail);

						$this->db->update("mt_purchase_detail",$data2,array("purchase_detail_id"=>$thisId));

						$data['err'] = false;
						$data['msg'] = 'Sukses';
					} else {
						$data['err'] = true;
						$data['msg'] = 'Purchase sudah lunas!';
					}
				} else {
					$data['err'] = true;
					$data['msg'] = 'ID Purchase tidak ditemukan!';
				}
			} else {
				$data['err'] = true;
				$data['msg'] = 'ID Purchase tidak ditemukan atau Jumlah bayar tidak numeric!';
			}
		}

		die(json_encode($data));
		exit();
	}

	function save_payment_remaining(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if( isset($_POST['thisAction']) && $_POST['thisAction'] != '' ){
			$thisAction  = dbClean(trim($_POST['thisAction']));
			$thisId      = dbClean(trim($_POST['thisId']));
			$price       = dbClean(trim($_POST['price']));

			if(trim($thisId)!=''){
				$v = $this->db->get_where("mt_purchase",array(
					'purchase_id ' => $thisId
				),1,0)->row();
				if(count($v)>0){
					if($v->purchase_status != 1){
						$price = convertRpToInt($price);
						$data2['purchase_payment_remaining'] = ($v->purchase_payment_remaining - $price);
						if($data2['purchase_payment_remaining'] == 0){ $data2['purchase_status'] = 1; } else { $data2['purchase_status'] = 0; }

						$arr_payment_detail = array();
						$temp_purchase_payment_detail = json_decode($v->purchase_payment_detail);
						foreach ($temp_purchase_payment_detail as $key => $value) {
							$arr_payment_detail[] = array('date' => $value->date, 'price' => $value->price);
						}
						$arr_payment_detail[] = array('date' => timestamp(), 'price' => $price);
						$data2['purchase_payment_detail'] = json_encode($arr_payment_detail);

						$this->db->update("mt_purchase",$data2,array("purchase_id"=>$thisId));

						$data['err'] = false;
						$data['msg'] = 'Sukses';
					} else {
						$data['err'] = true;
						$data['msg'] = 'Purchase sudah lunas!';
					}
				} else {
					$data['err'] = true;
					$data['msg'] = 'ID Purchase tidak ditemukan!';
				}
			} else {
				$data['err'] = true;
				$data['msg'] = 'ID Purchase tidak ditemukan atau Jumlah bayar tidak numeric!';
			}
		}

		die(json_encode($data));
		exit();
	}
	// function change_status($id='',$val=''){
		// $data = array();
		// $data['msg'] = '';
	// 	$id  = dbClean(trim($id));
	// 	$val = dbClean(trim($val));
	// 	if(trim($id) != ''){
	// 		if($val == 'true'){ $val = '1'; } else { $val = '0'; }
	// 		$this->db->update("mt_purchase",array("purchase_status"=>$val),array("purchase_id"=>$id));
	// 		$data['msg'] = 'success';
	// 	}

		// die(json_encode($data));
		// exit();
	// }

	function check_form(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_form' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$thisChkId     = dbClean(trim($_POST['thisChkId']));
			$thisChkParent = dbClean(trim($_POST['thisChkParent']));
			$thisChkRel    = dbClean(trim($_POST['thisChkRel']));

			$this->DATA->table="mt_purchase";
			if(trim($thisVal)!=''){
				if(trim($thisChkId)!=''){
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	   => $thisVal,
						'purchase_id !=' => $thisChkId
					));
				} else {
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	=> $thisVal
					));
				}
				if(empty($this->data_form->$thisChkRel)){
					$data['err'] = false;
					$data['msg'] = '';
				} else {
					$data['err'] = true;
					$data['msg'] = 'Data sudah ada...';
				}
			}
		}

		die(json_encode($data));
		exit();
	}

	function views_purchase_noted(){
		$data = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		$data['result'] = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));

			if(trim($thisVal)==''){ $thisVal = 0; }
			$r = $this->db->get_where("mt_purchase",array(
				'purchase_id ' => $thisVal
			),1,0)->row();

			$data['content'] = '
			<form class="form_save_label" action="'.$this->own_link.'/save_purchase_noted" method="post" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <legend>Form Catatan</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Catatan</label>
                        <div class="col-sm-9">
                            <textarea name="purchase_noted" class="form-control tinymce" rows="6">'.$r->purchase_noted.'</textarea>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="hidden" name="purchase_id" value="'.$r->purchase_id.'" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                        </div>
                    </div>
                </div>
            </form>
			';

		}

		die(json_encode($data));
		exit();
	}

	function save_purchase_noted(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){

			$data2 = array(
				'purchase_noted' 	=> $_POST['purchase_noted']
			);

			$this->db->update("mt_purchase",$data2,array("purchase_id"=>$_POST['purchase_id']));

			$data['err'] = false;
			$data['msg'] = 'Sukses menyimpan data.';

		}

		die(json_encode($data));
		exit();
	}

}
