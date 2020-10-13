<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");

class Me extends AdminController {

	function __construct()
	{
		parent::__construct();
		$this->_set_title( 'Dashboard' );

		$this->upload_path="./assets/collections/photo/";
		$this->upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 50, 'quality'	=> '85%'),
			array('name'	=> 'small','width'	=> 200, 'quality'	=> '85%')
		);
		$this->image_size_str = "Size: 200px x 200px";

		//$this->output->enable_profiler(TRUE);

		if(!isset($this->jCfg['dashboard']['chart_type'])){
			$this->jCfg['dashboard']['chart_type'] = 'pie';
			$this->_releaseSession();
		}

		if(!isset($this->jCfg['dashboard']['report_type'])){
			$this->jCfg['dashboard']['report_type'] = 'wilayah';
			$this->_releaseSession();
		}

		$this->user_id 			= isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'';
		$this->usergroup 		= get_user_group($this->user_id);
		$this->store_id 		= get_user_store($this->user_id);
	}

	function set_chart_type(){
		$this->jCfg['dashboard']['chart_type'] = isset($_GET['v'])?dbClean($_GET['v']):'';
		$this->_releaseSession();

		$go = isset($_GET['next'])?$_GET['next']:$this->own_link;
		redirect($go);
	}

	function set_report_type(){
		$this->jCfg['dashboard']['report_type'] = isset($_GET['v'])?dbClean($_GET['v']):'';
		$this->_releaseSession();

		$go = isset($_GET['next'])?$_GET['next']:$this->own_link;
		redirect($go);
	}

	function index(){
		$data = array();

        check_product_by_group();

		$this->_v("index",$data);
	}

	function user_guide(){
		$this->_set_title( 'User Guide' );
		$this->_v("user_guide",array());
	}

	function change_background(){
		// $this->_set_title( 'Ganti Latar Belakang & Warna' );
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'save' ){
			$color = (($_POST['thisColor']=="")?NULL:$_POST['thisColor']);
			$bg    = (($_POST['thisBg']=="")?NULL:$_POST['thisBg']);

			$this->db->update("mt_app_user",array(
				'user_background' 	=> $bg,
				'user_themes'		=> $color
			),array(
				'user_id'		=> $this->jCfg['user']['id']
			));

			//set sesstion...
			$this->jCfg['user']['bg']		= $bg;
			$this->jCfg['user']['color']	= $color;
			$this->_releaseSession();
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}

	function profile(){
		$this->_set_title('Profile Saya');
		$this->breadcrumb[] = array(
			"title"		=> "Profile"
		);

		$this->_v("profile-view",array(
			"data"	=> $this->db->get_where("mt_app_user",array(
				"user_id"	=> $this->jCfg['user']['id']
			))->row()
		));
	}

	function edit_profile(){

		$this->_set_title('Update Profile Saya');

		$this->breadcrumb[] = array(
			"title"		=> "Profile",
			"url"		=> site_url("admin/me/profile")
		);

		$this->breadcrumb[] = array(
			"title"		=> "Update Profile"
		);

		if( isset($_POST['update']) ){

			$this->DATA->table = "mt_app_user";

			$data = array(
				'user_fullname'		=> dbClean($_POST['user_fullname']),
				'user_email'		=> dbClean($_POST['user_email'])
			);

			if( isset($_POST['user_name']) && trim($_POST['user_name']) != ''){
				$data['user_name'] = dbClean($_POST['user_name']);
				$this->jCfg['user']['name'] = dbClean($_POST['user_name']);
			}
			if( isset($_POST['user_password']) && trim($_POST['user_password']) != ''){
				$data['user_password'] = md5(dbClean($_POST['user_password']));
			}

			$a = $this->_save_master(
				$data,
				array(
					'user_id' => $this->jCfg['user']['id']
				),
				$this->jCfg['user']['id']
			);

			$this->jCfg['user']['fullname'] = dbClean($_POST['user_fullname']);
			$this->jCfg['user']['email']    = dbClean($_POST['user_email']);
			$this->_releaseSession();

			$id = $a['id'];
			if(dbClean($_POST['remove_images']) == 1){
				$this->_delte_old_files(
				array(
					'field' => 'user_photo',
					'par'	=> array('user_id' => $id)
				));

				$this->db->update("mt_app_user",array("user_photo"=>NULL),array("user_id"=>$id));
			} else {
				// $id = $this->jCfg['user']['id'];
				// $this->_uploaded(
				// array(
				// 	'id'		=> $id ,
				// 	'input'		=> 'user_photo',
				// 	'param'		=> array(
				// 					'field' => 'user_photo',
				// 					'par'	=> array('user_id' => $id)
				// 				)
				// ));
			}

			redirect(site_url("admin/me/profile?msg=".urlencode('Update data user success')."&type_msg=success"));
		}

		$this->_v("profile-edit",array(
			"data"	=> $this->db->get_where("mt_app_user",array(
					"user_id"	=> $this->jCfg['user']['id']
				))->row()
		));
	}

	function change_password(){
		$this->breadcrumb[] = array(
			"title"		=> "Profile",
			"url"		=> site_url("admin/me/profile")
		);
		$this->breadcrumb[] = array(
			"title"		=> "Ubah Password"
		);

		$pesan="";
		if(isset($_POST['btn_simpan'])){
			$pass_lama = md5(dbClean($_POST['old_pass']));
			$this->DATA->table="mt_app_user";
			$m1 = $this->DATA->_getall(array(
				"user_name"		=> $this->jCfg['user']['name'],
				"user_password"	=> $pass_lama
			));
			if(count($m1)>0){
				$pass_baru = md5(dbClean($_POST['new_pass']));
				$mx = $this->DATA->_update(
					array(
						"user_name"		=> $this->jCfg['user']['name']
					),array(
						"user_password" => $pass_baru
					)
				);
				$pesan = ($mx)?"Success update your password":"Success update your password";
				$mtype = ($mx)?"success":"error";
			}else{
				$pesan ="Your old password is not correctly!";
				$mtype = "error";
			}

			redirect(site_url("admin/me/change_password?msg=".urlencode($pesan)."&type_msg=".$mtype));

		}

		$this->_set_title('Ubah Password');
		$this->_v("change-password",array(
			"pesan"	=> $pesan
		));
	}

	function change_avatar(){

		if(isset($_POST['avatar_src'])){
			$this->DATA->table = "mt_app_user";
			// $avatar_data = json_decode('['.$_POST['avatar_data'].']', true);
			// foreach($avatar_data as $k => $v){
			// 	$x = $v['x'];
			// 	$y = $v['y'];
			// 	$w = $v['width'];
			// 	$h = $v['height'];
			// 	$r = $v['rotate'];
			// }

			$data = $_POST['avatar_src'];
			list($type, $data) = explode(';', $data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			$temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
			 file_put_contents($temp_file_path, $data);
			 $image_info = getimagesize($temp_file_path);
			 $_FILES['user_photo'] = array(
			     'name' => 'img.jpg',
			     'tmp_name' => $temp_file_path,
			     'size'  => filesize($temp_file_path),
			     'error' => UPLOAD_ERR_OK,
			     'type'  => 'jpg',
			 );

			$this->upload_path="./assets/collections/photo/";
			$this->upload_resize  = array(
				array('name'	=> 'thumb','width'	=> 50, 'quality'	=> '85%'),
				array('name'	=> 'small','width'	=> 200, 'quality'	=> '85%')
			);
			$this->image_size_str = "Size: 200px x 200px";

			$id = $this->jCfg['user']['id'];
			$this->_uploaded(
			array(
				'id'		=> $id ,
				'input'		=> 'user_photo',
				'param'		=> array(
								'field' => 'user_photo',
								'par'	=> array('user_id' => $id)
							)
			));

			$response = array(
			  'state'  => 200,
			  'message' => '',
			  'result' => get_image(base_url()."assets/collections/photo/small/".get_user_photo($id))
			);
			die(json_encode($response));
			exit();
		}
	}

	function check_form(){
		$err = true;
		$msg = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'check_form' ){
			$thisVal       = dbClean(trim($_POST['thisVal']));
			$thisChkId     = dbClean(trim($_POST['thisChkId']));
			$thisChkParent = dbClean(trim($_POST['thisChkParent']));
			$thisChkRel    = dbClean(trim($_POST['thisChkRel']));

			$this->DATA->table="mt_app_user";
			if(trim($thisVal)!=''){
				if(trim($thisChkId)!=''){
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	   => $thisVal,
						'user_id !='   => $thisChkId
					));
				} else {
					$this->data_form = $this->DATA->data_id(array(
						$thisChkRel	=> $thisVal
					));
				}
				if(empty($this->data_form->$thisChkRel)){
					$err = false;
					$msg = '';
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

	function device_apps(){
		$this->_set_title( 'Aplikasi Smartphone' );
		$data['device_config'] = $this->db->get("mt_device_config")->row();
		$this->_v("device_apps",$data);
	}

	function save_apps(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';
		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'upload'){
			if($_POST['device_android_apk'] != ""){
				if($_POST['device_name'] == "android"){
					$dataUpdate = array(
						'device_android_apk' 	 => dbClean($_POST['device_android_apk']),
						'device_android_date' 	 => timestamp()
					);
					if($_POST['device_android_version'] != ""){
						$dataUpdate['device_android_version'] = dbClean($_POST['device_android_version']);
					}
					$this->db->update("mt_device_config",$dataUpdate,array("device_config_id"=>dbClean($_POST['device_config_id'])));
					$data['err']  = false;
					$data['msg'] .= 'Berhasil simpan data..';
				}
			} else {
				$files = $_FILES['files'];
		        if (!empty($files['name'])){
					$upload_path    = './assets/collections/files/';
					$filename 		= $files['name'];
					// $rand			= strtolower(changeEnUrl($filename)).'_'.convDatetoString(timestamp());
					$ext			= substr($filename, strpos($filename,'.'), strlen($filename)-1);
					$imgname		= $filename;
					$imgPath		= $upload_path.$imgname;
		        	if($_POST['device_name'] == "android"){
		        		$old_file = get_name_app('device_android_apk');
		        		unlink($upload_path.$old_file);
		        		if(isset($files) && $files['error'] == 0){
							if(move_uploaded_file($files['tmp_name'], $imgPath)){

								$dataUpdate = array(
									'device_android_apk'  	 => $imgname,
									'device_android_date' 	 => timestamp()
								);
								if($_POST['device_android_version'] != ""){
									$dataUpdate['device_android_version'] = dbClean($_POST['device_android_version']);
								}
								$this->db->update("mt_device_config",$dataUpdate,array("device_config_id"=>dbClean($_POST['device_config_id'])));

								$data['err']  = false;
								$data['msg'] .= 'Upload success..';
							}
						} else {
							$data['err']  = true;
							$data['msg'] .= 'Error: ' . $files['error'] . '<br>';
						}
		        	}

				} else {
					$data['err'] = true;
					$data['msg'] = 'Error: Harap pilih salah satu.';
				}
			}
		}
		redirect($this->own_link."/device_apps?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));
	}

	function bug(){
		if(isset($_POST['btn_simpan'])){
			$pesan = dbClean($_POST['pesan']);
			$url   = isset($_GET['url'])?$_GET['url']:'';
			$by    = $this->jCfg['user']['name'];
			$tgl   = timestamp();
			$msg   = "Telah Terjadi Error Pada ".$tgl." Dilaporkan Oleh : ".$by."\n";
			$msg  .= "Error Pada ".$url." \n Pesan : ".$pesan."\n";

			$this->sendEmail(array(
				'from'		=> 'web@'.$this->domain,
				'to'		=> array(getCfgApp('bug_email')),
				'subject'	=> 'Bolanews Bug',
				'priority'	=> 1,
				'message'	=> $msg
			));

			echo "<script>parent.location.reload(true);</script>";
		}
		$this->_v("report_bug",array(
			"url"	=> isset($_GET['url'])?$_GET['url']:''
		),FALSE);
	}

}

