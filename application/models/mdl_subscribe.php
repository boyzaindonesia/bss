<?php
class mdl_subscribe extends CI_Model{ 
	
	var $tabel = 'mt_subscribe';
	
	function __construct(){
		parent::__construct();
	} 

	function data_subscribe($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */
		
		$this->db->select('*');
		$this->db->where("subscribe_istrash",0);
		
		/* where or like conditions */
		if( trim($this->jCfg['search']['date_end']) != "" ){
			$this->db->where("( subscribe_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}
		
		if( trim($this->jCfg['search']['date_start'])!="" ){
			$this->db->where("( subscribe_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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
			$this->db->order_by('mt_subscribe.subscribe_date','desc');
		}
		
		$qry = $this->db->get('mt_subscribe');
		if($count==FALSE){
			$total = $this->data_subscribe($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}
	
}