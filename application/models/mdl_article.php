<?php
class mdl_article extends CI_Model{

	var $tabel = 'mt_article';

	function __construct(){
		parent::__construct();
	}

	function data_article($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_article.article_id, mt_article.article_title, mt_article.article_image, mt_article.article_video, mt_article.article_publishdate, mt_article.article_count, mt_article.article_status, mt_article.article_category_id, mt_article_category.category_title, mt_app_user.user_name');
		$this->db->join("mt_article_category","mt_article_category.category_id = mt_article.article_category_id");
		$this->db->join("mt_app_user","mt_app_user.user_id = mt_article.article_user_id");
		$this->db->where("mt_article.article_istrash",0);

		/* where or like conditions */

		if( trim($this->jCfg['search']['date_end']) != "" ){
			$this->db->where("( mt_article.article_publishdate <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}

		if( trim($this->jCfg['search']['date_start'])!="" ){
			$this->db->where("( mt_article.article_publishdate >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
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
			$this->db->order_by('mt_article.article_publishdate','desc');
		}

		$qry = $this->db->get('mt_article');
		if($count==FALSE){
			$total = $this->data_article($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}

	function data_article_front($p=array()){
		$where = '';
		if( isset($p['category_id']) && isset($p['category_id']) ){
			$cat = $p['category_id'];
			$where .= "AND article_category_id IN (".$cat.")";
		}
		if( isset($p['keyword']) && trim($p['keyword'])!=""){
			$where .= " AND ( article_title like '%".pTxt($p['keyword'])."%'
							 OR article_lead like '%".pTxt($p['keyword'])."%'
							 OR article_content like '%".pTxt($p['keyword'])."%'
						   )";
		}
		$sql = "
		 	select
				SQL_CALC_FOUND_ROWS *
			FROM mt_article
			WHERE article_istrash != 1
				AND article_status = 1
				".$where."
			ORDER BY article_publishdate DESC
		";

		if( isset($p['limit']) && isset($p['offset']) ){
			$offset = empty($p['offset'])?0:$p['offset'];
			$sql .= " LIMIT ".$offset.",".$p['limit']." ";
		}
		$query = $this->db->query($sql);

		$found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');

		return array(
			"data" 	=> $query->result(),
			"total"	=> $found_rows->row()
		);
	}

}