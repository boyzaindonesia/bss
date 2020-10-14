<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class product_not_sale extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        $this->_set_action(array("view","edit","delete"),"ITEM");
        $this->_set_title( 'Produk Tidak Dijual' );
        $this->DATA->table = "mt_product";
        $this->folder_view = "product/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_product","M");
        $this->breadcrumb[] = array(
            "title"     => "Produk Tidak Dijual",
            "url"       => $this->own_link
        );

        $this->cat_search = array(
            ''                                              => 'Semua Pencarian...',
            'mt_product.product_name'                       => 'Judul',
			'mt_product.product_name_simple'				=> 'Nama Singkat',
            'mt_product.product_code'                       => 'Kode',
            'mt_product_category.product_category_title'    => 'Kategori',
        );

        $this->user_id  = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id = get_user_store($this->user_id);
    }

    function _reset(){
        $this->jCfg['search'] = array(
            'class'     => $this->_getClass(),
            'name'      => 'product_not_sale',
            'date_start'=> '',
            'date_end'  => '',
            'status'    => '',
            'order_by'  => 'mt_product.product_date_push',
            'order_dir' => 'desc',
            'filter'    => '40',
            'colum'     => '',
            'keyword'   => ''
        );
        $this->_releaseSession();
    }

    function index(){
        $hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
        if($hal != 'product_not_sale'){
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
            "product_show_id"     => "1",
            "product_status_id"   => "3",
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
            "base_url"  => $this->own_link.'/index'
        ));

        $data['url'] = base_url()."admin/product";
        $data['url_parent'] = base_url()."admin/product";

        $this->_v($this->folder_view.$this->prefix_view,$data);
    }

}
