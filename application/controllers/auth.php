<?php
include_once(APPPATH."libraries/FrontController.php");
class auth extends FrontController {

	function __construct(){
		parent::__construct(); 		
		$this->jCfg['theme'] = 'admin/template';
		$this->_releaseSession();
	}
	
	function index(){
		
		if($this->jCfg['is_login_bp']==1){
			redirect(site_url("admin/me"));
		}
 
		$data = array(
			'message'=>''
		);

		$msg = $_GET['msgs'];
		if(!empty($msg)){
			$data['msg']	= $msg;
		}
		
		$i = $_GET['i'];
		if(!empty($i)){
			$data['i']	= $i;
		}

		if(isset($_POST['login']) && $_POST['login'] == 'yes'){

			$u = dbClean($this->input->post('username'));
			$p = md5(dbClean($this->input->post('password')));
			if( trim($u) == '' || trim($p) == '' ){
				$data['message']	= 'Login Fail...';	
			}else{
				
				$d = $this->db->get_where("mt_app_user",array(
						"user_name"		=> $u,
						"user_password"	=> $p,
						"user_status"	=> 1,
						"is_trash"		=> 0
					))->row();
				
				if(count($d) > 0){					
					/*set session*/
					
					$group = $this->db->get_where("mt_app_acl_group",array(
							"ag_id" => $d->user_group
						))->row();

					$this->jCfg['is_login_bp'] 		= 1;
					$this->jCfg['user']['id'] 		= $d->user_id;
					$this->jCfg['user']['name']		= $d->user_name;
					$this->jCfg['user']['photo']    = $d->user_photo;
					$this->jCfg['user']['fullname'] = $d->user_fullname;
					$this->jCfg['user']['email']    = $d->user_email;
					$this->jCfg['user']['is_all']	= $d->is_show_all;	
					$this->jCfg['user']['bg']		= $d->user_background;
					$this->jCfg['user']['color']	= $d->user_themes;
					$this->jCfg['user']['level'] 	= count($group)>0?$group->ag_group_name:'';
					$this->jCfg['chat_online'] 		= array(
						"userid_chat_online"		=> $d->user_id,
						"username_chat_online"		=> $d->user_name
					);
															
					$this->_releaseSession();
					//debugCode($this->jCfg['is_login_bp']);
					$this->db->update("mt_app_user",array(
						'user_logindate' => date("Y-m-d H:i:s")
					),array(
						'user_id' => $d->user_id
					));
					//debugCode($this->jCfg['is_login_bp']);					
					if( $this->jCfg['referer'] != '' && $this->jCfg['referer'] != 'chat'){
							redirect($this->jCfg['referer']);
					}else{
						redirect(site_url('admin/me'));
					}
				}else{
					$data['message']	= 'Please check Username and password...';
				}
			}		
		}	

		$this->_v('login',$data);
	}
	
	function out(){
		$this->jCfg['user']['id'] 		= '';
		$this->jCfg['user']['fullname'] = 'Guest';
		$this->jCfg['user']['name'] 	= 'guest';
		$this->jCfg['user']['email']    = '';
		$this->jCfg['user']['level'] 	= '';
		$this->jCfg['user']['ujian_type'] = '';
		$this->jCfg['user']['access'] 	= array();
		$this->jCfg['menu'] 			= array();
		$this->jCfg['is_login_bp'] 		= 0;
		$this->jCfg['user']['is_all']	= 0;
		$this->jCfg['chat_online']		= array();
		$this->jCfg['referer']			= "";
		$this->_releaseSession();
		redirect(site_url()."cms");
	}

	
	function forgot(){

		$data = array(
			'message'=>''
		);

		$msg = $_GET['msgs'];
		if(!empty($msg)){
			$data['msg']	= $msg;
		}
		
		$i = $_GET['i'];
		if(!empty($i)){
			$data['i']	= $i;
		}

		if(isset($_POST['forgot']) && $_POST['forgot'] == 'yes'){
			// debugCode($_POST);

			$user['data'] 	= '';
			$e	 			= isset($_POST['email'])?$_POST['email']:'';
			$hast_key 		= "musica_email_encrypt";
			$this->load->library('encrypt');
			$emailencrypt 	= $this->encrypt->encode($e,$hast_key);

			if(!empty($e)){
				$user = $this->db->get_where("mt_app_user",array(
						"user_email"	=> $e,
						"user_status"	=> 1,
						"is_trash"		=> 0
					))->row();
				$id			= isset($user->user_id)?$user->user_id:"";
				$name		= isset($user->user_fullname)?$user->user_fullname:"";
				$email		= isset($user->user_email)?$user->user_email:"";
				$username	= isset($$user->user_name)?$user->user_name:"";
			}
			
			if(!empty($user)){
				
				$pesan_email  = 'Kepada Yth. <strong>'.$name.'</strong><br/><br/>';
				$pesan_email .= 'Anda telah mengirimkan permintaan untuk mengganti password anda.<br/>';
				$pesan_email .= 'Klik <a href="'.base_url().'cms/reset_password_confirmation?key='.$emailencrypt.'">disini</a> untuk reset password anda atau abaikan email ini untuk menolak permintaan ganti password.<br/><br/>';
				$pesan_email .= 'login, visit <a href="'.base_url().'login">'.base_url().'login</a><br/><br/>';
				$pesan_email .= 'Regards';

				$this->sendEmail(array(
					"from"		=> '<noreply@'.$_SERVER['HTTP_HOST'].'>',
					"to"		=> $email,
					"title"		=> "Reset Password - ".$_SERVER['HTTP_HOST'],
					"subject"	=> "Reset Password",
					"type"		=> "html",
					"message"	=> $pesan_email,
				));
				$msg = 'Permintaan reset password anda telah dikirim. Cek Email Anda.';
				redirect(base_url().'cms/forgot?msgs='.urlencode($msg));
			}else{
				$msg = 'Email Anda tidak terdaftar!';
				redirect(base_url().'cms/forgot?msgs='.urlencode($msg).'&i=1');
			}
			
		}

		$this->_v('forgot',$data);
	}
	
	function reset_password_confirmation($id){
		$this->load->library('encrypt');
		$e	 			= isset($_GET['key'])?$_GET['key']:'';
		$email 			= str_replace(" ","+",$e);
		$hast_key 		= "musica_email_encrypt";
		$emaildecrypt 	= $this->encrypt->decode($email, $hast_key);
		$user['data'] 	= '';
		
		if(!empty($emaildecrypt)){
			$user = $this->db->get_where("mt_app_user",array(
					"user_email"	=> $emaildecrypt,
					"user_status"	=> 1,
					"is_trash"		=> 0
				))->row();
			$id			= isset($user->user_id)?$user->user_id:"";
			$name		= isset($user->user_fullname)?$user->user_fullname:"";
			$email		= isset($user->user_email)?$user->user_email:"";
			$username	= isset($user->user_name)?$user->user_name:"";
			
		}
		
		if(!empty($user)){
			$password = substr(base_convert(sha1(uniqid(mt_rand())), 36, 15), 0, 8);
			$data = array( 'user_password' => md5($password) );
			$this->db->where('user_id', $id);
			$this->db->update('mt_app_user', $data); 
			
			$pesan_email  = 'Kepada Yth. <strong>'.$name.'</strong><br/><br/>';
			$pesan_email .= 'Password account anda telah di reset. Sekarang Anda dapat mengakses akun anda di website kami dengan menggunakan rincian di bawah ini :<br/>';
			$pesan_email .= 'Email Address : '.$email.'<br/>';
			$pesan_email .= 'Username : '.$username.'<br/>';
			$pesan_email .= 'Password : '.$password.'<br/>';
			$pesan_email .= 'login, visit <a href="'.base_url().'login">'.base_url().'login</a><br/><br/>';
			$pesan_email .= 'Regards';
			//debugCode($username);	
			$this->sendEmail(array(
				"from"		=> '<noreply@'.$_SERVER['HTTP_HOST'].'>',
				"to"		=> $email,
				"title"		=> "Confirmation Reset Password",
				"subject"	=> "Confirmation Reset Password",
				"type"		=> "html",
				"message"	=> $pesan_email,
			));
			$msg = 'Password Anda berhasil diganti. Silahkan cek email anda.';
			redirect(base_url().'cms?msgs='.urlencode($msg));
		}else{
			$msg = 'Email Anda tidak terdaftar!';
			redirect(base_url().'cms?msgs='.urlencode($msg).'&i=1');
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */