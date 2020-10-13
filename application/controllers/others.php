<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class others extends FrontController {
	function __construct()
	{
		parent::__construct();

		$this->load->library('Mobile_Detect');
	    // $detect = new Mobile_Detect();
	    // if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
	    //     header("Location: /mobile"); exit;
	    // } else {
	    //     header("Location: /desktop"); exit;
	    // }

	}

	function _reset_product(){
		$this->jCfg['front_search_product'] = array(
			'class'		  => $this->_getClass(),
			'name'		  => 'front_product',
			'short_by'    => 1,
			'order_by'    => '',
			'order_dir'   => '',
			'grid_view'	  => 'grid',
			'offset'      => 0,
			'per_page'    => 30,
			'keyword'	  => ''
		);
		$this->_releaseSession();
	}

	function index(){
		$this->page     = '';
		$this->title    = '';
		$this->cur_menu = '';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';
		$this->backtotop   = true;
		$this->scroll_anchor = true;

		$data = array();
		$data['own_link'] = $_SERVER['REQUEST_URI'];
		$load_function = 'not_found';

		$CI 		= getCI();
		$name 		= $CI->uri->segment(1);
		$action 	= $CI->uri->segment(2);
		$action_id 	= $CI->uri->segment(3);

        if(empty($name)){
        	$this->not_found();
        } else {
        	//CEK DIMENU
	        $r = $this->db->get_where("mt_menus",array(
				'url'		=> $name
			),1,0)->row();
			if(count($r)>0){
				$class = $r->menus_class;
				$functions = $r->menus_function;
				if($functions == ''){
			    	debugCode($functions);
				} else {
					$data['menus'] = $r;
					$data['menus_action'] = $action;
					$data['menus_action_id'] = $action_id;

					if($class == 'load_function'){
						$this->load->library('../controllers/front/site');
						$this->site->$functions();
					} else {
						$this->page     = $r->menus_title;
						$this->title    = $r->menus_title;
						$this->cur_menu = $r->url;
						$this->header_type = '1';
						$this->footer_type = '1';
						$this->url_back    = '';
						$this->backtotop   = true;
						$this->scroll_anchor = true;

						$data['menu']    = $r->menus_title;
						$data['menu_id'] = $r->menus_id;

						$data['section'] = $this->db->order_by('position','asc')->get_where("mt_menus",array(
							"menus_parent_id"	=> $data['menu_id']
						))->result();

						// 	$id = '7';
						// 	$cat = $this->db->get_where("mt_article_category",array(
						// 		"category_id"	=> $id
						// 	))->row();
						// 	$data['category_title']	= $cat->category_title;
						// 	$data['category_desc']	= $cat->category_desc;
						// 	$data['category_image']	= $cat->category_image;

						$this->_v($functions,$data);
					}
				}
			} else { // CEK APAKAH INI PRODUK
				$this->page        = ucwords($name);
				$this->title       = ucwords($name);
				$this->cur_menu    = $name;
				$this->header_type = '0';
				$this->footer_type = '0';
				$this->url_back    = $name;

				// $d = $this->db->get_where("mt_member",array(
				// 	'member_username'		=> $name
				// ),1,0)->row();
				// if(count($d)>0){
				// 	$data['member_store'] = $d;
				// 	$data['member_action'] = $action;
				// 	$data['member_action_id'] = $action_id;

				// 	$this->_v('account_store',$data);
				// } else {
				// 	$this->not_found();
				// }

				$data['product'] = array();
				$this->product_id = "";
				$this->category_id   = "";
				$this->menu_category = "";
				$this->awards_id = "";
				$this->brand_id  = "";
				$this->tags_id   = "";
				$this->status_id = "";
				$this->min_range = "";
				$this->max_range = "";
				$this->keyword   = "";
				$this->collections = "";

				// $this->short_by  = 1;
				// $this->order_by  = "";
				// $this->order_dir = "";
				// $this->grid_view = "grid";
				// $this->offset    = 0;
				// $this->per_page  = 30;

				$this->url_get   = "";
				$this->not_found = false;
				$this->msg_error = "";

				if($name == 'collections' || $name == 'product') { // OK
					$load_function = 'product';
					$this->menu_category = 'collections';
				} else if($name == 'brand') { // OK
					if($action == ''){
						$load_function = 'brand';
						$this->page    = 'Brand';
						$this->title   = $this->page;
						$this->_v('brand',$data);
					} else {
						$m = $this->db->get_where("mt_product_brand",array(
							"url"	=> $action
						),1,0)->row();
						if(count($m) > 0){
							$load_function  = 'product';
							$this->brand_id = $m->product_brand_id;
							$this->page     = 'Brand '.ucwords($m->product_brand_name);
							$this->title    = $this->page;
						}
					}
				} else if($name == 'tag') { // OK
					if($action == ''){
						$load_function = 'tag';
						$this->page    = 'Tagline';
						$this->title   = $this->page;
						$this->_v('tag',$data);
					} else {
						$m = $this->db->get_where("mt_product_tags",array(
							"url"	=> $action
						),1,0)->row();
						if(count($m) > 0){
							$load_function = 'product';
							$this->tags_id = $m->product_tags_id;
							$this->page    = 'Tag '.ucwords($m->product_tags_name);
							$this->title   = $this->page;
						}
					}
				} else if($name == 'search') { // OK
					$load_function     = 'product';
					$this->page        = 'Cari '.$_GET['keyword'];
					$this->title       = 'Cari <em>"'.$_GET['keyword'].'"</em>';
					$this->keyword     = isset($_GET['keyword'])?$_GET['keyword']:'';
					if($this->keyword != ""){
						$this->collections = isset($_GET['collections'])?$_GET['collections']:'';
						if($this->keyword != ""){ $this->url_get .= 'keyword='.$_GET['keyword'].'&'; }
						if($this->collections != ""){ $this->url_get .= 'collections='.$_GET['collections'].'&'; }
						if($this->collections != ""){
							$m2 = $this->db->get_where("mt_product_category",array(
								'url'		=> $this->collections
							),1,0)->row();
							if(count($m2)>0){
								$this->category_id = get_arr_id_product_category($m2->product_category_id);
							}
						}
					} else {
						$this->not_found = true;
                        $this->msg_error = '<strong>Oopps !</strong> Kata pencarian harus diisi.';
					}
				} else if($name == 'sold-out') { // OK
					$load_function = 'product';
					$this->menu_category = 'sold-out';
					$this->page      = 'Sold Out';
					$this->title     = $this->page;
					$this->status_id = 3;
				} else { // OK
					$m = $this->db->get_where("mt_product_awards",array(
						'url'		=> $name
					),1,0)->row();
					if(count($m)>0){ //https://www.butiksasha.com/best-seller/
						$load_function   = 'product';
						$this->awards_id = $m->product_awards_id;
						$this->page      = ucwords($m->product_awards_name);
						$this->title     = $this->page;
					} else { //https://www.butiksasha.com/segiempat/
						$m2 = $this->db->get_where("mt_product_category",array(
							'url'		=> $name
						),1,0)->row();
						if(count($m2)>0){
							$load_function = 'product';
							// $this->category_id = $m2->product_category_id;
							$this->page        = ucwords($m2->product_category_title);
							$this->title       = $this->page;
							$this->category_id = get_arr_id_product_category($m2->product_category_id);

							if($action != ''){ //https://www.butiksasha.com/segiempat/segiempat-saudia-ansania
								$load_function    = 'product_detail';
								$m3 = $this->db->get_where("mt_product",array(
									'url'		=> $action
								),1,0)->row();
								if(count($m3)>0){
									$this->page       = ucwords($m3->product_name);
									$this->title      = $this->page;
									$this->product_id = $m3->product_id;
									$this->db->update("mt_product",array("product_view"=>($m3->product_view + 1)),array("product_id"=>$this->product_id));
								} else {
									$this->not_found = true;
									$this->msg_error = '<strong>Info !</strong> Produk tidak ditemukan.';
								}
							}
						} else {
							$load_function = 'not_found';
						}
					}
				}

				if($load_function == 'not_found'){
					$this->not_found();
				} else if($load_function == 'product'){
					$this->load->model("mdl_product","M");

					$hal = isset($this->jCfg['front_search_product']['name'])?$this->jCfg['front_search_product']['name']:"home";
					if($hal != 'front_product'){
						$this->_reset_product();
					}

					$from = isset($_GET['from'])?$_GET['from']:"";
			        if($from == 'sidebar'){
			            $this->_reset_product();
			        }

                    if($this->input->post('grid_view') && trim($this->input->post('grid_view'))!=""){
                        $this->jCfg['front_search_product']['grid_view'] = $this->input->post('grid_view');
                        $this->_releaseSession();
                    }

                    $this->short_by = $this->jCfg['front_search_product']['short_by'];
                    if($this->input->post('short_by')&& trim($this->input->post('short_by'))!=""){
	                    $this->short_by = $this->input->post('short_by');
                        $this->jCfg['front_search_product']['short_by'] = $this->short_by;
                        $this->_releaseSession();
                    }
                    switch ($this->short_by) {
                        case '1': $this->order_by = 'product_date_push'; $this->order_dir = "desc"; break;
                        case '2': $this->order_by = 'product_sold'; $this->order_dir = "desc"; break;
                        case '3': $this->order_by = 'product_price_sale'; $this->order_dir = "asc"; break;
                        case '4': $this->order_by = 'product_price_sale'; $this->order_dir = "desc"; break;
                        case '5': $this->order_by = 'product_name'; $this->order_dir = "asc"; break;
                        case '6': $this->order_by = 'product_name'; $this->order_dir = "desc"; break;
                        default: $this->order_by = 'product_date_push'; $this->order_dir = "desc"; break;
                    }

                    $this->per_page = $this->jCfg['front_search_product']['per_page'];
                    if($this->input->post('per_page') && trim($this->input->post('per_page'))!=""){
	                    $this->per_page = $this->input->post('per_page');
                        $this->jCfg['front_search_product']['per_page'] = $this->per_page;
                        $this->_releaseSession();
                    }

                    if(isset($_GET['min-range']) && $_GET['min-range'] != '' && is_numeric($_GET['min-range'])){
                        $this->min_range = $_GET['min-range'];
                        $this->url_get  .= 'min-range='.$_GET['min-range'].'&';
                    }
                    if(isset($_GET['max-range']) && $_GET['max-range'] != '' && is_numeric($_GET['max-range'])){
                        $this->max_range = $_GET['max-range'];
                        $this->url_get  .= 'max-range='.$_GET['max-range'].'&';
                    }

                    $pageNum = 1;
                    if(isset($_GET['page'])&&$_GET['page']!=''){ $pageNum = $_GET['page']; }
                    $this->offset  = ($pageNum - 1) * $this->per_page;

                    $this->colum = "";
			        $this->param = array(
			            ''                                              => 'Semua Pencarian...',
			            'mt_product.product_name'                       => 'Judul',
			            'mt_product.product_name_simple'                => 'Nama Singkat',
			            'mt_product.product_code'                       => 'Kode',
			            'mt_product_category.product_category_title'    => 'Kategori'
			        );

					$par_filter = array(
			            "product_awards_id"   => $this->awards_id,
			            "product_category_id" => $this->category_id,
			            "product_brand_id"    => $this->brand_id,
			            "product_tags_id"     => $this->tags_id,
			            "product_status_id"   => $this->status_id,
			            "product_show_id"     => 1,
			            "min_range"           => $this->min_range,
			            "max_range"           => $this->max_range,
			            "type_result"         => "front",
			            "order_by"            => $this->order_by,
			            "order_dir"           => $this->order_dir,
			            "offset"              => $this->offset,
			            "limit"               => $this->per_page,
			            "colum"               => $this->colum,
			            "keyword"             => $this->keyword,
			            "param"               => $this->param
					);
					$this->data_table = $this->M->data_product($par_filter);
					$data["products"] = $this->_data_front(array(
						'pageNum'		=> $pageNum,
						'base_url'		=> base_url().$this->url_back.'?'.$this->url_get
					));

                    if(count($this->data_table['data']) == 0){
                        $this->not_found = true;
                        $range_price = "";
                        if($this->keyword != ""){
	                        $this->msg_error = '<strong>Oopps !</strong> Kata pencarian <em>"'.$this->keyword.'"</em> tidak ditemukan.';
	                    } else if($this->collections != ""){
	                        $this->msg_error = '<strong>Oopps !</strong> Collections <em>"'.$this->collections.'"</em> tidak ditemukan.';
	                    } else if($this->awards_id != ""){
	                        $this->msg_error = '<strong>Oopps !</strong> Belum ada produk <em>"'.$this->page.'"</em>';
	                    } else if($this->brand_id != ""){
	                        $this->msg_error = '<strong>Oopps !</strong> Brand <em>"'.$this->page.'"</em> tidak ditemukan.';
	                    } else if($this->tags_id != ""){
	                        $this->msg_error = '<strong>Oopps !</strong> Tag <em>"'.$this->page.'"</em> tidak ditemukan.';
	                    } else if($this->category_id != "" && $this->product_id == "" && $this->keyword == ""){
	                        $this->msg_error = '<strong>Oopps !</strong> Belum ada produk <em>"'.$this->page.'"</em>';
	                    } else {
	                        $this->msg_error = '<strong>Oopps !</strong> Produk tidak ditemukan.';
	                    }

                        $data["products"]['data'] = get_product_best_selling(6);
                    }

					$this->_v('product',$data);
				} else if($load_function == 'product_detail'){
					$this->load->model("mdl_product","M");

					$data['isLike']     = get_check_like($this->product_id,'member_like');
					$data['isWishlist'] = get_check_like($this->product_id,'member_wishlist');

					$par_filter = array(
						"store_id"			  => NULL,
						"product_id" 		  => $this->product_id,
			            "type_result"         => "front",
			            "date_start"          => NULL,
			            "date_end"            => NULL,
			            "order_by"            => NULL,
			            "order_dir"           => NULL,
			            "offset"              => 0,
			            "limit"               => 1,
			            "colum"               => NULL,
			            "keyword"             => NULL,
			            "param"               => NULL
					);
					$data_product = $this->M->data_product($par_filter);
					$data['product']   = $data_product['data'][0];

					// SAMA SEPERTI MODAL PRODUK
					// $data['cart_id']        = $this->product_id;
					// $data['cart_name'] 		= $data['product']->product_name;
					// $data['cart_image'] 	= get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($data['product']->product_id));
					// $data['cart_link'] 		= base_url().'product/'.$data['product']->url;

	                // $cur_cart = $this->jCfg['cart'][$data['cart_id']];

	                // $detail = $data['product_detail'];
	                // $data['cart_id_detail']         = $detail->product_detail_id;
	                // $data['cart_weight']            = $detail->product_weight;
	                // $data['cart_weight_span']       = convertGrToKg($data['cart_weight']);
	                // $data['cart_grosir_price_span'] = $detail->product_price_grosir;
	                // $data['cart_total_weight']      = 0;
	                // $data['cart_total_weight_span'] = 0;
	                // $data['cart_total_qty']         = 0;
	                // $data['cart_total_qty_span']    = 0;
	                // $data['cart_total_price']       = 0;
	                // $data['cart_total_price_span']  = 0;

	                // $data['cart_price']      = $detail->product_price_sale;
	                // $data['cart_price_span'] = '<p class="special-price"><span class="price">'.convertRP($detail->product_price_sale).'</span></p>';
	                // if($detail->product_price_discount > 0){
	                //     $data['cart_price']      = $detail->product_price_discount;
	                //     $data['cart_price_span'] = '<p class="old-price"><span class="price">'.convertRP($detail->product_price_sale).'</span></p><p class="special-price"><span class="price">'.convertRP($detail->product_price_discount).'</span></p>';
	                // }
	                // $data['cart_normal_price']      = $data['cart_price'];
	                // $data['cart_normal_price_span'] = $data['cart_price_span'];

	                // if(isset($cur_cart)){
	                //     $data['cart_total_weight']      = $data['cart_weight'] * $data['cart_total_qty'];
	                //     $data['cart_total_weight_span'] = convertGrToKg($data['cart_total_weight']);
	                //     $data['cart_total_price']       = $data['cart_price'] * $data['cart_total_qty'];
	                //     $data['cart_total_price_span']  = convertRp($data['cart_total_price']);
	                // }

					$this->_v('product_detail',$data);
				}
			}
		}
	}

	function not_found(){
		$this->page     = 'ERROR 404 - PAGE NOT FOUND';
		$this->cur_menu = '404';
		$this->header_type = '1';
		$this->footer_type = '1';
		$this->url_back    = '';

		$data = '';
		$data['menu'] = "404";
		$data['menu_id'] = "";

		$this->_v('404',$data);
	}

}
