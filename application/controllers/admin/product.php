<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");

class product extends AdminController {
	function __construct()
	{
		parent::__construct();
		$this->_set_action();
		$this->_set_action(array("view","edit","delete","detail"),"ITEM");
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
            array('name'    => 'small','width'  => 350, 'quality'   => '100%'),
			array('name'	=> 'large','width'	=> 800, 'quality'	=> '100%')
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

        $data['total_data'] = $this->data_table['total'];
		$data['url'] = base_url()."admin/product/index";

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function add(){
		$this->breadcrumb[] = array(
			"title"		=> "Add"
		);
		$this->_v($this->folder_view.$this->prefix_view."_form");
	}

	function view($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "View"
		);
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				"product_id"	=> $id,
				"store_id"		=> $this->store_id
			));
			if(empty($this->data_form->product_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$data['description'] = $this->db->get_where("mt_product_description",array(
				'product_id'	=> $id
			),1,0)->row();

			$data['detail'] = $this->db->get_where("mt_product_detail",array(
				'product_id'	=> $id
			),1,0)->row();

			$data['image_product'] = $this->db->order_by('position','asc')->get_where("mt_product_image",array(
				'product_id'	=> $id
			))->result();
			$this->_v($this->folder_view.$this->prefix_view."_view",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function edit($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "Edit"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				"product_id"	=> $id,
				"store_id"		=> $this->store_id
			));
			if(empty($this->data_form->product_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$data['description'] = $this->db->get_where("mt_product_description",array(
				'product_id'	=> $id
			),1,0)->row();

			$data['detail'] = $this->db->get_where("mt_product_detail",array(
				'product_id'	=> $id
			),1,0)->row();

			$data['image_product'] = $this->db->order_by('position','asc')->get_where("mt_product_image",array(
				'product_id'	=> $id
			))->result();
			$this->_v($this->folder_view.$this->prefix_view."_form",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function duplicate($id=''){
		$this->breadcrumb[] = array(
			"title"		=> "Duplicate"
		);

		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id)!=''){
			$this->data_form = $this->DATA->data_id(array(
				"product_id"	=> $id,
				"store_id"		=> $this->store_id
			));
			if(empty($this->data_form->product_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$data['mp'] = '';

			$data['description'] = $this->db->get_where("mt_product_description",array(
				'product_id'	=> $id
			),1,0)->row();

			$data['detail'] = $this->db->get_where("mt_product_detail",array(
				'product_id'	=> $id
			),1,0)->row();

			$this->data_form->product_id 				 = "";
			// $this->data_form->product_code 			     = "";
			$data['detail']->product_detail_id 			 = "";
			$data['description']->product_description_id = "";
			$data['image_product'] 						 = "";
			$this->_v($this->folder_view.$this->prefix_view."_form",$data);
		}else{
			redirect($this->own_link);
		}
	}

	function setsale($id=''){
        $exp = explode("-", $id);
        $id  = dbClean(trim($exp[0]));
        if(trim($id) != ''){
            $this->data_form = $this->DATA->data_id(array(
                "product_id"    => $id,
                "store_id"      => $this->store_id
            ));
            if(empty($this->data_form->product_id)){
                redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
            }

            $data = array(
                'product_date_update'           => timestamp()
            );
            $this->db->update("mt_product",$data,array("product_id"=>$id));

            $dataDetail = $this->db->get_where("mt_product_detail",array(
                'product_id'    => $id
            ),1,0)->row();

            $data_detail = array(
                'product_status_id'         => 1,
                'product_stock_detail'      => NULL
            );

            $log_item = "";
            $total_stock = 0;
            if($dataDetail->product_stock_detail != ''){
                $arr_stock = array();
                $arr_item  = array();
                $product_stock_detail = json_decode($dataDetail->product_stock_detail);
                foreach ($product_stock_detail as $key3 => $value3) {
                    $total_stock = ($total_stock + $value3->qty);
                    $arr_stock[] = array('id'       => $value3->id,
                                         'name'     => $value3->name,
                                         'color'    => $value3->color,
                                         'qty'      => $value3->qty,
                                         'status'   => ($value3->qty>0?1:2)
                                      );

                    $arr_item[] = array('id'       => $value3->id,
                                        'name'     => $value3->name,
                                        'qty'      => $value3->qty,
                                        'status'   => ($value3->qty>0?1:2)
                                    );
                }
                $data_detail['product_stock_detail'] = json_encode($arr_stock);
                $log_item  = json_encode($arr_item);
            }
            if($dataDetail->product_stock_first == 0){
                $data_detail['product_stock_first'] = $total_stock;
            }

            $this->db->update("mt_product_detail",$data_detail,array("product_detail_id"=>$dataDetail->product_detail_id));
            set_last_date_product_setup();
            writeLog(array(
                'log_user_type'     => "1", // Admin
                'log_user_id'       => $this->jCfg['user']['id'],
                'log_role'          => $this->jCfg['user']['level'],
                'log_type'          => "2", // Produk
                'log_detail_id'     => $id,
                'log_detail_item'   => $log_item,
                'log_detail_qty'    => $total_stock,
                'log_title_id'      => "6", // Produk Dijual Kembali
                'log_desc'          => "",
                'log_status' 		=> "0"
            ));
        }

		if($this->input->get("next")!=""){
	        redirect($this->input->get("next")."?msg=".urlencode('Save data success')."&type_msg=success");
        } else {
	        redirect($this->own_link."?msg=".urlencode('Save data success')."&type_msg=success");
        }
    }

	function setnotsale($id=''){
		$exp = explode("-", $id);
		$id  = dbClean(trim($exp[0]));
		if(trim($id) != ''){
			$this->data_form = $this->DATA->data_id(array(
				"product_id"	=> $id,
				"store_id"		=> $this->store_id
			));
			if(empty($this->data_form->product_id)){
				redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
			}

			$dataDetail = $this->db->get_where("mt_product_detail",array(
				'product_id'	=> $id
			),1,0)->row();
			$log_qty  = $dataDetail->product_stock;
            $log_item = "";

			$data = array(
				'product_status_id'			=> 3,
				'product_stock_detail'		=> NULL
			);
			if($dataDetail->product_stock_detail != ''){
				$arr_stock = array();
                $arr_item  = array();
				$product_stock_detail = json_decode($dataDetail->product_stock_detail);
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

			$this->db->update("mt_product_detail",$data,array("product_detail_id"=>$dataDetail->product_detail_id));
            set_last_date_product_setup();
			writeLog(array(
                'log_user_type'     => "1", // Admin
                'log_user_id'       => $this->jCfg['user']['id'],
                'log_role'          => $this->jCfg['user']['level'],
                'log_type'          => "2", // Produk
                'log_detail_id'     => $id,
                'log_detail_item'   => $log_item,
                'log_detail_qty'    => $log_qty,
                'log_title_id'      => "7", // Produk diset Tidak Dijual
                'log_desc'          => "",
                'log_status' 		=> "0"
            ));

            if($this->input->get("sendnotif")=="1"){
                sendProductNotif(array(
	                'user_id'       => $this->user_id,
	                'store_id'      => $this->store_id,
	                'product_id'    => $id,
	                'product_item'  => $log_item,
	                'product_qty'   => $log_qty,
	                'notif_title'   => "Produk diset Tidak Dijual",
	                'notif_desc'    => "",
	                'notif_status'  => 1,
	                'notif_notify'  => 3
                ));
            }

		}
		if($this->input->get("next")!=""){
	        redirect($this->input->get("next")."?msg=".urlencode('Save data success')."&type_msg=success");
        } else {
	        redirect($this->own_link."?msg=".urlencode('Save data success')."&type_msg=success");
        }
	}

    function move_to_archive(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
            $checked_files = $_POST['checked_files'];
            foreach ($checked_files as $k => $v) {
                $product_id = $v;
                $r = $this->db->get_where("mt_product",array(
                    "product_id"    => $product_id
                ),1,0)->row();
                if(count($r) > 0){
                    // SAVE PRODUCT ARCHIVE
                    $product = array();
                    foreach ($r as $k => $v) { $product[$k] = $v; }
                    $product['product_date_archive'] = timestamp();
                    $this->DATA->table="mt_product_archive";
                    $a = $this->_save_master($product, array('product_id' => $product_id),'');
                    $this->db->delete("mt_product",array('product_id' => $product_id));

                    // SAVE PRODUCT DETAIL ARCHIVE
                    $r2 = $this->db->get_where("mt_product_detail",array(
                        "product_id "  => $product_id
                    ),1,0)->result();
                    if(count($r2) > 0){
                        foreach ($r2 as $key2 => $val2) {
                            $product_detail_id = $val2->product_detail_id;
                            $product_detail = array();
                            foreach ($val2 as $k2 => $v2) { $product_detail[$k2] = $v2; }
                            $product_detail['product_detail_id'] = NULL;
                            $this->DATA->table="mt_product_archive_detail";
                            $a2 = $this->_save_master($product_detail, array('product_detail_id' => $product_detail_id),'');
                            $this->db->delete("mt_product_detail",array('product_detail_id' => $product_detail_id));
                        }
                    }

                    // SAVE PRODUCT IMAGE ARCHIVE
                    $r3 = $this->db->get_where("mt_product_image",array(
                        "product_id "  => $product_id
                    ))->result();
                    if(count($r3) > 0){
                        foreach ($r3 as $key3 => $val3) {
                            $image_id = $val3->image_id;
                            $product_images = array();
                            foreach ($val3 as $k3 => $v3) { $product_images[$k3] = $v3; }
                            $product_images['image_id'] = NULL;
                            $this->DATA->table="mt_product_archive_image";
                            $a3 = $this->_save_master($product_images, array('image_id' => $image_id),'');
                            $this->db->delete("mt_product_image",array('image_id' => $image_id));
                        }
                    }

                    // SAVE PRODUCT DESCRIPTION ARCHIVE
                    $r4 = $this->db->get_where("mt_product_description",array(
                        "product_id "  => $product_id
                    ),1,0)->result();
                    if(count($r4) > 0){
                        foreach ($r4 as $key4 => $val4) {
                            $product_description_id = $val4->product_description_id;
                            $product_description = array();
                            foreach ($val4 as $k4 => $v4) { $product_description[$k4] = $v4; }
                            $product_description['product_description_id'] = NULL;
                            $this->DATA->table="mt_product_archive_description";
                            $a4 = $this->_save_master($product_description, array('product_description_id' => $product_description_id),'');
                            $this->db->delete("mt_product_description",array('product_description_id' => $product_description_id));
                        }
                    }

                    $notif = $this->db->get_where("mt_product_notif",array(
                        "product_id" => $product_id
                    ))->result();
                    if(count($notif) > 0){
                        foreach ($notif as $key => $value) {
                            $this->DATA->table = "mt_product_notif";
                            $this->DATA->_delete(array("notif_id"   => $value->notif_id),true);
                        }
                    }

                    $mp = $this->db->get_where("mt_product_mp",array(
                        "product_id" => $product_id
                    ))->result();
                    if(count($mp) > 0){
                        foreach ($mp as $key => $value) {
                            $this->DATA->table = "mt_product_mp";
                            $this->DATA->_delete(array("product_mp_id"   => $value->product_mp_id),true);
                        }
                    }

                }

                // set_last_date_product_setup();
                writeLog(array(
                    'log_user_type'     => "1", // Admin
                    'log_user_id'       => $this->jCfg['user']['id'],
                    'log_role'          => $this->jCfg['user']['level'],
                    'log_type'          => "2", // Produk
                    'log_detail_id'     => $id,
                    'log_detail_item'   => $log_item,
                    'log_detail_qty'    => $log_qty,
                    'log_title_id'      => "16", // Produk Diarsipkan
                    'log_desc'          => "",
                    'log_status'        => "0"
                ));
            }

            $data['err']    = false;
            $data['msg']    = 'Berhasil diarsipkan...';
        }
        if($this->input->get("next")!=""){
            redirect($this->input->get("next")."?msg=".urlencode('Archive data success')."&type_msg=success");
        } else {
            redirect($this->own_link."?msg=".urlencode('Archive data success')."&type_msg=success");
        }
    }

    function delete($id=''){
        $id = explode("-", $id);
        $id = dbClean(trim($id[0]));
        if(trim($id) != ''){

            $this->db->update("mt_product",array("product_code"=>NULL,"product_show_id"=>0,"product_istrash"=>1,"product_date_istrash"=>timestamp()),array("product_id" => $id, "store_id" => $this->store_id));

            $dataDetail = $this->db->get_where("mt_product_image",array(
				"product_id"	=> $id
			))->result();
			$i = 1;
			foreach($dataDetail as $rD){
				$idD = $rD->image_id;
				$this->DATA->table = 'mt_product_image';
				$this->_delte_old_files_without_thumb(
					array(
						'field' => 'image_filename',
						'par'	=> array('image_id' => $idD)
				));
				// $this->db->delete("mt_product_image",array('image_id' => $idD));
				$i += 1;
			}

            $dataDetail = $this->db->get_where("mt_product_detail",array(
                'product_id'    => $id
            ),1,0)->row();
            $log_qty  = $dataDetail->product_stock;
            $log_item = "";
            if($dataDetail->product_stock_detail != ''){
                $arr_item  = array();
                $product_stock_detail = json_decode($dataDetail->product_stock_detail);
                foreach ($product_stock_detail as $key3 => $value3) {
                    $arr_item[] = array('id'       => $value3->id,
                                        'name'     => $value3->name,
                                        'qty'      => $value3->qty,
                                        'status'   => $value3->status
                                    );
                }
                $log_item = json_encode($arr_item);
            }

            set_last_date_product_setup();
            writeLog(array(
                'log_user_type'     => "1", // Admin
                'log_user_id'       => $this->jCfg['user']['id'],
                'log_role'          => $this->jCfg['user']['level'],
                'log_type'          => "2", // Produk
                'log_detail_id'     => $id,
                'log_detail_item'   => $log_item,
                'log_detail_qty'    => $log_qty,
                'log_title_id'      => "9", // Produk Dihapus
                'log_desc'          => "",
                'log_status' 		=> "0"
            ));
        }

        if($this->input->get("next")!=""){
	        redirect($this->input->get("next")."?msg=".urlencode('Delete data success')."&type_msg=success");
        } else {
	        redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
        }
    }

    function delete_multi(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
            $checked_files = $_POST['checked_files'];
            foreach ($checked_files as $k => $v) {
                $id = $v;
                $this->db->update("mt_product",array("product_code"=>NULL,"product_show_id"=>0,"product_istrash"=>1,"product_date_istrash"=>timestamp()),array("product_id" => $id, "store_id" => $this->store_id));

                $dataDetail = $this->db->get_where("mt_product_image",array(
                    "product_id"    => $id
                ))->result();
                $i = 1;
                foreach($dataDetail as $rD){
                    $idD = $rD->image_id;
                    $this->DATA->table = 'mt_product_image';
                    $this->_delte_old_files_without_thumb(
                        array(
                            'field' => 'image_filename',
                            'par'   => array('image_id' => $idD)
                    ));
                    // $this->db->delete("mt_product_image",array('image_id' => $idD));
                    $i += 1;
                }

                $dataDetail = $this->db->get_where("mt_product_detail",array(
                    'product_id'    => $id
                ),1,0)->row();
                $log_qty  = $dataDetail->product_stock;
                $log_item = "";
                if($dataDetail->product_stock_detail != ''){
                    $arr_item  = array();
                    $product_stock_detail = json_decode($dataDetail->product_stock_detail);
                    foreach ($product_stock_detail as $key3 => $value3) {
                        $arr_item[] = array('id'       => $value3->id,
                                            'name'     => $value3->name,
                                            'qty'      => $value3->qty,
                                            'status'   => $value3->status
                                        );
                    }
                    $log_item = json_encode($arr_item);
                }

                set_last_date_product_setup();
                writeLog(array(
                    'log_user_type'     => "1", // Admin
                    'log_user_id'       => $this->jCfg['user']['id'],
                    'log_role'          => $this->jCfg['user']['level'],
                    'log_type'          => "2", // Produk
                    'log_detail_id'     => $id,
                    'log_detail_item'   => $log_item,
                    'log_detail_qty'    => $log_qty,
                    'log_title_id'      => "9", // Produk Dihapus
                    'log_desc'          => "",
                    'log_status'        => "0"
                ));
            }

            $data['err']    = false;
            $data['msg']    = 'Berhasil simpan data...';
        }
        if($this->input->get("next")!=""){
            redirect($this->input->get("next")."?msg=".urlencode('Delete data success')."&type_msg=success");
        } else {
            redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
        }
    }

    function delete_permanent($id=''){
        $id = explode("-", $id);
        $id = dbClean(trim($id[0]));
        if(trim($id) != ''){
			$this->DATA->table = "mt_product";
			$this->DATA->_delete(array("product_id"	=> idClean($id)),true);

			$description = $this->db->get_where("mt_product_description",array(
				"product_id" => $id
			),1,0)->row();
			if(count($description) > 0){
				$this->DATA->table = "mt_product_description";
				$this->DATA->_delete(array("product_description_id"	=> $description->product_description_id),true);
			}

			$detail = $this->db->get_where("mt_product_detail",array(
				"product_id" => $id
			),1,0)->row();
            $log_qty  = "";
            $log_item = "";
			if(count($detail) > 0){
                $log_qty  = $detail->product_stock;
                if($detail->product_stock_detail != ''){
                    $arr_item  = array();
                    $product_stock_detail = json_decode($detail->product_stock_detail);
                    foreach ($product_stock_detail as $key3 => $value3) {
                        $arr_item[] = array('id'       => $value3->id,
                                            'name'     => $value3->name,
                                            'qty'      => $value3->qty,
                                            'color'    => $value3->color,
                                            'status'   => $value3->status
                                        );
                    }
                    $log_item = json_encode($arr_item);
                }
				$this->DATA->table = "mt_product_detail";
				$this->DATA->_delete(array("product_detail_id"	=> $detail->product_detail_id),true);
			}

			$image = $this->db->get_where("mt_product_image",array(
				"product_id" => $id
			))->result();
			if(count($image) > 0){
				foreach ($image as $key => $value) {
					$this->DATA->table = "mt_product_image";
					$this->DATA->_delete(array("image_id"	=> $value->image_id),true);
				}
			}

			$notif = $this->db->get_where("mt_product_notif",array(
				"product_id" => $id
			))->result();
			if(count($notif) > 0){
				foreach ($notif as $key => $value) {
					$this->DATA->table = "mt_product_notif";
					$this->DATA->_delete(array("notif_id"	=> $value->notif_id),true);
				}
			}

            $mp = $this->db->get_where("mt_product_mp",array(
                "product_id" => $id
            ))->result();
            if(count($mp) > 0){
                foreach ($mp as $key => $value) {
                    $this->DATA->table = "mt_product_mp";
                    $this->DATA->_delete(array("product_mp_id"   => $value->product_mp_id),true);
                }
            }

			$reseller_prices = $this->db->get_where("mt_reseller_prices",array(
				"product_id" => $id
			))->result();
			if(count($reseller_prices) > 0){
				foreach ($reseller_prices as $key => $value) {
					$this->DATA->table = "mt_reseller_prices";
					$this->DATA->_delete(array("reseller_prices_id"	=> $value->reseller_prices_id),true);
				}
			}

			$orders_detail = $this->db->get_where("mt_orders_detail",array(
				"product_id" => $id
			))->result();
			foreach ($orders_detail as $key => $val) {
				$this->db->update("mt_orders_detail",array("product_id"=>0),array("orders_detail_id"=>$val->orders_detail_id));
			}

			$store_orders_detail = $this->db->get_where("mt_store_orders_detail",array(
				"product_id" => $id
			))->result();
			foreach ($store_orders_detail as $key => $val) {
				$this->db->update("mt_store_orders_detail",array("product_id"=>0),array("store_orders_detail_id"=>$val->store_orders_detail_id));
			}

            set_last_date_product_setup();
            writeLog(array(
                'log_user_type'     => "1", // Admin
                'log_user_id'       => $this->jCfg['user']['id'],
                'log_role'          => $this->jCfg['user']['level'],
                'log_type'          => "2", // Produk
                'log_detail_id'     => $id,
                'log_detail_item'   => $log_item,
                'log_detail_qty'    => $log_qty,
                'log_title_id'      => "12", // Produk Dihapus Permanen
                'log_desc'          => "",
                'log_status' 		=> "0"
            ));
        }

        if($this->input->get("next")!=""){
	        redirect($this->input->get("next")."?msg=".urlencode('Delete data success')."&type_msg=success");
        } else {
	        redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
        }
    }

    function delete_permanent_multi(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
            $checked_files = $_POST['checked_files'];
            foreach ($checked_files as $k => $v) {
                $id = $v;
                $this->DATA->table = "mt_product";
                $this->DATA->_delete(array("product_id" => idClean($id)),true);

                $description = $this->db->get_where("mt_product_description",array(
                    "product_id" => $id
                ),1,0)->row();
                if(count($description) > 0){
                    $this->DATA->table = "mt_product_description";
                    $this->DATA->_delete(array("product_description_id" => $description->product_description_id),true);
                }

                $detail = $this->db->get_where("mt_product_detail",array(
                    "product_id" => $id
                ),1,0)->row();
                $log_qty  = "";
                $log_item = "";
                if(count($detail) > 0){
                    $log_qty  = $detail->product_stock;
                    if($detail->product_stock_detail != ''){
                        $arr_item  = array();
                        $product_stock_detail = json_decode($detail->product_stock_detail);
                        foreach ($product_stock_detail as $key3 => $value3) {
                            $arr_item[] = array('id'       => $value3->id,
                                                'name'     => $value3->name,
                                                'qty'      => $value3->qty,
                                                'color'    => $value3->color,
                                                'status'   => $value3->status
                                            );
                        }
                        $log_item = json_encode($arr_item);
                    }
                    $this->DATA->table = "mt_product_detail";
                    $this->DATA->_delete(array("product_detail_id"  => $detail->product_detail_id),true);
                }

                $image = $this->db->get_where("mt_product_image",array(
                    "product_id" => $id
                ))->result();
                if(count($image) > 0){
                    foreach ($image as $key => $value) {
                        $this->DATA->table = "mt_product_image";
                        $this->DATA->_delete(array("image_id"   => $value->image_id),true);
                    }
                }

                $notif = $this->db->get_where("mt_product_notif",array(
                    "product_id" => $id
                ))->result();
                if(count($notif) > 0){
                    foreach ($notif as $key => $value) {
                        $this->DATA->table = "mt_product_notif";
                        $this->DATA->_delete(array("notif_id"   => $value->notif_id),true);
                    }
                }

                $mp = $this->db->get_where("mt_product_mp",array(
                    "product_id" => $id
                ))->result();
                if(count($mp) > 0){
                    foreach ($mp as $key => $value) {
                        $this->DATA->table = "mt_product_mp";
                        $this->DATA->_delete(array("product_mp_id"   => $value->product_mp_id),true);
                    }
                }

                $reseller_prices = $this->db->get_where("mt_reseller_prices",array(
                    "product_id" => $id
                ))->result();
                if(count($reseller_prices) > 0){
                    foreach ($reseller_prices as $key => $value) {
                        $this->DATA->table = "mt_reseller_prices";
                        $this->DATA->_delete(array("reseller_prices_id" => $value->reseller_prices_id),true);
                    }
                }

                $orders_detail = $this->db->get_where("mt_orders_detail",array(
                    "product_id" => $id
                ))->result();
                foreach ($orders_detail as $key => $val) {
                    $this->db->update("mt_orders_detail",array("product_id"=>0),array("orders_detail_id"=>$val->orders_detail_id));
                }

                $store_orders_detail = $this->db->get_where("mt_store_orders_detail",array(
                    "product_id" => $id
                ))->result();
                foreach ($store_orders_detail as $key => $val) {
                    $this->db->update("mt_store_orders_detail",array("product_id"=>0),array("store_orders_detail_id"=>$val->store_orders_detail_id));
                }

                set_last_date_product_setup();
                writeLog(array(
                    'log_user_type'     => "1", // Admin
                    'log_user_id'       => $this->jCfg['user']['id'],
                    'log_role'          => $this->jCfg['user']['level'],
                    'log_type'          => "2", // Produk
                    'log_detail_id'     => $id,
                    'log_detail_item'   => $log_item,
                    'log_detail_qty'    => $log_qty,
                    'log_title_id'      => "12", // Produk Dihapus Permanen
                    'log_desc'          => "",
                    'log_status'        => "0"
                ));
            }

            $data['err']    = false;
            $data['msg']    = 'Berhasil simpan data...';
        }
        if($this->input->get("next")!=""){
            redirect($this->input->get("next")."?msg=".urlencode('Delete data success')."&type_msg=success");
        } else {
            redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
        }
    }

	function empty_trash(){
		$data = $this->db->get_where("mt_product",array(
			"product_istrash"	=> 1,
			"store_id"			=> $this->store_id
		))->result();
		foreach($data as $r){
			$id = $r->product_id;
			$this->DATA->table = "mt_product";
			$this->DATA->_delete(array("product_id"	=> idClean($id)),true);

			$description = $this->db->get_where("mt_product_description",array(
				"product_id" => $id
			),1,0)->row();
			if(count($description) > 0){
				$this->DATA->table = "mt_product_description";
				$this->DATA->_delete(array("product_description_id"	=> $description->product_description_id),true);
			}

			$detail = $this->db->get_where("mt_product_detail",array(
				"product_id" => $id
			),1,0)->row();
            $log_qty  = "";
            $log_item = "";
			if(count($detail) > 0){
                $log_qty  = $detail->product_stock;
                if($detail->product_stock_detail != ''){
                    $arr_item  = array();
                    $product_stock_detail = json_decode($detail->product_stock_detail);
                    foreach ($product_stock_detail as $key3 => $value3) {
                        $arr_item[] = array('id'       => $value3->id,
                                            'name'     => $value3->name,
                                            'qty'      => $value3->qty,
                                            'color'    => $value3->color,
                                            'status'   => $value3->status
                                        );
                    }
                    $log_item = json_encode($arr_item);
                }
				$this->DATA->table = "mt_product_detail";
				$this->DATA->_delete(array("product_detail_id"	=> $detail->product_detail_id),true);
			}

			$image = $this->db->get_where("mt_product_image",array(
				"product_id" => $id
			))->result();
			if(count($image) > 0){
				foreach ($image as $key => $value) {
					$this->DATA->table = "mt_product_image";
					$this->DATA->_delete(array("image_id"	=> $value->image_id),true);
				}
			}

			$notif = $this->db->get_where("mt_product_notif",array(
				"product_id" => $id
			))->result();
			if(count($notif) > 0){
				foreach ($notif as $key => $value) {
					$this->DATA->table = "mt_product_notif";
					$this->DATA->_delete(array("notif_id"	=> $value->notif_id),true);
				}
			}

			$reseller_prices = $this->db->get_where("mt_reseller_prices",array(
				"product_id" => $id
			))->result();
			if(count($reseller_prices) > 0){
				foreach ($reseller_prices as $key => $value) {
					$this->DATA->table = "mt_reseller_prices";
					$this->DATA->_delete(array("reseller_prices_id"	=> $value->reseller_prices_id),true);
				}
			}

			$orders_detail = $this->db->get_where("mt_orders_detail",array(
				"product_id" => $id
			))->result();
			foreach ($orders_detail as $key => $val) {
				$this->db->update("mt_orders_detail",array("product_id"=>0),array("orders_detail_id"=>$val->orders_detail_id));
			}

			$store_orders_detail = $this->db->get_where("mt_store_orders_detail",array(
				"product_id" => $id
			))->result();
			foreach ($store_orders_detail as $key => $val) {
				$this->db->update("mt_store_orders_detail",array("product_id"=>0),array("store_orders_detail_id"=>$val->store_orders_detail_id));
			}

            set_last_date_product_setup();
            writeLog(array(
                'log_user_type'     => "1", // Admin
                'log_user_id'       => $this->jCfg['user']['id'],
                'log_role'          => $this->jCfg['user']['level'],
                'log_type'          => "2", // Produk
                'log_detail_id'     => $id,
                'log_detail_item'   => $log_item,
                'log_detail_qty'    => $log_qty,
                'log_title_id'      => "12", // Produk Dihapus Permanen
                'log_desc'          => "",
                'log_status' 		=> "0"
            ));
		}
		if($this->input->get("next")!=""){
			redirect($this->input->get("next")."?msg=".urlencode('Empty trash data success')."&type_msg=success");
        } else {
			redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
        }
	}

    function set_hide_all_product(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        if(isset($_POST['checked_files']) && $_POST['checked_files'] != ''){
            $checked_files = $_POST['checked_files'];
            foreach ($checked_files as $k => $v) {
                $id = $v;
                $products = $this->db->get_where("mt_product",array(
                    "product_id" => $id
                ),1,0)->row();
                $this->db->update("mt_product",array("product_show_id"=>0),array("product_id"=>$key));
                set_last_date_product_setup();

            }
            if($this->input->get("next")!=""){
                redirect($this->input->get("next")."?msg=".urlencode('Berhasil set not publish')."&type_msg=success");
            } else {
                redirect($this->own_link."?msg=".urlencode('Berhasil set not publish')."&type_msg=success");
            }
        }
    }

	function push($id=''){
		$id = explode("-", $id);
		$id = dbClean(trim($id[0]));
		if(trim($id) != ''){
			$this->db->update("mt_product",array("product_date_push"=>timestamp()),array("product_id"=>$id));
            set_last_date_product_setup();
		}

        if($this->input->get("next")!=""){
            redirect($this->input->get("next")."?msg=".urlencode('Push product success')."&type_msg=success");
        } else {
            redirect($this->own_link."?msg=".urlencode('Push product success')."&type_msg=success");
        }
	}
    function ajax_push(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
            $id = dbClean(trim($_POST['thisVal']));
            if(trim($id)!=''){
                $this->db->update("mt_product",array("product_date_push"=>timestamp()),array("product_id"=>$id));
                $data['err']         = false;
                $data['msg']         = "Sukses menaikan produk..";
                set_last_date_product_setup();
            }
        }

        die(json_encode($data));
        exit();
    }

	function save(){
		// if (dbClean($_POST['product_id']) == ""){
		// 	$dataPos = $this->db->order_by('position','asc')->get_where("mt_product",array(
		// 		"product_istrash !=" => 1
		// 	))->result();
		// 	$position = 1;
		// 	foreach($dataPos as $r){
		// 		$id = $r->product_id;
		// 		$this->db->update("mt_product",array("position"=>$position),array("product_id"=>$id));
		// 		$position +=1;
		// 	}
		// }
        $isCreate = true;
        if(dbClean($_POST['product_id']) != ""){ $isCreate = false; }

		$data = array(
			'product_name'				=> dbClean(ucwords($_POST['product_name'])),
            'product_name_simple'       => dbClean(ucwords($_POST['product_name_simple'])),
			'product_type_motif'		=> dbClean(ucwords($_POST['product_type_motif'])),
			'product_category_id'		=> dbClean($_POST['product_category_id']),
            'product_group_id'          => dbClean($_POST['product_group_id']),
			'product_brand_id'			=> dbClean($_POST['product_brand_id']),
			'supplier_id'				=> dbClean($_POST['supplier_id']),
			'product_show_id'			=> dbClean($_POST['product_show_id']),
			'product_approved'			=> 1,
			'product_date_update'		=> timestamp(),
			'product_istrash'			=> 0
		);

        if($_POST['product_group_id'] == "addnew"){
            $dataGroup = array(
                'store_id'                  => $this->store_id,
                'product_group_name'        => dbClean(ucwords($_POST['product_group_name'])),
                'product_group_date'        => timestamp(),
                'product_group_show'        => 1,
                'product_group_istrash'     => 0
            );

            $this->DATA->table = "mt_product_group";
            $saveGroup = $this->_save_master(
                $dataGroup,
                array(
                    'product_group_id' => ''
                ),
                ''
            );
            $data['product_group_id'] = $saveGroup['id'];
        }

        if($_POST['product_brand_id'] == "addnew"){
            $dataBrand = array(
                'product_brand_name'    => dbClean(ucwords($_POST['product_brand_name'])),
                'product_brand_status'  => 1,
                'product_brand_date'    => timestamp(),
                'product_brand_istrash' => 0,
                'position'              => 0
            );
            $title = dbClean($_POST['product_brand_name']);
            $dataBrand['url'] = generateUniqueURL($title,"mt_product_brand");

            $this->DATA->table = "mt_product_brand";
            $saveBrand = $this->_save_master(
                $dataBrand,
                array(
                    'product_brand_id' => ''
                ),
                ''
            );
            $data['product_brand_id'] = $saveBrand['id'];
        }

        if($_POST['supplier_id'] == "addnew"){
            $dataSupplier = array(
                'supplier_name'         => dbClean(ucwords($_POST['supplier_name'])),
                'supplier_address'      => NULL,
                'supplier_city_id'      => 0,
                'supplier_province_id'  => 0,
                'date_created'          => timestamp(),
                'supplier_istrash'      => 0
            );

            $this->DATA->table = "mt_supplier";
            $saveSupplier = $this->_save_master(
                $dataSupplier,
                array(
                    'supplier_id' => ''
                ),
                ''
            );
            $data['supplier_id'] = $saveSupplier['id'];
        }

		// $data['product_awards_id'] = "";
		// if($_POST['product_awards_id'] != ""){
		// 	$i = 0; $arr = '';
		// 	foreach ($_POST['product_awards_id'] as $key){ $arr .= ($i==0?'':',').$key; $i += 1; }
		// 	$data['product_awards_id'] = $arr;
		// }

		$data['product_tags'] = "";
		if($_POST['product_tags'] != ""){
			$i = 0; $arr = '';
			foreach ($_POST['product_tags'] as $key){ $arr .= ($i==0?'':',').$key; $i += 1; }
			$data['product_tags'] = $arr;
		}

		$id = "";
		if(dbClean($_POST['product_name']) != ""){
            if($isCreate){
                $code1 = dbClean($_POST['product_code'][0]);
                $code2 = dbClean($_POST['product_code'][1]);
                $code3 = dbClean($_POST['product_code'][2]);
                $fullcode = strtoupper($code1.$code2.$code3);
                $data['product_code']      = $fullcode;
                $v = $this->db->get_where('mt_product',array(
                    'product_code' => $fullcode
                ),1,0)->row();
                if(count($v)>0){
                    $thisVal = strtoupper($code1."".$code2);
                    $create_product_code       = create_product_code($thisVal);
                    $data['product_code']      = $create_product_code['fullcode'];
                }

				$data['store_id']		   = $this->store_id;
				$data['product_user_id']   = $this->user_id;
				$data['product_date'] 	   = timestamp();
				$data['product_date_push'] = timestamp();

				$title = dbClean($_POST['product_name']);
				if($title==''){ $title = 'product'; }
				$data['url'] = generateUniqueURL($title,"mt_product");
			} else {
                if(dbClean($_POST['product_code']) != ""){
                    $v = $this->db->get_where('mt_product',array(
                        'product_id' => dbClean($_POST['product_id'])
                    ),1,0)->row();
                    if(count($v) > 0){
                        if($v->product_code != dbClean($_POST['product_code'])){
                            $fullcode = dbClean($_POST['product_code']);
                            $data['product_code'] = $fullcode;

                            $v2 = $this->db->get_where('mt_product',array(
                                'product_code' => $fullcode
                            ),1,0)->row();
                            if(count($v2)>0){
                                $data['product_code'] = "DOB".$fullcode;
                            }
                        }
                    }
                }
            }

            $this->DATA->table = "mt_product";
			$a = $this->_save_master(
				$data,
				array(
					'product_id' => dbClean($_POST['product_id'])
				),
				dbClean($_POST['product_id'])
			);

			$id = $a['id'];
		}

		// if(dbClean($_POST['remove_images']) == 1){
		// 	$this->_delte_old_files_without_thumb(
		// 	array(
		// 		'field' => 'product_image',
		// 		'par'	=> array('product_id' => $id)
		// 	));

		// 	$this->db->update("mt_product",array("product_image"=>NULL),array("product_id"=>$id));
		// } else {
		// 	$this->_uploaded(
		// 	array(
		// 		'id'		=> $id ,
		// 		'input'		=> 'product_image',
		// 		'param'		=> array(
		// 						'field' => 'product_image',
		// 						'par'	=> array('product_id' => $id)
		// 					)
		// 	));
		// }

		if($id != "") {

			// SAVE DETAIL
			$data_detail = array(
				'product_id'				=> idClean($id),
				'product_price_buy'			=> dbClean(convertRpToInt($_POST['product_price_buy'])),
				'product_price_sale'		=> dbClean(convertRpToInt($_POST['product_price_sale'])),
				'product_stock_first'		=> dbClean($_POST['product_stock_first']),
                'product_stock_copy'        => 0,
				'product_min_order'			=> dbClean($_POST['product_min_order']),
				'product_size'				=> dbClean($_POST['product_size']),
				'product_weight'			=> dbClean($_POST['product_weight'])
			);

			$data_detail['product_price_discount'] = "0";
			if(dbClean($_POST['chk_product_price_discount']) == "1"){
				$data_detail['product_price_discount'] = dbClean(convertRpToInt($_POST['product_price_discount']));
			}

			$data_detail['product_price_grosir'] = NULL;
			if(dbClean($_POST['chk_product_price_grosir']) == "1"){
				if(isset($_POST['product_qty_grosir'])){
					$product_qty_grosir   = $_POST['product_qty_grosir'];
					$product_price_grosir = $_POST['product_price_grosir'];

					$arr_grosir = array();
					$count_before = array();
				    foreach ($product_qty_grosir as $key1 => $n1) {
				        $count_before[] = $n1;
				    }
				    $i = 1;
					foreach ($product_qty_grosir as $key => $n) {
				        $min = $n;
				        $max = $count_before[$i] - 1;
				        $min_max = $min.' - '.$max;
				        if($count_before[$i] == ''){
				            $min_max = '>= '.$min;
				        }
				        $i += 1;

						$arr_grosir[] = array('name' 	=> $min_max,
											  'qty' 	=> $n,
											  'price'	=> convertRpToInt($product_price_grosir[$key])
											);
					}
					$data_detail['product_price_grosir'] = json_encode($arr_grosir);
				}
			}

            $log_item    = "";
            $log_qty     = "";
            $log_desc    = "";
			$data_detail['product_status_id']    = dbClean($_POST['product_status_id']);
            $data_detail['product_stock_detail'] = NULL;
            if(dbClean($_POST['chkVariant']) == "1"){
                $stock_status = false;
                $count_item_stok = 0;
                if(isset($_POST['product_stock_id'])){
					$arr_stock = array();
	                $arr_item  = array();
					$product_stock_id     = $_POST['product_stock_id'];
					$product_stock_name   = $_POST['product_stock_name'];
					$product_stock_color  = $_POST['product_stock_color'];
					$product_stock_old    = $_POST['product_stock_old'];
					$product_stock_qty    = $_POST['product_stock_qty'];
					$product_stock_status = $_POST['product_stock_status'];
					foreach ($product_stock_id as $key => $n) {
						$product_new_stock = $product_stock_qty[$key];
                        $count_item_stok   = $count_item_stok + $product_new_stock;
						$status_stock = $product_stock_status[$key];
						// if($data_detail['product_status_id'] == 3 && $product_new_stock == 0){ $status_stock = 2; }
						// else if($data_detail['product_status_id'] == 1 && $product_new_stock > 0){ $status_stock = 1; }

						$arr_stock[] = array('id' 		=> (strlen($n)==1?'0':'').$n,
											 'name' 	=> $product_stock_name[$key],
											 'color' 	=> ($product_stock_color[$key]!=''?strtolower($product_stock_color[$key]):'eeeff1'),
											 'qty' 		=> $product_new_stock,
											 'status' 	=> $status_stock
										  );

						if($status_stock == 1){ $stock_status = true; }

                        if($isCreate){
                            $arr_item[] = array('id'       => (strlen($n)==1?'0':'').$n,
                                                'name'     => $product_stock_name[$key],
                                                'qty'      => $product_stock_qty[$key],
                                                'status'   => $status_stock
                                            );
                        } else {
    						$arr_item[] = array('id'       => (strlen($n)==1?'0':'').$n,
                                                'name'     => $product_stock_name[$key],
                                                'qty_old'  => $product_stock_old[$key],
                                                'qty_new'  => $product_new_stock,
                                                'status'   => $status_stock
                                            );
                        }
					}
					$data_detail['product_stock_detail'] = json_encode($arr_stock);
                    $log_item = json_encode($arr_item);
                    $log_qty  = $count_item_stok;
				}
				$data_detail['product_stock'] = $count_item_stok;

                $data_detail['product_stock_copy'] = dbClean($_POST['product_stock_copy']);
			} else {
                $stock_status = true;
                $data_detail['product_stock'] = dbClean($_POST['product_stock']);
                $log_qty = $data_detail['product_stock'];
            }

			if($data_detail['product_status_id'] == 1 && $stock_status == false){
                $data_detail['product_status_id'] = 3;
            } else if($data_detail['product_status_id'] == 3 && $stock_status == true){
                // $data_detail['product_status_id'] = 1;
            }

			$this->DATA->table = "mt_product_detail";
			$save_detail = $this->_save_master(
				$data_detail,
				array(
					'product_detail_id' => dbClean($_POST['product_detail_id'])
				),
				dbClean($_POST['product_detail_id'])
			);
			$id_detail = $save_detail['id'];

			// if($data_detail['product_stock'] < 1){
			// 	$this->db->update("mt_product",array("product_show_id"=>0),array("product_id"=>$id));
			// }

			// SAVE DESCRIPTION
			if(dbClean($_POST['product_lead']) != "" || dbClean($_POST['product_description']) != "" || dbClean($_POST['product_meta_keyword']) != "" || dbClean($_POST['product_meta_description']) != "") {
				$data_desc = array(
					'product_id'				=> idClean($id),
					'product_lead'				=> dbClean($_POST['product_lead']),
					'product_description'		=> dbClean($_POST['product_description']),
					'product_meta_description'	=> dbClean($_POST['product_meta_description']),
					'product_meta_keyword'		=> dbClean($_POST['product_meta_keyword'])
				);
				$this->DATA->table = "mt_product_description";
				$save_desc = $this->_save_master(
					$data_desc,
					array(
						'product_description_id' => dbClean($_POST['product_description_id'])
					),
					dbClean($_POST['product_description_id'])
				);
			} else {
				$this->DATA->table = "mt_product_description";
				$this->DATA->_delete(array("product_id"	=> idClean($id) ),true);
			}

			$title_id = "1"; // Produk Baru
			if(!$isCreate){
				$title_id = "3"; // Perubahan Stok
			}
			$product_id = $id;
			writeLog(array(
				'log_user_type' 	=> "1", // Admin
				'log_user_id' 		=> $this->jCfg['user']['id'],
				'log_role' 			=> $this->jCfg['user']['level'],
				'log_type' 			=> "2", // Produk
				'log_detail_id' 	=> $product_id,
				'log_detail_item' 	=> $log_item,
				'log_detail_qty' 	=> $log_qty,
				'log_title_id' 		=> $title_id,
				'log_desc' 			=> $log_desc,
				'log_status' 		=> "0"
			));

			// SIMPAN KE PRINT BARCODE
			// $product_id = $id;
			// $product = get_detail_product($product_id);
   //          if(count($product) > 0){
   //              $dataBarcode = array(
   //                  'store_id'               => $this->store_id,
   //                  'product_id'             => $product_id,
   //                  'product_qty'            => 0,
   //                  'product_detail'         => NULL,
   //                  'print_barcode_status'   => 0,
   //                  'print_barcode_istrash'  => 0,
   //                  'print_barcode_date'     => timestamp()
   //              );

   //              $detail = get_product_detail($product_id);
   //              if($detail->product_stock_detail != ''){
   //              	$total_qty = 0;
   //                  $arr_stock = array();
   //                  $product_stock_detail = json_decode($detail->product_stock_detail);
   //                  foreach ($product_stock_detail as $key3 => $value3) {
   //                  	$total_qty = $total_qty + $value3->qty;
   //                      $arr_stock[] = array('id'       => $value3->id,
   //                                           'name'     => $value3->name,
   //                                           'qty'      => $value3->qty
   //                                        );
   //                  }
   //                  $dataBarcode['product_qty'] = $total_qty;
   //                  $dataBarcode['product_detail'] = json_encode($arr_stock);
   //              }

   //              $this->DATA->table="mt_print_barcode";
   //              $aBarcode = $this->_save_master(
   //                  $dataBarcode,
   //                  array(
   //                      'print_barcode_id' => ''
   //                      ),
   //                  ''
   //              );
   //          }
		}

		if ( isset($_POST['desc']) && count($_POST['desc']) > 0) {

			$cek = $this->db->get_where("mt_product_image",array(
				'product_id' => $id
			))->result();

			$id_current_arr = array();
			$position = 0;
			if( count($cek) > 0 ){
				foreach($cek as $mm=>$vv){
					$id_current_arr[] = $vv->image_id ;
				}
			}

			$item_del_arr = array();
			foreach ($_POST['desc'] as $kd => $vd) {

				$cek_pd = $this->db->get_where("mt_product_image",array(
					'image_id' => $kd
				))->row();

				if( count($cek_pd) == 0 ){
					//inser item..
					$this->db->insert("mt_product_image",array(
						"product_id" 		=> $id,
						"image_desc"		=> dbClean($vd),
						"image_date"		=> timestamp(),
						"position"			=> $position
					));
					$item_id = $this->db->insert_id();

				}else{
					$this->db->update("mt_product_image",array(
						"product_id" 	=> $id,
						"image_desc"		=> dbClean($vd),
						"image_date"		=> timestamp(),
						"position"			=> $position
					),array(
						"image_id"				=> $kd
					));

					$item_id = $kd;
				}
				$position +=1;

				$item_del_arr[] = $item_id;

				//upload item gallery..
				$this->DATA->table = 'mt_product_image';
				if($_FILES['file_'.$kd]['error']!=4){
					$this->_uploaded(
					array(
						'id'		=> $item_id,
						'input'		=> 'file_'.$kd,
						'param'		=> array(
										'field' => 'image_filename',
										'par'	=> array('image_id' => $item_id )
									)
					));
				}
			}

			//delete item..
			if( count($id_current_arr) > 0 ){
				foreach ($id_current_arr as $ov) {
					if( !in_array($ov,$item_del_arr) ){
						$this->DATA->table = 'mt_product_image';
						$this->_delte_old_files_without_thumb(
							array(
								'field' => 'image_filename',
								'par'	=> array('image_id' => $ov)
						));
						$this->db->delete("mt_product_image",array('image_id' => $ov));
					}
				}
			}
		} else {
			$this->DATA->table = 'mt_product_image';
			$this->_delte_old_files_without_thumb(
				array(
					'field' => 'image_filename',
					'par'	=> array('product_id' => $id)
			));
			$this->db->delete("mt_product_image",array('product_id' => $id));
		}

        set_last_date_product_setup();
		if($this->input->post("next")!=""){
			redirect($this->input->post("next")."?msg=".urlencode('Save data success')."&type_msg=success");
        } else {
			redirect($this->own_link."/view/".$id.'-'.changeEnUrl($_POST['product_name'])."?msg=".urlencode('Save data success')."&type_msg=success");
        }
	}


	// function detail($id='',$action='',$type='',$id_detail=''){
	// 	$this->image_size_str = "Size: 1024px x 720px";
	// 	$id = explode("-", $id);
	// 	$id = dbClean(trim($id[0]));
	// 	if(trim($id)!=''){
	// 		$this->data_form = $this->DATA->data_id(array(
	// 			'product_id'	=> $id
	// 		));
	// 		if(empty($this->data_form->product_id)){
	// 			redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
	// 		}

	// 		$data['product_detail_type'] = $type;

	// 		$this->_set_title( $this->data_form->product_name );
	// 		$this->breadcrumb[] = array(
	// 			"title"		=> "Detail ".$this->data_form->product_name
	// 		);
	// 		if(trim($action)!=''){
	// 			if(trim($id_detail)!=''){
	// 				$this->DATA->table = "mt_product_detail";
	// 				$data['product_detail'] = $this->DATA->data_id(array(
	// 					'product_detail_id'	=> $id_detail
	// 				));
	// 				if(empty($data['product_detail']->product_detail_id)){
	// 					redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
	// 				}
	// 			}

	// 			$this->_v($this->folder_view.$this->prefix_view."_detail_form",$data);
	// 		} else {
	// 			$this->_v($this->folder_view.$this->prefix_view."_detail",$data);
	// 		}
	// 	}else{
	// 		redirect($this->own_link);
	// 	}
	// }

	// function delete_detail($id=''){
	// 	$id = explode("-", $id);
	// 	$id = dbClean(trim($id[0]));
	// 	if(trim($id) != ''){
	// 		$this->DATA->table = "mt_product_detail";
	// 		$d = $this->db->get_where("mt_product_detail",array(
	// 			'product_detail_id' => $id
	// 		))->row();
	// 		if(count($d)>0){
	// 			$product_id = $d->product_id;

	// 			$this->_delte_old_files_without_thumb(
	// 				array(
	// 					'field' => 'product_detail_images',
	// 					'par'	=> array('product_detail_id' => $id)
	// 			));
	// 			$this->DATA->_delete(array("product_detail_id"	=> idClean($id)),true);

	// 			redirect($this->own_link."/detail/".$product_id."-".changeEnUrl(get_data_product_front($product_id)->product_name)."?msg=".urlencode('Delete data success')."&type_msg=success");
	// 		}
	// 	}
	// }

	// function save_detail(){
	// 	if (dbClean($_POST['product_detail_id']) == ""){
	// 		$dataPos = $this->db->order_by('position','asc')->get_where("mt_product_detail",array(
	// 			"product_id" 		  => dbClean($_POST['product_id']),
	// 			"product_detail_type" => dbClean($_POST['product_detail_type'])
	// 		))->result();
	// 		$position = 1;
	// 		foreach($dataPos as $r){
	// 			$id = $r->product_detail_id;
	// 			$this->db->update("mt_product_detail",array("position"=>$position),array("product_detail_id"=>$id));
	// 			$position +=1;
	// 		}
	// 	}

	// 	$data = array(
	// 		'product_id'				=> dbClean($_POST['product_id']),
	// 		'product_detail_name'		=> dbClean(ucwords($_POST['product_detail_name'])),
	// 		// 'product_detail_desc'		=> dbClean($_POST['product_detail_desc']),
	// 		'product_detail_type'		=> dbClean($_POST['product_detail_type'])
	// 	);

	// 	$product_detail_desc = NULL;
	// 	if(isset($_POST['product_detail_desc'])){
	// 		$product_detail_desc = dbClean($_POST['product_detail_desc']);
	// 	} else if(isset($_POST['arr_name'])){
	// 		$arr_result = array();
	// 		$arr_name = $_POST['arr_name'];
	// 		$arr_desc = $_POST['arr_desc'];
	// 		foreach ($arr_name as $key => $n) {
	// 			$arr_result[] = array('name' => $n, 'desc' => $arr_desc[$key]);
	// 		}
	// 		$product_detail_desc = json_encode($arr_result);
	// 	}

	// 	$data['product_detail_desc'] = $product_detail_desc;

	// 	$this->DATA->table = "mt_product_detail";
	// 	$a = $this->_save_master(
	// 		$data,
	// 		array(
	// 			'product_detail_id' => dbClean($_POST['product_detail_id'])
	// 		),
	// 		dbClean($_POST['product_detail_id'])
	// 	);

	// 	$id = $a['id'];
	// 	if(dbClean($_POST['remove_images']) == 1){
	// 		$this->_delte_old_files_without_thumb(
	// 		array(
	// 			'field' => 'product_detail_images',
	// 			'par'	=> array('product_detail_id' => $id)
	// 		));

	// 		$this->db->update("mt_product_detail",array("product_detail_images"=>NULL),array("product_detail_id"=>$id));
	// 	} else {
	// 		$this->upload_path="./assets/collections/product/";
	// 		$this->upload_resize  = array(
	// 			array('name'	=> 'thumb','width'	=> 450, 'quality'	=> '85%'),
	// 			array('name'	=> 'small','width'	=> 720, 'quality'	=> '85%'),
	// 			array('name'	=> 'large','width'	=> 1024, 'quality'	=> '85%')
	// 		);

	// 		if(isset($_FILES['product_detail_images'])){
	// 			$this->_uploaded(
	// 			array(
	// 				'id'		=> $id ,
	// 				'input'		=> 'product_detail_images',
	// 				'param'		=> array(
	// 								'field' => 'product_detail_images',
	// 								'par'	=> array('product_detail_id' => $id)
	// 							)
	// 			));
	// 		}
	// 	}

	// 	redirect($this->own_link."/detail/".$_POST['product_id'].'-'.changeEnUrl(get_data_product_front($_POST['product_id'])->product_name)."?msg=".urlencode('Save data success')."&type_msg=success");
	// }


    function view_stock_detail(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));
            if(trim($thisVal) != ""){
                $r = $this->db->get_where("mt_product",array(
                    'product_id'    => $thisVal
                ),1,0)->row();

                $detail = $this->db->get_where("mt_product_detail",array(
                    'product_id'    => $r->product_id
                ),1,0)->row();

				$product_status = '';
                $product_status_id = isset($detail->product_status_id)?$detail->product_status_id:"";
                $get_product_status = get_product_status();
                if(count($get_product_status)>0){
                    $i = 0;
                    foreach ($get_product_status as $key) {
                        $product_status .= '<option value="'.$key->product_status_id.'" '.(($i=='0')||($product_status_id==$key->product_status_id)?'selected':'').'>'.$key->product_status_name.'</option>';
                        $i += 1;
                    }
                }

                // GENERATE SPESIFIKASI ITEM DETAIL
                $chkVariant   = false;
                $groupStock   = "";
                $groupVariant = "hide";
                $total_stock  = 0;
                $last_name    = "";
                $content_stock_detail = '';
                if($detail->product_stock_detail != ''){
                    $chkVariant   = true;
                    $groupStock   = "disabled";
                    $groupVariant = "";

                    $data['product_stock_detail'] = json_decode($detail->product_stock_detail);
                    foreach ($data['product_stock_detail'] as $key => $value) {
                        $isi_stock_detail .= '
                            <tr class="item-stok-array">
                                <td class="nobr text-center">
                                    <input type="text" name="product_stock_id[]" value="'.$value->id.'" class="form-control form-no text-uppercase text-center" maxlength="2" required>
                                </td>
                                <td>
                                    <input type="text" name="product_stock_name[]" value="'.$value->name.'" class="form-control">
                                </td>
                                <td class="nobr text-center">
                                    <input type="hidden" name="product_stock_old[]" value="'.$value->qty.'">
                                    <input type="text" name="product_stock_qty[]" value="'.$value->qty.'" class="form-control calc-count-qty" maxlength="5">
                                </td>
                                <td>
                                    <select name="product_stock_status[]" class="form-control">
                                        <option value="1" selected>Ready</option>
                                        <option value="2" '.($value->status==2?'selected':'').' >Terjual</option>
                                    </select>
                                </td>
                                <td class="nobr text-center">
                                    <input type="hidden" name="product_stock_color[]" value="'.$value->color.'"/>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-remove-stok-array btn-xs" title="Hapus"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>';

                            $last_name    = $value->name;
                            $total_stock += $value->qty;
                    }

                    $content_stock_detail = $isi_stock_detail;

                } // END GENERATE SPESIFIKASI ITEM DETAIL

                $data['content'] = '
                <form class="form_save_stock_detail" data-id="'.$r->product_id.'" action="'.$this->own_link.'/save_stock_detail" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="row popup-content-product mb-20">
                        <div class="col-sm-4 magnific-popup-wrap">
                            <a class="zooming" href="'.get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id)).'">
                                <img class="img-responsive" src="'.get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id)).'" />
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <h4>'.$r->product_name.' - '.$r->product_code.'</h4>
                            <p class="mb-5"><small>'.get_root_product_category_parent($r->product_category_id).'</small></p>
                            <div class="form-horizontal">
                            	<div class="form-group mb-5">
                                    <label class="col-sm-5 control-label text-left">Status Stok</label>
			                        <div class="col-sm-7">
			                            <select name="product_status_id" class="form-control" required>
			                            '.$product_status.'
			                            </select>
			                        </div>
			                    </div>
                                <div class="form-group mb-0">
                                    <label class="col-sm-5 control-label text-left">Jumlah Stok</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="product_stock" value="'.$detail->product_stock.'" min="0" class="form-control groupStock" '.$groupStock.'>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="col-sm-5 control-label text-left"></label>
                                    <div class="col-sm-7">
                                        <div class="checkbox">
                                            <label><input type="checkbox" class="chkVariant" name="chkVariant" value="1" '.($chkVariant?'checked':'').' onclick="checkProductVariant();"> Ada Variasi</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive groupVariant '.$groupVariant.'">
                        <table class="table table-th-block">
                            <colgroup>
                                <col width="1">
                                <col>
                                <col width="1">
                                <col width="1">
                                <col width="1">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="nobr text-center">ID</th>
                                    <th>Nama</th>
                                    <th class="nobr text-center">Stok</th>
                                    <th class="nobr text-center" width="120">Status</th>
                                    <th class="nobr text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="result-stok-array" data-last-name="'.$last_name.'">
                            '.$content_stock_detail.'
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="btn btn-info btn-add-stok-array"><i class="fa fa-plus"></i> Tambah</div>
                                    </td>
                                    <td align="center" id="count-item-stok">
                                        '.$total_stock.' pcs
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="form-msg"></div>
                    <div class="form-group mb-0">
                        <div class="checkbox no-margin">
                            <label><input type="checkbox" class="chkPerseri" name="chkPerseri" value="1"> Cetak Perseri (10)</label>
                        </div>
                        <div class="checkbox no-margin">
                            <label><input type="checkbox" class="chkBarcode" name="chkBarcode" value="1"> Export untuk buat Barcode</label>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <input type="hidden" name="product_id" value="'.$r->product_id.'" />
                        <input type="hidden" name="thisAction" value="save" />
                        <button type="submit" name="save_update" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                    </div>
                </form>';

            }

        }

        die(json_encode($data));
        exit();
    }

    function save_stock_detail(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['url'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){

            $product_id = $_POST['product_id'];
            $products = $this->db->get_where("mt_product",array(
                'product_id'    => $product_id,
                "store_id"      => $this->store_id
            ),1,0)->row();
            if(count($products) > 0){
                $detail = $this->db->get_where("mt_product_detail",array(
                    'product_id'    => $product_id
                ),1,0)->row();
                $product_status_id_old = $detail->product_status_id;

                $dataProduct = array(
                    'product_date_update'   => timestamp()
                );
                $this->db->update("mt_product",$dataProduct,array("product_id"=>$product_id));

				$log_item    = "";
                $log_qty     = "";
                $log_desc    = "";
				$data_detail = array();
				$data_detail['product_status_id']    = dbClean($_POST['product_status_id']);
				$data_detail['product_stock_detail'] = NULL;
				if(dbClean($_POST['chkVariant']) == "1"){
					$stock_status    = false;
					$count_item_stok = 0;
					if(isset($_POST['product_stock_id'])){
	                    $arr_stock = array();
                        $arr_item  = array();
	                    $product_stock_id     = $_POST['product_stock_id'];
	                    $product_stock_name   = $_POST['product_stock_name'];
	                    $product_stock_color  = $_POST['product_stock_color'];
                        $product_stock_old    = $_POST['product_stock_old'];
	                    $product_stock_qty    = $_POST['product_stock_qty'];
	                    $product_stock_status = $_POST['product_stock_status'];
	                    foreach ($product_stock_id as $key => $n) {
	                        $product_new_stock = $product_stock_qty[$key];
							$count_item_stok   = $count_item_stok + $product_new_stock;

	                        $status_stock = $product_stock_status[$key];

	                        $arr_stock[] = array('id'       => (strlen($n)==1?'0':'').$n,
	                                             'name'     => $product_stock_name[$key],
	                                             'color'    => ($product_stock_color[$key]!=''?strtolower($product_stock_color[$key]):'eeeff1'),
	                                             'qty'      => $product_new_stock,
	                                             'status'   => $status_stock
	                                          );

	                        if($status_stock == 1){ $stock_status = true; }

                            $arr_item[] = array('id'       => (strlen($n)==1?'0':'').$n,
                                                'name'     => $product_stock_name[$key],
                                                'qty_old'  => $product_stock_old[$key],
                                                'qty_new'  => $product_new_stock,
                                                'status'   => $status_stock
                                            );
	                    }
	                    $data_detail['product_stock_detail'] = json_encode($arr_stock);
                        $log_item = json_encode($arr_item);
                        $log_qty  = $count_item_stok;
	                }
	                $data_detail['product_stock'] = $count_item_stok;
                } else {
					$stock_status = true;
					$data_detail['product_stock'] = dbClean($_POST['product_stock']);
                    $log_qty = $data_detail['product_stock'];
                }

                if($data_detail['product_status_id'] == 1 && $stock_status == false){
					$data_detail['product_status_id'] = 3;
				} else if($data_detail['product_status_id'] == 3 && $stock_status == true){
					// $data_detail['product_status_id'] = 1;
				}

                if($detail->product_stock_first == 0){
                    $data_detail['product_stock_first'] = $data_detail['product_stock'];
                }

                $this->db->update("mt_product_detail",$data_detail,array("product_detail_id"=>$detail->product_detail_id));

                $data['err'] = false;
                $data['msg'] = 'Sukses update stok.';
                set_last_date_product_setup();

                if($data_detail['product_status_id'] == 1 && $products->product_group_id != 0){
                    $product_group_id = $products->product_group_id;
                    $this->db->update("mt_product_group",array("product_group_show"=>1),array("product_group_id"=>$product_group_id));
                }

                $log_title_id = 3; // Perubahan Stok
                if($product_status_id_old != 1 && $data_detail['product_status_id'] == 1){
                	$log_title_id = 6; // Produk Dijual Kembali
                }
                writeLog(array(
                    'log_user_type'     => "1", // Admin
                    'log_user_id'       => $this->jCfg['user']['id'],
                    'log_role'          => $this->jCfg['user']['level'],
                    'log_type'          => "2", // Produk
                    'log_detail_id'     => $product_id,
                    'log_detail_item'   => $log_item,
                    'log_detail_qty'    => $log_qty,
                    'log_title_id'      => $log_title_id,
                    'log_desc'          => $log_desc,
                    'log_status' 		=> "0"
                ));
                sendProductNotif(array(
                    'user_id'       => $this->user_id,
                    'store_id'      => $this->store_id,
                    'product_id'    => $product_id,
                    'product_item'  => $log_item,
                    'product_qty'   => $log_qty,
                    'notif_title'   => "Produk update stok",
                    'notif_desc'    => "",
                    'notif_status'  => 1,
                    'notif_notify'  => 3
                ));

                if(dbClean($_POST['chkBarcode']) == "1"){
                    // SIMPAN KE PRINT BARCODE
                    $data['result']  = array();
                    $data['plastik'] = array();
                    $this->jCfg['export_barcode'] = array();
                    $this->jCfg['plastik_barcode'] = array();
                    $this->_releaseSession();
                    $product_qty = $data_detail['product_stock'];

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
                        $dataBarcode = array(
                            'store_id'               => $this->store_id,
                            'product_id'             => $product_id,
                            'product_qty'            => $product_qty,
                            'product_detail'         => NULL,
                            'print_barcode_status'   => 1,
                            'print_barcode_istrash'  => 0,
                            'print_barcode_date'     => timestamp()
                        );

                        if($product_qty != ""){
                            $detail = get_product_detail($product_id);
                            if($detail->product_stock_detail != ''){
                                $chkPerseri = 0;
                                if(dbClean($_POST['chkPerseri']) == "1"){ $chkPerseri = 1; }
    	                        $arr_stock = array();
    	                        $product_stock_detail = json_decode($detail->product_stock_detail);
    	                        foreach ($product_stock_detail as $key3 => $value3) {
                                    $new_qty = $value3->qty;
                                    if($chkPerseri == 1 && $new_qty > 0){
                                        $new_qty = ceil($value3->qty / 10) + 1;
                                    }
    	                            $arr_stock[] = array('id'       => $value3->id,
    	                                                 'name'     => $value3->name,
    	                                                 'qty'      => $new_qty
    	                                              );
    	                        }
    	                        $dataBarcode['product_detail'] = json_encode($arr_stock);
    	                    }
                        }

                        $this->DATA->table="mt_print_barcode";
                        $aBarcode = $this->_save_master(
                            $dataBarcode,
                            array(
                                'print_barcode_id' => $print_barcode_id
                                ),
                            $print_barcode_id
                        );

                        $print_barcode_id = $aBarcode['id'];
                        $printed_barcode  = get_printed_barcode($print_barcode_id);
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

                        $this->jCfg['export_barcode']  = arrayToObj($data['result']);
                        $this->jCfg['plastik_barcode'] = arrayToObj($data['plastik']);
                        $this->_releaseSession();

                        $data['url'] = base_url().'admin/product_barcode/export_barcode';
                    }
                    $data['msg'] = 'Sukses merubah status dijual dan akan segera diexport untuk mencetak barcode.';
                }
            } else {
                $data['err'] = true;
                $data['msg'] = 'Produk tidak ditemukan.';
            }
        }

        die(json_encode($data));
        exit();
    }

    function view_add_stock_detail(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));
            if(trim($thisVal) != ""){
                $r = $this->db->get_where("mt_product",array(
                    'product_id'    => $thisVal
                ),1,0)->row();

                $detail = $this->db->get_where("mt_product_detail",array(
                    'product_id'    => $r->product_id
                ),1,0)->row();

                $product_status = '';
                $product_status_id = isset($detail->product_status_id)?$detail->product_status_id:"";
                $get_product_status = get_product_status();
                if(count($get_product_status)>0){
                    $i = 0;
                    foreach ($get_product_status as $key) {
                        $product_status .= '<option value="'.$key->product_status_id.'" '.(($i=='0')||($product_status_id==$key->product_status_id)?'selected':'').'>'.$key->product_status_name.'</option>';
                        $i += 1;
                    }
                }

                // GENERATE SPESIFIKASI ITEM DETAIL
                $chkVariant   = false;
                $groupStock   = "";
                $groupVariant = "hide";
                $total_stock  = 0;
                $last_name    = "";
                $content_stock_detail = '';
                $content_stock_single = '';
                if($detail->product_stock_detail != ''){
                    $chkVariant   = true;
                    $groupStock   = "disabled";
                    $groupVariant = "";

                    $data['product_stock_detail'] = json_decode($detail->product_stock_detail);
                    foreach ($data['product_stock_detail'] as $key => $value) {
                        $isi_stock_detail .= '
                            <tr class="item-stok-array">
                                <td class="nobr text-center">
                                    <input type="hidden" name="product_stock_color[]" value="'.$value->color.'"/>
                                    <input type="hidden" name="product_stock_id[]" value="'.$value->id.'" class="form-control form-no text-uppercase text-center" maxlength="2" required>
                                    '.$value->id.'
                                </td>
                                <td>
                                    <input type="hidden" name="product_stock_name[]" value="'.$value->name.'" class="form-control">
                                    '.$value->name.'
                                </td>
                                <td class="nobr text-center">
                                    <input type="hidden" name="product_stock_old[]" value="'.$value->qty.'">
                                    '.$value->qty.'
                                </td>
                                <td class="nobr text-center">
                                    <input type="text" name="product_stock_qty[]" value="0" class="form-control calc-count-qty" maxlength="5">
                                </td>
                                <td class="nobr text-center">
                                    '.($value->status==1?'Ready':'Terjual').'
                                </td>
                            </tr>';

                            $last_name    = $value->name;
                            $total_stock += $value->qty;
                    }

                    $content_stock_detail = $isi_stock_detail;

                } else { // END GENERATE SPESIFIKASI ITEM DETAIL
                    $content_stock_single = '<div class="form-group mt-5 mb-0">
                                                <label class="col-sm-5 control-label text-left">Tambah Stok</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="product_stock" value="0" min="0" class="form-control">
                                                </div>
                                            </div>';
                }

                $data['content'] = '
                <form class="form_save_stock_detail" data-id="'.$r->product_id.'" action="'.$this->own_link.'/save_add_stock_detail" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="row popup-content-product mb-20">
                        <div class="col-sm-4 magnific-popup-wrap">
                            <a class="zooming" href="'.get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id)).'">
                                <img class="img-responsive" src="'.get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id)).'" />
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <h4>'.$r->product_name.' - '.$r->product_code.'</h4>
                            <p class="mb-5"><small>'.get_root_product_category_parent($r->product_category_id).'</small></p>
                            <div class="form-horizontal">
                                <div class="form-group mb-5">
                                    <label class="col-sm-5 control-label text-left">Status Stok</label>
                                    <div class="col-sm-7">
                                        <select name="product_status_id" class="form-control" disabled>
                                        '.$product_status.'
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="col-sm-5 control-label text-left">Jumlah Stok</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="" value="'.$detail->product_stock.'" min="0" class="form-control" disabled>
                                    </div>
                                </div>
                                '.$content_stock_single.'
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive groupVariant '.$groupVariant.'">
                        <table class="table table-th-block">
                            <colgroup>
                                <col width="1">
                                <col>
                                <col width="1">
                                <col width="1">
                                <col width="1">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="nobr text-center">ID</th>
                                    <th>Nama</th>
                                    <th class="nobr text-center">Stok Lama</th>
                                    <th class="nobr text-center">Tambah</th>
                                    <th class="nobr text-center" width="120">Status</th>
                                </tr>
                            </thead>
                            <tbody class="result-stok-array" data-last-name="'.$last_name.'">
                            '.$content_stock_detail.'
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">

                                    </td>
                                    <td align="center">
                                        '.$total_stock.' pcs
                                    </td>
                                    <td align="center" id="count-item-stok">
                                        0 pcs
                                    </td>
                                    <td colspan="1"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="form-msg"></div>
                    <div class="form-group mb-0">
                        <div class="checkbox no-margin">
                            <label><input type="checkbox" class="chkPerseri" name="chkPerseri" value="1"> Cetak Perseri (10)</label>
                        </div>
                        <div class="checkbox no-margin">
                            <label><input type="checkbox" class="chkBarcode" name="chkBarcode" value="1"> Export untuk buat Barcode</label>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <input type="hidden" name="product_id" value="'.$r->product_id.'" />
                        <input type="hidden" name="thisAction" value="save" />
                        <button type="submit" name="save_update" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                    </div>
                </form>';

            }

        }

        die(json_encode($data));
        exit();
    }

    function save_add_stock_detail(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['url'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){

            $product_id = $_POST['product_id'];
            $products = $this->db->get_where("mt_product",array(
                'product_id'    => $product_id,
                "store_id"      => $this->store_id
            ),1,0)->row();
            if(count($products) > 0){
                $detail = $this->db->get_where("mt_product_detail",array(
                    'product_id'    => $product_id
                ),1,0)->row();
                $product_status_id_old = $detail->product_status_id;

                $dataProduct = array(
                    'product_date_update'   => timestamp()
                );
                $this->db->update("mt_product",$dataProduct,array("product_id"=>$product_id));

                $log_item    = "";
                $log_qty     = "";
                $log_desc    = "";
                $data_detail = array();
                // $data_detail['product_status_id']    = $detail->product_status_id;
                $data_detail['product_stock_detail'] = NULL;
                if($detail->product_stock_detail != ""){
                    $arr_add_item = array();
                    if(isset($_POST['product_stock_id'])){
                        $product_stock_id  = $_POST['product_stock_id'];
                        $product_stock_qty = $_POST['product_stock_qty'];
                        foreach ($product_stock_id as $key => $n) {
                            if($product_stock_qty[$key] > 0){
                                $arr_add_item[$product_stock_id[$key]] = $product_stock_qty[$key];
                            }
                        }
                    }

                    $add_stock  = 0;
                    $arr_stock  = array();
                    $arr_item   = array();
                    $product_stock_detail = json_decode($detail->product_stock_detail);
                    foreach ($product_stock_detail as $key3 => $val3) {
                        $new_qty = $val3->qty;
                        if(array_key_exists($val3->id, $arr_add_item)){
                            $add_stock += $arr_add_item[$val3->id];
                            $new_qty = ($new_qty + $arr_add_item[$val3->id]);
                        }
                        $arr_stock[] = array('id'       => $val3->id,
                                             'name'     => $val3->name,
                                             'color'    => $val3->color,
                                             'qty'      => $new_qty,
                                             'status'   => ($new_qty > 0?1:2)
                                          );

                        $arr_item[] = array('id'        => $val3->id,
                                            'name'      => $val3->name,
                                            'qty_old'   => $val3->qty,
                                            'qty_new'   => $new_qty,
                                            'status'    => ($new_qty > 0?1:2)
                                        );

                        $new_stock += $new_qty;
                    }
                    $data_detail['product_stock_detail'] = json_encode($arr_stock);
                    $log_item  = json_encode($arr_item);
                    $data_detail['product_stock'] = $new_stock;
                    $log_qty = $add_stock;
                } else {
                    $add_stock = dbClean($_POST['product_stock']);
                    $data_detail['product_stock'] = $detail->product_stock + $add_stock;
                    $log_qty = $data_detail['product_stock'];
                }

                if($detail->product_stock_first == 0){
                    $data_detail['product_stock_first'] = $data_detail['product_stock'];
                }

                $this->db->update("mt_product_detail",$data_detail,array("product_detail_id"=>$detail->product_detail_id));

                $data['err'] = false;
                $data['msg'] = 'Sukses tambah stok.';
                set_last_date_product_setup();

                $log_title_id = 3; // Perubahan Stok
                writeLog(array(
                    'log_user_type'     => "1", // Admin
                    'log_user_id'       => $this->jCfg['user']['id'],
                    'log_role'          => $this->jCfg['user']['level'],
                    'log_type'          => "2", // Produk
                    'log_detail_id'     => $product_id,
                    'log_detail_item'   => $log_item,
                    'log_detail_qty'    => $log_qty,
                    'log_title_id'      => $log_title_id,
                    'log_desc'          => $log_desc,
                    'log_status'        => "0"
                ));

                if(dbClean($_POST['chkBarcode']) == "1"){
                    // SIMPAN KE PRINT BARCODE
                    $this->jCfg['export_barcode'] = array();
                    $this->_releaseSession();
                    $product_qty = $add_stock;

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
                        $dataBarcode = array(
                            'store_id'               => $this->store_id,
                            'product_id'             => $product_id,
                            'product_qty'            => $product_qty,
                            'product_detail'         => NULL,
                            'print_barcode_status'   => 1,
                            'print_barcode_istrash'  => 0,
                            'print_barcode_date'     => timestamp()
                        );

                        if($product_qty != ""){
                            $detail = get_product_detail($product_id);
                            if($detail->product_stock_detail != ''){
                                $chkPerseri = 0;
                                if(dbClean($_POST['chkPerseri']) == "1"){ $chkPerseri = 1; }
                                $arr_stock = array();
                                $product_stock_detail = json_decode($detail->product_stock_detail);
                                foreach ($product_stock_detail as $key3 => $val3) {
                                    $new_qty = 0;
                                    if(array_key_exists($val3->id, $arr_add_item)){
                                        $new_qty = $arr_add_item[$val3->id];
                                        if($chkPerseri == 1 && $new_qty > 0){
                                            $new_qty = ceil($arr_add_item[$val3->id] / 10) + 1;
                                        }
                                    }
                                    $arr_stock[] = array('id'       => $val3->id,
                                                         'name'     => $val3->name,
                                                         'qty'      => $new_qty
                                                      );
                                }
                                $dataBarcode['product_detail'] = json_encode($arr_stock);
                            }
                        }

                        $this->DATA->table="mt_print_barcode";
                        $aBarcode = $this->_save_master(
                            $dataBarcode,
                            array(
                                'print_barcode_id' => $print_barcode_id
                                ),
                            $print_barcode_id
                        );

                        $print_barcode_id = $aBarcode['id'];
                        $printed_barcode  = get_printed_barcode($print_barcode_id);
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
                        }

                        $this->jCfg['export_barcode'] = arrayToObj($data['result']);
                        $this->_releaseSession();

                        $data['url'] = base_url().'admin/product_barcode/export_barcode';
                    }
                    $data['msg'] = 'Sukses merubah status dijual dan akan segera diexport untuk mencetak barcode.';
                }
            } else {
                $data['err'] = true;
                $data['msg'] = 'Produk tidak ditemukan.';
            }

        }

        die(json_encode($data));
        exit();
    }

    function view_get_stock_detail(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));
            if(trim($thisVal) != ""){
                $r = $this->db->get_where("mt_product",array(
                    'product_id'    => $thisVal
                ),1,0)->row();

                $detail = $this->db->get_where("mt_product_detail",array(
                    'product_id'    => $r->product_id
                ),1,0)->row();

                $product_status = '';
                $product_status_id = isset($detail->product_status_id)?$detail->product_status_id:"";
                $product_status    = get_product_status($product_status_id)->product_status_name;

                // GENERATE SPESIFIKASI ITEM DETAIL
                $groupStock   = "";
                $groupVariant = "hide";
                $total_stock  = 0;
                $content_stock_detail = '';
                if($detail->product_stock_detail != ''){
                    $groupStock   = "disabled";
                    $groupVariant = "";

                    $data['product_stock_detail'] = json_decode($detail->product_stock_detail);
                    foreach ($data['product_stock_detail'] as $key => $value) {
                        $isi_stock_detail .= '
                            <tr class="item-stok-array">
                                <td class="nobr text-center">
                                    <input type="hidden" name="product_stock_id[]" value="'.$value->id.'">
                                    '.$value->id.'
                                </td>
                                <td>'.$value->name.'</td>
                                <td class="nobr text-center">'.$value->qty.'</td>
                                <td class="nobr text-center">
                                    <input type="hidden" name="product_stock_old[]" value="'.$value->qty.'">
                                    <input type="text" name="product_stock_qty[]" value="0" class="form-control calc-count-get-qty" min="0" max="'.$value->qty.'">
                                </td>
                                <td class="product_stock_status text-center">'.($value->status==1?"Ready":"Terjual").'</td>
                            </tr>';
                            $total_stock += $value->qty;
                    }

                    $content_stock_detail = $isi_stock_detail;

                } // END GENERATE SPESIFIKASI ITEM DETAIL

                $data['content'] = '
                <form class="form_save_get_stock_detail" data-id="'.$r->product_id.'" action="'.$this->own_link.'/save_get_stock_detail" method="post" autocomplete="off" enctype="multipart/form-data">
                    <legend>Pengurangan Stok</legend>
                    <div class="row popup-content-product mb-20">
                        <div class="col-sm-4 magnific-popup-wrap">
                            <a class="zooming" href="'.get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id)).'">
                                <img class="img-responsive" src="'.get_image(base_url()."assets/collections/product/small/".get_cover_image_detail($r->product_id)).'" />
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <h4>'.$r->product_name.' - '.$r->product_code.'</h4>
                            <p class="mb-5"><small>'.get_root_product_category_parent($r->product_category_id).'</small></p>
                            <div class="form-horizontal">
                                <div class="form-group mb-5">
                                    <label class="col-sm-5 control-label text-left">Status Stok</label>
                                    <div class="col-sm-7"><div class="form-control">'.$product_status.'</div></div>
                                </div>
                                <div class="form-group mb-5">
                                    <label class="col-sm-5 control-label text-left">Jumlah Stok</label>
                                    <div class="col-sm-7"><div class="form-control">'.$detail->product_stock.' pcs</div></div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="col-sm-5 control-label text-left">Jumlah Ambil</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="product_stock" value="0" min="0" max="'.$detail->product_stock.'" class="form-control groupStock" '.$groupStock.'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive groupVariant '.$groupVariant.'">
                        <table class="table table-th-block">
                            <colgroup>
                                <col width="1">
                                <col>
                                <col width="1">
                                <col width="1">
                                <col width="1">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="nobr text-center">ID</th>
                                    <th>Nama</th>
                                    <th class="nobr text-center">Stok</th>
                                    <th class="nobr text-center">Ambil</th>
                                    <th class="nobr text-center" width="120">Status</th>
                                </tr>
                            </thead>
                            <tbody class="result-stok-array">
                            '.$content_stock_detail.'
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Catatan</label>
                        <div class="col-sm-9">
                            <textarea name="noted" class="form-control no-resize" rows="3" maxlength="300" required></textarea>
                        </div>
                    </div>
                    <div class="form-msg"></div>
                    <div class="form-group form-action mb-0">
                        <input type="hidden" name="product_id" value="'.$r->product_id.'" />
                        <input type="hidden" name="thisAction" value="save" />
                        <button type="submit" name="save_update" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                    </div>
                </form>';

            }

        }

        die(json_encode($data));
        exit();
    }

    function save_get_stock_detail(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['url'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
            $product_id = $_POST['product_id'];
            $products = $this->db->get_where("mt_product",array(
                'product_id'    => $product_id,
                "store_id"      => $this->store_id
            ),1,0)->row();
            if(count($products) > 0){
                $detail = $this->db->get_where("mt_product_detail",array(
                    'product_id'    => $product_id
                ),1,0)->row();
                if(count($detail) > 0){
                    $dataDetail = array();
                    $dataDetail['product_status_id'] = $detail->product_status_id;

                    $log_item = "";
                    if($detail->product_stock_detail != ""){

                        $arr_cart_item = array();
                        if(isset($_POST['product_stock_id'])){
                            $product_stock_id  = $_POST['product_stock_id'];
                            $product_stock_qty = $_POST['product_stock_qty'];
                            foreach ($product_stock_id as $key => $n) {
                                if($product_stock_qty[$key] > 0){
                                    $arr_cart_item[$product_stock_id[$key]] = $product_stock_qty[$key];
                                }
                            }
                        }

                        $minus_stock = 0;
                        $new_stock   = 0;
                        $found_sold = false;
                        $arr_stock  = array();
                        $arr_item   = array();
                        $product_stock_detail = json_decode($detail->product_stock_detail);
                        foreach ($product_stock_detail as $key3 => $val3) {
                            $new_qty = $val3->qty;
                            if(array_key_exists($val3->id, $arr_cart_item)){
                                $minus_stock += $arr_cart_item[$val3->id];

                                $new_qty = ($new_qty - $arr_cart_item[$val3->id]);
                                if($new_qty < 1){
                                    $found_sold = true;
                                }
                            }
                            $arr_stock[] = array('id'       => $val3->id,
                                                 'name'     => $val3->name,
                                                 'color'    => $val3->color,
                                                 'qty'      => $new_qty,
                                                 'status'   => ($new_qty > 0?1:2)
                                              );

                            $arr_item[] = array('id'        => $val3->id,
                                                'name'      => $val3->name,
                                                'qty_old'   => $val3->qty,
                                                'qty_new'   => $new_qty,
                                                'status'    => ($new_qty > 0?1:2)
                                            );

                            $new_stock += $new_qty;
                        }
                        $dataDetail['product_stock_detail'] = json_encode($arr_stock);
                        $log_item  = json_encode($arr_item);
                        $dataDetail['product_stock'] = $new_stock;
                        $log_qty = $minus_stock;

                        if($found_sold){
                            sendProductNotif(array(
                                'user_id'       => $this->user_id,
                                'store_id'      => $this->store_id,
                                'product_id'    => $product_id,
                                'product_item'  => $log_item,
                                'product_qty'   => $new_stock,
                                'notif_title'   => "Variasi produk sudah habis",
                                'notif_desc'    => "",
                                'notif_status'  => 1,
                                'notif_notify'  => 3
                            ));
                        }
                    } else {
                        $minus_stock = $_POST['product_stock'];
                        $new_stock = ($detail->product_stock - $minus_stock);
                        $dataDetail['product_stock'] = $new_stock;
                        if($new_stock < 1){
                            $dataDetail['product_status_id'] = 3;
                            writeLog(array(
                                'log_user_type'     => "1", // Admin
                                'log_user_id'       => $this->user_id,
                                'log_role'          => NULL,
                                'log_type'          => "2", // Produk
                                'log_detail_id'     => $product_id,
                                'log_detail_item'   => NULL,
                                'log_detail_qty'    => $new_stock,
                                'log_title_id'      => "4", // Produk Soldout Otomatis
                                'log_desc'          => NULL,
                                'log_status'        => "0"
                            ));
                            sendProductNotif(array(
                                'user_id'       => $this->user_id,
                                'store_id'      => $this->store_id,
                                'product_id'    => $product_id,
                                'product_item'  => NULL,
                                'product_qty'   => $new_stock,
                                'notif_title'   => "Produk Soldout Otomatis",
                                'notif_desc'    => "",
                                'notif_status'  => 1,
                                'notif_notify'  => 3
                            ));
                        }
                        $log_qty = $minus_stock;
                    }

                    $log_desc = $_POST['noted'];
                    writeLog(array(
                        'log_user_type'     => "1", // Admin
                        'log_user_id'       => $this->user_id,
                        'log_role'          => NULL,
                        'log_type'          => "2", // Produk
                        'log_detail_id'     => $product_id,
                        'log_detail_item'   => $log_item,
                        'log_detail_qty'    => $log_qty,
                        'log_title_id'      => "11", // Pengurangan Stok
                        'log_desc'          => $log_desc,
                        'log_status'        => "0"
                    ));
                    $this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

                    $data['err'] = false;
                    $data['msg'] = "Berhasil kurangi stok.";
                    set_last_date_product_setup();
                } else {
                    $data['err'] = true;
                    $data['msg'] = "Produk detail tidak ditemukan.";
                }
            } else {
                $data['err'] = true;
                $data['msg'] = 'Produk tidak ditemukan.';
            }
        }

        die(json_encode($data));
        exit();
    }

    function view_popup_product_group(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $thisVal       = dbClean(trim($_POST['thisVal']));
            if(trim($thisVal) != ""){
                $arr_name  = "";
                $arr_group = "";
                $exp = explode("-", $thisVal);
                foreach ($exp as $n) {
                    $r = $this->db->get_where("mt_product",array(
                        'product_id'    => $n
                    ),1,0)->row();
                    $arr_name .= '<li>'.get_product_brand_name($r->product_brand_id).' - '.$r->product_name.'</li>';
                    if($r->product_group_id != 0){
                        $arr_group = $r->product_group_id;
                    }
                }

                $opt_group = "";
                $product_group = get_product_group();
                foreach ($product_group as $key) {
                    $opt_group .= '<option value="'.$key->product_group_id.'" '.(($key->product_group_id==$arr_group)?'selected':'').'>'.$key->product_group_name.' - '.convDate($key->product_group_date).'</option>';
                }

                $data['content'] = '
                <form class="form_save_product" data-id="'.$thisVal.'" action="'.$this->own_link.'/save_popup_product_group" method="post" autocomplete="off" enctype="multipart/form-data">
                    <legend>Group Produk</legend>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Produk</label>
                            <div class="col-sm-9">
                                <ol>
                                    '.$arr_name.'
                                </ol>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Group</label>
                            <div class="col-sm-9">
                                <select name="product_group_id" data-allows-new-values="true" data-placeholder="--- Select ---" class="form-control chosen-select" required>
                                    <option value="" selected>--- Pilih ---</option>
                                    '.$opt_group.'
                                    <option value="addnew">--- Buat Baru ---</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="product_group_name" style="display:none;">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-9">
                                <input type="text" name="product_group_name" placeholder="Nama Group Baru" value="" class="form-control" maxlength="50">
                            </div>
                        </div>
                        <div class="form-group form-action mb-0">
                            <input type="hidden" name="product_id" value="'.$thisVal.'" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" name="save_update" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                        </div>
                    </div>
                </form>';

            }

        }

        die(json_encode($data));
        exit();
    }

    function save_popup_product_group(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['url'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
            $product_group_id = $_POST['product_group_id'];
            if($_POST['product_group_id'] == "addnew"){
                $dataGroup = array(
                    'store_id'                  => $this->store_id,
                    'product_group_name'        => dbClean(ucwords($_POST['product_group_name'])),
                    'product_group_date'        => timestamp(),
                    'product_group_show'        => 1,
                    'product_group_istrash'     => 0
                );

                $this->DATA->table = "mt_product_group";
                $saveGroup = $this->_save_master(
                    $dataGroup,
                    array(
                        'product_group_id' => ''
                    ),
                    ''
                );
                $product_group_id = $saveGroup['id'];
            }

            $product_id = $_POST['product_id'];
            $exp = explode("-", $product_id);
            foreach ($exp as $n) {
                $r = $this->db->get_where("mt_product",array(
                    'product_id'    => $n
                ),1,0)->row();
                if(count($r) > 0){
                    $this->db->update("mt_product",array("product_group_id"=>$product_group_id),array("product_id"=>$r->product_id));
                }
            }

            $data['err'] = false;
            $data['msg'] = 'Berhasil simpan Group Produk..';
            set_last_date_product_setup();
        }

        die(json_encode($data));
        exit();
    }

    function form_update_price_multiple(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $product_id = dbClean(trim($_POST['thisVal']));
            $par_filter = array(
                "user_id"       => $this->user_id,
                "store_id"      => $this->store_id,
                "product_id"    => ($product_id!=""?$product_id:'')
            );

            $result = $this->MF->data_form_update_price_multiple($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

    function save_update_price_multiple(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['url'] = '';

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
            $found = false;
            $data_detail = array();
            if(isset($_POST['product_price_buy']) && $_POST['product_price_buy'] != ''){
                $data_detail['product_price_buy'] = convertRpToInt($_POST['product_price_buy']);
                $found = true;
            }
            if(isset($_POST['product_price_sale']) && $_POST['product_price_sale'] != ''){
                $data_detail['product_price_sale'] = convertRpToInt($_POST['product_price_sale']);
                $found = true;
            }
            if(isset($_POST['product_price_discount']) && $_POST['product_price_discount'] != ''){
                $data_detail['product_price_discount'] = convertRpToInt($_POST['product_price_discount']);
                $found = true;
            }

            if($found){
                $data_detail['product_price_grosir'] = NULL;
                if(dbClean($_POST['chk_product_price_grosir']) == "1"){
                    if(isset($_POST['product_qty_grosir'])){
                        $product_qty_grosir   = $_POST['product_qty_grosir'];
                        $product_price_grosir = $_POST['product_price_grosir'];

                        $arr_grosir = array();
                        $count_before = array();
                        foreach ($product_qty_grosir as $key1 => $n1) {
                            if($product_qty_grosir[$key] != "" && $product_price_grosir[$key] != ""){
                                $count_before[] = $n1;
                            }
                        }
                        $i = 1;
                        foreach ($product_qty_grosir as $key => $n) {
                            if($product_qty_grosir[$key] != "" && $product_price_grosir[$key] != ""){
                                $min = $n;
                                $max = $count_before[$i] - 1;
                                $min_max = $min.' - '.$max;
                                if($count_before[$i] == ''){
                                    $min_max = '>= '.$min;
                                }
                                $i += 1;

                                $arr_grosir[] = array('name'    => $min_max,
                                                      'qty'     => $n,
                                                      'price'   => convertRpToInt($product_price_grosir[$key])
                                                    );
                            }
                        }
                        if(count($arr_grosir) > 0){
                            $data_detail['product_price_grosir'] = json_encode($arr_grosir);
                        }
                    }
                }

                $product_id = $_POST['product_id'];
                $exp = explode("-", $product_id);
                foreach ($exp as $n) {
                    $r = $this->db->get_where("mt_product",array(
                        'store_id'    => $this->store_id,
                        'product_id'  => $n
                    ),1,0)->row();
                    if(count($r) > 0){
                        $this->db->update("mt_product_detail",$data_detail,array("product_id"=>$r->product_id));
                    }
                }

                $data['err'] = false;
                $data['msg'] = 'Berhasil update harga produk..';
                set_last_date_product_setup();
            } else {
                $data['err'] = true;
                $data['msg'] = 'Gagal update harga produk..';
            }
        }

        die(json_encode($data));
        exit();
    }

	function change_position(){
		if ($_POST) {
			$temp_position = $_SERVER['REMOTE_ADDR'];
			$ids    = $_POST["ids"];
			for ($idx = 0; $idx < count($ids); $idx+=1) {
				$id = $ids[$idx];
				//...
				$data = array(
					'position'		=> dbClean($idx),
					'temp_position'	=> dbClean($temp_position),
				);

				$a = $this->_save_master(
					$data,
					array(
						'product_id' => dbClean((int)$id)
					),
					dbClean((int)$id)
				);
			}
			return;
		}
	}

	function change_position_images_detail(){
		if ($_POST) {
			$temp_position = $_SERVER['REMOTE_ADDR'];
			$ids    = $_POST["ids"];
			for ($idx = 0; $idx < count($ids); $idx+=1) {
				$id = $ids[$idx];
				//...
				$data = array(
					'position'		=> dbClean($idx),
					'temp_position'	=> dbClean($temp_position),
				);

				$this->DATA->table = 'mt_product_image';
				$a = $this->_save_master(
					$data,
					array(
						'image_id' => dbClean((int)$id)
					),
					dbClean((int)$id)
				);
                set_last_date_product_setup();
			}
			return;
		}
	}

	function check_form(){
		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_form' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$thisChkId     = dbClean(trim($_POST['thisChkId']));
			$thisChkParent = dbClean(trim($_POST['thisChkParent']));
			$thisChkRel    = dbClean(trim($_POST['thisChkRel']));

			$this->DATA->table="mt_product";
			if(trim($thisVal)!=''){
				if(trim($thisChkId)!=''){
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	   	  => $thisVal,
						'product_id !='   => $thisChkId
					));
				} else {
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	=> $thisVal
					));
				}
				if(empty($this->data_form->$thisChkRel)){
					$err = false;
					$msg = '';
				} else {
					$err = true;
					$msg = 'Data sudah ada...';
				}
			}
		}

		$return = array('msg' => $msg,'err' => $err);
		die(json_encode($return));
		exit();
	}

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_product",array("product_show_id"=>$val),array("product_id"=>$id));
			$msg = 'success';
            set_last_date_product_setup();
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

	function create_product_code(){
		$data = array();
		$data['err'] 	 = true;
		$data['msg'] 	 = '';
		$data['code']     = '';
		$data['fullcode'] = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'create' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));

			if(trim($thisVal)!=''){
				$create_product_code = create_product_code($thisVal);
				$data['code'] 	 	 = $create_product_code['code'];
				$data['fullcode'] 	 = $create_product_code['fullcode'];
				$data['err'] 	 	 = false;
			}
		}

		die(json_encode($data));
		exit();
	}

    // EDIT MULTIPLE
    function edit_sku(){
        $this->_set_title( 'ID / SKU PRODUK DI MARKETPLACE' );
        $this->breadcrumb[] = array(
            "title"     => "ID / SKU PRODUK DI MARKETPLACE"
        );

        if(isset($_GET['id']) && $_GET['id']!=''){
            $id   = $_GET['id'];
            $exp  = explode("-", $id);
            foreach ($exp as $n) {
                // $product_id = $n;
                // $product = get_detail_product($product_id);

                // $data['mp'] = $this->db->get_where("mt_product_mp",array(
                //     'product_id'    => $id
                // ),1,0)->row();
                // debugCode($data['mp']);
            }
            $this->_v($this->folder_view.$this->prefix_view."_edit_sku",$data);
        } else {
            redirect($this->own_link);
        }
    }

    function save_edit_sku(){
        if(isset($_POST['product_id'])){
            $product_id = $_POST['product_id'];
            foreach ($product_id as $key => $n) {
                $data_mp = array(
                    'product_id'                => idClean($n),
                    'product_mp_tokped'         => dbClean($_POST['product_mp_tokped'][$key]),
                    'product_mp_bulap'          => dbClean($_POST['product_mp_bulap'][$key]),
                    'product_mp_shopee'         => dbClean($_POST['product_mp_shopee'][$key]),
                    'product_mp_lazada'         => dbClean($_POST['product_mp_lazada'][$key])
                );

                $this->DATA->table = "mt_product_mp";
                $save_mp = $this->_save_master(
                    $data_mp,
                    array(
                        'product_mp_id' => dbClean($_POST['product_mp_id'][$key])
                    ),
                    dbClean($_POST['product_mp_id'][$key])
                );
                set_last_date_product_setup();
            }
        }

        if($this->input->get("next")!=""){
            redirect($this->input->get("next")."?msg=".urlencode('Update SKU data success')."&type_msg=success");
        } else {
            redirect($this->own_link."?msg=".urlencode('Update SKU data success')."&type_msg=success");
        }

    }

    function form_update_stock_barcode(){
        $data = array();
        $data['err']     = true;
        $data['msg']     = '';
        $data['content'] = '';
        $data['result_msg'] = "";
        if(!isset($this->jCfg['result_last_update_stock_barcode'])){
            $this->jCfg['result_last_update_stock_barcode'] = array();
            $this->_releaseSession();
        } else {
            foreach ($this->jCfg['result_last_update_stock_barcode'] as $n) {
                $data['result_msg'] .= "<li>".$n."</li>";
            }
        }
        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
            $par_filter = array(
                "user_id"       => $this->user_id,
                "store_id"      => $this->store_id,
                "result_msg"    => $data['result_msg']
            );

            $result = $this->MF->data_form_update_stock_barcode($par_filter);
            $data['content'] = $result['data'];
        }

        die(json_encode($data));
        exit();
    }

    function save_update_stock_barcode(){
        $data = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['content'] = '';

        $data['result_msg'] = array();
        if(!isset($this->jCfg['result_last_update_stock_barcode'])){
            $this->jCfg['result_last_update_stock_barcode'] = array();
            $this->_releaseSession();
        }

        if( isset($_POST['thisAction']) && $_POST['thisAction'] != '' ){
            $thisAction      = dbClean(trim($_POST['thisAction']));
            $product_barcode = dbClean(trim($_POST['product_barcode']));
            $par_filter = array(
                "user_id"         => $this->user_id,
                "store_id"        => $this->store_id,
                "thisAction"      => $thisAction,
                "product_barcode" => $product_barcode
            );

            $result = $this->MP->save_update_stock_barcode($par_filter);
            $data['content'] = $result['data'];
            $data['err']     = $result['error'];
            $data['msg']     = $result['msg'];
            $data['result_msg'] = $result['result_msg'];
        }

        if(!$data['err']){
            if(count($this->jCfg['result_last_update_stock_barcode']) > 0){
                $i = 0;
                foreach ($this->jCfg['result_last_update_stock_barcode'] as $n) {
                    if($i < 29){ $data['result_msg'][] = $n; }
                    $i += 1;
                }
            }
        }

        $this->jCfg['result_last_update_stock_barcode'] =  arrayToObj($data['result_msg']);
        $this->_releaseSession();
        $data['result_last_update'] = $this->jCfg['result_last_update_stock_barcode'];

        die(json_encode($data));
        exit();
    }

}