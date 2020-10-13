<?php
include_once(APPPATH."libraries/FrontController.php");
class product extends FrontController {

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

	function _reset_product(){
		$this->jCfg['search_product'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'account_product',
			'status'	=> '',
			'order_by'  => 'product_date_push',
			'order_dir' => 'desc',
			'grid_view'	=> 'grid',
			'filter_page' => '15',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$this->page     = 'Product';
		$this->cur_menu = 'product';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$data = '';
		debugCode('a');
		// $url  = $this->uri->segment(2);

		// --- SEMUANYA DIPINDAHKAN KE CONTROLLER OTHER.PHP ---
		// // SEARCH BY AWARDS
		// $data['awards_id'] = '';
		// $awards_name = (isset($_GET['special'])?$_GET['special']:'');
		// $awards_page = ($awards_name!=''?'special='.$awards_name.'&':'');
		// if($awards_name != ''){
		// 	$m = $this->db->get_where("mt_product_awards",array(
		// 		"url"	=> $awards_name
		// 	),1,0)->row();
		// 	if(count($m) > 0){ $data['awards_id'] = $m->product_awards_id; }
		// }

		// // SEARCH BY CATEGORY
		// $data['category_id'] = '';
		// $category_title = (isset($_GET['types'])?$_GET['types']:'');
		// $category_page  = ($category_title!=''?'types='.$category_title.'&':'');
		// if($category_title != ''){
		// 	$m = $this->db->get_where("mt_product_category",array(
		// 		"url"	=> $category_title
		// 	),1,0)->row();
		// 	if(count($m) > 0){ $data['category_id'] = $m->product_category_id; }
		// }

		// // SEARCH BY TAGS
		// $data['tags_id'] = '';
		// $tags_name = (isset($_GET['tags'])?$_GET['tags']:'');
		// $tags_page = ($tags_name!=''?'tags='.$tags_name.'&':'');
		// if($tags_name != ''){
		// 	$m = $this->db->get_where("mt_product_tags",array(
		// 		"url"	=> $tags_name
		// 	),1,0)->row();
		// 	if(count($m) > 0){ $data['tags_id'] = $m->product_tags_id; }
		// }

		// $page_url = $awards_page.$category_page.$tags_page;

		// // $input_get = $this->input->get();
		// // debugCode($input_get);

		// $hal = isset($this->jCfg['search_product']['name'])?$this->jCfg['search_product']['name']:"";
		// if($hal == ''){
		// 	$this->_reset_product();
		// }

		// if($this->input->post('grid_view') && trim($this->input->post('grid_view'))!=""){
		// 	$this->jCfg['search_product']['grid_view'] = $this->input->post('grid_view');
		// 	$this->_releaseSession();
		// }

		// $order_by  = $this->jCfg['search_product']['order_by'];
		// $order_dir = $this->jCfg['search_product']['order_dir'];
		// if($this->input->post('order_by') && trim($this->input->post('order_by'))!=""){
		// 	switch ($this->input->post('order_by')) {
		// 		case 'date-desc': $order_by = 'product_date_push'; $order_dir = "desc"; break;
		// 		case 'date-asc': $order_by = 'product_date_push'; $order_dir = "asc"; break;
		// 		case 'name-desc': $order_by = 'product_name'; $order_dir = "desc"; break;
		// 		case 'name-asc': $order_by = 'product_name'; $order_dir = "asc"; break;
		// 		default: $order_by = 'product_date_push'; $order_dir = "desc"; break;
		// 	}
		// 	$this->jCfg['search_product']['order_by'] = $order_by;
		// 	$this->jCfg['search_product']['order_dir'] = $order_dir;
		// 	$this->_releaseSession();
		// }
		// if($this->input->post('filter_page') && trim($this->input->post('filter_page'))!=""){
		// 	$this->jCfg['search_product']['filter_page'] = $this->input->post('filter_page');
		// 	$this->_releaseSession();
		// }

		// /* paging produk */
		// $this->per_page = $this->jCfg['search_product']['filter_page'];
		// // $this->uri_segment = 2;
		// $pageNum      = 1;
  //       if(isset($_GET['page'])&&$_GET['page']!=''){ $pageNum = $_GET['page']; }
  //       $offset       = ($pageNum - 1) * $this->per_page;


		// $this->data_table = $this->M->data_product_front(array(
		// 	'product_awards_id' 	=> $data['awards_id'],
		// 	'product_category_id' 	=> $data['category_id'],
		// 	'product_tags_id' 		=> $data['tags_id'],
		// 	'order_by' 				=> $order_by,
		// 	'order_dir' 			=> $order_dir,
		// 	'limit' 				=> $this->per_page,
		// 	'offset'				=> $offset
		// ));

		// $data['pagination'] = $this->_data_front(array(
		// 	'base_url'		=> base_url().'product?'.$page_url
		// ));

		// $data['product']  = $this->data_table['obj_result'];
		// // $data['product']  = arrayToObject($this->data_table['result']);

		// $this->_v('product',$data);
	}

	// function product_detail(){
	// 	$this->page     = 'Product';
	// 	$this->cur_menu = 'product';
	// 	$this->header_type = '0';
	// 	$this->footer_type = '0';
	// 	$this->url_back    = '';

	// 	$data = '';
	// 	$data['err'] 	 = true;
	// 	$data['msg'] 	 = '';
	// 	$data['content'] = '';

	// 	$url  = $this->uri->segment(2);
	// 	if(trim($url)!=''){
	// 		$data['product'] = $this->db->get_where("mt_product",array(
	// 			'url'	=> $url
	// 		),1,0)->row();
	// 		if(count($data['product']) == 0){
	// 			$data['err'] 	 = true;
	// 			$data['msg'] 	 = 'Produk tidak ditemukan';
	// 		} else {
	// 			$data['err'] 	 = false;
	// 			$this->page      = $data['product']->product_name;
	// 			$product_id 	 = $data['product']->product_id;

	// 			$data['product']->product_view = $data['product']->product_view + 1;
	// 			$this->db->update("mt_product",array("product_view"=>$data['product']->product_view),array("product_id"=>$product_id));

	// 			$data['like'] = get_check_like($product_id,'member_like');
	// 			$data['wishlist'] = get_check_like($product_id,'member_wishlist');

	// 			$data['product_category'] = $this->db->get_where("mt_product_category",array(
	// 				'product_category_id'	=> $data['product']->product_category_id
	// 			),1,0)->row();

	// 			$data['product_description'] = $this->db->get_where("mt_product_description",array(
	// 				'product_id'	=> $product_id
	// 			),1,0)->row();

	// 			$data['product_detail'] = $this->db->get_where("mt_product_detail",array(
	// 				'product_id'	=> $product_id
	// 			),1,0)->row();

	// 			$this->db->order_by('position','asc');
	// 			$data['product_image'] = $this->db->get_where("mt_product_image",array(
	// 				'product_id'	=> $product_id
	// 			))->result();

	// 			// SAMA SEPERTI MODAL PRODUK
	// 			$data['cart_id']        = $product_id;
	// 			$data['cart_name'] 		= $data['product']->product_name;
	// 			$data['cart_image'] 	= get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($data['product']->product_id));
	// 			$data['cart_link'] 		= base_url().'product/'.$data['product']->url;

 //                $cur_cart = $this->jCfg['cart'][$data['cart_id']];

 //                $detail = $data['product_detail'];
 //                $data['cart_id_detail']         = $detail->product_detail_id;
 //                $data['cart_weight']            = $detail->product_weight;
 //                $data['cart_weight_span']       = convertGrToKg($data['cart_weight']);
 //                $data['cart_grosir_price_span'] = $detail->product_price_grosir;
 //                $data['cart_total_weight']      = 0;
 //                $data['cart_total_weight_span'] = 0;
 //                $data['cart_total_qty']         = 0;
 //                $data['cart_total_qty_span']    = 0;
 //                $data['cart_total_price']       = 0;
 //                $data['cart_total_price_span']  = 0;

 //                $data['cart_price']      = $detail->product_price_sale;
 //                $data['cart_price_span'] = '<p class="special-price"><span class="price">'.convertRP($detail->product_price_sale).'</span></p>';
 //                if($detail->product_price_discount > 0){
 //                    $data['cart_price']      = $detail->product_price_discount;
 //                    $data['cart_price_span'] = '<p class="old-price"><span class="price">'.convertRP($detail->product_price_sale).'</span></p><p class="special-price"><span class="price">'.convertRP($detail->product_price_discount).'</span></p>';
 //                }
 //                $data['cart_normal_price']      = $data['cart_price'];
 //                $data['cart_normal_price_span'] = $data['cart_price_span'];

 //                // GENERATE SPESIFIKASI ITEM DETAIL
 //                $data['product_stock_detail_content'] = '';
 //                if($detail->product_stock_detail != ''){
 //                    $data['product_stock_detail'] = json_decode($detail->product_stock_detail);
 //                    $isi_grosir = '';
 //                    foreach ($data['product_stock_detail'] as $key => $value) {
 //                        $data['cart_qty_id']  = '';
 //                        $data['cart_qty_qty'] = 0;
 //                        if(isset($cur_cart)){
 //                            if(array_key_exists($value->id, $cur_cart['cart-qty'])){
 //                                $data['cart_qty_id']  = $cur_cart['cart-qty'];
 //                                $data['cart_qty_qty'] = isset($cur_cart['cart-qty'][$value->id])?$cur_cart['cart-qty'][$value->id]:'0';
 //                                $data['cart_total_qty']      = $data['cart_total_qty'] + $cur_cart['cart-qty'][$value->id];
 //                                $data['cart_total_qty_span'] = $data['cart_total_qty'];
 //                            }
 //                        }

 //                        $status_stock_detail = true;
 //                        if($detail->product_status_id == '3' || $value->status == '2'){ $status_stock_detail = false; }

 //                        $isi_stock_detail .= '
 //                            <div class="col-xs-25">
 //                                <div class="product-variasi-item clearfix '.($status_stock_detail==false?'disabled':'').' '.($data['cart_qty_id']!=''?'checked':'').'" style="'.($value->color!=''?'border-color:#'.$value->color.';':'').'" title="'.$value->name.'">
 //                                    <div class="checkbox">
 //                                        <label>
 //                                            <input type="checkbox" name="chk-cart-detail['.$value->id.']" class="form-cart-checkbox" value="1" '.($data['cart_qty_id']!=''?'checked':'').' '.($status_stock_detail==false?'disabled':'').'>
 //                                            <div class="checkbox-text" style="'.($value->color!=''?'background-color:#'.$value->color.';':'').'">
 //                                                <div class="centeralign"><div class="centeralign-2">
 //                                                    '.($value->name!=''?$value->name:$value->id).'
 //                                                </div></div>
 //                                            </div>
 //                                        </label>
 //                                    </div>
 //                                    <div class="select">
 //                                        <input type="hidden" name="cart-detail-stock['.$value->id.']" value="'.$value->qty.'">
 //                                        <select name="cart-qty['.$value->id.']" class="form-control cart-update-qty" '.($data['cart_qty_id']!=''?'':'disabled="disabled"').'>
 //                                            <option value="" disabled selected>0</option>
 //                                            '.get_option_qty($data['cart_qty_qty']).'
 //                                        </select>
 //                                    </div>
 //                                </div>
 //                            </div>
 //                        ';

 //                            // [{"id":"1","name":"","color":"ff5f56","qty":"3","status":"1"},{"id":"2","name":"","color":"a1e9e6","qty":"2","status":"1"},{"id":"3","name":"","color":"8cbb87","qty":"2","status":"1"},{"id":"4","name":"","color":"acb5b2","qty":"4","status":"1"},{"id":"5","name":"","color":"7fabe8","qty":"2","status":"1"},{"id":"6","name":"","color":"c49f75","qty":"4","status":"1"}]
 //                    }

 //                    $data['product_stock_detail_content'] = '
 //                        <div class="form-group product-variasi small">
 //                            <label>Ceklis warna dan isi jumlahnya</label>
 //                            <div class="row">
 //                                '.$isi_stock_detail.'
 //                            </div>
 //                            <p class="mb-0 mt-5">Jumlah Qty: <span class="cart-total-qty-span">'.$data['cart_total_qty_span'].' item</span></p>
 //                        </div>';
 //                } // END GENERATE SPESIFIKASI ITEM DETAIL

 //                // GENERATE GROSIR
 //                $data['product_grosir_content'] = '';
 //                if($detail->product_price_grosir != ''){
 //                    $count_qty_grosir = array();
 //                    $data['product_price_grosir'] = json_decode($detail->product_price_grosir);
 //                    $isi_grosir = '';
 //                    foreach ($data['product_price_grosir'] as $key => $value) {
 //                        $isi_grosir .= '
 //                            <tr>
 //                                <td class="text-left">'.$value->name.' barang</td>
 //                                <td class="text-left">'.convertRP($value->price).'</td>
 //                            </tr>';

 //                        if(isset($cur_cart)){
 //                            if($value->qty <= $data['cart_total_qty']){
 //                                $data['cart_price']      = $value->price;
 //                                $data['cart_price_span'] = '<p class="special-price"><span class="price">'.convertRp($data['cart_price']).'</span></p>';
 //                            }
 //                        }
 //                    }
 //                    // [{"name":"3 - 5","qty":"3","price":"45000"},{"name":"6 - 9","qty":"6","price":"43500"},{"name":">= 10","qty":"10","price":"42000"}]

 //                    $data['product_grosir_content'] = '
 //                        <div class="product-price-grosir">
 //                            <span class="product-price-grosir-container">
 //                                <span class="badge">GROSIR</span>
 //                                <span class="product-price-grosir-btn">Beli Banyak Lebih Murah</span>
 //                                <div class="table-responsive table-product-price-grosir mt-10">
 //                                    <table class="table table-th-block table-info small no-margin">
 //                                        <colgroup>
 //                                            <col>
 //                                            <col>
 //                                        </colgroup>
 //                                        <thead>
 //                                            <tr>
 //                                                <th><strong>Kuantitas</strong></th>
 //                                                <th><strong>Harga Satuan</strong></th>
 //                                            </tr>
 //                                        </thead>
 //                                        <tbody>
 //                                            '.$isi_grosir.'
 //                                        </tbody>
 //                                    </table>
 //                                </div>
 //                            </span>
 //                        </div>';
 //                } // END GENERATE GROSIR

 //                // GENERATE STATUS
 //                $data['product_status_span'] = '';
	                // switch ($detail->product_status_id) {
	                //     case '1': $data['product_status_span'] = '<p class="availability">Status: <span>'.get_name_product_status($detail->product_status_id).'</span></p>'; break;
	                //     case '2': $data['product_status_span'] = '<p class="pre_order">Status: <span>'.get_name_product_status($detail->product_status_id).'</span></p>'; break;
	                //     case '3': $data['product_status_span'] = '<p class="out_of_stock">Status: <span>'.get_name_product_status($detail->product_status_id).'</span></p>'; break;
	                //     default: break;
	                // }
 //                // END GENERATE STATUS

 //                if(isset($cur_cart)){
 //                    $data['cart_total_weight']      = $data['cart_weight'] * $data['cart_total_qty'];
 //                    $data['cart_total_weight_span'] = convertGrToKg($data['cart_total_weight']);
 //                    $data['cart_total_price']       = $data['cart_price'] * $data['cart_total_qty'];
 //                    $data['cart_total_price_span']  = convertRp($data['cart_total_price']);
 //                }
	// 		}
	// 	} else {
	// 		$data['err'] 	 = true;
	// 		$data['msg'] 	 = 'Produk tidak ditemukan';
	// 	}

	// 	$this->_v('product_detail',$data);
	// }

	function ajax_product_category(){
		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'find_parent' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$thisId        = dbClean(trim($_POST['thisId']));

			if($thisVal!=''){
				$front_get_category_menu = front_get_category_menu($thisVal);
				if(!empty($front_get_category_menu)){
					$err = false;
					$msg .= '<option value="" selected>--- SELECT ---</option>';
							foreach ($front_get_category_menu as $v) {
							$msg .= '<option value="'.$v->product_category_id.'" '.($v->product_category_id==$thisId?'selected':'').' >'.$v->product_category_title.'</option>';
							}
				}
			}
		}

		$return = array('msg' => $msg,'err' => $err);
		die(json_encode($return));
		exit();
	}

	function ajax_get_product(){
	    $error 	= true;
	    $msg 	= '';
		$rows 	= array();
	    $postdata = $_POST;
	    if (isset($postdata)) {
	    	// $request 	= json_decode($postdata);
			// $thisAction = mysql_real_escape_string($request->thisAction);

			$par_filter = array();
			foreach ($postdata as $key => $value) {
				$par_filter[$key] = $value;
			}
			$thisAction = $par_filter['thisAction'];
			if($thisAction == 'getdata'){
				$this->data_table = $this->M->data_product_front($par_filter);
				$rows['product']  = $this->data_table;
			}
		}

		die(json_encode($rows));
		exit();
	}

	function ajax_get_product_modal(){
		$data = array();
		$data['err'] 	 = true;
		$data['msg'] 	 = '';
		$data['content'] = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'get_product' ){
			$thisVal = dbClean(trim($_POST['thisVal']));

			// $this->DATA->table="mt_product";
			if(trim($thisVal)!=''){
				$r = $this->db->get_where("mt_product",array(
					'product_id' 		=> $thisVal,
					'product_istrash' 	=> '0'
				),1,0)->row();
				if(count($r) > 0){
					$data['err'] = false;

					$data['cart_id'] 		= $r->product_id;
					$data['cart_name'] 		= $r->product_name;
					$data['cart_image'] 	= get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id));
					$data['cart_link'] 		= base_url().'product/'.$r->url;

                    $wishlist = get_check_like($r->product_id,'member_wishlist');

					$data['cart_image_detail'] = '';
					$get_image_detail          = get_image_detail($r->product_id);
					foreach ($get_image_detail as $key => $value) {
						// $data['cart_image_detail'] .= '<li><img src="'.get_image(base_url().'assets/collections/product/small/'.$value->image_filename).'" alt=""></li>';

						$data['cart_image_detail'] .= '<li class="item hoverStyle" data-thumb="'.get_image(base_url()."assets/collections/product/thumb/".$value->image_filename).'">
	                                <img class="fullwidth" src="'.get_image(base_url()."assets/collections/product/small/".$value->image_filename).'">
	                                <div class="hoverBox">
	                                    <div class="hoverIcons">
	                                        <a href="'.get_image(base_url()."assets/collections/product/small/".$value->image_filename).'" class="eye hovicon"><i class="fa fa-expand expand-pic"></i></a>
	                                        <a href="javascript:void(0);" class="heart hovicon btn-add-like '.($wishlist?'active':'').'" data-like-id="'.$r->product_id.'" data-like-type="wishlist">
	                                            <i class="small-ajax-loader hide"></i>
	                                            <i class="fa fa-heart-o"></i>
	                                        </a>
	                                    </div>
	                                </div>
	                            </li>';
					}

                	$cur_cart = $this->jCfg['cart'][$data['cart_id']];

					$detail = get_product_detail($r->product_id)[0];
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

					$data['cart_price']      = $detail->product_price_sale;
                    $data['cart_price_span'] = '<p class="special-price"><span class="price">'.convertRP($detail->product_price_sale).'</span></p>';
                    if($detail->product_price_discount > 0){
                        $data['cart_price']      = $detail->product_price_discount;
                        $data['cart_price_span'] = '<p class="old-price"><span class="price">'.convertRP($detail->product_price_sale).'</span></p><p class="special-price"><span class="price">'.convertRP($detail->product_price_discount).'</span></p>';
                    }
                    $data['cart_normal_price'] 		= $data['cart_price'];
                    $data['cart_normal_price_span'] = $data['cart_price_span'];

                	// GENERATE SPESIFIKASI ITEM DETAIL
                    $data['product_stock_detail_content'] = '';
					if($detail->product_stock_detail != ''){
                        $data['product_stock_detail'] = json_decode($detail->product_stock_detail);
                        $isi_grosir = '';
                        foreach ($data['product_stock_detail'] as $key => $value) {
							$data['cart_qty_id']  = '';
							$data['cart_qty_qty'] = 0;
		                	if(isset($cur_cart)){
		                		if(array_key_exists($value->id, $cur_cart['cart-qty'])){
			                		$data['cart_qty_id']  = $cur_cart['cart-qty'];
									$data['cart_qty_qty'] = isset($cur_cart['cart-qty'][$value->id])?$cur_cart['cart-qty'][$value->id]:'0';
									$data['cart_total_qty'] 	 = $data['cart_total_qty'] + $cur_cart['cart-qty'][$value->id];
									$data['cart_total_qty_span'] = $data['cart_total_qty'];
		                		}
		                	}

                            $status_stock_detail = true;
	                        if($detail->product_status_id == '3' || $value->status == '2'){ $status_stock_detail = false; }

	                        $isi_stock_detail .= '
	                            <div class="col-xs-6">
	                                <div class="product-variasi-item clearfix '.($status_stock_detail==false?'disabled':'').' '.($data['cart_qty_id']!=''?'checked':'').'" style="'.($value->color!=''?'border-color:#'.$value->color.';':'').'" title="'.$value->name.'">
	                                    <div class="checkbox">
	                                        <label>
	                                            <input type="checkbox" name="chk-cart-detail['.$value->id.']" class="form-cart-checkbox" value="1" '.($data['cart_qty_id']!=''?'checked':'').' '.($status_stock_detail==false?'disabled':'').'>
	                                            <div class="checkbox-text" style="'.($value->color!=''?'background-color:#'.$value->color.';':'').'">
	                                                <div class="centeralign"><div class="centeralign-2">
	                                                    '.($value->name!=''?$value->name:$value->id).'
	                                                </div></div>
	                                            </div>
	                                        </label>
	                                    </div>
	                                    <div class="select">
	                                        <input type="hidden" name="cart-detail-stock['.$value->id.']" value="'.$value->qty.'">
	                                        <select name="cart-qty['.$value->id.']" class="form-control cart-update-qty" '.($data['cart_qty_id']!=''?'':'disabled="disabled"').'>
	                                            <option value="" disabled selected>0</option>
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
	                            <p class="mb-0 mt-5">Jumlah Qty: <span class="cart-total-qty-span">'.$data['cart_total_qty_span'].' item</span></p>
	                        </div>';
					} // END GENERATE SPESIFIKASI ITEM DETAIL

                    // GENERATE GROSIR
                    $data['product_grosir_content'] = '';
					if($detail->product_price_grosir != ''){
                        $count_qty_grosir = array();
                        $data['product_price_grosir'] = json_decode($detail->product_price_grosir);
                        $isi_grosir = '';
                        foreach ($data['product_price_grosir'] as $key => $value) {
                            $isi_grosir .= '
                            	<tr>
                                    <td class="text-left">'.$value->name.' barang</td>
                                    <td class="text-right">'.convertRP($value->price).'</td>
                                </tr>';

                            if(isset($cur_cart)){
                            	if($value->qty <= $data['cart_total_qty']){
		                            $data['cart_price']      = $value->price;
		                            $data['cart_price_span'] = '<p class="special-price"><span class="price">'.convertRp($data['cart_price']).'</span></p>';
		                        }
                            }
                        }
                        // [{"name":"3 - 5","qty":"3","price":"45000"},{"name":"6 - 9","qty":"6","price":"43500"},{"name":">= 10","qty":"10","price":"42000"}]

                        $data['product_grosir_content'] = '
                        	<div class="product-price-grosir">
                                <span class="product-price-grosir-container">
                                    <span class="badge">GROSIR</span>
                                    <span class="product-price-grosir-btn">Beli Banyak Lebih Murah</span>
                                    <div class="table-responsive table-product-price-grosir">
                                        <p class="no-margin text-center"><small>Harga Grosir</small></p>
                                        <table class="table table-th-block table-info small no-margin">
                                            <colgroup>
                                                <col>
                                                <col>
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th><strong>Kuantitas</strong></th>
                                                    <th><strong>Harga Satuan</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                '.$isi_grosir.'
                                            </tbody>
                                        </table>
                                    </div>
                                </span>
                            </div>';
                	} // END GENERATE GROSIR

	                // GENERATE STATUS
	                $data['product_status_span'] = '';
	                switch ($detail->product_status_id) {
	                    case '1': $data['product_status_span'] = '<p class="availability">Status: <span>'.get_name_product_status($detail->product_status_id).'</span></p>'; break;
	                    case '2': $data['product_status_span'] = '<p class="pre_order">Status: <span>'.get_name_product_status($detail->product_status_id).'</span></p>'; break;
	                    case '3': $data['product_status_span'] = '<p class="out_of_stock">Status: <span>'.get_name_product_status($detail->product_status_id).'</span></p>'; break;
	                    default: break;
	                }
	                // END GENERATE STATUS

                	if(isset($cur_cart)){
						$data['cart_total_weight'] 	    = $data['cart_weight'] * $data['cart_total_qty'];
						$data['cart_total_weight_span'] = convertGrToKg($data['cart_total_weight']);
						$data['cart_total_price']       = $data['cart_price'] * $data['cart_total_qty'];
						$data['cart_total_price_span']  = convertRp($data['cart_total_price']);
					}

                    $data['content'] = '
                    <form class="form-cart cart-item cart-item-'.$data['cart_id'].'" data-id="'.$data['cart_id'].'" action="'.base_url().'ajax-cart-add" method="post" enctype="multipart/form-data">
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
						    <div class="data-normal-price" style="display:none;">'.$data['cart_normal_price'].'</div>
						    <div class="data-normal-price-span" style="display:none;">'.$data['cart_normal_price_span'].'</div>
						    <div class="cart-grosir-price-span" style="display:none;">'.$data['cart_grosir_price_span'].'</div>
						</div>
						<div class="product-images">
                            <ul id="product-slider-modal" class="product-item-slider product-item-slider-modal product-image">
	                            '.$data['cart_image_detail'].'
	                        </ul>
                        </div>
                        <div class="product-info">
                            <h1 class="product-name">'.$r->product_name.'</h1>
                            '.$data['product_status_span'].'
                            <div class="price-box cart-price-span">'.$data['cart_price_span'].'</div>
                            '.$data['product_grosir_content'].'
                            '.$data['product_stock_detail_content'].'
                            <div class="quick-add-to-cart">
                            	'.($detail->product_status_id != 3?'
                                <button class="single_add_to_cart_button cart-add-btn" type="submit">BELI</button>
	                            ':'
	                            <div class="single_add_to_cart_button disabled">'.(get_product_status($detail->product_status_id)->product_status_name).'</div>
	                            ').'
                            </div>
                            <div class="social-sharing">
                                <div class="widget widget_socialsharing_widget">
                                    <h3 class="widget-title-modal">Share this product</h3>
                                    <ul class="social-icons">
                                        <li><a target="_blank" title="Facebook" href="index.html#" class="facebook social-icon"><i class="fa fa-facebook"></i></a></li>
                                        <li><a target="_blank" title="Twitter" href="index.html#" class="twitter social-icon"><i class="fa fa-twitter"></i></a></li>
                                        <li><a target="_blank" title="Pinterest" href="index.html#" class="pinterest social-icon"><i class="fa fa-pinterest"></i></a></li>
                                        <li><a target="_blank" title="Google +" href="index.html#" class="gplus social-icon"><i class="fa fa-google-plus"></i></a></li>
                                        <li><a target="_blank" title="LinkedIn" href="index.html#" class="linkedin social-icon"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
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

	function ajax_like(){
		$err = true;
		$msg = '';
		$msg1 = '';
		$err1 = '';
		$action = '';

		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			$err = true;
			$action = base_url().'login';
		} else {

			if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
				$thisId        = dbClean(trim($_POST['thisId']));
				$thisType      = isset($_POST['thisType'])?$_POST['thisType']:'like';
				if(trim($thisId) != ''){
					switch ($thisType) {
						case 'like': $mt = 'member_like'; $ct = 'product_like'; $msg1 = 'You like'; $err1 = 'Like'; break;
						case 'wishlist': $mt = 'member_wishlist'; $ct = 'product_wishlist'; $msg1 = 'Sudah dalam Wishlist'; $err1 = 'Tambah ke Wishlist'; break;
						default: break;
					}

					$new   = '';
					$like  = get_member_like($this->user_login,$mt);
					$count = get_count_like($thisId,$ct);

					$arr = array();
					$rmv = array();
					if($like!=''){
						foreach (explode(',', $like) as $n){
							$arr[] = $n;
							if($n != $thisId){ $rmv[] = $n; }
						}
					}
					$action = (!in_array($thisId, $arr)?'add':'remove');

					if($action=='add'){
						$msg = $msg1;
						$count += 1;
						if($like!=''){
							$new = $like.','.$thisId;
						} else {
							$new = $thisId;
						}
					} else {
						$msg = $err1;
						$count -= 1;
						$new = implode(',', $rmv);
					}

					$this->db->update("mt_product",array($ct=>$count),array("product_id"=>$thisId));
					$this->db->update("mt_member",array($mt=>$new),array("member_id"=>$this->user_login));


					$err = false;
				}
			} else {
				redirect(base_url());
			}
		}

		$return = array('msg' => $msg,'err' => $err,'count' => $count,'action' => $action);
		die(json_encode($return));
		exit();
	}

}
