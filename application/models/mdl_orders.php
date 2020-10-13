<?php
class mdl_orders extends CI_Model{

    var $tabel = 'mt_orders';

    function __construct(){
        parent::__construct();
    }

    function data_orders($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */

        if(isset($p['get_all']) && $p['get_all'] == TRUE){
            if(trim($p['colum'])=="" && trim($p['keyword']) != ""){
                $this->db->select('mt_orders.*, mt_orders_shipping.*');
                $this->db->join("mt_orders","mt_orders.orders_id = mt_orders_shipping.orders_id",'left');
            } else {
                $this->db->select('mt_orders.*');
            }
        } else {
            $this->db->select('mt_orders.*, mt_orders_shipping.*');
            $this->db->join("mt_orders_shipping","mt_orders_shipping.orders_id = mt_orders.orders_id",'left');
            // $this->db->join("mt_orders_payment","mt_orders_payment.orders_id = mt_orders.orders_id",'left');
        }

        /* where or like conditions */
        if( isset($p['orders_id']) && $p['orders_id'] != "" ){
            $this->db->where("mt_orders.orders_id",$p['orders_id']);
        }

        if( isset($p['orders_istrash']) && $p['orders_istrash'] != ""){
            $this->db->where("mt_orders.orders_istrash",$p['orders_istrash']);
        } else {
            $this->db->where("mt_orders.orders_istrash",0);
        }

        if( isset($p['store_id']) && $p['store_id'] != "" ){
            $this->db->where("mt_orders.store_id",$p['store_id']);
        } else {
            $this->db->where("mt_orders.store_id",1);
        }

        if( isset($p['orders_print']) && $p['orders_print'] != "" ){
            $this->db->where("mt_orders.orders_print",$p['orders_print']);
        }

        if( isset($p['orders_source_invoice']) && $p['orders_source_invoice'] != "" ){
            $this->db->where("mt_orders.orders_source_invoice",$p['orders_source_invoice']);
        }

        if( isset($p['orders_status']) && $p['orders_status'] != "" ){
            $arrStatus = array();
            $exp = explode(',', $p['orders_status']);
            foreach ($exp as $n) {
                $arrStatus[] = $n;
            }
            $this->db->where_in("mt_orders.orders_status", $arrStatus);
            // $this->db->where("mt_orders.orders_status",$p['orders_status']);
        }

        if( isset($p['orders_source_id']) && $p['orders_source_id'] != "" ){
            $arrSource = array();
            $exp = explode(',', $p['orders_source_id']);
            foreach ($exp as $n) {
                $arrSource[] = $n;
            }
            $this->db->where_in("mt_orders.orders_source_id", $arrSource);
            // $this->db->where("mt_orders.orders_source_id",$p['orders_source_id']);
            // $this->db->where_in("mt_orders.orders_source_id", array('1'));
        }
        if( isset($p['orders_courier_id']) && $p['orders_courier_id'] != "" ){
            $arrCourier = array();
            $arrCourier[] = $p['orders_courier_id'];
            $exp = explode(',', $p['orders_courier_id']);
            foreach ($exp as $n) {
                $arrCourier[] = $n;
            }
            $this->db->where_in("mt_orders.orders_courier_id", $arrCourier);
        }
        if( isset($p['orders_claim_status']) && $p['orders_claim_status'] != "" ){
            $arrClaim = array();
            $exp = explode(',', $p['orders_claim_status']);
            foreach ($exp as $n) {
                $arrClaim[] = $n;
            }
            $this->db->where_in("mt_orders.orders_claim_status", $arrClaim);
        }

        if( isset($p['orders_product_detail']) && $p['orders_product_detail'] != "" ){
            $this->db->where("mt_orders.orders_product_detail",$p['orders_product_detail']);
        }

        if( trim($p['date_end']) != "" ){
            $this->db->where("( mt_orders.orders_date <= '".$p['date_end']." 23:59:59' )");
        }

        if( trim($p['date_start'])!="" ){
            $this->db->where("( mt_orders.orders_date >= '".$p['date_start']." 00:00:00' )");
        }
        // dont modified....
        if( trim($p['colum'])=="" && trim($p['keyword']) != "" ){
            $str_like = "( ";
            $i=0;
            foreach ($p['param'] as $key => $value) {
                if($key != ""){
                    $str_like .= $i!=0?"OR":"";
                    $str_like .=" ".$key." LIKE '%".$p['keyword']."%' ";
                    $i++;
                }
            }
            $str_like .= " ) ";
            $this->db->where($str_like);
        }
        if( trim($p['colum'])!="" && trim($p['keyword']) != "" ){
            $this->db->like($p['colum'],$p['keyword']);
        }
        if($count==FALSE){
            if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
                $p['offset'] = empty($p['offset'])?0:$p['offset'];
                $this->db->limit($p['limit'],$p['offset']);
            }
        }

        if( (isset($p['order_by']) && $p['order_by']!="") && (isset($p['order_dir']) && $p['order_dir']!="") ){
            $order_by  = $p['order_by'];
            $order_dir = $p['order_dir'];
            $this->db->order_by($order_by,$order_dir);
        } else {
            $this->db->order_by('mt_orders.orders_date','desc');
        }

        if( trim($p['colum'])=="" && trim($p['keyword']) != "" ){
            $qry = $this->db->get('mt_orders_shipping');
        } else {
            $qry = $this->db->get('mt_orders');
        }

        if($count==FALSE){
            $total = $this->data_orders($p,TRUE);

            $type_result = "fullresult";
            if(isset($p['type_result']) && $p['type_result'] != ""){
                $type_result = $p['type_result'];
            }
            switch ($p['type_result']) {
                case 'list':
                    $isFull     = FALSE;
                    $isKey      = array("store_id","orders_id","orders_code","orders_invoice","orders_date","orders_status","orders_print","orders_source_id","orders_source_name","orders_source_invoice","orders_source_price","orders_source_fee","orders_price_buy_total","orders_price_product","orders_price_shipping","orders_price_debet_ship","orders_price_insurance","orders_price_ppn","orders_price_grand_total","orders_voucher_price","orders_claim_price","orders_price_return","orders_noted","orders_courier_id","orders_courier_name","orders_shipping_resi","orders_ship_name","orders_ship_phone","orders_shipping_name","orders_shipping_email","orders_shipping_address","orders_shipping_city","orders_shipping_province","orders_shipping_postal_code","orders_shipping_phone","orders_shipping_weight","orders_product_category_title","orders_shipping_dropship","orders_detail","orders_total_qty","orders_claim_status","orders_claim_price","orders_claim_date");
                    break;
                case 'list_app':
                    $isFull     = FALSE;
                    $isKey      = array("store_id","orders_id","orders_code","orders_invoice","orders_date","orders_status","orders_print","orders_source_id","orders_source_name","orders_source_invoice","orders_source_price","orders_source_fee","orders_price_buy_total","orders_price_product","orders_price_shipping","orders_price_debet_ship","orders_price_insurance","orders_price_ppn","orders_price_grand_total","orders_voucher_price","orders_claim_price","orders_price_return","orders_noted","orders_courier_id","orders_courier_name","orders_shipping_resi","orders_ship_name","orders_ship_phone","orders_shipping_name","orders_shipping_email","orders_shipping_address","orders_shipping_city","orders_shipping_province","orders_shipping_postal_code","orders_shipping_phone","orders_shipping_weight","orders_product_category_title","orders_shipping_dropship","orders_detail","orders_total_qty");
                    break;
                case 'list_app_simple':
                    $isFull     = FALSE;
                    $isKey      = array("store_id","orders_id","orders_date","orders_source_name","orders_source_invoice","orders_noted","orders_courier_name","orders_shipping_name");
                    break;
                default:
                    $isFull     = TRUE;
                    $isKey      = array();
                    break;
            }

            $result = array();
            $convToJSON = array('orders_detail_item','orders_timestamp_desc');
            foreach ($qry->result() as $key => $val) {
                foreach ($val as $key2 => $val2) {
                    if(in_array($key2, $isKey) || $isFull){
                        $result[$key]->$key2 = $val2;
                        if(in_array($key2, $convToJSON) && $val2 != ''){
                            $result[$key]->$key2 = json_decode($val2);
                        }

                        if(isset($p['get_all']) && $p['get_all'] == TRUE){
                            if(trim($p['colum'])!=""|| trim($p['keyword']) == ""){
                                $m3 = $this->db->get_where("mt_orders_shipping",array(
                                    "orders_id" => $val->orders_id
                                ),1,0)->row();
                                foreach ($m3 as $key3 => $val3) {
                                    if(in_array($key3, $isKey) || $isFull){
                                        $result[$key]->$key3 = $val3;
                                        if(in_array($key3, $convToJSON) && $val3 != ''){
                                            $result[$key]->$key3 = json_decode($val3);
                                        }
                                    }
                                }
                            }
                        }


                        $total_qty    = 0;
                        if(in_array("orders_detail", $isKey) || $isFull){
                            $arrDetail    = array();
                            $ordersDetail = $this->db->order_by("orders_detail_id","asc")->get_where("mt_orders_detail",array(
                                "orders_id" => $val->orders_id
                            ))->result();
                            foreach ($ordersDetail as $key4 => $val4) {
                                $total_qty += $val4->orders_detail_qty;
                                $arrDetail[$key4]->product_code        = get_product_code($val4->product_id);
                                $arrDetail[$key4]->product_name_simple = get_product_name_simple($val4->product_id);
                                foreach ($val4 as $key5 => $val5) {
                                    $arrDetail[$key4]->$key5 = $val5;
                                    if(in_array($key5, $convToJSON) && $val5 != ''){
                                        $arrDetail[$key4]->$key5 = json_decode($val5);
                                    }
                                }
                            }
                            $result[$key]->orders_detail    = $arrDetail;
                        }
                        if(in_array("orders_total_qty", $isKey) || $isFull){
                            $result[$key]->orders_total_qty = $total_qty;
                        }
                        if(in_array("orders_source_name", $isKey) || $isFull){
                            $result[$key]->orders_source_name = get_orders_source($val->orders_source_id)->orders_source_name;
                        }
                        if(in_array("orders_courier_name", $isKey) || $isFull){
                            $courier = get_orders_courier($val->orders_courier_id);
                            $result[$key]->orders_courier_name    = $courier->orders_courier_name;
                            $result[$key]->orders_courier_service = $courier->orders_courier_service;
                        }
                        if(in_array("orders_ship_name", $isKey) || $isFull){
                            if($val->orders_ship_name == ""){
                                $detail_store     = get_detail_store($val->store_id);
                                $store_name       = $detail_store->store_name;
                                $store_phone      = $detail_store->store_phone;
                                $result[$key]->orders_ship_name  = $store_name;
                                $result[$key]->orders_ship_phone = $store_phone;
                            }
                        }
                    }
                }
            }

            return array(
                    "data"  => $result,
                    "total" => $total
                );
        }else{
            return $qry->num_rows();
        }
    }

    function data_orders_backup($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */

        if(isset($p['get_all']) && $p['get_all'] == TRUE){
            if(trim($p['colum'])=="" && trim($p['keyword']) != ""){
                $this->db->select('mt_orders_bk.*, mt_orders_shipping_bk.*');
                $this->db->join("mt_orders_bk","mt_orders_bk.orders_id = mt_orders_shipping_bk.orders_id",'left');
            } else {
                $this->db->select('mt_orders_bk.*');
            }
        } else {
            $this->db->select('mt_orders_bk.*, mt_orders_shipping_bk.*');
            $this->db->join("mt_orders_shipping_bk","mt_orders_shipping_bk.orders_id = mt_orders_bk.orders_id",'left');
            // $this->db->join("mt_orders_payment_bk","mt_orders_payment_bk.orders_id = mt_orders_bk.orders_id",'left');
        }

        /* where or like conditions */
        if( isset($p['orders_id']) && $p['orders_id'] != "" ){
            $this->db->where("mt_orders_bk.orders_id",$p['orders_id']);
        }

        if( isset($p['orders_istrash']) && $p['orders_istrash'] != ""){
            $this->db->where("mt_orders_bk.orders_istrash",$p['orders_istrash']);
        } else {
            $this->db->where("mt_orders_bk.orders_istrash",0);
        }

        if( isset($p['store_id']) && $p['store_id'] != "" ){
            $this->db->where("mt_orders_bk.store_id",$p['store_id']);
        } else {
            $this->db->where("mt_orders_bk.store_id",1);
        }

        if( isset($p['orders_print']) && $p['orders_print'] != "" ){
            $this->db->where("mt_orders_bk.orders_print",$p['orders_print']);
        }

        if( isset($p['orders_source_invoice']) && $p['orders_source_invoice'] != "" ){
            $this->db->where("mt_orders_bk.orders_source_invoice",$p['orders_source_invoice']);
        }

        if( isset($p['orders_status']) && $p['orders_status'] != "" ){
            $arrStatus = array();
            $exp = explode(',', $p['orders_status']);
            foreach ($exp as $n) {
                $arrStatus[] = $n;
            }
            $this->db->where_in("mt_orders_bk.orders_status", $arrStatus);
            // $this->db->where("mt_orders_bk.orders_status",$p['orders_status']);
        }

        if( isset($p['orders_source_id']) && $p['orders_source_id'] != "" ){
            $arrSource = array();
            $exp = explode(',', $p['orders_source_id']);
            foreach ($exp as $n) {
                $arrSource[] = $n;
            }
            $this->db->where_in("mt_orders_bk.orders_source_id", $arrSource);
            // $this->db->where("mt_orders_bk.orders_source_id",$p['orders_source_id']);
            // $this->db->where_in("mt_orders_bk.orders_source_id", array('1'));
        }
        if( isset($p['orders_courier_id']) && $p['orders_courier_id'] != "" ){
            $arrCourier = array();
            $arrCourier[] = $p['orders_courier_id'];
            $exp = explode(',', $p['orders_courier_id']);
            foreach ($exp as $n) {
                $arrCourier[] = $n;
            }
            $this->db->where_in("mt_orders_bk.orders_courier_id", $arrCourier);
        }
        if( isset($p['orders_claim_status']) && $p['orders_claim_status'] != "" ){
            $arrClaim = array();
            $exp = explode(',', $p['orders_claim_status']);
            foreach ($exp as $n) {
                $arrClaim[] = $n;
            }
            $this->db->where_in("mt_orders_bk.orders_claim_status", $arrClaim);
        }

        if( isset($p['orders_product_detail']) && $p['orders_product_detail'] != "" ){
            $this->db->where("mt_orders_bk.orders_product_detail",$p['orders_product_detail']);
        }

        if( trim($p['date_end']) != "" ){
            $this->db->where("( mt_orders_bk.orders_date <= '".$p['date_end']." 23:59:59' )");
        }

        if( trim($p['date_start'])!="" ){
            $this->db->where("( mt_orders_bk.orders_date >= '".$p['date_start']." 00:00:00' )");
        }
        // dont modified....
        if( trim($p['colum'])=="" && trim($p['keyword']) != "" ){
            $str_like = "( ";
            $i=0;
            foreach ($p['param'] as $key => $value) {
                if($key != ""){
                    $str_like .= $i!=0?"OR":"";
                    $str_like .=" ".$key." LIKE '%".$p['keyword']."%' ";
                    $i++;
                }
            }
            $str_like .= " ) ";
            $this->db->where($str_like);
        }
        if( trim($p['colum'])!="" && trim($p['keyword']) != "" ){
            $this->db->like($p['colum'],$p['keyword']);
        }
        if($count==FALSE){
            if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
                $p['offset'] = empty($p['offset'])?0:$p['offset'];
                $this->db->limit($p['limit'],$p['offset']);
            }
        }

        if( (isset($p['order_by']) && $p['order_by']!="") && (isset($p['order_dir']) && $p['order_dir']!="") ){
            $order_by  = $p['order_by'];
            $order_dir = $p['order_dir'];
            $this->db->order_by($order_by,$order_dir);
        } else {
            $this->db->order_by('mt_orders_bk.orders_date','desc');
        }

        if( trim($p['colum'])=="" && trim($p['keyword']) != "" ){
            $qry = $this->db->get('mt_orders_shipping_bk');
        } else {
            $qry = $this->db->get('mt_orders_bk');
        }

        if($count==FALSE){
            $total = $this->data_orders_backup($p,TRUE);

            $type_result = "fullresult";
            if(isset($p['type_result']) && $p['type_result'] != ""){
                $type_result = $p['type_result'];
            }
            switch ($p['type_result']) {
                case 'list':
                    $isFull     = FALSE;
                    $isKey      = array("store_id","orders_id","orders_code","orders_invoice","orders_date","orders_status","orders_print","orders_source_id","orders_source_name","orders_source_invoice","orders_source_price","orders_source_fee","orders_price_buy_total","orders_price_product","orders_price_shipping","orders_price_debet_ship","orders_price_insurance","orders_price_ppn","orders_price_grand_total","orders_voucher_price","orders_claim_price","orders_price_return","orders_noted","orders_courier_id","orders_courier_name","orders_shipping_resi","orders_ship_name","orders_ship_phone","orders_shipping_name","orders_shipping_email","orders_shipping_address","orders_shipping_city","orders_shipping_province","orders_shipping_postal_code","orders_shipping_phone","orders_shipping_weight","orders_product_category_title","orders_shipping_dropship","orders_detail","orders_total_qty","orders_claim_status","orders_claim_price","orders_claim_date");
                    break;
                default:
                    $isFull     = TRUE;
                    $isKey      = array();
                    break;
            }

            $result = array();
            $convToJSON = array('orders_detail_item','orders_timestamp_desc');
            foreach ($qry->result() as $key => $val) {
                foreach ($val as $key2 => $val2) {
                    if(in_array($key2, $isKey) || $isFull){
                        $result[$key]->$key2 = $val2;
                        if(in_array($key2, $convToJSON) && $val2 != ''){
                            $result[$key]->$key2 = json_decode($val2);
                        }

                        if(isset($p['get_all']) && $p['get_all'] == TRUE){
                            if(trim($p['colum'])!=""|| trim($p['keyword']) == ""){
                                $m3 = $this->db->get_where("mt_orders_shipping_bk",array(
                                    "orders_id" => $val->orders_id
                                ),1,0)->row();
                                foreach ($m3 as $key3 => $val3) {
                                    if(in_array($key3, $isKey) || $isFull){
                                        $result[$key]->$key3 = $val3;
                                        if(in_array($key3, $convToJSON) && $val3 != ''){
                                            $result[$key]->$key3 = json_decode($val3);
                                        }
                                    }
                                }
                            }
                        }


                        $total_qty    = 0;
                        if(in_array("orders_detail", $isKey) || $isFull){
                            $arrDetail    = array();
                            $ordersDetail = $this->db->order_by("orders_detail_id","asc")->get_where("mt_orders_detail_bk",array(
                                "orders_id" => $val->orders_id
                            ))->result();
                            foreach ($ordersDetail as $key4 => $val4) {
                                $total_qty += $val4->orders_detail_qty;
                                $arrDetail[$key4]->product_code        = get_product_code($val4->product_id);
                                $arrDetail[$key4]->product_name_simple = get_product_name_simple($val4->product_id);
                                foreach ($val4 as $key5 => $val5) {
                                    $arrDetail[$key4]->$key5 = $val5;
                                    if(in_array($key5, $convToJSON) && $val5 != ''){
                                        $arrDetail[$key4]->$key5 = json_decode($val5);
                                    }
                                }
                            }
                            $result[$key]->orders_detail    = $arrDetail;
                        }
                        if(in_array("orders_total_qty", $isKey) || $isFull){
                            $result[$key]->orders_total_qty = $total_qty;
                        }
                        if(in_array("orders_source_name", $isKey) || $isFull){
                            $result[$key]->orders_source_name = get_orders_source($val->orders_source_id)->orders_source_name;
                        }
                        if(in_array("orders_courier_name", $isKey) || $isFull){
                            $courier = get_orders_courier($val->orders_courier_id);
                            $result[$key]->orders_courier_name    = $courier->orders_courier_name;
                            $result[$key]->orders_courier_service = $courier->orders_courier_service;
                        }
                        if(in_array("orders_ship_name", $isKey) || $isFull){
                            if($val->orders_ship_name == ""){
                                $detail_store     = get_detail_store($val->store_id);
                                $store_name       = $detail_store->store_name;
                                $store_phone      = $detail_store->store_phone;
                                $result[$key]->orders_ship_name  = $store_name;
                                $result[$key]->orders_ship_phone = $store_phone;
                            }
                        }
                    }
                }
            }

            return array(
                    "data"  => $result,
                    "total" => $total
                );
        }else{
            return $qry->num_rows();
        }
    }

    function data_marketplace_payment($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */

        $this->db->select('mt_marketplace_payment.*, mt_marketplace_payment_detail.*');
        $this->db->join("mt_marketplace_payment_detail","mt_marketplace_payment_detail.marketplace_payment_id = mt_marketplace_payment.marketplace_payment_id",'left');


        /* where or like conditions */
        if( isset($p['marketplace_payment_detail_id']) ){
            $this->db->where("mt_marketplace_payment_detail.marketplace_payment_detail_id",$p['marketplace_payment_detail_id']);
        } else {
            $this->db->where("mt_marketplace_payment_detail.marketplace_payment_detail_id !=",0);
        }

        if( isset($p['store_id']) ){
            $this->db->where("mt_marketplace_payment_detail.store_id",$p['store_id']);
        } else {
            $this->db->where("mt_marketplace_payment_detail.store_id",1);
        }

        if( isset($p['orders_payment_status']) ){
            $this->db->where("mt_marketplace_payment_detail.orders_payment_status",$p['orders_payment_status']);
        }

        if( isset($p['orders_source_id']) && $p['orders_source_id'] != NULL ){
            $arrSource = array();
            $exp = explode(',', $p['orders_source_id']);
            foreach ($exp as $n) {
                $arrSource[] = $n;
            }
            $this->db->where_in("mt_marketplace_payment_detail.orders_source_id", $arrSource);
            // $this->db->where("mt_orders.orders_source_id",$p['orders_source_id']);
            // $this->db->where_in("mt_orders.orders_source_id", array('1'));
        }
        if( isset($p['orders_courier_id']) && $p['orders_courier_id'] != NULL ){
            $arrCourier = array();
            $arrCourier[] = $p['orders_courier_id'];
            $exp = explode(',', $p['orders_courier_id']);
            foreach ($exp as $n) {
                $arrCourier[] = $n;
            }
            $this->db->where_in("mt_marketplace_payment_detail.orders_shipping_courier", $arrCourier);
        }

        if( trim($this->jCfg['search']['date_end']) != "" ){
            //debugCode($this->jCfg['search']['date_end']);
            $this->db->where("( mt_marketplace_payment_detail.orders_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");

        }

        if( trim($this->jCfg['search']['date_start'])!="" ){
            //debugCode($this->jCfg['search']['date_end']);
            $this->db->where("( mt_marketplace_payment_detail.orders_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");

        }
        // dont modified....
        if( trim($this->jCfg['search']['colum'])=="" && trim($this->jCfg['search']['keyword']) != "" ){
            $str_like = "( ";
            $i=0;
            foreach ($p['param'] as $key => $value) {
                if($key != ""){
                    $str_like .= $i!=0?"OR":"";
                    $str_like .=" ".$key." LIKE '%".$this->jCfg['search']['keyword']."%' ";
                    $i++;
                }
            }
            $str_like .= " ) ";
            $this->db->where($str_like);
        }
        if( trim($this->jCfg['search']['colum'])!="" && trim($this->jCfg['search']['keyword']) != "" ){
            $this->db->like($this->jCfg['search']['colum'],$this->jCfg['search']['keyword']);
        }
        if($count==FALSE){
            if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
                $p['offset'] = empty($p['offset'])?0:$p['offset'];
                $this->db->limit($p['limit'],$p['offset']);
            }
        }

        if(trim($this->jCfg['search']['order_by'])!=""){
            $order_by  = $this->jCfg['search']['order_by'];
            $order_dir = $this->jCfg['search']['order_dir'];
            $this->db->order_by($order_by,$order_dir);
        } else {
            $this->db->order_by('mt_marketplace_payment_detail.orders_date','desc');
        }

        $qry = $this->db->get('mt_marketplace_payment');
        if($count==FALSE){
            $total = $this->data_marketplace_payment($p,TRUE);
            return array(
                    "data"  => $qry->result(),
                    "total" => $total
                );
        }else{
            return $qry->num_rows();
        }
    }

    function data_orders_front($p=array()){
        $where = '';
        /* where or like conditions */
        if(isset($p['orders_id']) && $p['orders_id'] != ''){
            $where .= "AND mt_orders.orders_id ".check_isnot($p['orders_id'])." ";
        }
        if(isset($p['member_type']) && $p['member_type'] != ''){
            $where .= "AND mt_orders.member_type ".check_isnot($p['member_type'])." ";
        }
        if(isset($p['member_id']) && $p['member_id'] != ''){
            $where .= "AND mt_orders.member_id ".check_isnot($p['member_id'])." ";
        }
        if(isset($p['orders_status']) && $p['orders_status'] != ''){
            $where .= "AND mt_orders.orders_status ".check_isnot($p['orders_status'])." ";
        }
        if(isset($p['orders_source_id']) && $p['orders_source_id'] != ''){
            $where .= "AND mt_orders.orders_source_id ".check_isnot($p['orders_source_id'])." ";
        }
        if(isset($p['orders_source_invoice']) && $p['orders_source_invoice'] != ''){
            $where .= "AND mt_orders.orders_source_invoice ".check_isnot($p['orders_source_invoice'])." ";
        }
        if(isset($p['product_id']) && $p['product_id'] != ''){
            $where .= "AND mt_orders_detail.product_id ".check_isnot($p['product_id'])." ";
        }
        if(isset($p['orders_shipping_name']) && $p['orders_shipping_name'] != ''){
            $where .= "AND mt_orders_shipping.orders_shipping_name ".check_isnot($p['orders_shipping_name'])." ";
        }
        if(isset($p['orders_shipping_city']) && $p['orders_shipping_city'] != ''){
            $where .= "AND mt_orders_shipping.orders_shipping_city ".check_isnot($p['orders_shipping_city'])." ";
        }
        if(isset($p['orders_shipping_city']) && $p['orders_shipping_city'] != ''){
            $where .= "AND mt_orders_shipping.orders_shipping_city ".check_isnot($p['orders_shipping_city'])." ";
        }
        if(isset($p['orders_shipping_province']) && $p['orders_shipping_province'] != ''){
            $where .= "AND mt_orders_shipping.orders_shipping_province ".check_isnot($p['orders_shipping_province'])." ";
        }
        if(isset($p['orders_shipping_phone']) && $p['orders_shipping_phone'] != ''){
            $where .= "AND mt_orders_shipping.orders_shipping_phone ".check_isnot($p['orders_shipping_phone'])." ";
        }
        if(isset($p['date_end']) && $p['date_end'] != ''){
            $where .= "AND ( mt_orders.orders_date <= '".$p['date_end']." 23:59:59' )";
        }
        if(isset($p['date_start']) && $p['date_start'] != ''){
            $where .= "AND ( mt_orders.orders_date >= '".$p['date_start']." 00:00:00' )";
        }

        if( isset($p['keyword']) && trim($p['keyword'])!=""){
            $where .= " AND ( mt_orders.orders_invoice like '%".pTxt($p['keyword'])."%'
                             OR mt_orders_shipping.orders_shipping_name like '%".pTxt($p['keyword'])."%'
                             OR mt_orders_shipping.orders_shipping_address like '%".pTxt($p['keyword'])."%'
                             OR mt_orders_shipping.orders_shipping_resi like '%".pTxt($p['keyword'])."%'
                           )";
        }

        $order_by = 'mt_orders.orders_date desc';
        if(trim($p['order_by']) != ""){
            $p['order_dir'] = empty($p['order_dir'])?'desc':$p['order_dir'];
            $order_by = $p['order_by']." ".$p['order_dir'];
            if($p['order_by']=='random'){ $order_by = 'rand()'; }
        }

        $limit = '';
        if( isset($p['limit']) && isset($p['offset']) ){
            $offset = empty($p['offset'])?0:$p['offset'];
            $limit  = " LIMIT ".$offset.",".$p['limit']." ";
        }

            // LEFT JOIN mt_orders_detail ON mt_orders_detail.orders_id = mt_orders.orders_id
        $sql = "
            select
                SQL_CALC_FOUND_ROWS *, mt_orders.*, mt_orders_detail.*, mt_orders_info.*, mt_orders_payment.*, mt_orders_shipping.*, mt_orders_timestamp.*, mt_product.*, mt_product.url as url_product, mt_product_image.*
            FROM mt_orders
            LEFT JOIN mt_orders_detail ON mt_orders_detail.orders_detail_id =
            (SELECT mt_orders_detail.orders_detail_id FROM mt_orders_detail WHERE mt_orders_detail.orders_id = mt_orders.orders_id ORDER BY mt_orders_detail.orders_detail_id ASC LIMIT 1)
            LEFT JOIN mt_orders_info ON mt_orders_info.orders_id = mt_orders.orders_id
            LEFT JOIN mt_orders_payment ON mt_orders_payment.orders_id = mt_orders.orders_id
            LEFT JOIN mt_orders_shipping ON mt_orders_shipping.orders_id = mt_orders.orders_id
            LEFT JOIN mt_orders_timestamp ON mt_orders_timestamp.orders_id = mt_orders.orders_id
            LEFT JOIN mt_product ON mt_product.product_id = mt_orders_detail.product_id
            LEFT JOIN mt_product_image ON mt_product_image.image_id =
            (SELECT mt_product_image.image_id FROM mt_product_image WHERE mt_product_image.product_id = mt_product.product_id ORDER BY mt_product_image.position ASC LIMIT 1)
            WHERE mt_orders.orders_istrash != 1
                ".$where."
            ORDER BY ".$order_by." ".$limit."
        ";

        $query = $this->db->query($sql);
        $result = $query->result();

        $new_result = array();
        $convToJSON = array('orders_detail_item','orders_timestamp_desc');
        foreach ($result as $key => $val) {
            foreach ($val as $kk => $vv) {
                $new_result[$key][$kk] = $vv;
                if(in_array($kk, $convToJSON) && $vv != ''){
                    $new_result[$key][$kk] = json_decode($vv);
                }
            }

            $new_result[$key]['image_detail'] = get_image_detail($val->product_id);
        }

        $found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');
        return array(
            "result" => $new_result,
            "count"  => $found_rows->row()
        );
    }

    function data_orders_booked($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */
        $this->db->select('mt_temp_orders.*');

        /* where or like conditions */
        if( isset($p['temp_orders_id']) && $p['temp_orders_id'] != ""){
            $this->db->where("temp_orders_id",$p['temp_orders_id']);
        } else {
            $this->db->where("temp_orders_id !=", 0);
        }
        if( isset($p['store_id']) && $p['store_id'] != "" ){
            $this->db->where("store_id",$p['store_id']);
        } else {
            $this->db->where("store_id",1);
        }

        if( isset($p['member_type']) && $p['member_type'] != "" ){
            $this->db->where("member_type",$p['member_type']);
        }
        if( isset($p['member_id']) && $p['member_id'] != "" ){
            $this->db->where("member_id",$p['member_id']);
        }
        if( isset($p['orders_booked']) && $p['orders_booked'] != "" ){
            $this->db->where("orders_booked",$p['orders_booked']);
        }

        if( isset($p['orders_source_id']) && $p['orders_source_id'] != "" ){
            $arrSource = array();
            $exp = explode(',', $p['orders_source_id']);
            foreach ($exp as $n) {
                $arrSource[] = $n;
            }
            $this->db->where_in("orders_source_id", $arrSource);
            // $this->db->where("orders_source_id",$p['orders_source_id']);
            // $this->db->where_in("orders_source_id", array('1'));
        }

        if( trim($p['date_end']) != "" ){
            $this->db->where("( temp_orders_date <= '".$p['date_end']." 23:59:59' )");
        }

        if( trim($p['date_start'])!="" ){
            $this->db->where("( temp_orders_date >= '".$p['date_start']." 00:00:00' )");
        }
        // dont modified....
        if( trim($p['colum'])=="" && trim($p['keyword']) != "" ){
            $str_like = "( ";
            $i=0;
            foreach ($p['param'] as $key => $value) {
                if($key != ""){
                    $str_like .= $i!=0?"OR":"";
                    $str_like .=" ".$key." LIKE '%".$p['keyword']."%' ";
                    $i++;
                }
            }
            $str_like .= " ) ";
            $this->db->where($str_like);
        }
        if( trim($p['colum'])!="" && trim($p['keyword']) != "" ){
            $this->db->like($p['colum'],$p['keyword']);
        }
        if($count==FALSE){
            if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
                $p['offset'] = empty($p['offset'])?0:$p['offset'];
                $this->db->limit($p['limit'],$p['offset']);
            }
        }

        if( (isset($p['order_by']) && $p['order_by']!="") && (isset($p['order_dir']) && $p['order_dir']!="") ){
            $order_by  = $p['order_by'];
            $order_dir = $p['order_dir'];
            $this->db->order_by($order_by,$order_dir);
        } else {
            $this->db->order_by('temp_orders_date','desc');
        }

        $qry = $this->db->get('mt_temp_orders');
        if($count==FALSE){
            $total = $this->data_orders_booked($p,TRUE);

            $type_result = "fullresult";
            if(isset($p['type_result']) && $p['type_result'] != ""){
                $type_result = $p['type_result'];
            }
            switch ($p['type_result']) {
                case 'list':
                    $isFull     = FALSE;
                    $isKey      = array("temp_orders_id","temp_orders_date","username","store_id","orders_source_id","orders_source_name","orders_source_invoice","orders_price_grand_total","orders_booked","product_detail_item","orders_total_qty");
                    break;
                default:
                    $isFull     = TRUE;
                    $isKey      = array();
                    break;
            }

            $result     = array();
            $convToJSON = array('product_detail_item');
            foreach ($qry->result() as $key => $val) {
                $total_qty   = 0;
                $total_price = 0;
                foreach ($val as $key2 => $val2) {
                    if(in_array($key2, $isKey) || $isFull){
                        $result[$key]->$key2 = $val2;
                        if(in_array($key2, $convToJSON) && $val2 != ''){
                            $result[$key]->$key2 = json_decode($val2);
                            if(in_array("product_detail_item", $isKey) || $isFull){
                                foreach ($result[$key]->$key2 as $key3 => $val3) {
                                    $total_qty   += $val3->qty;
                                    $total_price += ($val3->price_sale * $val3->qty);
                                }
                            }
                        }

                        if(in_array("orders_total_qty", $isKey) || $isFull){
                            $result[$key]->orders_total_qty = $total_qty;
                        }
                        if(in_array("orders_price_grand_total", $isKey) || $isFull){
                            $result[$key]->orders_price_grand_total = $total_price;
                        }
                        if(in_array("orders_source_name", $isKey) || $isFull){
                            $result[$key]->orders_source_name = get_orders_source($val->orders_source_id)->orders_source_name;
                        }
                        if(in_array("username", $isKey) || $isFull){
                            if($val->member_type == 1){
                                $result[$key]->username = get_user_name($val->member_id);
                            } else if($val->member_type == 2){
                                $result[$key]->username = get_member_name($val->member_id);
                            } else {
                                $result[$key]->username = "";
                            }
                        }
                    }
                }
            }

            return array(
                    "data"  => $result,
                    "total" => $total
                );
        }else{
            return $qry->num_rows();
        }
    }

    function data_courier_package($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */
        $this->db->select('mt_noted.*, DATE(noted_date) AS date');

        /* where or like conditions */
        $this->db->where("noted_type", "1");
        $this->db->where("noted_istrash", "0");
        $this->db->group_by('DATE(noted_date)');
        // $this->db->where("noted_date LIKE", "%".$date_start."%");

        if( trim($p['date_end']) != "" ){
            $this->db->where("( noted_date <= '".$p['date_end']." 23:59:59' )");
        }

        if( trim($p['date_start'])!="" ){
            $this->db->where("( noted_date >= '".$p['date_start']." 00:00:00' )");
        }
        // dont modified....
        if( trim($p['colum'])=="" && trim($p['keyword']) != "" ){
            $str_like = "( ";
            $i=0;
            foreach ($p['param'] as $key => $value) {
                if($key != ""){
                    $str_like .= $i!=0?"OR":"";
                    $str_like .=" ".$key." LIKE '%".$p['keyword']."%' ";
                    $i++;
                }
            }
            $str_like .= " ) ";
            $this->db->where($str_like);
        }
        if( trim($p['colum'])!="" && trim($p['keyword']) != "" ){
            $this->db->like($p['colum'],$p['keyword']);
        }
        if($count==FALSE){
            if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
                $p['offset'] = empty($p['offset'])?0:$p['offset'];
                $this->db->limit($p['limit'],$p['offset']);
            }
        }

        if( (isset($p['order_by']) && $p['order_by']!="") && (isset($p['order_dir']) && $p['order_dir']!="") ){
            $order_by  = $p['order_by'];
            $order_dir = $p['order_dir'];
            $this->db->order_by($order_by,$order_dir);
        } else {
            $this->db->order_by('noted_date','desc');
        }

        $qry = $this->db->get('mt_noted');
        if($count==FALSE){
            $total = $this->data_courier_package($p,TRUE);
            $result = array();
            foreach ($qry->result() as $key => $val) {
                $result[$key]->date = $val->date;
                $result[$key]->item = get_count_courier_package($val->date);
            }
            return array(
                    "data"  => $result,
                    "total" => $total
                );
        }else{
            return $qry->num_rows();
        }
    }


    function data_courier_payment($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */
        $this->db->select('mt_noted.*, DATE(noted_date) AS date');

        /* where or like conditions */
        $this->db->where("noted_istrash", "0");
        $this->db->group_by('DATE(noted_date)');
        // $this->db->where("noted_date LIKE", "%".$date_start."%");

        if(isset($p['noted_type']) && $p['noted_type'] != ''){
            $this->db->where("noted_type", $p['noted_type']);
        }
        // if( isset($p['noted_type']) && $p['noted_type'] != "" ){
        //     $arrType = array();
        //     $exp = explode(',', $p['noted_type']);
        //     foreach ($exp as $n) {
        //         $arrType[] = $n;
        //     }
        //     $this->db->where_in("noted_type", $arrType);
        // }

        if( trim($p['date_end']) != "" ){
            $this->db->where("( noted_date <= '".$p['date_end']." 23:59:59' )");
        }

        if( trim($p['date_start'])!="" ){
            $this->db->where("( noted_date >= '".$p['date_start']." 00:00:00' )");
        }
        // dont modified....
        if( trim($p['colum'])=="" && trim($p['keyword']) != "" ){
            $str_like = "( ";
            $i=0;
            foreach ($p['param'] as $key => $value) {
                if($key != ""){
                    $str_like .= $i!=0?"OR":"";
                    $str_like .=" ".$key." LIKE '%".$p['keyword']."%' ";
                    $i++;
                }
            }
            $str_like .= " ) ";
            $this->db->where($str_like);
        }
        if( trim($p['colum'])!="" && trim($p['keyword']) != "" ){
            $this->db->like($p['colum'],$p['keyword']);
        }
        if($count==FALSE){
            if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
                $p['offset'] = empty($p['offset'])?0:$p['offset'];
                $this->db->limit($p['limit'],$p['offset']);
            }
        }

        if( (isset($p['order_by']) && $p['order_by']!="") && (isset($p['order_dir']) && $p['order_dir']!="") ){
            $order_by  = $p['order_by'];
            $order_dir = $p['order_dir'];
            $this->db->order_by($order_by,$order_dir);
        } else {
            $this->db->order_by('noted_date','desc');
        }

        $qry = $this->db->get('mt_noted');
        if($count==FALSE){
            $total = $this->data_courier_payment($p,TRUE);
            $result = array();
            $totalPrice = array();
            foreach ($qry->result() as $key => $val) {
                $result[$key]->date = $val->date;
                $result[$key]->item = get_count_courier_payment($val->date, $p['noted_type']);
            }
            $tagihan = get_total_courier_payment();
            return array(
                    "data"    => $result,
                    "total"   => $total,
                    "tagihan" => $tagihan
                );
        }else{
            return $qry->num_rows();
        }
    }

    // function cart_loaded_apps($p=array()){
    //     if($p['member_type']!='' && $p['member_id']!=''){
    //         $set_shipping = set_shipping();
    //         $details = array();
    //         $details['cart_ppn_price']                      = $set_shipping['ppn'];
    //         $details['cart_shipping_price']                 = isset($this->jCfg['cart_shipping_price'])?$this->jCfg['cart_shipping_price']:0;

    //         $details['cart_total_weight']                   = 0;
    //         $details['cart_total_weight_span']              = convertGrToKg($details['cart_total_weight']);
    //         $details['cart_voucher_price']                  = isset($this->jCfg['cart_voucher_price'])?$this->jCfg['cart_voucher_price']:0;
    //         $details['cart_voucher_price_span']             = convertRp($details['cart_voucher_price']);
    //         $details['cart_grandtotal_qty']                 = 0;
    //         $details['cart_grandtotal_qty_span']            = 0;
    //         $details['cart_grandtotal_weight']              = 0;
    //         $details['cart_grandtotal_weight_span']         = convertGrToKg($details['cart_grandtotal_weight']);
    //         $details['cart_total_price']                    = 0;
    //         $details['cart_total_price_span']               = convertRp($details['cart_total_price']);
    //         $details['cart_subgrandtotal_price']            = 0;
    //         $details['cart_subgrandtotal_price_span']       = convertRp($details['cart_subgrandtotal_price']);
    //         $details['cart_grandtotal_ppn_price']           = 0;
    //         $details['cart_grandtotal_ppn_price_span']      = convertRp($details['cart_grandtotal_ppn_price']);
    //         $details['cart_grandtotal_shipping_price']      = 0;
    //         $details['cart_grandtotal_shipping_price_span'] = convertRp($details['cart_grandtotal_shipping_price']);
    //         $details['cart_grandtotal_price']               = 0;
    //         $details['cart_grandtotal_price_span']          = convertRp($details['cart_grandtotal_price']);

    //         $where ='';
    //         $where .= "mt_temp_orders.member_type = ".$p['member_type']." ";
    //         $where .= "AND mt_temp_orders.member_id = ".$p['member_id']." ";
    //         $sql = "
    //             select
    //                 SQL_CALC_FOUND_ROWS *, mt_temp_orders.*, mt_product.*, mt_product_detail.*, mt_product_image.*
    //             FROM mt_temp_orders
    //             LEFT JOIN mt_product ON mt_product.product_id = mt_temp_orders.product_id
    //             LEFT JOIN mt_product_detail ON mt_product_detail.product_id = mt_product.product_id
    //             LEFT JOIN mt_product_image ON mt_product_image.image_id =
    //             (SELECT mt_product_image.image_id FROM mt_product_image WHERE mt_product_image.product_id = mt_product.product_id ORDER BY mt_product_image.position ASC LIMIT 1)
    //             WHERE ".$where."
    //             ORDER BY mt_temp_orders.temp_orders_date desc
    //         ";
    //         $query = $this->db->query($sql);
    //         $result = $query->result();
    //         $found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');

    //         $products   = array();
    //         $convToJSON = array('product_detail_item','product_stock_detail','product_price_grosir');
    //         $permisions = array('temp_orders_id','temp_orders_date','product_id','product_code','product_name','product_price_grosir','product_price_sale','product_price_discount','product_weight','product_detail_item','product_stock_detail','image_filename','product_date_update');
    //         foreach ($result as $key => $val) {
    //             foreach ($val as $kk => $vv) {
    //                 if(in_array($kk, $permisions)){
    //                     $products[$key][$kk] = $vv;
    //                     if(in_array($kk, $convToJSON) && $vv != ''){
    //                         $products[$key][$kk] = json_decode($vv);
    //                     }
    //                 }
    //             }

    //             // $products[$key]['image_detail'] = get_image_detail($val->product_id);

    //             $products[$key]['cart_total_qty'] = 0;
    //             $product_detail_item = json_decode($val->product_detail_item);
    //             foreach ($product_detail_item as $key2 => $val2) {
    //                 $products[$key]['cart_total_qty'] = $products[$key]['cart_total_qty'] + $val2->qty;
    //             }

    //             $details['cart_grandtotal_qty']      = $details['cart_grandtotal_qty'] + $products[$key]['cart_total_qty'];
    //             $details['cart_grandtotal_qty_span'] = $details['cart_grandtotal_qty'];

    //             $products[$key]['cart_total_weight']        = $val->product_weight * $products[$key]['cart_total_qty'];
    //             $products[$key]['cart_total_weight_span']   = convertGrToKg($products[$key]['cart_total_weight']);
    //             $details['cart_grandtotal_weight']          = $details['cart_grandtotal_weight'] + $products[$key]['cart_total_weight'];
    //             $details['cart_grandtotal_weight_span']     = convertGrToKg($details['cart_grandtotal_weight']);

    //             $products[$key]['cart_price']      = $val->product_price_sale;
    //             $products[$key]['cart_price_span'] = convertRP($products[$key]['cart_price']);
    //             if($val->product_price_discount != '0'){
    //                 $products[$key]['cart_price']      = $val->product_price_discount;
    //                 $products[$key]['cart_price_span'] = convertRP($products[$key]['cart_price']);
    //             }
    //             if($val->product_price_grosir != ''){
    //                 $products[$key]['product_price_grosir'] = json_decode($val->product_price_grosir);
    //                 foreach ($products[$key]['product_price_grosir'] as $key2 => $val2){
    //                     if($val2->qty <= $products[$key]['cart_total_qty']){
    //                         $products[$key]['cart_price']      = $val2->price;
    //                         $products[$key]['cart_price_span'] = convertRp($products[$key]['cart_price']);
    //                     }
    //                 }
    //             }
    //             $products[$key]['cart_total_price']      = $products[$key]['cart_price'] * $products[$key]['cart_total_qty'];
    //             $products[$key]['cart_total_price_span'] = convertRp($products[$key]['cart_total_price']);

    //             $details['cart_subgrandtotal_price']      = $details['cart_subgrandtotal_price'] + $products[$key]['cart_total_price'];
    //             $details['cart_subgrandtotal_price_span'] = convertRp($details['cart_subgrandtotal_price']);

    //             $details['cart_grandtotal_ppn_price']      = ($details['cart_subgrandtotal_price'] * $details['cart_ppn_price'])/100;
    //             $details['cart_grandtotal_ppn_price_span'] = convertRp($details['cart_grandtotal_ppn_price']);

    //             // $details['cart_grandtotal_shipping_price']      = $details['cart_shipping_price'] * $details['cart_grandtotal_weight_span']; // Jika dihitung per 1kg
    //             $details['cart_grandtotal_shipping_price']      = $details['cart_shipping_price'];
    //             $details['cart_grandtotal_shipping_price_span'] = convertRp($details['cart_grandtotal_shipping_price']);

    //             // $data['cart_voucher_price']      = 5000;
    //             if($details['cart_voucher_price'] != 0){
    //                 $details['cart_voucher_price_span'] = '-'.convertRp($details['cart_voucher_price']);
    //             }

    //             $details['cart_grandtotal_price']  = (($details['cart_subgrandtotal_price'] + $details['cart_grandtotal_ppn_price'] + $details['cart_grandtotal_shipping_price']) - $details['cart_voucher_price']);
    //             $details['cart_grandtotal_price_span'] = convertRp($details['cart_grandtotal_price']);

    //         }


    //         return array(
    //             "products" => $products,
    //             "details"  => $details,
    //             "count"    => $found_rows->row()
    //         );
    //     }
    // }

}