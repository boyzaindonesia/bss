<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class reseller_prices extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("edit"),"ITEM");
		$this->_set_title( 'Harga Untuk Reseller' );
		$this->DATA->table = "mt_product";
		$this->folder_view = "reseller/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_product","M");
		$this->breadcrumb[] = array(
			"title"		=> "Harga Untuk Reseller",
			"url"		=> $this->own_link
		);

		$this->cat_search = array(
			''							=> 'Semua Pencarian...',
			'mt_product.product_name'	=> 'Judul'
		);
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'reseller_prices',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_product.product_date_push',
			'order_dir' => 'desc',
			'filter' 	=> '',
			'colum'		=> '',
			'keyword'	=> '',
			'product_status_id'	=> '1'
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'reseller_prices'){
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

        if(isset($_POST['product_status_id']) && $_POST['product_status_id'] != ''){
			$this->jCfg['search']['product_status_id'] = $_POST['product_status_id'];
			$this->_releaseSession();
		}

        $par_filter = array(
            "store_id"            => "1",
            "product_status_id"   => $this->jCfg['search']['product_status_id'],
            "type_result"         => "list",
            "date_start"          => $this->jCfg['search']['date_start'],
            "date_end"            => $this->jCfg['search']['date_end'],
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => $this->uri->segment($this->uri_segment),
            "limit"               => $this->jCfg['search']['filter'],
            "keyword"             => $this->jCfg['search']['keyword'],
            "colum"               => $this->jCfg['search']['colum'],
            "param"               => $this->cat_search
        );

		$this->data_table = $this->M->data_product($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		$data['url'] = base_url()."admin/reseller_prices";

		// $user_id 	  		  = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
		// $store_id     		  = get_user_store($user_id);
		// $data['store_id'] 	  = $store_id;
		// $data['detail_store'] = get_detail_store($store_id);

		if($this->jCfg['search']['product_status_id'] != 1){
			$this->_set_title( 'List Produk "'.get_name_product_status($this->jCfg['search']['product_status_id']).'"' );
		}

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function get_reseller_prices(){
		$data = array();
		$data['err'] 	 = true;
		$data['msg'] 	 = '';
		$data['content'] = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'get_reseller_prices' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));

			// $this->DATA->table="mt_product";
			if(trim($thisVal)!=''){

				$i = 1;
				$newId   = array();
				$newName = '';
				$expProduct = explode('-', $thisVal);
				foreach ($expProduct as $key => $value) {
					$r = $this->db->get_where("mt_product",array(
						'product_id' 	 => $value
					),1,0)->row();
					if(count($r) > 0){
						$newId[] = $value;
						$newName .= '<h5>'.$i.'. '.$r->product_name.'</h5>';
						$i += 1;
					}
				}

				if(count($newId) > 0){
					$data['err'] = false;

					$layout_store = '';
					$get_store = get_store();
                    foreach ($get_store as $key => $value) {
                        if($value->store_id != 1){
                        	$reseller_price = 0;
                        	if(count($newId) == 1){
                        		$reseller_price = get_reseller_price($value->store_id, $newId[0]);
                        	}

                        	$layout_store .= '
                            	<tr>
	                                <td>
                                        <input type="hidden" name="store_id[]" value="'.$value->store_id.'">
	                                    '.$value->store_name.'
	                                </td>
	                                <td width="150">
	                                    <div class="input-group">
			                                <span class="input-group-addon">Rp</span>
			                                <input type="text" name="price[]" value="'.($reseller_price!=0?$reseller_price:'').'" class="form-control moneyRp_masking" maxlength="23">

			                            </div>
	                                </td>
	                            </tr>';
                        }
                    }

					$data['content'] = '
					<form class="form-update-harga" action="'.$this->own_link.'/update_harga_reseller" method="post" enctype="multipart/form-data">
						<h3 class="no-margin">HARGA RESELLER</h3>
	                    <div class="row popup-content-product mb-20">
	                        <div class="col-sm-12">
	                            '.$newName.'
	                        </div>
	                    </div>
	                    <div class="table-responsive">
	                        <table class="table table-th-block">
	                            <colgroup>
	                                <col>
	                                <col width="1">
	                            </colgroup>
	                            <thead>
	                                <tr>
	                                    <th>Nama</th>
	                                    <th class="nobr text-center">Harga</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                            '.$layout_store.'
	                            </tbody>
	                        </table>
	                    </div>
	                    <div class="form-msg"></div>
                        <div class="form-group form-action mb-0">
                        	<label class="col-sm-3 control-label"></label>
                        	<div class="col-sm-9">
	                        	<input type="hidden" name="product_id" value="'.$thisVal.'" />
	                            <button type="button" class="btn btn-primary update-harga-btn">Simpan</button>
	                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
	                        </div>
                        </div>
	                </form>';
				}
			}
		}

		die(json_encode($data));
		exit();
	}

	function update_harga_reseller(){
		$data = array();
		$data['err'] 	= false;
		$data['msg'] 	= '';
		$log_desc 		= '';
		if(isset($_POST['product_id']) && $_POST['product_id'] != ''){
			$product_id = dbClean(trim($_POST['product_id']));

			$newId   = array();
			$expProduct = explode('-', $product_id);
			foreach ($expProduct as $key => $value) {
				$r = $this->db->get_where("mt_product",array(
					'product_id' 	 => $value
				),1,0)->row();
				if(count($r) > 0){
					$newId[] = $value;
				}
			}

			$log_detail_item = $product_id;

			$store_price = array();
			if(count($newId) > 0){
				$data['err'] = false;
				foreach ($newId as $key => $val) {
					$product_id = $val;

					if(isset($_POST['store_id'])){
						$store  = $_POST['store_id'];
						$prices = $_POST['price'];

						foreach ($store as $key2 => $val2) {
							$store_id = $store[$key2];
							$price    = $prices[$key2];
							if($price != '' || $price != 0){
								$reseller_prices_id = '';
								$r2 = $this->db->get_where("mt_reseller_prices",array(
									'product_id' 	=> $product_id,
									'store_id' 	 	=> $store_id
								),1,0)->row();
								if(count($r2) > 0){
									$reseller_prices_id = $r2->reseller_prices_id;
								}

								$data_detail = array();
								$data_detail['product_id'] = $product_id;
								$data_detail['store_id']   = $store_id;
								$data_detail['price']      = convertRpToInt($price);

								$this->DATA->table = "mt_reseller_prices";
								$save_detail = $this->_save_master(
									$data_detail,
									array(
										'reseller_prices_id' => $reseller_prices_id
									),
									$reseller_prices_id
								);
							}

							$store_price[$key2]->store_id = $store_id;
							$store_price[$key2]->price    = $price;
						}
					}
				}

				$log_desc = json_encode($store_price);

				writeLog(array(
                    'log_user_type'     => "1", // Admin
                    'log_user_id'       => $this->jCfg['user']['id'],
                    'log_role'          => NULL,
                    'log_type'          => "2", // Produk
                    'log_detail_id'     => NULL,
                    'log_detail_item'   => $log_detail_item,
                    'log_detail_qty'    => NULL,
                    'log_title_id'      => "8", // Perubahan Harga Reseller
                    'log_desc'          => $log_desc,
                    'log_status'        => "0"
                ));

				$data['err'] = false;
				$data['msg'] = 'Sukses merubah harga reseller.';
			} else {
				$data['err'] = true;
				$data['msg'] = 'Produk tidak ditemukan.';
			}
		}

		die(json_encode($data));
		exit();
	}

}
