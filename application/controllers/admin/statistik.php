<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class statistik extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view"),"ITEM");
		$this->_set_title( 'Data Statistik' );
		$this->DATA->table="mt_saldo";
		$this->folder_view = "orders/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_statistik","M");
        $this->load->model("mdl_report","MR");

		$this->breadcrumb[] = array(
			"title"		=> "Data Statistik",
			"url"		=> $this->own_link
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
			'name'		=> 'saldo',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'saldo_date',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'statistik'){
			$this->_reset();
		}

		redirect($this->own_link.'/grafik');
	}

	function grafik(){
		$data = array();
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		// $par_penjualan = array(
		// 	"tipe_date"	 => 'monthly',
		// 	"date_start" => '2016-12',
		// 	"order_by"   => 'desc'
		// );
		// $data['data_statistik_penjualan'] = $this->M->data_statistik_penjualan($par_penjualan);

        $data['url']            = base_url()."admin/statistik/grafik";
        $data['url_form']       = base_url()."admin/statistik/grafik";
        $data['tab']            = 'tab1';
        $data['content_layout'] = $this->prefix_view."_grafik.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function table(){
		$data = array();
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		$par_penjualan = array(
			"tipe_date"	 => 'monthly',
			"date_start" => '2016-12',
			"order_by"   => 'desc'
		);
		$data['data_statistik_penjualan'] = $this->M->data_statistik_penjualan($par_penjualan);

        $data['url']            = base_url()."admin/statistik/table";
        $data['url_form']       = base_url()."admin/statistik/table";
        $data['tab']            = 'tab2';
        $data['content_layout'] = $this->prefix_view."_table.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
	}

	// function view($id=''){
	// 	$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
	// 	if($hal != 'saldo'){
	// 		$this->_reset();
	// 	}

	// 	$exp = explode("-", $id);
	// 	$id = dbClean(trim($exp[0]));
	// 	$name = dbClean(trim($exp[1]));

	// 	$this->breadcrumb[] = array(
	// 		"title"		=> ubah_huruf_awal($name),
	// 		"url"		=> $this->own_link."/view/".$id."-".$name
	// 	);

	// 	if(trim($id)!=''){
	// 		$order_by = $this->jCfg['search']['order_by'];
	//         if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
	// 			$explode_order_by = explode("-", $_POST['order_by']);
	// 			$this->jCfg['search']['order_by'] = $explode_order_by[0];
	// 			$this->jCfg['search']['order_dir'] = $explode_order_by[1];
	// 			$this->_releaseSession();
	// 		}
	// 		if(isset($_POST['filter'])){
	// 			$this->jCfg['search']['filter'] = $_POST['filter'];
	// 			$this->_releaseSession();
	// 		}

	// 		$this->uri_segment = 5;
	// 		$this->per_page = $this->jCfg['search']['filter'];
	// 		$par_filter = array(
	// 			"id"		=> $id,
	// 			"offset"	=> $this->uri->segment($this->uri_segment),
	// 			"limit"		=> $this->per_page,
	// 			"param"		=> $this->cat_search
	// 		);
	// 		$this->data_table = $this->M->data_saldo($par_filter);
	// 		$data = $this->_data(array(
	// 			"base_url"	=> $this->own_link."/view/".$id."-".$name
	// 		));

	// 		$data['url'] = base_url()."admin/saldo/view/".$id."-".$name;

	// 		$data['id'] = $id;
	// 		$data['name'] = ubah_huruf_awal($name);
	// 		$this->_set_title( 'History saldo '.ubah_huruf_awal($name) );
	// 		$this->_v($this->folder_view.$this->prefix_view."_view",$data);

	// 	}else{
	// 		redirect($this->own_link);
	// 	}
	// }

	// function save(){
	// 	$data = array();
	// 	$data['err'] = true;
	// 	$data['msg'] = '';

	// 	if( isset($_POST['thisAction']) && $_POST['thisAction'] != '' ){
	// 		$thisAction  = dbClean(trim($_POST['thisAction']));
	// 		$thisId      = dbClean(trim($_POST['thisId']));
	// 		$price       = dbClean(trim($_POST['price']));
	// 		$noted       = dbClean(trim($_POST['noted']));

	// 		if(trim($thisId)!=''){
	// 			$type = '';
	// 			if($thisAction == 'plus'){
	// 				$type = 1;
	// 			} else if($thisAction == 'minus'){
	// 				$type = 2;
	// 			}
	// 			if($type != ''){
	// 				insert_saldo(array(
	// 					'orders_source_id'	=> $thisId,
	// 					'orders_id'			=> '',
	// 					'saldo_price'		=> convertRpToInt($price),
	// 					'saldo_noted'		=> $noted,
	// 					'saldo_type'		=> $type
	// 				));
	// 				$data['err'] = false;
	// 				$data['msg'] = 'Sukses';
	// 			} else {
	// 				$data['err'] = true;
	// 				$data['msg'] = 'Tidak ada action!';
	// 			}
	// 		} else {
	// 			$data['err'] = true;
	// 			$data['msg'] = 'ID Source tidak ditemukan atau Jumlah dana tidak numeric!';
	// 		}
	// 	}

	// 	die(json_encode($data));
	// 	exit();
	// }

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

}
