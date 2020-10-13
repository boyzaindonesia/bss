<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class log extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        $this->_set_action(array("view","delete"),"ITEM");
        $this->_set_title( 'Log Aktifitas' );
        $this->DATA->table = "mt_app_log";
        $this->folder_view = "config/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_log","M");
        $this->breadcrumb[] = array(
            "title"     => "Log Aktifitas",
            "url"       => $this->own_link
        );

        $this->cat_search = array(
            ''                              => 'Semua Pencarian...',
            'mt_app_log.log_title'          => 'Judul',
            'mt_app_log.log_desc'           => 'Deskripsi',
            'mt_app_log.log_user_name'      => 'Username',
            'mt_app_log.log_class'          => 'Class',
            'mt_app_log.log_function'       => 'Function',
            'mt_app_log.log_ip'             => 'Ip Address',
            'mt_app_log.log_user_agent'     => 'User Agent',
            'mt_product.product_name'       => 'Nama Produk',
            'mt_product.product_name_simple'=> 'Nama Produk Simple',
            'mt_product.product_code'       => 'Kode Produk',
            'mt_app_user.user_name'         => 'Username',
            'mt_app_user.user_fullname'     => 'User Fullname'
        );
    }

    function _reset(){
        $this->jCfg['search'] = array(
            'class'     => $this->_getClass(),
            'name'      => 'log',
            'date_start'=> '',
            'date_end'  => '',
            'status'    => '',
            'order_by'  => 'log_date',
            'order_dir' => 'desc',
            'filter'    => '25',
            'colum'     => '',
            'keyword'   => '',
            'log_user_id'=> '1',
            'log_status'=> '1'
        );
        $this->_releaseSession();
    }

    function index(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'log'){
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

        if(!isset($this->jCfg['search']['log_user_id'])||$this->jCfg['search']['log_user_id']==''){
            $this->jCfg['search']['log_user_id'] = "";
            $this->_releaseSession();
        }
        if(isset($_POST['log_user_id'])){
            if($_POST['log_user_id'] == ""){
                $this->jCfg['search']['log_user_id'] = "";
            } else {
                $this->jCfg['search']['log_user_id'] = $_POST['log_user_id'];
            }
            $this->_releaseSession();
        }
        $this->log_user_id = $this->jCfg['search']['log_user_id'];

        if(!isset($this->jCfg['search']['log_type'])||$this->jCfg['search']['log_type']==''){
            $this->jCfg['search']['log_type'] = "";
            $this->_releaseSession();
        }
        if(isset($_POST['log_type'])){
            if($_POST['log_type'] == ""){
                $this->jCfg['search']['log_type'] = "";
            } else {
                $this->jCfg['search']['log_type'] = $_POST['log_type'];
            }
            $this->_releaseSession();
        }
        $this->log_type = $this->jCfg['search']['log_type'];

        if(!isset($this->jCfg['search']['log_title_id'])||$this->jCfg['search']['log_title_id']==''){
            $this->jCfg['search']['log_title_id'] = "";
            $this->_releaseSession();
        }
        if(isset($_POST['log_title_id'])){
            if($_POST['log_title_id'] == ""){
                $this->jCfg['search']['log_title_id'] = "";
            } else {
                $this->jCfg['search']['log_title_id'] = $_POST['log_title_id'];
            }
            $this->_releaseSession();
        }
        $this->log_title_id = $this->jCfg['search']['log_title_id'];

        $this->per_page = $this->jCfg['search']['filter'];
        $par_filter = array(
            "log_type"    => $this->log_type,
            "log_title_id"=> $this->log_title_id,
            "log_user_id" => $this->log_user_id,
            "offset"      => $this->uri->segment($this->uri_segment),
            "limit"       => $this->per_page,
            "param"       => $this->cat_search
        );
        $this->data_table = $this->M->data($par_filter);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/index'
        ));

        $data['url'] = base_url()."admin/log";

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
                'log_id'    => $id
            ));
            if(empty($this->data_form->log_id)){
                redirect($this->own_link."?msg=".urlencode('Data Log tidak ditemukan')."&type_msg=error");
            }

            $this->_v($this->folder_view.$this->prefix_view."_view");
        }else{
            redirect($this->own_link);
        }
    }

    function delete($id=''){
        $id = explode("-", $id);
        $id = dbClean(trim($id[0]));
        if(trim($id) != ''){
            $this->db->update("mt_app_log",array("log_istrash"=>1),array("log_id"=>$id));
        }
        redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
    }

    function empty_trash(){
        $data = $this->db->get_where("mt_app_log",array(
            "log_istrash"   => 1
        ))->result();
        foreach($data as $r){
            $id = $r->log_id;
            $this->DATA->_delete(array("log_id" => idClean($id)),true);
        }
        redirect($this->own_link."?msg=".urlencode('Empty trash data success')."&type_msg=success");
    }

}
