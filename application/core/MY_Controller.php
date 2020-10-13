<?php
include("globalModel.php");
class MY_Controller extends CI_Controller{

	var $DATA;
	var $upload_path	= './assets/files/';
	var $upload_allowed_types	= 'gif|jpg|png|avi|mp4|mpeg|mpg|movie|mov|qt|flv|pdf|docx|doc|ppt|txt|rtf|xls';
	var $upload_types	= 'image';	// image or file.
	var $upload_resize  = array(
			array('name'	=> 'thumb','width'	=> 200, 'quality'	=> '85%'),
			array('name'	=> 'small','width'	=> 350, 'quality'	=> '85%'),
			array('name'	=> 'large','width'	=> 500, 'quality'	=> '85%')
		);
	var $message = "";
	var $folder_view = "";
	var $prefix_view = "";
	var $per_page 		= 20;
	var $uri_segment 	= 4;
	var $domain = "";
	var $data_table;
	var $user_online  = array();
	var $is_watermark = TRUE;

	function __construct(){

		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');

		/*$set=ini_set('mssql.textlimit','65536');
   		ini_set('mssql.textlimit',$set);

   		$set2=ini_set('mssql.textlimit','65536');
   		ini_set('mssql.textsize',$set2);
		*/


		$this->_initConfig();
		$this->output->enable_profiler(false);
		$this->DATA = new globalModel();

		$this->domain = $_SERVER['SERVER_NAME'];
		//$this->write_log();

		$this->user_online  = $this->get_list_user();

		$this->is_watermark = TRUE;
		//debugCode($this->jCfg);
	}

	function _initConfig(){
		$s = $this->session->userdata("jcfg");
		if(is_array($s))
			$this->jCfg = $s;
		else
			$this->_initSession();
	}

	function isLogin(){
		if($this->jCfg['is_login_bp'] == 1){
			redirect('');
		}else{
			redirect('auth');
		}
	}

	function current_session($i=0){
		for($x=0;$x>=$i;$x++){
			$this->_initSession();
		}
	}

	function _initSession(){
		$this->jCfg = array(
			'mt_app_id'		=> '1',
			'mt_app_name'	=> '',
			'is_login_bp'	=> 0,
			'view'			=> array(
					"data"	=> "all",
					"t"		=> "all"
				),
			'user' => array(
				'id' 		=> '',
				'name'		=> 'guest',
				'fullname'	=> 'Guest',
				'level'		=> '',
				'is_all'	=> 0,
				'color'		=> 'mine',
				'bg'		=> 'ptrn_e',
				'ujian_type'=> '',
				'email'		=> ''
			),
			'menu'			=> array(),
			'current_class'		=> '',
			'current_funtion' 	=> '',
			'mod_rewrite'	=> 1,
			'theme'			=> '',
			'search'		=> array(
									'class'		=> '',
									'date_start'=> '',
									'date_end'	=> '',
									'status'	=> '',
									'per_page'	=> 20,
									'order_by'  => '',
									'colum'		=> '',
									'keyword'	=> '',
									'order_dir' => 'ASC'
								),
			'referer'		=> '',
			'chat_online'	=> array(),
			'access'		=> array(),
			'lang'			=> 'ind',
			'captcha'		=> array()
		);
		$this->_releaseSession();
	}

	function setReferer($url=''){
		$this->jCfg['referer'] = $url;
		$this->_releaseSession();
	}

	function setMenu($menu=array()){
		$this->jCfg['menu'] = $menu;
		$this->_releaseSession();
	}

	function setMessage($message=''){
		$this->jCfg['message'] = $message;
		$this->_releaseSession();
	}


	function setLang($lang='eng'){
		$this->jCfg['lang'] = $lang;
		$this->_releaseSession();
	}
	function setAccess($acc=array()){
		$this->jCfg['access'] = $acc;
		$this->_releaseSession();
	}

	function getReferer(){
		return $this->jCfg['referer'];
	}

	function _releaseSession(){
		$this->session->set_userdata(array("jcfg"=>$this->jCfg));
	}

	/*
	* stuff function
	*/
	function _getClass(){ // return name of current class
		return $this->router->fetch_class();
	}

	function _getMethod(){ // return name of current methode
		return $this->router->fetch_method();
	}


	function sendEmail($p=array()){
		$this->load->library('email');

		/*mail method */
/*		$config['protocol'] = 'sendmail';
		$config['mailtype'] = isset($p['type'])?$p['type']:'html';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['priority'] = isset($p['priority'])?$p['priority']:3;
*/
		/*smtp method */



		$config['protocol']  = 'smtp';
		$config['smtp_host'] = 'mail.gerbangusaha.com';
		$config['smtp_port'] = 25;
		$config['smtp_user'] = 'order@gerbangusaha.com';
		$config['smtp_pass'] = 'g3rb4ngus4h4';
		$config['priority']  = 1;
		$config['mailtype']  = 'html';

		//$config['charset']   = 'utf-8';
		//$config['wordwrap']  = TRUE;

		/* send mail method */
		/*$config['protocol'] = 'sendmail';
		$config['mailtype'] = 'html';
		$config['charset']  = 'utf-8';
		$config['wordwrap'] = TRUE;
		*/
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
		$this->email->from($p['from'], isset($p['title'])?$p['title']:' Islam Indonesia');
		$this->email->to($p['to']);
		if( isset($p['cc']) && trim($p['cc']) != "" ){
			$this->email->cc($p['cc']);
		}
		$this->email->subject('Islam Indonesia : '.$p['subject']);
		$this->email->message($p['message']);

		if(isset($p['alt_message']) && trim($p['alt_message'])!='' ){
			$this->email->set_alt_message($p['alt_message']);
		}

		$this->email->send();
		//debugCode($this->email->print_debugger());

	}

	function get_list_user(){
		$ses = $this->db->get("mt_app_sessions")->result();
		$list_user = array();
		if( count($ses) > 0 ){
			foreach($ses as $r){
				$m = $r->user_data;
				$t = explode("username_chat_online",$m);
				$ntmp = isset($t[1])?explode("}",$t[1]):array();
				if( isset($ntmp[0]) ){
					$mx = str_replace(";","",$ntmp[0]);
					$expl = explode('"',trim($mx));
					if(isset( $expl[2] )){
						$list_user[$expl[2]] = 1;
					}
				}
			}
		}

		return($list_user);
	}

	function _uploaded($par=array()){
		$this->load->library('image_lib');

		$uri = $this->upload_path;
		$folder_upload = (isset($par['folder']))?$par['folder']:'';
		if($_FILES[$par['input']]['error']==4)
			return false;
		// $uId = uniqid();
		$uId = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, +5);

		$iname = $_FILES[$par['input']]['name'];
		//$img_name = explode('.',$iname);
		//debugCode($img_name[0]);

		$check_dot = explode('.',$iname); //removing dot character
		$count = count($check_dot);
		if ($count > 2){
			$r = array_pop($check_dot);
			$iname = implode('-',$check_dot).'.'.$r;
		}
		$check_space = strpos($iname, ' ');	//removing space character
		$file_Name = ($check_space === false) ? $iname : str_replace(' ','-',$iname) ;
		$config['upload_path'] = $uri.$folder_upload."/";
		$config['file_name'] = $file_Name;
		$config['allowed_types'] = $this->upload_allowed_types;
		$config['max_size']		= 1024*20;
		if(trim($this->upload_types)=='image'){
			$config['max_width']  	= 1024*5;
			$config['max_height'] 	= 768*5;
		}

		$this->load->library('upload');
		$this->upload->initialize($config);


		// if( $this->upload->do_upload($par['input']) ) //Original
		if( $this->upload->do_upload($par['input'], true) )
		{
			$media = $this->upload->data($par['input']);
			$fileName = url_title($media["raw_name"],'-',TRUE).'-'.$uId.$media["file_ext"];

			//echo $fileName;//die();
			if(file_exists($config['upload_path'].$file_Name)){
				rename($config['upload_path'].$file_Name,$config['upload_path'].$fileName);
			}
			//debugCode($config['upload_path'].$file_Name.' '.$config['upload_path'].$fileName);
			//$img = $uId.$this->upload->file_ext;

			$this->_delte_old_files($par['param']);
			$this->DATA->_update($par['param']['par'],array($par['param']['field']=>$fileName));

			if(trim($this->upload_types)=='image'){

				$fileNameResize = $config['upload_path'].$fileName;
				//debugCode($fileNameResize);
				$img = getimagesize($fileNameResize);
				$realWidth	= $img[0];
				$realHeight = $img[1];

				/* Watermarking */
				/*if($this->is_watermark == TRUE){
					$config = null;
					$config['source_image']	= $fileNameResize;
					$config['image_library'] = 'gd2';
					$config['wm_type'] = 'overlay';
					$config['wm_overlay_path'] = './assets/collections/wm_source.png';
					$config['wm_vrt_alignment'] = 'bottom';
					$config['wm_hor_alignment'] = 'right';
					$config['wm_opacity'] = '80';
					$this->image_lib->initialize($config);
					$this->image_lib->watermark();
					$this->image_lib->clear();
					unset($config);
				}
*/

				$resize = array();
				foreach($this->upload_resize as $r){
					$resize[] = array(
						"width"			=> $r['width'],
						"height"		=> $r['height'],
						"quality"		=> $r['quality'],
						"source_image"	=> $fileNameResize,
						"new_image"		=> $uri.$r['name']."/".$fileName
					);
				}

				foreach($resize as $k=>$v){
					$oriW = $v['width'];
					$oriH = $v['height'];
					$x = $v['width']/$realWidth;
					$y = $v['height']/$realHeight;
					if($x < $y) {
						$v['width'] = round($realWidth*($v['height']/$realHeight));
					} else {
						$v['height'] = round($realHeight*($v['width']/$realWidth));
					}
				// debugCode($v);
					$this->image_lib->initialize($v);
					if(!$this->image_lib->resize()){
						debugCode($this->image_lib->display_errors());
						die("Error Resize....");
					}
					$this->image_lib->clear();

					/*if($k==0){
						$config = null;
						$config['image_library'] = 'GD2';
						$im = getimagesize($v['new_image']);
						$toCropLeft = ($im[0] - ($oriW *1))/2;
						$toCropTop = ($im[1] - ($oriH*1))/2;

						$config['source_image'] = $v['new_image'];
						$config['width'] = $oriW;
						$config['height'] = $oriH;
						$config['x_axis'] = $toCropLeft;
						$config['y_axis'] = $toCropTop;
						$config['maintain_ratio'] = false;

						$this->image_lib->initialize($config);

						if(!$this->image_lib->crop()){
							die("Error Crop..");
						}
						$this->image_lib->clear();
					}*/

					// $data['x'] = $this->input->post('x');
			  //       $data['y'] = $this->input->post('y');
			  //       $data['w'] = $this->input->post('w');
			  //       $data['h'] = $this->input->post('h');

			  //       $config['image_library'] = 'gd2';
			  //       //$path =  'uploads/apache.jpg';
			  //       $config['source_image'] = 'uploads/'.$data['user_data']['img_link'];
			  //       // $config['create_thumb'] = TRUE;
			  //       //$config['new_image'] = './uploads/new_image.jpg';
			  //       $config['maintain_ratio'] = FALSE;
			  //       $config['width']  = $data['w'];
			  //       $config['height'] = $data['h'];
			  //       $config['x_axis'] = $data['x'];
			  //       $config['y_axis'] = $data['y'];

			  //       $this->load->library('image_lib', $config);

			  //       if(!$this->image_lib->crop())
			  //       {
			  //           echo $this->image_lib->display_errors();
			  //       }

				}
				//delete original image
				if(file_exists($config['upload_path'].$fileName)){
					unlink($config['upload_path'].$fileName);
				}
			} // end if this type image

		}
		else {
			debugCode($this->upload->display_errors());
		}
	}

	function _delte_old_files($par=array()){
		$uri = $this->upload_path;
		$files = $this->DATA->data_id($par['par']);
		$folder = isset($par['folder'])?$par['folder'].'/':'original/';
		if( !empty( $files->{$par['field']} ) ){
			$ori_file = $uri.$folder.$files->{$par['field']};
			if(file_exists($ori_file)){
				unlink($ori_file);
			}
			if(trim($this->upload_types)=='image' && count($this->upload_resize) > 0){
				$data = array();
				foreach($this->upload_resize as $m){
					$data[] = $uri.$m['name']."/".$files->{$par['field']};
				}
				foreach($data as $v){
					if(file_exists($v)){
						unlink($v);
					}
				}
			}
		}
	}

	function _delte_old_files_without_thumb($par=array()){
		$uri = $this->upload_path;
		$files = $this->DATA->data_id($par['par']);
		$folder = isset($par['folder'])?$par['folder'].'/':'original/';
		if( !empty( $files->{$par['field']} ) ){
			$ori_file = $uri.$folder.$files->{$par['field']};
			if(file_exists($ori_file)){
				unlink($ori_file);
			}
			if(trim($this->upload_types)=='image' && count($this->upload_resize) > 0){
				$data = array();
				foreach($this->upload_resize as $m){
					if($m['name']!='thumb'){
						$data[] = $uri.$m['name']."/".$files->{$par['field']};
					}
				}
				foreach($data as $v){
					if(file_exists($v)){
						unlink($v);
					}
				}
			}
		}
	}

	function _data_web($m=array()){

		$this->load->library('pagination');
		$config['per_page'] = $this->per_page;
		$data = $this->data_table;
		$config['base_url'] = $m['base_url'];
		$config['total_rows'] = $data['total'];
		$config['uri_segment'] = $this->uri_segment;
		$config['suffix']	= $this->config->item('url_suffix');
		$config['full_tag_open'] = '<div class="pagination">';
		$config['full_tag_close'] = '</div>';
		$config['next_link'] = '&rarr;';
		$config['prev_link'] = '&larr;';
		$config['show_page_numbers'] = TRUE;

		$noPage = $this->uri->segment($this->uri_segment)==''||$this->uri->segment($this->uri_segment)==1?1:$this->uri->segment($this->uri_segment)+1;
		$endPage = ($noPage-1)+$this->per_page;
		($endPage>$data['total']?$endPage=$data['total']:'');

		$config['noPage']  = $noPage;
		$config['endPage'] = $endPage;

		$this->pagination->initialize($config);
		return array(
			'data'			=>	$data['data'],
			'cRec'			=>  $data['total'],
			'noPage'		=>  $noPage,
			'endPage'		=>  $endPage,
			'cPage'			=>  ceil($data['total']/$this->per_page),
			'paging'		=> 	$this->pagination->create_links()
		);
	}

	function _data_front($m=array()){

		$this->load->library('pagination');
		$config['per_page'] = $this->per_page;
		$data = $this->data_table;
		//debugCode($data['total']);
		if(isset($data['total']->found_rows) > 0){
			$data['total'] = isset($data['total']->found_rows)?$data['total']->found_rows:0;
		}else{
			$data['total'] = isset($data['total'])?$data['total']:0;
		}
		$config['base_url'] = $m['base_url'];
		$config['total_rows'] = $data['total'];
		$config['uri_segment'] = $this->uri_segment;
		$config['suffix']	= $this->config->item('url_suffix');
		$config['full_tag_open'] = '<ul class="pagination-numbers">';
		$config['full_tag_close'] = '</ul>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['next_link'] = '<i class="fa fa-angle-right"></i>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['prev_link'] = '<i class="fa fa-angle-left"></i>';
		$config['cur_tag_open'] = '<li><a href="javascript:void(0);" class="page-number current" style="cursor:default;">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['tag_open_disabled']  = '<li class="disabled">';
		$config['tag_close_disabled'] = '</li>';
		$config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link_disabled'] = FALSE;
		$config['next_link_disabled'] = FALSE;
		$config['show_page_numbers'] = FALSE;
		$config['use_page_numbers'] = TRUE;
		$config['page_query_string'] = TRUE;
		$config['is_ampersand'] = '';

		// $noPage = $this->uri->segment($this->uri_segment)==''||$this->uri->segment($this->uri_segment)==1?1:$this->uri->segment($this->uri_segment)+1;
		// $endPage = ($noPage-1)+$this->per_page;

		$noPage  = (($m['pageNum'] - 1) * $this->per_page) + 1;
		$endPage = $m['pageNum'] * $this->per_page;

		($endPage>$data['total']?$endPage=$data['total']:'');
		$config['noPage']  = $noPage;
		$config['endPage'] = $endPage;

		$this->pagination->initialize($config);
		return array(
			'data'			=>	$data['data'],
			'cRec'			=>  $data['total'],
			'noPage'		=>  $noPage,
			'endPage'		=>  $endPage,
			'cPage'			=>  ceil($data['total']/$this->per_page),
			'paging'		=> 	$this->pagination->create_links()
		);
	}

	function _data($m=array()){

		$this->load->library('pagination');
		$config['per_page'] = $this->per_page;
		$data = $this->data_table;
		$config['base_url'] = $m['base_url'];
		$config['total_rows'] = $data['total'];
		$config['uri_segment'] = $this->uri_segment;
		$config['suffix']	= $this->config->item('url_suffix');
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['next_link'] = '›';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['prev_link'] = '‹';
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['tag_open_disabled']  = '<li class="disabled">';
		$config['tag_close_disabled'] = '</li>';
		$config['last_link'] = '»';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = '«';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link_disabled'] = TRUE;
		$config['next_link_disabled'] = TRUE;
		$config['show_page_numbers'] = TRUE;

		$noPage = $this->uri->segment($this->uri_segment)==''||$this->uri->segment($this->uri_segment)==1?1:$this->uri->segment($this->uri_segment)+1;
		$endPage = ($noPage-1)+$this->per_page;
		($endPage>$data['total']?$endPage=$data['total']:'');

		$config['noPage']  = $noPage;
		$config['endPage'] = $endPage;

		$this->pagination->initialize($config);
		return array(
			'data'			=>	$data['data'],
			'cRec'			=>  $data['total'],
			'noPage'		=>  $noPage,
			'endPage'		=>  $endPage,
			'cPage'			=>  ceil($data['total']/$this->per_page),
			'paging'		=> 	$this->pagination->create_links()
		);
	}

	function write_log(){

		$class 	= $this->_getClass();
		$method	= $this->_getMethod();
		$name	= $this->jCfg['user']['name'];
		$id 	= $this->jCfg['user']['id'];
		$ip		= $_SERVER['REMOTE_ADDR'];
		$browser= $_SERVER['HTTP_USER_AGENT'];
		$url 	= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$flag_ins = TRUE;
		if( (!isset($this->jCfg['current_class'])) && (!isset($this->jCfg['current_funtion'])) ){
			$this->jCfg['current_class'] = $class;
			$this->jCfg['current_funtion'] = $method;
			$this->_releaseSession();
		}else{

			if($this->jCfg['current_class']==$class && $this->jCfg['current_funtion']==$method){
				$flag_ins = FALSE;
			}

			$this->jCfg['current_class'] = $class;
			$this->jCfg['current_funtion'] = $method;
			$this->_releaseSession();
		}

		if(!empty($id) && $flag_ins==TRUE && $this->jCfg['current_class']!="chat" ){

			$POST=isset($_POST)?json_encode($_POST):"";
			$GET=isset($_GET)?json_encode($_GET):"";
			$arr_method = array(
				"detail_member","detail_advertiser","cek_advertiser_username",
				"search","get_ads","detail_iklan","get_tag","report","get_tag",
				"get_tag_brand","get_reach","detail","get_ads_info","view_image",
				"get_city","test","index","im_lost","access"
			);
			if(!in_array($method,$arr_method)){
				$this->db->insert("mt_app_log",array(
					"log_date"		=> date("Y-m-d H:i:s"),
					"log_class"		=> $class,
					"log_function" 	=> $method,
					"log_url"		=> $url,
					"log_user_type"	=> 1,
					"log_user_id"	=> $id,
					"log_ip"		=> $ip,
					"log_role"		=> $this->jCfg['user']['level'],
					"log_user_agent"	=> $browser,
					"log_user_name"	=> $name,
					"log_var_get"	=> $GET,
					"log_var_post"	=> $POST,
					"log_type"		=> 0
				));
			}
		}
	}

}