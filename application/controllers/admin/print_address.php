<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class print_address extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_title( 'List Alamat Pengiriman' );
		$this->DATA->table="mt_print_address";
		$this->folder_view = "orders/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_orders","M");
		$this->breadcrumb[] = array(
				"title"		=> "List Alamat Pengiriman",
				"url"		=> $this->own_link
			);

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        $this->detail_store     = get_detail_store($this->store_id);
        $this->store_name       = $this->detail_store->store_name;
        $this->store_phone      = $this->detail_store->store_phone;
        $this->store_product    = $this->detail_store->store_product;
	}

	function index(){
		$data = array();
		$data['tab'] = 'tab1';

		$this->db->order_by('print_address_status asc, orders_shipping_name asc');
		$data['data'] = $this->db->get_where("mt_print_address",array(
			"store_id " 			=> $this->store_id,
			"print_address_status " => 0
		))->result();

		$this->breadcrumb[] = array(
				"title"		=> $this->store_name
			);

        $data['content_layout'] = $this->prefix_view."_new_label.php";
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function list_all(){
		$data = array();
		$data['tab'] = 'tab2';

		$timestamp  = timestamp();
		$date_start = getMinWeekly($timestamp, 1);
		$date_end   = $timestamp;

		$this->db->select("mt_print_address.*");
		$this->db->where("store_id", $this->store_id);
		$this->db->where("( print_address_date <= '".(convDatepickerDec($date_end))." 23:59:59' )");
		$this->db->where("( print_address_date >= '".(convDatepickerDec($date_start))." 00:00:00' )");
		$this->db->order_by('print_address_date', 'desc');
		$data['data'] = $this->db->get("mt_print_address")->result();

		$this->breadcrumb[] = array(
				"title"		=> $this->store_name
			);

        $data['url_form']       = base_url()."admin/print_address/list_all";
        $data['content_layout'] = $this->prefix_view."_list_all.php";
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

    function views(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));

            if(trim($thisVal)==''){ $thisVal = 0; }
            $r = $this->db->get_where("mt_print_address",array(
                'print_address_id ' => $thisVal,
                'store_id '         => $this->store_id
            ),1,0)->row();

            $i = 0;
            $orders_source = '';
            $arr_orders_source = get_orders_source();
            foreach ($arr_orders_source as $k => $v) {
                $selected = (($i=='0')||($v->orders_source_id==$r->orders_source_id)?'selected':'');
                $orders_source .= '<option value="'.$v->orders_source_id.'" '.$selected.'>'.$v->orders_source_name.'</option>';
                $i += 1;
            }

            $i = 0;
            $shipping_courier = '';
            $get_orders_courier = get_orders_courier();
            foreach ($get_orders_courier as $k => $v) {
                $get_orders_courier2 = get_orders_courier($v->orders_courier_id, true);
                foreach ($get_orders_courier2 as $k2 => $v2) {
                    $shipping_courier .= '<option value="'.$v2->orders_courier_id.'" '.(($i=='0')||($r->orders_courier_id==$v2->orders_courier_id)?'selected':'').'>'.$v->orders_courier_name.' - '.$v2->orders_courier_service.'</option>';
                    $i += 1;
                }
            }

            $data['content'] = '
            <form class="form_save_new_label" action="'.$this->own_link.'/save_new_label" method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <legend>Form Alamat Pengiriman</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Orderan Dari</label>
                        <div class="col-sm-4">
                            <select name="orders_source_id" class="form-control" required>
                                '.$orders_source.'
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="orders_source_invoice" value="'.$r->orders_source_invoice.'" class="form-control text-uppercase" placeholder="No Invoice Bukalapak / Tokopedia">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kurir</label>
                        <div class="col-sm-4">
                            <select name="orders_courier_id" class="form-control">
                                <option value="" selected>--- Pilih ----</option>
                                '.$shipping_courier.'
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="orders_product_category_title" value="'.($r->orders_product_category_title!=''?$r->orders_product_category_title:$this->store_product).'" class="form-control" placeholder="Isi Paket">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Pengirim</label>
                        <div class="col-sm-4">
                            <input type="text" name="orders_ship_name" value="'.($r->orders_ship_name!=''?$r->orders_ship_name:$this->store_name).'" class="form-control" placeholder="'.$this->store_name.'">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="orders_ship_phone" value="'.($r->orders_ship_phone!=''?$r->orders_ship_phone:$this->store_phone).'" class="form-control" placeholder="'.$this->store_phone.'">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Penerima</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_name" value="'.$r->orders_shipping_name.'" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Hp</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_phone" value="'.$r->orders_shipping_phone.'" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat Lengkap</label>
                        <div class="col-sm-9">
                            <textarea name="orders_shipping_address" class="form-control no-resize" rows="3" maxlength="300" >'.$r->orders_shipping_address.'</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kode Booking</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_resi" value="'.$r->orders_shipping_resi.'" class="form-control text-uppercase" placeholder="Kode Booking" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Harga Barang</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_product" value="'.convertRp($r->orders_price_product).'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                        <label class="col-sm-2 control-label">Asuransi</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_insurance" value="'.convertRp($r->orders_price_insurance).'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Estimasi Ongkir</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_shipping" value="'.convertRp($r->orders_price_shipping).'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                        <label class="col-sm-2 control-label">Berat</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="orders_shipping_weight" value="'.$r->orders_shipping_weight.'" class="form-control" maxlength="2" placeholder="1">
                                <span class="input-group-addon">Kg</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Catatan Order (optional)</label>
                        <div class="col-sm-9">
                            <textarea name="orders_noted" class="form-control no-resize" rows="3" maxlength="300" >'.$r->orders_noted.'</textarea>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="hidden" name="print_address_id" value="'.$r->print_address_id.'" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                        </div>
                    </div>
                </div>
            </form>
            ';

        }

        die(json_encode($data));
        exit();
    }

    function form_detail_courier(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));

            if(trim($thisVal)==''){ $thisVal = 0; }
            $r = $this->db->get_where("mt_print_address",array(
                'print_address_id '    => $thisVal,
                'store_id '            => $this->store_id
            ),1,0)->row();

            $data['content'] = '
            <form class="form_save_detail_courier" action="'.$this->own_link.'/save_detail_courier" method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <legend>'.$r->orders_source_invoice.'</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Penerima</label>
                        <div class="col-sm-9">
                            <div class="form-control">'.$r->orders_shipping_name.'</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Isi Paket</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_product_category_title" value="'.($r->orders_product_category_title!=''?$r->orders_product_category_title:$this->store_product).'" class="form-control" placeholder="Isi Paket">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kode Booking</label>
                        <div class="col-sm-9">
                            <input type="text" name="orders_shipping_resi" value="'.$r->orders_shipping_resi.'" class="form-control text-uppercase" placeholder="Kode Booking" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Harga Barang</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_product" value="'.convertRp($r->orders_price_product).'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                        <label class="col-sm-2 control-label">Asuransi</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_insurance" value="'.convertRp($r->orders_price_insurance).'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Estimasi Ongkir</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" name="orders_price_shipping" value="'.convertRp($r->orders_price_shipping).'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                            </div>
                        </div>
                        <label class="col-sm-2 control-label">Berat</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="orders_shipping_weight" value="'.$r->orders_shipping_weight.'" class="form-control" maxlength="2" placeholder="1">
                                <span class="input-group-addon">Kg</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="hidden" name="print_address_id" value="'.$r->print_address_id.'" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                        </div>
                    </div>
                </div>
            </form>
            ';

        }

        die(json_encode($data));
        exit();
    }

    function save_detail_courier(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){

            $data2 = array(
                'orders_product_category_title' => dbClean($_POST['orders_product_category_title']),
                'orders_price_product'          => dbClean(convertRpToInt($_POST['orders_price_product'])),
                'orders_price_insurance'        => dbClean(convertRpToInt($_POST['orders_price_insurance'])),
                'orders_price_shipping'         => dbClean(convertRpToInt($_POST['orders_price_shipping'])),
                'orders_shipping_resi'          => dbClean(strtoupper($_POST['orders_shipping_resi'])),
                'orders_shipping_weight'        => dbClean($_POST['orders_shipping_weight'])
            );

            $this->db->update("mt_print_address",$data2,array("print_address_id"=>dbClean($_POST['print_address_id'])));
            $data['err'] = false;
            $data['msg'] = 'Sukses menyimpan data.';

        }

        die(json_encode($data));
        exit();
    }

	function deletes(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'delete'){
			$id = '';
			$exp = '';
			$data['data'] = array();
			if(isset($_POST['thisId']) && $_POST['thisId'] !=''){

                $id = $_POST['thisId'];
				$this->DATA->table="mt_print_address";
				$exp = explode("-", $id);
				foreach ($exp as $key) {
					$print_address = get_print_address($key);
                    if($print_address->store_id == $this->store_id){
                        $this->DATA->_delete(array("print_address_id"   => idClean($key)),true);
                    }
				}
			}

			$data['err'] = false;
			$data['msg'] = 'Sukses menghapus data.';
		}

		die(json_encode($data));
		exit();
	}

	function save_new_label(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
			$orders_dropship = 0;
            if($this->store_name != dbClean($_POST['orders_ship_name']) ){
				$orders_dropship = 1;
                if($this->store_id == 1){
    				$param = array('member_phone' => dbClean($_POST['orders_ship_phone']));
    				$search_isreseller = get_search_data_member($param);
    				if(count($search_isreseller) == 0){
    					$dataMember = array(
    						'member_name'			=> dbClean(ubah_huruf_awal($_POST['orders_ship_name'])),
    						'member_username'		=> generateUniqueUsername(dbClean($_POST['orders_ship_name'])),
    						'member_phone' 			=> dbClean($_POST['orders_ship_phone']),
    						'member_status' 		=> 0,
    						'member_date'			=> timestamp(),
    						'member_isreseller'		=> 1,
    						'member_istrash'		=> 0
    					);

    					$this->DATA->table="mt_member";
    					$a = $this->_save_master(
    						$dataMember,
    						array(
    							'member_id' => dbClean($_POST['member_id'])
    							),
    						dbClean($_POST['member_id'])
    						);
    				}
                }
            }

			$data2 = array(
				'store_id'						=> $this->store_id,
				'orders_source_id'				=> dbClean($_POST['orders_source_id']),
				'orders_source_invoice' 		=> dbClean(strtoupper($_POST['orders_source_invoice'])),
				'orders_shipping_resi' 			=> dbClean(strtoupper($_POST['orders_shipping_resi'])),
				'orders_shipping_dropship'		=> $orders_dropship,
				'orders_ship_name'				=> dbClean(ubah_huruf_awal($_POST['orders_ship_name'])),
				'orders_ship_phone'				=> dbClean($_POST['orders_ship_phone']),
				'orders_shipping_name'			=> dbClean(strtoupper($_POST['orders_shipping_name'])),
				'orders_shipping_address'		=> dbClean(ubah_huruf_awal($_POST['orders_shipping_address'])),
				'orders_shipping_phone' 		=> dbClean($_POST['orders_shipping_phone']),
                'orders_price_shipping'         => dbClean(convertRpToInt($_POST['orders_price_shipping'])),
                'orders_shipping_weight'        => dbClean($_POST['orders_shipping_weight']),
				'orders_product_category_title' => dbClean($_POST['orders_product_category_title']),
                'orders_price_product'          => dbClean(convertRpToInt($_POST['orders_price_product'])),
				'orders_price_insurance' 	    => dbClean(convertRpToInt($_POST['orders_price_insurance'])),
                'orders_courier_id'             => dbClean($_POST['orders_courier_id']),
				'orders_noted' 					=> dbClean($_POST['orders_noted']),
				'print_address_status' 			=> 0,
				'print_address_istrash' 		=> 0,
				'print_address_date' 			=> timestamp()
			);

			$this->DATA->table="mt_print_address";
			$a = $this->_save_master(
				$data2,
				array(
					'print_address_id' => dbClean($_POST['print_address_id'])
					),
				dbClean($_POST['print_address_id'])
				);

			$id = $a['id'];
			$data['err'] = false;
			$data['msg'] = 'Sukses menyimpan data.';

		}

		die(json_encode($data));
		exit();
	}

	function empty_tmp_file(){
		$files = glob('./assets/collections/tmp_files/*');
		foreach($files as $file){
			if(is_file($file))
			unlink($file);
		}
		redirect($this->own_link."?msg=".urlencode("Empty file excel successfully...")."&type_msg=success");
	}

    function upload(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['items'] = array();
        $count = 0;

        $this->load->model("mdl_marketplace_excel","MME");
        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'upload'){

            $orders_source_id = $_POST['orders_source_id'];
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

                            $paramExcel = array();
                            for ($row = 0; $row < $highestRow; $row++) {
                                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                    $cellVal = "";
                                    $cell = $worksheet->getCellByColumnAndRow($col, ($row + 1));
                                    if(strstr($cell->getValue(),'=')==true){
                                        $cellVal = $worksheet->getCellByColumnAndRow($col, ($row + 1))->getCalculatedValue();
                                    } else {
                                        $cellVal = $cell->getValue();
                                    }
                                    $colName = "col_".$col;
                                    $paramExcel[$row]->$colName = $cellVal;
                                }
                            }

                            if($orders_source_id == 3){ // TOKOPEDIA
                                $dataExcel     = $this->MME->data_tokopedia_new_orders($paramExcel);
                            } else if($orders_source_id == 2){ // BUKALAPAK
                                $dataExcel     = $this->MME->data_bukalapak_new_orders($paramExcel);
                            } else if($orders_source_id == 8){ // SHOPEE
                                $dataExcel     = $this->MME->data_shopee_new_orders($paramExcel);
                            } else if($orders_source_id == 11){ // LAZADA
                                $dataExcel     = $this->MME->data_lazada_new_orders($paramExcel);
                            }
                            $data['err']   = $dataExcel['err'];
                            $data['msg']   = $dataExcel['msg'];
                            $data['items'] = $dataExcel['items'];
                        }

                        if(count($data['items']) > 0){
                            foreach ($data['items'] as $key => $val) {
                                $m = $this->db->get_where("mt_print_address",array(
                                    "store_id"              => $this->store_id,
                                    "orders_source_id"      => $val['orders_source_id'],
                                    "orders_source_invoice" => strtoupper($val['orders_source_invoice'])
                                ),1,0)->row();
                                if(count($m) == 0){
                                    $data2 = array(
                                        'store_id'                      => $this->store_id,
                                        'orders_source_id'              => $val['orders_source_id'],
                                        'orders_source_invoice'         => strtoupper($val['orders_source_invoice']),
                                        'orders_shipping_dropship'      => $val['orders_dropship'],
                                        'orders_ship_name'              => ucwords($val['orders_ship_name']),
                                        'orders_ship_phone'             => $val['orders_ship_phone'],
                                        'orders_shipping_name'          => strtoupper($val['orders_shipping_name']),
                                        'orders_shipping_phone'         => $val['orders_shipping_phone'],
                                        'orders_shipping_address'       => ucwords($val['orders_shipping_address']),
                                        'orders_courier_id'             => $val['orders_courier_id'],
                                        'orders_product_category_title' => $val['orders_product_category_title'],
                                        'orders_price_shipping'         => $val['orders_price_shipping'],
                                        'orders_price_product'          => $val['orders_price_product'],
                                        'orders_price_insurance'        => $val['orders_price_insurance'],
                                        'orders_shipping_weight'        => 1,
                                        'orders_shipping_resi'          => strtoupper($val['orders_resi']),
                                        'orders_noted'                  => $val['orders_noted'],
                                        'print_address_status'          => 0,
                                        'print_address_istrash'         => 0,
                                        'print_address_date'            => timestamp()
                                    );

                                    $this->DATA->table="mt_print_address";
                                    $a2 = $this->_save_master(
                                        $data2,
                                        array(
                                            'print_address_id' => ''
                                            ),
                                        ''
                                        );

                                    $count += 1;

                                    $id = $a2['id'];
                                }
                            }

                            $data['err']  = false;
                            $data['msg'] .= 'Upload success.. <br/>Total: '.$count;
                        }
                    }
                } else {
                    $data['err']  = true;
                    $data['msg'] .= 'Error: ' . $files['error'] . '<br>';
                }
            }

            $upload_files = glob('./assets/collections/tmp_files/*');
            foreach($upload_files as $file){
                if(is_file($file))
                unlink($file);
            }

            redirect($this->own_link."?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));

        }
    }

}
