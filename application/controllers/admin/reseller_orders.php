<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");

class reseller_orders extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit"),"ITEM");
		$this->_set_title( 'List Data Order' );
		$this->DATA->table="mt_store_orders";
		$this->folder_view = "orders/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_reseller_orders","M");
		$this->breadcrumb[] = array(
				"title"		=> "List Order",
				"url"		=> $this->own_link
			);

		$this->cat_search = array(
			''										=> 'Semua Pencarian...',
			'mt_store_orders.store_orders_name'		=> 'Nama Customer',
			'mt_store_orders.store_orders_code'  	=> 'No Order',
			'mt_store_orders.store_orders_invoice' 	=> 'No Invoice'
		);

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        $this->detail_store     = get_detail_store($this->store_id);
        $this->store_name       = $this->detail_store->store_name;
        $this->store_phone      = $this->detail_store->store_phone;
        $this->store_product    = $this->detail_store->store_product;
	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'reseller_orders',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_store_orders.store_orders_date',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function _reset_payment(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'reseller_orders_payment',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_store_payment.payment_date',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

    function index(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'marketplace'){
            $this->_reset();
        }

        redirect($this->own_link.'/list_orders');
    }

	function list_orders(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'reseller_orders'){
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

        $par_filter = array(
            "store_id"            => $this->store_id,
            "type_result"         => "",
            "date_start"          => $this->jCfg['search']['date_start'],
            "date_end"            => $this->jCfg['search']['date_end'],
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => $this->uri->segment($this->uri_segment),
            "limit"               => $this->jCfg['search']['filter'],
            "colum"               => $this->jCfg['search']['colum'],
            "keyword"             => $this->jCfg['search']['keyword'],
            "param"               => $this->cat_search
        );

		$this->data_table = $this->M->data_orders($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/list_orders'
		));

        $data['url']            = base_url()."admin/reseller_orders/list_orders";
        $data['url_form']       = base_url()."admin/reseller_orders/list_orders";
        $data['tab']            = 'tab1';
        $data['content_layout'] = $this->prefix_view."_list.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function list_payment(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'reseller_orders_payment'){
			$this->_reset_payment();
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
            $this->_reset_payment();
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

		$this->cat_search = array(
			''			     => 'Semua Pencarian...',
			'payment_price'  => 'Bayar'
		);

        $par_filter = array(
            "store_id"            => $this->store_id,
            "type_result"         => "",
            "date_start"          => $this->jCfg['search']['date_start'],
            "date_end"            => $this->jCfg['search']['date_end'],
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => $this->uri->segment($this->uri_segment),
            "limit"               => $this->jCfg['search']['filter'],
            "colum"               => $this->jCfg['search']['colum'],
            "keyword"             => $this->jCfg['search']['keyword'],
            "param"               => $this->cat_search
        );

        $this->data_table = $this->M->data_orders_payment($par_filter);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/list_payment'
        ));

        $data['url']            = base_url()."admin/reseller_orders";
        $data['url_form']       = base_url()."admin/reseller_orders/list_payment";
        $data['tab']            = 'tab2';
        $data['content_layout'] = $this->prefix_view."_payment.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
	}

	// function export_data(){
	// 	$data = array();

	// 	$this->data_table = $this->M->data_orders();
	// 	$data = $this->_data(array(
	// 		"base_url"	=> $this->own_link.'/index'
	// 	));

	// 	$this->_v($this->folder_view.$this->prefix_view."_export",$data,FALSE);
	// }

	function add(){
		$this->breadcrumb[] = array(
			"title"		=> "Add"
		);

		$data = array();

		$this->_v($this->folder_view.$this->prefix_view."_form",$data);
	}

	// function view($id=''){
	// 	$data = array();
	// 	$this->breadcrumb[] = array(
	// 		"title"		=> "View"
	// 	);

	// 	$id = explode("-", $id);
	// 	$id = dbClean(trim($id[0]));
	// 	if(trim($id)!=''){
	// 		$this->data_form = $this->DATA->data_id(array(
	// 			'orders_id'	=> $id
	// 		));
	// 		if(empty($this->data_form->orders_id)){
	// 			redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
	// 		}

	// 		$data['orders_detail']    = get_orders_detail($this->data_form->orders_id);
	// 		$data['orders_payment']   = get_detail_orders_payment($this->data_form->orders_id);
	// 		$data['orders_shipping']  = get_detail_orders_shipping($this->data_form->orders_id);
	// 		$data['orders_timestamp'] = get_detail_orders_timestamp($this->data_form->orders_id);

	// 		$this->_v($this->folder_view.$this->prefix_view."_view",$data);
	// 	}else{
	// 		redirect($this->own_link);
	// 	}
	// }

	// function edit($id=''){
	// 	$data = array();
	// 	$this->breadcrumb[] = array(
	// 		"title"		=> "Edit"
	// 	);

	// 	$id = explode("-", $id);
	// 	$id = dbClean(trim($id[0]));
	// 	if(trim($id)!=''){
	// 		$this->data_form = $this->DATA->data_id(array(
	// 			'orders_id'	=> $id
	// 		));
	// 		if(empty($this->data_form->orders_id)){
	// 			redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
	// 		}

	// 		$data['orders_detail']    = get_orders_detail($this->data_form->orders_id);
	// 		$data['orders_payment']   = get_detail_orders_payment($this->data_form->orders_id);
	// 		$data['orders_shipping']  = get_detail_orders_shipping($this->data_form->orders_id);
	// 		$data['orders_timestamp'] = get_detail_orders_timestamp($this->data_form->orders_id);

	// 		unset($this->jCfg['cart']);
	// 		unset($this->jCfg['cart_shipping_price']);
	// 		unset($this->jCfg['cart_voucher_price']);
	// 		$this->_releaseSession();

	// 		foreach ($data['orders_detail'] as $key => $value) {
	// 			$new_cart_qty = array();
	// 			$orders_detail_item = json_decode($value->orders_detail_item);
 //                foreach ($orders_detail_item as $key2 => $value2) {
 //                	$new_cart_qty[$value2->id] = $value2->qty;
 //                }

	// 			$this->jCfg['cart'][$value->product_id]= array(
	// 				'cart-id'		=> $value->product_id,
	// 				'cart-qty'		=> $new_cart_qty
	// 			);
	// 		}

	// 		$this->jCfg['cart_shipping_price'] = $this->data_form->orders_price_shipping;
	// 		$this->jCfg['cart_voucher_price'] = $this->data_form->orders_voucher_price;
	// 		$this->_releaseSession();

	// 		$this->_v($this->folder_view.$this->prefix_view."_form",$data);
	// 	}else{
	// 		redirect($this->own_link);
	// 	}
	// }

	function delete($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
	// 		$this->DATA->_delete(array("store_orders_id"	=> idClean($id)),true);
			$this->db->update("mt_store_orders",array("store_orders_istrash"=>1),array("store_orders_id"=>$id));
		}
		redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
	}

	function empty_trash(){
		$data = $this->db->get_where("mt_store_orders",array(
			"store_orders_istrash"	=> 1
		))->result();
		foreach($data as $r){
			$id = $r->store_orders_id;
			$this->DATA->_delete(array("store_orders_id"	=> idClean($id)),true);
		}
		redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
	}

	// BELUM UPDATE
	function checkout(){
		$data = array();
		$data['href'] 		= '';
		$data['cart_err'] 	= false;
		$data['cart_msg'] 	= array();
		$data['cart_err_stock'] = false;
		$data['cart_msg_stock'] = array();

        debugCode("Belum");

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'checkout'){

			$this->store_id 	= 1;
			if(isset($_POST['reseller_orders']) && $_POST['reseller_orders'] == true){
				$this->store_id = get_user_store($this->user_id);
			}

			$cur_cart = $this->db->order_by('temp_orders_date','desc')->get_where("mt_temp_orders",array(
				'member_type' 		=> 1,
				'member_id' 		=> $this->user_id
			))->result();
			if(count($cur_cart) == 0){
				$data['cart_err']   = true;
				$data['cart_msg'][] = 'Keranjang belanja masih kosong.';
			} else {

				$set_shipping = set_shipping();
				$data['cart_ppn_price'] 			= $set_shipping['ppn'];
				$data['cart_grandtotal_price_buy'] 	= 0;
				$data['cart_subgrandtotal_price'] 	= 0;
				$data['cart_grandtotal_weight']     = 0;
				$data['cart_grandtotal_qty'] 		= 0;
				$data['cart_shipping_price'] 		= convertRpToInt($_POST['orders-shipping-price']);
				$data['cart_voucher_price'] 		= convertRpToInt($_POST['orders-voucher-price']);
				$data['cart_voucher_code']  		= dbClean($_POST['orders-voucher-code']);

				$data['temp_orders_detail'] = array();
				foreach ($cur_cart as $k => $v){
					$r = $this->db->get_where("mt_product",array(
						'product_id' 		=> $v->product_id,
						'product_istrash' 	=> '0'
					),1,0)->row();
					if(count($r) > 0){
						$data['cart_id'] 	  = $r->product_id;
						$data['product_sold'] = $r->product_sold;

						$detail 					= get_product_detail($r->product_id);
						$data['cart_id_detail'] 	= $detail->product_detail_id;
						$data['product_price_buy'] 	= $detail->product_price_buy;
						$data['cart_weight'] 		= $detail->product_weight;

						$arr_detail_item = array();
						$data['product_detail_item'] = json_decode($v->product_detail_item);
						foreach ($data['product_detail_item'] as $key => $value) {
							$arr_detail_item[$value->id] = $value->qty;
						}

						if($detail->product_status_id == '1'){
							$data['product_stock'] 	  = $detail->product_stock;
							$data['cart_total_qty']   = 0;
							$data['cart_detail_item'] = array();
							$data['product_stock_detail'] = json_decode($detail->product_stock_detail);
							foreach ($data['product_stock_detail'] as $key => $value) {
								if(array_key_exists($value->id, $arr_detail_item)){

									if($this->store_id != 1){ // CEK STOK
										if($arr_detail_item[$value->id] > $value->qty){
											$data['cart_err'] = true;
											$data['cart_err_stock'] = true;
											$data['cart_msg_stock'][] = array(
																			'id' 	=> $value->id,
																			'qty' 	=> $value->qty,
																			'msg' 	=> 'Varian No '.$value->id.' hanya tersedia <strong>'.$value->qty.'</strong> pcs.'
																		);
										}
									}

									if($data['cart_err_stock'] == false){
										$data['cart_total_qty'] 	= $data['cart_total_qty'] + $arr_detail_item[$value->id];
										$data['cart_detail_item'][] = array('id' 	=> $value->id,
																	  		'qty'	=> $arr_detail_item[$value->id]
																		);
									}
								}
							}

							if($data['cart_err_stock'] == false){
								$data['cart_grandtotal_qty']      	 = $data['cart_grandtotal_qty'] + $data['cart_total_qty'];
								$data['cart_total_weight'] 			 = $data['cart_weight'] * $data['cart_total_qty'];
								$data['cart_grandtotal_weight']      = $data['cart_grandtotal_weight'] + $data['cart_total_weight'];
								$data['cart_grandtotal_weight_span'] = convertGrToKg($data['cart_grandtotal_weight']);

								if($this->store_id == 1){
									$data['cart_price']      = $detail->product_price_sale;
									if($detail->product_price_discount != '0'){
										$data['cart_price']  = $detail->product_price_discount;
									}
				                    if($detail->product_price_grosir != ''){
				                        $data['product_price_grosir'] = json_decode($detail->product_price_grosir);
				                        foreach ($data['product_price_grosir'] as $key => $value){
				                        	if($value->qty <= $data['cart_total_qty']){
					                            $data['cart_price']      = $value->price;
					                        }
				                        }
				                    }
				                } else {
		                            $data['cart_price']      = get_product_reseller_price($this->store_id, $r->product_id);
				                }

			                    $data['cart_total_price']      		= $data['cart_price'] * $data['cart_total_qty'];
								$data['cart_subgrandtotal_price']   = $data['cart_subgrandtotal_price'] + $data['cart_total_price'];
								$data['cart_grandtotal_ppn_price']  = ($data['cart_subgrandtotal_price'] * $data['cart_ppn_price'])/100;
								// $data['cart_grandtotal_shipping_price'] = $data['cart_shipping_price'] * $data['cart_grandtotal_weight_span']; // Jika dihitung per 1kg
								$data['cart_grandtotal_shipping_price'] = $data['cart_shipping_price'];

								$data['cart_grandtotal_price']  = (($data['cart_subgrandtotal_price'] + $data['cart_grandtotal_ppn_price'] + $data['cart_grandtotal_shipping_price']) - $data['cart_voucher_price']);

								$data['cart_grandtotal_price_buy'] = $data['cart_grandtotal_price_buy'] + ($data['product_price_buy'] * $data['cart_total_qty']);

								$item = array('product_id'			=> $data['cart_id'],
									  		  'product_sold'		=> $data['product_sold'],
									  		  'product_detail_id'	=> $data['cart_id_detail'],
									  		  'product_price_buy'	=> $data['product_price_buy'],
									  		  'product_stock'		=> $data['product_stock'],
									  		  'product_stock_detail'=> json_encode($data['product_stock_detail']),
									  		  'orders_detail_price'	=> $data['cart_price'],
									  		  'orders_detail_qty'	=> $data['cart_total_qty'],
									  		  'orders_detail_weight'=> $data['cart_weight'],
									  		  'orders_detail_item'	=> json_encode($data['cart_detail_item'])
											);

								array_push($data['temp_orders_detail'], $item);
								$data['temp_orders_detail'] = arrayToObj($data['temp_orders_detail']);

							}
						} else {
							$data['cart_err']   = true;
							$data['cart_msg'][] = get_product_status($detail->product_status_id)->product_status_name;
						}
					} else {
						$data['cart_err']   = true;
						$data['cart_msg'][] = 'Produk tidak ditemukan.';
					}
				}
			}

			// SAVE
			if($data['cart_err'] == false){

				$store_orders_code 	 = create_store_orders_code();

				// SAVE MT_STORE_ORDERS
				$data1 = array(
					'user_id'					=> $this->user_id,
					'store_id'					=> $this->store_id,
					'store_orders_name'			=> dbClean(ucwords($_POST['orders_shipping_name'])),
					'store_orders_noted'		=> dbClean($_POST['orders_noted']),
					'store_orders_date'			=> timestamp(),
					'store_orders_code'			=> $store_orders_code['orders_code'],
					'store_orders_invoice'		=> $store_orders_code['orders_invoice'],
					'store_orders_istrash'		=> 0,
					'orders_price_buy_total'	=> $data['cart_grandtotal_price_buy'],
					'orders_price_grand_total'	=> $data['cart_grandtotal_price'],
					'ip_address'				=> $_SERVER['REMOTE_ADDR'],
					'user_agent'				=> $_SERVER['HTTP_USER_AGENT']
				);

				$this->DATA->table="mt_store_orders";
				$a = $this->_save_master(
					$data1,
					array(
						'store_orders_id' => ''
					),
					''
				);

				$id = $a['id'];
				if($id != ''){

					// SAVE MT_STORE_ORDERS_DETAIL
					foreach ($data['temp_orders_detail'] as $key => $value) {
						$data2 = array(
							'store_orders_id'		=> $id,
                            'product_id'            => $value->product_id,
                            'product_name'          => get_title_product($value->product_id),
							'product_images'		=> get_cover_image_detail($value->product_id),
							'product_price_buy'		=> $value->product_price_buy,
							'orders_detail_price'	=> $value->orders_detail_price,
							'orders_detail_qty'		=> $value->orders_detail_qty,
							'orders_detail_weight'	=> $value->orders_detail_weight,
							'orders_detail_item'	=> $value->orders_detail_item
						);

						$this->DATA->table="mt_store_orders_detail";
						$a2 = $this->_save_master(
							$data2,
							array(
								'orders_detail_id' => ''
							),
							''
						);
						$orders_detail_id = $a2['id'];
						if($orders_detail_id != ''){





							// PENGURANGAN STOK
							$data3['product_stock'] = ($value->product_stock - $value->orders_detail_qty);
							// if($data3['product_stock'] <= 0){ $data3['product_status_id'] = 3; }

							$arr_detail_item 	= array();
							$orders_detail_item = json_decode($value->orders_detail_item);
							foreach ($orders_detail_item as $key2 => $value2) {
								$arr_detail_item[$value2->id] = $value2->qty;
							}

                            if($value->product_stock_detail != ''){
    							$arr_stock = array();
    							$product_stock_detail = json_decode($value->product_stock_detail);
    							foreach ($product_stock_detail as $key3 => $value3) {
    								$new_qty = $value3->qty;
    								if(array_key_exists($value3->id, $arr_detail_item)){
    									$new_qty = $new_qty - $arr_detail_item[$value3->id];
    								}
    								$arr_stock[] = array('id' 		=> $value3->id,
    													 'name' 	=> $value3->name,
    													 'color' 	=> $value3->color,
    													 'qty' 		=> $new_qty,
    													 'status' 	=> $value3->status
    												  );
    							}
    							$data3['product_stock_detail'] = json_encode($arr_stock);
                            }
							$this->db->update("mt_product_detail",$data3,array("product_detail_id"=>$value->product_detail_id));

							$product_sold = $value->product_sold + $value->orders_detail_qty;
							$this->db->update("mt_product",array("product_sold"=>$product_sold),array("product_id"=>$value->product_id));

							$del = $this->db->delete("mt_temp_orders",array(
								'member_type' 	 => 1,
								'member_id' 	 => $this->user_id,
								'product_id'	 => $value->product_id
							));

						}
					}
				}

				$data['href'] = $this->own_link;

				unset($this->jCfg['cart_shipping_price']);
				unset($this->jCfg['cart_voucher_price']);
				$this->_releaseSession();
			} else {
				// $swal_msg = '';
				// foreach ($data['cart_msg'] as $key => $value){ $swal_msg .= '<li>'.urlencode($value).'</li>'; }
				// foreach ($data['cart_msg_stock'] as $key => $value){ $swal_msg .= '<li>'.urlencode($value['msg']).'</li>'; }
				// redirect($this->own_link."/add/?msg=".urlencode('Save data order failed')."&type_msg=error&swal_msg=".$swal_msg."&swal_type=error&swal_title=Error!");
			}
		}

		die(json_encode($data));
		exit();
	}

	// BELUM UPDATE
	function cart_get_product(){
		$data = array();
		$data['err'] 	 = true;
		$data['msg'] 	 = '';
		$data['content'] = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'get_product' ){
			$thisVal    = dbClean(trim($_POST['thisVal']));

			$this->store_id 	= 1;
			if(isset($_POST['reseller_orders']) && $_POST['reseller_orders'] == true){
				$this->store_id = get_user_store($this->user_id);
			}

			if(trim($thisVal)!=''){
				$r = $this->db->get_where("mt_product",array(
					'product_id ' 		=> $thisVal,
					'product_istrash ' 	=> '0'
				),1,0)->row();
				if(count($r) > 0){
					$data['err'] = false;

					$data['cart_id'] 		= $r->product_id;
					$data['cart_name'] 		= $r->product_name;
					$data['cart_image'] 	= get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));
					$data['cart_link'] 		= base_url().'product/'.$r->url;

					$detail = get_product_detail($r->product_id);
					$data['cart_id_detail'] 		= $detail->product_detail_id;
					$data['cart_weight'] 			= $detail->product_weight;
					$data['cart_weight_span'] 		= convertGrToKg($data['cart_weight']);
					$data['cart_grosir_price_span'] = $detail->product_price_grosir;
					$data['cart_total_weight'] 		= 0;
					$data['cart_total_weight_span'] = 0;
					$data['cart_total_qty'] 		= 0;
					$data['cart_total_qty_span'] 	= 0;
					$data['cart_total_price'] 		= 0;
					$data['cart_total_price_span'] 	= 0;

					if($this->store_id == 1){
						$data['cart_price']      = $detail->product_price_sale;
	                    $data['cart_price_span'] = convertRP($detail->product_price_sale);
	                    if($detail->product_price_discount != '0'){
	                        $data['cart_price']      = $detail->product_price_discount;
	                        $data['cart_price_span'] = '<span class="price-discount">'.convertRP($detail->product_price_sale).'</span><span class="text-danger">'.convertRP($detail->product_price_discount).'</span>';
	                    }
	                    $data['cart_normal_price'] 		= $data['cart_price'];
	                    $data['cart_normal_price_span'] = $data['cart_price_span'];
	                } else {
						$data['cart_price']      = get_product_reseller_price($this->store_id, $r->product_id);
	                    $data['cart_price_span'] = convertRP($data['cart_price']);
	                    $data['cart_normal_price'] 		= $data['cart_price'];
	                    $data['cart_normal_price_span'] = $data['cart_price_span'];
	                }

	                $arr_detail_item = array();
	                $cart = $this->db->get_where("mt_temp_orders",array(
						'member_type' 		=> 1,
						'member_id' 		=> $this->user_id,
						'product_id' 		=> $r->product_id
					),1,0)->row();
					if(count($cart) > 0){
						$data['product_detail_item'] = json_decode($cart->product_detail_item);
						foreach ($data['product_detail_item'] as $key => $value) {
							$arr_detail_item[$value->id] = $value->qty;
						}
					}

                	// GENERATE SPESIFIKASI ITEM DETAIL
                    $data['product_stock_detail_content'] = '';
					if($detail->product_stock_detail != ''){
                        $data['product_stock_detail'] = json_decode($detail->product_stock_detail);
                        $isi_grosir = '';
                        foreach ($data['product_stock_detail'] as $key => $value) {
							$data['cart_qty_id']  = '';
							$data['cart_qty_qty'] = 0;

							if(count($arr_detail_item) > 0){
								if(array_key_exists($value->id, $arr_detail_item)){
			                		$data['cart_qty_id']  = $value->id;
									$data['cart_qty_qty'] = isset($arr_detail_item[$value->id])?$arr_detail_item[$value->id]:'0';
									$data['cart_total_qty'] 	 = $data['cart_total_qty'] + $arr_detail_item[$value->id];
									$data['cart_total_qty_span'] = $data['cart_total_qty'];
								}
							}

							$checkbox_class    = '';
							$checkbox_disabled = '';
							if($value->status==2 || $value->qty<=0){
								$checkbox_class = 'soldout';
								if($this->store_id != 1){
									$checkbox_disabled = 'disabled';
								}
							}

                            $arr_bg_gelap = array('000000','020202');

                            $isi_stock_detail .= '
                                <div class="col-sm-4">
                                    <div class="product-variasi-item clearfix '.$checkbox_disabled.' '.($data['cart_qty_id']!=''?'checked':'').'" style="'.($value->color!=''?'border-color:#'.$value->color.';':'').'" title="'.$value->name.($checkbox_class=='soldout'?' Soldout':'').'">
                                        <div class="checkbox '.$checkbox_class.'">
                                            <label>
                                                <input type="checkbox" name="chk-cart-detail['.$value->id.']" class="form-cart-checkbox" '.$checkbox_disabled.' value="1" '.($data['cart_qty_id']!=''?'checked':'').'>
                                                <div class="checkbox-text" style="'.($value->color!=''?'background-color:#'.$value->color.';':'').' '.(in_array($value->color, $arr_bg_gelap)?'color:#ffffff;':'').'">
                                                    <div class="centeralign"><div class="centeralign-2">
                                                        '.($value->name!=''?$value->name:$value->id).'
                                                    </div></div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="select">
                                            <input type="hidden" name="cart-detail-stock['.$value->id.']" value="'.$value->qty.'">
                                            <select name="cart-qty['.$value->id.']" class="form-control cart-update-qty" '.($data['cart_qty_id']!=''?'':'disabled="disabled"').'>
                                                <option value="0" disabled selected>0</option>
                                                '.get_option_qty($data['cart_qty_qty']).'
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            ';

                            // [{"id":"1","name":"","color":"ff5f56","qty":"3","status":"1"},{"id":"2","name":"","color":"a1e9e6","qty":"2","status":"1"},{"id":"3","name":"","color":"8cbb87","qty":"2","status":"1"},{"id":"4","name":"","color":"acb5b2","qty":"4","status":"1"},{"id":"5","name":"","color":"7fabe8","qty":"2","status":"1"},{"id":"6","name":"","color":"c49f75","qty":"4","status":"1"}]
                        }

                        $data['product_stock_detail_content'] = '
                        	<div class="form-group product-variasi small">
	                            <label>Ceklis warna dan isi jumlahnya</label>
	                            <div class="row">
		                            '.$isi_stock_detail.'
	                            </div>
	                        </div>';
					} // END GENERATE SPESIFIKASI ITEM DETAIL

                    // GENERATE GROSIR
                    if($this->store_id == 1){
	                    $data['product_grosir_content'] = '';
						if($detail->product_price_grosir != ''){
	                        $count_qty_grosir = array();
	                        $data['product_price_grosir'] = json_decode($detail->product_price_grosir);
	                        $isi_grosir = '';
	                        foreach ($data['product_price_grosir'] as $key => $value) {
	                            $isi_grosir .= '
	                            	<tr>
	                                    <td class="text-center">'.$value->name.' barang</td>
	                                    <td>'.convertRP($value->price).'</td>
	                                </tr>';

	                            if(count($arr_detail_item) > 0){
	                            	if($value->qty <= $data['cart_total_qty']){
			                            $data['cart_price']      = $value->price;
			                            $data['cart_price_span'] = convertRp($data['cart_price']);
			                        }
	                            }
	                        }
	                        // [{"name":"3 - 5","qty":"3","price":"45000"},{"name":"6 - 9","qty":"6","price":"43500"},{"name":">= 10","qty":"10","price":"42000"}]

	                        $data['product_grosir_content'] = '
	                        	<div class="table-responsive">
		                            <small>Detail Harga Grosir</small>
		                            <table class="table table-th-block table-info small">
		                                <colgroup>
		                                    <col>
		                                    <col>
		                                </colgroup>
		                                <thead>
		                                    <tr>
		                                        <th class="text-center" width="100">Beli</th>
		                                        <th>Harga Satuan</th>
		                                    </tr>
		                                </thead>
		                                <tbody>
			                                '.$isi_grosir.'
		                                </tbody>
		                            </table>
		                        </div>';
	                	} // END GENERATE GROSIR
	                } else {
                		$data['product_grosir_content'] = '';
                		$data['cart_grosir_price_span'] = '';
                	}

                	if(count($arr_detail_item) > 0){
						$data['cart_total_weight'] 	    = $data['cart_weight'] * $data['cart_total_qty'];
						$data['cart_total_weight_span'] = convertGrToKg($data['cart_total_weight']);
						$data['cart_total_price']       = $data['cart_price'] * $data['cart_total_qty'];
						$data['cart_total_price_span']  = convertRp($data['cart_total_price']);
					}

                    $data['content'] = '
                    <form class="form-cart cart-item cart-item-'.$data['cart_id'].'" data-id="'.$data['cart_id'].'" action="'.$this->own_link.'/cart_add" method="post" enctype="multipart/form-data">
		                <div style="display:none;">
						    <input type="hidden" name="cart-id" value="'.$data['cart_id'].'" />
						    <input type="hidden" name="cart-name" value="'.$data['cart_name'].'" />
						    <input type="hidden" name="cart-image" value="'.$data['cart_image'].'" />
						    <input type="hidden" name="cart-link" value="'.$data['cart_link'].'" />
						    <input type="hidden" name="cart-price" value="'.$data['cart_price'].'" />
						    <input type="hidden" name="cart-weight" value="'.$data['cart_weight'].'" />
						    <input type="hidden" name="cart-total-weight" value="'.$data['cart_total_weight'].'" />
						    <input type="hidden" name="cart-total-qty" value="'.$data['cart_total_qty'].'" />
						    <input type="hidden" name="cart-total-price" value="'.$data['cart_total_price'].'" />
						    <input type="hidden" name="cart-id-detail" value="'.$data['cart_id_detail'].'" />
						    <input type="hidden" name="cart-action" value="add" />
						    <input type="hidden" name="reseller_orders" value="'.(isset($_POST['reseller_orders'])?$_POST['reseller_orders']:false).'" />
						    <div class="data-normal-price" style="display:none;">'.$data['cart_normal_price'].'</div>
						    <div class="data-normal-price-span" style="display:none;">'.$data['cart_normal_price_span'].'</div>
						    <div class="cart-grosir-price-span" style="display:none;">'.$data['cart_grosir_price_span'].'</div>
						</div>
		                <div class="popup-content-product">
			                <div class="row">
			                    <div class="col-sm-4">
			                        <img class="img-responsive" src="'.get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id)).'" />
			                    </div>
			                    <div class="col-sm-8">
			                        <h4>'.$r->product_name.'</h4>
			                        <p class="mb-5"><small>'.get_root_product_category_parent($r->product_category_id).'</small></p>
			                        <div class="cart-price-span mb-10">'.$data['cart_price_span'].'</div>
			                        '.$data['product_grosir_content'].'
			                    </div>
			                </div>
			                <div class="row mt-10">
			                    <div class="col-sm-12">
			                        '.$data['product_stock_detail_content'].'
			                        <div class="form-msg"></div>
			                        <div class="form-group form-action mb-0">
			                        	'.($detail->product_status_id == 1?'
			                            <button type="button" class="btn btn-primary cart-add-btn">Submit</button>
			                            ':'
			                            <div class="btn btn-primary disabled">'.(get_product_status($detail->product_status_id)->product_status_name).'</div>
			                            ').'
			                        </div>
			                    </div>
			                </div>
		                </div>
		            </form>';
				}
			}
		}

		die(json_encode($data));
		exit();
	}

	// BELUM UPDATE
	function cart_add(){
		$data = array();
		$data['cart_id'] 	= '';
		$data['cart_err'] 	= false;
		$data['cart_msg'] 	= array();
		$data['cart_err_stock'] = false;
		$data['cart_msg_stock'] = array();

		if(isset($_POST['cart-id']) && isset($_POST['cart-action'])){
			$cart_action 	= isset($_POST['cart-action'])?$_POST['cart-action']:'';
			$new_cart_id 	= isset($_POST['cart-id'])?$_POST['cart-id']:'';
			$new_cart_qty 	= isset($_POST['cart-qty'])?$_POST['cart-qty']:'';

			$this->store_id     = 1;
			if(isset($_POST['reseller_orders']) && $_POST['reseller_orders'] == true){
				$this->store_id = get_user_store($this->user_id);
			}

			$r = $this->db->get_where("mt_product",array(
				'product_id' 		=> $new_cart_id,
				'product_istrash' 	=> '0'
			),1,0)->row();
			if(count($r) > 0){
				$detail = get_product_detail($r->product_id);

				$product_id 			= $r->product_id;
				$product_stock 			= $detail->product_stock;
				$product_stock_detail 	= json_decode($detail->product_stock_detail);

				if($detail->product_status_id == '1'){

					if($this->store_id != 1){ // CEK STOK
						foreach ($product_stock_detail as $key => $value) {
							if(array_key_exists($value->id, $new_cart_qty)){
								// echo $value->id.' -> '.$value->qty.' => '.$new_cart_qty[$value->id].'<br/>';
								if($new_cart_qty[$value->id] > $value->qty){
									$data['cart_err'] = true;
									$data['cart_err_stock'] = true;
									$data['cart_msg_stock'][] = array(
																	'id' 	=> $value->id,
																	'qty' 	=> $value->qty,
																	'msg' 	=> 'Varian No '.$value->id.' hanya tersedia <strong>'.$value->qty.'</strong> pcs.'
																);
								}
							}
						}
					}

					if($data['cart_err_stock'] == false){
						$temp_orders_id = "";
						$cart = $this->db->get_where("mt_temp_orders",array(
							'member_type' 		=> 1,
							'member_id' 		=> $this->user_id,
							'product_id' 		=> $new_cart_id
						),1,0)->row();
						if(count($cart) > 0){
							$temp_orders_id = $cart->temp_orders_id;
						}

						$arr_new_cart_qty = array();
						foreach ($new_cart_qty as $key => $n) {
							$arr_new_cart_qty[] = array('id' 	=> $key,
														'qty' 	=> $n
														);
						}
						$json_new_cart_qty = json_encode($arr_new_cart_qty);

						$dataCart = array(
							'temp_orders_date'			=> timestamp(),
							'member_type'				=> 1,
							'member_id'					=> $this->user_id,
							'product_id'				=> $new_cart_id,
							'product_detail_item'		=> $json_new_cart_qty,
							'ip_address'				=> $_SERVER['REMOTE_ADDR'],
							'user_agent'				=> $_SERVER['HTTP_USER_AGENT']
						);
						$this->DATA->table="mt_temp_orders";
						$aCart = $this->_save_master(
							$dataCart,
							array(
								'temp_orders_id' => $temp_orders_id
							),
							$temp_orders_id
						);
					}
				} else {
					$data['cart_err']   = true;
					$data['cart_msg'][] = get_product_status($detail->product_status_id)->product_status_name;
				}
			} else {
				$data['cart_err']   = true;
				$data['cart_msg'][] = 'Produk tidak ditemukan.';
			}

			$data['cart_id'] = $new_cart_id;

			die(json_encode($data));
			exit();
		}
	}

	// BELUM UPDATE
	function cart_load(){
		$data = array();
		$data['cart_err'] 	= false;
		$data['cart_msg'] 	= array();
		$data['cart_count'] = 0;
		$data['cart_list']  = '';
		$data['cart_table'] = '';
		$data['cart_temp_item'] = '';

		$set_shipping = set_shipping();
		$data['cart_ppn_price'] 						= $set_shipping['ppn'];
		$data['cart_shipping_price'] 					= isset($this->jCfg['cart_shipping_price'])?$this->jCfg['cart_shipping_price']:0;
		$data['cart_total_weight'] 						= 0;
		$data['cart_total_weight_span'] 				= convertGrToKg($data['cart_total_weight']);
		$data['cart_voucher_price']      				= isset($this->jCfg['cart_voucher_price'])?$this->jCfg['cart_voucher_price']:0;
		$data['cart_voucher_price_span'] 				= convertRp($data['cart_voucher_price']);
		$data['cart_grandtotal_qty']    				= 0;
		$data['cart_grandtotal_qty_span'] 				= 0;
		$data['cart_grandtotal_weight'] 				= 0;
		$data['cart_grandtotal_weight_span'] 			= convertGrToKg($data['cart_grandtotal_weight']);
        $data['cart_total_price']      					= 0;
		$data['cart_total_price_span'] 					= convertRp($data['cart_total_price']);
		$data['cart_subgrandtotal_price']      			= 0;
		$data['cart_subgrandtotal_price_span'] 			= convertRp($data['cart_subgrandtotal_price']);
		$data['cart_grandtotal_ppn_price']      		= 0;
		$data['cart_grandtotal_ppn_price_span'] 		= convertRp($data['cart_grandtotal_ppn_price']);
		$data['cart_grandtotal_shipping_price']      	= 0;
		$data['cart_grandtotal_shipping_price_span'] 	= convertRp($data['cart_grandtotal_shipping_price']);
		$data['cart_grandtotal_price']  				= 0;
		$data['cart_grandtotal_price_span'] 			= convertRp($data['cart_grandtotal_price']);

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'load'){

			$this->store_id 	= 1;
			if(isset($_POST['reseller_orders']) && $_POST['reseller_orders'] == true){
				$this->store_id = get_user_store($this->user_id);
			}

			$cur_cart = $this->db->order_by('temp_orders_date','desc')->get_where("mt_temp_orders",array(
				'member_type' 		=> 1,
				'member_id' 		=> $this->user_id
			))->result();
			if(count($cur_cart) == 0){
				$data['cart_list'] = '<div>Keranjang belanja masih kosong.</div>';
				$data['cart_table'] = '<tr><td colspan="8">Keranjang belanja masih kosong.</td></tr>';
			} else {
				$data['cart_count'] = count($cur_cart);
				foreach ($cur_cart as $k => $v){
					$r = $this->db->get_where("mt_product",array(
						'product_id' 		=> $v->product_id,
						'product_istrash' 	=> '0'
					),1,0)->row();
					if(count($r) > 0){
						$data['cart_id'] 	= $r->product_id;
						$data['cart_name'] 	= $r->product_name;
						$data['cart_image'] = get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));
						$data['cart_link'] 	= base_url().'product/'.$r->url;

						$detail 			= get_product_detail($r->product_id);
						$data['cart_id_detail'] 	= $detail->product_detail_id;
						$data['cart_weight'] 		= $detail->product_weight;
						$data['cart_weight_span'] 	= convertGrToKg($data['cart_weight']);

						$arr_detail_item = array();
						$data['product_detail_item'] = json_decode($v->product_detail_item);
						foreach ($data['product_detail_item'] as $key => $value) {
							$arr_detail_item[$value->id] = $value->qty;
						}

						$data['cart_qty_id']  	= '';
						$data['cart_qty_qty'] 	= '';
						$data['cart_variant'] 	= '';
						$data['cart_total_qty'] = 0;
						$data['product_stock_detail'] = json_decode($detail->product_stock_detail);
						foreach ($data['product_stock_detail'] as $key => $value) {
							if(array_key_exists($value->id, $arr_detail_item)){
								$data['cart_qty_id']   .= '<p class="mb-0">'.$value->name.'</p>';
								$data['cart_qty_qty']  .= '<p class="mb-0">'.$arr_detail_item[$value->id].'</p>';
								$data['cart_variant']  .= '<li><span class="label label-dark">'.$value->name.' = '.$arr_detail_item[$value->id].' pcs</span></li>';
								$data['cart_total_qty'] 	 = $data['cart_total_qty'] + $arr_detail_item[$value->id];
								$data['cart_total_qty_span'] = $data['cart_total_qty'];
							}
						}
						$data['cart_grandtotal_qty']      = $data['cart_grandtotal_qty'] + $data['cart_total_qty'];
						$data['cart_grandtotal_qty_span'] = $data['cart_grandtotal_qty'];

						$data['cart_total_weight'] 			 = $data['cart_weight'] * $data['cart_total_qty'];
						$data['cart_total_weight_span'] 	 = convertGrToKg($data['cart_total_weight']);
						$data['cart_grandtotal_weight']      = $data['cart_grandtotal_weight'] + $data['cart_total_weight'];
						$data['cart_grandtotal_weight_span'] = convertGrToKg($data['cart_grandtotal_weight']);

	                    if($this->store_id == 1){
	                    	$data['cart_price']      = $detail->product_price_sale;
							$data['cart_price_span'] = convertRP($data['cart_price']);
							if($detail->product_price_discount != '0'){
								$data['cart_price']      = $detail->product_price_discount;
								$data['cart_price_span'] = convertRP($data['cart_price']);
							}
		                    if($detail->product_price_grosir != ''){
		                        $data['product_price_grosir'] = json_decode($detail->product_price_grosir);
		                        foreach ($data['product_price_grosir'] as $key => $value){
		                        	if($value->qty <= $data['cart_total_qty']){
			                            $data['cart_price']      = $value->price;
			                            $data['cart_price_span'] = convertRp($data['cart_price']);
			                        }
		                        }
		                    }
		                } else {
                            $data['cart_price']      = get_product_reseller_price($this->store_id, $r->product_id);
                            $data['cart_price_span'] = convertRp($data['cart_price']);
		                }

	                    $data['cart_total_price']      = $data['cart_price'] * $data['cart_total_qty'];
						$data['cart_total_price_span'] = convertRp($data['cart_total_price']);

						$data['cart_subgrandtotal_price']      = $data['cart_subgrandtotal_price'] + $data['cart_total_price'];
						$data['cart_subgrandtotal_price_span'] = convertRp($data['cart_subgrandtotal_price']);

						$data['cart_grandtotal_ppn_price']      = ($data['cart_subgrandtotal_price'] * $data['cart_ppn_price'])/100;
						$data['cart_grandtotal_ppn_price_span'] = convertRp($data['cart_grandtotal_ppn_price']);

						// $data['cart_grandtotal_shipping_price']      = $data['cart_shipping_price'] * $data['cart_grandtotal_weight_span']; // Jika dihitung per 1kg
						$data['cart_grandtotal_shipping_price']      = $data['cart_shipping_price'];

						$data['cart_grandtotal_shipping_price_span'] = convertRp($data['cart_grandtotal_shipping_price']);

						// $data['cart_voucher_price']      = 5000;
						if($data['cart_voucher_price'] != 0){
							$data['cart_voucher_price_span'] = '-'.convertRp($data['cart_voucher_price']);
						}

						$data['cart_grandtotal_price']  = (($data['cart_subgrandtotal_price'] + $data['cart_grandtotal_ppn_price'] + $data['cart_grandtotal_shipping_price']) - $data['cart_voucher_price']);
						$data['cart_grandtotal_price_span'] = convertRp($data['cart_grandtotal_price']);

						// GENERATE OUTPUT
						$data['cart_list'] .= '
						<div class="cart-list-item">
	                        <div class="cart-list-image" style="background-image: url('.$data['cart_image'].');"></div>

	                        <div class="cart-list-content">
	                            <p class="no-margin no-padding"><strong>'.$data['cart_name'].'</strong></p>
	                            <h6 class="no-margin no-padding mb-5">'.$data['cart_price_span'].' x '.$data['cart_total_qty'].' = <strong>'.$data['cart_total_price_span'].'</strong></h6>
	                            <h6 class="no-margin no-padding">Varian:</h6>
	                            <ul class="list-inline mb-0">
		                            '.$data['cart_variant'].'
	                            </ul>
	                            <div class="cart-list-action">
		                            <div class="btn btn-info btn-xs btn-popup-product" data-id="'.$data['cart_id'].'" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></div>
		                            <div class="btn btn-danger btn-xs cart-remove-btn" data-id="'.$data['cart_id'].'" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i></div>
	                            </div>
	                        </div>
	                    </div>';

						$data['cart_table'] .= '
								<tr>
	                                <td><img src="'.$data['cart_image'].'" class="avatar"></td>
	                                <td>'.$data['cart_name'].'</td>
	                                <td class="nobr text-left">'.$data['cart_qty_id'].'</td>
	                                <td class="nobr text-center">'.$data['cart_qty_qty'].'</td>
	                                <td class="nobr text-center">'.$data['cart_total_qty'].'</td>
	                                <td class="nobr text-right">'.$data['cart_price_span'].'</td>
	                                <td class="nobr text-right">'.$data['cart_total_price_span'].'</td>
	                                <td class="nobr text-center">
			                            <div class="btn btn-info btn-xs btn-popup-product" data-id="'.$data['cart_id'].'" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></div>
			                            <div class="btn btn-danger btn-xs cart-remove-btn" data-id="'.$data['cart_id'].'" data-toggle="tooltip" data-original-title="Hapus"><i class="fa fa-times"></i></div>
	                                </td>
	                            </tr>';

	                    $data['cart_temp_item'] .= '
	                    		<div class="cart-item cart-item-'.$data['cart_id'].'" data-id="'.$data['cart_id'].'">
						            <input type="hidden" name="cart-total-weight" value="'.$data['cart_total_weight'].'" />
						            <input type="hidden" name="cart-total-qty" value="'.$data['cart_total_qty'].'" />
						            <input type="hidden" name="cart-total-price" value="'.$data['cart_total_price'].'" />
						        </div>';

					}
				}
			}

			die(json_encode($data));
			exit();
		}
	}

	// function cart_check_before_submit(){
	// 	$data = array();
	// 	$data['cart_err'] 	= false;
	// 	$data['cart_msg'] 	= '';
	// 	$data['cart_err_stock'] = false;
	// 	$data['cart_msg_stock'] = array();

	// 	if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'check'){
	// 		$cur_cart = isset($this->jCfg['cart'])?$this->jCfg['cart']:'';
	// 		if($cur_cart == '' || count($cur_cart) <= 0){
	// 			$data['cart_err']   = true;
	// 			$data['cart_msg'][] = 'Keranjang belanja masih kosong.';
	// 		} else {
	// 			foreach ($cur_cart as $k => $v){
	// 				$r = $this->db->get_where("mt_product",array(
	// 					'product_id' 		=> $v['cart-id'],
	// 					'product_istrash' 	=> '0'
	// 				),1,0)->row();
	// 				if(count($r) > 0){
	// 					$detail = get_product_detail($r->product_id);
	// 					if($detail->product_status_id == '1'){
	// 						$data['product_stock_detail'] = json_decode($detail->product_stock_detail);
	// 						foreach ($data['product_stock_detail'] as $key => $value) {
	// 							if(array_key_exists($value->id, $v['cart-qty'])){
	// 								if($v['cart-qty'][$value->id] > $value->qty){
	// 									$data['cart_err'] = true;
	// 									$data['cart_err_stock'] = true;
	// 									$data['cart_msg_stock'][] = array(
	// 																	'id' 	=> $value->id,
	// 																	'qty' 	=> $value->qty,
	// 																	'msg' 	=> 'Produk '.$r->product_name.' No '.$value->id.' stok yang tersedia hanya <strong>'.$value->qty.'</strong>.'
	// 																);
	// 								}
	// 							}
	// 						}
	// 					} else {
	// 						$data['cart_err']   = true;
	// 						$data['cart_msg'][] = get_product_status($detail->product_status_id)->product_status_name;
	// 					}
	// 				} else {
	// 					$data['cart_err']   = true;
	// 					$data['cart_msg'][] = 'Produk tidak ditemukan.';
	// 				}
	// 			}
	// 		}
	// 		die(json_encode($data));
	// 		exit();
	// 	}
	// }

	// BELUM UPDATE
	function cart_remove(){
		$data = array();
		$data['cart_err'] 	= true;
		$data['cart_msg'] 	= '';
		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'remove'){
			$thisVal      = dbClean(trim($_POST['thisVal']));
			if(trim($thisVal) != '' && $this->user_id != ''){
				$del = $this->db->delete("mt_temp_orders",array(
					'member_type' 	 => 1,
					'member_id' 	 => $this->user_id,
					'product_id'	 => $thisVal
				));
				if($del){
					$data['cart_err'] 	= false;
					$data['cart_msg'] 	= 'Berhasil menghapus dari keranjang!';
				} else {
					$data['cart_err'] 	= true;
					$data['cart_msg'] 	= 'Produk tidak ditemukan.';
				}
			} else {
				$data['cart_err'] 	= true;
				$data['cart_msg'] 	= 'Produk tidak ditemukan.';
			}

			die(json_encode($data));
			exit();
		}
	}

	// BELUM UPDATE
	function cart_empty(){
		$data = array();
		$data['cart_err'] 	= true;
		$data['cart_msg'] 	= '';
		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'remove'){
			$thisVal      = dbClean(trim($_POST['thisVal']));
			if($this->user_id != ''){
				$this->db->delete("mt_temp_orders",array(
					'member_type' 	=> 1,
					'member_id' 	=> $this->user_id
				));

				$data['cart_err'] 	= false;
				$data['cart_msg'] 	= 'Berhasil kosongkan keranjang belanja!';
			} else {
				$data['cart_err'] 	= true;
				$data['cart_msg'] 	= 'Produk tidak ditemukan.';
			}

			die(json_encode($data));
			exit();
		}
	}

    // SUDAH UPDATE
	function form_payment(){
		$data = array();
		$data['err'] 	= true;
		$data['msg'] 	= array();
		$data['result'] = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'payment' ){
            $data['err']    = false;
            $lastsaldo = get_saldo_store($this->store_id);

            $i = 0;
            $payment_method = '';
            $arr_payment_method = get_payment_method();
            foreach ($arr_payment_method as $k => $v) {
                $payment_method .= '<option value="" disabled>'.$v->payment_method_name.'</option>';

                $arr_payment_method2 = get_payment_method($v->payment_method_id, true);
                foreach ($arr_payment_method2 as $k2 => $v2) {
                    $selected = (($i=='0')||($v2->payment_method_id==$r->payment_method_id)?'selected':'');
                    $name_account = '';
                    if($v2->payment_method_name_account != ''){
                        $name_account = ' ('.$v2->payment_method_name_account.' - '.$v2->payment_method_no_account.')';
                    }
                    $payment_method .= '<option value="'.$v2->payment_method_id.'" '.$selected.'>&nbsp; &nbsp; &nbsp;'.$v2->payment_method_name.$name_account.'</option>';
                    $i += 1;
                }
            }

			$data['content'] = '
			<form class="form_save_payment" action="'.$this->own_link.'/save_payment" method="post" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <legend>Form Pembayaran</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Total Bayar</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="payment_price" value="'.$lastsaldo.'" class="form-control moneyRp_masking" maxlength="23" disabled>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Metode Bayar</label>
                        <div class="col-sm-9">
                        	<select name="payment_method" class="form-control" required>
	                        	<option value="" selected disabled>--- Pilih ---</option>
	                        	'.$payment_method.'
                        	</select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Jumlah Bayar</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="payment_price" value="" class="form-control moneyRp_masking" maxlength="23" required>

                            </div>
                        </div>
                        <div class="payment_status"></div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
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

    // SUDAH UPDATE
	function save_payment(){
		$data = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		$data['href'] 	= '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $payment_price  = dbClean($_POST['payment_price']);
            $payment_method = dbClean($_POST['payment_method']);

            $lastsaldo  = get_saldo_store($this->store_id);
            $totalPrice = convertRpToInt($payment_price);

            $data2 = array(
                'user_id'           => $this->user_id,
                'store_id'          => $this->store_id,
                'store_orders_id'   => 0,
                'payment_method_id' => $payment_method,
                'payment_saldo'     => $lastsaldo,
                'payment_price'     => $totalPrice,
                'payment_noted'     => NULL,
                'payment_type'      => 2,
                'payment_accept'    => 0,
                'payment_date'      => timestamp(),
                'payment_istrash'   => 0
            );

            $this->DATA->table="mt_store_payment";
            $a2 = $this->_save_master(
                $data2,
                array(
                    'store_payment_id' => ''
                ),
                ''
            );
            if($a2['id']){
                if($lastsaldo != ""){
                    // $saldo = ($lastsaldo - $totalPrice); // Pemesanan
                    $saldo = ($lastsaldo + $totalPrice); // Pembayaran
                    $this->db->update("mt_store",array("store_saldo"=>$saldo),array("store_id"=>$this->store_id));
                } else {
                    writeLog(array(
                        'log_user_type'     => "1", // Admin
                        'log_user_id'       => $this->user_id,
                        'log_role'          => NULL,
                        'log_type'          => "5", // Pemesanan Reseller
                        'log_detail_id'     => $a2['id'],
                        'log_detail_item'   => NULL,
                        'log_detail_qty'    => $totalPrice,
                        'log_title_id'      => "36", // Gagal Update Saldo Pembayaran
                        'log_desc'          => NULL,
                        'log_status'        => "1"
                    ));
                }
            } else {
                writeLog(array(
                    'log_user_type'     => "1", // Admin
                    'log_user_id'       => $this->user_id,
                    'log_role'          => NULL,
                    'log_type'          => "5", // Pemesanan Reseller
                    'log_detail_id'     => $a2['id'],
                    'log_detail_item'   => NULL,
                    'log_detail_qty'    => $totalPrice,
                    'log_title_id'      => "35", // Gagal Simpan Saldo Pembayaran
                    'log_desc'          => NULL,
                    'log_status'        => "1"
                ));
            }

            writeLog(array(
                'log_user_type'     => "1", // Admin
                'log_user_id'       => $this->user_id,
                'log_role'          => NULL,
                'log_type'          => "5", // Pemesanan Reseller
                'log_detail_id'     => $a2['id'],
                'log_detail_item'   => NULL,
                'log_detail_qty'    => $totalPrice,
                'log_title_id'      => "34", // Berhasil Melakukan Pembayaran
                'log_desc'          => NULL,
                'log_status'        => "0"
            ));

            $data['err']    = false;
            $data['msg']    = "Berhasil simpan pembayaran";
		}

		die(json_encode($data));
		exit();
	}


	// function cart_empty(){
	// 	unset($this->jCfg['cart']);
	// 	unset($this->jCfg['cart_shipping_price']);
	// 	unset($this->jCfg['cart_voucher_price']);
	// 	$this->_releaseSession();

	// 	redirect($this->own_link."/add?msg=".urlencode('Empty Cart success')."&type_msg=success");
	// }

	// function change_status($id='',$val=''){
		// $data = array();
		// $data['msg'] = '';
	// 	$id  = dbClean(trim($id));
	// 	$val = dbClean(trim($val));
	// 	if(trim($id) != ''){
	// 		if($val == 'true'){ $val = '1'; } else { $val = '0'; }
	// 		$this->db->update("mt_orders",array("member_status"=>$val),array("member_id"=>$id));
	// 		$data['msg'] = 'success';
	// 	}

		// die(json_encode($data));
		// exit();
	// }

	// function check_form(){
	// 	$data = array();
	// 	$data['err'] = true;
	// 	$data['msg'] = '';
	// 	if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_form' ){
	// 		$thisVal       = dbClean(trim($_POST['thisVal']));
	// 		$thisChkId     = dbClean(trim($_POST['thisChkId']));
	// 		$thisChkParent = dbClean(trim($_POST['thisChkParent']));
	// 		$thisChkRel    = dbClean(trim($_POST['thisChkRel']));

	// 		$this->DATA->table="mt_orders";
	// 		if(trim($thisVal)!=''){
	// 			if(trim($thisChkId)!=''){
	// 				$this->data_form = $this->DATA->data_id(array(
	// 					$thisChkRel	   => $thisVal,
	// 					'orders_id !=' => $thisChkId
	// 				));
	// 			} else {
	// 				$this->data_form = $this->DATA->data_id(array(
	// 					$thisChkRel	=> $thisVal
	// 				));
	// 			}
	// 			if(empty($this->data_form->$thisChkRel)){
	// 				$data['err'] = false;
	// 				$data['msg'] = '';
	// 				// if(($thisChkRel=='orders_source_invoice')&&(checkIsRoute($thisVal))){
	// 				// 	$data['err'] = true;
	// 				// 	$data['msg'] = 'Data sudah ada...';
	// 				// }
	// 			} else {
	// 				$data['err'] = true;
	// 				$data['msg'] = 'Data sudah ada...';
	// 			}
	// 		}
	// 	}

	// 	die(json_encode($data));
	// 	exit();
	// }

}
