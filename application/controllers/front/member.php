<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class Member extends FrontController {
	var $user_login = "";
	public function __construct()
	{
		parent::__construct();
		$this->load->helper("date");
		$this->load->model("mdl_article","MA");
		$this->load->model("mdl_member","MM");
		$this->load->model('mdl_city','MC');
		$this->load->model('mdl_province','MPR');
		$this->load->model("mdl_product","MP");

		$this->load->library('Mobile_Detect');
	 //    $detect = new Mobile_Detect();
	 //    if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
	 //        // header("Location: /mobile"); exit;
	 //    } else {
	 //        // header("Location: /desktop"); exit;
	 //    }

		checkIsBlacklistOnLoad();

	}

	function _reset(){
		$this->jCfg['search'] = array(
			'class'		=> $this->_getClass(),
			'name'		=> 'member',
			'date_start'=> '',
			'date_end'	=> '',
			'status'	=> '',
			'order_by'  => '',
			'order_dir' => '',
			'colum'		=> '',
			'keyword'	=> ''
		);
		$this->_releaseSession();
	}

	function index(){
		//debugCode($this->jCfg['member']);
		$this->page     = 'Member';
		$this->cur_menu = 'member';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$hal = isset($this->jCfg['search']['name'])?$this->jCfg['search']['name']:"home";
		if($hal != 'member'){
			$this->_reset();
		}

		$n = '';
		$next = isset($_GET['next'])?$_GET['next']:"";
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		$data = array();
		// $data['me'] = $this->db->get_where("mt_member",array(
		// 	"member_id"	=> $this->user_login
		// ))->row();

		$data['id_member'] = $this->jCfg['member']['member_id'];

	}

    function fblogin() {
    	redirect(base_url().'oAuth/facebook/');
    }
    function googlelogin() {
    	redirect(base_url().'oAuth/google/');
    }
    function twitterlogin() {
    	redirect(base_url().'oAuth/twitter/');
    }

    function authorize() {
		$this->page     = 'Authorize';
		$this->cur_menu = 'authorize';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$id 		= isset($_POST['id'])?$_POST['id']:'';
		$name 		= isset($_POST['name'])?$_POST['name']:'';
		$email 		= isset($_POST['email'])?$_POST['email']:'';
		$image 		= isset($_POST['image'])?$_POST['image']:'';
		$gender 	= isset($_POST['gender'])?$_POST['gender']:'';
		$locale 	= isset($_POST['locale'])?$_POST['locale']:'';
		$link 		= isset($_POST['link'])?$_POST['link']:'';
		$method 	= isset($_POST['method'])?$_POST['method']:'';
		$error      = false;
		$msg        = '';

		if($method!=''){
			switch ($method) {
				case 'facebook': $thisTable = 'facebook'; break;
				case 'twitter': $thisTable = 'twitter'; break;
				case 'google': $thisTable = 'google'; break;
				default: $thisTable = ''; break;
			}

			if($thisTable!=''){
				$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
				if( trim($this->user_login)!="" ){
					$d = $this->db->get_where("mt_member",array(
						'member_id' => $this->user_login
					))->row();
					if(count($d) > 0){
						if($d->$thisTable == ''){
							$d2 = $this->db->get_where("mt_member",array(
								$thisTable		=> $id
							))->row();
							if(count($d2) > 0){
								$data['msg'] = "Profil kamu sebelumnya sudah dihubungkan.";
								redirect(base_url()."profile?msg=".urlencode($data['msg'])."&type_msg=error");
							} else {
								$this->db->update("mt_member",array($thisTable=>$id),array("member_id"=>$this->user_login));
								$data['msg'] = "Profil kamu sudah berhasil dihubungkan.";
								redirect(base_url()."profile?msg=".urlencode($data['msg'])."&type_msg=success");
							}
						} else {
							$data['msg'] = "Profil kamu sebelumnya sudah dihubungkan.";
							redirect(base_url()."profile?msg=".urlencode($data['msg'])."&type_msg=error");
						}
					}
				} else {
				    $d = $this->db->get_where("mt_member",array(
						$thisTable		=> $id
					))->row();
					if(count($d) > 0){
						$arr = array('9');
					    if(in_array($d->member_status, $arr)){
							$data['msg'] = get_status_member($d->member_status)['msg'];
							$this->jCfg['member'] 		= '';
							$this->_releaseSession();
							redirect(base_url()."login?msg=".urlencode($data['msg'])."&type_msg=error");
					    } else {
							$this->db->update("mt_member",array("member_logindate"=>timestamp()),array("member_id"=>$d->member_id));
							if($d->member_status == 1 && $d->member_verification != ''){
								$this->db->update("mt_member",array("member_verification"=>NULL),array("member_id"=>$d->$member_id));
							}

							$this->jCfg['member']['member_id'] 		= $d->member_id;
							$this->jCfg['member']['member_name']	= $d->member_name;
							$this->jCfg['member']['member_email']	= $d->member_email;
							$this->jCfg['member']['member_login']	= 1;
							$this->_releaseSession();

					    	redirect(base_url());
					    }
					} else {
					    $d = $this->db->get_where("mt_member",array(
							'member_email'		=> $email
						))->row();
						if(count($d) > 0){
							$arr = array('9');
						    if(in_array($d->member_status, $arr)){
								$data['msg'] = get_status_member($d->member_status)['msg'];
								$this->jCfg['member'] 		= '';
								$this->_releaseSession();
								redirect(base_url()."login?msg=".urlencode($data['msg'])."&type_msg=error");
						    } else {
								$this->db->update("mt_member",array("member_logindate"=>timestamp(),$thisTable=>$id),array("member_id"=>$d->member_id));
								if($d->member_status == 1 && $d->member_verification != ''){
									$this->db->update("mt_member",array("member_verification"=>NULL),array("member_id"=>$d->$member_id));
								}

								$this->jCfg['member']['member_id'] 		= $d->member_id;
								$this->jCfg['member']['member_name']	= $d->member_name;
								$this->jCfg['member']['member_email']	= $d->member_email;
								$this->jCfg['member']['member_login']	= 1;
								$this->_releaseSession();

						    	redirect(base_url());
						    }
						} else {
							$this->load->view('front/fullwidth/account_register', array(
								'id' 		=> $id,
								'name'		=> $name,
								'email'		=> $email,
								'image'		=> $image,
								'gender'	=> $gender,
								'locale'	=> $locale,
								'link'		=> $link,
								'method'	=> $method,
								'error'		=> $error,
								'msg'		=> $msg
							));
						}
					}
				}
			} else {
				redirect(base_url()."login?msg=".urlencode('You social media not founds.')."&type_msg=error");
			}
		} else {
			redirect(base_url()."login?msg=".urlencode('You social media not founds.')."&type_msg=error");
		}
    }

    function account_login(){
		//$this->output->cache(30);
		$this->page     = 'Login';
		$this->cur_menu = 'login';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		if($this->jCfg['member']['member_login']==1&&$this->jCfg['member']['member_id']!=''){
	    	redirect(base_url().'account');
	    	exit();
		}

		/* url redirect */
		//$data['msgs'] = isset($_GET['msgs'])?$_GET['msgs']:'';
		$next = isset($_GET['next'])?$_GET['next']:'';

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'login' ){
			$post_username 	= $this->input->post('member_username');
			$post_password 	= md5($this->input->post('member_password'));

			$where = '(member_username="'.$post_username.'" OR member_email = "'.$post_username.'")';
			$d = $this->db->get_where("mt_member",$where)->row();

			// $d = $this->db->get_where("mt_member",array(
			// 	'member_username'	=> $post_username
			// ))->row();
			if(count($d) > 0){
				if($post_password == $d->member_password){

					$arr = array('9');
				    if(in_array($d->member_status, $arr)){
						$this->jCfg['member'] 		= '';
						$this->_releaseSession();
						$data['msg'] = get_status_member($d->member_status)['msg'];
				    } else {
						$member_id 	 	 = $d->member_id;
						$member_name 	 = $d->member_name;
						$member_email 	 = $d->member_email;

						$this->db->update("mt_member",array("member_logindate"=>timestamp(),"member_token_password"=>NULL),array("member_id"=>$member_id));

						if($d->member_status == 1 && $d->member_verification != ''){
							$this->db->update("mt_member",array("member_verification"=>NULL),array("member_id"=>$member_id));
						}

						$this->jCfg['member']['member_id'] 		= $member_id;
						$this->jCfg['member']['member_name']	= $member_name;
						$this->jCfg['member']['member_email']	= $member_email;
						$this->jCfg['member']['member_login']	= 1;
						$this->_releaseSession();

						$url = isset($next)?$next:"";
						redirect($url);
				    }

				} else {
					$data['msg'] = 'Password Anda salah.';
				}
			} else {
				$data['msg'] = 'Email/Username Anda tidak terdaftar.';
			}
		}

		$data['next']	= '?next='.$next;
		$this->_v('account_login',$data);
	}

	function account_logout(){
		/* logout */
		$this->jCfg['member'] 		= '';
		$this->jCfg['cart'] 		= '';
		$this->_releaseSession();
		redirect(base_url());
	}

	function account_register(){
		$this->page     = 'Register';
		$this->cur_menu = 'register';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		if($this->jCfg['member']['member_login']==1&&$this->jCfg['member']['member_id']!=''){
	    	redirect(base_url().'account');
	    	exit();
		}

		/* url redirect */
		$next = isset($_GET['next'])?$_GET['next']:base_url().'account';
		$n = '?next='.$next;

		$data = "";
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'register' ){

			$this->DATA->table="mt_member";
			$user_email = $this->DATA->data_id(array(
				'member_email'	=> dbClean($_POST['member_email'])
			));

			if(empty($user_email->member_email)){

				if(checkIsBlacklist($_POST['member_email'])['found']){
					$data['msg'] = 'Maaf email kamu masuk dalam data blacklist. silahkan hubungi kami jika menurut anda itu adalah sebuah keliruan.';
					redirect(base_url().'register?msg='.$data['msg']);
				} else {
					$member_status = 0;
					$facebook = NULL;
					$twitter  = NULL;
					$google   = NULL;
					switch (dbClean($_POST['method'])) {
						case 'facebook': $facebook = dbClean($_POST['id']); $member_status = 1; break;
						case 'twitter': $twitter = dbClean($_POST['id']); $member_status = 1; break;
						case 'google': $google = dbClean($_POST['id']); $member_status = 1; break;
						default: break;
					}

					/* save data member */
					$data_members = array(
						'member_name'		=> dbClean(ucwords($_POST['member_name'])),
						'member_email'		=> dbClean($_POST['member_email']),
						'member_password'	=> md5(dbClean($_POST['member_password'])),
						'member_tgl_lahir'	=> dbClean($_POST['bday_yy']).'-'.dbClean($_POST['bday_mm']).'-'.dbClean($_POST['bday_dd'].' 00:00:00'),
						'member_phone'		=> dbClean($_POST['member_phone']),
						'member_jenis_kelamin'	=> dbClean($_POST['gender']),
						'member_status'		=> $member_status,
						'member_date'		=> timestamp(),
						'member_logindate'	=> timestamp(),
						'facebook'			=> $facebook,
						'twitter'			=> $twitter,
						'google'			=> $google,
						'newsletter'		=> 1
					);

					$title = dbClean(strtolower($_POST['member_name']));
					$data_members['member_username'] = generateUniqueUsername($title);

					$this->db->insert('mt_member', $data_members);
					$member_id 	= $this->db->insert_id();

					$this->jCfg['member']['member_id'] 		= $member_id;
					$this->jCfg['member']['member_name']	= dbClean($_POST['member_name']);
					$this->jCfg['member']['member_email']	= dbClean($_POST['member_email']);
					$this->jCfg['member']['member_login']	= 1;
					$this->_releaseSession();
					//debugCode($this->jCfg['member']);

					if($member_status == 0){
						$token = generateUniqueToken(30,'mt_member','member_verification');
						$this->db->update("mt_member",array("member_verification"=>$token),array("member_id"=>$member_id));

					}
					// $pesan_email  = 'Hai Sister <strong>'.dbClean($_POST['nama']).', Selamat ! </strong><br/><br/>';
					// $pesan_email .= 'Kamu sudah terdaftar sebagai member Vanilla Hijab <br/><br/>';
					// $pesan_email .= 'Silahkan Login menggunakan email dan password yang sudah didaftarkan <br>
					// 				 email = '.dbClean($_POST['email']).'<br>
					// 				 password = '.dbClean($_POST['password']).'<br/><br/>';
					// $pesan_email .= 'Jangan lewatkan jadwal upload kami untuk melakukan pembelian<br/><br/>';
					// $pesan_email .= 'Nikmati pengalaman berbelanja yang menyenangkan di vanillahijab.com <br/><br/>';

					// $pesan_email .= 'Selamat Berbelanja';
					// //debugCode($pesan_email);

					// $config['protocol'] = 'smtp';
					// $config['smtp_host'] = 'mail.vanillahijab.com';
					// $config['smtp_port'] = 25;
					// $config['smtp_user'] = 'no-reply@vanillahijab.com';
					// $config['smtp_pass'] = 'Hmw7v4&6';
					// $config['mailtype']  = 'html';

					// $this->load->library('email', $config);
					// $this->email->set_newline("\r\n");
					// $this->email->from("no-reply@vanillahijab.com", "Vanilla Hijab");
					// $this->email->to(dbClean($_POST['email']));
					// //$this->email->cc('raditya.pratama3@gmail.com');
					// $this->email->subject("Registrasi Membership di vanillahijab.com");
					// $this->email->message($pesan_email);

					// if($this->email->send()){

					// }

					$data['msg'] = 'Silahkan cek email anda untuk detail data.';
					redirect($next);
				}
			} else {
				$data['msg'] = 'Maaf alamat email telah terpakai';
			}
		}

		$data['next']	= '?next='.$next;
		$this->_v('account_register',$data);
	}

	function account_verification(){

		$email 		= isset($_GET['email'])?$_GET['email']:'';
		$token 		= isset($_GET['token'])?$_GET['token']:'';
		$type 		= isset($_GET['type'])?$_GET['type']:'';

		if($type=='create'){
			$member_id = $this->jCfg['member']['member_id'];
			if($member_id != ''){
				$d = $this->db->get_where("mt_member",array(
					'member_id'			=> $member_id,
					'member_status'		=> 0
				))->row();
				if(count($d) > 0){
					$token = generateUniqueToken(30,'mt_member','member_verification');
					$this->db->update("mt_member",array("member_verification"=>$token),array("member_id"=>$member_id));
					// $pesan_email  = 'Hai Sister <strong>'.dbClean($_POST['nama']).', Selamat ! </strong><br/><br/>';
					// $pesan_email .= 'Kamu sudah terdaftar sebagai member Vanilla Hijab <br/><br/>';
					// $pesan_email .= 'Silahkan Login menggunakan email dan password yang sudah didaftarkan <br>
					// 				 email = '.dbClean($_POST['email']).'<br>
					// 				 password = '.dbClean($_POST['password']).'<br/><br/>';
					// $pesan_email .= 'Jangan lewatkan jadwal upload kami untuk melakukan pembelian<br/><br/>';
					// $pesan_email .= 'Nikmati pengalaman berbelanja yang menyenangkan di vanillahijab.com <br/><br/>';

					// $pesan_email .= 'Selamat Berbelanja';
					// //debugCode($pesan_email);

					// $config['protocol'] = 'smtp';
					// $config['smtp_host'] = 'mail.vanillahijab.com';
					// $config['smtp_port'] = 25;
					// $config['smtp_user'] = 'no-reply@vanillahijab.com';
					// $config['smtp_pass'] = 'Hmw7v4&6';
					// $config['mailtype']  = 'html';

					// $this->load->library('email', $config);
					// $this->email->set_newline("\r\n");
					// $this->email->from("no-reply@vanillahijab.com", "Vanilla Hijab");
					// $this->email->to(dbClean($_POST['email']));
					// //$this->email->cc('raditya.pratama3@gmail.com');
					// $this->email->subject("Registrasi Membership di vanillahijab.com");
					// $this->email->message($pesan_email);

					// if($this->email->send()){

					// }

					$data['msg'] = "Kode verifikasi berhasil dikirim ke ".$d->member_email.", Silahkan cek email anda.";
					redirect(base_url()."account?msg=".urlencode($data['msg'])."&type_msg=success");

				}
			} else {
				redirect(base_url());
			}
		} else {
			if($email!=''&&$token!=''){
				$d = $this->db->get_where("mt_member",array(
					'member_email'			=> $email,
					'member_verification'	=> $token,
					'member_status'			=> 0
				))->row();
				if(count($d) > 0){
					$this->db->update("mt_member",array("member_status"=>1,"member_verification"=>NULL),array("member_id"=>$d->member_id));

					// $pesan_email  = 'Hai Sister <strong>'.dbClean($_POST['nama']).', Selamat ! </strong><br/><br/>';
					// $pesan_email .= 'Kamu sudah terdaftar sebagai member Vanilla Hijab <br/><br/>';
					// $pesan_email .= 'Silahkan Login menggunakan email dan password yang sudah didaftarkan <br>
					// 				 email = '.dbClean($_POST['email']).'<br>
					// 				 password = '.dbClean($_POST['password']).'<br/><br/>';
					// $pesan_email .= 'Jangan lewatkan jadwal upload kami untuk melakukan pembelian<br/><br/>';
					// $pesan_email .= 'Nikmati pengalaman berbelanja yang menyenangkan di vanillahijab.com <br/><br/>';

					// $pesan_email .= 'Selamat Berbelanja';
					// //debugCode($pesan_email);

					// $config['protocol'] = 'smtp';
					// $config['smtp_host'] = 'mail.vanillahijab.com';
					// $config['smtp_port'] = 25;
					// $config['smtp_user'] = 'no-reply@vanillahijab.com';
					// $config['smtp_pass'] = 'Hmw7v4&6';
					// $config['mailtype']  = 'html';

					// $this->load->library('email', $config);
					// $this->email->set_newline("\r\n");
					// $this->email->from("no-reply@vanillahijab.com", "Vanilla Hijab");
					// $this->email->to(dbClean($_POST['email']));
					// //$this->email->cc('raditya.pratama3@gmail.com');
					// $this->email->subject("Registrasi Membership di vanillahijab.com");
					// $this->email->message($pesan_email);

					// if($this->email->send()){

					// }

					$data['msg'] = "Selamat akun anda sudah Aktif";
					redirect(base_url()."account-verification?msg=".urlencode($data['msg'])."&type_msg=success");
				} else {
					$data['msg'] = "Akun anda tidak ditemukan";
					redirect(base_url()."account-verification?msg=".urlencode($data['msg'])."&type_msg=error");
				}
			} else {
				if(!isset($_GET['msg'])){
					redirect(base_url());
				}
			}
		}
	}

	function account_reset_password(){
		$this->page     = 'Reset Password';
		$this->cur_menu = 'login';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		if($this->jCfg['member']['member_login']==1&&$this->jCfg['member']['member_id']!=''){
	    	redirect(base_url().'account');
	    	exit();
		}

		/* data post */
		$data = "";
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'reset_password' ){
			$email        = dbClean(trim($_POST['email']));
			$d = $this->db->get_where("mt_member",array(
				'member_email'		=> $email
			))->row();
			if(count($d) > 0){
				$member_id 	 	 = $d->member_id;
				$member_name 	 = $d->member_name;
				$member_email 	 = $d->member_email;
				$member_password = $d->member_password;

				$token = generateUniqueToken(30,'mt_member','member_token_password');
				$this->db->update("mt_member",array("member_token_password"=>$token),array("member_id"=>$member_id));

				// $link = http://localhost/bukapromo/create-password?token=g8ZfXCoFNlA6IzWvaMbOh0U95DpcyK&email=fheby.kere.hore@gmail.com

				// $pesan_email  = 'Hai Sister. <strong>'.$member_name.'</strong><br/><br/>';
				// $pesan_email .= 'Silahkan klik link berikut untuk merubah password anda:<br/>';
				// $pesan_email .= 'Link rubah password, klik <a href="'.base_url().'change-password/'.$member_id.'">'.base_url().'change-password</a><br/><br/>';
				// $pesan_email .= 'Regards';
				// //debugCode($pesan_email);

				// $config['protocol'] = 'smtp';
				// $config['smtp_host'] = 'mail.vanillahijab.com';
				// $config['smtp_port'] = 25;
				// $config['smtp_user'] = 'no-reply@vanillahijab.com';
				// $config['smtp_pass'] = 'Hmw7v4&6';
				// $config['mailtype']  = 'html';

				// $this->load->library('email', $config);
				// $this->email->set_newline("\r\n");
				// $this->email->from("no-reply@vanillahijab.com", "vanilla hijab");
				// $this->email->to($email);
				// $this->email->cc('raditya.pratama3@gmail.com');
				// $this->email->subject("Rubah Password di vanillahijab.com");
				// $this->email->message($pesan_email);

				// if($this->email->send()){

				// }

				$data['msg'] = 'Silahkan cek email anda di inbox atau junk folder untuk merubah password';
			} else {
				$data['err'] = 'Data Anda tidak terdaftar, Silahkan hubungi kami!';
			}
		}
		$this->_v('account_reset_password',$data);
	}

	function account_create_password(){
		$this->page     = 'Create New Password';
		$this->cur_menu = 'login';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		if($this->jCfg['member']['member_login']==1&&$this->jCfg['member']['member_id']!=''){
	    	redirect(base_url().'account');
	    	exit();
		}

		/* data post */
		$data = "";
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'create_new_password' ){
			$email        = dbClean(trim($_POST['email']));
			$token     	  = dbClean(trim($_POST['token']));
			$password 	  = dbClean(trim($_POST['password']));
			$password2    = dbClean(trim($_POST['password2']));
			$d = $this->db->get_where("mt_member",array(
				'member_email'			=> $email,
				'member_token_password'	=> $token
			))->row();
			if(count($d) > 0){
				$member_id 	 	 = $d->member_id;
				if($password == $password2){
					$this->db->update("mt_member",array("member_password"=>md5($password),"member_token_password"=>NULL),array("member_id"=>$member_id));

					$data['msg'] = "Kata sandi berhasil dirubah";
					redirect(base_url()."reset-password?msg=".urlencode($data['msg'])."&type_msg=success");
				} else {
					$data['err'] = "Konfrimasi Kata sandi tidak sama.";
					redirect(base_url()."create-password?token=".$token."&email=".$email."&msg=".urlencode($data['err'])."&type_msg=error");
				}
			} else {
				$data['err'] = 'Data Anda tidak terdaftar, Silahkan hubungi kami!';
				redirect(base_url()."reset-password?msg=".urlencode($data['err'])."&type_msg=error");
			}
		} else {
			$email 		= isset($_GET['email'])?$_GET['email']:'';
			$token 		= isset($_GET['token'])?$_GET['token']:'';

			if($email!=''&&$token!=''){
				$d = $this->db->get_where("mt_member",array(
					'member_email'			=> $email,
					'member_token_password'	=> $token
				))->row();
				if(count($d) == 0){
					$data['err'] = 'Data Anda tidak terdaftar, Silahkan hubungi kami!';
					redirect(base_url()."reset-password?msg=".urlencode($data['err'])."&type_msg=error");
				}
			} else {
				redirect(base_url()."reset-password");
			}
		}
		$this->_v('account_create_password',$data);
	}

	function account(){
		$this->page     = 'My Account';
		$this->cur_menu = 'account';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'account';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}
		$data['me'] = $this->db->get_where("mt_member",array(
			"member_id"	=> $this->user_login
		))->row();

		$data['address_default'] = $this->db->order_by('member_address_status','DESC')->get_where("mt_member_address",array(
			"member_id"	=> $this->user_login
		),1,0)->row();

		$this->_v("account",$data);
	}

	function account_form(){
		$this->page     = 'My Profile';
		$this->cur_menu = 'profile';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'profile';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		$data = array();
		$data['me'] = $this->db->get_where("mt_member",array(
			"member_id"	=> $this->user_login
		))->row();

		$data['id_member'] = $this->jCfg['member']['member_id'];

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
			$this->DATA->table="mt_member";

			$post_password 	= md5($this->input->post('member_password'));
			if($post_password == $data['me']->member_password){
				/* save data member */
				$data_members = array(
					'member_tempat_lahir'	=> dbClean($_POST['member_tempat_lahir']),
					'member_tgl_lahir'		=> dbClean($_POST['bday_yy']).'-'.dbClean($_POST['bday_mm']).'-'.dbClean($_POST['bday_dd'].' 00:00:00'),
					'member_jenis_kelamin'	=> dbClean($_POST['gender']),
					'member_phone'			=> dbClean($_POST['member_phone'])
				);

				if($data['me']->member_username_change==1){
					if(in_array($data['me']->member_status, array('0','1'))) {
						if(dbClean($_POST['member_username'])!='') {
							$this->data_form = $this->DATA->data_id(array(
								'member_username'	=> dbClean($_POST['member_username']),
								'member_id !=' 		=> $this->user_login
							));
							if((empty($this->data_form->member_username))&&(checkIsRoute($thisVal))){
								redirect(base_url()."profile?msg=".urlencode('Username sudah ada.')."&type_msg=error");
								exit();
							} else {
								$data_members['member_username'] 		= dbClean($_POST['member_username']);
								$data_members['member_username_change'] = 0;
							}
						}
					}
				}

				$a = $this->_save_master(
					$data_members,
					array(
						'member_id' => $this->user_login
					),
					$this->user_login
				);
				redirect(base_url()."profile?msg=".urlencode('Profil Anda sudah berhasil di rubah.')."&type_msg=success");
			} else {
				redirect(base_url()."profile?msg=".urlencode('Password anda salah.')."&type_msg=error");
			}
		}

		$this->_v("account_form",$data);
	}

	function account_change_email(){
		$this->page     = 'Permintaan Ubah Email';
		$this->cur_menu = 'profile';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'change-email';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		$data = array();
		$data['me'] = $this->db->get_where("mt_member",array(
			"member_id"	=> $this->user_login
		))->row();

	    if(!in_array($data['me']->member_status, array('0','1'))) {
			$data['msg'] = get_status_member($data['me']->member_status)['msg'];
			redirect(base_url()."profile");
			exit();
	    }

		$data['id_member'] = $this->jCfg['member']['member_id'];

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
			$this->DATA->table="mt_member";

			$post_password 		 = md5($this->input->post('member_password'));
			$member_email 		 = $this->input->post('member_email');
			$member_verification = $this->input->post('member_verification');

			if($post_password == $data['me']->member_password){
				if($member_verification == $data['me']->member_verification){

					$r = $this->db->get_where("mt_member",array(
						"member_email"	=> $member_email,
						"member_id != "	=> $this->user_login
					))->row();
					if(count($r)==0){
						/* save data member */
						$data_members = array(
							'member_email'			=> $member_email,
							'member_verification'	=> NULL
						);

						$a = $this->_save_master(
							$data_members,
							array(
								'member_id' => $this->user_login
							),
							$this->user_login
						);

						redirect(base_url()."change-email?msg=".urlencode('Email berhasil di rubah.')."&type_msg=success");
					} else {
						redirect(base_url()."change-email?msg=".urlencode('Email sudah digunakan.')."&type_msg=error");
					}
				} else {
					redirect(base_url()."change-email?msg=".urlencode('Kode Verifikasi anda salah.')."&type_msg=error");
				}
			} else {
				redirect(base_url()."change-email?msg=".urlencode('Kata sandi anda salah.')."&type_msg=error");
			}
		}

		$this->_v("account_change_email",$data);
	}

	function account_change_password(){
		$this->page     = 'Ubah Kata Sandi';
		$this->cur_menu = 'profile';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'change-password';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		$data = array();
		$data['me'] = $this->db->get_where("mt_member",array(
			"member_id"	=> $this->user_login
		))->row();

	    if(!in_array($data['me']->member_status, array('0','1'))) {
			$data['msg'] = get_status_member($data['me']->member_status)['msg'];
			redirect(base_url()."profile");
			exit();
	    }

		$data['id_member'] = $this->jCfg['member']['member_id'];

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
			$this->DATA->table="mt_member";

			$post_password 			= md5($this->input->post('old_member_password'));
			$new_member_password 	= md5($this->input->post('new_member_password'));
			$new_member_password2 	= md5($this->input->post('new_member_password2'));

			if($post_password == $data['me']->member_password){
				if($new_member_password == $new_member_password2){
					/* save data member */
					$data_members = array(
						'member_password'		=> $new_member_password
					);

					$a = $this->_save_master(
						$data_members,
						array(
							'member_id' => $this->user_login
						),
						$this->user_login
					);

					redirect(base_url()."change-password?msg=".urlencode('Kata sandi berhasil di rubah.')."&type_msg=success");
				} else {
					redirect(base_url()."change-password?msg=".urlencode('Konfirmasi kata sandi tidak sama.')."&type_msg=error");
				}
			} else {
				redirect(base_url()."change-password?msg=".urlencode('Kata sandi lama anda salah.')."&type_msg=error");
			}
		}

		$this->_v("account_change_password",$data);
	}

	function change_photo_profile(){
		$this->page     = 'Change Photo Profile';
		$this->cur_menu = 'profile';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'change-photo-profile';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'upload' ){
			$data = $_POST['thisSrc'];
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
			file_put_contents($temp_file_path, $data);
			$image_info = getimagesize($temp_file_path);
			$_FILES['member_photo'] = array(
				'name' => 'avatar.jpg',
				'tmp_name' => $temp_file_path,
				'size'  => filesize($temp_file_path),
				'error' => UPLOAD_ERR_OK,
				'type'  => 'jpg',
			);

			$this->DATA->table="mt_member";
			$this->upload_path="./assets/collections/photo/";
			$this->upload_resize  = array(
				array('name'	=> 'thumb','width'	=> 50, 'quality'	=> '85%'),
				array('name'	=> 'small','width'	=> 200, 'quality'	=> '85%')
			);
			$this->_uploaded(
			array(
				'id'		=> $this->user_login,
				'input'		=> 'member_photo',
				'param'		=> array(
								'field' => 'member_photo',
								'par'	=> array('member_id' => $this->user_login)
							)
			));
			$err = false;
			$msg = 'Sukses';
		}

		$return = array('msg' => $msg,'err' => $err);
		die(json_encode($return));
		exit();
	}

	function check_form_member(){
		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_form' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$thisChkId     = dbClean(trim($_POST['thisChkId']));
			$thisChkParent = dbClean(trim($_POST['thisChkParent']));
			$thisChkRel    = dbClean(trim($_POST['thisChkRel']));

			$this->DATA->table="mt_member";
			if(trim($thisVal)!=''){
				if(trim($thisChkId)!=''){
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	   => $thisVal,
						'member_id !=' => $thisChkId
					));
				} else {
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	=> $thisVal
					));
				}
				if(empty($this->data_form->$thisChkRel)){
					$err = false;
					$msg = '';
					if(($thisChkRel=='member_username')&&(checkIsRoute($thisVal))){
						$err = true;
						$msg = 'Data sudah ada...';
					}
				} else {
					$err = true;
					$msg = 'Data sudah ada...';
				}
			}

		}

		$return = array('msg' => $msg,'err' => $err);
		die(json_encode($return));
		exit();
	}

	function account_send_verification(){
		$this->page     = 'Send Verification';
		$this->cur_menu = 'profile';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login');
		}

		$err = true;
		$msg = 'Kode Verifikasi gagal dikirim.';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'send' ){

			$token = generateUniqueToken(6,'mt_member','member_verification');
			$this->db->update("mt_member",array("member_verification"=>$token),array("member_id"=>$this->user_login));

			//KOde verifikasi permintaan perubahan email
			// $pesan_email  = 'Hai <strong>'.dbClean($_POST['nama']).', Selamat ! </strong><br/><br/>';
			// $pesan_email .= 'Kamu sudah terdaftar sebagai member Vanilla Hijab <br/><br/>';
			// $pesan_email .= 'Silahkan Login menggunakan email dan password yang sudah didaftarkan <br>
			// 				 email = '.dbClean($_POST['email']).'<br>
			// 				 password = '.dbClean($_POST['password']).'<br/><br/>';
			// $pesan_email .= 'Jangan lewatkan jadwal upload kami untuk melakukan pembelian<br/><br/>';
			// $pesan_email .= 'Nikmati pengalaman berbelanja yang menyenangkan di vanillahijab.com <br/><br/>';

			// $pesan_email .= 'Selamat Berbelanja';
			// //debugCode($pesan_email);

			// $config['protocol'] = 'smtp';
			// $config['smtp_host'] = 'mail.vanillahijab.com';
			// $config['smtp_port'] = 25;
			// $config['smtp_user'] = 'no-reply@vanillahijab.com';
			// $config['smtp_pass'] = 'Hmw7v4&6';
			// $config['mailtype']  = 'html';

			// $this->load->library('email', $config);
			// $this->email->set_newline("\r\n");
			// $this->email->from("no-reply@vanillahijab.com", "Vanilla Hijab");
			// $this->email->to(dbClean($_POST['email']));
			// //$this->email->cc('raditya.pratama3@gmail.com');
			// $this->email->subject("Registrasi Membership di vanillahijab.com");
			// $this->email->message($pesan_email);

			// if($this->email->send()){

			// }
			$err = false;
			$msg = 'Kode Verifikasi berhasil dikirim, silahkan cek email anda.';
		}

		$return = array('msg' => $msg,'err' => $err);
		die(json_encode($return));
		exit();
	}

	function account_address($action="",$id=""){
		$this->page     = 'Daftar Alamat';
		$this->cur_menu = 'address';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'address';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

        $id = explode("-", $id);
		$id = dbClean(trim($id[0]));

		$data['address'] = $this->db->order_by('member_address_status','DESC')->get_where("mt_member_address",array(
			"member_id"	=> $this->user_login
		))->result();

		if($action=='add' || $action=='edit'){

            $get_data_member_status = get_data_member_status();
			if(!in_array($get_data_member_status, array('0','1'))) {
				$data['msg'] = get_status_member($get_data_member_status)['msg'];
				redirect(base_url()."address");
				exit();
		    }

			if($action=='edit'){
				$data['address'] = $this->db->get_where("mt_member_address",array(
					"member_id"			=> $this->user_login,
					"member_address_id"	=> $id
				))->row();
				if(count($data['address'])==0){
					redirect(base_url()."address?msg=".urlencode('Alamat tidak ditemukan.')."&type_msg=error");
					exit();
				}
			}

			if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
				$error = true;
				$data = array(
					'member_address_title'			=> dbClean(ucwords($_POST['member_address_title'])),
					'member_address_name'			=> dbClean(ucwords($_POST['member_address_name'])),
					'member_address_desc'			=> dbClean($_POST['member_address_desc']),
					'member_address_city_id'		=> dbClean($_POST['member_address_city_id']),
					'member_address_postal_code'	=> dbClean($_POST['member_address_postal_code']),
					'member_address_phone'			=> dbClean($_POST['member_address_phone'])
				);

				$data['member_address_province_id'] = $this->db->get_where("mt_app_city",array(
															"city_id"	=> dbClean($_POST['member_address_city_id'])
														))->row()->city_province_id;

				if (dbClean($_POST['member_address_id']) == ""){
					$data['member_id']             = $this->user_login;
					$data['member_address_date']   = timestamp();

					$member_address_status = 1;
					$r = $this->db->get_where("mt_member_address",array(
						"member_id"			=> $this->user_login
					))->row();
					if(count($r)>0){ $member_address_status = 0; }
					$data['member_address_status'] = $member_address_status;

					$error = false;
				} else {
					$r = $this->db->get_where("mt_member_address",array(
						"member_id"			=> $this->user_login,
						"member_address_id"	=> dbClean($_POST['member_address_id'])
					))->row();
					if(count($r)>0){
						$error = false;
					}
				}

				if($error == false){
					$this->DATA->table="mt_member_address";
					$a = $this->_save_master(
						$data,
						array(
							'member_address_id' => dbClean($_POST['member_address_id'])
						),
						dbClean($_POST['member_address_id'])
					);
					redirect(base_url()."address?msg=".urlencode('Alamat berhasil disimpan.')."&type_msg=success");
				} else {
					redirect(base_url()."address?msg=".urlencode('Alamat gagal disimpan.')."&type_msg=error");
				}
			}

			$this->_v("account_address_form",$data);
		} else if($action=='delete'){
			$data['address'] = $this->db->get_where("mt_member_address",array(
				"member_id"			=> $this->user_login,
				"member_address_id"	=> $id
			))->row();
			if(count($data['address'])==0){
				redirect(base_url()."address?msg=".urlencode('Alamat tidak ditemukan.')."&type_msg=error");
				exit();
			} else {
				$this->DATA->table="mt_member_address";
				$this->DATA->_delete(array("member_address_id"	=> idClean($id)),true);
				redirect(base_url()."address?msg=".urlencode('Alamat utama berhasil dihapus.')."&type_msg=success");
			}
		} else if($action=='set_primary'){
			$data['address'] = $this->db->get_where("mt_member_address",array(
				"member_id"			=> $this->user_login,
				"member_address_id"	=> $id
			))->row();
			if(count($data['address'])==0){
				redirect(base_url()."address?msg=".urlencode('Alamat tidak ditemukan.')."&type_msg=error");
				exit();
			} else {
				$this->db->update("mt_member_address",array("member_address_status"=>0),array("member_id"=>$this->user_login));
				$this->db->update("mt_member_address",array("member_address_status"=>1),array("member_address_id"=>$id));
				redirect(base_url()."address?msg=".urlencode('Alamat utama berhasil disimpan.')."&type_msg=success");
			}
		} else {
			$this->_v("account_address",$data);
		}

	}

	function account_wishlist($action=""){
		$this->page     = 'My Wishlist';
		$this->cur_menu = 'wishlist';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'wishlist';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		/* paging wishlist */
		$this->per_page = 20;
		$this->uri_segment = 2;
		$this->data_table = $this->MM->data_member_wishlist(array(
			'member_id' 			=> $this->user_login,
			'limit' 				=> $this->per_page,
			'offset'				=> $this->uri->segment($this->uri_segment)
		));

		$data = $this->_data_front(array(
			'base_url'		=> base_url().'wishlist/'
		));

		$this->_v("account_wishlist",$data);

	}

	function account_list_orders($action=""){
		$this->page     = 'List Orders';
		$this->cur_menu = 'list_orders';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$n = 'ya';
		$next = isset($_GET['next'])?$_GET['next']:base_url().'list-orders';
		if(isset($n)&&$n!=''){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['member_id'])?$this->jCfg['member']['member_id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}

		/* paging wishlist */
		// $this->per_page = 20;
		// $this->uri_segment = 2;
		// $this->data_table = $this->MM->data_member_wishlist(array(
		// 	'member_id' 			=> $this->user_login,
		// 	'limit' 				=> $this->per_page,
		// 	'offset'				=> $this->uri->segment($this->uri_segment)
		// ));

		// $data = $this->_data_front(array(
		// 	'base_url'		=> base_url().'wishlist/'
		// ));

		$this->_v("account_list_orders",$data);

	}

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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
