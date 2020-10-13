<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
class store extends AdminController {  
	function __construct()    
	{
		parent::__construct();
			    
		$this->_set_action();
		$this->_set_action(array("detail"),"ITEM");
		$this->_set_title( 'Pengaturan Toko' );
		$this->DATA->table = "mt_store";
		$this->folder_view = "config/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->breadcrumb[] = array(
			"title"		=> "Pengaturan Toko",
			"url"		=> $this->own_link
		);

		$this->upload_path="./assets/collections/logo/thumb/dark/";
		$this->image_size_str = "<strong>Tulisan: Hitam<br/>Background: Putih<br/>Ukuran: 200px x 42px</strong>";
	}
	
	function index(){
		$store_id = get_user_store(isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'');
		$data['store'] = $this->db->get_where("mt_store",array(
			"store_id"	=> $store_id
		),1,0)->row();

		$this->_v($this->folder_view.$this->prefix_view,$data);
	}

	function save(){
		$store_id = get_user_store(isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'');
		if($store_id == dbClean($_POST['store_id'])){
			$data = array(
				'store_name'				=> dbClean($_POST['store_name']),
				'store_phone'				=> dbClean($_POST['store_phone']),
				'store_product'				=> dbClean($_POST['store_product']),
				'store_noted_thanks'		=> dbClean($_POST['store_noted_thanks']),
				'store_istrash'				=> 0		
			);

			if (dbClean($_POST['store_id']) == "") {
				$data['store_date'] = timestamp();
			}
			$a = $this->_save_master( 
				$data,
				array(
					'store_id' => dbClean($_POST['store_id'])
				),
				dbClean($_POST['store_id'])
			);
			$id = $a['id'];
			$this->_uploaded(
			array(
				'id'		=> $id ,
				'input'		=> 'store_logo',
				'param'		=> array(
								'field' => 'store_logo', 
								'par'	=> array('store_id' => $id)
							)
			));
			
			redirect($this->own_link."?msg=".urlencode('Save data success')."&type_msg=success");
		} else {
			redirect($this->own_link."?msg=".urlencode('Anda tidak berhak akses halaman ini')."&type_msg=error");
		}
	}

	function save_sosmed(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'save'){
			$data2 = array(
				'store_sosmed_name'				=> dbClean($_POST['store_sosmed_name']),
				'store_sosmed_date' 			=> timestamp()
			);

			if (dbClean($_POST['store_sosmed_id']) == "") {
				$store_id = get_user_store(isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'');
				$data2['store_id'] = $store_id;
				$data2['store_cat_sosmed_id']   = dbClean($_POST['store_cat_sosmed_id']);
				$data2['store_sosmed_status'] = dbClean($_POST['store_sosmed_status']);

				$position = $this->db->select_max('position')->get_where("mt_store_sosmed",array(
					"store_id"	=> $store_id
				))->row();
				$data2['position'] = $position->position + 1;

			}

			$this->DATA->table="mt_store_sosmed";
			$a = $this->_save_master( 
				$data2,
				array(
					'store_sosmed_id' => dbClean($_POST['store_sosmed_id'])
					),
				dbClean($_POST['store_sosmed_id'])			
				);

			$data['err'] = false;
			$data['msg'] = 'Sukses menyimpan data.';

		}

		die(json_encode($data));
		exit();
	}

	function views(){
		$data = array();
		$data['err'] 	= true;
		$data['msg'] 	= '';
		$data['result'] = '';
		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'view' ){
			$thisVal  = dbClean(trim($_POST['thisVal']));

			if(trim($thisVal)==''){ $thisVal = 0; }
			$r = $this->db->get_where("mt_store_sosmed",array(
				'store_sosmed_id ' => $thisVal
			),1,0)->row();

			$arr_cat = array();
			$store_id = get_user_store(isset($this->jCfg['user']['id'])?$this->jCfg['user']['id']:'');
			$get_store_sosmed = get_store_sosmed($store_id);
			foreach ($get_store_sosmed as $k => $v) {
				$arr_cat[] = $v->store_cat_sosmed_id;
			}

            $i = 0;
            $get_store_cat_sosmed = get_store_cat_sosmed();
            foreach ($get_store_cat_sosmed as $k => $v) {
                $selected = (($v->store_cat_sosmed_id==$r->store_cat_sosmed_id)?'selected':'');
                $disabled = (in_array($v->store_cat_sosmed_id, $arr_cat)?'disabled':'');
                $store_cat_sosmed_id .= '<option value="'.$v->store_cat_sosmed_id.'" '.$selected.' '.$disabled.'>'.$v->store_cat_sosmed_name.'</option>';
                $i += 1;
            }

			$data['content'] = '
			<form class="form_save_sosmed" action="'.base_url().'admin/store/save_sosmed" method="post" enctype="multipart/form-data">
                <div class="form-horizontal">
                    <legend>Form Social Media</legend>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tipe <span class="req">*</span></label>
                        <div class="col-md-9">
                            <select name="store_cat_sosmed_id" class="form-control" required '.($thisVal!=0?'disabled':'').'>
		                        <option value="" selected>--- SELECT ---</option>
                                '.$store_cat_sosmed_id.'
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama <span class="req">*</span></label>
                        <div class="col-md-9">
                            <input type="text" name="store_sosmed_name" value="'.($r->store_sosmed_name!=''?$r->store_sosmed_name:'').'" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group form-action mb-0">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <input type="hidden" name="store_sosmed_id" value="'.$r->store_sosmed_id.'" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                        </div>
                    </div>
                </div>
            </form>
			';

		}

		die(json_encode($data));
		exit();
	}
	function deletes(){
		$data = array();
		$data['err'] = true;
		$data['msg'] = '';

		if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'delete'){
			$id = '';
			$exp = '';
			$data['data'] = array();
			if(isset($_POST['thisId']) && $_POST['thisId'] !=''){
				$this->DATA->table="mt_store_sosmed";
				$id = $_POST['thisId'];
				$exp = explode("-", $id);
				foreach ($exp as $key) {
					$this->DATA->_delete(array("store_sosmed_id"	=> idClean($key)),true);	
				}
			}

			$data['err'] = false;
			$data['msg'] = 'Sukses menghapus data.';
		}

		die(json_encode($data));
		exit();
	}
	function change_position(){
		if ($_POST) {
			$temp_position = $_SERVER['REMOTE_ADDR'];
			$ids    = $_POST["ids"];
			for ($idx = 0; $idx < count($ids); $idx+=1) {
				$id = $ids[$idx];
				//...
				$data = array(
					'position'		=> dbClean($idx),		
					'temp_position'	=> dbClean($temp_position),		
				);		

				$this->DATA->table = "mt_store_sosmed";
				$a = $this->_save_master( 
					$data,
					array(
						'store_sosmed_id' => dbClean((int)$id)
					),
					dbClean((int)$id)			
				);
			}
			return;
		}
	}

	function change_status($id='',$val=''){
		$msg = '';
		$id  = dbClean(trim($id));
		$val = dbClean(trim($val));
		if(trim($id) != ''){
			if($val == 'true'){ $val = '1'; } else { $val = '0'; }
			$this->db->update("mt_store_sosmed",array("store_sosmed_status"=>$val),array("store_sosmed_id"=>$id));
			$msg = 'success';
		}

		$return = array('msg' => $msg);
		die(json_encode($return));
		exit();
	}
	
}