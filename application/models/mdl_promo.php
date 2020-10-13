<?php
class mdl_product extends CI_Model{ 
	
	var $tabel = 'mt_product';
	
	function __construct(){
		parent::__construct();
	} 

	function data_product($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */
		
		$this->db->select('mt_product.*, mt_product_category.*, mt_app_user.user_name');
		$this->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id");
		$this->db->join("mt_app_user","mt_app_user.user_id = mt_product.product_user_id");
		$this->db->where("product_istrash",0);
		
		/* where or like conditions */

		if( trim($this->jCfg['search']['date_end']) != "" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( mt_product.product_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");

		}
		
		if( trim($this->jCfg['search']['date_start'])!="" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( mt_product.product_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");

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
			$this->db->order_by('mt_product.product_id','desc');
		}

		$qry = $this->db->get('mt_product');
		if($count==FALSE){
			$total = $this->data_product($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}
	
	function data_product_front($p=array()){
		$where = '';
		if( isset($p['product_category_id']) && $p['product_category_id'] != NULL ){
			$cat = $p['product_category_id'];
			$where .= "AND product_category_id = ".$cat." ";
		}
		
		if( isset($p['keyword']) && trim($p['keyword'])!=""){
			$where .= " AND ( product_name like '%".pTxt($p['keyword'])."%' 
							 OR product_lead like '%".pTxt($p['keyword'])."%'
							 OR product_desc like '%".pTxt($p['keyword'])."%'
						   )";
		}
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM mt_product
			WHERE product_istrash != 1
			AND product_istop = 1
				".$where."
			ORDER BY product_id ASC
		";	

		if( isset($p['limit']) && isset($p['offset']) ){
			$offset = empty($p['offset'])?0:$p['offset'];				
			$sql .= " LIMIT ".$offset.",".$p['limit']." ";
		}
		$query = $this->db->query($sql);
		
		$found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');

		return array(
			"data" 	=> $query->result(),
			"count"	=> $found_rows->row()
		);
	}	
}