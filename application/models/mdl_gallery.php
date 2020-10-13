<?php
class mdl_gallery extends CI_Model{ 
	
	var $tabel = 'mt_gallery';
	
	function __construct(){
		parent::__construct();
	} 

	function data_gallery($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */
		
		$this->db->select('*');
		$this->db->where("gallery_istrash",0);
		
		/* where or like conditions */

		if( trim($this->jCfg['search']['date_end']) != "" ){
			$this->db->where("( mt_gallery.gallery_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}
		
		if( trim($this->jCfg['search']['date_start'])!="" ){
			$this->db->where("( mt_gallery.gallery_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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
			$this->db->order_by('mt_gallery.position','asc');
		}
		
		$qry = $this->db->get('mt_gallery');
		if($count==FALSE){
			$total = $this->data_gallery($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}
	
	function gallery($p=array()){
		$where = '';
		
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM mt_gallery
			WHERE 
				gallery_status = 1
				AND gallery_istrash != 1
				".$where."
			ORDER BY position ASC
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