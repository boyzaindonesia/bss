<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class report extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view"),"ITEM");
		$this->_set_title( 'Data Laporan' );
		$this->DATA->table="mt_orders";
		$this->folder_view = "orders/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_report","M");

		$this->breadcrumb[] = array(
			"title"		=> "Data Laporan",
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
			'name'		=> 'report',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'orders_date',
			'order_dir' => 'desc',
			'filter' 	=> '25',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'report'){
			$this->_reset();
		}

		redirect($this->own_link.'/product');
	}

	function product(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'report'){
			$this->_reset();
		}

		$data = array();
		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

		if(!isset($this->jCfg['search']['orders_source_id'])||$this->jCfg['search']['orders_source_id']==''){
            $this->jCfg['search']['orders_source_id'] = NULL;
            $this->_releaseSession();
        }
        if(isset($_POST['orders_source_id'])){
            if($_POST['orders_source_id'] == ""){
                $this->jCfg['search']['orders_source_id'] = NULL;
            } else {
                $this->jCfg['search']['orders_source_id'] = $_POST['orders_source_id'];
            }
            $this->_releaseSession();
        }
        $this->orders_source_id = $this->jCfg['search']['orders_source_id'];

        if(!isset($this->jCfg['search']['date_start'])||$this->jCfg['search']['date_start']==''){
            $this->jCfg['search']['date_start'] = getYearMonthDate(getMinDay(timestamp(), 6));
            $this->jCfg['search']['date_end']   = getYearMonthDate(timestamp());
            $this->_releaseSession();
        }
		if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'Cari'){
			if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
				$this->jCfg['search']['date_start'] = $this->input->post('date_start');

			if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
				$this->jCfg['search']['date_end'] = $this->input->post('date_end');

			$this->_releaseSession();
        }

		$result     = array();
		$arrDate    = array();
		$date_start = $this->jCfg['search']['date_start'];
		$date_end   = $this->jCfg['search']['date_end'];
		if(trim($date_start)!='' && trim($date_end)!=''){
			$datestart = new DateTime($date_start);
			$dateend   = new DateTime(getAddDay($date_end, 1));
			$interval  = DateInterval::createFromDateString('1 day');
			$periode   = new DatePeriod($datestart, $interval, $dateend);
			$i = 0;
			foreach ($periode as $dt) {
				$date   = $dt->format("Y-m-d");
				$par_filter = array(
                    "orders_source_id" => $this->orders_source_id,
                    "date_start"    => $date,
                    "date_end"      => $date
                );

				$report = $this->M->data_report_product($par_filter);

				$arrDate[$i] = convDatepickerDec($date);
				$result[$i]->date    = convDatepickerDec($date);
				$result[$i]->product = $report['product'];
				$i += 1;
			}

			$data['err'] 	 = false;
			$data['msg'] 	 = 'Pengambilan data tanggal '.$date_start.' sampai '.$date_end.' berhasil..';
			$data['result']  = $result;
		}
		$data['arrDate']   = $arrDate;
		$data['dateStart'] = $date_start;
		$data['dateEnd']   = $date_end;

		$i = 0;
		$list_product = $this->db->order_by('product_date_update','desc')->get_where("mt_product",array(
				'store_id'	      => $this->store_id,
				'product_show_id' => 1,
				'product_istrash' => 0
			))->result();
		foreach ($list_product as $k => $v) {
			$product_detail = $this->db->get_where("mt_product_detail",array(
				'product_id'	=> $v->product_id
			),1,0)->row();

			$total_qty = 0;
			$shopee    = 0;
			$tokopedia = 0;
			$lazada    = 0;
			$other     = 0;
			$found_all_date = false;
            foreach ($arrDate as $k2 => $v2) {
            	$found = false;
                foreach ($result[$k2]->product as $k3 => $v3) {
                	if($v->product_id == $k3){
                        $total      = $v3->total;
                        $total_qty += $v3->total;
                        $shopee    += $v3->shopee;
                        $tokopedia += $v3->tokopedia;
                        $lazada    += $v3->lazada;
                        $other     += $v3->other;
                        $found = true;
                        $found_all_date = true;
                    }
                }
                if(!$found){ $total = 0;}
                $data['list_product'][$i]->$v2 = $total;
            }

            if($found_all_date || ($product_detail->product_status_id == 1)){
				$data['list_product'][$i]->product_id   = $v->product_id;
				$data['list_product'][$i]->product_name = $v->product_name_simple;
				$data['list_product'][$i]->product_status_id = $product_detail->product_status_id;
				$data['list_product'][$i]->product_stock = $product_detail->product_stock;
				$data['list_product'][$i]->last_update = getYearMonthDate($v->product_date_update);
				$data['list_product'][$i]->total_qty = $total_qty;
				$data['list_product'][$i]->shopee    = $shopee;
				$data['list_product'][$i]->tokopedia = $tokopedia;
				$data['list_product'][$i]->lazada    = $lazada;
				$data['list_product'][$i]->other     = $other;
				$i += 1;
            }
		}

		// debugCode($data);

        $data['url']            = base_url()."admin/report/product";
        $data['url_form']       = base_url()."admin/report/product";
        $data['tab']            = 'tab0';
        $data['content_layout'] = $this->prefix_view."_product.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
	}

    function orders_marketplace(){
        $data = array();
        $this->_set_title( 'Data Laporan Penjualan' );
        $this->breadcrumb[] = array(
            "title"     => "Penjualan Marketplace"
        );

        $par_filter = array(
            "product_group_id"    => NULL,
            "store_id"            => $this->store_id,
            "reseller_id"         => NULL,
            "product_category_id" => NULL,
            "product_group_show"  => "1",
            "type_result"         => "list_desktop",
            "date_start"          => NULL,
            "date_end"            => NULL,
            "order_by"            => NULL,
            "order_dir"           => NULL,
            "offset"              => 0,
            "limit"               => NULL,
            "keyword"             => NULL,
            "colum"               => NULL,
            "param"               => NULL
        );

        $data = $this->M->data_orders_marketplace($par_filter);

        $data['url']            = base_url()."admin/report/orders_marketplace";
        $data['url_form']       = base_url()."admin/report/orders_marketplace";
        $data['tab']            = 'tab1';
        $data['content_layout'] = $this->prefix_view."_orders_marketplace.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function orders_product(){
        $data = array();
        $this->_set_title( 'Data Laporan Penjualan' );
        $this->breadcrumb[] = array(
            "title"     => "Penjualan"
        );

        $data['url']            = base_url()."admin/report/orders_product";
        $data['url_form']       = base_url()."admin/report/orders_product";
        $data['tab']            = 'tab1';
        $data['content_layout'] = $this->prefix_view."_orders_product.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

	function ajax_get_orders_report(){
		$data   = array();
		$result = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		$data['result'] = '';

		// $date_start = "2018-09-28";
		// $date_end   = "2018-09-28";
  //       $par_filter = array(
  //           "date_start"    => $date_start,
  //           "date_end"      => $date_end
  //       );
  //       $this->data_table = $this->M->data_produk_keluar($par_filter);
		// debugCode($this->data_table);

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'getdata' ){
			$date_start = dbClean(trim($_POST['date_start']));
			$date_end   = dbClean(trim($_POST['date_end']));
			if(trim($date_start)!='' && trim($date_end)!=''){
				$par_filter = array(
		            "date_start"    => convDatepickerDec($date_start),
		            "date_end"      => convDatepickerDec($date_end)
		        );

				$data = $this->M->data_produk_keluar($par_filter);
		        $new_result = $this->M->layout_produk_keluar($data['result']);
				$data['result']  = $new_result;
				$data['total_price_buy']  = convertRp($data['total_price_buy']);
				$data['total_price_sale'] = convertRp($data['total_price_sale']);
				$data['total_laba']       = convertRp($data['total_laba']);
				$data['total_qty']        = $data['total_qty']." pcs";
			}
		}
		die(json_encode($data));
		exit();
	}

	// function ajax_get_orders_report_original(){
	// 	$data   = array();
	// 	$result = array();
	// 	$data['err'] 	= true;
	// 	$data['msg'] 	= '';
	// 	$data['result'] = '';
	// 	$date_start = "2018-09-28";
	// 	$date_end   = "2018-09-28";

 //        $par_filter = array(
 //            "date_start"    => $date_start,
 //            "date_end"      => $date_end
 //        );

 //        $this->data_table = $this->M->data_produk_keluar_original($par_filter);
	// 	debugCode($this->data_table);
	// }

}
