<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AppsController.php");
class Site extends AppsController {
	var $cur_menu = '';

	function __construct()
	{
		parent::__construct();
		$this->folder_view = "/";

		// $this->DATA->table = "mt_orders";
		// $this->load->model("mdl_location","M");

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

	}

	function sample_json(){
		$error 	= true;
	    $msg 	= '';
		$rows 	= array();
		$result = array();

	    $count  = 22;
	    $offset = 0;
	    $limit  = $count;

	    if(isset($_POST['offset'])&&$_POST['offset']!=''){ $offset = $_POST['offset']; }
	    if(isset($_POST['limit'])&&$_POST['limit']!=''){ $limit = $_POST['limit']; }
	    if(isset($_GET['offset'])&&$_GET['offset']!=''){ $offset = $_GET['offset']; }
	    if(isset($_GET['limit'])&&$_GET['limit']!=''){ $limit = $_GET['limit']; }
	    if (isset($_POST["params"]) && $_POST["params"]!='') {
            $request 	= json_decode($_POST["params"]);
            $offset 	= mysql_real_escape_string($request->offset);
            $limit    	= mysql_real_escape_string($request->limit);
            $thisAction = mysql_real_escape_string($request->thisAction);
        }

	    $idx = 0;
	    $a = 0;
	    for ($i=0; $i < $count; $i++){
	    	$idx += 1;
	    	if($idx == 13){ $idx = 1; }
	    	switch ($idx) {
	    		case 1: $title = "Jon Snow"; $desc = "King Jon Snow is a prominent character"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 2: $title = "Sansa Stark"; $desc = "Princess Sansa Stark is a prominent character"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 3: $title = "Tyrion Lannister"; $desc = "Tyrion Lannister is the youngest child"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 4: $title = "Arya Stark"; $desc = "Arya Stark is the youngest daughter"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 5: $title = "Jorah Mormont"; $desc = "Ser Jorah Mormont is a major"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 6: $title = "Daenerys Targaryen"; $desc = "Daenerys is the only daughter"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 7: $title = "Robb Stark"; $desc = "Robb was the eldest child of Lady Catelyn"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 8: $title = "Ygritte"; $desc = "Ygritte was a quick-witted"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 9: $title = "Ros"; $desc = "Ros is a prostitute in the brothel"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 10: $title = "Osha"; $desc = "Osha is a wildling spearwife"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 11: $title = "Jaime Lannister"; $desc = "Ser Jaime Lannister is a major character"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		case 12: $title = "Myrcella Baratheon"; $desc = "Princess Myrcella was commonly thought"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    		default: $title = "Title"; $desc = "Loremipsum is dolor amet"; $image = "https://upload.wikimedia.org/wikipedia/id/d/d8/Game_of_Thrones_title_card.jpg"; break;
	    	}

	    	if($i >= $offset && $a < $limit){
		    	$result[$a]['id'] = ($i+1);
		    	$result[$a]['title'] = $title.' '.($i+1);
		    	$result[$a]['desc']  = $desc;
		    	$result[$a]['image'] = $image;
		    	for ($aa=0; $aa < 2; $aa++) {
			    	$result[$a]['detail'][$aa]['id'] = $aa;
			    	$result[$a]['detail'][$aa]['name'] = "Detail 1";
		    	}
		    	$a += 1;
		    }
	    }

		$error = false;

		$rows['result']  = $result;
		$rows['numrows'] = $count;
		$rows['error']   = $error;
		$rows['msg']     = $msg;
		die(json_encode($rows));
		exit();
	}

	function check_update_apps(){
		$error 	= true;
	    $msg 	= '';
		$rows 	= array();
		$version = "";
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $thisVersion     	= mysql_real_escape_string($request->thisVersion);
            $thisValue    		= mysql_real_escape_string($request->thisValue);
            $thisAction  		= mysql_real_escape_string($request->thisAction);

            if($thisAction == 'check'){
	            $cek_ = $this->db->get_where("mt_device_config",array(
					"device_config_id" => 1
				),1,0)->row();
				if(count($cek_) > 0){
					if($thisValue == 'android'){ $version = $cek_->device_android_version; }
					if($thisVersion != $version){
						$error  = true;
						$msg    = "Aplikasi perlu di update, hubungi admin untuk melakukan update aplikasi.";
					} else {
						$error  = false;
						$msg    = "Aplikasi sudah terupdate.";
					}
				} else {
					$error = true;
					$msg   = "Config tidak ditemukan.";
				}
			}
		}

		$rows['error']  = $error;
		$rows['msg']    = $msg;
		die(json_encode($rows));
		exit();
	}

	function get_list_statistic(){
		$error 	= true;
	    $msg 	= '';
	    $total  = 0;
		$rows 	= array();
		$result = array();
	    // $postdata = file_get_contents("php://input");
	    if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id     		 = mysql_real_escape_string($request->user_id);
            $store_id    		 = mysql_real_escape_string($request->store_id);
            $token  			 = mysql_real_escape_string($request->token);
            $thisAction  		 = mysql_real_escape_string($request->thisAction);

            $cek_user = $this->db->get_where("mt_app_user",array(
				"user_id"		=> $user_id,
				"token_apps"	=> $token
			),1,0)->row();
			if(count($cek_user) > 0){
				if($thisAction == 'getdata'){
					$store_id = 1;
					$countProductDetail = getCountProductDetail($store_id);
					$result[0]['name']   = "Stok Ready";
					$result[0]['jumlah'] = $countProductDetail['stock_ready']." pcs";
					$result[1]['name']   = "Harga Beli";
					$result[1]['jumlah'] = convertRp($countProductDetail['price_buy']);
					$result[2]['name']   = "Harga Jual";
					$result[2]['jumlah'] = convertRp($countProductDetail['price_sale']);

					$store_id = 2;
					$countProductDetail = getCountProductDetail($store_id);
					$result[3]['name']   = "PXL - Stok Ready";
					$result[3]['jumlah'] = $countProductDetail['stock_ready']." pcs";
					$result[4]['name']   = "PXL - Harga Beli";
					$result[4]['jumlah'] = convertRp($countProductDetail['price_buy']);
					$result[5]['name']   = "PXL - Harga Jual";
					$result[5]['jumlah'] = convertRp($countProductDetail['price_sale']);

					$total  = 6;
					$error  = false;
					$msg    = "Statistik ditemukan..";
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

	function ajax_get_orders_report(){
		$data   = array();
		$result = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		$data['result'] = '';
		// $date_start = "2018-07-13";
		// $date_end   = "2018-07-23";
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'getdata' ){
			$date_start = dbClean(trim($_POST['date_start']));
			$date_end   = dbClean(trim($_POST['date_end']));
			if(trim($date_start)!='' && trim($date_end)!=''){
				$datestart = new DateTime($date_start);
				$dateend   = new DateTime(getAddDay($date_end, 1));
				$interval  = DateInterval::createFromDateString('1 day');
				$periode   = new DatePeriod($datestart, $interval, $dateend);
				$i = 0;
				foreach ($periode as $dt) {
					$date   = $dt->format("Y-m-d");
					$par_filter = array(
	                    "date_start"    => $date,
	                    "date_end"      => $date
	                );

        			$this->load->model("mdl_report","MR");
					$report = $this->MR->data_report_orders($par_filter);

					$result[$i]->date      = convDatepickerDec($date);
					$result[$i]->statistik = $report['result'];
					$i += 1;
				}

				$data['err'] 	 = false;
				$data['msg'] 	 = 'Pengambilan data tanggal '.$date_start.' sampai '.$date_end.' berhasil..';
				$data['result']  = $result;
			}
		}

		die(json_encode($data));
		exit();
	}

	function ajax_get_history_orders_report(){
		$data   = array();
		$result = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		$data['result'] = '';
		// $date_start = "2018-10-06";
		// $date_end   = "2018-10-13";
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'getdata' ){
			$date_start = dbClean(trim($_POST['date_start']));
			$date_end   = dbClean(trim($_POST['date_end']));
			if(trim($date_start)!='' && trim($date_end)!=''){
				$datestart = new DateTime($date_start);
				$dateend   = new DateTime(getAddDay($date_end, 1));
				$interval  = DateInterval::createFromDateString('1 day');
				$periode   = new DatePeriod($datestart, $interval, $dateend);
				$i = 0;
				foreach ($periode as $dt) {
					$date   = $dt->format("Y-m-d");
					$par_filter = array(
	                    "date_start"    => $date,
	                    "date_end"      => $date
	                );

        			$this->load->model("mdl_report","MR");
					$report = $this->MR->data_report_orders($par_filter);

					$result[$i]->date      = convDatepickerDec($date);
					$result[$i]->statistik = $report['result'];
					$i += 1;
				}

                $new_result = $this->MR->layout_report_history_orders_mobile($result);
                $data['result']  = $new_result;

				$data['err'] 	 = false;
				$data['msg'] 	 = 'Pengambilan data tanggal '.$date_start.' sampai '.$date_end.' berhasil..';
				// $data['result']  = $result;
			}
		}

		// debugCode($data);
		die(json_encode($data));
		exit();
	}

	function test_webview(){
		$data = array();

		$this->_v($this->folder_view."statistic",$data, false);
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

	// function get_history(){
	// 	$error 	= true;
	//     $msg 	= '';
	// 	$rows 	= array();
	// 	$result = array();
	// 	$newShift = array();
	// 	$newWilayah  = array();
	// 	$newLocation = array();
	// 	$newLocationDetail = array();
	//     // $postdata = file_get_contents("php://input");
	//     if (isset($_POST)) {
 //            $request     = json_decode($_POST["params"]);
 //            $user_id     = mysql_real_escape_string($request->user_id);
 //            $shift_id    = mysql_real_escape_string($request->shift_id);
 //            $wilayah_id  = mysql_real_escape_string($request->wilayah_id);
 //            $location_id = mysql_real_escape_string($request->location_id);
 //            $order_dir   = mysql_real_escape_string($request->order_dir);
 //            $datestart   = mysql_real_escape_string($request->datestart);
 //            $dateend     = mysql_real_escape_string($request->dateend);
 //            $offset 	 = (mysql_real_escape_string($request->offset) != 0?mysql_real_escape_string($request->offset):0);
 //            $limit    	 = (mysql_real_escape_string($request->limit) != 0?mysql_real_escape_string($request->limit):500);
 //            $thisAction  = mysql_real_escape_string($request->thisAction);

	// 		// $user_id = 1;
	// 		// $wilayah_id = "";
	// 		// $location_id = "";
	// 		// $order_dir = "DESC";
	// 		if($thisAction == 'getdata'){
	// 			// GENERATE WILAYAH
	// 			$arrWil = array();
	// 			$arrLok = array();
	// 			$user_wilayah_location = get_user_wilayah_location($user_id);
	// 			foreach ($user_wilayah_location as $k => $v) {
	// 				foreach ($v->wilayah_id as $n) { $arrWil[] = $n; }
	// 				foreach ($v->location_id as $n) { $arrLok[] = $n; }
	// 			}

	// 			$i = 0;
	// 			$this->db->order_by('wilayah_date','ASC');
	// 			$this->db->where("wilayah_istrash", 0);
	// 			$this->db->where_in("wilayah_id", $arrWil);
	// 			$wilayah = $this->db->get("mt_wilayah")->result();
	// 			foreach ($wilayah as $key => $val) {
	// 				if($wilayah_id == "" && $i == 0){ $wilayah_id = $val->wilayah_id; }
	// 				$newWilayah[$key]['wilayah_id']   = $val->wilayah_id;
	// 				$newWilayah[$key]['wilayah_name'] = $val->wilayah_name;

	// 				$ii = 0;
	// 				$this->db->order_by('location_name','ASC');
	// 				$this->db->where("location_istrash", 0);
	// 				$this->db->where("wilayah_id", $val->wilayah_id);
	// 				$this->db->where_in("location_id", $arrLok);
	// 				$location = $this->db->get("mt_location")->result();
	// 				foreach ($location as $key2 => $val2) {
	// 					if($location_id == "" && $ii == 0){ $location_id = $val2->location_id; }
	// 					$newWilayah[$key]['location'][$key2]['location_id']    = $val2->location_id;
	// 					$newWilayah[$key]['location'][$key2]['location_name']  = $val2->location_name;
	// 					$newWilayah[$key]['location'][$key2]['location_shift'] = array();

	// 					$this->db->order_by('location_shift_id','ASC');
	// 					$this->db->where("location_id", $val2->location_id);
	// 					$location_shift = $this->db->get("mt_location_shift")->result();
	// 					foreach ($location_shift as $key3 => $val3) {
	// 						$newWilayah[$key]['location'][$key2]['location_shift'][$key3]['shift_id']   = $val3->location_shift_id;
	// 						$newWilayah[$key]['location'][$key2]['location_shift'][$key3]['shift_name'] = $val3->location_shift_name;
	// 					}

	// 					$ii += 1;
	// 				}
	// 				$i += 1;
	// 			}
	// 			// - GENERATE FILTER WILAYAH, LOKASI, SHIFT

	// 			// GENERATE LOKASI, SHIFT, POINT
	// 			$location  = array();
	// 			$shift     = array();
	// 			$detail    = array();
	// 			$cur_shift = "";
	// 			$gen_shift = "";
	// 			if( $location_id != "" ){
	// 				$location  = get_detail_location($location_id);
	// 				$shift     = get_location_shift($location_id, "DESC");
	// 				$detail    = get_location_detail($location_id);
	// 				$cur_shift = get_shift_id($location_id);
	// 				$gen_shift = get_current_shift($location_id);
	// 			}

	// 			foreach ($location as $key => $val) {
	// 				$newLocation[$key]['location_id']   = $val->location_id;
	// 				$newLocation[$key]['location_name'] = $val->location_name;
	// 				$newLocation[$key]['wilayah_id'] 	= $val->wilayah_id;
	// 				$newLocation[$key]['wilayah_name']  = get_name_wilayah($val->wilayah_id);
	// 			}

	// 			foreach ($detail as $key => $val) {
	// 				$newLocationDetail[$key]['detail_id']   = $val->location_detail_id;
	// 				$newLocationDetail[$key]['detail_name'] = $val->location_detail_name;
	// 			}

	// 			$arrShift = array();
	// 			foreach ($shift as $key => $val) {
	// 				$arrShift[] = $val->location_shift_id;
	// 			}
	// 			if( trim($shift_id) != "" ){ $arrShift  = array($shift_id); }
	// 			// - GENERATE LOKASI, SHIFT, POINT

	// 			$timestamp  = timestamp();
	// 			$cur_date   = convDatepickerDec($timestamp);
	// 			$date_start = date('Y-m-01', strtotime($timestamp));
	// 			$date_end   = date('Y-m-t', strtotime($timestamp));
	// 			if( trim($datestart) != "" ){ $date_start  = $datestart; }
	// 			if( trim($dateend) != "" ){ $date_end  = $dateend; }

	// 			// $date_end   = "2017-08-04";
	// 			$thisDate   = new DateTime( $cur_date );
	// 			$date_end   = new DateTime( $date_end );
	// 			$date_start = new DateTime( $date_start );
	// 			if($date_end > $thisDate){ $date_end = $thisDate; }
	// 			$arrDate = array();
	// 			if( trim($order_dir) == "ASC" ){
	// 				for($i = $date_start; $date_start <= $date_end; $i->modify('+1 days')){
	// 					$arrDate[] = $i->format("Y-m-d");
	// 				}
	// 			} else {
	// 				for($i = $date_end; $date_end >= $date_start; $i->modify('-1 days')){
	// 					$arrDate[] = $i->format("Y-m-d");
	// 				}
	// 			}

	// 		    $i = 0;
	// 			$a = 0;
	// 			$result = array();
	// 			foreach ($arrDate as $r) {
	// 				foreach ($shift as $k => $v) {
	// 					if(in_array($v->location_shift_id, $arrShift)){
	// 						if($i >= $offset && $a < $limit){
	// 							$return = true;
	// 							if($r == $cur_date && $v->location_shift_id > $cur_shift){ $return = false; }
	// 							if($return ==  true){
	// 								$result[$a]->history_date = $r;
	// 								$result[$a]->shift_id     = $v->location_shift_id;
	// 								$result[$a]->history_time = array();
	// 								$b = 0;
	// 								foreach ($detail as $kk => $vv) {
	// 									$params = array(
	// 										"location_detail_id"    => $vv->location_detail_id,
	// 										"location_shift_id"     => $v->location_shift_id,
	// 										"location_history_date" => $result[$a]->history_date
	// 										);
	// 									$location_history_by_shift = get_location_history_by_shift($params);
	// 									if(count($location_history_by_shift) > 0){
	// 										$history_time = convTime($location_history_by_shift->location_history_date);
	// 										$result[$a]->history_time[$b]->detail_id = $vv->location_detail_id;
	// 										$result[$a]->history_time[$b]->time = $history_time;
	// 										$b += 1;
	// 									}
	// 								}

	// 								$a += 1;
	// 							}
	// 						}
	// 					}
	// 				}
	// 				$i += 0;
	// 			}

	// 			$total = $a;
	// 			$error = false;

	// 		}
	// 	}

	// 	$rows['numrows']  = $total;
	// 	$rows['result']   = $result;
	// 	$rows['wilayah']  		 = $newWilayah;
	// 	$rows['shift']    		 = $gen_shift;
	// 	$rows['location'] 		 = $newLocation;
	// 	$rows['location_detail'] = $newLocationDetail;

	// 	$rows['error']  = $error;
	// 	$rows['msg']    = $msg;
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
