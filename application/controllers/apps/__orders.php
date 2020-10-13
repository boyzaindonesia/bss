<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class orders extends FrontController {
	var $cur_menu = '';

	function __construct()  
	{
		parent::__construct(); 

		$this->DATA->table = "mt_orders";
		$this->load->model("mdl_orders","M");

		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
	        header('Access-Control-Max-Age: 86400');    // cache for 1 day
	    }

	    // Access-Control headers are received during OPTIONS requests
	    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
	    		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
	    		header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	    	exit(0);
	    }

	}

	function index(){
	    $error 	= true;
	    $msg 	= '';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'getdata'){
				$par_filter = array();
				foreach ($request as $key => $value) {
					$par_filter[$key] = $value;
				}
				$this->data_table = $this->M->data_orders_front($par_filter);
				$rows['orders']  = $this->data_table;
			}
		}
		
		$rows['error'] = $error;
		$rows['msg']   = $msg;
		die(json_encode($rows));
		exit();
	}

	function cart_add_temp_product(){
		$error 	= true;
	    $msg 	= 'Please check your connection!';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'save'){
				$par_filter = array();
				foreach ($request as $key => $value) {
					$par_filter[$key] = $value;
				}

				$data = array(
					'member_type'			=> $par_filter['member_type'],
					'member_id'				=> $par_filter['member_id'],
					'product_id'			=> $par_filter['product_id'],
					'product_detail_item'	=> $par_filter['product_detail_item'],
					'temp_orders_date'		=> timestamp(),
					'ip_address'			=> $_SERVER['REMOTE_ADDR'],
					'user_agent'			=> $_SERVER['HTTP_USER_AGENT']
				);

				$this->DATA->table="mt_temp_orders";
				$a = $this->_save_master( 
					$data,
					array(
						'temp_orders_id' => $par_filter['temp_orders_id']
					),
					$par_filter['temp_orders_id']			
				);

				$id = $a['id'];
				if($id != ''){
					$error 	= false;
					$msg    = 'Berhasil menambahkan ke keranjang';
				} else {
					$error 	= true;
					$msg    = 'Gagal menambahkan ke keranjang';
				}

				$rows['cart']  = $this->M->cart_loaded_apps($par_filter);
			}
		}
		
		$rows['error'] = $error;
		$rows['msg']   = $msg;
		die(json_encode($rows));
		exit();
	}

	function cart_load(){
		$error 	= true;
	    $msg 	= 'Please check your connection!';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'getdata'){
				$par_filter = array();
				foreach ($request as $key => $value) {
					$par_filter[$key] = $value;
				}

				$rows['cart'] = $this->M->cart_loaded_apps($par_filter);
				$error = false;
				$msg   = 'Sukses...';
			}
		}

		$rows['error'] = $error;
		$rows['msg']   = $msg;
		die(json_encode($rows));
		exit();
	}

	function cart_remove(){
		$error 	= true;
	    $msg 	= 'Please check your connection!';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'remove'){
				$par_filter = array();
				foreach ($request as $key => $value) {
					$par_filter[$key] = $value;
				}

				$m = $this->db->delete("mt_temp_orders",array('temp_orders_id' => $par_filter['temp_orders_id']));
				if($m){
					$rows['cart'] = $this->M->cart_loaded_apps($par_filter);
					$error = false;
					$msg   = 'Sukses...';
				} else {
					$error 	= true;
				    $msg 	= 'Produk tidak ditemukan didalam keranjang!';
				}
			}
		}

		$rows['error'] = $error;
		$rows['msg']   = $msg;
		die(json_encode($rows));
		exit();
	}

	function cart_checkout(){
		$error 	= false;
	    $msg 	= 'Please check your connection!';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'save'){
				$par_filter = array();
				foreach ($request as $key => $value) {
					$par_filter[$key] = $value;
				}

				$rows['cart']  = $this->M->cart_loaded_apps($par_filter);
				$cart_products = $rows['cart']['products'];
				$cart_details  = $rows['cart']['details'];
				if(count($cart_products) <= 0){
					$error 	= true;
				    $msg 	= 'Keranjang belanja masih kosong.';
				} else {

					$set_shipping = set_shipping();
					$data['cart_ppn_price'] 			= $set_shipping['ppn'];
					$data['cart_grandtotal_price_buy'] 	= 0;
					$data['cart_subgrandtotal_price'] 	= 0;
					$data['cart_grandtotal_weight']     = 0;
					$data['cart_grandtotal_qty'] 		= 0;
					$data['cart_shipping_price'] 		= convertRpToInt($par_filter['orders_shipping_price']);
					$data['cart_voucher_price'] 		= 0;
					$data['cart_voucher_code']  		= '';

					$data['temp_orders_detail'] = array();
					foreach ($cart_products as $k => $v){
						$r = $this->db->get_where("mt_product",array(
							'product_id' 		=> $v['product_id'],
							'product_istrash' 	=> '0'
						),1,0)->row();
						if(count($r) > 0){
							$data['product_id']   = $r->product_id;
							$data['product_sold'] = $r->product_sold;
							$data['temp_orders_id'] = $v['temp_orders_id'];

							$detail 			= get_product_detail($r->product_id)[0];
							$data['cart_id_detail'] 	= $detail->product_detail_id;
							$data['product_price_buy'] 	= $detail->product_price_buy;
							$data['cart_weight'] 		= $detail->product_weight;

							if($detail->product_status_id == '1'){
								$data['product_stock'] 	  = $detail->product_stock;

								$data['product_stock_detail'] = json_decode($detail->product_stock_detail);

								$data['cart_total_qty']   = 0;
								$data['cart_detail_item'] = array();
								$data['product_detail_item'] = $v['product_detail_item'];
								foreach ($data['product_detail_item'] as $key2 => $val2) {
									$data['cart_total_qty'] = $data['cart_total_qty'] + $val2->qty;
									$data['cart_detail_item'][] = array('id' 	=> $val2->id, 
																  		'qty'	=> $val2->qty
																	);
								}

								// $rows['test_console'] = $v['product_detail_item'];


								$data['cart_grandtotal_qty']    = $data['cart_grandtotal_qty'] + $data['cart_total_qty'];
								$data['cart_total_weight'] 		= $data['cart_weight'] * $data['cart_total_qty'];
								$data['cart_grandtotal_weight'] = $data['cart_grandtotal_weight'] + $data['cart_total_weight'];

								$data['cart_price']      = $detail->product_price_sale;
								if($detail->product_price_discount != '0'){
									$data['cart_price']  = $detail->product_price_discount;
								}
			                    if($detail->product_price_grosir != ''){
			                        $data['product_price_grosir'] = json_decode($detail->product_price_grosir);
			                        foreach ($data['product_price_grosir'] as $key => $value){ 
			                        	if($value->qty <= $data['cart_total_qty']){
				                            $data['cart_price']  = $value->price;
				                        }
			                        }
			                    }
			                    $data['cart_total_price']      		= $data['cart_price'] * $data['cart_total_qty'];
								$data['cart_subgrandtotal_price']   = $data['cart_subgrandtotal_price'] + $data['cart_total_price'];
								$data['cart_grandtotal_ppn_price']  = ($data['cart_subgrandtotal_price'] * $data['cart_ppn_price'])/100;
								// $data['cart_grandtotal_shipping_price'] = $data['cart_shipping_price'] * $data['cart_grandtotal_weight_span']; // Jika dihitung per 1kg
								$data['cart_grandtotal_shipping_price'] = $data['cart_shipping_price'];

								$data['cart_grandtotal_price']  = (($data['cart_subgrandtotal_price'] + $data['cart_grandtotal_ppn_price'] + $data['cart_grandtotal_shipping_price']) - $data['cart_voucher_price']);

								$data['cart_grandtotal_price_buy'] = $data['cart_grandtotal_price_buy'] + ($data['product_price_buy'] * $data['cart_total_qty']);

								$data['temp_orders_detail'][] = array('temp_orders_id'		=> $data['temp_orders_id'],
															  		  'product_id'			=> $data['product_id'],
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


							} else {
								$data['cart_err']   = true;
								$data['cart_msg'][] = get_product_status($detail->product_status_id)[0]->product_status_name;
							}
						} else {
							$data['cart_err']   = true;
							$data['cart_msg'][] = 'Produk tidak ditemukan.';
						}
					}

					// SAVE 
					if($error == false){

						// SAVE MT_ORDERS
						$data1 = array(
							'orders_status'				=> $par_filter['orders_status'],
							'orders_source_id'			=> $par_filter['orders_source_id'],
							'orders_source_invoice'		=> $par_filter['orders_source_invoice'],
							'orders_noted'				=> '',
							'orders_price_buy_total'	=> $data['cart_grandtotal_price_buy'],
							'orders_price_shipping'		=> $data['cart_grandtotal_shipping_price'],
							'orders_price_ppn'			=> $data['cart_grandtotal_ppn_price'],
							'orders_price_grand_total'	=> $data['cart_grandtotal_price'],
							'orders_voucher_price'		=> $data['cart_voucher_price'],
							'orders_voucher_code'		=> $data['cart_voucher_code'],
							'orders_feedback'			=> 1,
							'ip_address'				=> $_SERVER['REMOTE_ADDR'],
							'user_agent'				=> $_SERVER['HTTP_USER_AGENT']
						);

						if ($par_filter['orders_id'] == ""){
							$create_orders_code 	 = create_orders_code();
							$data1['orders_code']    = $create_orders_code['orders_code'];
							$data1['orders_invoice'] = $create_orders_code['orders_invoice'];
							$data1['member_type']    = $par_filter['member_type'];
							$data1['member_id']      = $par_filter['member_id'];

							$data1['notify']  		= 0;
							$data1['date_notify']   = timestamp();
							$data1['orders_date']   = timestamp();
						}

						$this->DATA->table="mt_orders";
						$a = $this->_save_master( 
							$data1,
							array(
								'orders_id' => $par_filter['orders_id']
							),
							$par_filter['orders_id']			
						);
						
						$id = $a['id'];
						if($id != ''){

							// $rows['cart_products'] = $data['temp_orders_detail'];
							// SAVE MT_ORDERS_DETAIL
							foreach ($data['temp_orders_detail'] as $key => $value) {
								$data2['orders_id'] 			= $id;
								$data2['product_id'] 			= $value['product_id'];
								$data2['product_price_buy'] 	= $value['product_price_buy'];
								$data2['orders_detail_price'] 	= $value['orders_detail_price'];
								$data2['orders_detail_qty'] 	= $value['orders_detail_qty'];
								$data2['orders_detail_weight'] 	= $value['orders_detail_weight'];
								$data2['orders_detail_item'] 	= $value['orders_detail_item'];

								$this->DATA->table="mt_orders_detail";
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
									$data3['product_stock'] = ($value['product_stock'] - $value['orders_detail_qty']);
									// if($data3['product_stock'] <= 0){ $data3['product_status_id'] = 3; }

									$arr_detail_item 	= array();
									$orders_detail_item = json_decode($value['orders_detail_item']);
									foreach ($orders_detail_item as $key2 => $value2) {
										$arr_detail_item[$value2->id] = $value2->qty;
									}

									$arr_stock = array();
									$product_stock_detail = json_decode($value['product_stock_detail']);
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
									$this->db->update("mt_product_detail",$data3,array("product_detail_id"=>$value['product_detail_id']));

									$product_sold = $value['product_sold'] + $value['orders_detail_qty'];
									$this->db->update("mt_product",array("product_sold"=>$product_sold),array("product_id"=>$value['product_id']));

									$this->db->delete("mt_temp_orders",array('temp_orders_id' => $value['temp_orders_id']));
								}
							} 

							// SAVE MT_ORDERS_PAYMENT
							$data4 = array(
								'orders_payment_method'			=> '1',
								'orders_payment_name_account'	=> '',
								'orders_payment_no_account'		=> '',
								'orders_payment_price'			=> $data['cart_grandtotal_price'],
								'orders_payment_grand_total'	=> $data['cart_grandtotal_price'],
								'orders_payment_status'			=> 3
							);

							$orders_payment_id = '';
							if ($par_filter['orders_id'] != ""){
								$orders_payment = get_detail_orders_payment($par_filter['orders_id']);
								$orders_payment_id = $orders_payment->orders_payment_id;
							} else {
								$create_payment_code = create_payment_code();
								$data4['orders_payment_code'] 	= $create_payment_code['payment_code'];
								$data4['orders_id'] 			= $id;
								$data4['orders_payment_date'] 	= timestamp();
								$data4['notify'] 				= 0;
								$data4['date_notify'] 			= timestamp();
							}
							$this->DATA->table="mt_orders_payment";
							$a4 = $this->_save_master( 
								$data4,
								array(
									'orders_payment_id' => $orders_payment_id
								),
								$orders_payment_id		
							);
							$orders_payment_id = $a4['id'];

							// SAVE MT_ORDERS_SHIPPING
							$orders_shipping_status = NULL;
							if($data1['orders_status'] == 7 || $data1['orders_status'] == 8){
								$orders_shipping_status = 7;
							} else if($data1['orders_status'] == 6){
								$orders_shipping_status = 6;
							} else if($data1['orders_status'] == 5){
								$orders_shipping_status = 5;
							}
							
							$data5 = array(
								'orders_shipping_status'		=> $orders_shipping_status,
								'orders_shipping_method'		=> '1',
								'orders_shipping_name'			=> dbClean(ubah_huruf_awal($par_filter['orders_shipping_name'])),
								'orders_shipping_email'			=> dbClean($par_filter['orders_shipping_email']),
								'orders_shipping_address'		=> dbClean(ubah_huruf_awal($par_filter['orders_shipping_address'])),
								'orders_shipping_city'			=> dbClean($par_filter['city']),
								'orders_shipping_province'		=> dbClean($par_filter['province']),
								'orders_shipping_postal_code'	=> dbClean($par_filter['orders_shipping_postal_code']),
								'orders_shipping_phone'			=> dbClean($par_filter['orders_shipping_phone']),
								'orders_shipping_courier'		=> dbClean($par_filter['orders_shipping_courier']),
								'orders_shipping_resi'			=> dbClean(strtoupper($par_filter['orders_shipping_resi'])),
								'orders_shipping_price'			=> $data['cart_grandtotal_shipping_price']
							);

							$orders_shipping_id = '';
							if (dbClean($par_filter['orders_id']) != ""){
								$orders_shipping = get_detail_orders_shipping($par_filter['orders_id']);
								$orders_shipping_id = $orders_shipping->orders_shipping_id;
							} else {
								$data5['orders_id'] 			= $id;
								$data5['orders_shipping_date'] 	= timestamp();
								$data5['notify'] 				= 0;
								$data5['date_notify'] 			= timestamp();
							}

							$this->DATA->table="mt_orders_shipping";
							$a5 = $this->_save_master( 
								$data5,
								array(
									'orders_shipping_id' => $orders_shipping_id
								),
								$orders_shipping_id		
							);
							$orders_shipping_id = $a5['id'];

							// SAVE MT_ORDERS_TIMESTAMP
							if (dbClean($par_filter['orders_id']) == ""){
								$arr_orders_timestamp = array();
								$get_orders_status = get_orders_status();
								foreach ($get_orders_status as $key6 => $value6){
									if($data1['orders_status'] <= 8){
										if($value6['id'] <= $data1['orders_status']){
											$arr_orders_timestamp[] = array('id' 		=> $value6['id'], 
																		    'timestamp' => timestamp()
																	  );
										}
									} else {
										$arr_orders_status = array(1,9);
										if($data1['orders_status'] == 10){ $arr_orders_status = array(1,2,10); }
										if(in_array($value6['id'], $arr_orders_status)){
											$arr_orders_timestamp[] = array('id' 		=> $value6['id'], 
																		    'timestamp' => timestamp()
																	  );
										}
									}
								}

								$data5 = array(
									'orders_id'					=> $id,
									'orders_timestamp_desc'		=> json_encode($arr_orders_timestamp)
								);
								$this->DATA->table="mt_orders_timestamp";
								$a5 = $this->_save_master( 
									$data5,
									array(
										'orders_timestamp_id' => ''
									),
									''		
								);
								$orders_timestamp_id = $a5['id'];
							}
						}

						$error = false;
						$msg   = 'Checkout berhasil...';

						$rows['cart'] = $this->M->cart_loaded_apps($par_filter);

						if($data1['orders_status'] == '8'){
							insert_saldo(array(
								'orders_source_id'	=> $data1['orders_source_id'],
								'orders_id'			=> $id,
								'saldo_price'		=> $data1['orders_price_grand_total'],
								'saldo_noted'		=> 'Remit untuk transaksi #'.$data1['orders_code'],
								'saldo_type'		=> 1
							));
						}
					} else {
						// $swal_msg = '';
						// foreach ($data['cart_msg'] as $key => $value){ $swal_msg .= '<li>'.urlencode($value).'</li>'; }
						// foreach ($data['cart_msg_stock'] as $key => $value){ $swal_msg .= '<li>'.urlencode($value['msg']).'</li>'; }
						// redirect($this->own_link."/add/?msg=".urlencode('Save data order failed')."&type_msg=error&swal_msg=".$swal_msg."&swal_type=error&swal_title=Error!");
					}


				}

			}
		}

		$rows['error'] = $error;
		$rows['msg']   = $msg;
		die(json_encode($rows));
		exit();
	}
}
