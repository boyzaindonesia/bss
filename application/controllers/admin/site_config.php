<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class site_config extends AdminController {
	function __construct()
	{
		parent::__construct();

		$this->_set_action();
		$this->_set_action(array("detail"),"ITEM");
		$this->_set_title( 'Pengaturan' );
		$this->DATA->table = "mt_configuration";
		$this->folder_view = "config/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->breadcrumb[] = array(
			"title"		=> "Pengaturan",
			"url"		=> $this->own_link
		);
		$this->upload_path="./assets/images/";
		$this->image_size_str = "300px x 60px";
	}

	function index(){
		$data['configuration'] = $this->db->get("mt_configuration")->row();
		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function save(){
		$data = array(
			'configuration_name'			=> dbClean(ucwords($_POST['configuration_name'])),
			'configuration_about'			=> dbClean($_POST['configuration_about']),
			'configuration_alamat'			=> dbClean($_POST['configuration_alamat']),
			'configuration_email'			=> dbClean($_POST['configuration_email']),
			'configuration_email_cc'		=> dbClean(''),
			'configuration_telp'			=> dbClean($_POST['configuration_telp']),
			'configuration_fax'				=> dbClean($_POST['configuration_fax']),
		);

		if($_POST['configuration_email_cc']){
			$i = 0; $arr = '';
			foreach ($_POST['configuration_email_cc'] as $key){ $arr .= ($i==0?'':',').$key; $i += 1; }
			$data['configuration_email_cc'] = dbClean($arr);
		}

		if (dbClean($_POST['configuration_id']) == "") {
			$data['configuration_date'] = timestamp();
		}
		$a = $this->_save_master(
			$data,
			array(
				'configuration_id' => dbClean($_POST['configuration_id'])
			),
			dbClean($_POST['configuration_id'])
		);
		$id = $a['id'];
		$this->_uploaded(
		array(
			'id'		=> $id ,
			'input'		=> 'configuration_logo',
			'param'		=> array(
							'field' => 'configuration_logo',
							'par'	=> array('configuration_id' => $id)
						)
		));

		redirect($this->own_link."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function save_sosmed(){
		$data = array(
			'configuration_website'			=> dbClean($_POST['configuration_website']),
			'configuration_fb_name'			=> dbClean($_POST['configuration_fb_name']),
			'configuration_fb_link'			=> dbClean($_POST['configuration_fb_link']),
			'configuration_tw_name'			=> dbClean($_POST['configuration_tw_name']),
			'configuration_tw_link'			=> dbClean($_POST['configuration_tw_link']),
			'configuration_ig_name'			=> dbClean($_POST['configuration_ig_name']),
			'configuration_ig_link'			=> dbClean($_POST['configuration_ig_link']),
			'configuration_yt_name'			=> dbClean($_POST['configuration_yt_name']),
			'configuration_yt_link'			=> dbClean($_POST['configuration_yt_link']),
		);

		if (dbClean($_POST['configuration_id']) == "") {
			$data['configuration_date'] = timestamp();
		}
		$a = $this->_save_master(
			$data,
			array(
				'configuration_id' => dbClean($_POST['configuration_id'])
			),
			dbClean($_POST['configuration_id'])
		);

		redirect($this->own_link."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function save_meta(){
		$data = array(
			'configuration_meta_title'		=> dbClean($_POST['configuration_meta_title']),
			'configuration_meta_keyword'	=> dbClean($_POST['configuration_meta_keyword']),
			'configuration_meta_desc'		=> dbClean($_POST['configuration_meta_desc']),
		);

		if (dbClean($_POST['configuration_id']) == "") {
			$data['configuration_date'] = timestamp();
		}
		$a = $this->_save_master(
			$data,
			array(
				'configuration_id' => dbClean($_POST['configuration_id'])
			),
			dbClean($_POST['configuration_id'])
		);

		redirect($this->own_link."?msg=".urlencode('Save data success')."&type_msg=success");
	}

	function reset_app_acl(){ //http://localhost/butiksasha/admin/site_config/reset_app_acl
		// Untuk mereset / merapihkan / mengurutkan tabel:
		// - mt_app_acl_accesses
		// - mt_app_acl_access_actions
		// - mt_app_acl_group_accesses
		// import table mt_app_acl_accesses_bk, mt_app_acl_access_actions_bk, mt_app_acl_group_accesses_bk (application/controller/admin/_bk_app_acl/)

		debugCode('sudah jadi');
		$i = 0;
		$m = $this->db->order_by('acc_by_order','asc')->get_where("mt_app_acl_accesses",array(
			'acc_id !=' 		=> 0
		))->result();
		foreach ($m as $r) {
			$data = array(
				'acc_group'				=> $r->acc_group,
				'acc_menu'				=> $r->acc_menu,
				'acc_group_controller'	=> $r->acc_group_controller,
				'acc_controller_name'	=> $r->acc_controller_name,
				'acc_access_name'		=> $r->acc_access_name,
				'acc_description'		=> $r->acc_description,
				'acc_by_order'			=> $i,
				'app_id'				=> $r->app_id,
				'acc_css_class'			=> $r->acc_css_class,
				'acc_isshow'			=> $r->acc_isshow
			);

			$this->DATA->table = "mt_app_acl_accesses_bk";
			$a = $this->_save_master(
				$data,
				array(
					'acc_id' => ''
				),
				''
			);
			$new_acc_id = $a['id'];
			if($new_acc_id != ''){
				$m2 = $this->db->order_by('aca_id','asc')->get_where("mt_app_acl_access_actions",array(
					'aca_access_id' 		=> $r->acc_id
				))->result();
				foreach ($m2 as $r2) {
					$data2 = array(
						'aca_access_id'			=> $new_acc_id,
						'aca_action_id'			=> $r2->aca_action_id,
						'app_id'				=> $r2->app_id
					);
					$this->DATA->table = "mt_app_acl_access_actions_bk";
					$a2 = $this->_save_master(
						$data2,
						array(
							'aca_id' => ''
						),
						''
					);
					$new_aca_id = $a2['id'];
				}

				$m3 = $this->db->order_by('aga_id','asc')->get_where("mt_app_acl_group_accesses",array(
					'aga_access_id' 		=> $r->acc_id
				))->result();
				foreach ($m3 as $r3) {
					$data3 = array(
						'aga_access_id'			=> $new_acc_id,
						'aga_group_id'			=> $r3->aga_group_id,
						'app_id'				=> $r3->app_id,
						'aga_action_id'			=> $r3->aga_action_id
					);
					$this->DATA->table = "mt_app_acl_group_accesses_bk";
					$a3 = $this->_save_master(
						$data3,
						array(
							'aga_id' => ''
						),
						''
					);
					$new_aga_id = $a3['id'];
				}
			}

			$i += 1;
		}

		// Setelah itu hapus tabel:
		// - mt_app_acl_accesses
		// - mt_app_acl_access_actions
		// - mt_app_acl_group_accesses

		// Kemudian rename table:
		// - mt_app_acl_accesses_bk -> mt_app_acl_accesses
		// - mt_app_acl_access_actions_bk -> mt_app_acl_access_actions
		// - mt_app_acl_group_accesses_bk -> mt_app_acl_group_accesses
		// DONE
	}
}