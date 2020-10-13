<?php
class mdl_message extends CI_Model{ 
	
	var $tabel = 'mt_message';
	
	function __construct(){
		parent::__construct();
	} 

	function data_message($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */
		
		$this->db->select('*');
		$this->db->where("message_istrash",0);
		
		/* where or like conditions */

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
			$this->db->order_by('mt_message.message_date','desc');
		}

		$qry = $this->db->get('mt_message');
		if($count==FALSE){
			$total = $this->data_message($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}
	
	// function gallery($p=array()){
	// 	$where = '';
		
	// 	$sql = "
	// 	 	select 
	// 			SQL_CALC_FOUND_ROWS *
	// 		FROM mt_message
	// 		WHERE 
	// 			gallery_status = 1
	// 			AND message_istrash != 1
	// 			".$where."
	// 		ORDER BY message_date DESC
	// 	";	
		
	// 	if( isset($p['limit']) && isset($p['offset']) ){
	// 		$offset = empty($p['offset'])?0:$p['offset'];				
	// 		$sql .= " LIMIT ".$offset.",".$p['limit']." ";
	// 	}
	// 	$query = $this->db->query($sql);
		
	// 	$found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');

	// 	return array(
	// 		"data" 	=> $query->result(),
	// 		"count"	=> $found_rows->row()
	// 	);
	// }	
}