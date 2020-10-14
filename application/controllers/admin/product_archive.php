<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class product_archive extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        $this->_set_action(array("view","delete"),"ITEM");
        $this->_set_title( 'Produk Arsip' );
        $this->DATA->table = "mt_product_archive";
        $this->folder_view = "product/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_product_archive","MA");
        $this->breadcrumb[] = array(
            "title"     => "Produk Arsip",
            "url"       => $this->own_link
        );

        $this->cat_search = array(
            ''                                             => 'Semua Pencarian...',
            'mt_product_archive.product_name'              => 'Judul',
			'mt_product_archive.product_name_simple'	   => 'Nama Singkat',
            'mt_product_archive.product_code'              => 'Kode',
            'mt_product_archive.product_category_title'    => 'Kategori',
        );

        $this->user_id  = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id = get_user_store($this->user_id);
    }

    function _reset(){
        $this->jCfg['search'] = array(
            'class'     => $this->_getClass(),
            'name'      => 'product_archive',
            'date_start'=> '',
            'date_end'  => '',
            'status'    => '',
            'order_by'  => 'mt_product_archive.product_date_archive',
            'order_dir' => 'desc',
            'filter'    => '40',
            'colum'     => '',
            'keyword'   => ''
        );
        $this->_releaseSession();
    }

    function index(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'product_archive'){
            $this->_reset();
        }

        $this->breadcrumb[] = array(
            "title"     => "List"
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

        $par_filter = array(
            "store_id"            => $this->store_id,
            "product_istrash"     => "0",
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

        $this->data_table = $this->MA->data_product_archive($par_filter);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/index'
        ));

        $data['total_data'] = $this->data_table['total'];
        $data['url'] = base_url()."admin/product_archive";
        $data['url_parent'] = base_url()."admin/product_archive";

        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

    function view($id=''){
        $this->breadcrumb[] = array(
            "title"     => "View"
        );
        $id = explode("-", $id);
        $id = dbClean(trim($id[0]));
        if(trim($id)!=''){
            $this->data_form = $this->DATA->data_id(array(
                "product_id"    => $id,
                "store_id"      => $this->store_id
            ));
            if(empty($this->data_form->product_id)){
                redirect($this->own_link."?msg=".urlencode('Data tidak ditemukan')."&type_msg=error");
            }

            // $data['description'] = $this->db->get_where("mt_product_description",array(
            //     'product_id'    => $id
            // ),1,0)->row();

            $data['detail'] = $this->db->get_where("mt_product_archive_detail",array(
                'product_id'    => $id
            ),1,0)->row();

            $data['image_product'] = $this->db->order_by('position','asc')->get_where("mt_product_archive_image",array(
                'product_id'    => $id
            ))->result();
            $this->_v($this->folder_view.$this->prefix_view."_view",$data);
        }else{
            redirect($this->own_link);
        }
    }

    function delete_permanent($id=''){
        $id = explode("-", $id);
        $id = dbClean(trim($id[0]));
        if(trim($id) != ''){
            $this->DATA->table = "mt_product_archive";
            $this->DATA->_delete(array("product_id" => idClean($id)),true);

            $description = $this->db->get_where("mt_product_archive_description",array(
                "product_id" => $id
            ),1,0)->row();
            if(count($description) > 0){
                $this->DATA->table = "mt_product_archive_description";
                $this->DATA->_delete(array("product_description_id" => $description->product_description_id),true);
            }

            $detail = $this->db->get_where("mt_product_archive_detail",array(
                "product_id" => $id
            ),1,0)->row();
            if(count($detail) > 0){
                $this->DATA->table = "mt_product_archive_detail";
                $this->DATA->_delete(array("product_detail_id"  => $detail->product_detail_id),true);
            }

            $image = $this->db->get_where("mt_product_archive_image",array(
                "product_id" => $id
            ))->result();
            if(count($image) > 0){
                foreach ($image as $key => $value) {
                    $this->DATA->table = "mt_product_archive_image";
                    $this->_delte_old_files_without_thumb(
                        array(
                            'field' => 'image_filename',
                            'par'   => array('image_id' => $value->image_id)
                    ));
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
                $this->DATA->table = "mt_product_archive";
                $this->DATA->_delete(array("product_id" => idClean($id)),true);

                $description = $this->db->get_where("mt_product_archive_description",array(
                    "product_id" => $id
                ),1,0)->row();
                if(count($description) > 0){
                    $this->DATA->table = "mt_product_archive_description";
                    $this->DATA->_delete(array("product_description_id" => $description->product_description_id),true);
                }

                $detail = $this->db->get_where("mt_product_archive_detail",array(
                    "product_id" => $id
                ),1,0)->row();
                if(count($detail) > 0){
                    $this->DATA->table = "mt_product_archive_detail";
                    $this->DATA->_delete(array("product_detail_id"  => $detail->product_detail_id),true);
                }

                $image = $this->db->get_where("mt_product_archive_image",array(
                    "product_id" => $id
                ))->result();
                if(count($image) > 0){
                    foreach ($image as $key => $value) {
                        $this->DATA->table = "mt_product_archive_image";
                        $this->_delte_old_files_without_thumb(
                            array(
                                'field' => 'image_filename',
                                'par'   => array('image_id' => $value->image_id)
                        ));
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

}
