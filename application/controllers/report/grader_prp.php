<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/excel/reader.php");


class Grader_prp extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("edit","delete"),"ITEM");
		$this->_set_title( 'List Grader' );
		$this->DATA->table="mt_member";
		$this->folder_view = "report/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_data_member","M");
		$this->breadcrumb[] = array(
				"title"		=> "Data Grader",
				"url"		=> $this->own_link
			);
			
	}

	
	
	
	
	function index($id=""){
		
		//debugCode($_POST);
		
		$this->breadcrumb[] = array(
				"title"		=> "List"
			);
		
					
		
		
		
		
		$this->per_page = 100;
		$par_filter = array(
				'kelas'		=> $id,
				"status_approve" => 1,	
				"offset"	=> $this->uri->segment($this->uri_segment),
				"limit"		=> $this->per_page
			);
		$this->data_table = $this->M->data_article_prp($par_filter);
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'?id='.$_GET['id']
			));
			

			
			$nor_a_tambah	  = !empty($_POST['mu_a_tambah'])?$_POST['mu_a_tambah']:0;
			$nor_b_tambah	  = !empty($_POST['mu_b_tambah'])?$_POST['mu_b_tambah']:0;
			$nor_c_tambah	  = !empty($_POST['mu_c_tambah'])?$_POST['mu_c_tambah']:0;
			$nor_d_tambah	  = !empty($_POST['mu_d_tambah'])?$_POST['mu_d_tambah']:0;
			$nor_e_tambah	  = !empty($_POST['mu_e_tambah'])?$_POST['mu_e_tambah']:0;
			$nor_a_kali		  = !empty($_POST['mu_a_kali'])?$_POST['mu_a_kali']:1;
			$nor_b_kali		  = !empty($_POST['mu_b_kali'])?$_POST['mu_b_kali']:1;
			$nor_c_kali		  = !empty($_POST['mu_c_kali'])?$_POST['mu_c_kali']:1;
			$nor_d_kali		  = !empty($_POST['mu_d_kali'])?$_POST['mu_d_kali']:1;
			$nor_e_kali		  = !empty($_POST['mu_e_kali'])?$_POST['mu_e_kali']:1;
			//debugCode($nor_b_kali);	
				
			$bobot_absen 		= 20 ;
			$bobot_studi_kasus  = 25 ;
			$bobot_mentoring    = 20 ;
			$mu = get_mu_prp($_GET['id']);
			$n=0;
			$jml_a = 0;
			$jml_b = 0;
			$jml_c = 0;
			$jml_d = 0;
			$jml_e = 0;
			$jml_lulus = 0;
			
			//debugCode($data);
			foreach($data['data'] as $r){
				$nilai_a = get_nilai($r->no_reg_iapi,$r->kelas,'A');
          		$nilai_b = get_nilai($r->no_reg_iapi,$r->kelas,'B');
          		$nilai_c = get_nilai($r->no_reg_iapi,$r->kelas,'C');
          		$nilai_d = get_nilai($r->no_reg_iapi,$r->kelas,'D');
          		$nilai_e = get_nilai($r->no_reg_iapi,$r->kelas,'E');
          		
          		$normal_a =  ( $nilai_a * $nor_a_kali ) + $nor_a_tambah ;  
				$normal_b =  ( $nilai_b * $nor_b_kali ) + $nor_b_tambah ;
				$normal_c =  ( $nilai_c * $nor_c_kali ) + $nor_c_tambah ;
				$normal_d =  ( $nilai_d * $nor_d_kali ) + $nor_d_tambah ;
				$normal_e =  ( $nilai_e * $nor_e_kali ) + $nor_e_tambah ;
				
				$normalisasi_a =  $normal_a>=100?"99":$normal_a;  
				if($normalisasi_a>75){
					$jml_a = $jml_a + 1;	
				}
				$normalisasi_b =  $normal_b>=100?"99":$normal_b;
				if($normalisasi_b>75){
					$jml_b = $jml_b + 1;	
				}
				$normalisasi_c =  $normal_c>=100?"99":$normal_c;
				if($normalisasi_c>75){
					$jml_c = $jml_c + 1;	
				}
				$normalisasi_d =  $normal_d>=100?"99":$normal_d;
				if($normalisasi_d>75){
					$jml_d = $jml_d + 1;	
				}
				$normalisasi_e =  $normal_e>=100?"99":$normal_e;
				if($normalisasi_e>75){
					$jml_e = $jml_e + 1;	
				}
				
				$jumlah_skor_mu = $normalisasi_a + $normalisasi_b + $normalisasi_c + $normalisasi_d + $normalisasi_e ;
				  
				$total_nilais = ($jumlah_skor_mu/$mu*0.35);
				$total_nilai = round($total_nilais,0, PHP_ROUND_HALF_UP);
				$total_skors = (($jumlah_skor_mu/$mu)*0.35) + $bobot_absen + $bobot_studi_kasus + $bobot_mentoring ; 
				$total_skor  = round($total_skors,0, PHP_ROUND_HALF_UP);
				
				$total_skor_akhirs = $total_skor>=100?"99":$total_skor;
				$total_skor_akhir = round($total_skor_akhirs,0, PHP_ROUND_HALF_UP);
				
				if($total_skor_akhir>75){
					$jml_lulus = $jml_lulus + 1;	
				}
				
				$datas['peserta'][$n] = array(
					'id_member'			=> $r->id_member,
					'nama'				=> $r->nama,
					'no_reg_iapi'		=> $r->no_reg_iapi,
					'nilai_a'			=> $nilai_a,
					'nilai_b'			=> $nilai_b,
					'nilai_c'			=> $nilai_c,
					'nilai_d'			=> $nilai_d,
					'nilai_e'			=> $nilai_e,
					'normalisasi_a'		=> $normalisasi_a,
					'normalisasi_b'		=> $normalisasi_b,
					'normalisasi_c'		=> $normalisasi_c,
					'normalisasi_d'		=> $normalisasi_d,
					'normalisasi_e'		=> $normalisasi_e,
					'total_nilai'		=> $total_nilai,
					'total_skor'		=> $total_skor,
					'total_skor_akhir'	=> $total_skor_akhir
					
				);
				
				$n++;
			}
			$datas['jml_a'] 	= $jml_a;
			$datas['jml_b'] 	= $jml_b;
			$datas['jml_c'] 	= $jml_c;
			$datas['jml_d'] 	= $jml_d;
			$datas['jml_e'] 	= $jml_e;
			$datas['jml_lulus']	= $jml_lulus;
			//debugCode($_POST['btn_publish']);
				
		$data['url'] = base_url()."report/grader_prp/index";
		$data['jml'] = $this->data_table[total];
		
		$this->_v($this->folder_view.$this->prefix_view,$datas);
	}
	
	function publish($id_kelas=""){
		//debugCode($id_kelas);	
		
		$chk_item_acc = $_POST['chk_item_acc'];
		foreach($chk_item_acc as $r ){
		//debugCode($r);
				$this->db->update("mt_doc_prp",array(
					'lulus'	=> 1
				),array(
					"id_member"	=> $r,
					"kelas"		=> $id_kelas
				));		
					
				
		}
		
		redirect(base_url()."member/prp_member_data?id=".$id_kelas."&msg=".urlencode('File Berhasil diupdate'));
		
	}
	function export_data(){
		$data = array();
		
		$par_filter = array(
				"status"			=> isset($_GET['status'])?$_GET['status']:"",
				"kategori"			=> isset($_GET['kategori'])?$_GET['kategori']:"",
				"iuran"				=> isset($_GET['iuran'])?$_GET['iuran']:"",
				"no_reg_iapi"		=> isset($_GET['no_reg_iapi'])?$_GET['no_reg_iapi']:"",
				"no_reg_negara"		=> isset($_GET['no_reg_negara'])?$_GET['no_reg_negara']:""
			);

		$this->data_table = $this->M->data_article($par_filter);
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));
			
	  	$data['jml'] = $this->data_table[total];
		$this->_v($this->folder_view.$this->prefix_view."_export",$data,FALSE);
	}


	function upload($kelas=""){
		
		//debugCode($kelas);
		if($_FILES['file_xls']['error'] != 4){
			$this->upl(array(
				"input"	=> "file_xls",
				"kelas"	=> $kelas
			));
			redirect($this->own_link."?id=".$kelas."msg=".urlencode('File Berhasil diupload'));
		}else{
			redirect($this->own_link."?id=".$kelas."msg=".urlencode('Masukan file yang akan diupload'));
		}
			
	}
		
		
	function upl($par=array()){
		
		//debugCode($par['kelas']);
		$this->upload_path="./assets/collections/files/";	
		if($_FILES[$par['input']]['error']==4)
			return false;
		$uId = uniqid();
		$fileName = $uId;
		$config['upload_path'] = $this->upload_path;
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'xls';
		$config['max_size']		= 1024*20;
		$this->load->library('upload');
		$this->upload->initialize($config);		
		if( $this->upload->do_upload($par['input']) ){
			$file = $uId.$this->upload->file_ext;			
			//read excel and input to table tmp...
			$full_path_file = $this->upload_path.$file;
			
			$this->xls_reader(array(
				"file"	=> $full_path_file,
				"kelas" => $par['kelas']
			));
			
			// delete file...
			if(file_exists($this->upload_path.$file) && !is_dir($this->upload_path.$file)){
				unlink($this->upload_path.$file);
			}
		}
	}
	
	
	
	function xls_reader($par=array()){
		
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		$data->read($par['file']);
		error_reporting(E_ALL ^ E_NOTICE);
		
		//debugCode($data->sheets[0]);
		$this->DATA->table="mt_nilai";
		
		
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			$dta_xls = array(
				'id_member'			=> $data->sheets[0]['cells'][$i][1],
				'nilai'				=> $data->sheets[0]['cells'][$i][2],
				'mata_ujian'		=> $data->sheets[0]['cells'][$i][3],
				'status'			=> $data->sheets[0]['cells'][$i][4]==NULL?'':$data->sheets[0]['cells'][$i][4],	
				'kelas'				=> $par['kelas']							
			);
			
			//debugCode($dta_xls);
			$a = $this->_save_master( 
			$dta_xls,
			array(
				'id' => dbClean($_POST['id'])
			),
			dbClean($_POST['id'])			
		);


			//debugCode($dta_xls);			
			
						
			
		}
	}

	
	


	
	

}
