<?php
class mdl_purchase extends CI_Model{

	var $tabel = 'mt_purchase';

	function __construct(){
		parent::__construct();
	}

	function data_purchase($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_purchase.*, mt_purchase_detail.*, mt_supplier.*');
		$this->db->join("mt_purchase_detail","mt_purchase_detail.purchase_id = mt_purchase.purchase_id",'left');
		$this->db->join("mt_supplier","mt_supplier.supplier_id = mt_purchase.supplier_id",'left');
		$this->db->where("mt_purchase.purchase_istrash",0);

		if(isset($p['group_by']) && $p['group_by']==true){
			$this->db->group_by("mt_purchase.purchase_id");
		}
		if(isset($p['type']) && $p['type']!=""){
			$this->db->where("mt_purchase.purchase_type",$p['type']);
		}
		if(isset($p['detail_status']) && $p['detail_status']!=""){
			$this->db->where("mt_purchase_detail.purchase_detail_status",$p['detail_status']);
		}

		/* where or like conditions */
		if( trim($this->jCfg['search']['date_end']) != "" ){
			$this->db->where("( purchase_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}

		if( trim($this->jCfg['search']['date_start'])!="" ){
			$this->db->where("( purchase_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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
			$this->db->order_by('mt_purchase.purchase_date','desc');
		}

		$qry = $this->db->get('mt_purchase');
		if($count==FALSE){
			$total = $this->data_purchase($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}

	function data_purchase_front($p=array()){
		$where = '';
		/* where or like conditions */
		if(isset($p['purchase_id']) && $p['purchase_id'] != ''){
			$where .= "AND mt_purchase.purchase_id ".check_isnot($p['purchase_id'])." ";
		}
		if(isset($p['supplier_id']) && $p['supplier_id'] != ''){
			$where .= "AND mt_purchase.supplier_id ".check_isnot($p['supplier_id'])." ";
		}
		if(isset($p['purchase_user_id']) && $p['purchase_user_id'] != ''){
			$where .= "AND mt_purchase.purchase_user_id ".check_isnot($p['purchase_user_id'])." ";
		}
		if(isset($p['purchase_status']) && $p['purchase_status'] != ''){
			$where .= "AND mt_purchase.purchase_status ".check_isnot($p['purchase_status'])." ";
		}
		if(isset($p['purchase_type']) && $p['purchase_type'] != ''){
			$where .= "AND mt_purchase.purchase_type ".check_isnot($p['purchase_type'])." ";
		}
		if(isset($p['date_end']) && $p['date_end'] != ''){
			$where .= "AND ( mt_purchase.purchase_date <= '".$p['date_end']." 23:59:59' )";
		}
		if(isset($p['date_start']) && $p['date_start'] != ''){
			$where .= "AND ( mt_purchase.purchase_date >= '".$p['date_start']." 00:00:00' )";
		}

		if( isset($p['keyword']) && trim($p['keyword'])!=""){
			$where .= " AND ( mt_purchase.purchase_invoice like '%".pTxt($p['keyword'])."%'
							 OR mt_purchase_detail.purchase_detail_name like '%".pTxt($p['keyword'])."%'
						   )";
		}

		$order_by = 'mt_purchase.purchase_date desc';
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

		$sql = "
		 	select
				SQL_CALC_FOUND_ROWS *, mt_purchase.*, mt_purchase_detail.*
			FROM mt_purchase
            LEFT JOIN mt_purchase_detail ON mt_purchase_detail.purchase_detail_id =
            (SELECT mt_purchase_detail.purchase_detail_id FROM mt_purchase_detail WHERE mt_purchase_detail.purchase_id = mt_purchase.purchase_id ORDER BY mt_purchase_detail.purchase_detail_id ASC LIMIT 1)
			WHERE mt_purchase.purchase_istrash != 1
				".$where."
			ORDER BY ".$order_by." ".$limit."
		";

		$query = $this->db->query($sql);
		$found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');
		return array(
			"result" => $query->result(),
			"count"	 => $found_rows->row()
		);
	}

}