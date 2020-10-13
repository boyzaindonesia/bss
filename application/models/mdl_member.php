<?php
class mdl_member extends CI_Model{ 
	
	var $tabel = 'mt_member';
	
	function __construct(){
		parent::__construct();
	} 

	function data_member($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */
		
		$this->db->select('*');
		$this->db->where("member_istrash",0);
		
		/* where or like conditions */
		if( trim($this->jCfg['search']['date_end']) != "" ){
			$this->db->where("( member_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}
		
		if( trim($this->jCfg['search']['date_start'])!="" ){
			$this->db->where("( member_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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
			$this->db->order_by('mt_member.member_date','desc');
		}
		
		$qry = $this->db->get('mt_member');
		if($count==FALSE){
			$total = $this->data_member($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}

	function data_member_wishlist($p=array()){
		$where = '';
		$data = array();
		$member_id = '';
		if( isset($p['member_id']) && $p['member_id'] != NULL ){
			$member_id = $p['member_id'];
		}
		if( isset($p['limit']) && isset($p['offset']) ){
			$limit  = empty($p['limit'])?0:$p['limit'];				
			$offset = empty($p['offset'])?0:$p['offset'];
		}

		$i = 0;
		$min = $offset;
		$max = ($offset + $limit);
		$m = $this->db->get_where("mt_member",array(
			"member_istrash"	=> '0',
			"member_id"			=> $member_id
		),1,0)->result();
		$member_wishlist = isset($m[0]->member_wishlist)?$m[0]->member_wishlist:"";
		if($member_wishlist!=''){
			// $explode = explode(',', $member_wishlist);
			$explode = array_map('strrev', explode(',', strrev($member_wishlist))); // explode dari kanan ke kiri
			foreach ($explode as $n){
				if(($min<=$i) && ($i<$max)){
					// $arr[] = $n;
					$data[] = $this->db->get_where("mt_product",array(
								"product_istrash"	=> '0',
								"product_id"		=> $n
							),1,0)->row();
				}
				$i += 1;
			}
		}

		return array(
			"data" 	=> $data ,
			"count"	=> count($explode)
		);

	}
	
}