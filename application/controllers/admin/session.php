<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class session extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        $this->_set_action(array("view","delete"),"ITEM");
        $this->_set_title( 'Session' );
        $this->DATA->table = "mt_app_sessions";
        $this->folder_view = "config/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_session","M");
        $this->breadcrumb[] = array(
            "title"     => "Session",
            "url"       => $this->own_link
        );

        $this->cat_search = array(
            ''               => 'Semua Pencarian...',
            'ip_address'     => 'Ip Address',
            'user_agent'     => 'User Agent'
        );
    }

    function _reset(){
        $this->jCfg['search'] = array(
            'class'     => $this->_getClass(),
            'name'      => 'session',
            'date_start'=> '',
            'date_end'  => '',
            'status'    => '',
            'order_by'  => 'session_id',
            'order_dir' => 'desc',
            'filter'    => '25',
            'colum'     => '',
            'keyword'   => ''
        );
        $this->_releaseSession();
    }

    function index(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'session'){
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
            "offset"      => $this->uri->segment($this->uri_segment),
            "limit"       => $this->per_page,
            "param"       => $this->cat_search
        );
        $this->data_table = $this->M->data($par_filter);
        $data = $this->_data(array(
            "base_url"  => $this->own_link.'/index'
        ));

        $data['url'] = base_url()."admin/session";

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
                'session_id'    => $id
            ));
            if(empty($this->data_form->session_id)){
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
            $this->DATA->_delete(array("session_id" => idClean($id)),true);
        }
        redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
    }

    function clear_session(){
        $m = $this->db->get_where("mt_app_sessions",array(
            "session_id !="   => '0'
        ))->result();
        foreach ($m as $key => $value) {
            $id = $value->session_id;
            $this->DATA->_delete(array("session_id" => idClean($id)),true);
        }
        redirect($this->own_link."?msg=".urlencode('Delete data success')."&type_msg=success");
    }

}
