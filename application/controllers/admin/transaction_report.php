<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class transaction_report extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        // $this->DATA->table="mt_orders";
        // $this->folder_view = "orders/";
        $this->prefix_view = strtolower($this->_getClass());
        $this->load->model("mdl_report","MR");

        $this->user_id          = isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
        $this->store_id         = get_user_store($this->user_id);
        // $this->detail_store     = get_detail_store($this->store_id);
        // $this->store_name       = $this->detail_store->store_name;
        // $this->store_phone      = $this->detail_store->store_phone;
        // $this->store_product    = $this->detail_store->store_product;
    }

    function ajax_get_orders_report(){
        $data   = array();
        $result = array();
        $data['err']    = true;
        $data['msg']    = '';
        $data['result'] = '';

        // $date_start = "2018-09-28";
        // $date_end   = "2018-09-28";
        // $par_filter = array(
        //     "date_start"    => $date_start,
        //     "date_end"      => $date_end
        // );

        if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'getdata' ){
            $date_start = dbClean(trim($_POST['date_start']));
            $date_end   = dbClean(trim($_POST['date_end']));
            if(trim($date_start)!='' && trim($date_end)!=''){
                $par_filter = array(
                    "date_start"    => convDatepickerDec($date_start),
                    "date_end"      => convDatepickerDec($date_end)
                );

                $data = $this->MR->data_report_orders($par_filter);
                $new_result = $this->MR->layout_report_new_orders($data['result']);
                $data['result']  = $new_result;
            }
        }
        die(json_encode($data));
        exit();
    }



}
