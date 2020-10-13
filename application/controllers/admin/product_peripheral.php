<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class product_peripheral extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view"),"ITEM");
		$this->_set_title( 'Produk Dijual' );
		$this->DATA->table = "mt_product";
		$this->folder_view = "product/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_product","M");
        $this->load->model("mdl_product_form","MF");
        $this->load->model("mdl_product_process","MP");
		$this->breadcrumb[] = array(
			"title"		=> "Produk Dijual",
			"url"		=> $this->own_link
		);

		$this->upload_path="./assets/collections/product/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 100, 'quality'	=> '90%'),
			array('name'	=> 'small','width'	=> 350, 'quality'	=> '90%')
		);
		$this->image_size_str = "Size: 800px x 800px";

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
			'name'		=> 'product',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => 'mt_product.product_date_push',
			'order_dir' => 'desc',
			'filter' 	=> '50',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

    //INJECT

    function test_product(){
        $i = 0;

        $product_id  = "";
        $store_id    = 1;
        $reseller_id = "";
        $product_category_id = "";
        $product_status_id = "1";
        $product_show_id = "1";
        $date_start = "";
        $date_end = "";
        $order_by = "product_date_push";
        $order_dir = "desc";
        $offset = "0";
        $limit = "50";
        $keyword = "";
        $colum = "";

        $param = array(
            ''                                              => 'Semua Pencarian...',
            'mt_product.product_name'                       => 'Judul',
            'mt_product.product_name_simple'                => 'Nama Singkat',
            'mt_product.product_code'                       => 'Kode',
            'mt_product_category.product_category_title'    => 'Kategori'
        );

        $par_filter = array(
            "product_id"          => ($product_id!=""?$product_id:''),
            "store_id"            => ($store_id!=""?$store_id:'1'),
            "reseller_id"         => $reseller_id,
            "product_category_id" => ($product_category_id!=""?$product_category_id:NULL),
            "product_status_id"   => ($product_status_id!=""?$product_status_id:NULL),
            "product_show_id"     => ($product_show_id!=""?$product_show_id:NULL),
            "type_result"         => "list_app",
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

        debugCode($data_product);
    }

    function test_product_by_group(){
        $i = 0;

        $product_group_id = "";
        $store_id    = 1;
        $date_start = "";
        $date_end = "";
        $order_by = "product_group_date";
        $order_dir = "desc";
        $offset = "0";
        $limit = "";
        $keyword = "";
        $colum = "";

        $param = array(
            ''                    => 'Semua Pencarian...',
            'product_group_name'  => 'Nama'
        );

        $par_filter = array(
            "product_group_id"    => ($product_group_id!=""?$product_group_id:''),
            "store_id"            => ($store_id!=""?$store_id:'1'),
            "type_result"         => "list_app",
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
        $data_product = $this->M->data_product_by_group($par_filter);

        debugCode($data_product);
    }

    function list_barcode_product(){
        $error  = true;
        $msg    = '';
        $total  = 0;
        $rows   = array();
        $result = array();
        $store_id = "1";
        $reseller_id = "";
        // $postdata = file_get_contents("php://input");

        $result = get_list_barcode_product($store_id, $reseller_id);
        $total  = count($result);
        $error  = false;
        $msg    = "Produk ditemukan..";

        $rows['numrows']  = $total;
        $rows['result']   = $result;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    // function injek_set_not_sale(){
    //  $this->db->order_by('product_id','asc');
    //  $products = $this->db->get_where("mt_product",array(
    //      "product_id !=" => 0
    //  ))->result();
    //  foreach ($products as $key => $val) {
    //      $dataDetail = $this->db->get_where("mt_product_detail",array(
    //          'product_id'    => $val->product_id
    //      ),1,0)->row();
    //      $data = array(
    //          'product_status_id'         => 3,
    //          'product_stock'             => 0,
    //          'product_stock_detail'      => NULL
    //      );
    //      if($dataDetail->product_stock_detail != ''){
    //          $arr_stock = array();
    //          $product_stock_detail = json_decode($dataDetail->product_stock_detail);
    //          foreach ($product_stock_detail as $key3 => $value3) {
    //              $arr_stock[] = array('id'       => $value3->id,
    //                                   'name'     => $value3->name,
    //                                   'color'    => $value3->color,
    //                                   'qty'      => 0,
    //                                   'status'   => 2
    //                                );
    //          }
    //          $data['product_stock_detail'] = json_encode($arr_stock);
    //      }
    //      $this->db->update("mt_product_detail",$data,array("product_detail_id"=>$dataDetail->product_detail_id));
    //  }
    // }

    // function injek_update_product_stock(){
    //  $notId = array();
    //  $this->db->order_by('product_id','asc');
    //  $products = $this->db->get_where("mt_product",array(
    //      "product_id !=" => 0
    //  ))->result();
    //  foreach ($products as $key => $val) {
    //      if(!in_array($val->product_id, $notId)){
    //          $dataDetail = get_product_detail($val->product_id);
    //          if($dataDetail->product_stock_detail != ''){
    //              $arr_stock = array();
    //              $product_stock_detail = json_decode($dataDetail->product_stock_detail);
    //              foreach ($product_stock_detail as $key3 => $value3) {
    //                  $arr_stock[] = array('id'       => (strlen($value3->id)==1?'0':'').$value3->id,
    //                                       'name'     => $value3->name,
    //                                       'color'    => $value3->color,
    //                                       'qty'      => $value3->qty,
    //                                       'status'   => $value3->status
    //                                    );
    //              }

    //              $data_detail['product_stock_detail'] = json_encode($arr_stock);
    //              $this->db->update("mt_product_detail",$data_detail,array("product_detail_id"=>$dataDetail->product_detail_id));
    //          }
    //      }
    //  }
    // }

    // function rename_name_product(){
    //  $notId = array();
    //  $remove_name = array("Jilbab","Motif");
    //  $this->db->order_by('product_id','asc');
    //  $products = $this->db->get_where("mt_product",array(
    //      "product_id !=" => 0
    //  ))->result();
    //  foreach ($products as $key => $val) {
    //      if(!in_array($val->product_id, $notId)){
    //          $name = $val->product_names;
    //          $exp = explode(" ", $name);
    //          $temp_name = "";
    //          $i = 0;
    //          foreach ($exp as $n) {
    //              if(!in_array($n, $remove_name)){
    //                  $temp_name .= ($i==0?"":" ").$n;
    //                  $i += 1;
    //              }
    //          }

    //          $data['product_name'] = $temp_name;
    //          $this->db->update("mt_product",$data,array("product_id"=>$val->product_id));
    //      }
    //  }
    // }

    // function cek_double_product_code(){
    //  $temp = array();
    //  $this->db->order_by('product_name_simple','asc');
    //  $products = $this->db->get_where("mt_product",array(
    //      "product_id !=" => 0
    //  ))->result();
    //  foreach ($products as $key => $val) {
    //      if(!in_array($val->product_code, $temp)){
    //          $temp[] = $val->product_code;
    //      } else {
    //          echo $val->product_code.' ditemukan duplikat.<br/>';
    //      }
    //  }
    // }

    // function injek_update_product_code(){
    //  $data  = array();
    //  $notId = array(32,126,96,140);
    //  $this->db->order_by('product_name_simple','asc');
    //  $products = $this->db->get_where("mt_product",array(
    //      "product_category_id " => 2
    //  ))->result();
    //  foreach ($products as $key => $val) {
    //      if(!in_array($val->product_id, $notId)){
    //          $cat = "SE";
    //          $name = substr($val->product_name_simple, 0, 3);
    //          $thisVal = strtoupper($cat.$name);
    //          $create_product_code = create_product_code($thisVal);
    //          // $data['code']         = $create_product_code['code'];
    //          // $data['fullcode']     = $create_product_code['fullcode'];

    //          $data['product_code'] = $create_product_code['fullcode'];
    //          // $data['product_code'] = NULL;
    //          $this->db->update("mt_product",$data,array("product_id"=>$val->product_id));
    //      }
    //  }
    // }

    // function injek_update_url_product(){
    //  $data  = array();
    //  $notId = array();
    //  $this->db->order_by('product_id','asc');
    //  $products = $this->db->get_where("mt_product",array(
    //      "product_id !=" => 0
    //  ))->result();
    //  foreach ($products as $key => $val) {
    //      if(!in_array($val->product_id, $notId)){

    //          $title = $val->product_name;
    //          if($title==''){ $title = 'product'; }
    //          $data['url'] = generateUniqueURL($title,"mt_product");

    //          $this->db->update("mt_product",$data,array("product_id"=>$val->product_id));
    //      }
    //  }
    // }

    // function injek_update_simple_name(){
    //  $data  = array();
    //  $notId = array();
    //  $remove_name = array('Pashmina', 'Segiempat', 'Satin', 'Motif');
    //  $this->db->order_by('product_id','asc');
    //  $products = $this->db->get_where("mt_product",array(
    //      "product_id !=" => 0
    //  ))->result();
    //  foreach ($products as $key => $val) {
    //      if(!in_array($val->product_id, $notId)){

    //          $exp = explode(' ', $val->product_name);
    //          $temp_name = "";
    //          $i = 0;
    //          foreach ($exp as $n) {
    //              if(!in_array($n, $remove_name)){
    //                  $temp_name .= ($i==0?"":" ").$n;
    //                  $i += 1;
    //              }
    //          }

    //          $data['product_name_simple'] = $temp_name;
    //          $this->db->update("mt_product",$data,array("product_id"=>$val->product_id));
    //      }
    //  }
    // }

}
