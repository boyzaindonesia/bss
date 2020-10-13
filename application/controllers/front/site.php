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
		$this->isMobile = false;
	    $detect = new Mobile_Detect();
	    if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) { $this->isMobile = true; }
	    // if($this->isMobile){ echo 'Mobile'; } else { echo 'Desktop'; } ON VIEW

	    // if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
	    //     header("Location: /mobile"); exit;
	    // } else {
	    //     header("Location: /desktop"); exit;
	    // }


		/* email config */
		$this->config_email = config_email();

		checkIsBlacklistOnLoad();

	}

	function index(){
		$this->page     = '';
		$this->cur_menu = 'home';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		/*
		$data['menu_id'] = $_GET['id'];
		$data['contact'] = $this->db->get("mt_contact")->row();
		$this->_v('contact',$data);
		*/
		$data = '';
		$data['menu'] = "home";
		$data['menu_id'] = "";

		$this->_v('home',$data);

		// $data['menu'] = "Coming Soon";
		// $data['menu_id'] = "";
		// $this->_v('coming_soon',$data);
	}

	function home(){
		$this->page     = '';
		$this->cur_menu = 'home';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		/*
		$data['menu_id'] = $_GET['id'];
		$data['contact'] = $this->db->get("mt_contact")->row();
		$this->_v('contact',$data);
		*/
		$data = '';
		$data['menu'] = "home";
		$data['menu_id'] = "";

		$this->_v('home',$data);
	}

	function notify_me(){
		$data = '';
		$msg  = "";
		$status = "error";

		if(isset($_POST['email']) && $_POST['email']!=""){
			$email = $_POST['email'];

			$h =  $this->db->get_where("mt_notify_me",array(
				"email"	=> $email
			))->result();
			if(empty($h)){
				$msg = timestamp();

				$data = array(
					'email'			=> dbClean($email),
					'date_created'	=> timestamp()
					);

				$this->DATA->table = "mt_notify_me";
				$a = $this->_save_master(
					$data,
					array(
						'notify_me_id' => ''
					)
				);

				$status = "success";
			} else {
				$status = "success";
			}
		}

        $return = array('status' => $status,
                        'msg' => $msg
                        );
        die(json_encode($return));
        exit();
	}

	function about(){
		$this->page     = 'About';
		$this->cur_menu = 'about';
		$this->header_type = '1';
		$this->footer_type = '1';
		$this->url_back    = '';

		$data = '';
		$data['menu'] = "about";
		$data['menu_id'] = "";
		$this->_v('about',$data);
	}

	function news(){
		//$this->output->cache(30);
		$this->page     = 'Blog & News';
		$this->cur_menu = 'news';
		$this->header_type = '1';
		$this->footer_type = '1';
		$this->url_back    = '';

		$data = '';
		$data['menu'] = "news";
		$data['menu_id'] = "";

		$id = '1';
		$cat = $this->db->get_where("mt_article_category",array(
			"category_id"	=> $id
		))->row();
		$data['category_title']	= $cat->category_title;
		$data['category_desc']	= $cat->category_desc;
		$data['category_image']	= $cat->category_image;
		$this->page             = $cat->category_title;

		$url 	= $this->uri->segment(2);
		if(empty($url)){
			/* paging article */
			$pageNum = 1;
	        if(isset($_GET['page'])&&$_GET['page']!=''){ $pageNum = $_GET['page']; }

			$this->per_page     = 12;
	        $this->offset       = ($pageNum - 1) * $this->per_page;
			$this->data_table   = $this->M->data_article_front(array(
				'category_id' 	=> $id,
				'limit' 		=> $this->per_page,
				'offset'		=> $this->offset
			));
			$data["article"] = $this->_data_front(array(
				'pageNum'		=> $pageNum,
				'base_url'		=> base_url().'news?'
			));

			// $data["news"] 		= front_load_article($id,8,0);
			// $data["count_news"] = front_load_article_count($id);
			// $data['ids']        = $id;

			//debugCode($data);
			$this->_v('news',$data);

		} else {
			$this->url_back    = base_url().'news';

			/* data article */
			$data['article'] = $this->db->get_where("mt_article",array(
				"article_istrash"	=> 0,
				"article_status"	=> 1,
				"url"				=> $url
			),1,0)->row();
			if(count($data['article']) > 0){
				//debugCode($data);
				$article_count = 0;
				foreach($data as $r){
					$article_id    = $r->article_id;
					$article_count = ($r->article_count + 1);
					$data2 = array(
			           'article_count' => $article_count
			        );
					$this->db->where('article_id', $article_id);
					$this->db->update('mt_article', $data2);
				}
			}

			$this->_v('news_detail',$data);
		}
	}

	function load_more_news(){
		$data = '';
		$content  = "";
		$lastPage = false;
		$pageShow = "";
		$msg      = "";

		if(isset($_POST['thisAction']) && $_POST['thisAction']=="load"){
	        $i = 0;
			$id = $_POST['id'];
			$iddetail = $_POST['iddetail'];
			$result = $_POST['result'];
			$rowsPerPage = $_POST['rowsPerPage'];
			$pageShow = $_POST['pageShow'];
			$maxPage = $_POST['maxPage'];

			$h =  $this->db->order_by("article_id","DESC")->get_where("mt_article",array(
				"article_category_id"	=> $id,
				"article_istrash"	=> 0,
				"article_status"	=> 1
			),$rowsPerPage,$pageShow)->result();

			 // debugCode($h);
            foreach($h as $v=>$r){
                // ($r->image_type==1?$i+=1:'');
                if($i<$rowsPerPage){

                	$article_content_small = strip_tags(stripslashes($r->article_content));
                	$article_content_small = substr( $article_content_small, 0,140).' ...';

                	$content .= '<div class="item col-sm-6 col-md-4 col-lg-3" style="opacity:0;">
                            <div class="item-container shadow">
                                <div class="item-img scale">
			                        <a href="'.base_url().'news/'.$r->url.'">
                                    <img class="fullwidth" src="'.base_url().'assets/collections/article/thumb/'.$r->article_image.'">
                                    '.($r->article_video!=''?'<div class="play-video"></div>':'').'
                                    </a>
                                </div>
                                <div class="item-content">
			                        <a href="'.base_url().'news/'.$r->url.'">
	                                    <h3>'.$r->article_title.'</h3>
	                                    <p>'.$r->article_lead.'</p>
                                    </a>
                                    <div class="item-action">
	                                    <div class="action-date">
	                                        <div class="icon icon-xs icon-date"></div>
	                                        <span>'.convDateEng($r->article_date).' | '.$r->article_count.' views</span>
	                                    </div>
	                                    <div class="action-share">
	                                        <div class="action-share-content">
	                                             <a title="Facebook" href="javascript:;" data-url="'.current_url().'" data-title="'.$r->article_title.' - '.$r->article_lead.'" data-image="'.base_url().'assets/collections/article/large/'.$r->article_image.'" data-desc="'.$r->article_lead.'" class="fb-share-button">
	                                                <img src="'.base_url().'assets/images/icon-facebook-2.png"/>
	                                            </a>
	                                            <a title="Twitter" href="javascript:;" data-url="'.current_url().'" data-text="'.$r->article_title.'" data-image="'.base_url().'assets/collections/article/large/'.$r->article_image.'" data-hashtags="fhebykerehore" data-via="fhebykerehore" class="tw-share-button">
	                                                <img src="'.base_url().'assets/images/icon-twitter-2.png"/>
	                                            </a>
	                                        </div>
	                                        <div class="icon icon-xs icon-share btn-share"></div>
	                                    </div>
	                                </div>
                                </div>
                            </div>
                        </a>
                    </div>';

	                $i+=1;
	                $pageShow+=1;
                }
            }

		}

		// $pageShow = $pageShow + $rowsPerPage;
        // if(ceil($pageShow/($rowsPerPage)) > $maxPage){ $lastPage = true; }
        if($pageShow >= $maxPage){ $lastPage = true; }

        // debugCode($lastPage);
        $return = array('content' => $content,
                        'lastPage' => $lastPage,
                        'pageShow' => $pageShow,
                        'msg' => $msg
                        );
        die(json_encode($return));
        // exit();
	}


	function how_to_buy(){
		$this->page     = 'How To Buy';
		$this->cur_menu = 'how_to_buy';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$data = '';
		$data['menu'] = "how_to_buy";
		$data['menu_id'] = "";

		$this->_v('how_to_buy',$data);
	}

	function faq(){
		$this->page     = 'FAQ';
		$this->cur_menu = 'faq';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$data = '';
		$data['menu'] = "faq";
		$data['menu_id'] = "";

		$this->_v('faq',$data);
	}

	function legal_information(){
		$this->page     = 'Legal Information';
		$this->cur_menu = 'legal_information';
		$this->header_type = '0';
		$this->footer_type = '0';
		$this->url_back    = '';

		$data = '';
		$data['menu'] = "legal_information";
		$data['menu_id'] = "";

		$id  = '6';
		$cat = $this->db->get_where("mt_article_category",array(
			"category_id"	=> $id
		),1,0)->row();
		if(count($cat) > 0){
			$data['article_category'] = $cat;

			$data['article_group'] = $this->db->order_by('article_date', 'desc')->get_where("mt_article",array(
				"article_category_id"	=> $cat->category_id
			))->result();

			$url = $this->uri->segment(2);
			$legal = $this->db->get_where("mt_article",array(
				"url"	=> $url
			),1,0)->row();
			if(count($legal) > 0){
				$this->page     = $legal->article_title;
				$this->cur_menu = $legal->url;

				$data['legal'] = $legal;
				$this->_v('page_legal_information',$data);
			} else {
				$this->_v('404',$data);
			}
		} else {
			$this->_v('404',$data);
		}
	}

	// function contact(){
	// 	$this->page     = 'Contact Us';
	// 	$this->cur_menu = 'contact';
	// 	$this->header_type = '1';
	// 	$this->footer_type = '1';
	// 	$this->url_back    = '';

	// 	$data = '';
	// 	$data['menu'] = "contact";
	// 	$data['menu_id'] = "";

	// 	$id = '4';
	// 	$cat = $this->db->get_where("mt_article_category",array(
	// 		"category_id"	=> $id
	// 	))->row();
	// 	$data['category_title']	= $cat->category_title;
	// 	$data['category_desc']	= $cat->category_desc;
	// 	$data['category_image']	= $cat->category_image;

	// 	$data['contact'] = $this->db->get("mt_contact")->result();

	// 	// debugCode($data['contact']);
	// 	$this->_v('contact',$data);
	// }

	// function pages($id=''){
	// 	$data = '';
	// 	$cat = $this->db->get_where("mt_article_category",array(
	// 		"category_id"	=> $id
	// 	))->row();
	// 	$data['category_title']	= $cat->category_title;
	// 	$data['category_desc']	= $cat->category_desc;
	// 	$data['category_image']	= $cat->category_image;

	// 	$data['article'] = $this->db->get_where("mt_article",array(
	// 		"article_category_id"	=> $id
	// 	))->result();

	// 	$this->page     = $cat->category_title;
	// 	$this->cur_menu = $cat->category_title;
	// 	$this->header_type = '1';
	// 	$this->footer_type = '1';
	// 	$this->url_back    = '';

	// 	$data['menu'] = $cat->category_title;
	// 	$data['menu_id'] = $id;

	// 	$this->_v('pages',$data);
	// }

	function test_email(){
		$this->load->library('email', $this->config_email);
		$this->email->set_newline("\r\n");
		$this->email->from($this->config_email['smtp_user'], $this->config_email['smtp_fullname']);

		$this->email->to('fheby.kere.hore@gmail.com');
		//$this->email->cc('fheby.kere.hore@gmail.com');
		$this->email->subject('Notifikasi: Test kirim email dari website '.$_SERVER['HTTP_HOST']);
		$this->email->message('<p>Test kirim email jam :'.timestamp().'</p>');
		if($this->email->send()){
			echo "Your email was sent successfully.";
		} else {
			echo "Your email failed to sent.";
		}
	}

	function send_message(){

		if( isset($_POST['thisAction']) && $_POST['thisAction'] == 'send_message' ){
			$message_name 		= isset($_POST['message_name'])?$_POST['message_name']:"";
			$message_email 		= isset($_POST['message_email'])?$_POST['message_email']:"";
			$message_phone 		= isset($_POST['message_phone'])?$_POST['message_phone']:"";
			$message_subject 	= isset($_POST['message_subject'])?$_POST['message_subject']:"";
			$message_desc 		= isset($_POST['message_desc'])?$_POST['message_desc']:"";

	        if( empty($message_name) || empty($message_email) || empty($message_desc) ){
				redirect(base_url()."contact?msg=".urlencode('Incomplete data. please check your data')."&type_msg=error");
			}

			$to_email	= get_name_app('configuration_email');
			$to_name	= get_name_app('configuration_name');

			$mail_message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					    <title>'.$to_name.'</title>
					    <style type="text/css">
					        h1,h2,h3,h4,h5,h6, p { color:#0c0c0c; }
					        a { text-decoration:none; }
					        img { display:block; }
					    </style>
					</head>
					<body style="padding:0; margin:0px auto; background:#ffffff; width:100%;">
					    <table border="0" cellpadding="10" cellspacing="0" width="100%" style="margin:8px auto; width:600px; background:#ffffff; color:#0c0c0c; font-size:13px; font-family:Helvetica, sans-serif, Arial;">
					        <tr>
					            <td style="background-color:#ffffff; padding:10px 25px;">
					                <h2 style="margin:0px 0px 15px 0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Hai,</h2>
					                <p style="margin:0px 0px 5px 0px; padding: 3px 0px; text-align: left;">Anda mendapat pesan baru dari website <a href="'.base_url().'">'.$_SERVER['HTTP_HOST'].'</a>, dengan detail sbb:</p>
					                <table border="0" cellpadding="0" cellspacing="0" width="100%">
					                    <tr valign="top">
					                        <td width="110"><p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Nama</p></td>
					                        <td><p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">: '.$message_name.'</p></td>
					                    </tr>
					                    <tr valign="top">
					                        <td><p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Email</p></td>
					                        <td><p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">: <a href="mailto:'.$message_email.'" target="_blank">'.$message_email.'</a></p></td>
					                    </tr>
					                    <tr valign="top">
					                        <td><p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Phone</p></td>
					                        <td><p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">: '.$message_phone.'</p></td>
					                    </tr>
					                    <tr valign="top">
					                        <td><p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Subject</p></td>
					                        <td><p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">: '.$message_subject.'</p></td>
					                    </tr>
					                    <tr valign="top">
					                        <td><p style="margin:0px 0px 15px 0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Pesan</p></td>
					                        <td><p style="margin:0px 0px 15px 0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">: '.$message_desc.'</p></td>
					                    </tr>
					                </table>
					                <p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Terima Kasih,</p>
					                <p style="margin:0px 0px 15px 0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Automated Mailer. ( jangan membalas email ini. )</p>
					            </td>
					        </tr>
					        <tr>
					            <td style="background-color:#f6f6f6; padding:0px;"><p style="margin:0px 0px 0px 0px; padding: 3px 0px; text-align: center;">Copyright &copy; 2016. <a href="'.base_url().'">'.ucwords($to_name).'</a>. All Rights Reserved.</p></td>
					        </tr>
					    </table>
					</body>
					</html>';

			$mail_message_fed = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					    <title>'.$to_name.'</title>
					    <style type="text/css">
					        h1,h2,h3,h4,h5,h6, p { color:#0c0c0c; }
					        a { text-decoration:none; }
					        img { display:block; }
					    </style>
					</head>
					<body style="padding:0; margin:0px auto; background:#ffffff; width:100%;">
					    <table border="0" cellpadding="10" cellspacing="0" width="100%" style="margin:8px auto; width:600px; background:#ffffff; color:#0c0c0c; font-size:13px; font-family:Helvetica, sans-serif, Arial;">
					        <tr>
					            <td style="background-color:#ffffff; padding:10px 25px;">
					                <h2 style="margin:0px 0px 15px 0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Hai '.$message_name.',</h2>
					                <p style="margin:0px 0px 15px 0px; padding: 3px 0px; text-align: left;">Terima kasih anda sudah menghubungi kami di <a href="'.base_url().'">'.$_SERVER['HTTP_HOST'].'</a></p>
					                <p style="margin:0px 0px 15px 0px; padding: 3px 0px; text-align: left;">Kami akan menghubungi anda secepatnya.</p>
					                <p style="margin:0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Terima Kasih,</p>
					                <p style="margin:0px 0px 15px 0px; padding: 3px 0px; color:#0c0c0c; text-align: left;">Automated Mailer. ( jangan membalas email ini. )</p>
					            </td>
					        </tr>
					        <tr>
					            <td style="background-color:#f6f6f6; padding:0px;"><p style="margin:0px 0px 0px 0px; padding: 3px 0px; text-align: center;">Copyright &copy; 2016. <a href="'.base_url().'">'.ucwords($to_name).'</a>. All Rights Reserved.</p></td>
					        </tr>
					    </table>
					</body>
					</html>';

			$data = array(
				'message_name'			=> dbClean($_POST['message_name']),
				'message_email'			=> dbClean($_POST['message_email']),
				'message_phone'			=> dbClean($_POST['message_phone']),
				'message_subject'		=> dbClean($_POST['message_subject']),
				'message_desc'			=> dbClean($_POST['message_desc']),
				'message_date'			=> timestamp(),
				'notify'				=> 3
			);

			$this->DATA->table = "mt_message";
			$a = $this->_save_master(
				$data,
				array(
					'message_id' => ''
				)
			);

			// DARI SINI
			// $this->load->library('email', $this->config_email);
			// $this->email->set_newline("\r\n");
			// $this->email->from($this->config_email['smtp_user'], $this->config_email['smtp_fullname']);

			// $this->email->to($to_email);
			// //$this->email->cc('fheby.kere.hore@gmail.com');
			// $this->email->subject('Notifikasi: Pesan baru dari website '.$_SERVER['HTTP_HOST']);
			// $this->email->message($mail_message);
			// $this->email->send();

			// // $this->load->library('email', $config);
			// // $this->email->set_newline("\r\n");
			// // $this->email->from("no-reply@tandafuntastrip.com", $to_name);

			// $this->email->to($message_email, $message_name);
			// $this->email->subject('Terima kasih anda sudah menghubungi '.$_SERVER['HTTP_HOST']);
			// $this->email->message($mail_message_fed);
			// SAMPAI SINI

	        if($this->email->send()){
				redirect(base_url()."contact?msg=".urlencode('Your email was sent successfully.')."&type_msg=success");
	        } else {
				redirect(base_url()."contact?msg=".urlencode('Your email failed to sent.')."&type_msg=error");
			}
		}

	}


	// function sicepat_to(){
	// 	$data = '';

	// 	$requestUrl = 'http://api.sicepat.com/customer/destination?api-key=391274a334f7527a6c1f340532aeaddd';
	// 	$response  = file_get_contents($requestUrl);
	// 	$jsonobj  = json_decode($response);
	// 	$resultArray = object2array($jsonobj);
	// 	//debugCode($resultArray[sicepat]);
	// 	foreach($resultArray[sicepat][results] as $r ){
	// 		$Data = array(
	// 			"destination_code" 		=> $r[destination_code],
	// 			"subdistrict" 			=> $r[subdistrict],
	// 			"city" 					=> $r[city],
	// 			"province" 				=> $r[province]
	// 		);
	// 		//debugCode($Data);
	// 			$this->db->insert('mt_sicepat', $Data);
	// 			$trans_id 	= $this->db->insert_id();
	// 	}

	// }



	// function checkout_store(){
	// 	$n = '';

	// 	$next = isset($_GET['next'])?$_GET['next']:base_url();
	// 	if( trim($this->user_login)=="" ){
	// 		$n = '?next='.$next;
	// 	}
	// 	$this->user_login = isset($this->jCfg['member']['id'])?$this->jCfg['member']['id']:'';
	// 	if( trim($this->user_login)=="" ){
	// 		redirect(base_url().'login'.$n);
	// 	}

	// 	//debugCode($code);

	// 	$data['me'] = $this->db->get_where("mt_member",array(
	// 		"member_id"	=> $this->user_login
	// 	))->row();

	// 	//debugCode($data['biaya']);

	// 	$data['data'] = isset($this->jCfg['cart']['store'])?$this->jCfg['cart']['store']:'';

	// 	if(!empty($_POST['order'])){

	// 			// debugCode($_POST);
	// 	  	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	// 		$randstring = '';
	// 		for ($i = 0; $i < 6; $i++) {
	// 				$randstring .= $characters[rand(0, strlen($characters))];
	// 		}

	// 		/*-- no invoice --*/
	// 		$n = $this->db->query("
	// 			select max(substr(transaction_invoice,-10)) as jum from mt_transaction_store
	// 		")->row();
	// 		$v = $n->jum+1;
	// 		$inv = "INV/".date("Y")."/".date("m")."/".date("d")."/".str_repeat("0",10-strlen($v)).$v;

	// 		$Data = array(
	// 			"transaction_member_id" 		=> $this->jCfg['member']['id'],
	// 			"transaction_member" 			=> $data['me']->nama,
	// 			"transaction_email" 			=> $data['me']->email,
	// 			"transaction_hp" 				=> $data['me']->hp,
	// 			"transaction_alamat" 			=> $data['me']->alamat,
	// 			"transaction_code" 				=> $randstring,
	// 			"transaction_invoice" 			=> $inv,
	// 			"transaction_pay_method" 		=> "transfer",
	// 			"transaction_pay_status"		=> "ORDERING",
	// 			"transaction_price"				=> $_POST['total_price'],
	// 			"transaction_biaya_kirim"		=> $_POST['tarif'],
	// 			"transaction_date" 				=> date("Y-m-d H:i:s"),
	// 			"transaction_status" 			=> 0,
	// 			"transaction_istrash" 			=> 0
	// 		);
	// 		//debugCode($Data);
	// 			$this->db->insert('mt_transaction_store', $Data);
	// 			$trans_id 	= $this->db->insert_id();
	// 			$dataTrans['transaction'] 	= $Data;
	// 			$dataTrans['trans_id'] 		= $trans_id;

	// 		$prod = isset($this->jCfg['cart']['store'])?$this->jCfg['cart']['store']:'';
	// 		if(!empty($prod)){

	// 			foreach($prod as $p){

	// 				$postData = array(
	// 					'detail_id_member'				=> $this->jCfg['member']['id'],
	// 					'detail_transaction_id'			=> $trans_id,
	// 					'detail_trans_id'				=> $p['id'],
	// 					'detail_trans_nama'				=> $p['product_name'],
	// 					'detail_trans_price'			=> $p['price'],
	// 					'detail_trans_qty'				=> $p['qty']
	// 				);

	// 			$this->db->insert('mt_transaction_detail', $postData);
	// 			}
	// 		}

	// 						$pesan_email 				= $this->load->view('front/fullwidth/email_pemesanan',$dataTrans,TRUE);
	// 						//debugCode($pesan_email);


 //            				$config['protocol'] = 'smtp';
	// 						$config['smtp_host'] = 'mail.vanillahijab.com';
	// 						$config['smtp_port'] = 25;
	// 						$config['smtp_user'] = 'no-reply@vanillahijab.com';
	// 						$config['smtp_pass'] = 'Hmw7v4&6';
	// 						$config['mailtype']  = 'html';

	// 					$this->load->library('email', $config);
	// 					$this->email->set_newline("\r\n");
	// 					$this->email->from("no-reply@vanillahijab.com", "Vanilla Hijab");
	// 					$this->email->to($this->jCfg['member']['email']);
	// 					//$this->email->cc('raditya.pratama3@gmail.com');
	// 					$this->email->subject("Pemesanan di vanillahijab.com");
	// 					$this->email->message($pesan_email);

	// 					unset($this->jCfg['cart']);
	// 					$this->_releaseSession();

	// 					if($this->email->send()){

	// 					}
	// 					redirect(base_url().'messages/ordering/'.$trans_id.'?next='.base_url().'messages/ordering/'.$trans_id);


	// 	}

	// 	$data['page'] = "pay";
	// 	$this->_v('checkout_store',$data);
	// }


	// function messages($id="",$trans){
	// 	$n = base_url().'messages';
	// 	$next = isset($_GET['next'])?$_GET['next']:"";
	// 	if(isset($n)){
	// 		$n = '?next='.$next;
	// 	}
	// 	//debugCode($trans);
	// 	$this->user_login = isset($this->jCfg['member']['id'])?$this->jCfg['member']['id']:'';
	// 	if( trim($this->user_login)=="" ){
	// 		redirect(base_url().'login'.$n);
	// 	}
	// 	$data['me'] = $this->db->get_where("mt_member",array(
	// 		"member_id"	=> $this->user_login
	// 	))->row();

	// 	$data['transaction'] = $this->db->get_where("mt_transaction_store",array(
	// 		"id"	=> $trans
	// 	))->row();

	// 	$data['detail'] = $this->db->get_where("mt_transaction_detail",array(
	// 		"detail_transaction_id"	=> $trans
	// 	))->result();

	// 	//debugCode($data['detail']);

	// 	$data['conf_id'] = $this->user_login;
	// 	if($id == 'confirmation'){
	// 		$this->_v('messages_confirmation',$data);
	// 	}
	// 	if($id == 'ordering'){
	// 		$this->_v('messages_ordering',$data);
	// 	}
	// 	if($id == 'success'){
	// 		$this->_v('messages_success',$data);
	// 	}
	// 	if($id == 'register'){
	// 		$this->_v('messages_register',$data);
	// 	}
	// }

 //    function delete_cart_ppl(){
	// 	$id_detail 	= isset($_GET['id'])?dbClean($_GET['id']):'';
	// 	if(!empty($id_detail)){
	// 		unset($this->jCfg['cart']['items'][$id_detail]);
	// 		$this->_releaseSession();
	// 	}
	// 	redirect(base_url().'cart');
	// }

	// function search(){
	// 	/*-- search --*/
	// 	$keyword = $this->jCfg['search']['keyword'];
	// 	if(isset($_POST['search'])){
	// 		$keyword = dbClean($_POST['keyword']);
	// 		$this->jCfg['search']['keyword'] = $keyword;
	// 		$this->_releaseSession();
	// 	}
	// 	/*-- page --*/
	// 	$this->per_page = 9;
	// 	$this->uri_segment = 2;
	// 	$this->data_table = $this->M->data_article_front(array(
	// 		'keyword'	=> $keyword,
	// 		'limit' 	=> $this->per_page,
	// 		'offset'	=> $this->uri->segment($this->uri_segment)
	// 	) );
	// 	$data = $this->_data_front(array(
	// 			'base_url'		=> base_url().'search'
	// 		)
	// 	);
	// 	$data['keyword'] = $keyword;
	// 	$data['page'] = "Search";
	// 	$data['menu'] = "news";
	// 	$this->_v('category',$data);
	// }


	// function test(){
	// 	$zip = new ZipArchive;
	// 	if ($zip->open('test.zip') === TRUE) {
	// 	  $zip->extractTo('assets/zip/');
	// 	  $zip->close();
	// 	  echo 'ok';
	// 	} else {
	// 	  echo 'failed';
	// 	}
	// }


	// function pindah_data_table (){
	// 	$t = $this->db->get_where("mt_product_category_2")->result();
	// 	foreach ($t as $r) {
	// 		$data = array(
	// 			'product_category_date'			=> dbClean($r->product_category_date),
	// 			'product_category_status'		=> 1,
	// 			'product_category_title'		=> dbClean($r->product_category_title),
	// 			'product_category_desc'			=> dbClean($r->product_category_desc),
	// 			'product_category_image'		=> dbClean($r->product_category_image),
	// 			'product_category_parent_id'	=> dbClean($r->product_category_parent_id),
	// 			'position'						=> dbClean($r->position),
	// 		);

	// 		$title = dbClean($r->product_category_title);
	// 		$data['url'] = generateUniqueURL($title,"mt_product_category");

	// 		$this->DATA->table="mt_product_category";
	// 		$a = $this->_save_master(
	// 			$data,
	// 			array(
	// 				'product_category_id' => 0
	// 			)
	// 		);
	// 	}
	// }

}
