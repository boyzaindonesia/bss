<?php
// $table = 'member_notif_id'->INT 20,'member_id'->INT 20,'member_notif_desc'->TEXT,'member_notif_action'->INT 1, 'member_notif_status'->INT 1,'member_notif_repeat'->INT 1,'member_notif_logout'->INT 1,'member_notif_date'->DATETIME,'member_notif_istrash'->INT 1;

$route['check-member-notif'] 			= "front/member/check_member_notif";

	function check_member_notif(){
		$found  = false;
		$msg    = '';
		$repeat = false;
		$url    = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check' ){
			$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
			if( trim($this->user_login)!="" ){
				$this->DATA->table="mt_member_notif";
				$v = $this->db->get_where("mt_member_notif",array(
					"member_id"				=> $this->user_login,
					"member_notif_status"	=> '1'
				))->row();
				if(count($v)>0){
					$found  = true;
					$msg 	= $v->member_notif_desc;
					if($v->member_notif_repeat == '1'){
						$repeat = true;
					}
					if($v->member_notif_logout == '1'){
						$url    = base_url().'login';
						$this->jCfg['member'] 		= '';
						$this->_releaseSession();
					}

					$this->db->update("mt_member_notif",array("member_notif_status"=>'0'),array("member_notif_id"=>$v->member_notif_id));
				}
			}
		}

		$return = array('found' => $found,'msg' => $msg,'repeat' => $repeat,'url' => $url);
		die(json_encode($return));
		exit();
	}
	
