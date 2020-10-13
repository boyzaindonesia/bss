<?php
class mdl_notification extends CI_Model{

	var $tabel = 'mt_product';

	function __construct(){
		parent::__construct();

	}

	function data_notif_product($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_product_notif.*, mt_product.*, mt_product_detail.*, mt_app_user.user_name');
		$this->db->join("mt_product","mt_product.product_id = mt_product_notif.product_id",'left');
		$this->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');
		$this->db->join("mt_app_user","mt_app_user.user_id = mt_product_notif.user_id",'left');
		$this->db->where("mt_product_notif.notif_istrash",0);

		/* where or like conditions */

		if( isset($p['user_id']) && $p['user_id'] != "" ){
			$this->db->where("mt_app_user.user_id",$p['user_id']);
		}
		if( isset($p['store_id']) && $p['store_id'] != "" ){
			$this->db->where("mt_product.store_id",$p['store_id']);
		}
		if( isset($p['product_id']) && $p['product_id'] != "" ){
			$this->db->where("mt_product.product_id",$p['product_id']);
		}
		if( isset($p['notif_id']) && $p['notif_id'] != "" ){
			$this->db->where("mt_product_notif.notif_id",$p['notif_id']);
		}
		if( isset($p['notif_status']) && $p['notif_status'] != "" ){
			$this->db->where("mt_product_notif.notif_status",$p['notif_status']);
		}

		if( trim($this->jCfg['search']['date_end']) != "" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( mt_product_notif.notif_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}

		if( trim($this->jCfg['search']['date_start'])!="" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( mt_product_notif.notif_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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
			$this->db->order_by('mt_product_notif.notif_date','desc');
		}

		$qry = $this->db->get('mt_product_notif');
		if($count==FALSE){
			$total = $this->data_notif_product($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}


}