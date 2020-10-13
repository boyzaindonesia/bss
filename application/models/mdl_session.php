<?php
class mdl_session extends CI_Model{

	var $tabel = 'mt_app_sessions';

	function __construct(){
		parent::__construct();
	}

	function data($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_app_sessions.*');
		$this->db->where("session_id !=",0);

		/* where or like conditions */

		if( trim($this->jCfg['search']['date_end']) != "" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( last_activity <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}

		if( trim($this->jCfg['search']['date_start'])!="" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( last_activity >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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
				$this->db->order_by('session_id','desc');
			}
		}

		$qry = $this->db->get('mt_app_sessions');
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