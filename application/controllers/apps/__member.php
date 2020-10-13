<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class Member extends FrontController {
	var $cur_menu = '';

	function __construct()  
	{
		parent::__construct(); 

		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
	        header('Access-Control-Max-Age: 86400');    // cache for 1 day
	    }

	    // Access-Control headers are received during OPTIONS requests
	    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
	    		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	    	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
	    		header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	    	exit(0);
	    }

	}

	function index(){
		
	}

	function login_admin(){
		$error 	= true;
	    $msg 	= 'Please check your connection!';
		$rows 	= array();
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$thisAction = mysql_real_escape_string($request->thisAction);
			if($thisAction == 'login'){
				$par_filter = array();
				foreach ($request as $key => $value) {
					$par_filter[$key] = $value;
				}

				$rows['user'] = array();
				$this->db->select("*");
				$where = "(user_name='".strtolower($par_filter['username'])."' OR 
						   user_email='".$par_filter['username']."'
						)"; 
				$this->db->where($where);
				$this->db->limit(1);
				$m = $this->db->get("mt_app_user")->row();
				if(!empty($m)){
					if($m->user_password == md5(strtolower($par_filter['password']))){
						$rows['user'] = $m;
						$error 	= false;
						$msg    = 'Berhasil login.';
					} else {
						$error 	= true;
						$msg    = 'Username atau Password salah.';
					}
				} else {
					$error 	= true;
					$msg    = 'Username atau Password salah.';
				}

				$rows['error'] = $error;
				$rows['msg']   = $msg;
			}
		}
		
		die(json_encode($rows));
		exit();
	}

	function login(){
		// debugCode('login');

	    $member_id 		= '';
	    $member_name 	= '';
	    $member_email 	= '';
	    $member_token 	= '';
	    $member_level  	= 'public';
	    $error 			= true;
	    $msg 			= '';
	    $postdata = file_get_contents("php://input");
	    if (isset($postdata)) {
	    	$request 	= json_decode($postdata);
			$username 	= mysql_real_escape_string($request->username);
			$password	= mysql_real_escape_string($request->password);
			$thisAction	= mysql_real_escape_string($request->thisAction);

			if($thisAction == 'login'){
				if($username != '' && $password != ''){
					$post_username 	= $username;
					$post_password 	= md5($password);

					$where = '(member_username="'.$post_username.'" OR member_email = "'.$post_username.'")';
					$d = $this->db->get_where("mt_member",$where)->row();
					if(count($d) > 0){
						if($post_password == $d->member_password){
							$arr = array('9');
						    if(in_array($d->member_status, $arr)){
						    	$msg   = get_status_member($d->member_status)['msg'];
						    	$error = true;
						    } else {
								$member_id 	 	= $d->member_id;
								$member_name 	= $d->member_name;
								$member_email 	= $d->member_email;
								$member_token 	= $d->member_token_apps;
								$member_level  	= 'public';
								$error 			= false;

								$this->db->update("mt_member",array("member_logindate"=>timestamp(),"member_token_password"=>NULL),array("member_id"=>$member_id));

								if($d->member_status == 1 && $d->member_verification != ''){
									$this->db->update("mt_member",array("member_verification"=>NULL),array("member_id"=>$member_id));
								}

								if($member_token == ''){
									$member_token = generateUniqueToken(20,'mt_member','member_token_apps');
									$this->db->update("mt_member",array("member_token_apps"=>$member_token),array("member_id"=>$member_id));
								}
						    }
						} else {
							$msg = 'Password Anda salah.';
						}
					} else {
						$msg = 'Email/Username Anda tidak terdaftar.';
					}

					$return = array('member_id' => $member_id,
									'member_name' => $member_name,
									'member_email' => $member_email,
									'member_level' => $member_level,
									'member_token' => $member_token,
									'error' => $error,
									'msg' => $msg
								);
					die(json_encode($return));
					exit();
				}
			}
		}
	}

	function register(){
		debugCode('register');

		// $error 	= true;
	 //    $msg 	= 'Please check your connection!';
		// $rows 	= array();
	 //    $postdata = file_get_contents("php://input");
	 //    if (isset($postdata)) {
	 //    	$request 	= json_decode($postdata);
		// 	$thisAction = mysql_real_escape_string($request->thisAction);
		// 	if($thisAction == 'register'){
		// 		$par_filter = array();
		// 		foreach ($request as $key => $value) {
		// 			$par_filter[$key] = $value;
		// 		}

		// 		$this->db->select("*");
		// 		$where = "(user_name='".strtolower($par_filter['username'])."' OR 
		// 				   user_email='".$par_filter['email']."'
		// 				)"; 
		// 		$this->db->where($where);
		// 		$this->db->limit(1);
		// 		$m = $this->db->get("mt_app_user_test")->row();
		// 		if(!empty($m)){
		// 			$error 	= true;
		// 			$msg    = 'Username atau email sudah terdaftar.';
		// 		} else {
		// 			$data = array(
		// 				'user_name'			=> strtolower($par_filter['username']),
		// 				'user_fullname'		=> ucwords($par_filter['fullname']),
		// 				'user_email'		=> $par_filter['email'],
		// 				'user_password'		=> md5(strtolower($par_filter['password'])),
		// 				'time_add'			=> date('Y-m-d H:i:s')
		// 			);

		// 			$this->DATA->table="mt_app_user_test";
		// 			$a = $this->_save_master( 
		// 				$data,
		// 				array(
		// 					'user_id' => ''
		// 				),
		// 				''
		// 			);
		// 			$id = $a['id'];
		// 			if($id != ''){
		// 				$error 	= false;
		// 				$msg    = 'Registrasi berhasil disimpan.';
		// 			}
		// 		}

		// 		$rows['error'] = $error;
		// 		$rows['msg']   = $msg;
		// 	}
		// }
		
		// die(json_encode($rows));
		// exit();
	}

	function get_member($id=''){
		$exp 	= explode("-", $id);
		$id 	= dbClean(trim($exp[0]));
		$token 	= dbClean(trim($exp[1]));
		$rows 	= array();
		if(trim($id)!=''&&trim($token)!=''){
			$d = $this->db->get_where("mt_member",array(
				'member_id'			=> $id,
				'member_token_apps'	=> $token
			))->row();
			if(count($d) > 0){
				// $rows['product'][] = $row;
		        // $jsonArrayObject = (array('id_product' => $row["id_product"], 'name' => $row["name"], 'images' => $path_images.$row["images"]));
		        $jsonArrayObject = $d;
		        $rows['member'] = $jsonArrayObject;
			}
		}

		print(json_encode($rows));
		exit();
	}

}
