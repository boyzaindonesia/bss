<?php
include_once(APPPATH."libraries/FrontController.php");
class Gallery extends FrontController {
	var $cur_menu = '';

	function __construct()  
	{
		parent::__construct(); 
		$this->load->model("mdl_gallery","F");

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
		$this->page     = 'Gallery';
		$this->cur_menu = 'gallery';
		$this->header_type = '1';
		$this->footer_type = '1';
		$this->url_back    = '';

		/*
		$data['menu_id'] = $_GET['id'];
		$data['contact'] = $this->db->get("mt_contact")->row();
		$this->_v('contact',$data);	
		*/
		$data = '';
		$data['menu'] = "gallery";
		$data['menu_id'] = "";

		/* paging article */
		// $this->per_page = 10;
		// $this->uri_segment = 2;
		// $this->data_table = $this->M->data_article_front(array(
		// 	'category_id' 	=> 1,
		// 	'limit' 		=> $this->per_page, 
		// 	'offset'		=> $this->uri->segment($this->uri_segment)
		// ));
		// $data = $this->_data_front(array(
		// 	'base_url'		=> base_url().'gallery/'.$t
		// ));

		$id 	= '';
		$data["gallery"] 		= front_load_gallery($id,8,0);
		$data["count_gallery"]  = front_load_count_gallery($id);
		$data['ids']            = $id;

		$id = '5';
		$cat = $this->db->get_where("mt_article_category",array(
			"category_id"	=> $id
		))->row();
		$data['category_title']	= $cat->category_title;
		$data['category_desc']	= $cat->category_desc;
		$data['category_image']	= $cat->category_image;

		//debugCode($data);
		$this->_v('gallery',$data);
	}

	function load_more_gallery(){
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

			$h =  $this->db->order_by("position","asc")->get_where("mt_gallery",array(
				"gallery_istrash"	=> 0,
				"gallery_status"	=> 1
			),$rowsPerPage,$pageShow)->result();

			 // debugCode($h);
            foreach($h as $v=>$r){
                // ($r->image_type==1?$i+=1:'');
                if($i<$rowsPerPage){

                    $content .= '<div class="item item-boxgrid col-sm-6 col-md-4 col-lg-3" style="opacity:0;">
                        <div class="item-container shadow">
                            <div class="item-img btn-item-boxgrid scale" data-img-boxgrid="'.base_url().'assets/collections/gallery/large/'.$r->gallery_images.'" data-video-boxgrid="'.(isset($r->gallery_video)?$r->gallery_video:'').'">
                                <img class="fullwidth" src="'.base_url()."assets/collections/gallery/thumb/".$r->gallery_images.'">
                                '.($r->gallery_video!=''?'<div class="play-video"></div>':'').'
                            </div>
                            <div class="item-content item-boxgrid-title">
                                <h3>'.$r->gallery_name.'</h3>
                                <div class="item-action">
                                    <div class="action-date">
                                        <div class="icon icon-xs icon-date"></div>
                                        <span>'.convDateEng($r->gallery_date).'</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';

                    $content .= '<script type="text/javascript">
					    $(document).ready(function($) {
					        BoxgridImg.init();
					    });
					</script>';
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
	
	function gallery_images(){
		$id = $this->uri->segment(3);
		$idMenu = $this->uri->segment(2);
		$id_gallery = explode("-",$id);
		$gallery_id = isset($id_gallery[0])?$id_gallery[0]:"";
		
		$data['gallery'] = $this->db->get_where("mt_gallery",array(
			"gallery_id"	=> $gallery_id
		))->row();
		
		/* gallery count*/
		$count 		= $data['gallery']->gallery_views + 1;
		$dataCount 	= array( 'gallery_views' => $count );
		$this->db->where('gallery_id', $gallery_id);
		$this->db->update('mt_gallery', $dataCount); 			
		//debugCode($data);
		$data['menu_id'] = $idMenu;
		$data['title'] = "Foto Galeri";
		$data['page'] = "Foto Galeri";
		$data['menu'] = "Foto Galeri";
		$this->_v('gallery_images',$data);	
	}
	
}
