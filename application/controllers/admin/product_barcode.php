<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class product_barcode extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        $this->_set_title( 'Cetak Barcode Produk' );
        $this->DATA->table="mt_print_barcode";
        $this->folder_view = "product/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_product","M");
        $this->breadcrumb[] = array(
                "title"     => "Cetak Barcode Produk",
                "url"       => $this->own_link
            );

        $this->cat_search = array(
            ''                                          => 'Semua Pencarian...',
            'mt_product.product_name'                   => 'Nama Product',
            'mt_product.product_name_simple'            => 'Nama Singkat',
            'mt_product.product_code'                   => 'Kode',
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
            'class'     => $this->_getClass(),
            'name'      => 'product_barcode',
            'date_start'=> '',
            'date_end'  => '',
            'status'    => '',
            'order_by'  => 'mt_print_barcode.print_barcode_date',
            'order_dir' => 'desc',
            'filter'    => '25',
            'colum'     => '',
            'keyword'   => '',
            'orders_source_id'   => NULL,
            'orders_courier_id'  => NULL
        );
        $this->_releaseSession();
    }

    function index(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'product_barcode'){
            $this->_reset();
        }

        $this->breadcrumb[] = array(
            "title"     => 'List'
        );

        $order_by = $this->jCfg['search']['order_by'];
        $_POST['order_by'] = 'mt_print_barcode.print_barcode_date-desc';
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
            $explode_order_by = explode("-", $_POST['order_by']);
            $this->jCfg['search']['order_by'] = $explode_order_by[0];
            $this->jCfg['search']['order_dir'] = $explode_order_by[1];
            $this->_releaseSession();
        }

        $par_filter = array(
            "store_id"              => $this->store_id,
            "print_barcode_status"  => 0,
            "offset"                => NULL,
            "limit"                 => NULL,
            "param"                 => NULL
        );
        $data = $this->M->data_product_barcode($par_filter);

        $data['tab']            = 'tab1';
        $data['content_layout'] = $this->prefix_view."_new_print.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function list_all(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'product_barcode_list_all'){
            $this->_reset();
            $this->jCfg['search']['name'] = 'product_barcode_list_all';
            $this->_releaseSession();
        }

        $this->breadcrumb[] = array(
            "title"     => 'Semua List'
        );

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'search'){
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

        if(isset($_POST['searchAction']) && $_POST['searchAction'] == 'reset'){
            $this->_reset();
        }

        $order_by = $this->jCfg['search']['order_by'];
        // $_POST['order_by'] = 'mt_print_barcode.print_barcode_date-desc';
        if(isset($_POST['order_by']) && $_POST['order_by'] != ''){
            $explode_order_by = explode("-", $_POST['order_by']);
            $this->jCfg['search']['order_by'] = $explode_order_by[0];
            $this->jCfg['search']['order_dir'] = $explode_order_by[1];
            $this->_releaseSession();
        }

        $par_filter = array(
            "store_id"  => $this->store_id,
            "offset"    => $this->uri->segment($this->uri_segment),
            "limit"     => $this->per_page,
            "param"     => $this->cat_search
        );
        $this->data_table = $this->M->data_product_barcode($par_filter);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/list_all'
        ));

        $data['url']            = base_url()."admin/product_barcode/list_all";
        $data['url_form']       = base_url()."admin/product_barcode/list_all";
        $data['tab']            = 'tab2';
        $data['content_layout'] = $this->prefix_view."_list_all.php";
        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function print_again(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
            $id = $_POST['thisId'];
            $m = $this->db->get_where("mt_print_barcode",array(
                "store_id"             => $this->store_id,
                "print_barcode_id"     => $id
            ),1,0)->row();
            if(count($m) > 0){
                $data1 = array(
                    'print_barcode_status'      => 0
                );

                $this->db->update("mt_print_barcode",$data1,array("print_barcode_id"=>$m->print_barcode_id));
                $data['err'] = false;
                $data['msg'] = 'Sukses mengubah produk.';
            } else {
                $data['err'] = true;
                $data['msg'] = 'Produk tidak ditemukan.';
            }

        }

        die(json_encode($data));
        exit();
    }

    function deletes(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'delete'){
            $id = $_POST['thisId'];
            $m = $this->db->get_where("mt_print_barcode",array(
                "store_id"             => $this->store_id,
                "print_barcode_id"     => $id
            ),1,0)->row();
            if(count($m) > 0){
                $data1 = array(
                    'print_barcode_status'      => 1,
                    'print_barcode_istrash'     => 1
                );

                $this->db->update("mt_print_barcode",$data1,array("print_barcode_id"=>$m->print_barcode_id));
                $data['err'] = false;
                $data['msg'] = 'Sukses menghapus produk.';
            } else {
                $data['err'] = true;
                $data['msg'] = 'Produk tidak ditemukan.';
            }

        }

        die(json_encode($data));
        exit();
    }

    function save_multi_detail(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['href'] = '';
        $data['data'] = array();

        if(isset($_POST['checked_product']) && $_POST['checked_product'] != ''){
            $checked_product = $_POST['checked_product'];
            foreach ($checked_product as $k => $v) {
                $product_id  = $v;
                $product_qty = $_POST['product_qty'][$product_id];

                $print_barcode_id = '';
                $m = $this->db->get_where("mt_print_barcode",array(
                        "store_id"             => $this->store_id,
                        "product_id"           => $product_id,
                        "print_barcode_status" => 0
                ),1,0)->row();
                if(count($m) > 0){
                    $print_barcode_id = $m->print_barcode_id;
                }

                $product = get_detail_product($product_id);
                if(count($product) > 0){
                    $data1 = array(
                        'store_id'               => $this->store_id,
                        'product_id'             => $product_id,
                        'product_qty'            => $product_qty,
                        'print_barcode_status'   => 0,
                        'print_barcode_istrash'  => 0,
                        'print_barcode_date'     => timestamp()
                    );

                    if($product_qty != ""){
                        $detail = get_product_detail($product_id);
                        if($detail->product_stock_detail != ''){
                            $arr_stock = array();
                            $product_stock_detail = json_decode($detail->product_stock_detail);
                            foreach ($product_stock_detail as $key3 => $value3) {
                                $arr_stock[] = array('id'       => $value3->id,
                                                     'name'     => $value3->name,
                                                     'qty'      => $product_qty
                                                  );
                            }
                            $data1['product_detail'] = json_encode($arr_stock);
                        }
                    }

                    $this->DATA->table="mt_print_barcode";
                    $a1 = $this->_save_master(
                        $data1,
                        array(
                            'print_barcode_id' => $print_barcode_id
                            ),
                        $print_barcode_id
                    );
                }
            }

            $data['err'] = false;
            $data['msg'] = 'Sukses menambahkan produk.';
        }

        die(json_encode($data));
        exit();
    }

    function get_table_add_products($id=""){
        $msg     = '';
        $content = '';
        $dataProduk = get_table_products($this->store_id);
        if(count($dataProduk) > 0){
            foreach($dataProduk as $r){
            $isFound = false;
            $chkdisabled = "";
            $print_barcode = $this->db->get_where("mt_print_barcode",array(
                 "store_id"             => $this->store_id,
                 "product_id"           => $r->product_id,
                 "print_barcode_status" => 0
            ),1,0)->row();
            if(count($print_barcode) > 0){
                $chkdisabled="disabled checked";
                $isFound = true;
            }

            $content .= '<tr class="'.($isFound?' selected':'').'">
                <td class="nobr text-center">
                    <input type="checkbox" name="checked_product[]" data-id="'.$r->product_id.'" value="'.$r->product_id.'" '.($isFound?' checked':'').' >
                </td>
                <td class="nobr text-center">'.$r->product_id.'.</td>
                <td>
                    <img src="'.get_image(base_url()."assets/collections/product/thumb/".get_cover_image_detail($r->product_id)).'" class="avatar">
                </td>
                <td>'.$r->product_name.($isFound?'<br/><small class="text-danger">(Sudah Ditambahkan)</small>':'').'</td>
                <td class="nobr">'.get_product_category_name($r->product_category_id).'</td>
                <td class="nobr text-center"><input type="number" name="product_qty['.$r->product_id.']" value="'.$print_barcode->product_qty.'" class="form-control product_qty" min="0" /></td>
                <td class="nobr text-center">'.($r->product_show_id=='1'?'<span class="label label-success">Tampil</span>':'<span class="label label-danger">Tidak Tampil</span>').'</td>
            </tr>';
            }
            $msg = 'success';
        }

        $return = array('msg' => $msg,'content' => $content);
        die(json_encode($return));
        exit();
    }

    function generate_export(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['href'] = '';
        $data['result']  = array();
        $data['plastik'] = array();

        $this->jCfg['export_barcode']  = array();
        $this->jCfg['plastik_barcode'] = array();
        $this->_releaseSession();

        if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
            $checked_files = $_POST['checked_files'];
            foreach ($checked_files as $k => $v) {

                $m = $this->db->get_where("mt_print_barcode",array(
                        "store_id"             => $this->store_id,
                        "print_barcode_id"     => $v
                ),1,0)->row();
                if(count($m) > 0){
                    $dataBarcode = array();

                    $dataBarcode['product_qty'] = $m->product_qty;
                    if($m->product_detail != ''){
                        $arr_stock = array();
                        $product_detail = json_decode($m->product_detail);
                        foreach ($product_detail as $key2 => $value2) {
                            $arr_stock[] = array('id'       => $value2->id,
                                                 'name'     => $value2->name,
                                                 'qty'      => $_POST['product_qty'][$m->print_barcode_id.'-'.$value2->id]
                                              );
                        }
                        $dataBarcode['product_detail'] = json_encode($arr_stock);
                    } else {
                        $dataBarcode['product_qty'] = $_POST['product_qty'][$m->print_barcode_id];
                    }

                    $dataBarcode['print_barcode_status'] = 1;
                    $this->db->update("mt_print_barcode",$dataBarcode,array("print_barcode_id"=>$m->print_barcode_id));

                    // $m2 = $this->db->get_where("mt_print_barcode",array(
                    //         "store_id"             => $this->store_id,
                    //         "print_barcode_id"     => $v
                    // ),1,0)->row();
                    // $product_detail = json_decode($m2->product_detail);

                    $product_id   = $m->product_id;
                    $optSaveStock = $_POST['optSaveStock'][$v];
                    if($optSaveStock == '1'){
                        $dataProduct = array();

                        $total_qty = 0;
                        $arr_stock2 = array();
                        $detail    = get_product_detail($product_id);
                        if($detail->product_stock_detail != ''){
                            $product_stock_detail = json_decode($detail->product_stock_detail);
                            foreach ($product_stock_detail as $key3 => $value3) {
                                $new_qty = $value3->qty;
                                $product_detail = json_decode($dataBarcode['product_detail']);
                                foreach ($product_detail as $key2 => $value2) {
                                    if($value2->id == $value3->id){
                                        $new_qty   = $value2->qty;
                                        $total_qty = $total_qty + $new_qty;
                                    }
                                }

                                $arr_stock2[] = array('id'       => $value3->id,
                                                      'name'     => $value3->name,
                                                      'color'    => $value3->color,
                                                      'qty'      => $new_qty,
                                                      'status'   => ($new_qty>0?1:2)
                                                   );
                            }
                            $dataProduct['product_stock_detail'] = json_encode($arr_stock2);
                        } else {
                            $total_qty = $_POST['product_qty'][$m->print_barcode_id];
                        }
                        $dataProduct['product_stock']        = $total_qty;
                        $dataProduct['product_stock_first']  = $total_qty;

                        $this->db->update("mt_product_detail",$dataProduct,array("product_id"=>$product_id));
                        set_last_date_product_setup();
                    } else if($optSaveStock == '2'){
                        $dataProduct = array();

                        $total_qty = 0;
                        $total_qty_first = 0;
                        $total_qty_barcode = 0;
                        $arr_stock2 = array();
                        $detail    = get_product_detail($product_id);
                        if($detail->product_stock_detail != ''){
                            $product_stock_detail = json_decode($detail->product_stock_detail);
                            foreach ($product_stock_detail as $key3 => $value3) {
                                $new_qty = $value3->qty;
                                $product_detail = json_decode($dataBarcode['product_detail']);
                                foreach ($product_detail as $key2 => $value2) {
                                    if($value2->id == $value3->id){
                                        $new_qty   = ($value3->qty + $value2->qty);
                                        $total_qty = $total_qty + $new_qty;
                                        $total_qty_barcode = $total_qty_barcode + $value2->qty;
                                    }
                                }

                                $arr_stock2[] = array('id'       => $value3->id,
                                                      'name'     => $value3->name,
                                                      'color'    => $value3->color,
                                                      'qty'      => $new_qty,
                                                      'status'   => ($new_qty>0?1:2)
                                                   );
                            }
                            $dataProduct['product_stock_detail'] = json_encode($arr_stock2);
                            $total_qty_first = ($detail->product_stock_first + $total_qty_barcode);
                        } else {
                            $total_qty = ($detail->product_stock + $_POST['product_qty'][$m->print_barcode_id]);
                            $total_qty_first = ($detail->product_stock_first + $_POST['product_qty'][$m->print_barcode_id]);
                        }
                        $dataProduct['product_stock']        = $total_qty;
                        $dataProduct['product_stock_first']  = $total_qty_first;

                        $this->db->update("mt_product_detail",$dataProduct,array("product_id"=>$product_id));
                        set_last_date_product_setup();
                    }

                    $printed_barcode = get_printed_barcode($m->print_barcode_id);
                    if($printed_barcode->product_detail != ''){
                        $detail = json_decode($printed_barcode->product_detail);
                        foreach ($detail as $key5 => $val5) {
                            for($ii=0; $ii<$val5->qty; $ii++){
                                $data['result'][] = array(
                                    'product_barcode'   => create_product_barcode($printed_barcode->product_code,$val5->id),
                                    'product_name'      => $printed_barcode->product_name_simple.' '.$val5->name,
                                    'category_name'     => $printed_barcode->product_category_title,
                                    'price_sale'        => $printed_barcode->product_price_sale,
                                    'store_code'        => $printed_barcode->store_code
                                );
                            }
                            $data['plastik'][] = array(
                                'product_barcode'   => create_product_barcode($printed_barcode->product_code),
                                'product_name'      => $printed_barcode->product_name_simple,
                                'product_varian'    => $val5->name
                            );
                        }
                    } else {
                        for($ii=0; $ii<$printed_barcode->product_qty; $ii++){
                            $data['result'][] = array(
                                'product_barcode'   => create_product_barcode($printed_barcode->product_code),
                                'product_name'      => $printed_barcode->product_name_simple,
                                'category_name'     => $printed_barcode->product_category_title,
                                'price_sale'        => $printed_barcode->product_price_sale,
                                'store_code'        => $printed_barcode->store_code
                            );
                        }
                        $data['plastik'][] = array(
                            'product_barcode'   => create_product_barcode($printed_barcode->product_code),
                            'product_name'      => $printed_barcode->product_name_simple,
                            'product_varian'    => NULL
                        );
                    }
                }
            }

            $this->jCfg['export_barcode']  = arrayToObj($data['result']);
            $this->jCfg['plastik_barcode'] = arrayToObj($data['plastik']);
            $this->_releaseSession();

            $data['err'] = false;
            $data['msg'] = 'Sukses menyimpan jumlah print barcode dan akan segera mendownloadnya.';

        }

        die(json_encode($data));
        exit();
    }

    function export_barcode(){
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Jakarta');

        $export_barcode  = isset($this->jCfg['export_barcode'])?$this->jCfg['export_barcode']:array();
        $plastik_barcode = isset($this->jCfg['plastik_barcode'])?$this->jCfg['plastik_barcode']:array();
        if(count($export_barcode) > 0){
            $timestamp = timestamp();

            $item_barcode = $export_barcode;
            if(count($item_barcode) > 0){

                // debugCode($item_barcode);
                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->getProperties()->setCreator($this->store_name)
                                             ->setLastModifiedBy($this->store_name)
                                             ->setTitle("Data Barcode Product ".$this->store_name)
                                             ->setSubject("Data Barcode Product ".$this->store_name)
                                             ->setDescription("Data Barcode Product ".$this->store_name)
                                             ->setKeywords("Data Barcode Product")
                                             ->setCategory("Data Barcode Product");

                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'product_barcode')
                                              ->setCellValue('B1', 'product_name')
                                              ->setCellValue('C1', 'category_name')
                                              ->setCellValue('D1', 'price_sale')
                                              ->setCellValue('E1', 'store_code');

                $iRow = 2;
                foreach ($item_barcode as $k => $v) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, strtoupper($v->product_barcode))
                                                  ->setCellValue('B'.$iRow, $v->product_name)
                                                  ->setCellValue('C'.$iRow, $v->category_name)
                                                  ->setCellValue('D'.$iRow, convertRp($v->price_sale))
                                                  ->setCellValue('E'.$iRow, strtoupper($v->store_code));
                    $iRow += 1;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Master');
                // $objPHPExcel->setActiveSheetIndex(0);

                $objPHPExcel->createSheet(1);
                $objPHPExcel->setActiveSheetIndex(1);
                $objPHPExcel->getActiveSheet()->setTitle('Plastik');
                $objPHPExcel->getActiveSheet()->setCellValue("A1", "product_barcode");
                $objPHPExcel->getActiveSheet()->setCellValue("B1", "product_name");
                $objPHPExcel->getActiveSheet()->setCellValue("C1", "product_varian");
                $iRow = 2;
                foreach ($plastik_barcode as $k => $v) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$iRow, strtoupper($v->product_barcode))
                                                  ->setCellValue('B'.$iRow, $v->product_name)
                                                  ->setCellValue('C'.$iRow, $v->product_varian);
                    $iRow += 1;
                }

                $objPHPExcel->setActiveSheetIndex(0);

                $filename = 'barcode_'.changeEnUrl($this->store_name);

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

                $this->jCfg['export_barcode'] = array();
                $this->_releaseSession();
            } else {
                echo 'Tidak ada item yang ingin di export...';
            }
        } else {
            echo 'Tidak ada data yang ingin di export...';
        }
    }

}
