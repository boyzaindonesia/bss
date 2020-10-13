<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class ajax_function extends FrontController {
	function __construct()
	{
		parent::__construct();

	}

	function index(){
		// debugCode('a');

	}

	function get_marketplace_new_orders(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';
		$data['count']  = '';
		$data['result'] = array();

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'getdata' ){
			$thisVal = dbClean(trim($_POST['thisVal']));
			$count   = 0;
			if($thisVal == "shopee"){
				// $data['result'][] = array("nama_product" => "aaa");
				$arr_source_link   = array('https://seller.shopee.co.id/portal/sale/ship?categoryId=24');
				// $arr_source_link[] = array('https://seller.shopee.co.id/portal/sale/ship?categoryId=4');

				// $scraper_link = 'https://seller.shopee.co.id/portal/sale/ship?categoryId=24';
				$scraper_link = 'https://seller.shopee.co.id/portal/sale/517457141';

				// foreach ($arr_source_link as $scraper_link) {
					$contents2 = file_get_contents($scraper_link);

					// $dom2 = new domDocument;
					// @$dom2->loadHTML($contents2);
					// $dom2->preserveWhiteSpace = true;
					// $dom2->formatOutput = true;
					// $xpath = new DomXpath($dom2);

					// $product_description = $dom2->saveHTML($xpath->query('//div[@id="shopee-powerseller-root"]')->item(0));
					// $po_type_time = $dom2->getElementById('shopee-powerseller-root')->nodeValue;
					// $product_description = $dom2->saveHTML($xpath->query('//p[@itemprop="description"]')->item(0));

				// 	$list_box = $xpath->query('//div[@class="mass-ship-list-item ember-view"]');
				//     foreach($list_box as $key2 => $val2) {
				//     	// mass-ship-list-item__orderid mass-ship-list-item__orderid--width
				//     	$count += 1;
				// 	}
					// $html = '';
					// foreach ($xpath->query('//div[@id="shopee-powerseller-root"]/node()') as $node)
					// {
					//     $html .= $xml->saveHTML($node);
					// }

					// $shop_id       = $dom2->getElementById('shopee-powerseller-root')->childNodes;

		// $product_description = $dom2->saveHTML($xpath->query('//span[@class="shopee-switch-button__title"]')->item(0));
					// debugCode($html);
				// 	$data['result'][] = array("nama_product" 	=> "aaa",
				// 							   "content" 		=> $contents2
				// 							);
				// }

					// debugCode($po_type_time);
					$data['count']  = $product_description;

			}

		}

		die(json_encode($data));
		exit();
	}

	function get_product_code(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';
		$data['result'] = array();

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'getdata' ){
			$this->db->order_by("product_id", "asc");
			$m = $this->db->get_where("mt_product",array(
				"product_id !="	=> '0'
			))->result();

			$product_code = array();
			foreach ($m as $key => $value) {
				$product_code[] = $value->product_code;
			}

			$data['result'] = $product_code;
		}

		die(json_encode($data));
		exit();
	}

	function autocomplete_product(){
	    $error 	= true;
	    $msg 	= '';
		$rows 	= array();
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'getdata' ){
			$par_filter = array();
			foreach ($_POST as $key => $value) {
				$par_filter[$key] = $value;
			}

			$this->load->model("mdl_product","M");
			$this->data_table = $this->M->data_product_front($par_filter);
			$rows['product']  = $this->data_table;

			$store_id = 1;
			if(isset($par_filter['reseller_orders']) && $par_filter['reseller_orders'] == true){
				$user_id 	  = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
				$store_id     = get_user_store($user_id);
			}

			$product_thumb = array();
            foreach ($rows['product']['result'] as $key => $value) {

				if($store_id != 1){
					$value['product_price_sale'] = get_reseller_price($store_id, $value['product_id']);
					$value['product_price_discount'] = "0";
				}

                $product_thumb[] = array('id'  		 	 => $value['product_id'],
			                            'label'     	 => $value['product_name'],
			                            'category'  	 => $value['product_category_title'],
			                            'root_category'  => $value['root_product_category'],
			                            'price_sale' 	 => $value['product_price_sale'],
			                            'price_discount' => $value['product_price_discount'],
			                            'status_id' 	 => $value['product_status_id'],
			                            'status_name' 	 => get_name_product_status($value['product_status_id']),
			                            'image'     	 => get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($value['product_id'])),
			                            'href'      	 => base_url().'admin/product/view/'.$value['product_id'].'-'.changeEnUrl($value['product_name']),
			                            'href_front'     => base_url().$value['url_product_category'].'/'.$value['url_product'].'/'
		                      );
            }

			$rows['product_thumb']  = $product_thumb;
		}

		die(json_encode($rows));
		exit();
	}

	// function autocomplete_product_2(){
	// 	$data = array();
	// 	$data['err'] = true;
	// 	$data['msg'] = '';
	// 	$data['product'] = array();

	// 	if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check' ){
	// 		$thisVal       = dbClean(trim($_POST['thisVal']));
	// 		if(trim($thisVal)!=''){
	// 			$this->load->model("mdl_product","M");
	// 			$par_filter['product_status_id'] = '1';
	// 			$par_filter['keyword'] = $thisVal;
	// 			$this->data_table = $this->M->data_product_front($par_filter);
	// 			$data['product']  = $this->data_table;
	// 		}
	// 	}

	// 	die(json_encode($data));
	// 	exit();
	// }

	function autocomplete_member(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';
		$data['empty_member'] = true;
		$data['empty_others'] = true;
		$data['result'] = array();

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			// $bold_thisVal  = '<strong>'.$thisVal.'</strong>';

			// $this->DATA->table="mt_orders_shipping";
			if(trim($thisVal)!=''){
				$i = 0;

				$user_id 	= isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
				$store_id  	= get_user_store($user_id);

				$v = $this->db->group_by('orders_shipping_address')->get_where("mt_print_address",array(
					'orders_shipping_name LIKE' => '%'.$thisVal.'%',
					'store_id' 					=> $store_id
				))->result();
				if(!empty($v)){
					foreach($v as $r => $v){
						$data['empty_others'] = false;
						$data['err'] = false;
						$data['result'][$i] = array(
							'name' 			=> $v->orders_shipping_name,
							'email' 		=> $v->orders_shipping_email,
							'address' 		=> $v->orders_shipping_address,
							'city' 			=> $v->orders_shipping_city,
							'city_name' 	=> getCitySet($v->orders_shipping_city),
							'province' 		=> $v->orders_shipping_province,
							'province_name' => getProvSet($v->orders_shipping_province),
							'postal_code' 	=> $v->orders_shipping_postal_code,
							'phone' 		=> $v->orders_shipping_phone
						);

						$i += 1;
					}
				} else {
					$data['empty_others'] = true;
				}

				$v = $this->db->group_by('orders_shipping_address')->get_where("mt_orders_shipping",array(
					'orders_shipping_name LIKE' => '%'.$thisVal.'%'
				))->result();
				if(!empty($v)){
					foreach($v as $r => $v){
						$data['empty_member'] = false;
						$data['err'] = false;
						$data['result'][$i] = array(
							'name' 			=> $v->orders_shipping_name,
							'email' 		=> $v->orders_shipping_email,
							'address' 		=> $v->orders_shipping_address,
							'city' 			=> $v->orders_shipping_city,
							'city_name' 	=> getCitySet($v->orders_shipping_city),
							'province' 		=> $v->orders_shipping_province,
							'province_name' => getProvSet($v->orders_shipping_province),
							'postal_code' 	=> $v->orders_shipping_postal_code,
							'phone' 		=> $v->orders_shipping_phone
						);

						$i += 1;
					}
				} else {
					$data['empty_member'] = true;
				}

				if($data['empty_member'] == true && $data['empty_others'] == true){
					$data['err'] = true;
					$data['msg'] = 'No matching records.';
				}

			}
		}

		die(json_encode($data));
		exit();
	}


	function autocomplete_print_address(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';
		$data['empty_others'] = true;
		$data['result'] = array();

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			// $bold_thisVal  = '<strong>'.$thisVal.'</strong>';

			// $this->DATA->table="mt_orders_shipping";
			if(trim($thisVal)!=''){
				$i = 0;

				$user_id 	= isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
				$store_id  	= get_user_store($user_id);

				$v = $this->db->group_by('orders_shipping_address')->get_where("mt_print_address",array(
					'orders_shipping_name LIKE' => '%'.$thisVal.'%',
					'store_id' 					=> $store_id
				))->result();
				if(!empty($v)){
					foreach($v as $r => $v){
						$data['empty_others'] = false;
						$data['err'] = false;
						$data['result'][$i] = array(
							'name' 			=> $v->orders_shipping_name,
							'email' 		=> $v->orders_shipping_email,
							'address' 		=> $v->orders_shipping_address,
							'city' 			=> $v->orders_shipping_city,
							'city_name' 	=> getCitySet($v->orders_shipping_city),
							'province' 		=> $v->orders_shipping_province,
							'province_name' => getProvSet($v->orders_shipping_province),
							'postal_code' 	=> $v->orders_shipping_postal_code,
							'phone' 		=> $v->orders_shipping_phone
						);

						$i += 1;
					}
				} else {
					$data['empty_others'] = true;
				}

				if($data['empty_others'] == true){
					$data['err'] = true;
					$data['msg'] = 'No matching records.';
				}

			}
		}

		die(json_encode($data));
		exit();
	}

	function get_province_city(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';
		$data['result'] = array();

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check' ){
			$thisVal = dbClean(trim($_POST['thisVal']));
			if(trim($thisVal)!=''){
				$province = $thisVal;
				$city = '';
				$data['result'] = option_province_city($province,$city);
			}
		}

		die(json_encode($data));
		exit();
	}

	function subscribe(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if( isset($_POST['email']) && $_POST['email'] != '' ){
			$email = dbClean(trim($_POST['email']));
			if(trim($email) != ''){
				$subscribe_id = "";
				$v = $this->db->get_where("mt_subscribe",array(
					'subscribe_email' => $email
				),1,0)->row();
				if(count($v) > 0){
					if($v->subscribe_istrash == '0'){
						$data['err'] = true;
						$data['msg'] = 'Email anda sudah terdaftar...';
					} else {
						$data['err']  = false;
						$subscribe_id = $v->subscribe_id;
					}
				} else {
					$data['err'] = false;
				}

				if($data['err'] == false){
					$data1 = array(
						'subscribe_email'		=> $email,
						'subscribe_istrash' 	=> 0,
						'subscribe_date'		=> timestamp()
					);

					$this->DATA->table="mt_subscribe";
					$a = $this->_save_master(
						$data1,
						array(
							'subscribe_id' => $subscribe_id
						)
					);

					$data['err'] = false;
					$data['msg'] = 'Terima kasih sudah berlangganan, nantikan penawaran terbaik dari kami...';
				}

			}
		}

		die(json_encode($data));
		exit();
	}

	function get_ajax_link($id=''){
		$msg     = '';
		$content = '';
		$id  = dbClean(trim($id));
		if(trim($id) != ''){
			switch (trim($id)) {
				case '1':
					$dataArticle = get_data_article();
				    if(count($dataArticle) > 0){
						foreach($dataArticle as $r){
						$content .= '<tr>
						    <td class="nobr text-center">'.$r->article_id.'.</td>
						    <td>'.$r->article_title.'</td>
						    <td class="nobr">'.get_category_name($r->article_category_id).'</td>
						    <td class="nobr text-center">'.($r->article_status=='1'?'<span class="label label-success">Active</span>':'<span class="label label-danger">Non Active</span>').'</td>
						    <td class="nobr text-center">
								<a href="javascript:void(0)" onclick="setArticle('."'".$r->article_id.'. '.$r->article_title."'".','."'1'".','."'form-article'".');" class="btn btn-danger btn-xs">Select</a>
						    </td>
						</tr>';
						}
				    }
					break;
				case '3':
					$dataArticle = get_data_product();
				    if(count($dataArticle) > 0){
						foreach($dataArticle as $r){
						$content .= '<tr>
						    <td class="nobr text-center">'.$r->product_id.'.</td>
						    <td>'.$r->product_name.'</td>
						    <td class="nobr">'.get_product_category_name($r->product_category_id).'</td>
						    <td class="nobr text-center">'.($r->product_show_id=='1'?'<span class="label label-success">Tampil</span>':'<span class="label label-danger">Tidak Tampil</span>').'</td>
						    <td class="nobr text-center">
								<a href="javascript:void(0)" onclick="setArticle('."'".$r->product_id.'. '.$r->product_name."'".','."'3'".','."'form-produk'".');" class="btn btn-danger btn-xs">Select</a>
						    </td>
						</tr>';
						}
				    }
					break;
				case '4':
					$dataArticle = front_get_category_menu();
				    if(count($dataArticle) > 0){
						foreach($dataArticle as $r){
						$content .= '<tr>
						    <td class="nobr text-center">'.$r->product_category_id.'.</td>
						    <td>'.$r->product_category_title.'</td>
						    <td class="nobr text-center">'.($r->product_category_status=='1'?'<span class="label label-success">Active</span>':'<span class="label label-danger">Non Active</span>').'</td>
						    <td class="nobr text-center">
								<a href="javascript:void(0)" onclick="setArticle('."'".$r->product_category_id.'. '.$r->product_category_title."'".','."'4'".','."'form-category-produk'".');" class="btn btn-danger btn-xs">Select</a>
						    </td>
						</tr>';
						}
				    }
					break;
				case '5':
					$dataArticle = front_load_count_gallery();
				    if(count($dataArticle) > 0){
						foreach($dataArticle as $r){
						$content .= '<tr>
						    <td class="nobr text-center">'.$r->gallery_id.'.</td>
							<td class="">
							    <img src="'.get_image(base_url()."assets/collections/gallery/thumb/".$r->gallery_images).'" class="avatar mfp-fade">
							</td>
						    <td>'.$r->gallery_name.'</td>
						    <td class="nobr text-center">'.($r->gallery_status=='1'?'<span class="label label-success">Active</span>':'<span class="label label-danger">Non Active</span>').'</td>
						    <td class="nobr text-center">
								<a href="javascript:void(0)" onclick="setArticle('."'".$r->gallery_id.'. '.$r->gallery_name."'".','."'5'".','."'form-gallery'".');" class="btn btn-danger btn-xs">Select</a>
						    </td>
						</tr>';
						}
				    }
					break;
				default:
					# code...
					break;
			}
			$msg = 'success';
		}

		$return = array('msg' => $msg,'content' => $content);
		die(json_encode($return));
		exit();
	}

	function generate_short_link_code(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';
		$data['result'] = array();

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'getdata' ){
			$short_link_code = generateUniqueToken(6,'mt_short_link','short_link_code');
			$data['err'] = false;
			$data['msg'] = $short_link_code;
		}

		die(json_encode($data));
		exit();
	}

}
