<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class product extends FrontController {
	var $cur_menu = '';

	function __construct()
	{
		parent::__construct();

		$this->DATA->table = "mt_product";
		$this->load->model("mdl_product","M");
		$this->load->model("mdl_product_apps","MA");

		$this->upload_path="./assets/collections/product/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 100, 'quality'	=> '90%'),
			array('name'	=> 'small','width'	=> 350, 'quality'	=> '90%')
		);

		// if (isset($_SERVER['HTTP_ORIGIN'])) {
		// 	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		// 	header('Access-Control-Allow-Credentials: true');
	 //        header('Access-Control-Max-Age: 86400');    // cache for 1 day
	 //    }

	 //    // Access-Control headers are received during OPTIONS requests
	 //    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	 //    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
	 //    		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	 //    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
	 //    		header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	 //    	exit(0);
	 //    }
	}

	function index(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
		$result = array();
		$reseller_id = "";
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		 = mysql_real_escape_string($request->user_id);
            $store_id    		 = mysql_real_escape_string($request->store_id);
            $product_id 		 = mysql_real_escape_string($request->product_id);
            $product_category_id = mysql_real_escape_string($request->product_category_id);
            $product_show_id 	 = mysql_real_escape_string($request->product_show_id);
            $product_status_id	 = mysql_real_escape_string($request->product_status_id);
            $token  			 = mysql_real_escape_string($request->token);
            $thisAction  		 = mysql_real_escape_string($request->thisAction);

            $type_result  		 = mysql_real_escape_string($request->type_result);
            $date_start  		 = mysql_real_escape_string($request->date_start);
            $date_end  			 = mysql_real_escape_string($request->date_end);
            $order_by  			 = mysql_real_escape_string($request->order_by);
            $order_dir  		 = mysql_real_escape_string($request->order_dir);
            $offset  			 = mysql_real_escape_string($request->offset);
            $limit  			 = mysql_real_escape_string($request->limit);
            $keyword  			 = mysql_real_escape_string($request->keyword);

			if($thisAction == 'getdata'){

				$cek_user = $this->db->get_where("mt_app_user",array(
					"user_id"		=> $user_id,
					"token_apps"	=> $token
				),1,0)->row();
				if(count($cek_user) > 0){
					if($cek_user->user_group == "5"){ $reseller_id = get_user_store($user_id); }

					$colum = "";
					$param = array(
						''												=> 'Semua Pencarian...',
						'mt_product.product_name'						=> 'Judul',
						'mt_product.product_name_simple'				=> 'Nama Singkat',
						'mt_product.product_code'						=> 'Kode',
						'mt_product_category.product_category_title'	=> 'Kategori'
					);

					$par_filter = array(
						"store_id"			  => ($store_id!=""?$store_id:'1'),
						"reseller_id"		  => $reseller_id,
						"product_id" 		  => ($product_id!=""?$product_id:NULL),
						"product_category_id" => ($product_category_id!=""?$product_category_id:NULL),
						"product_status_id"	  => ($product_status_id!=""?$product_status_id:NULL),
						"product_show_id" 	  => ($product_show_id!=""?$product_show_id:NULL),
			            "type_result"         => ($type_result!=""?$type_result:''),
			            "date_start"          => ($date_start!=""?$date_start:''),
			            "date_end"            => ($date_end!=""?$date_end:''),
			            "order_by"            => ($order_by!=""?$order_by:''),
			            "order_dir"           => ($order_dir!=""?$order_dir:''),
			            "offset"              => ($offset!=0?$offset:0),
			            "limit"               => ($limit!=0?$limit:1000),
			            "colum"               => ($colum!=""?$colum:''),
			            "keyword"             => ($keyword!=""?$keyword:''),
			            "param"               => ($param!=""?$param:NULL)
					);

					$data_product = $this->M->data_product($par_filter);

					$result = $data_product['data'];
					$total  = $data_product['total'];
					$error  = false;
					$msg    = "Produk ditemukan..";
				} else {
					$error = true;
					$msg   = "Anda tidak mempunyai hak akses.";
				}
			}
		}

		$newProductShow = array();
		for ($i=0; $i < 2; $i++) {
	    	switch ($i) {
	    		case 0: $id = "1"; $name = "Tampil"; break;
	    		case 1: $id = "2"; $name = "Tidak Tampil"; break;
	    		default: $id = ""; $name = ""; break;
	    	}
			$newProductShow[$i]->id   = $id;
			$newProductShow[$i]->name = $name;
		}

		$newProductStatus = array();
		$product_status = get_product_status();
		foreach ($product_status as $key => $val) {
			$newProductStatus[$key]->id   = $val->product_status_id;
			$newProductStatus[$key]->name = $val->product_status_name;
		}

		$newProductCategory = array();
		$category = $this->db->order_by("position", "asc")->get_where("mt_product_category",array(
			"product_category_istrash"	=> '0',
			"product_category_status"	=> '1',
			"product_category_parent_id !="	=> '0'
		))->result();
		foreach ($category as $key => $val) {
			$newProductCategory[$key]->id    = $val->product_category_id;
			$newProductCategory[$key]->name = $val->product_category_title;
		}

		$rows['numrows']  = $total;
		$rows['result']   = $result;
		$rows['product_category'] = $newProductCategory;
		$rows['product_show']   = $newProductShow;
		$rows['product_status'] = $newProductStatus;
		$rows['barcode']  = get_list_barcode_product($store_id, $reseller_id);

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function list_product_by_group(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
		$result = array();
		$reseller_id = "";
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		 = mysql_real_escape_string($request->user_id);
            $store_id    		 = mysql_real_escape_string($request->store_id);
            $token  			 = mysql_real_escape_string($request->token);
            $thisAction  		 = mysql_real_escape_string($request->thisAction);

            $product_group_id  	 = mysql_real_escape_string($request->product_group_id);
            $product_show_id  	 = mysql_real_escape_string($request->product_show_id);
            $product_status_id   = mysql_real_escape_string($request->product_status_id);
            $type_result  		 = mysql_real_escape_string($request->type_result);
            $date_start  		 = mysql_real_escape_string($request->date_start);
            $date_end  			 = mysql_real_escape_string($request->date_end);
            $order_by  			 = mysql_real_escape_string($request->order_by);
            $order_dir  		 = mysql_real_escape_string($request->order_dir);
            $offset  			 = mysql_real_escape_string($request->offset);
            $limit  			 = mysql_real_escape_string($request->limit);
            $keyword  			 = mysql_real_escape_string($request->keyword);

			if($thisAction == 'getdata'){

				$cek_user = $this->db->get_where("mt_app_user",array(
					"user_id"		=> $user_id,
					"token_apps"	=> $token
				),1,0)->row();
				if(count($cek_user) > 0){
					if($cek_user->user_group == "5"){ $reseller_id = get_user_store($user_id); }

					if($product_group_id == ""){
						$colum = "";
						$param = array(
							''						=> 'Semua Pencarian...',
							'product_group_name'	=> 'Judul',
						);

						$par_filter = array(
				            "product_group_id"    => ($product_group_id!=""?$product_group_id:''),
				            "store_id"            => ($store_id!=""?$store_id:'1'),
				            "type_result"         => ($type_result!=""?$type_result:''),
				            "product_group_show"  => "1",
				            "date_start"          => ($date_start!=""?$date_start:''),
				            "date_end"            => ($date_end!=""?$date_end:''),
				            "order_by"            => ($order_by!=""?$order_by:''),
				            "order_dir"           => ($order_dir!=""?$order_dir:''),
				            "offset"              => ($offset!=0?$offset:0),
				            "limit"               => ($limit!=0?$limit:1000),
				            "colum"               => ($colum!=""?$colum:''),
				            "keyword"             => ($keyword!=""?$keyword:''),
				            "param"               => ($param!=""?$param:NULL)
				        );

						$data = $this->MA->data_product_by_group($par_filter);
					} else {
						$colum = "";
						$param = array(
							''												=> 'Semua Pencarian...',
							'mt_product.product_name'						=> 'Judul',
							'mt_product.product_name_simple'				=> 'Nama Singkat',
							'mt_product.product_code'						=> 'Kode',
							'mt_product_category.product_category_title'	=> 'Kategori'
						);

						$par_filter = array(
							"product_group_id" 	  => ($product_group_id!=""?$product_group_id:NULL),
							"store_id"			  => ($store_id!=""?$store_id:'1'),
							"reseller_id"		  => $reseller_id,
							"product_status_id"	  => ($product_status_id!=""?$product_status_id:NULL),
							"product_show_id" 	  => ($product_show_id!=""?$product_show_id:NULL),
				            "type_result"         => ($type_result!=""?$type_result:''),
				            "date_start"          => ($date_start!=""?$date_start:''),
				            "date_end"            => ($date_end!=""?$date_end:''),
				            "order_by"            => ($order_by!=""?$order_by:''),
				            "order_dir"           => ($order_dir!=""?$order_dir:''),
				            "offset"              => ($offset!=0?$offset:0),
				            "limit"               => ($limit!=0?$limit:1000),
				            "colum"               => ($colum!=""?$colum:''),
				            "keyword"             => ($keyword!=""?$keyword:''),
				            "param"               => ($param!=""?$param:NULL)
						);

						$data = $this->M->data_product($par_filter);
					}

					$result = $data['data'];
					$total  = $data['total'];
					$error  = false;
					$msg    = "Produk ditemukan..";
				} else {
					$error = true;
					$msg   = "Anda tidak mempunyai hak akses.";
				}
			}
		}

		$i = 0;
		$newStore = array();
		$notId    = array("1","2");
		$store    = get_store();
		foreach ($store as $key => $val) {
			if(!in_array($val->store_id, $notId)){
				$newStore[$i]->id   = $val->store_id;
				$newStore[$i]->name = $val->store_name;
				$i += 1;
			}
		}

		$rows['numrows']  = $total;
		$rows['result']   = $result;
		$rows['store']    = $newStore;

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function list_barcode_product(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
		$result = array();
		$reseller_id = "";
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		= mysql_real_escape_string($request->user_id);
            $store_id    		= mysql_real_escape_string($request->store_id);
            $product_status_id  = mysql_real_escape_string($request->product_status_id);
            $token  			= mysql_real_escape_string($request->token);
            $thisAction  		= mysql_real_escape_string($request->thisAction);

			$cek_user = $this->db->get_where("mt_app_user",array(
				"user_id"		=> $user_id,
				"token_apps"	=> $token
			),1,0)->row();
			if(count($cek_user) > 0){
				if($thisAction == 'getdata'){
					if($cek_user->user_group == 5){ $reseller_id = get_user_store($user_id); }

					$result = get_list_barcode_product($store_id, $reseller_id, $product_status_id);
					$total  = count($result);
					$error  = false;
					$msg    = "Produk ditemukan..";
				}
			} else {
				$error = true;
				$msg   = "Anda tidak mempunyai hak akses.";
			}
		}

		$rows['numrows']  = $total;
		$rows['result']   = $result;

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function list_barcode_product_reseller(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
		$result = array();
		$reseller_id = "";
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		= mysql_real_escape_string($request->user_id);
            $store_id    		= mysql_real_escape_string($request->store_id);
            $token  			= mysql_real_escape_string($request->token);
            $thisAction  		= mysql_real_escape_string($request->thisAction);

			$cek_user = $this->db->get_where("mt_app_user",array(
				"user_id"		=> $user_id,
				"token_apps"	=> $token
			),1,0)->row();
			if(count($cek_user) > 0){
				if($thisAction == 'getdata'){
					if($cek_user->user_group == 5){ $reseller_id = get_user_store($user_id); }

					$result = get_list_barcode_product($store_id, $reseller_id);
					$total  = count($result);
					$error  = false;
					$msg    = "Produk ditemukan..";
				}
			} else {
				$error = true;
				$msg   = "Anda tidak mempunyai hak akses.";
			}
		}

        $i = 0;
        $newStore = array();
        $notId    = array("1","2");
        $store    = get_store();
        foreach ($store as $key => $val) {
            if(!in_array($val->store_id, $notId)){
                $newStore[$i]->id   = $val->store_id;
                $newStore[$i]->name = $val->store_name;
                $i += 1;
            }
        }

		$rows['numrows'] = $total;
		$rows['result']  = $result;
        $rows['store']   = $newStore;

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function list_product_reseller(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
		$result = array();
		$reseller_id = "";
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		 = mysql_real_escape_string($request->user_id);
            $store_id    		 = mysql_real_escape_string($request->store_id);
            $product_category_id = mysql_real_escape_string($request->product_category_id);
            $product_show_id 	 = mysql_real_escape_string($request->product_show_id);
            $product_status_id	 = mysql_real_escape_string($request->product_status_id);
            $token  			 = mysql_real_escape_string($request->token);
            $thisAction  		 = mysql_real_escape_string($request->thisAction);

            $type_result  		 = mysql_real_escape_string($request->type_result);
            $date_start  		 = mysql_real_escape_string($request->date_start);
            $date_end  			 = mysql_real_escape_string($request->date_end);
            $order_by  			 = mysql_real_escape_string($request->order_by);
            $order_dir  		 = mysql_real_escape_string($request->order_dir);
            $offset  			 = mysql_real_escape_string($request->offset);
            $limit  			 = mysql_real_escape_string($request->limit);
            $keyword  			 = mysql_real_escape_string($request->keyword);

			if($thisAction == 'getdata'){

				$cek_user = $this->db->get_where("mt_app_user",array(
					"user_id"		=> $user_id,
					"token_apps"	=> $token
				),1,0)->row();
				if(count($cek_user) > 0){
					if($cek_user->user_group == "5"){ $reseller_id = get_user_store($user_id); }

					$colum = "";
					$param = array(
						''												=> 'Semua Pencarian...',
						'mt_product.product_name'						=> 'Judul',
						'mt_product.product_name_simple'				=> 'Nama Singkat',
						'mt_product.product_code'						=> 'Kode',
						'mt_product_category.product_category_title'	=> 'Kategori'
					);

					$type_result = "list_product_reseller";
					$par_filter = array(
						"store_id"			  => ($store_id!=""?$store_id:'1'),
						"product_category_id" => ($product_category_id!=""?$product_category_id:NULL),
						"product_status_id"	  => ($product_status_id!=""?$product_status_id:NULL),
						"product_show"		  => ($product_show_id!=""?$product_show_id:NULL),
			            "type_result"         => ($type_result!=""?$type_result:''),
			            "date_start"          => ($date_start!=""?$date_start:''),
			            "date_end"            => ($date_end!=""?$date_end:''),
			            "order_by"            => ($order_by!=""?$order_by:''),
			            "order_dir"           => ($order_dir!=""?$order_dir:''),
			            "offset"              => ($offset!=0?$offset:0),
			            "limit"               => ($limit!=0?$limit:1000),
			            "colum"               => ($colum!=""?$colum:''),
			            "keyword"             => ($keyword!=""?$keyword:''),
			            "param"               => ($param!=""?$param:NULL)
					);

					$data = $this->M->data_product($par_filter);

					$result = $data['data'];
					$total  = $data['total'];
					$error  = false;
					$msg    = "Produk ditemukan..";
				} else {
					$error = true;
					$msg   = "Anda tidak mempunyai hak akses.";
				}
			}
		}

		$newProductShow = array();
		for ($i=0; $i < 2; $i++) {
	    	switch ($i) {
	    		case 0: $id = "1"; $name = "Tampil"; break;
	    		case 1: $id = "2"; $name = "Tidak Tampil"; break;
	    		default: $id = ""; $name = ""; break;
	    	}
			$newProductShow[$i]->id   = $id;
			$newProductShow[$i]->name = $name;
		}

		$newProductStatus = array();
		$product_status = get_product_status();
		foreach ($product_status as $key => $val) {
			$newProductStatus[$key]->id   = $val->product_status_id;
			$newProductStatus[$key]->name = $val->product_status_name;
		}

		$newProductCategory = array();
		$category = $this->db->order_by("position", "asc")->get_where("mt_product_category",array(
			"product_category_istrash"	=> '0',
			"product_category_status"	=> '1',
			"product_category_parent_id !="	=> '0'
		))->result();
		foreach ($category as $key => $val) {
			$newProductCategory[$key]->id    = $val->product_category_id;
			$newProductCategory[$key]->name = $val->product_category_title;
		}

		$i = 0;
		$newStore = array();
		$notId    = array("1","2");
		$store    = get_store();
		foreach ($store as $key => $val) {
			if(!in_array($val->store_id, $notId)){
				$newStore[$i]->id   = $val->store_id;
				$newStore[$i]->name = $val->store_name;
				$i += 1;
			}
		}

		$rows['numrows']  = $total;
		$rows['result']   = $result;
		$rows['product_category'] = $newProductCategory;
		$rows['product_show']   = $newProductShow;
		$rows['product_status'] = $newProductStatus;
		$rows['store']  = $newStore;

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function update_harga_reseller(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
		$result = array();
		$reseller_id = "";
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id    = mysql_real_escape_string($request->user_id);
            $store_id   = mysql_real_escape_string($request->store_id);
            $product_id = mysql_real_escape_string($request->product_id);
            $token 		= mysql_real_escape_string($request->token);
            $thisAction	= mysql_real_escape_string($request->thisAction);

            $store_price = array();
            foreach ($request as $key => $value) {
                if($key == "store_price"){
                    $store_price = $value;
                }
            }

			if($thisAction == 'save'){
				$cek_user = $this->db->get_where("mt_app_user",array(
					"user_id"		=> $user_id,
					"token_apps"	=> $token
				),1,0)->row();
				if(count($cek_user) > 0){
					// if($cek_user->user_group == "5"){ $reseller_id = get_user_store($user_id); }

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
					$log_desc = $store_price;

					if(count($newId) > 0){
						foreach ($newId as $key => $val) {
							$product_id = $val;
                            $json_price = json_decode($store_price);
							foreach ($json_price as $key2 => $val2) {
								$store_id = $val2->store_id;
								$price    = $val2->price;
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
							}
						}

						writeLog(array(
                            'log_user_type'     => "1", // Admin
                            'log_user_id'       => $user_id,
                            'log_role'          => NULL,
                            'log_type'          => "2", // Produk
                            'log_detail_id'     => NULL,
                            'log_detail_item'   => $log_detail_item,
                            'log_detail_qty'    => NULL,
                            'log_title_id'      => "8", // Perubahan Harga Reseller
                            'log_desc'          => $log_desc,
                            'log_status'        => "0"
                        ));

						$error  = false;
						$msg    = "Sukses merubah harga reseller.";
					} else {
						$error  = true;
						$msg    = "Produk tidak ditemukan.";
					}
				} else {
					$error = true;
					$msg   = "Anda tidak mempunyai hak akses.";
				}
			}
		}

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function get_detail_product(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
		$result = array();
		$reseller_id = "";
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		 = mysql_real_escape_string($request->user_id);
            $product_id 		 = mysql_real_escape_string($request->product_id);
            $token  			 = mysql_real_escape_string($request->token);
            $thisAction  		 = mysql_real_escape_string($request->thisAction);

			if($thisAction == 'getdata'){
				$cek_user = $this->db->get_where("mt_app_user",array(
					"user_id"		=> $user_id,
					"token_apps"	=> $token
				),1,0)->row();
				if(count($cek_user) > 0){
					if($cek_user->user_group == "5"){ $reseller_id = get_user_store($user_id); }

					$par_filter = array(
						"reseller_id"		  => $reseller_id,
						"product_id"		  => ($product_id!=""?$product_id:NULL),
			            "type_result"         => "",
						"offset"			  => 0,
						"limit"				  => 1,
						"param"				  => NULL
					);
					$data = $this->M->data_product($par_filter);

					$result = $data['data'];
					$total  = $data['total'];
					if($total > 0){
						$error  = false;
						$msg    = "Produk ditemukan..";
					} else {
						$error  = true;
						$msg    = "Produk tidak ditemukan..";
					}
				} else {
					$error = true;
					$msg   = "Anda tidak mempunyai hak akses.";
				}
			}
		}

		$rows['numrows']  = $total;
		$rows['result']   = $result;

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function update_stock_barcode_product(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		= mysql_real_escape_string($request->user_id);
            $store_id    		= mysql_real_escape_string($request->store_id);
            $product_barcode    = mysql_real_escape_string($request->product_barcode);
            $token  			= mysql_real_escape_string($request->token);
            $thisAction  		= mysql_real_escape_string($request->thisAction);

            if($product_barcode != ""){
				$cek_user = $this->db->get_where("mt_app_user",array(
					"user_id"		=> $user_id,
					"token_apps"	=> $token
				),1,0)->row();
				if(count($cek_user) > 0){
					$arrNoBarcode = explode("-", $product_barcode);
					$m = $this->db->get_where("mt_product",array(
						"product_code"	=> strtoupper($arrNoBarcode[0])
					),1,0)->row();
					if(count($m) > 0){
						$store_id = $m->store_id;
						$detail = $this->db->get_where("mt_product_detail",array(
							'product_id'	=> $m->product_id
						),1,0)->row();
						if(count($detail) > 0){
							$product_id = $m->product_id;
							if(count($arrNoBarcode) == 1){
								if($detail->product_stock_detail == '' || $detail->product_stock_detail == '[]'){
									$title_id = "";
									$new_qty  = 0;
									if($thisAction == 'minus'){
										if($detail->product_stock > 0){
											$title_id = "15";  // Pengurangan Stok Manual
											$new_qty  = ($detail->product_stock - 1);
											$new_qty  = ($new_qty > 0?$new_qty:0);
											$error    = false;
											$msg      = $m->product_name_simple." stok berhasil dikurangi. Stok saat ini ".($new_qty>0?"ada: ".$new_qty.".":"sudah habis.");
											if($new_qty==0){
												sendProductNotif(array(
					                                'user_id'       => $user_id,
					                                'store_id'      => $store_id,
					                                'product_id'    => $product_id,
					                                'product_item'  => NULL,
					                                'product_qty'   => $new_qty,
					                                'notif_title'   => "Produk sudah habis",
					                                'notif_desc'    => "",
					                                'notif_status'  => 1,
					                                'notif_notify'  => 3
					                            ));
											}
										}
									} else if($thisAction == 'plus'){
										$title_id = "14"; // Penambahan Stok Manual
										$new_qty  = ($detail->product_stock + 1);
										if($detail->product_stock == 0){
											sendProductNotif(array(
				                                'user_id'       => $user_id,
				                                'store_id'      => $store_id,
				                                'product_id'    => $product_id,
				                                'product_item'  => NULL,
				                                'product_qty'   => $new_qty,
				                                'notif_title'   => "Produk restock lagi",
				                                'notif_desc'    => "",
				                                'notif_status'  => 1,
				                                'notif_notify'  => 3
				                            ));
										}
										$error    = false;
										$msg      = $m->product_name_simple." stok berhasil ditambahkan. Stok saat ini ada: ".$new_qty;
									}
									$total_qty = $new_qty;
									if($error == false){
										$dataDetail = array(
											'product_status_id'			=> ($total_qty > 0?1:3),
											'product_stock'				=> $total_qty
										);
										$this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

										writeLog(array(
											'log_user_type' 	=> "1", // Admin
											'log_user_id' 		=> $user_id,
											'log_role' 			=> NULL,
											'log_type' 			=> "2", // Produk
											'log_detail_id' 	=> $product_id,
											'log_detail_item' 	=> NULL,
											'log_detail_qty' 	=> 1,
											'log_title_id' 		=> $title_id,
											'log_desc' 			=> NULL,
											'log_status' 		=> "0"
										));
									}
								} else {
									$error = true;
									$msg   = "Kode Barcode harus diakhiri dengan No Variasi Produk.";
								}
							} else if(count($arrNoBarcode) == 2){
								$title_id  = "";
								$log_item  = "";
								$new_qty   = 0;
								$status    = 0;
								$total_qty = 0;
								$found_sold = false;
								$found_restock = false;
								$found_variasi = false;
								$arr_stock = array();
								$arr_item  = array();
								$product_stock_detail = json_decode($detail->product_stock_detail);
								foreach ($product_stock_detail as $key3 => $value3) {
									$new_qty = $value3->qty;
									$status  = $value3->status;
									if($value3->id == $arrNoBarcode[1]){
										$found_variasi = true;
										if($thisAction == 'minus'){
											if($value3->qty > 0){
												$title_id = "15"; // Pengurangan Stok Manual
												$new_qty  = ($value3->qty - 1);
												$new_qty  = ($new_qty > 0?$new_qty:0);
												$error    = false;
												$msg      = $m->product_name_simple." ".$value3->name." stok berhasil dikurangi. Stok saat ini ".($new_qty>0?"ada: ".$new_qty.".":"sudah habis.");
												if($new_qty==0){
													$found_sold = true;
												}
											} else {
												$error   = true;
												$msg     = $m->product_name_simple." ".$value3->name." stok sudah habis.";
											}
										} else if($thisAction == 'plus'){
											$title_id = "14"; // Penambahan Stok Manual
											$new_qty  = ($value3->qty + 1);
											$error    = false;
											$msg      = $m->product_name_simple." ".$value3->name." stok berhasil ditambahkan. Stok saat ini ada: ".$new_qty;
											if($value3->qty==0){
												$found_restock = true;
											}
										}
										$status  = ($new_qty > 0?'1':'2');

										$arr_item[] = array('id' 		=> $value3->id,
															'name' 		=> $value3->name,
															'qty_old'  	=> $value3->qty,
					                                        'qty_new'  	=> $new_qty,
															'status' 	=> $status
														);
									}
									$arr_stock[] = array('id' 		=> $value3->id,
														 'name' 	=> $value3->name,
														 'color' 	=> $value3->color,
														 'qty' 		=> $new_qty,
														 'status' 	=> $status
													  );
									$total_qty += $new_qty;

									if($found_sold){
										sendProductNotif(array(
			                                'user_id'       => $user_id,
			                                'store_id'      => $store_id,
			                                'product_id'    => $product_id,
			                                'product_item'  => json_encode($arr_item),
			                                'product_qty'   => $total_qty,
			                                'notif_title'   => "Variasi produk sudah habis",
			                                'notif_desc'    => "",
			                                'notif_status'  => 1,
			                                'notif_notify'  => 3
			                            ));
									}
									if($found_restock){
										sendProductNotif(array(
			                                'user_id'       => $user_id,
			                                'store_id'      => $store_id,
			                                'product_id'    => $product_id,
			                                'product_item'  => json_encode($arr_item),
			                                'product_qty'   => $total_qty,
			                                'notif_title'   => "Variasi produk restock kembali",
			                                'notif_desc'    => "",
			                                'notif_status'  => 1,
			                                'notif_notify'  => 3
			                            ));
									}
								}

								if($found_variasi == false){
		                            $error   = true;
		                            $msg     = $m->product_name." variasi ".$arrNoBarcode[1]." tidak ditemukan.";
		                        }

								if($error == false){
									$dataDetail = array(
										'product_status_id'			=> ($total_qty > 0?1:3),
										'product_stock'				=> $total_qty,
										'product_stock_detail'		=> json_encode($arr_stock)
									);
									$this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

									writeLog(array(
										'log_user_type' 	=> "1", // Admin
										'log_user_id' 		=> $user_id,
										'log_role' 			=> NULL,
										'log_type' 			=> "2", // Produk
										'log_detail_id' 	=> $product_id,
										'log_detail_item' 	=> json_encode($arr_item),
										'log_detail_qty' 	=> 1,
										'log_title_id' 		=> $title_id,
										'log_desc' 			=> NULL,
										'log_status' 		=> "0"
									));
								} else {
									$msg   = ($msg!=""?$msg:"No Variasi tidak ditemukan.");
								}
	                        }
						} else {
							$error = true;
							$msg   = "Produk Detail tidak ditemukan.";
						}
					} else {
						$error = true;
						$msg   = "Kode Barcode tidak ditemukan.";
					}
				} else {
					$error = true;
					$msg   = "Anda tidak mempunyai hak akses.";
				}
			} else {
				$error = true;
				$msg   = "Kode Barcode belum diinput.";
			}
		}

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function update_product(){
		$error 	= true;
	    $msg 	= '';
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		 = mysql_real_escape_string($request->user_id);
            $product_id 		 = mysql_real_escape_string($request->product_id);
            $token  			 = mysql_real_escape_string($request->token);
            $thisAction  		 = mysql_real_escape_string($request->thisAction);

			$cek_user = $this->db->get_where("mt_app_user",array(
				"user_id"		=> $user_id,
				"token_apps"	=> $token
			),1,0)->row();
			if(count($cek_user) > 0){
				$product = $this->db->get_where("mt_product",array(
					'product_id'	=> $product_id
				),1,0)->row();
				if(count($product) > 0){
					$store_id = $product->store_id;
					if($thisAction == 'set_not_sale'){
						$detail = $this->db->get_where("mt_product_detail",array(
							'product_id'	=> $product_id
						),1,0)->row();
						if(count($detail) > 0){
							$log_item = "";
				            $log_qty  = $detail->product_stock;
							$this->db->update("mt_product",array("product_date_update"=>timestamp()),array("product_id" => $product_id));

							$data = array(
								'product_status_id'			=> 3
							);
							if($detail->product_stock_detail != ''){
								$arr_stock = array();
								$arr_item  = array();
								$product_stock_detail = json_decode($detail->product_stock_detail);
								foreach ($product_stock_detail as $key3 => $value3) {
									$arr_stock[] = array('id' 		=> $value3->id,
														 'name' 	=> $value3->name,
														 'color' 	=> $value3->color,
														 'qty' 		=> $value3->qty,
														 'status' 	=> 2
													  );

									$arr_item[] = array('id'       => $value3->id,
				                                        'name'     => $value3->name,
				                                        'qty'      => $value3->qty,
				                                        'status'   => $value3->status
				                                    );
								}
								$data['product_stock_detail'] = json_encode($arr_stock);
								$log_item = json_encode($arr_item);
							}

							$this->db->update("mt_product_detail",$data,array("product_detail_id"=>$detail->product_detail_id));

							writeLog(array(
								'log_user_type' 	=> "1", // Admin
								'log_user_id' 		=> $user_id,
								'log_role' 			=> NULL,
								'log_type' 			=> "2", // Produk
								'log_detail_id' 	=> $product_id,
								'log_detail_item' 	=> $log_item,
								'log_detail_qty' 	=> $log_qty,
								'log_title_id' 		=> "7", // Produk diset tidak dijual
								'log_desc' 			=> NULL,
								'log_status' 		=> "0"
							));

							sendProductNotif(array(
				                'user_id'       => $user_id,
				                'store_id'      => $store_id,
				                'product_id'    => $product_id,
				                'product_item'  => $log_item,
				                'product_qty'   => $log_qty,
				                'notif_title'   => "Produk diset Tidak Dijual",
				                'notif_desc'    => "",
				                'notif_status'  => 1,
				                'notif_notify'  => 3
			                ));

							$error = false;
							$msg   = "Berhasil set produk menjadi Tidak Dijual.";
						} else {
							$error = true;
							$msg   = "Produk detail tidak ditemukan.";
						}
					} else if($thisAction == 'set_sale'){
						$detail = $this->db->get_where("mt_product_detail",array(
							'product_id'	=> $product_id
						),1,0)->row();
						if(count($detail) > 0){
							$log_item = "";
				            $log_qty  = $detail->product_stock;
							$this->db->update("mt_product",array("product_date_update"=>timestamp()),array("product_id" => $product_id));

							$data = array(
								'product_status_id'			=> 1
							);
							if($detail->product_stock_detail != ''){
								$arr_stock = array();
								$arr_item  = array();
								$product_stock_detail = json_decode($detail->product_stock_detail);
								foreach ($product_stock_detail as $key3 => $value3) {
									$arr_stock[] = array('id' 		=> $value3->id,
														 'name' 	=> $value3->name,
														 'color' 	=> $value3->color,
														 'qty' 		=> $value3->qty,
														 'status' 	=> ($value3->qty>0?1:2)
													  );

									$arr_item[] = array('id'       => $value3->id,
				                                        'name'     => $value3->name,
				                                        'qty'      => $value3->qty,
				                                        'status'   => $value3->status
				                                    );
								}
								$data['product_stock_detail'] = json_encode($arr_stock);
								$log_item = json_encode($arr_item);
							}

							$this->db->update("mt_product_detail",$data,array("product_detail_id"=>$detail->product_detail_id));

							writeLog(array(
								'log_user_type' 	=> "1", // Admin
								'log_user_id' 		=> $user_id,
								'log_role' 			=> NULL,
								'log_type' 			=> "2", // Produk
								'log_detail_id' 	=> $product_id,
								'log_detail_item' 	=> $log_item,
								'log_detail_qty' 	=> $log_qty,
								'log_title_id' 		=> "6", // Produk Dijual Kembali
								'log_desc' 			=> NULL,
								'log_status' 		=> "0"
							));

							$error = false;
							$msg   = "Berhasil set produk menjadi Dijual.";
						} else {
							$error = true;
							$msg   = "Produk detail tidak ditemukan.";
						}
					} else if($thisAction == 'reset_stock'){
						$detail = $this->db->get_where("mt_product_detail",array(
							'product_id'	=> $product_id
						),1,0)->row();
						if(count($detail) > 0){
							$log_item = "";
				            $log_qty  = $detail->product_stock;

							$this->db->update("mt_product",array("product_date_update"=>timestamp()),array("product_id" => $product_id));

							$data = array(
								'product_status_id'			=> 3,
								'product_stock'				=> 0
							);
							if($detail->product_stock_detail != ''){
								$arr_stock = array();
								$arr_item  = array();
								$product_stock_detail = json_decode($detail->product_stock_detail);
								foreach ($product_stock_detail as $key3 => $value3) {
									$arr_stock[] = array('id' 		=> $value3->id,
														 'name' 	=> $value3->name,
														 'color' 	=> $value3->color,
														 'qty' 		=> 0,
														 'status' 	=> 2
													  );

									$arr_item[] = array('id'       => $value3->id,
				                                        'name'     => $value3->name,
				                                        'qty'      => $value3->qty,
				                                        'status'   => $value3->status
				                                    );
								}
								$data['product_stock_detail'] = json_encode($arr_stock);
								$log_item = json_encode($arr_item);
							}

							$this->db->update("mt_product_detail",$data,array("product_detail_id"=>$detail->product_detail_id));

							writeLog(array(
								'log_user_type' 	=> "1", // Admin
								'log_user_id' 		=> $user_id,
								'log_role' 			=> NULL,
								'log_type' 			=> "2", // Produk
								'log_detail_id' 	=> $product_id,
								'log_detail_item' 	=> $log_item,
								'log_detail_qty' 	=> $log_qty,
								'log_title_id' 		=> "5", // Reset Stok
								'log_desc' 			=> NULL,
								'log_status' 		=> "0"
							));

							$error = false;
							$msg   = "Berhasil reset stok produk.";
						} else {
							$error = true;
							$msg   = "Produk detail tidak ditemukan.";
						}
					} else if($thisAction == 'delete'){
						$this->db->update("mt_product",array("product_show"=>0,"product_istrash"=>1,"product_date_istrash"=>timestamp()),array("product_id" => $product_id));

						$image = $this->db->get_where("mt_product_image",array(
							"product_id"	=> $product_id
						))->result();
						$i = 1;
						foreach($image as $rD){
							$idD = $rD->image_id;
							$this->DATA->table = 'mt_product_image';
							$this->_delte_old_files_without_thumb(
								array(
									'field' => 'image_filename',
									'par'	=> array('image_id' => $idD)
							));
							$this->db->delete("mt_product_image",array('image_id' => $idD));
							$i += 1;
						}

						writeLog(array(
							'log_user_type' 	=> "1", // Admin
							'log_user_id' 		=> $user_id,
							'log_role' 			=> NULL,
							'log_type' 			=> "2", // Produk
							'log_detail_id' 	=> $product_id,
							'log_detail_item' 	=> NULL,
							'log_detail_qty' 	=> NULL,
							'log_title_id' 		=> "9", // Produk Dihapus
							'log_desc' 			=> NULL,
							'log_status' 		=> "0"
						));

						$error = false;
						$msg   = "Berhasil menghapus produk.";
					}
				} else {
					$error = true;
					$msg   = "Produk tidak ditemukan.";
				}
			} else {
				$error = true;
				$msg   = "Anda tidak mempunyai hak akses.";
			}
		}

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function update_product_stock(){
		$error 	= true;
	    $msg 	= '';
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		 = mysql_real_escape_string($request->user_id);
            $store_id     		 = mysql_real_escape_string($request->store_id);
            $product_id 		 = mysql_real_escape_string($request->product_id);
            // $stock_detail 		 = mysql_real_escape_string($request->stock_detail);
            $token  			 = mysql_real_escape_string($request->token);
            $thisAction  		 = mysql_real_escape_string($request->thisAction);

			$stock_detail = array();
            foreach ($request as $key => $value) {
                if($key == "stock_detail"){
                    $stock_detail = $value;
                }
            }

			$cek_user = $this->db->get_where("mt_app_user",array(
				"user_id"		=> $user_id,
				"token_apps"	=> $token
			),1,0)->row();
			if(count($cek_user) > 0){
				$product = $this->db->get_where("mt_product",array(
					'product_id'	=> $product_id
				),1,0)->row();
				if(count($product) > 0){
					$store_id = $product->store_id;
					$detail = $this->db->get_where("mt_product_detail",array(
						'product_id'	=> $product_id
					),1,0)->row();
					if(count($detail) > 0){
						$this->db->update("mt_product",array("product_date_update"=>timestamp()),array("product_id" => $product_id));

						$tempStr  = "";
                        $tempQty  = 0;
                        $tempItem = array();
                        $json_stock_detail = json_decode($stock_detail);
                        foreach ($json_stock_detail as $key2 => $val2){
                        	$tempQty += $val2->qty;
                        	if($val2->id != 0){
	                            $tempItem[$val2->id] = $val2->qty;
	                            $tempStr .= "id:".$val2->id." = ".$val2->qty."<br/>";
	                        }
                        }

                    	$error = false;
                        $new_stock = $tempQty;
						$product_stock = $detail->product_stock;
						if($thisAction == 'add'){
                        	$new_stock = $product_stock + $tempQty;
							$msg   = "Berhasil tambahkan stok produk.";
                        } else if($thisAction == 'minus'){
                        	$new_stock = $product_stock - $tempQty;
							$msg   = "Berhasil kurangi stok produk.";
                        } else if($thisAction == 'update'){
                        	$new_stock = $tempQty;
							$msg   = "Berhasil perbaharui stok produk.";
                        }
                        // $msg .= $tempStr;

                        $data_detail = array(
                            'product_stock'         => $new_stock,
                            'product_stock_detail'  => NULL
                        );
                        if($detail->product_stock_detail != "" && (count($tempItem) > 0) ){
                            $arr_stock  = array();
                            $product_stock_detail = json_decode($detail->product_stock_detail);
                            foreach ($product_stock_detail as $key2 => $val2){
                                $total_stock = $val2->qty;
                                if(array_key_exists($val2->id, $tempItem)){
                                    if($thisAction == 'add'){
	                                    $total_stock = $val2->qty + $tempItem[$val2->id];
			                        } else if($thisAction == 'minus'){
	                                    $total_stock = $val2->qty - $tempItem[$val2->id];
			                        } else if($thisAction == 'update'){
	                                    $total_stock = $tempItem[$val2->id];
			                        }

                                    if(($tempItem[$val2->id] != $val2->qty) && ($total_stock <= 0)){
                                        sendProductNotif(array(
                                            'user_id'       => $this->user_id,
                                            'store_id'      => $this->store_id,
                                            'product_id'    => $product_id,
                                            'product_item'  => $val2->id,
                                            'product_qty'   => $total_stock,
                                            'notif_title'   => "Produk item update stok",
                                            'notif_desc'    => "",
                                            'notif_status'  => 1,
                                            'notif_notify'  => 3
                                        ));
                                    }
                                }
                                $arr_stock[] = array('id'       => $val2->id,
                                                     'name'     => $val2->name,
                                                     'color'    => $val2->color,
                                                     'qty'      => $total_stock,
                                                     'status'   => ($total_stock>0?1:2)
                                                  );
                            }
                            $data_detail['product_stock_detail'] = json_encode($arr_stock);
                        }
                        $this->db->update("mt_product_detail",$data_detail,array("product_detail_id"=>$detail->product_detail_id));
					}
				} else {
					$error = true;
					$msg   = "Produk tidak ditemukan.";
				}
			} else {
				$error = true;
				$msg   = "Anda tidak mempunyai hak akses.";
			}
		}

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	// function get_user_location(){
	// 	$check_shift_history = check_shift_history('1', '2');
	// 	echo $check_shift_history;
	// 	$user_location 		  = get_shift_id('1');
	// 	$user_location 		= get_current_shift();
	// 	debugCode($user_location);
	// 	$arrUserLocation = array();
	// 	foreach ($user_location as $key => $val) {
	// 		$arrUserLocation[] = $val;
	// 	}
	// 	if(in_array(4, $user_location)){
	// 		echo 'true';
	// 		// debugCode(get_user_location(2));
	// 	} else {
	// 		echo 'false';
	// 	}
	// }

	// function check_history(){
	// 	$error 	= true;
	//     $msg 	= '';
	// 	$rows 	= array();
	// 	$newLocation = array();
	// 	$location_id          = "";
	// 	$location_name        = "";
	// 	$location_detail_id   = "";
	// 	$location_detail_name = "";
	// 	$location_shift_id    = "";
	// 	$location_shift_name  = "";
	// 	$nfc_tag_id           = "";
	//     // $postdata = file_get_contents("php://input");
	//     if (isset($_POST)) {
 //            $request    = json_decode($_POST["params"]);
 //            $nfc_tag_id = mysql_real_escape_string($request->nfc_tag_id);
 //            $user_id    = mysql_real_escape_string($request->user_id);
 //            $thisAction = mysql_real_escape_string($request->thisAction);

	// 		if($thisAction == 'check'){
	// 			$m = $this->db->get_where("mt_location_detail",array(
	// 				"nfc_tag_id"	=> $nfc_tag_id
	// 			),1,0)->row();
	// 			if(count($m) > 0){
	// 				$location_detail_id   = $m->location_detail_id;
	// 				$location_detail_name = $m->location_detail_name;
	// 				$location_id   		  = $m->location_id;
	// 				$location_name        = get_name_location($location_id);
	// 				$user_location 		  = get_user_location($user_id);

	// 				if(in_array($location_id, $user_location)){
	// 					$location_shift_id   = get_shift_id($location_id);
	// 					$location_shift_name = get_location_shift_name($location_shift_id);
	// 					$check_shift_history = check_shift_history($location_detail_id, $location_shift_id);
	// 					if($check_shift_history){
	// 						$error 	= true;
	// 						$msg    = $location_name.' '.$location_detail_name.' Shift '.$location_shift_name.' sudah checkin..';
	// 					} else {
	// 						$error  = false;
	// 						$msg    = "Sukses...";
	// 					}
	// 				} else {
	// 					$error 	= true;
	// 					$msg    = 'User tidak terdaftar di lokasi '.$location_name.'..';
	// 				}
	// 			} else {
	// 				$error 	= true;
	// 				$msg    = 'NFC TAG '.$nfc_tag_id.' tidak terdaftar..';
	// 			}
	// 		}
	// 	}

	// 	$newLocation = array(
	// 		'location_id'				=> $location_id,
	// 		'location_name'				=> $location_name,
	// 		'location_detail_id'		=> $location_detail_id,
	// 		'location_detail_name'		=> $location_detail_name,
	// 		'location_shift_id' 		=> $location_shift_id,
	// 		'location_shift_name' 		=> $location_shift_name,
	// 		'nfc_tag_id'				=> $nfc_tag_id
	// 	);

	// 	$rows['error']    = $error;
	// 	$rows['msg']      = $msg;
	// 	$rows['location'] = $newLocation;
	// 	die(json_encode($rows));
	// 	exit();
	// }

	// function add_history(){
	// 	$error 	= true;
	//     $msg 	= '';
	// 	$rows 	= array();
	//     // $postdata = file_get_contents("php://input");
	//     if (isset($_POST)) {
 //            $request    = json_decode($_POST["params"]);
 //            $nfc_tag_id = mysql_real_escape_string($request->nfc_tag_id);
 //            $user_id    = mysql_real_escape_string($request->user_id);
 //            $message    = mysql_real_escape_string($request->message);
 //            $thisAction = mysql_real_escape_string($request->thisAction);

	// 		if($thisAction == 'save'){
	// 			$location_detail_id = 0;
	// 			$m = $this->db->get_where("mt_location_detail",array(
	// 				"nfc_tag_id"	=> $nfc_tag_id
	// 			),1,0)->row();
	// 			if(count($m) > 0){
	// 				$location_detail_id   = $m->location_detail_id;
	// 				$location_detail_name = $m->location_detail_name;
	// 				$location_id   		  = $m->location_id;
	// 				$location_name        = get_name_location($location_id);
	// 				$user_location 		  = get_user_location($user_id);

	// 				if(in_array($location_id, $user_location)){
	// 					$location_shift_id   = get_shift_id($location_id);
	// 					$location_shift_name = get_location_shift_name($location_shift_id);
	// 					$check_shift_history = check_shift_history($location_detail_id, $location_shift_id);
	// 					if(!$check_shift_history){
	// 						$data = array(
	// 							'location_history_type'		=> 1,
	// 							'location_detail_id'		=> $location_detail_id,
	// 							'location_shift_id' 		=> $location_shift_id,
	// 							'user_id'					=> $user_id,
	// 							'location_history_message'	=> $message,
	// 							'location_history_istrash'	=> 0,
	// 							'location_history_date'		=> timestamp()
	// 						);

	// 						$this->DATA->table="mt_location_history";
	// 						$a = $this->_save_master(
	// 							$data,
	// 							array(
	// 								'location_history_id' => ''
	// 							),
	// 							''
	// 						);

	// 						$this->db->update("mt_location",array("location_last_update_history"=>timestamp()),array("location_id"=>$location_id));

	// 						$id = $a['id'];
	// 						if($id != ''){
	// 							$error 	= false;
	// 							$msg    = $location_name.' '.$location_detail_name.' Shift '.$location_shift_name.' berhasil disimpan..';
	// 						} else {
	// 							$error 	= true;
	// 							$msg    = $location_name.' '.$location_detail_name.' Shift '.$location_shift_name.' gagal disimpan..';
	// 						}
	// 					} else {
	// 						$error 	= true;
	// 						$msg    = $location_name.' '.$location_detail_name.' Shift '.$location_shift_name.' sudah checkin..';
	// 					}
	// 				} else {
	// 					$error 	= true;
	// 					$msg    = 'User tidak terdaftar di lokasi '.$location_name.'..';
	// 				}
	// 			} else {
	// 				$error 	= true;
	// 				$msg    = 'NFC TAG '.$nfc_tag_id.' tidak terdaftar..';
	// 			}
	// 		}
	// 	}

	// 	$rows['error'] = $error;
	// 	$rows['msg']   = $msg;
	// 	die(json_encode($rows));
	// 	exit();
	// }

	// function sync_tag(){
	// 	$error 	= true;
	//     $msg 	= '';
	// 	$rows 	= array();
	//     // $postdata = file_get_contents("php://input");
	//     if (isset($_POST)) {
 //            $request    = json_decode($_POST["params"]);
 //            $nfc_tag_id = mysql_real_escape_string($request->nfc_tag_id);
 //            $old_nfc_tag_id = mysql_real_escape_string($request->old_nfc_tag_id);
 //            $thisAction = mysql_real_escape_string($request->thisAction);

	// 		if($thisAction == 'save'){
	// 			$location_detail_id = 0;
	// 			$m = $this->db->get_where("mt_location_detail",array(
	// 				"nfc_tag_id"	=> $nfc_tag_id
	// 			),1,0)->row();
	// 			if(count($m) > 0){
	// 				$location_detail_id   = $m->location_detail_id;
	// 				$location_detail_name = $m->location_detail_name;
	// 				$location_name        = get_name_location($m->location_id);

	// 				$this->db->update("mt_location_detail",array("sync_nfc_tag"=>1),array("location_detail_id"=>$location_detail_id));

	// 				if($old_nfc_tag_id != ""){
	// 					$this->db->update("mt_location_detail",array("sync_nfc_tag"=>0),array("nfc_tag_id"=>$old_nfc_tag_id));
	// 				}

	// 				$error 	= false;
	// 				$msg    = $location_name.' '.$location_detail_name.' berhasil disimpan..';
	// 			} else {
	// 				$error 	= true;
	// 				$msg    = 'NFC TAG '.$nfc_tag_id.' tidak terdaftar..';
	// 			}
	// 		}
	// 	}

	// 	$rows['error'] = $error;
	// 	$rows['msg']   = $msg;
	// 	die(json_encode($rows));
	// 	exit();
	// }

	// function get_wilayah_and_location(){
	// 	$error 	= true;
	//     $msg 	= '';
	// 	$rows 	= array();
	// 	$result = array();
	//     if (isset($_POST)) {
 //            $request    = json_decode($_POST["params"]);
 //            $user_id    = mysql_real_escape_string($request->user_id);
 //            $thisAction = mysql_real_escape_string($request->thisAction);

	// 		if($thisAction == 'getdata'){
	// 			$user_wilayah_location = get_user_wilayah_location($user_id);
	// 			$arrWil = array();
	// 			$arrLok = array();
	// 			foreach ($user_wilayah_location as $k => $v) {
	// 				foreach ($v->wilayah_id as $n) { $arrWil[] = $n; }
	// 				foreach ($v->location_id as $n) { $arrLok[] = $n; }
	// 			}

	// 			$this->db->order_by('wilayah_date','ASC');
	// 			$this->db->where("wilayah_istrash", 0);
	// 			$this->db->where_in("wilayah_id", $arrWil);
	// 			$wilayah = $this->db->get("mt_wilayah")->result();
	// 			foreach ($wilayah as $key => $val) {
	// 				$result[$key]['wilayah_id']   = $val->wilayah_id;
	// 				$result[$key]['wilayah_name'] = $val->wilayah_name;

	// 				$this->db->order_by('location_name','ASC');
	// 				$this->db->where("location_istrash", 0);
	// 				$this->db->where("wilayah_id", $val->wilayah_id);
	// 				$this->db->where_in("location_id", $arrLok);
	// 				$location = $this->db->get("mt_location")->result();
	// 				foreach ($location as $key2 => $val2) {
	// 					$result[$key]['location'][$key2]['location_id']   = $val2->location_id;
	// 					$result[$key]['location'][$key2]['location_name'] = $val2->location_name;

	// 					$detail = get_location_detail($val2->location_id);
	// 					foreach ($detail as $key3 => $val3) {
	// 						$result[$key]['location'][$key2]['location_detail'][$key3]['location_detail_id'] = $val3->location_detail_id;
	// 						$result[$key]['location'][$key2]['location_detail'][$key3]['location_detail_name'] = $val3->location_detail_name;
	// 						$result[$key]['location'][$key2]['location_detail'][$key3]['nfc_tag_id'] = $val3->nfc_tag_id;
	// 						$result[$key]['location'][$key2]['location_detail'][$key3]['sync_nfc_tag'] = $val3->sync_nfc_tag;
	// 				    }
	// 				}
	// 			}

	// 			$error = false;
	// 		}
	// 	}

	// 	$rows['result'] = $result;
	// 	$rows['error']  = $error;
	// 	$rows['msg']    = $msg;
	// 	die(json_encode($rows));
	// 	exit();
	// }

}
