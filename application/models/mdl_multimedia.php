<?php
class mdl_multimedia extends CI_Model{ 
	
	
	function __construct(){
		parent::__construct();
	} 

	function get_photo($p=array()){
	
		$where = '';
		
		if( isset($p['id']) && trim($p['id'])!=""){
			$where .=" AND photo_id = '".dbClean($p['id'])."' ";
		}
		if( isset($p['album_id']) && trim($p['album_id'])!=""){
			$where .=" AND photo_album_id = '".dbClean($p['album_id'])."' ";
		}
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM m_photo_gallery
			WHERE photo_istrash = 0
				".$where." 
			ORDER BY photo_id DESC 
		";	 
		
		if( isset($p['limit']) && isset($p['offset']) ){
			$offset = empty($p['offset'])?0:$p['offset'];				
			$sql .= " LIMIT ".$offset.",".$p['limit']." ";
		}
		$query = $this->db->query($sql);
		
		$found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');

		return array(
			"data" 	=> $query->result(),
			"count"	=> $found_rows->num_rows()
		);
	}
	
	function get_album_foto($p=array()){
	
		$where = '';
		
		if( isset($p['album_id']) && trim($p['album_id'])!=""){
			$where .=" AND album_id = '".dbClean($p['album_id'])."' ";
		}

		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM m_photo_album
			WHERE album_status != 0
			AND album_istrash != 1
				".$where." 
			ORDER BY album_id DESC 
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
	
	function get_video($p=array()){
	
		$where = '';
		if( isset($p['valbum_id']) && trim($p['valbum_id'])!=""){
			$where .=" AND vgallery_album_id = '".dbClean($p['valbum_id'])."' ";
		}
		if( isset($p['id_video']) && trim($p['id_video'])!=""){
			$where .=" AND vgallery_id = '".dbClean($p['id_video'])."' ";
		}
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM m_video_gallery
			WHERE vgallery_status != 0
			AND vgallery_istrash != 1
				".$where." 
			ORDER BY vgallery_id DESC 
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
	
	function get_album_video($p=array()){
	
		$where = '';
		
		if( isset($p['album_id']) && trim($p['album_id'])!=""){
			$where .=" AND album_id = '".dbClean($p['album_id'])."' ";
		}
		
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM m_video_album
			WHERE valbum_status != 0
			AND valbum_istrash != 1
				".$where." 
			ORDER BY valbum_id DESC 
		";	 
		
		if( isset($p['limit']) && isset($p['offset']) ){
			$offset = empty($p['offset'])?0:$p['offset'];				
			$sql .= " LIMIT ".$offset.",".$p['limit']." ";
		}
		//debugCode($sql);
		$query = $this->db->query($sql);
		
		
		$found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');

		return array(
			"data" 	=> $query->result(),
			"count"	=> $found_rows->row()
		);
	}
		
	function multimedia($p=array()){
	
		$where = '';
		if( !empty($p['dbv']) && trim($p['dbv'])!=""){
			$where .= ' valbum_status != 0';
			$where .= ' AND valbum_istrash != 1';
			$db = dbClean($p['dbv']);
			$id = 'valbum_id';
		}
		if( !empty($p['dbf']) && trim($p['dbf'])!=""){
			$where .= ' album_status != 0';
			$where .= ' AND album_istrash != 1';
			$db = dbClean($p['dbf']);
			$id = 'album_id';
		}
		if( isset($p['id']) && trim($p['id'])!=""){
			$where .=" AND ".$id." = '".dbClean($p['id'])."' ";
		}
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM ".$db."
			WHERE 
				".$where." 
			ORDER BY ".$id." DESC 
		";	 
		
		if( isset($p['limit']) && isset($p['offset']) ){
			$offset = empty($p['offset'])?0:$p['offset'];				
			$sql .= " LIMIT ".$offset.",".$p['limit']." ";
		}
		//debugCode($sql);
		$query = $this->db->query($sql);
		
		
		$found_rows = $this->db->query('SELECT FOUND_ROWS() as found_rows');

		return array(
			"data" 	=> $query->result(),
			"count"	=> $found_rows->row()
		);
	}
	function get_tag($p=array()){
		$where = '';
		if( isset($p['album_id']) && trim($p['album_id'])!=""){
			$where .=" AND at_article_id = '".dbClean($p['album_id'])."' ";
		}
		
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM m_article_tags
			WHERE at_tags_type = 1
				".$where." 
			ORDER BY at_id DESC 
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
	
	function tagNews($p=array()){
		$where = '';
		
		if( isset($p['tag_id']) && trim($p['tag_id'])!=""){
			$where .=" AND at_tags_id in ( ".$p['tag_id']." )";
			$where .=" AND at_article_id != ".$p['article_id'];
			$param	= "ORDER BY rand() , at_tags_id DESC";
		}
		
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM  m_article_tags
			WHERE at_tags_type = 1
				".$where." 
				".$param." 
		";	 
		//debugCode($sql);
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
	function get_tagV($p=array()){
		$where = '';
		if( isset($p['album_id']) && trim($p['album_id'])!=""){
			$where .=" AND at_article_id = '".dbClean($p['album_id'])."' ";
		}
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM m_article_tags
			WHERE at_tags_type = 2
				".$where." 
			ORDER BY at_id DESC 
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
	
	function tagNewsV($p=array()){
		$where = '';
		
		if( isset($p['tag_id']) && trim($p['tag_id'])!=""){
			$where .=" AND at_tags_id in ( ".$p['tag_id']." )";
			$where .=" AND at_article_id != ".$p['article_id'];
			$param	= "ORDER BY rand() , at_tags_id DESC";
		}
		
		$sql = "
		 	select 
				SQL_CALC_FOUND_ROWS *
			FROM  m_article_tags
			WHERE at_tags_type = 2
				".$where." GROUP BY at_article_id, at_tags_type
				".$param." 
		";	 
		//debugCode($sql);
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