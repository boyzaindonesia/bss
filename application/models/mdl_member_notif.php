<?php
class mdl_member_notif extends CI_Model{ 
	
	var $tabel = 'mt_member_notif';
	
	function __construct(){
		parent::__construct();
	} 

	function data_member_notif($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_member_notif.*, mt_member.*');
		$this->db->join("mt_member","mt_member.member_id = mt_member_notif.member_id", 'left');
		$this->db->where("member_notif_istrash",0);
		
		/* where or like conditions */
		if( trim($this->jCfg['search']['date_end']) != "" ){
			$this->db->where("( member_notif_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}
		
		if( trim($this->jCfg['search']['date_start'])!="" ){
			$this->db->where("( member_notif_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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
			$this->db->order_by('mt_member_notif.member_notif_date','desc');
		}
		
		$qry = $this->db->get('mt_member_notif');
		if($count==FALSE){
			$total = $this->data_member_notif($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}
	
}