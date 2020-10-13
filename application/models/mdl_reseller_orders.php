<?php
class mdl_reseller_orders extends CI_Model{

	var $tabel = 'mt_store_orders';

	function __construct(){
		parent::__construct();
	}

	function data_orders($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_store_orders.*');

		/* where or like conditions */
		if( isset($p['store_orders_istrash']) && $p['store_orders_istrash'] != "" ){
			$this->db->where("mt_store_orders.store_orders_istrash",$p['store_orders_istrash']);
		} else {
			$this->db->where("mt_store_orders.store_orders_istrash",0);
		}

		if(isset($p['store_orders_id']) && $p['store_orders_id']!=""){
			$this->db->where("mt_store_orders.store_orders_id",$p['store_orders_id']);
		}
		if(isset($p['user_id']) && $p['user_id']!=""){
			$this->db->where("mt_store_orders.user_id",$p['user_id']);
		}
		if(isset($p['store_id']) && $p['store_id']!=""){
			$this->db->where("mt_store_orders.store_id",$p['store_id']);
		}

		/* where or like conditions */
		if( trim($p['date_end']) != "" ){
			$this->db->where("( mt_store_orders.store_orders_date <= '".$p['date_end']." 23:59:59' )");
		}
		if( trim($p['date_start'])!="" ){
			$this->db->where("( mt_store_orders.store_orders_date >= '".$p['date_start']." 00:00:00' )");
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
			if(trim($p['order_by'])!=""){
				$order_by  = $p['order_by'];
				$order_dir = $p['order_dir'];
				$this->db->order_by($order_by,$order_dir);
			} else {
				$this->db->order_by('mt_store_orders.store_orders_date','desc');
			}
		}

		$qry = $this->db->get('mt_store_orders');
		if($count==FALSE){
			$total = $this->data_orders($p,TRUE);
			$type_result = "fullresult";
            if(isset($p['type_result']) && $p['type_result'] != ""){
                $type_result = $p['type_result'];
            }
            switch ($p['type_result']) {
                case 'list':
                    $isFull = FALSE;
                    $isKey  = array("store_orders_id","store_orders_date","store_orders_name","store_orders_invoice","orders_price_grand_total","orders_detail");
                    break;
                case 'simpleview':
                    $isFull = FALSE;
                    $isKey  = array();
                    break;
                default:
                    $isFull = TRUE;
                    $isKey  = array();
                    break;
            }

            $result = array();
            $convToJSON = array('orders_detail','orders_detail_item');
            foreach ($qry->result() as $key => $val) {
                foreach ($val as $key2 => $val2) {
                    if(in_array($key2, $isKey) || $isFull){
                        $result[$key]->$key2 = $val2;
                        if(in_array($key2, $convToJSON) && $val2 != ''){
                            $result[$key]->$key2 = json_decode($val2);
                        }

                        $total_qty    = 0;
                        if(in_array("orders_detail", $isKey) || $isFull){
                            $arrDetail    = array();
                            $ordersDetail = $this->db->order_by("orders_detail_id","asc")->get_where("mt_store_orders_detail",array(
                                "store_orders_id" => $val->store_orders_id
                            ))->result();
                            foreach ($ordersDetail as $key4 => $val4) {
                                $total_qty += $val4->orders_detail_qty;
                                foreach ($val4 as $key5 => $val5) {
                                    $arrDetail[$key4]->$key5 = $val5;
                                    if(in_array($key5, $convToJSON) && $val5 != ''){
                                        $arrDetail[$key4]->$key5 = json_decode($val5);
                                    }
                                }
                            }
                            $result[$key]->orders_detail    = $arrDetail;
                        }
                        if(in_array("store_name", $isKey) || $isFull){
                            $result[$key]->store_name = get_store_name($val->store_id);
                        }
                        if(in_array("orders_total_qty", $isKey) || $isFull){
                            $result[$key]->orders_total_qty = $total_qty;
                        }
                    }
                }
            }

			return array(
					"data"	=> $result,
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}

	function data_orders_payment($p=array(),$count=FALSE){
		$total = 0;
		$saldo = 0;
		$allsaldo = array();
		/* table conditions */

		$this->db->select('mt_store_payment.*, mt_store_orders.store_orders_name, mt_store_orders.store_orders_code, mt_store_orders.store_orders_invoice');
        $this->db->join("mt_store_orders","mt_store_orders.store_orders_id = mt_store_payment.store_orders_id",'left');
		$this->db->where("mt_store_payment.payment_istrash",0);

		if(isset($p['store_orders_id']) && $p['store_orders_id']!=""){
			$this->db->where("mt_store_orders.store_orders_id",$p['store_orders_id']);
		}

		if(isset($p['store_payment_id']) && $p['store_payment_id']!=""){
			$this->db->where("mt_store_payment.store_payment_id",$p['store_payment_id']);
		}
		if(isset($p['user_id']) && $p['user_id']!=""){
			$this->db->where("mt_store_payment.user_id",$p['user_id']);
		}
		if(isset($p['store_id']) && $p['store_id']!=""){
			$this->db->where("mt_store_payment.store_id",$p['store_id']);
		}
		if(isset($p['payment_accept']) && $p['payment_accept']!=""){
			$this->db->where("mt_store_payment.payment_accept",$p['payment_accept']);
		}

		/* where or like conditions */
		if( trim($p['date_end']) != "" ){
			$this->db->where("( payment_date <= '".$p['date_end']." 23:59:59' )");
		}
		if( trim($p['date_start'])!="" ){
			$this->db->where("( payment_date >= '".$p['date_start']." 00:00:00' )");
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

		if(trim($p['order_by'])!=""){
			$order_by  = $p['order_by'];
			$order_dir = $p['order_dir'];
			$this->db->order_by($order_by,$order_dir);
		} else {
			$this->db->order_by('mt_store_payment.payment_date','desc');
		}

		$qry = $this->db->get('mt_store_payment');
		if($count==FALSE){
			$total = $this->data_orders_payment($p,TRUE);
			$result = array();
            $isFull = TRUE;
            $isKey  = array();
			foreach ($qry->result() as $key => $val) {
				foreach ($val as $key2 => $val2) {
                    if(in_array($key2, $isKey) || $isFull){
                        $result[$key]->$key2 = $val2;

                        $result[$key]->store_name = get_store_name($val->store_id);
                        if($val->payment_type == 2){
							$payment_method = get_payment_method($val->payment_method_id);
							$result[$key]->store_orders_name    = $payment_method->payment_method_name;
							$result[$key]->store_orders_code    = $payment_method->payment_method_name_account;
							$result[$key]->store_orders_invoice = $payment_method->payment_method_no_account;
						}
                    }
                }
			}

            $saldo = 0;
            $notId = array("1","2");
			if(isset($p['store_id']) && $p['store_id']!=""){
                if(!in_array($p['store_id'], $notId)){
    				$saldo = get_saldo_store($p['store_id']);
                }
			} else {
                $store = get_store();
                foreach ($store as $key => $val) {
                    if(!in_array($val->store_id, $notId)){
                        $saldo += get_saldo_store($val->store_id);
                    }
                }
            }

			$i = 0;
            $notId = array("1","2");
            $store = get_store();
            $allsaldo = array();
            foreach ($store as $key => $val) {
                if(!in_array($val->store_id, $notId)){
                    $allsaldo[$i]->id    = $val->store_id;
                    $allsaldo[$i]->name  = $val->store_name;
                    $allsaldo[$i]->saldo = $val->store_saldo;
                    $i += 1;
                }
            }

			return array(
					"data"	   => $result,
					"total"    => $total,
					"saldo"    => $saldo,
					"allsaldo" => $allsaldo
				);
		}else{
			return $qry->num_rows();
		}
	}

}