<?php
include_once(APPPATH."libraries/FrontController.php");
class Site extends FrontController {
	var $cur_menu = '';

	function __construct()  
	{
		parent::__construct(); 
		$this->load->model("mdl_article","M");
		$this->load->model("mdl_member","MM");
		
		$this->load->library('Mobile_Detect');
	    // $detect = new Mobile_Detect();
	    // if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
	    //     header("Location: /mobile"); exit;
	    // } else {
	    //     header("Location: /desktop"); exit;
	    // }

		checkIsBlacklistOnLoad();
		
	}
	
	function index(){
	
		$this->cur_menu = 'home';
		/*
		$data['menu_id'] = $_GET['id'];
		$data['contact'] = $this->db->get("mt_contact")->row();
		$this->_v('contact',$data);	
		*/
		$data = '';
		$data['menu'] = "home";
		$data['menu_id'] = "0";
		$this->_v('home',$data); 
	}
	
	
	function about(){
		$data = '';
		$this->cur_menu = 'about';
		$data['menu'] = "about";
		$data['menu_id'] = "0";
		$this->_v('about',$data);
	}
	

	function membership(){
		$data = '';
		$data['menu'] = "Membership";
		$data['menu_id'] = "0";
		$this->_v('membership',$data);
	}
	
	function sicepat_to(){
		$data = '';
		
		$requestUrl = 'http://api.sicepat.com/customer/destination?api-key=391274a334f7527a6c1f340532aeaddd';
		$response  = file_get_contents($requestUrl);
		$jsonobj  = json_decode($response);
		$resultArray = object2array($jsonobj);
		//debugCode($resultArray[sicepat]);
		foreach($resultArray[sicepat][results] as $r ){
			$Data = array(
				"destination_code" 		=> $r[destination_code],
				"subdistrict" 			=> $r[subdistrict],
				"city" 					=> $r[city],
				"province" 				=> $r[province]
			);
			//debugCode($Data);
				$this->db->insert('mt_sicepat', $Data); 
				$trans_id 	= $this->db->insert_id();
		}
		
	}
	
	
	function lupa_password(){
		$user['data'] 	= '';
		$e	 	= isset($_POST['email'])?$_POST['email']:'';
		$f	 	= isset($_POST['no_reg_iapi'])?$_POST['no_reg_iapi']:'';
		
		//debugCode($_POST);
		if(!empty($e)){
			$user 		= $this->MM->member(array('email' => $e, 'no_reg_iapi' => $f ));
			
			$id			= isset($user['data'][0]->id)?$user['data'][0]->id:"";
			$name		= isset($user['data'][0]->nama)?$user['data'][0]->nama:"";
			$email		= isset($user['data'][0]->email)?$user['data'][0]->email:"";
			$username	= isset($user['data'][0]->no_reg_iapi)?$user['data'][0]->no_reg_iapi:"";
			
		}
		
		//debugCode($user);
		if(!empty($user['data'])){
			$password = substr(base_convert(sha1(uniqid(mt_rand())), 36, 15), 0, 8);
			$data = array(
               'password' => md5($password),
			   
            );
			
			$this->db->where('id', $id);
			$this->db->update('mt_member', $data); 
			
			$pesan_email  = 'Kepada Yth. <strong>'.$name.'</strong><br/><br/>';
			$pesan_email .= 'Password account anda telah kami create. Sekarang Anda dapat mengakses akun anda di website kami dengan menggunakan rincian di bawah ini :<br/>';
			$pesan_email .= 'Email Address : '.$email.'<br/>';
			$pesan_email .= 'Username : '.$username.'<br/>';
			$pesan_email .= 'Password : '.$password.'<br/>';
			$pesan_email .= 'login, visit <a href="'.base_url().'login">'.base_url().'login</a><br/><br/>';
			$pesan_email .= 'Regards';
			//debugCode($username);	
			
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'mail.iapi.or.id';
			$config['smtp_port'] = 25;
			$config['smtp_user'] = 'no-reply@iapi.or.id';
			$config['smtp_pass'] = 'u1r1Ro*9';
			$config['mailtype']  = 'html';

			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			$this->email->from("info@iapi.or.id", "iapi.or.id");
			$this->email->to($email); 
			//$this->email->cc('raditya.pratama3@gmail.com');       
			$this->email->subject("Registrasi Membership di iapi.or.id");        
			$this->email->message($pesan_email);
			
		

			if($this->email->send()){
			}


			$data['msg'] = 'Selamat akun anda sudah aktif, Silahkan cek email anda';
			redirect(base_url().'membership?msgs='.$data['msg'].'&i=1');
		
		}else{
			
			$data['msg'] = '*Data Anda tidak terdaftar, Silahkan hubungi kami!';
			redirect(base_url().'membership?msgs='.$data['msg']);
		
		}
	}
	
	
	
	function confirmation($id="", $member=""){
		/* ID article */
		//debugCode($id);
		$data['page'] = "";
		$data['menu'] = "Confirmation";
		
		$this->cur_menu = 'lapor';


		$this->user_login = isset($this->jCfg['member']['id'])?$this->jCfg['member']['id']:'';
		
		
		$data['invoice'] = $this->db->get_where("mt_transaction_store",array(
			"id "	=> $id
		))->row();


		
		$data['detail'] = $this->db->get_where("mt_transaction_detail",array(
			"detail_transaction_id"	=> $id
		))->result();
		
		
		if(!empty($_POST)){
			//debugCode($_POST);
			$this->DATA->table="mt_confirmation";
				$dataConf = array(
					'conf_transaction_id' 	=> $_POST['transaction_id'],
					'conf_no_rek' 			=> $_POST['conf_no_rek'],
					'conf_bank' 			=> $_POST['conf_bank'],
					'conf_an' 				=> $_POST['conf_an'],
					'conf_date'		=> date('Y-m-d'),
					'conf_member_id'=> $this->user_login,
				);	
		
				$a = $this->_save_master( 
					$dataConf,
					array(
						'id' => dbClean($_POST['id'])
					),
					dbClean($_POST['id'])			
				);
				
		
				$id = $a['id'];
				$this->upload_path="./assets/collections/photo/";
				$this->_uploaded(
				array(
					'id'		=> $id ,
					'input'		=> 'bukti',
					'param'		=> array(
								'field' => 'bukti', 
								'par'	=> array('id' => $id)
							)
				));

						
				
				$dataTransaction = array(
				   'transaction_pay_status' => "PAID",
				   'transaction_status' 	=> "1",
				);
				
				$this->db->where('id', $_POST['transaction_id']);
				$this->db->update('mt_transaction_store', $dataTransaction); 
				
				$trans_id 					= $_POST['transaction_id'];
				$dataTrans['transaction'] 	= $data['invoice'];
				$dataTrans['trans_id'] 		= $trans_id;
				
				$pesan_email 				= $this->load->view('front/fullwidth/email_pembayaran',$dataTrans,TRUE);
				
				//debugCode($pesan_email);
							$config['protocol'] = 'smtp';
							$config['smtp_host'] = 'mail.vanillahijab.com';
							$config['smtp_port'] = 25;
							$config['smtp_user'] = 'no-reply@vanillahijab.com';
							$config['smtp_pass'] = 'Hmw7v4&6';
							$config['mailtype']  = 'html';
					
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");
				$this->email->from("no-reply@vanillahijab.com", "vanilla hijab");
				$this->email->to($data['me']->email);        
				$this->email->subject("Konfirmasi pembayaran di vanillahijab.com");        
				$this->email->message($pesan_email);

				if($this->email->send()){
					redirect(base_url().'messages/success?next='.base_url().'messages/success');
				}		
				
				$data['sukses']	= TRUE;	
			
		}
		
		//debugCode($data['invoice']);
		
		$this->_v('confirmation',$data);
	}
	
	
	
		
	function detail(){
		/* ID article */
		$this->cur_menu = 'lookbook';
		
		$url 	= $this->uri->segment(2);
		$id_url = explode('-',$url);
		$id 	= $id_url[0];
		$idMenu = $id_url[1];
		
		/* data article */
		$data['text'] = $this->db->get_where("mt_article",array(
			"article_id"	=> $id
		))->row();	
		
		
		$data['menu_id'] = $idMenu;
		$data['page'] = "";
		$data['menu'] = "news";
		$this->_v('detail',$data);
	}
	
	

	function lookbook(){
		/* topic type */
		$this->cur_menu = 'lookbook';
		$t = $this->uri->segment(2);
		
		/* ID category */
		$id_url  = explode('-',$t);
		$id 	 = $id_url[0];
		$menuId	 = $id_url[1];
		
		
		/* paging article */
		$this->per_page = 9;
		$this->uri_segment = 3;
		$this->data_table = $this->M->data_article_front(array(
			'category_id' 	=> $id,
			'limit' 		=> $this->per_page, 
			'offset'		=> $this->uri->segment($this->uri_segment)
		));
		$data = $this->_data_front(array(
			'base_url'		=> base_url().'category/'.$t
		));
		
		
		//debugCode($data);
		$data['menu'] = "news";
		$data['page'] = $catName;
		

		$this->_v('lookbook',$data);
		
	}
	
	
	function cart_store(){
		$data="";
		$data['title'] = "Cart Store";
		$data['menu'] = "cart Store";
		
		
		$next = isset($_GET['next'])?$_GET['next']:base_url();
		
		if( trim($this->user_login)=="" ){
			$n = '?next='.$next;
		}
	
		$this->user_login = isset($this->jCfg['member']['id'])?$this->jCfg['member']['id']:'';
		
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}
		
		if(isset($_POST['Beli'])){
			//debugCode($_POST);\
					if(isset($this->jCfg['cart']['store']))
					{
						$jumlah = $this->jCfg['cart']['store'];
						$batas = 0;
						$max = 3;
							foreach($jumlah as $r){
								$batas = $batas + $r['qty'];
							}
							$batas_akhir = $batas + $_POST['quantity'];
							$id					= isset($_POST['id'])?$_POST['id']:"";
							$product_name		= isset($_POST['product_name'])?$_POST['product_name']:"";
							$price				= isset($_POST['price'])?$_POST['price']:"";
							$qty				= $batas_akhir<=3?$_POST['quantity']:$max-$batas;
							$berat				= isset($_POST['berat'])?$_POST['berat']:"";

							if($batas_akhir<=3){
								$this->jCfg['cart']['store'][$id]= array(
									"id"			=> $id,
									"product_name"	=> $product_name,
									"qty"			=> $qty,
									"price"			=> $price,
									"berat"			=> $berat
								);
							
								$this->_releaseSession();
							}
					}else{
						$id					= isset($_POST['id'])?$_POST['id']:"";
						$product_name		= isset($_POST['product_name'])?$_POST['product_name']:"";
						$price				= isset($_POST['price'])?$_POST['price']:"";
						$qty				= isset($_POST['quantity'])?$_POST['quantity']:"";
						$berat				= isset($_POST['berat'])?$_POST['berat']:"";

						$this->jCfg['cart']['store'][$id]= array(
							"id"			=> $id,
							"product_name"	=> $product_name,
							"qty"			=> $qty,
							"price"			=> $price,
							"berat"			=> $berat
						);
					
						$this->_releaseSession();				
					}
			
		}

		$data['data'] = isset($this->jCfg['cart']['store'])?$this->jCfg['cart']['store']:'';
			//debugCode($data['data']);
		//debugCode($batas);
		$this->_v('cart-store',$data);	
	}
	
	function checkout_store(){
		$n = '';
		
		$next = isset($_GET['next'])?$_GET['next']:base_url();
		if( trim($this->user_login)=="" ){
			$n = '?next='.$next;
		}
		$this->user_login = isset($this->jCfg['member']['id'])?$this->jCfg['member']['id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}
		
		//debugCode($code);

		$data['me'] = $this->db->get_where("mt_member",array(
			"id"	=> $this->user_login
		))->row();

		//debugCode($data['biaya']);
		
		$data['data'] = isset($this->jCfg['cart']['store'])?$this->jCfg['cart']['store']:'';

		if(!empty($_POST['order'])){
				 
				// debugCode($_POST); 
		  	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randstring = '';
			for ($i = 0; $i < 6; $i++) {
					$randstring .= $characters[rand(0, strlen($characters))];
			}
			
			/*-- no invoice --*/
			$n = $this->db->query("
				select max(substr(transaction_invoice,-10)) as jum from mt_transaction_store
			")->row();
			$v = $n->jum+1;
			$inv = "INV/".date("Y")."/".date("m")."/".date("d")."/".str_repeat("0",10-strlen($v)).$v;
			
			$Data = array(
				"transaction_member_id" 		=> $this->jCfg['member']['id'],
				"transaction_member" 			=> $data['me']->nama,
				"transaction_email" 			=> $data['me']->email,
				"transaction_hp" 				=> $data['me']->hp,
				"transaction_alamat" 			=> $data['me']->alamat,
				"transaction_code" 				=> $randstring,
				"transaction_invoice" 			=> $inv,
				"transaction_pay_method" 		=> "transfer",
				"transaction_pay_status"		=> "ORDERING",
				"transaction_price"				=> $_POST['total_price'],
				"transaction_biaya_kirim"		=> $_POST['tarif'],
				"transaction_date" 				=> date("Y-m-d H:i:s"),
				"transaction_status" 			=> 0,
				"transaction_istrash" 			=> 0
			);
			//debugCode($Data);
				$this->db->insert('mt_transaction_store', $Data); 
				$trans_id 	= $this->db->insert_id();							
				$dataTrans['transaction'] 	= $Data;
				$dataTrans['trans_id'] 		= $trans_id;
						
			$prod = isset($this->jCfg['cart']['store'])?$this->jCfg['cart']['store']:'';
			if(!empty($prod)){
				
				foreach($prod as $p){

					$postData = array(
						'detail_id_member'				=> $this->jCfg['member']['id'],
						'detail_transaction_id'			=> $trans_id,
						'detail_trans_id'				=> $p['id'],
						'detail_trans_nama'				=> $p['product_name'],
						'detail_trans_price'			=> $p['price'],
						'detail_trans_qty'				=> $p['qty']
					);
			
				$this->db->insert('mt_transaction_detail', $postData); 
				}
			}
				
							$pesan_email 				= $this->load->view('front/fullwidth/email_pemesanan',$dataTrans,TRUE);
							//debugCode($pesan_email);
			

            				$config['protocol'] = 'smtp';
							$config['smtp_host'] = 'mail.vanillahijab.com';
							$config['smtp_port'] = 25;
							$config['smtp_user'] = 'no-reply@vanillahijab.com';
							$config['smtp_pass'] = 'Hmw7v4&6';
							$config['mailtype']  = 'html';

						$this->load->library('email', $config);
						$this->email->set_newline("\r\n");
						$this->email->from("no-reply@vanillahijab.com", "Vanilla Hijab");
						$this->email->to($this->jCfg['member']['email']); 
						//$this->email->cc('raditya.pratama3@gmail.com');       
						$this->email->subject("Pemesanan di vanillahijab.com");        
						$this->email->message($pesan_email);
			
						unset($this->jCfg['cart']);
						$this->_releaseSession();

						if($this->email->send()){
							
						}
						redirect(base_url().'messages/ordering/'.$trans_id.'?next='.base_url().'messages/ordering/'.$trans_id);		
						
						
		}
		
		$data['page'] = "pay";
		$this->_v('checkout_store',$data);
	}
	

	function messages($id="",$trans){
		$n = base_url().'messages';
		$next = isset($_GET['next'])?$_GET['next']:"";
		if(isset($n)){
			$n = '?next='.$next;
		}
		//debugCode($trans);
		$this->user_login = isset($this->jCfg['member']['id'])?$this->jCfg['member']['id']:'';
		if( trim($this->user_login)=="" ){
			redirect(base_url().'login'.$n);
		}
		$data['me'] = $this->db->get_where("mt_member",array(
			"id"	=> $this->user_login
		))->row();

		$data['transaction'] = $this->db->get_where("mt_transaction_store",array(
			"id"	=> $trans
		))->row();

		$data['detail'] = $this->db->get_where("mt_transaction_detail",array(
			"detail_transaction_id"	=> $trans
		))->result();

		//debugCode($data['detail']);
		
		$data['conf_id'] = $this->user_login;
		if($id == 'confirmation'){
			$this->_v('messages_confirmation',$data);
		}
		if($id == 'ordering'){
			$this->_v('messages_ordering',$data);
		}
		if($id == 'success'){
			$this->_v('messages_success',$data);
		}
		if($id == 'register'){
			$this->_v('messages_register',$data);
		}
	}
	
    function delete_cart_ppl(){
		$id_detail 	= isset($_GET['id'])?dbClean($_GET['id']):'';
		if(!empty($id_detail)){
			unset($this->jCfg['cart']['items'][$id_detail]);
			$this->_releaseSession();
		}
		redirect(base_url().'cart');
	}

	function search(){
		/*-- search --*/
		$keyword = $this->jCfg['search']['keyword'];
		if(isset($_POST['search'])){
			$keyword = dbClean($_POST['keyword']);
			$this->jCfg['search']['keyword'] = $keyword;
			$this->_releaseSession();
		}
		/*-- page --*/
		$this->per_page = 9;
		$this->uri_segment = 2;
		$this->data_table = $this->M->data_article_front(array(
			'keyword'	=> $keyword, 
			'limit' 	=> $this->per_page, 
			'offset'	=> $this->uri->segment($this->uri_segment)
		) );
		$data = $this->_data_front(array(
				'base_url'		=> base_url().'search'
			)
		);
		$data['keyword'] = $keyword;
		$data['page'] = "Search";
		$data['menu'] = "news";
		$this->_v('category',$data);	
	}
	
	
	function contact(){
		$this->cur_menu = 'lapor';
		$data['menu_id'] = $_GET['id'];
		$data['contact'] = $this->db->get("mt_contact")->row();
		$data['title'] = "Hubungi Kami";
		$data['page'] = "Hubungi Kami";
		$data['menu'] = "contact";
		$this->_v('contact',$data);	
	}
	
	function postemail(){
		$name 		= isset($_POST['name'])?$_POST['name']:"";
		$emailfrom 	= isset($_POST['email'])?$_POST['email']:"";
		$message 	= isset($_POST['message'])?$_POST['message']:"";
		$emailto	= get_name_app('configuration_email');
		
		$config['protocol']  = 'smtp';
		$config['smtp_host'] = 'mail.gerbangusaha.com';
		$config['smtp_port'] = 25;
		$config['smtp_user'] = 'order@gerbangusaha.com';
		$config['smtp_pass'] = 'g3rb4ngus4h4';
		$config['mailtype']  = 'html';		
		
        if( empty($name) || empty($emailfrom) || empty($message) ){
			$error = "Incomplete data. please check your data";
			redirect(base_url().'contact-us?i=1&msg='.$error);
		}
		
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($emailfrom, $name);
        $this->email->to($emailto);        
        $this->email->subject('Request for Contact us');        
        $this->email->message($message);
         
        if($this->email->send()){
            redirect(base_url().'contact-us?i=2&msg=Your email was sent successfully.');
        }else{
            redirect(base_url().'contact-us?i=2&msg=Your email failed to sent.');
		}
	}

	function city($id=""){
		/* ID article */
		
		/* data article */
		$return = $this->db->get_where("mt_app_city",array(
			"city_province_id"	=> $id
		))->result();	

		die(json_encode($return));
		
		
	}

	
	function test(){
		$zip = new ZipArchive;
		if ($zip->open('test.zip') === TRUE) {
		  $zip->extractTo('assets/zip/');
		  $zip->close();
		  echo 'ok';
		} else {
		  echo 'failed';
		}
	}
}
