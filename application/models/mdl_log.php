<?php
class mdl_log extends CI_Model{

	var $tabel = 'mt_app_log';

	function __construct(){
		parent::__construct();
	}

	function data($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_app_log.*,mt_product.*,mt_app_user.*');
		$this->db->join("mt_product","mt_product.product_id = mt_app_log.log_detail_id",'left');
		$this->db->join("mt_app_user","mt_app_user.user_id = mt_app_log.log_user_id",'left');

		/* where or like conditions */
		if( isset($p['log_istrash']) && $p['log_istrash'] != "" ){
			$this->db->where("log_istrash",$p['log_istrash']);
		} else {
			$this->db->where("log_istrash",0);
		}

		if( isset($p['log_id']) && $p['log_id'] != "" ){
			$this->db->where("log_id",$p['log_id']);
		}

		if( isset($p['log_user_type']) && $p['log_user_type'] != "" ){
			$this->db->where("log_user_type",$p['log_user_type']);
		}
		if( isset($p['log_user_id']) && $p['log_user_id'] != "" ){
			$this->db->where("log_user_id",$p['log_user_id']);
		}

		if( isset($p['log_type']) && $p['log_type'] != "" ){
			$this->db->where("log_type",$p['log_type']);
		}

		if( isset($p['log_title_id']) && $p['log_title_id'] != "" ){
			$this->db->where("log_title_id",$p['log_title_id']);
		}

		if( isset($p['log_status']) && $p['log_status'] != "" ){
			$this->db->where("log_status",$p['log_status']);
		}

		if( isset($p['log_detail_id']) && $p['log_detail_id'] != "" ){
			$this->db->where("log_detail_id",$p['log_detail_id']);
		}

		if( trim($this->jCfg['search']['date_end']) != "" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( log_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}

		if( trim($this->jCfg['search']['date_start'])!="" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( log_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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

		if( (isset($p['order_by']) && $p['order_by']!="") && (isset($p['order_dir']) && $p['order_dir']!="") ){
			$order_by  = $p['order_by'];
			$order_dir = $p['order_dir'];
			$this->db->order_by($order_by,$order_dir);
		} else {
			if(trim($this->jCfg['search']['order_by'])!=""){
				$order_by  = $this->jCfg['search']['order_by'];
				$order_dir = $this->jCfg['search']['order_dir'];
				$this->db->order_by($order_by,$order_dir);
			} else {
				$this->db->order_by('log_date','desc');
			}
		}

		$qry = $this->db->get('mt_app_log');
		if($count==FALSE){
			$total = $this->data($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}


}