<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class product_process extends AdminController {
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

    // UPLOAD
    function save_upload_product(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['items'] = array();
        $data['items_unpaid'] = array();
        $count = 0;

        $temp_unpaid   = array();
        $arr_tmp_id    = array();
        $download_path = "./assets/collections/tmp_files/";
        $fileDownload  = "";

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'upload'){
            $type_name = $_POST['type_name'];

            // UNPAID STOCK
            $files_temp = $_FILES['files_temp'];
            if (!empty($files_temp['name'])){
                $upload_path    = './assets/collections/tmp_files/';
                $filename       = $files_temp['name'];
                $rand           = strtolower(changeEnUrl($filename)).'_'.convDatetoString(timestamp());
                $ext            = substr($filename, strpos($filename,'.'), strlen($filename)-1);
                $imgname        = $rand.$ext;
                $imgPath        = $upload_path.$imgname;
                if(isset($files_temp) && $files_temp['error'] == 0){
                    if(move_uploaded_file($files_temp['tmp_name'], $imgPath)){

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

                            if($type_name == "update_stock_shopee"){
                                if($titleRow[1] == "No. Pesanan" && $titleRow[2] == "Status Pesanan"){

                                    $idx_unpaid  = 0;
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

                                        if($val[11] != ""){
                                            $product_code   = $val[11];
                                            $product_varian = "";
                                            $product_qty    = $val[17];
                                            $m = $this->db->get_where("mt_product",array(
                                                "product_code" => $product_code
                                            ),1,0)->row();
                                            if(count($m) > 0){
                                                $m2 = $this->db->get_where("mt_product_detail",array(
                                                    "product_id"      => $m->product_id
                                                ),1,0)->row();
                                                if(count($m2) > 0){
                                                    $product_stock_detail = $m2->product_stock_detail;
                                                    if($product_stock_detail != ""){
                                                        $exp = json_decode($product_stock_detail);
                                                        foreach ($exp as $key3 => $val3) {
                                                            if($val3->name == $val[14]){
                                                                $product_varian = $val3->id;
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            $product_code_varian = strtoupper($product_code."-".$product_varian);
                                            if(!in_array($product_code_varian, $temp_unpaid)){
                                                $temp_unpaid[] = $product_code_varian;
                                                $data['items_unpaid'][$idx_unpaid]->product_code = $product_code_varian;
                                                $data['items_unpaid'][$idx_unpaid]->product_qty  = $product_qty;
                                                $idx_unpaid += 1;
                                            } else {
                                                foreach ($data['items_unpaid'] as $k2 => $v2) {
                                                    if($v2->product_code == $product_code_varian){
                                                        $data['items_unpaid'][$k2]->product_qty += $product_qty;
                                                    }
                                                }
                                            }

                                        }
                                    }

                                }
                            }

                        }
                    }
                } else {
                    $data['err']  = true;
                    $data['msg'] .= 'Error: ' . $files['error'] . '<br>';
                }
            }
            // END UNPAID STOCK
            // debugCode($data['items_unpaid']);

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

                            } else if($type_name == "update_stock_website"){
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

                                        $product_id             = $val[1];
                                        $product_code           = $val[2];
                                        $product_name           = $val[3];
                                        $product_category_title = $val[4];
                                        $product_status_id      = $val[5];
                                        $product_price_buy      = $val[6];
                                        $product_price_sale     = $val[7];
                                        $product_price_grosir   = $val[8];
                                        $product_price_discount = $val[9];
                                        $product_stock          = $val[10];
                                        $product_stock_first    = $val[11];
                                        $product_stock_detail   = $val[12];

                                        if($product_id != "" && $product_code != ""){
                                            $m = $this->db->get_where("mt_product",array(
                                                "store_id"        => $this->store_id,
                                                "product_id"      => $product_id
                                            ),1,0)->row();
                                            if(count($m) > 0){
                                                $data2 = array();
                                                $data2['product_status_id']      = $product_status_id;
                                                $data2['product_price_buy']      = $product_price_buy;
                                                $data2['product_price_sale']     = $product_price_sale;
                                                $data2['product_price_grosir']   = ($product_price_grosir!=""?$product_price_grosir:NULL);
                                                $data2['product_price_discount'] = $product_price_discount;
                                                $data2['product_stock']          = $product_stock;
                                                $data2['product_stock_first']    = $product_stock_first;
                                                $data2['product_stock_detail']   = $product_stock_detail;

                                                $this->db->update("mt_product_detail",$data2,array("product_id"=>$product_id));

                                                set_last_date_product_setup();
                                                $count += 1;
                                            } else {
                                                $data['err']  = true;
                                                $data['msg'] .= '- Kode Produk: '.$product_id.' tidak ditemukan.<br>';
                                            }
                                        }
                                    }
                                } else {
                                        $data['msg'] .= '- COL 1: product_id, COL 2: product_code.<br>';
                                }

                            } else if($type_name == "update_stock_shopee"){
                                if($titleRow[1] == "et_title_product_id" && $titleRow[2] == "et_title_product_name"){

                                    $styleSold = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => 'FF0000')
                                        ));
                                    $styleVarianSold = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => '0000FF')
                                        ));
                                    $styleNotFound = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => 'FFF000')
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
                                            $iColSku    = 4;
                                            $iColStock  = 7;
                                            $iColStatus = NULL;
                                            $m = $this->db->get_where("mt_product",array(
                                                "product_code" => $val[5]
                                            ),1,0)->row();
                                            if(count($m) > 0){
                                                $m2 = $this->db->get_where("mt_product_detail",array(
                                                    "product_id"      => $m->product_id
                                                ),1,0)->row();
                                                if(count($m2) > 0){
                                                    $product_stock_detail = $m2->product_stock_detail;
                                                    if($product_stock_detail != ""){
                                                        $exp = json_decode($product_stock_detail);
                                                        foreach ($exp as $key3 => $val3) {
                                                            if($val3->name == $val[4]){
                                                                $value = ($val3->qty>0?$val3->qty:0);
                                                                if($value <= 0){
                                                                    $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleVarianSold);
                                                                }
                                                                if(count($data['items_unpaid']) > 0){
                                                                $product_code_varian = strtoupper($m->product_code."-".$val3->id);
                                                                    if(in_array($product_code_varian, $temp_unpaid)){
                                                                        foreach ($data['items_unpaid'] as $k2 => $v2) {
                                                                            if($v2->product_code == $product_code_varian){
                                                                                $value -= $v2->product_qty;
                                                                                $value = ($value>0?$value:0);
                                                                            }
                                                                        }
                                                                    }
                                                                }

                                                                $excel2->getActiveSheet()->setCellValueByColumnAndRow($iColStock, $row, $value);
                                                            }

                                                        }
                                                    } else {
                                                        $iCol = 7;
                                                        $value = ($m2->product_stock>0?$m2->product_stock:0);
                                                        $excel2->getActiveSheet()->setCellValueByColumnAndRow($iColStock, $row, $value);
                                                    }
                                                    $product_status_id    = $m2->product_status_id;
                                                    if($product_status_id != 1){
                                                        $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleSold);
                                                    }
                                                }

                                                $arr_tmp_id[] = $m->product_id;
                                                $count += 1;
                                            } else {
                                                $data['err']  = true;
                                                $data['msg'] .= '- ID: '.$val[5].' tidak ditemukan.<br>';
                                                $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleNotFound);
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

                            } else if($type_name == "update_stock_tokopedia"){
                                if($titleRow[1] == "product_id" && $titleRow[3] == "name"){

                                    $styleSold = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => 'FF0000')
                                        ));
                                    $styleVarianSold = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => '0000FF')
                                        ));
                                    $styleNotFound = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => 'FFF000')
                                        ));

                                    /** Error reporting */
                                    error_reporting(E_ALL);
                                    ini_set('display_errors', TRUE);
                                    ini_set('display_startup_errors', TRUE);
                                    date_default_timezone_set('Asia/Jakarta');

                                    $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
                                    $excel2 = $excel2->load($imgPath);
                                    $excel2->setActiveSheetIndex(0);

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
                                            $iColSku    = 10;
                                            $iColStock  = 7;
                                            $iColStatus = 9;

                                            $product_sku = $val[11];
                                            $exp_sku = explode("-", $product_sku);
                                            $product_code = $exp_sku[0];
                                            $varian_id    = "";
                                            if(count($exp_sku) > 1){
                                                $varian_id = $exp_sku[1];
                                            }
                                            $m = $this->db->get_where("mt_product",array(
                                                "product_code" => $product_code
                                            ),1,0)->row();
                                            if(count($m) > 0){
                                                $m2 = $this->db->get_where("mt_product_detail",array(
                                                    "product_id"      => $m->product_id
                                                ),1,0)->row();
                                                if(count($m2) > 0){
                                                    $product_stock_detail = $m2->product_stock_detail;
                                                    if($product_stock_detail != ""){
                                                        $exp = json_decode($product_stock_detail);
                                                        foreach ($exp as $key3 => $val3) {
                                                            if($val3->id == $varian_id){
                                                                $value = ($val3->qty>0?$val3->qty:0);
                                                                if($value <= 0){
                                                                    $excel2->getActiveSheet()->setCellValueByColumnAndRow(8, $row, "false");
                                                                    $excel2->getActiveSheet()->setCellValueByColumnAndRow($iColStatus, $row, 3);
                                                                    $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleVarianSold);
                                                                }
                                                                // if(count($data['items_unpaid']) > 0){
                                                                // $product_code_varian = strtoupper($m->product_code."-".$val3->id);
                                                                //     if(in_array($product_code_varian, $temp_unpaid)){
                                                                //         foreach ($data['items_unpaid'] as $k2 => $v2) {
                                                                //             if($v2->product_code == $product_code_varian){
                                                                //                 $value -= $v2->product_qty;
                                                                //                 $value = ($value>0?$value:0);
                                                                //             }
                                                                //         }
                                                                //     }
                                                                // }

                                                                $excel2->getActiveSheet()->setCellValueByColumnAndRow($iColStock, $row, $value);
                                                            }

                                                        }
                                                    } else {
                                                        $value = ($m2->product_stock>0?$m2->product_stock:0);
                                                        $excel2->getActiveSheet()->setCellValueByColumnAndRow($iColStock, $row, $value);
                                                    }
                                                    $product_status_id    = $m2->product_status_id;
                                                    if($product_status_id != 1){
                                                        $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleSold);
                                                    }
                                                }

                                                $arr_tmp_id[] = $m->product_id;
                                                $count += 1;
                                            } else {
                                                $data['err']  = true;
                                                // $data['msg'] .= '- ID: '.$val[11].' tidak ditemukan.<br>';
                                                $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleNotFound);
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
                                    $filename     = "shop-1454973-product-list.xlsx";
                                    // $filename     = changeEnUrl($this->store_name).'_update_stock_tokopedia_'.convDateFilename(timestamp()).'.xlsx';
                                    $fileDownload = $download_path.$filename;
                                    $objWriter    = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
                                    $objWriter->save($fileDownload);
                                }

                            } else if($type_name == "update_stock_lazada"){
                                if($titleRow[1] == "SellerSku" && $titleRow[2] == "Quantity"){

                                    $styleSold = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => 'FF0000')
                                        ));
                                    $styleVarianSold = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => '0000FF')
                                        ));
                                    $styleNotFound = array(
                                        'font'  => array(
                                            'bold'  => true,
                                            'color' => array('rgb' => 'FFF000')
                                        ));

                                    /** Error reporting */
                                    error_reporting(E_ALL);
                                    ini_set('display_errors', TRUE);
                                    ini_set('display_startup_errors', TRUE);
                                    date_default_timezone_set('Asia/Jakarta');

                                    $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
                                    $excel2 = $excel2->load($imgPath);
                                    $excel2->setActiveSheetIndex(0);

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
                                            $iColSku    = 0;
                                            $iColStock  = 1;
                                            $iColStatus = NULL;
                                            $product_sku  = $val[1];
                                            $product_qty  = $val[2];
                                            $product_name = $val[3];
                                            $exp_sku = explode("-", $product_sku);
                                            $product_code = $exp_sku[0];
                                            $varian_id    = "";
                                            if(count($exp_sku) > 1){
                                                $varian_id = $exp_sku[1];
                                            }
                                            $m = $this->db->get_where("mt_product",array(
                                                "product_code" => $product_code
                                            ),1,0)->row();
                                            if(count($m) > 0){
                                                $m2 = $this->db->get_where("mt_product_detail",array(
                                                    "product_id"      => $m->product_id
                                                ),1,0)->row();
                                                if(count($m2) > 0){
                                                    $product_stock_detail = $m2->product_stock_detail;
                                                    if($product_stock_detail != ""){
                                                        $exp = json_decode($product_stock_detail);
                                                        foreach ($exp as $key3 => $val3) {
                                                            if($val3->id == $varian_id){
                                                                $value = ($val3->qty>0?$val3->qty:0);
                                                                if($value <= 0){
                                                                    $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleVarianSold);
                                                                }
                                                                $excel2->getActiveSheet()->setCellValueByColumnAndRow($iColStock, $row, $value);
                                                            }
                                                        }
                                                    } else {
                                                        $value = ($m2->product_stock>0?$m2->product_stock:0);
                                                        $excel2->getActiveSheet()->setCellValueByColumnAndRow($iColStock, $row, $value);
                                                    }
                                                    $product_status_id    = $m2->product_status_id;
                                                    if($product_status_id != 1){
                                                        $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleSold);
                                                    }
                                                }

                                                $arr_tmp_id[] = $m->product_id;
                                                $count += 1;
                                            } else {
                                                $data['err']  = true;
                                                $data['msg'] .= '- ID: '.$val[1].' tidak ditemukan.<br>';
                                                $excel2->getActiveSheet()->getCellByColumnAndRow($iColSku, $row)->getStyle()->applyFromArray($styleNotFound);
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
                                    $filename     = changeEnUrl($this->store_name).'_update_stock_lazada_'.convDateFilename(timestamp()).'.xlsx';
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

            $thisUrl = $this->own_link;
            if($_POST['thisUrl'] != ""){ $thisUrl = $_POST['thisUrl']; }
            redirect($thisUrl."?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success')."".($fileDownload!=""?"&download=".base_url().$fileDownload:""));
        }
    }

    // DOWNLOAD
    function download_all_list_stock(){
        $data = array();
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Jakarta');

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator($this->store_name)
                                     ->setLastModifiedBy($this->store_name)
                                     ->setTitle("Data Stock Product")
                                     ->setSubject("Data Stock Product")
                                     ->setDescription("Data Stock Product")
                                     ->setKeywords("Data Stock Product")
                                     ->setCategory("Data Stock Product");

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'product_id')
                                      ->setCellValue('B1', 'product_code')
                                      ->setCellValue('C1', 'product_name')
                                      ->setCellValue('D1', 'product_category_title')
                                      ->setCellValue('E1', 'product_status_id')
                                      ->setCellValue('F1', 'product_price_buy')
                                      ->setCellValue('G1', 'product_price_sale')
                                      ->setCellValue('H1', 'product_price_grosir')
                                      ->setCellValue('I1', 'product_price_discount')
                                      ->setCellValue('J1', 'product_stock')
                                      ->setCellValue('K1', 'product_stock_first')
                                      ->setCellValue('L1', 'product_stock_detail');

        $data['data_export'] = array();
        $product = $this->db->order_by("product_id","desc")->get_where("mt_product",array(
            'store_id' => $this->store_id
        ))->result();
        if(count($product) > 0){
            $iRow = 2; // 1-based index
            foreach ($product as $key => $val) {
                $detail = get_product_detail($val->product_id);

                $objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, $val->product_id)
                                              ->setCellValue('B'.$iRow, strtoupper($val->product_code))
                                              ->setCellValue('C'.$iRow, $val->product_name)
                                              ->setCellValue('D'.$iRow, get_product_category_name($val->product_category_id))
                                              ->setCellValue('E'.$iRow, $detail->product_status_id)
                                              ->setCellValue('F'.$iRow, $detail->product_price_buy)
                                              ->setCellValue('G'.$iRow, $detail->product_price_sale)
                                              ->setCellValue('H'.$iRow, $detail->product_price_grosir)
                                              ->setCellValue('I'.$iRow, $detail->product_price_discount)
                                              ->setCellValue('J'.$iRow, $detail->product_stock)
                                              ->setCellValue('K'.$iRow, $detail->product_stock_first)
                                              ->setCellValue('L'.$iRow, $detail->product_stock_detail);

                $iRow += 1;
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('All List Stock');
        $objPHPExcel->setActiveSheetIndex(0);

        $filename = changeEnUrl($this->store_name).'_all_list_stock';

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

    function download_list_stock(){
        $data = array();
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
        $data = array();
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
