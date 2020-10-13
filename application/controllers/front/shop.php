<?php
include_once(APPPATH."libraries/FrontController.php");
class Shop extends FrontController {

	function __construct()
	{
		parent::__construct();
		$this->load->model("mdl_product","M");

		$this->load->library('Mobile_Detect');
	    // $detect = new Mobile_Detect();
	    // if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
	    //     header("Location: /mobile"); exit;
	    // } else {
	    //     header("Location: /desktop"); exit;
	    // }

		checkIsBlacklistOnLoad();

	}

	function index(){
		$data = '';

	}

	function cart_store(){
		$this->page     = 'Lihat Keranjangku';
		$this->cur_menu = 'cart_store';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'cart';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		$data = '';
		$data['menu'] = "cart_store";
		$data['menu_id'] = "";

		$this->_v('cart_store',$data);

	}

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

			$r = $this->db->get_where("mt_product",array(
				'product_id' 		=> $new_cart_id,
				'product_istrash' 	=> '0'
			),1,0)->row();
			if(count($r) > 0){
				$detail = get_product_detail($r->product_id)[0];

				$product_id 			= $r->product_id;
				$product_stock 			= $detail->product_stock;
				$product_stock_detail 	= json_decode($detail->product_stock_detail);

				if($detail->product_status_id == '1'){
					foreach ($product_stock_detail as $key => $value) {
						if(array_key_exists($value->id, $new_cart_qty)){
							// echo $value->id.' -> '.$value->qty.' => '.$new_cart_qty[$value->id].'<br/>';
							// if($new_cart_qty[$value->id] > $value->qty){
							// 	$data['cart_err'] = true;
							// 	$data['cart_err_stock'] = true;
							// 	$data['cart_msg_stock'][] = array(
							// 									'id' 	=> $value->id,
							// 									'qty' 	=> $value->qty,
							// 									'msg' 	=> 'Produk '.$r->product_name.' No '.$value->id.' stok yang tersedia hanya <strong>'.$value->qty.'</strong>.'
							// 								);
							// }
						}
					}

					if($data['cart_err_stock'] == false){
						$cur_cart = $this->jCfg['cart'][$new_cart_id];
						if(isset($cur_cart)){ //jika cart-id sudah ada maka hanya tambah qty saja
							// if($cart_action == 'add-update'){
							// 	$new_qty = $new_cart_qty;
							// } else if($cart_action == 'add'){
							// 	$new_qty = ($cur_cart['cart-qty'] + $new_cart_qty);
							// }
							$this->jCfg['cart'][$new_cart_id]= array(
								'cart-id'		=> $new_cart_id,
								'cart-qty'		=> $new_cart_qty
							);
							$this->_releaseSession();
						} else { //jika cart-id belum ada
							$this->jCfg['cart'][$new_cart_id]= array(
								'cart-id'		=> $new_cart_id,
								'cart-qty'		=> $new_cart_qty
							);
							$this->_releaseSession();
						}
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

	function cart_load(){
		// debugCode($this->jCfg['cart']);
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
		$data['cart_total_weight_span'] 				= convertGrToKg($data['cart_total_weight_span']);
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
			$cur_cart = isset($this->jCfg['cart'])?$this->jCfg['cart']:'';
			if($cur_cart == '' || count($cur_cart) <= 0){
				$data['cart_list'] = '<div>Belum ada keranjang</div>';
				$data['cart_table'] = '<tr><td colspan="8">Belum ada keranjang</td></tr>';
			} else {
				$data['cart_count'] = count($cur_cart);
				foreach ($cur_cart as $k => $v){
					$r = $this->db->get_where("mt_product",array(
						'product_id' 		=> $v['cart-id'],
						'product_istrash' 	=> '0'
					),1,0)->row();
					if(count($r) > 0){
						$data['cart_id'] 	= $r->product_id;
						$data['cart_name'] 	= $r->product_name;
						$data['cart_image'] = get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));
						$data['cart_link'] 	= base_url().get_url_product_category($r->product_category_id).'/'.$r->url;

						$detail 			= get_product_detail($r->product_id)[0];
						$data['cart_id_detail'] 	= $detail->product_detail_id;
						$data['cart_weight'] 		= $detail->product_weight;
						$data['cart_weight_span'] 	= convertGrToKg($data['cart_weight']);

						$data['cart_qty_id']  	= '';
						$data['cart_qty_qty'] 	= '';
						$data['cart_variant'] 	= '';
						$data['cart_total_qty'] = 0;
						$data['product_stock_detail'] = json_decode($detail->product_stock_detail);
						foreach ($data['product_stock_detail'] as $key => $value) {
							if(array_key_exists($value->id, $v['cart-qty'])){
								$data['cart_qty_id']   .= '<p class="mb-0">'.$value->name.'</p>';
								$data['cart_qty_qty']  .= '<p class="mb-0">'.$v['cart-qty'][$value->id].'</p>';
								$data['cart_variant']  .= '<li><span class="label label-dark">'.$value->name.' = '.$v['cart-qty'][$value->id].' pcs</span></li>';
								$data['cart_total_qty'] 	 = $data['cart_total_qty'] + $v['cart-qty'][$value->id];
								$data['cart_total_qty_span'] = $data['cart_total_qty'];
							}
						}
						$data['cart_grandtotal_qty']      = $data['cart_grandtotal_qty'] + $data['cart_total_qty'];
						$data['cart_grandtotal_qty_span'] = $data['cart_grandtotal_qty'];

						$data['cart_total_weight'] 			 = $data['cart_weight'] * $data['cart_total_qty'];
						$data['cart_total_weight_span'] 	 = convertGrToKg($data['cart_total_weight']);
						$data['cart_grandtotal_weight']      = $data['cart_grandtotal_weight'] + $data['cart_total_weight'];
						$data['cart_grandtotal_weight_span'] = convertGrToKg($data['cart_grandtotal_weight']);

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
	                    <div class="cart-products">
                            <div class="cart-image">
                                <a href="'.$data['cart_link'].'"><img src="'.$data['cart_image'].'" alt=""></a>
                            </div>
                            <div class="cart-product-info">
                                <a href="'.$data['cart_link'].'" class="product-name"> '.$data['cart_name'].' </a>
                                <a class="edit-product btn-popup-product modal-view" data-toggle="modal" data-target="#productModal" data-id="'.$data['cart_id'].'" data-name="'.$data['cart_name'].'" data-image="'.$data['cart_image'].'">Edit item</a>
                                <a class="remove-product cart-remove-btn" data-id="'.$data['cart_id'].'">Remove item</a>
                                <div class="price-times">
                                    <span class="quantity"><strong> '.$data['cart_total_qty'].' x</strong></span>
                                    <span class="p-price">'.$data['cart_price_span'].'</span>
                                </div>
                            </div>
                        </div>';

                        $data['cart_main'] .= '
	                    <div class="cart-main-item">
	                        <a href="'.$data['cart_link'].'" style="display:block;"><div class="cart-main-image" style="background-image: url('.$data['cart_image'].');"></div></a>
	                        <div class="cart-main-content">
	                            <h3 class="mb-5"><a href="'.$data['cart_link'].'"><strong>'.$data['cart_name'].'</strong></a></h3>
	                            <h6 class="mb-5">'.$data['cart_total_qty'].' x '.$data['cart_price_span'].' = <strong>'.$data['cart_total_price_span'].'</strong></h6>
	                            <h6 class="mb-0">Varian:</h6>
	                            <ul class="list-inline mb-0">
		                            '.$data['cart_variant'].'
	                            </ul>
	                            <div class="cart-main-action">
		                            <div class="btn btn-info btn-xs btn-popup-product modal-view" data-toggle="modal" data-target="#productModal" data-id="'.$data['cart_id'].'" data-name="'.$data['cart_name'].'" data-image="'.$data['cart_image'].'" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></div>
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

	function cart_remove(){
		$data = array();
		$data['cart_err'] 	= true;
		$data['cart_msg'] 	= '';
		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'remove'){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			if(trim($thisVal)!=''){
				$cur_cart = $this->jCfg['cart'][$thisVal];
				if(isset($cur_cart)){
					unset($this->jCfg['cart'][$thisVal]);
					$this->_releaseSession();
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

	function cart_empty(){
		unset($this->jCfg['cart']);
		unset($this->jCfg['cart_shipping_price']);
		unset($this->jCfg['cart_voucher_price']);
		$this->_releaseSession();

		redirect($this->own_link."/add?msg=".urlencode('Empty Cart success')."&type_msg=success");
	}

	function checkout_store(){
		$this->page     = 'Checkout';
		$this->cur_menu = 'checkout_store';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'checkout';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		$data = '';
		$data['menu'] = "checkout_store";
		$data['menu_id'] = "";

		$this->_v('checkout_store',$data);

	}

	function confirm_payment(){
		$this->page     = 'Konfirmasi Pembayaran';
		$this->cur_menu = 'confirm_payment';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		/* paging wishlist */
		// $this->per_page = 20;
		// $this->uri_segment = 2;
		// $this->data_table = $this->MM->data_member_wishlist(array(
		// 	'member_id' 			=> $this->user_login,
		// 	'limit' 				=> $this->per_page,
		// 	'offset'				=> $this->uri->segment($this->uri_segment)
		// ));

		// $data = $this->_data_front(array(
		// 	'base_url'		=> base_url().'wishlist/'
		// ));

		$this->_v("confirm_payment",$data);

	}

}
