<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class product_inject extends FrontController {
	function __construct()
	{
		parent::__construct();

		$this->load->model("mdl_product","M");
        $this->own_link = base_url()."admin/product_inject";
		$this->breadcrumb[] = array(
			"title"		=> "Produk Dijual",
			"url"		=> $this->own_link
		);

		$this->upload_path="./assets/collections/product/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 100, 'quality'	=> '90%'),
			array('name'	=> 'small','width'	=> 350, 'quality'	=> '90%'),
			array('name'	=> 'large','width'	=> 800, 'quality'	=> '90%')
		);
		$this->image_size_str = "Size: 800px x 800px";

		$this->cat_search = array(
			''												=> 'Semua Pencarian...',
            'mt_product.product_id'                         => 'ID',
			'mt_product.product_name'						=> 'Judul',
			'mt_product.product_name_simple'				=> 'Nama Singkat',
			'mt_product.product_code'						=> 'Kode',
			'mt_product_category.product_category_title'	=> 'Kategori'
		);

        $this->user_id          = isset($this->jCfg['user']['id'])&&$this->jCfg['user']['id']!=''?$this->jCfg['user']['id']:'1';
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

	function index(){
		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'product'){
			$this->_reset();
		}

        check_product_by_group();

		$this->breadcrumb[] = array(
			"title"		=> "List"
		);

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
			if($this->input->post('date_start') && trim($this->input->post('date_start'))!="")
				$this->jCfg['search']['date_start'] = $this->input->post('date_start');

			if($this->input->post('date_end') && trim($this->input->post('date_end'))!="")
				$this->jCfg['search']['date_end'] = $this->input->post('date_end');

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

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'reset'){
            $this->_reset();
        }

		$order_by = $this->jCfg['search']['order_by'];
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
			$explode_order_by = explode("-", $_POST['order_by']);
			$this->jCfg['search']['order_by'] = $explode_order_by[0];
			$this->jCfg['search']['order_dir'] = $explode_order_by[1];
			$this->_releaseSession();
		}
		if(isset($_POST['filter'])){
			$this->jCfg['search']['filter'] = $_POST['filter'];
			$this->_releaseSession();
		}
        $this->per_page = $this->jCfg['search']['filter'];

        if(!isset($this->jCfg['search']['product_status_id'])){
        // if(!isset($this->jCfg['search']['product_status_id'])||$this->jCfg['search']['product_status_id']==''){
            $this->jCfg['search']['product_status_id'] = '1';
            $this->_releaseSession();
        }
        if(isset($_POST['product_status_id'])){
            if($_POST['product_status_id'] == ""){
                $this->jCfg['search']['product_status_id'] = '';
            } else {
                $this->jCfg['search']['product_status_id'] = $_POST['product_status_id'];
            }
            $this->_releaseSession();
        }

        if(!isset($this->jCfg['search']['product_group_id'])){
        // if(!isset($this->jCfg['search']['product_group_id'])||$this->jCfg['search']['product_group_id']==''){
            $this->jCfg['search']['product_group_id'] = '';
            $this->_releaseSession();
        }
        if(isset($_POST['product_group_id'])){
            if($_POST['product_group_id'] == ""){
                $this->jCfg['search']['product_group_id'] = '';
            } else {
                $this->jCfg['search']['product_group_id'] = $_POST['product_group_id'];
            }
            $this->_releaseSession();
        }

        $par_filter = array(
            "product_id"          => NULL,
            "store_id"            => $this->store_id,
            "reseller_id"         => NULL,
            "product_category_id" => NULL,
            "product_status_id"   => $this->jCfg['search']['product_status_id'],
            "product_group_id"    => $this->jCfg['search']['product_group_id'],
            "product_show_id"     => "1",
            "type_result"         => "list",
            "date_start"          => $this->jCfg['search']['date_start'],
            "date_end"            => $this->jCfg['search']['date_end'],
            "order_by"            => $this->jCfg['search']['order_by'],
            "order_dir"           => $this->jCfg['search']['order_dir'],
            "offset"              => $this->uri->segment($this->uri_segment),
            "limit"               => $this->per_page,
            "keyword"             => $this->jCfg['search']['keyword'],
            "colum"               => $this->jCfg['search']['colum'],
            "param"               => $this->cat_search
        );

        $this->data_table = $this->M->data_product($par_filter);
		$data = $this->_data(array(
			"base_url"	=> $this->own_link.'/index'
		));

		// $data['url'] = base_url()."admin/product/index";

		// $this->_v($this->folder_view.$this->prefix_view,$data);


        $data['title'] = "Product";

        $data['own_links']      = $this->own_link;
        $data['url']            = $this->own_link."";
        $data['url_form']       = $this->own_link."";
        // $data['content_layout'] = $this->prefix_view."_new_orders.php";
        // $this->_v($this->folder_view.$this->prefix_view."_new_orders.php",$data);

        $this->load->view('admin/template/product/product_inject',$data);
	}


    // UPLOAD
    function upload(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['items'] = array();
        $count = 0;

        $arr_tmp_id    = array();
        $download_path = "./assets/collections/tmp_files/";
        $fileDownload  = "";

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'upload'){

            $type_name = $_POST['type_name'];
            $files = $_FILES['files'];
            if (!empty($files['name'])){
                $upload_path    = './assets/collections/tmp_files/';
                $filename       = $files['name'];
                $rand           = strtolower(changeEnUrl($filename)).'_'.convDatetoString(timestamp());
                $ext            = substr($filename, strpos($filename,'.'), strlen($filename)-1);
                $imgname        = $rand.$ext;
                $imgPath        = $upload_path.$imgname;
                if(isset($files) && $files['error'] == 0){
                    if(move_uploaded_file($files['tmp_name'], $imgPath)){

                        $objPHPExcel = PHPExcel_IOFactory::load($imgPath);
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            $worksheetTitle     = $worksheet->getTitle();
                            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                            // $nrColumns = ord($highestColumn) - 64;

                            $titleRow = array();
                            $row = 1;
                            $idx = 1;
                            for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                if(strstr($cell->getValue(),'=')==true){
                                    $titleRow[$idx] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                                } else {
                                    $titleRow[$idx] = $cell->getValue();
                                }
                                $idx += 1;
                            }

                            if($type_name == "insert_sku_shopee"){
                                if($titleRow[1] == "product_id" && $titleRow[2] == "product_code"){
                                    for ($row = 2; $row <= $highestRow; $row++) {
                                        $val = array();
                                        $idx = 1;
                                        for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                            if(strstr($cell->getValue(),'=')==true){
                                                $val[$idx] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                                            } else {
                                                $val[$idx] = $cell->getValue();
                                            }
                                            $idx += 1;
                                        }

                                        $product_id   = $val[1];
                                        $product_code = $val[2];
                                        if($product_id != "" && $product_code != ""){
                                            $m = $this->db->get_where("mt_product",array(
                                                "store_id"          => $this->store_id,
                                                "product_code"      => $product_code
                                            ),1,0)->row();
                                            if(count($m) > 0){
                                                $product_mp_id = NULL;
                                                $m2 = $this->db->get_where("mt_product_mp",array(
                                                    "product_id"      => $m->product_id
                                                ),1,0)->row();
                                                if(count($m2) > 0){
                                                    $product_mp_id = $m2->product_mp_id;
                                                }

                                                $data2 = array();
                                                $data2['product_id']        = $m->product_id;
                                                $data2['product_mp_shopee'] = $product_id;
                                                $this->DATA->table="mt_product_mp";
                                                $a2 = $this->_save_master(
                                                    $data2,
                                                    array(
                                                        'product_mp_id' => $product_mp_id
                                                    ),
                                                    $product_mp_id
                                                );

                                                $arr_tmp_id[] = $product_code;
                                                $count += 1;
                                            } else {
                                                $data['err']  = true;
                                                $data['msg'] .= '- Kode Produk: '.$product_id.' tidak ditemukan.<br>';
                                            }
                                        }
                                    }

                                    $check_double_sku_marketplace = check_double_sku_marketplace("shopee");
                                    if(count($check_double_sku_marketplace) > 0){
                                        $data['err']  = true;
                                        foreach ($check_double_sku_marketplace as $key3 => $val3) {
                                            $data['msg'] .= '- SKU DOUBLE: '.$val3->sku.' , name: '.$val3->name.'.<br>';
                                        }
                                    }
                                } else {
                                        $data['msg'] .= '- COL 1: product_id, COL 2: product_code.<br>';
                                }
                            } else if($type_name == "update_stock_shopee"){
                                if($titleRow[1] == "ps_product_id" && $titleRow[2] == "ps_sku_ref_no_parent"){

                                    $styleSold = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => 'FF0000')
                                        ));

                                    /** Error reporting */
                                    error_reporting(E_ALL);
                                    ini_set('display_errors', TRUE);
                                    ini_set('display_startup_errors', TRUE);
                                    date_default_timezone_set('Asia/Jakarta');

                                    $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
                                    $excel2 = $excel2->load($imgPath);
                                    $excel2->setActiveSheetIndex(0);

                                    for ($row = 4; $row <= $highestRow; $row++) {
                                        $val = array();
                                        $idx = 1;
                                        for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                            if(strstr($cell->getValue(),'=')==true){
                                                $val[$idx] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                                            } else {
                                                $val[$idx] = $cell->getValue();
                                            }
                                            $idx += 1;
                                        }

                                        if($val[1] != ""){
                                            $m = $this->db->get_where("mt_product",array(
                                                "product_code" => $val[2]
                                            ),1,0)->row();
                                            if(count($m) > 0){
                                                $m2 = $this->db->get_where("mt_product_detail",array(
                                                    "product_id"      => $m->product_id
                                                ),1,0)->row();
                                                if(count($m2) > 0){
                                                    $product_status_id    = $m2->product_status_id;
                                                    if($product_status_id != 1){
                                                        $excel2->getActiveSheet()->getCellByColumnAndRow(1, $row)->getStyle()->applyFromArray($styleSold);
                                                    }

                                                    $product_stock_detail = $m2->product_stock_detail;
                                                    if($product_stock_detail != ""){
                                                        $iCol = 8; // Start Vertical
                                                        $exp = json_decode($product_stock_detail);
                                                        foreach ($exp as $key3 => $val3) {
                                                            for ($i=1; $i <= 5; $i++) {
                                                                if($i == 5){
                                                                    $value = ($val3->qty>0?$val3->qty:0);
                                                                    $excel2->getActiveSheet()->setCellValueByColumnAndRow($iCol, $row, $value);
                                                                }
                                                                $iCol++;
                                                            }
                                                        }
                                                    } else {
                                                        $iCol = 6;
                                                        $value = ($m2->product_stock>0?$m2->product_stock:0);
                                                        $excel2->getActiveSheet()->setCellValueByColumnAndRow($iCol, $row, $value);
                                                    }
                                                }

                                                $arr_tmp_id[] = $m->product_id;
                                                $count += 1;
                                            } else {
                                                $data['err']  = true;
                                                $data['msg'] .= '- ID: '.$val[1].' tidak ditemukan.<br>';
                                            }
                                        }
                                    }

                                    $row = 2;
                                    $excel2->createSheet(1);
                                    $excel2->setActiveSheetIndex(1);
                                    $excel2->getActiveSheet()->setTitle('Belum Ada');
                                    $excel2->getActiveSheet()->setCellValue("A1", "product_id");
                                    $excel2->getActiveSheet()->setCellValue("B1", "product_code");
                                    $excel2->getActiveSheet()->setCellValue("C1", "product_name");
                                    $m3 = $this->db->order_by("product_id","desc")->get_where("mt_product_detail",array(
                                        "product_status_id" => 1
                                    ))->result();
                                    foreach ($m3 as $k3 => $v3) {
                                        if(!in_array($v3->product_id, $arr_tmp_id)){
                                            $prod = get_detail_product($v3->product_id);
                                            if($this->store_id == $prod->store_id){
                                                $excel2->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $prod->product_id);
                                                $excel2->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $prod->product_code);
                                                $excel2->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $prod->product_name);
                                                $row += 1;
                                            }
                                        }
                                    }

                                    $excel2->setActiveSheetIndex(0);
                                    $filename     = changeEnUrl($this->store_name).'_update_stock_shopee_'.convDateFilename(timestamp()).'.xlsx';
                                    $fileDownload = $download_path.$filename;
                                    $objWriter    = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
                                    $objWriter->save($fileDownload);
                                }

                            } else if($type_name == "update_price"){
                                if($titleRow[1] == "product_id" && $titleRow[2] == "product_code"){

                                    for ($row = 2; $row <= $highestRow; $row++) {
                                        $val = array();
                                        $idx = 1;
                                        for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                            if(strstr($cell->getValue(),'=')==true){
                                                $val[$idx] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                                            } else {
                                                $val[$idx] = $cell->getValue();
                                            }
                                            $idx += 1;
                                        }

                                        if($val[1] != ""){
                                            $m = $this->db->get_where("mt_product_detail",array(
                                                "product_id" => $val[1]
                                            ),1,0)->row();
                                            if(count($m) > 0){
                                                $data2 = array();
                                                $data2['product_price_buy']      = convertRpToInt($val[5]);
                                                $data2['product_price_sale']     = convertRpToInt($val[6]);
                                                $data2['product_price_discount'] = convertRpToInt($val[7]);
                                                $data2['product_price_grosir']   = NULL;

                                                if($val[8] != ""){
                                                    $product_grosir = array();
                                                    $product_grosir[] = array('qty' => $val[8],  'price' => $val[9]);
                                                    $product_grosir[] = array('qty' => $val[10], 'price' => $val[11]);
                                                    $product_grosir[] = array('qty' => $val[12], 'price' => $val[13]);

                                                    $arr_grosir   = array();
                                                    $count_before = array();
                                                    foreach ($product_grosir as $key1 => $val1) {
                                                        $count_before[] = $val1['qty'];
                                                    }
                                                    $i = 1;
                                                    foreach ($product_grosir as $key1 => $val1) {
                                                        $min = $val1['qty'];
                                                        $max = $count_before[$i] - 1;
                                                        $min_max = $min.' - '.$max;
                                                        if($count_before[$i] == ''){
                                                            $min_max = '>= '.$min;
                                                        }
                                                        $i += 1;

                                                        $arr_grosir[] = array('name'    => $min_max,
                                                                              'qty'     => $val1['qty'],
                                                                              'price'   => convertRpToInt($val1['price'])
                                                                            );
                                                    }
                                                    $data2['product_price_grosir'] = json_encode($arr_grosir);
                                                }

                                                $count += 1;
                                                $this->db->update("mt_product_detail",$data2,array("product_detail_id"=>$m->product_detail_id));
                                            } else {
                                                $data['err']  = true;
                                                $data['msg'] .= '- Kode Produk: '.$val[1].' tidak ditemukan.<br>';
                                            }
                                        }
                                    }
                                }

                            } else {

                            }
                        }
                    }
                } else {
                    $data['err']  = true;
                    $data['msg'] .= 'Error: ' . $files['error'] . '<br>';
                }
            }

            if($fileDownload == ""){
                $upload_files = glob('./assets/collections/tmp_files/*');
                foreach($upload_files as $file){
                    if(is_file($file))
                    unlink($file);
                }
            }

            if($data['msg'] == "") { $data['err'] = false; }
            $data['msg'] .= 'Upload success.. <br/>Total: '.$count;

            redirect($this->own_link."?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success')."".($fileDownload!=""?"&download=".base_url().$fileDownload:""));
        }
    }

    // DOWNLOAD
    function download_list_stock(){
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Jakarta');

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator($this->store_name)
                                     ->setLastModifiedBy($this->store_name)
                                     ->setTitle("Data Stock Product ".$this->store_name)
                                     ->setSubject("Data Stock Product ".$this->store_name)
                                     ->setDescription("Data Stock Product ".$this->store_name)
                                     ->setKeywords("Data Stock Product")
                                     ->setCategory("Data Stock Product");

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'product_id')
                                      ->setCellValue('B1', 'product_code')
                                      ->setCellValue('C1', 'product_name')
                                      ->setCellValue('D1', 'product_category_title')
                                      ->setCellValue('E1', 'product_status_name')
                                      ->setCellValue('F1', 'product_stock_first')
                                      ->setCellValue('G1', 'product_stock');

        $arrVariasi   = array("variasi_id_","variasi_name_","variasi_qty_","variasi_status_");
        $countVariasi = 20;
        $iRow = 1; // 1-based index
        $iCol = 7; // Start Vertical
        for ($i=1; $i <= $countVariasi; $i++) {
            // $iCol = 0;
            foreach ($arrVariasi as $n) {
                $value = $n.$i;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol, $iRow, $value);
                $iCol++;
            }
            // $iRow++;
        }

        $data['data_export'] = array();
        if(isset($_GET['id']) && $_GET['id']!=''){
            $id   = $_GET['id'];
            $exp  = explode("-", $id);
            $iRow = 2; // 1-based index
            foreach ($exp as $n) {
                $product_id = $n;
                $product = get_detail_product($product_id);
                $detail  = get_product_detail($product_id);

                $objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $product->product_id)
                                              ->setCellValue('B'.$iRow, strtoupper($product->product_code))
                                              ->setCellValue('C'.$iRow, $product->product_name)
                                              ->setCellValue('D'.$iRow, get_product_category_name($product->product_category_id))
                                              ->setCellValue('E'.$iRow, get_product_status($detail->product_status_id)->product_status_name)
                                              ->setCellValue('F'.$iRow, $detail->product_stock_first)
                                              ->setCellValue('G'.$iRow, $detail->product_stock);

                $product_stock_detail = $detail->product_stock_detail;
                if($product_stock_detail != ""){
                    $iCol = 7; // Start Vertical
                    $exp = json_decode($product_stock_detail);
                    foreach ($exp as $key3 => $val3) {
                        foreach ($arrVariasi as $key4 => $val4) {
                            switch ($key4) {
                                case 0: $value = $val3->id; break;
                                case 1: $value = $val3->name; break;
                                case 2: $value = $val3->qty; break;
                                case 3: $value = ($val3->status==1?"Ready":"Terjual"); break;
                                default: $value = ""; break;
                            }

                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol, $iRow, $value);
                            $iCol++;
                        }
                    }
                }

                $iRow += 1;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('List Stock');
        $objPHPExcel->setActiveSheetIndex(0);

        $filename = changeEnUrl($this->store_name).'_list_stock';

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'_'.convDateFilename(timestamp()).'.xls"');
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

    }

    function download_list_price(){
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Jakarta');

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator($this->store_name)
                                     ->setLastModifiedBy($this->store_name)
                                     ->setTitle("Data Price Product ".$this->store_name)
                                     ->setSubject("Data Price Product ".$this->store_name)
                                     ->setDescription("Data Price Product ".$this->store_name)
                                     ->setKeywords("Data Price Product")
                                     ->setCategory("Data Price Product");

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'product_id')
                                      ->setCellValue('B1', 'product_code')
                                      ->setCellValue('C1', 'product_name')
                                      ->setCellValue('D1', 'product_category_title')
                                      ->setCellValue('E1', 'product_price_buy')
                                      ->setCellValue('F1', 'product_price_sale')
                                      ->setCellValue('G1', 'product_price_discount');

        $arrGrosir   = array("grosir_qty_","grosir_price_");
        $countGrosir = 3;
        $iRow = 1; // 1-based index
        $iCol = 7; // Start Vertical
        for ($i=1; $i <= $countGrosir; $i++) {
            // $iCol = 0;
            foreach ($arrGrosir as $n) {
                $value = $n.$i;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol, $iRow, $value);
                $iCol++;
            }
            // $iRow++;
        }

        $data['data_export'] = array();
        if(isset($_GET['id']) && $_GET['id']!=''){
            $id   = $_GET['id'];
            $exp  = explode("-", $id);
            $iRow = 2; // 1-based index
            foreach ($exp as $n) {
                $product_id = $n;
                $product = get_detail_product($product_id);
                $detail  = get_product_detail($product_id);

                $objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $product->product_id)
                                              ->setCellValue('B'.$iRow, strtoupper($product->product_code))
                                              ->setCellValue('C'.$iRow, $product->product_name)
                                              ->setCellValue('D'.$iRow, get_product_category_name($product->product_category_id))
                                              ->setCellValue('E'.$iRow, $detail->product_price_buy)
                                              ->setCellValue('F'.$iRow, $detail->product_price_sale)
                                              ->setCellValue('G'.$iRow, $detail->product_price_discount);

                $product_price_grosir = $detail->product_price_grosir;
                if($product_price_grosir != ""){
                    $iCol = 7; // Start Vertical
                    $exp = json_decode($product_price_grosir);
                    foreach ($exp as $key3 => $val3) {
                        foreach ($arrGrosir as $key4 => $val4) {
                            switch ($key4) {
                                case 0: $value = $val3->qty; break;
                                case 1: $value = $val3->price; break;
                                default: $value = ""; break;
                            }

                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($iCol, $iRow, $value);
                            $iCol++;
                        }
                    }
                }

                $iRow += 1;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('List Harga');
        $objPHPExcel->setActiveSheetIndex(0);

        $filename = changeEnUrl($this->store_name).'_list_price';

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'_'.convDateFilename(timestamp()).'.xls"');
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

    }

}
