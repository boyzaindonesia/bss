<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class scraper extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete"),"ITEM");
		$this->_set_title( 'Scraper Online Shop' );
		$this->DATA->table = "mt_scraper";
		$this->folder_view = "scraper/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_scraper","M");
		$this->breadcrumb[] = array(
			"title"		=> "Scraper Online Shop",
			"url"		=> $this->own_link
		);
		$this->cat_search = array(
			''							=> 'Semua Pencarian...',
			'mt_scraper.scraper_name'	=> 'Judul'
		);

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';

	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'scraper',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'scraper_date',
			'order_dir' => 'DESC',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'scraper'){
			$this->_reset();
		}

		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		if($this->input->post('btn_search')){
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

		if($this->input->post('btn_reset')){
			$this->_reset();
		}

		$order_by = $this->jCfg['search']['order_by'];
		if($this->input->post('order_by') && trim($this->input->post('order_by'))!=""){
			$explode_order_by = explode("-", $this->input->post('order_by'));
			$this->jCfg['search']['order_by'] = $explode_order_by[0];
			$this->jCfg['search']['order_dir'] = $explode_order_by[1];
			$this->_releaseSession();
		}
		if($this->input->post('filter') && trim($this->input->post('filter'))!=""){
			$this->jCfg['search']['filter'] = $this->input->post('filter');
			$this->_releaseSession();
		}

		$this->uri_segment = 4;
		$this->per_page = $this->jCfg['search']['filter'];
		$par_filter = array(
			"offset"		=> $this->uri->segment($this->uri_segment),
			"limit"			=> $this->per_page,
			"param"			=> $this->cat_search
		);
		$this->data_table = $this->M->data_scraper($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		$data['url'] = base_url()."admin/scraper/index";

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
				'scraper_id'	=> $id
			));
			if(empty($this->data_form->scraper_id)){
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
				'scraper_id'	=> $id
			));
			if(empty($this->data_form->scraper_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$this->_v($this->folder_view.$this->prefix_view."_form");
		}else{
			redirect($this->own_link);
		}
	}

	function save_step_1(){
		$err = true;
		$msg = '';
		$token  = '';
		$result = array();

		if( isset($_POST['token']) && $_POST['token'] != '' ){

			$token    = $_POST['token'];
			$user_id  = _decrypt($token);
			$app_user = get_app_user($user_id);
			if(count($app_user) > 0){

				$scraper_source_id 	= dbClean(trim($_POST['scraper_source_id']));
				$scraper_link 		= dbClean(trim($_POST['scraper_link']));
				$scraper_old_name 	= dbClean($_POST['scraper_old_name']);
				$scraper_old_phone 	= dbClean($_POST['scraper_old_phone']);
				$scraper_start 		= dbClean(trim($_POST['scraper_start']));
				$chk_scraper_count 	= dbClean(trim($_POST['chk_scraper_count']));
				if($chk_scraper_count == 1){
					$scraper_count 	= 900000;
				} else {
					$scraper_count 	= dbClean(trim($_POST['scraper_count']));
				}

				$scraper_new_name 	= dbClean($_POST['scraper_new_name']);
				$scraper_new_phone 	= dbClean($_POST['scraper_new_phone']);
				$scraper_add_frontname = dbClean($_POST['scraper_add_frontname']);
				$scraper_add_endname   = dbClean($_POST['scraper_add_endname']);
				$scraper_add_frontdesc = dbClean($_POST['scraper_add_frontdesc']);
				$scraper_add_enddesc   = dbClean($_POST['scraper_add_enddesc']);

				$scraper_category 	= dbClean(trim($_POST['scraper_category']));
				$optionsFoto 	 	= dbClean(trim($_POST['optionsFoto']));
				$scraper_markup 	= dbClean(trim($_POST['scraper_markup']));
				$scraper_discount 	= dbClean(trim($_POST['scraper_discount']));

				$product = array();

				if($scraper_source_id == 1){ // Tokopedia
					$msg = "Get Data Tokopedia";
					$contents = file_get_contents($scraper_link);
					$msg = "Content: ".$contents;

					$dom = new domDocument;
					@$dom->loadHTML($contents);
					// $dom->preserveWhiteSpace = false;
					$dom->preserveWhiteSpace = true;
					$dom->formatOutput = true;
					$xpath = new DomXpath($dom);

					$shop_id       = $dom->getElementById('shop-id')->getAttribute('value');
					$xpLink 	   = explode('/', $scraper_link);
					$scraper_name  = strtolower($xpLink[3]);
					$msg 		   = "Shop id: ".$shop_id;

					$scraper_etalase = '';
					$etalase_id    = '';
					if($xpLink[4] == 'etalase'){
						$scraper_etalase = $xpLink[5];
						$etalase_id = $dom->getElementById('active_etalase_id')->getAttribute('value');
					}

					$rows    = ($scraper_count != ''?$scraper_count:900000);
					$start   = ($scraper_start != ''?($scraper_start - 1):0);
					$etalase = ($scraper_etalase != ''?'&etalase='.$etalase_id:'');
					$msg     = "Shop id: ".$shop_id.", Start: ".$start.", Etalase: ".$etalase;
					$search_product = 'https://ace.tokopedia.com/search/product/v3?shop_id='.$shop_id.''.$etalase.'&ob=11&rows='.$rows.'&start='.$start.'&full_domain=www.tokopedia.com&scheme=https&device=desktop&source=shop_product';

					// $search_product = 'https://ace.tokopedia.com/search/product/v3?full_domain=www.tokopedia.com&scheme=https&device=desktop&source=directory&page=1&fshop='.$shop_id.'&rows='.$rows.'&sc=8&start='.$start.'&ob=23';

					$container = $dom->getElementById('showcase-container')->nodeValue;


				 //    $contentss  = file_get_contents($search_product);
				 //    $jsonData   = json_decode($contentss);
				 //    $total_data = $jsonData->header->total_data;
				 //    $total_data_text = $jsonData->header->total_data_text;

				 //    $total    = 0;
				 //    $products = $jsonData->data->products;
					// // $msg = "Total Data Tokopedia ".count($products);

					// $products = 1;
				    if(count($products) > 0){
				    	$err = false;
					    $timestamp = timestamp();
				    	$data = array(
							'user_id'			=> $this->user_id,
							'scraper_name'		=> $scraper_name,
							'scraper_link'		=> $scraper_link,
							'scraper_source'	=> $scraper_source_id,
							'scraper_etalase'	=> $scraper_etalase,
							'scraper_total'		=> 0,
							'scraper_date'		=> $timestamp,
							'scraper_istrash'	=> 0
						);

						// $a = $this->_save_master(
						// 	$data,
						// 	array(
						// 		'scraper_id' => dbClean($_POST['scraper_id'])
						// 	),
						// 	dbClean($_POST['scraper_id'])
						// );


						// $id = $a['id'];
						// if($id != ''){
						//     foreach ($products as $key => $val) {

						// 		$product_price_grosir = '';
						// 		if(count($val->wholesale_price) > 0){
						// 			$arr_grosir = array();
						// 			foreach ($val->wholesale_price as $key2 => $val2) {
						// 				$arr_grosir[] = array('min' 	=> $val2->quantity_min,
						// 									  'max' 	=> $val2->quantity_max,
						// 									  'price'	=> $val2->price
						// 									);
						// 			}
						// 			$product_price_grosir = json_encode($arr_grosir);
						// 		}

						// 		$product[] = array(
						// 			'product_id'			=> $val->id,
						// 			'product_name'			=> $val->name,
						// 			'product_url'			=> strtok($val->url, '?'),
						// 			'product_category'		=> $val->department_id,
						// 			'product_condition'		=> $val->condition,
						// 			'product_price'			=> convertRpToInt2($val->price),
						// 			'product_price_grosir'	=> $product_price_grosir
						// 		);

						// 	    $total += 1;
						//     }

						//     $err = false;
						// 	$this->db->update("mt_scraper",array("scraper_total"=>$total),array("scraper_id"=>$id));
						// }

						// $log_item = NULL;
						// $log_qty  = $total;
						// writeLog(array(
			   //              'log_user_type'     => "1", // Admin
			   //              'log_user_id'       => $this->jCfg['user']['id'],
			   //              'log_role'          => $this->jCfg['user']['level'],
			   //              'log_type'          => "6", // Scraper
			   //              'log_detail_id'     => $id,
			   //              'log_detail_item'   => $log_item,
			   //              'log_detail_qty'    => $log_qty,
			   //              'log_title_id'      => "60", // Scraper Tokopedia
			   //              'log_desc'          => 'Scraper Link: <a href="" target="_blank">'.$scraper_link.'</a>',
			   //              'log_status' 		=> "0"
			   //          ));

					} else {
						$err = true;
						// $msg = "Total Produk Ditemukan ".count($products)."\n kemungkinan url get data produk tokopedia perlu di update.";
					}
				} else if($scraper_source_id == 2){ // Bukalapak

				} else if($scraper_source_id == 3){ // Shopee

				}

				$header = array(
					'scraper_source_id'		=> $scraper_source_id,
					'scraper_link'			=> $scraper_link,
					'scraper_name'			=> $scraper_name,
					'scraper_old_name'		=> $scraper_old_name,
					'scraper_old_phone'		=> $scraper_old_phone,
					'scraper_new_name'		=> $scraper_new_name,
					'scraper_new_phone'		=> $scraper_new_phone,
					'scraper_add_frontname'	=> $scraper_add_frontname,
					'scraper_add_endname'	=> $scraper_add_endname,
					'scraper_add_frontdesc'	=> $scraper_add_frontdesc,
					'scraper_add_enddesc'	=> $scraper_add_enddesc,
					'scraper_category'		=> $scraper_category,
					'optionsFoto'			=> $optionsFoto,
					'scraper_markup'		=> $scraper_markup,
					'scraper_discount'		=> $scraper_discount
				);

				$result = array(
					't'	=> $token,
					'h'	=> $header,
					'p'	=> $product,
					'c'	=> $total
				);

			} else {
				$err = true;
				$msg = 'Anda tidak memiliki akses...';
			} // End USER ID
		}

		$return = array('m' => $msg, 'e' => $err, 'r' => $result);
		die(json_encode($return));
		exit();
	}

	function save_step_2(){
		$err = true;
		$msg = '';
		$token  = '';
		$result = array();

		$this->jCfg['export_scraper'] = array();
		$this->jCfg['download_img']   = array();
		$this->_releaseSession();

		if( isset($_POST['t']) && $_POST['t'] != '' ){

			$token    = $_POST['t'];
			$user_id  = _decrypt($token);
			$app_user = get_app_user($user_id);
			if(count($app_user) > 0){
				$i      = $_POST['i'];

				$header = $_POST['h'];
				$scraper_source_id 		= $header['scraper_source_id'];
				$scraper_link 			= $header['scraper_link'];
				$scraper_name 			= $header['scraper_name'];
				$scraper_old_name 		= $header['scraper_old_name'];
				$scraper_old_phone 		= $header['scraper_old_phone'];
				$scraper_new_name 		= $header['scraper_new_name'];
				$scraper_new_phone 		= $header['scraper_new_phone'];
				$scraper_add_frontname 	= $header['scraper_add_frontname'];
				$scraper_add_endname 	= $header['scraper_add_endname'];
				$scraper_add_frontdesc 	= $header['scraper_add_frontdesc'];
				$scraper_add_enddesc 	= $header['scraper_add_enddesc'];
				$scraper_category 		= $header['scraper_category'];
				$optionsFoto 			= $header['optionsFoto'];
				$scraper_markup 		= $header['scraper_markup'];
				$scraper_discount 		= $header['scraper_discount'];

				$products = array();
				if($scraper_source_id == 1){ // Tokopedia
					$product 	 = $_POST['p'];
					$product_url = $product['product_url'];
				    $contents2   = file_get_contents($product_url);
					$dom2 = new domDocument;
					@$dom2->loadHTML($contents2);
					$dom2->preserveWhiteSpace = true;
					$dom2->formatOutput = true;
					$xpath = new DomXpath($dom2);

					$product_id 		= $product['product_id'];
					$product_name 		= convProductTitle($product['product_name'], $scraper_source_id, $scraper_old_name, $scraper_new_name, $scraper_add_frontname, $scraper_add_endname);

					$product_category 	= $product['product_category'];
					$product_category_1 = '';
				    $product_category_2 = '';
				    $product_category_3 = '';
				    $cat_box = $xpath->query('//ul[@class="search-cat-box breadcrumb sf-menu  ml-20 "]/li');
				    foreach($cat_box as $key2 => $val2) {
				    	if($key2 == 2){ $product_category_1 = cleanSpace($val2->nodeValue); }
				    	if($key2 == 4){ $product_category_2 = cleanSpace($val2->nodeValue); }
				    	if($key2 == 6){ $product_category_3 = cleanSpace($val2->nodeValue); }
					}

					$product_preorder  	   = 0;
					$product_preorder_day  = 0;
					$po_type_time = $dom2->getElementById('po_type_time')->nodeValue;
					if($po_type_time != ''){
						$product_preorder  	   = 1;
						$product_preorder_day  = cleanSpace(preg_replace('/[^0-9]/', '', $po_type_time));
					}

					$product_status    = 1;
					$btn_atc = $dom2->getElementById('btn-atc')->nodeValue;
					if(cleanSpace($btn_atc) == 'Stok Produk Kosong'){
						$product_status    = 2;
					}

				    $product_image = '';
				    $product_image_cover = '';
					foreach($xpath->query('//img[@class="prod-img-thumb"]') as $k => $img){
						$image = str_replace("cache/100-square/", "", $img->getAttribute('src'));
					    $product_image .= ($product_image==''?'':',').$image;
					    if($k==0){ $product_image_cover = $image; }
					}

					$product_description = $dom2->saveHTML($xpath->query('//p[@itemprop="description"]')->item(0));
					$product_description = str_replace('<p itemprop="description">','',$product_description);
					$product_description = str_replace('</p>','',$product_description);
					$product_description = convProductDesc($product_description, $scraper_source_id, $scraper_old_name, $scraper_new_name, $scraper_old_phone, $scraper_new_phone, $scraper_add_frontdesc, $scraper_add_enddesc);

					$product_price          = $product['product_price'];
					$product_price_grosir   = $product['product_price_grosir'];
					if($scraper_markup != 0 && $scraper_markup != ''){
						$calc_markup  	 = ($product_price * $scraper_markup) / 100;
						$product_price   = ($product_price + $calc_markup);

						if($product_price_grosir != ''){
							$arr_grosir  = array();
							$json_grosir = json_decode($product_price_grosir);
							foreach ($json_grosir as $k => $v) {
								$price     = $v->price;
								$calc  	   = ($price * $scraper_markup) / 100;
								$new_price = $price + $calc;

								$arr_grosir[] = array('min' 	=> $v->min,
													  'max' 	=> $v->max,
													  'price' 	=> $new_price
												  );
							}
							$product_price_grosir = json_encode($arr_grosir);
						}
					}

					$product_discount_1 = '';
					$product_discount_2 = '';
					if($scraper_discount != 0 && $scraper_discount != ''){
						$product_discount_1 = $scraper_discount;
						$calc_discount  	= ($product_price * $scraper_discount) / 100;
						$product_discount_2 = ($product_price + $calc_discount);
					}

					$product_weight    = convertStrToInt2($dom2->getElementById('p-info-weight')->nodeValue);
					$product_min_order = cleanSpace($dom2->getElementById('p-info-minorder')->nodeValue);
					$product_condition = $product['product_condition'];

					$product_etalase   = '';
					$product_video 	   = '';

				} else if($scraper_source_id == 2){ // Bukalapak

				} else if($scraper_source_id == 3){ // Shopee

				}

				$item = array();
				if($product_name != ''){
					$item = array(
						'product_id'			=> $product_id,
						'product_name'			=> $product_name,
						'product_url'			=> $product_url,
						'product_category'		=> $product_category,
						'product_category_1'	=> $product_category_1,
						'product_category_2'	=> $product_category_2,
						'product_category_3'	=> $product_category_3,
						'product_description'	=> $product_description,
						'product_price'			=> $product_price,
						'product_price_grosir'	=> $product_price_grosir,
						'product_discount_1'	=> $product_discount_1,
						'product_discount_2'	=> $product_discount_2,
						'product_weight'		=> $product_weight,
						'product_min_order'		=> $product_min_order,
						'product_etalase'		=> $product_etalase,
						'product_status'		=> $product_status,
						'product_preorder'		=> $product_preorder,
						'product_preorder_day'	=> $product_preorder_day,
						'product_condition'		=> $product_condition,
						'product_image'			=> $product_image,
						'product_image_cover'	=> $product_image_cover,
						'product_video'			=> $product_video
					);

					$err = false;
					$msg = '';

					if($_POST['n'] != ''){ $products = $_POST['n']; }
					array_push($products, $item);
					$products = arrayToObj($products);

					$layout = '<li><div class="no-product">'.$i.'.</div><img class="img-product" src="'.$product_image_cover.'"><div class="desc-product"><div><a href="'.$product_url.'" target="_blank">'.$product_name.'</a></div><div><h6 class="no-margin no-padding">'.$product_category_1.' > '.$product_category_2.' > '.$product_category_3.'</h6></div></div></li>';
				}

				$result = array(
					't'			=> $token,
					'h'			=> $header,
					's'			=> $item,
					'l'			=> $layout,
					'n'			=> $products
				);

				$this->jCfg['export_scraper'] = $result;
				$this->_releaseSession();

			} else {
				$err = true;
				$msg = 'Anda tidak memiliki akses...';
			} // End USER ID
		}

		$return = array('m' => $msg, 'e' => $err, 'r' => $result);
		die(json_encode($return));
		exit();
	}

	function export(){
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Asia/Jakarta');

		$export_scraper = isset($this->jCfg['export_scraper'])?$this->jCfg['export_scraper']:array();
		if(count($export_scraper) > 0){
			$timestamp = timestamp();

			$token    = $export_scraper['t'];
			$user_id  = _decrypt($token);
			$app_user = get_app_user($user_id);
			if(count($app_user) > 0){

				$header = $export_scraper['h'];
				$scraper_source_id 		= $header['scraper_source_id'];
				$scraper_link 			= $header['scraper_link'];
				$scraper_name 			= $header['scraper_name'];
				$scraper_old_name 		= $header['scraper_old_name'];
				$scraper_old_phone 		= $header['scraper_old_phone'];
				$scraper_new_name 		= $header['scraper_new_name'];
				$scraper_new_phone 		= $header['scraper_new_phone'];
				$scraper_add_frontname 	= $header['scraper_add_frontname'];
				$scraper_add_endname 	= $header['scraper_add_endname'];
				$scraper_add_frontdesc 	= $header['scraper_add_frontdesc'];
				$scraper_add_enddesc 	= $header['scraper_add_enddesc'];
				$scraper_category 		= $header['scraper_category'];
				$optionsFoto 			= $header['optionsFoto'];
				$scraper_markup 		= $header['scraper_markup'];
				$scraper_discount 		= $header['scraper_discount'];

				$get_source 			= get_source($header['scraper_source_id']);
				$scraper_source_name 	= $get_source['name'];
				$scraper_source_url 	= $get_source['url'];

				$products = $export_scraper['n'];
				if(count($products) > 0){
					// debugCode($products);

					// Create new PHPExcel object
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->getProperties()->setCreator("Programmer Nakal")
												 ->setLastModifiedBy("Programmer Nakal")
												 ->setTitle("Scraper Data Produk ".$scraper_old_name)
												 ->setSubject("Scraper Data Produk ".$scraper_old_name)
												 ->setDescription("Scraper Data Produk ".$scraper_old_name .' Link '.$scraper_link)
												 ->setKeywords("Scraper")
												 ->setCategory("Scraper");

					$download_img = array();
					if($scraper_source_id == 1){ // TOKOPEDIA

						$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Nama Produk')
						                              ->setCellValue('B1', 'Kategori')
						                              ->setCellValue('C1', 'Deskripsi Produk')
						                              ->setCellValue('D1', 'Harga')
						                              ->setCellValue('E1', 'Berat')
						                              ->setCellValue('F1', 'Pemesanan Minimum')
						                              ->setCellValue('G1', 'Status')
						                              ->setCellValue('H1', 'Jumlah Stok')
						                              ->setCellValue('I1', 'Etalase')
						                              ->setCellValue('J1', 'Preorder')
						                              ->setCellValue('K1', 'Waktu Preorder')
						                              ->setCellValue('L1', 'Kondisi')
						                              ->setCellValue('M1', 'Gambar 1')
						                              ->setCellValue('N1', 'Gambar 2')
						                              ->setCellValue('O1', 'Gambar 3')
						                              ->setCellValue('P1', 'Gambar 4')
						                              ->setCellValue('Q1', 'Gambar 5')
						                              ->setCellValue('R1', 'Video 1')
						                              ->setCellValue('S1', 'Video 2')
						                              ->setCellValue('T1', 'Video 3');

						$iRow = 2;
						foreach ($products as $k => $v) {
							$product_category = NULL;
							if($scraper_source_id == 1){ $product_category = $v->product_category; }

							$product_stock    = NULL;
							$product_etalase  = NULL;

							$product_status   = ($v->product_status == 2?'Stok Kosong':'Stok Tersedia');
							$product_preorder = ($v->product_preorder == 1?'YA':NULL);
							$product_preorder_day = NULL;
							if($v->product_preorder == 1){ $product_preorder_day = $v->product_preorder_day; }

							$product_condition = ($v->product_condition == 1?'Baru':'Bekas');

							$product_image    = array();
							$expImg    		  = explode(',', $v->product_image);
							for ($ii=0; $ii < 5; $ii++) {
								if($optionsFoto == 2){
									$product_image[$ii] = (isset($expImg[$ii])?basename($expImg[$ii]):'');
									if($product_image[$ii] != ''){
										$download_img[] = $expImg[$ii];
									}
								} else {
									$product_image[$ii] = (isset($expImg[$ii])?$expImg[$ii]:'');
								}
							}

							$product_video    = array();
							$expVideo    	  = explode(',', $v->product_video);
							for ($ii=0; $ii < 3; $ii++) {
								$product_video[$ii] = (isset($expVideo[$ii])?$expVideo[$ii]:'');
							}

							$objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $v->product_name)
							                              ->setCellValue('B'.$iRow, $product_category)
							                              ->setCellValue('C'.$iRow, $v->product_description)
							                              ->setCellValue('D'.$iRow, $v->product_price)
							                              ->setCellValue('E'.$iRow, $v->product_weight)
							                              ->setCellValue('F'.$iRow, $v->product_min_order)
							                              ->setCellValue('G'.$iRow, $product_status)
							                              ->setCellValue('H'.$iRow, $product_stock)
							                              ->setCellValue('I'.$iRow, $product_etalase)
							                              ->setCellValue('J'.$iRow, $product_preorder)
							                              ->setCellValue('K'.$iRow, $product_preorder_day)
							                              ->setCellValue('L'.$iRow, $product_condition)
							                              ->setCellValue('M'.$iRow, $product_image[0])
							                              ->setCellValue('N'.$iRow, $product_image[1])
							                              ->setCellValue('O'.$iRow, $product_image[2])
							                              ->setCellValue('P'.$iRow, $product_image[3])
							                              ->setCellValue('Q'.$iRow, $product_image[4])
							                              ->setCellValue('R'.$iRow, $product_video[0])
							                              ->setCellValue('S'.$iRow, $product_video[1])
							                              ->setCellValue('T'.$iRow, $product_video[2]);
							$iRow += 1;
						}

						$objPHPExcel->getActiveSheet()->setTitle('Scraper');
						$objPHPExcel->setActiveSheetIndex(0);

						$filename = $scraper_source_url.'_'.$scraper_name;

						// Redirect output to a client’s web browser (Excel5)
						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="'.$filename.'_'.convDateFilename($timestamp).'.xls"');
						header('Cache-Control: max-age=0');
						// If you're serving to IE 9, then the following may be needed
						header('Cache-Control: max-age=1');

						// If you're serving to IE over SSL, then the following may be needed
						header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
						header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
						header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
						header ('Pragma: public'); // HTTP/1.0

						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
						$objWriter->save('php://output');

					} else if($scraper_source_id == 2){ // BUKALAPAK

					} else if($scraper_source_id == 3){ // SHOPEE

					} else { // XLS DEFAULT

					}

					if(($optionsFoto == 2) && (count($download_img) > 0)){
						$this->jCfg['download_img']['filename'] = $filename;
						$this->jCfg['download_img']['files']    = $download_img;
						$this->_releaseSession();
					}
				} else {
					echo 'Tidak ada data produk yang ingin di export...';
				}
			} else {
				echo 'Anda tidak memiliki akses...';
			}
		} else {
			echo 'Tidak ada data yang ingin di export...';
		}
	}

	function download(){
		$timestamp = timestamp();
		$download_img = isset($this->jCfg['download_img'])?$this->jCfg['download_img']:array();
		if(count($download_img) > 0){
			echo '<div style="position:absolute;width:100%;top:20px;text-align:center;"><img style="width:50px;" src="'.base_url().'assets/admin/images/loader.gif"/><div>Mohon tunggu, sedang melakuan pengambilan images...</div></div>';
			// $files = array('https://ecs7.tokopedia.net/img/product-1/2017/11/16/92798492/92798492_55b96cef-c1e2-4932-81f1-d404af49f683_800_800.jpg');
			$files = $download_img['files'];
			if(count($files) > 0){
				$zip = new ZipArchive();
			    $tmp_file = tempnam('.','');
			    $zip->open($tmp_file, ZipArchive::CREATE);
			    foreach($files as $file){
			        $download_file = file_get_contents($file);
			        $zip->addFromString(basename($file), $download_file);
			    }
			    $zip->close();

			    $filename = $download_img['filename'];
			    header('Content-Type: application/zip');
			    header('Content-disposition: attachment; filename='.$filename.'_'.convDateFilename($timestamp).'.zip');
			    header('Content-Length: ' . filesize($tmp_file));
			    readfile($tmp_file);
			}
		} else {
			echo 'Tidak ada images yang ingin di download...';
		}
	}

	function example_export_xls(){
	// 	/** Error reporting */
	// 	error_reporting(E_ALL);
	// 	ini_set('display_errors', TRUE);
	// 	ini_set('display_startup_errors', TRUE);
	// 	date_default_timezone_set('Asia/Bangkok');

	// 	$timestamp = timestamp();

	// 	// Create new PHPExcel object
	// 	$objPHPExcel = new PHPExcel();

	// 	// Set document properties
	// 	$objPHPExcel->getProperties()->setCreator("Programmer Nakal")
	// 								 ->setLastModifiedBy("Programmer Nakal")
	// 								 ->setTitle("Scraper Data Produk")
	// 								 ->setSubject("Scraper Data Produk")
	// 								 ->setDescription("Scraper Data Produk")
	// 								 ->setKeywords("Scraper Data Produk")
	// 								 ->setCategory("Scraper");

	// 	// Add some data
	// 	$objPHPExcel->setActiveSheetIndex(0)
	// 	            ->setCellValue('A1', 'Hello')
	// 	            ->setCellValue('B2', 'world!')
	// 	            ->setCellValue('C1', 'Hello')
	// 	            ->setCellValue('D2', 'world!');

	// 	// Miscellaneous glyphs, UTF-8
	// 	$objPHPExcel->setActiveSheetIndex(0)
	// 	            ->setCellValue('A4', 'Miscellaneous glyphs')
	// 	            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

	// 	// Rename worksheet
	// 	$objPHPExcel->getActiveSheet()->setTitle('Simple');


	// 	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	// 	$objPHPExcel->setActiveSheetIndex(0);

	// 	$filename = '01smap'.$timestamp.'.xls';

	// 	// Redirect output to a client’s web browser (Excel5)
	// 	header('Content-Type: application/vnd.ms-excel');
	// 	header('Content-Disposition: attachment;filename="'.$filename.'"');
	// 	header('Cache-Control: max-age=0');
	// 	// If you're serving to IE 9, then the following may be needed
	// 	header('Cache-Control: max-age=1');

	// 	// If you're serving to IE over SSL, then the following may be needed
	// 	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	// 	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	// 	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	// 	header ('Pragma: public'); // HTTP/1.0

	// 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	// 	$objWriter->save('php://output');
	// 	exit;
	}

	function copy_image(){
		// $timestamp  = timestamp();
		// $get_source = get_source($scraper_source_id);
		// $pathImg    = 'assets/scraper/'.$get_source['url'].'/'.$scraper_name.'/'.getYearMonthDate($timestamp).'/';
		// if (!file_exists($pathImg)) { mkdir($pathImg, 0777, true); }
		// $expImg = explode(',', $product_image);
		// foreach ($expImg as $v) {
		// 	$rand = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
		// 	// $filename = pathinfo($v, PATHINFO_FILENAME);
		// 	$ext = pathinfo($v, PATHINFO_EXTENSION);
		// 	$newImg = $pathImg.$rand.'.'.$ext;
		// 	file_put_contents($newImg, file_get_contents($v));
		// 	$product_image_local .= ($product_image_local==''?'':',').base_url().$newImg;
		// }
	}

	function contoh_dom(){
		// $product_url = '';
		// $contents2   = file_get_contents($product_url);
		// $dom2 = new domDocument;
		// @$dom2->loadHTML($contents2);
		// $dom2->preserveWhiteSpace = true;
		// $dom2->formatOutput = true;
		// $xpath = new DomXpath($dom2);

		// $i = cleanSpace($dom2->getElementById('product-id')->getAttribute('value'));
		// $ii = cleanSpace($dom2->getElementById('p-info-condition')->nodeValue);

		// $cat_box = $xpath->query('//ul[@class="search-cat-box breadcrumb sf-menu  ml-20 "]/li');
	 //    foreach($cat_box as $key2 => $val2) {
	 //    	if($key2 == 2){ $product_category_1 = cleanSpace($val2->nodeValue); }
	 //    	if($key2 == 4){ $product_category_2 = cleanSpace($val2->nodeValue); }
	 //    	if($key2 == 6){ $product_category_3 = cleanSpace($val2->nodeValue); }
		// }

		// $po_type_time = $dom2->getElementById('po_type_time')->nodeValue;
		// if($po_type_time != ''){
		// 	$product_preorder  	   = 1;
		// 	$product_preorder_day  = cleanSpace(preg_replace('/[^0-9]/', '', $po_type_time));
		// }

	 //    $product_image = '';
		// foreach($xpath->query('//img[@class="prod-img-thumb"]') as $img){
		// 	$image = str_replace("cache/100-square/", "", $img->getAttribute('src'));
		//     $product_image .= ($product_image==''?'':',').$image;
		// }

		// $product_description = $dom2->saveHTML($xpath->query('//p[@itemprop="description"]')->item(0));
		// $product_description = str_replace('<p itemprop="description">','',$product_description);
		// $product_description = str_replace('</p>','',$product_description);
	}

	function jquery(){
	// 	var jquery simple terpakai
	// 	c total
	// 	d data
	// 	e err
	// 	h header
	// 	i int
	// 	l layout
	// 	m msg
	// 	n kumpulan semua produk scrap
	// 	p product
	// 	r result
	// 	s item product
	// 	t token
	// 	u user id _encrypt

	// 	function
	// 	a step1
	// 	b step2
	// 	c step3
	}

}
