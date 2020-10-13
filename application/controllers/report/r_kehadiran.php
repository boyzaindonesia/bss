<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/excel/reader.php");
class R_kehadiran extends AdminController {  
	function __construct()    
	{
		parent::__construct();    
		$this->_set_action();
		$this->_set_action(array("edit","delete"),"ITEM");
		$this->_set_title( 'List Kehadiran' );
		$this->DATA->table="mt_transaction";
		$this->folder_view = "report/";
		$this->prefix_view = strtolower($this->_getClass());
		$this->load->model("mdl_kelas","M");
		$this->breadcrumb[] = array(
				"title"		=> "Kehadiran",
				"url"		=> $this->own_link
			);
			
		
	}

	

	
	
	function index(){

		
		$this->breadcrumb[] = array(
				"title"		=> "List Kehadiran"
			);
		
		//debugCode($_POST);
		if(!empty($_POST)){
		//debugCode($_POST);
		$par_filter = array(
				"nama_ppl"			=> isset($_POST['nama_ppl'])?$_POST['nama_ppl']:"",
				"topik"				=> isset($_POST['topik'])?$_POST['topik']:"",
				"tanggal"			=> isset($_POST['tanggal'])?$_POST['tanggal']:"",
				"kota"				=> isset($_POST['kota'])?$_POST['kota']:"",
				"pembayaran"		=> isset($_POST['pembayaran'])?$_POST['pembayaran']:"",
				"hadir"		=> isset($_POST['hadir'])?$_POST['hadir']:"",
			);
			
		//debugCode($par_filter);
			
		$this->data_table = $this->M->data_member($par_filter);
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));
		
		//debugCode($data);	
		$data['url'] = base_url()."report/r_kehadiran/index";
		$data['jml'] = $this->data_table[total];
		
		}
		
		$this->_v($this->folder_view.$this->prefix_view."",$data);
	}
	
	
	function export_data(){
		$data = array();
		
		$par_filter = array(
				"nama_ppl"			=> isset($_GET['nama_ppl'])?$_GET['nama_ppl']:"",
				"topik"				=> isset($_GET['topik'])?$_GET['topik']:"",
				"tanggal"			=> isset($_GET['tanggal'])?$_GET['tanggal']:"",
				"kota"				=> isset($_GET['kota'])?$_GET['kota']:"",
				"pembayaran"		=> isset($_GET['pembayaran'])?$_GET['pembayaran']:"",
				"hadir"				=> isset($_GET['hadir'])?$_GET['hadir']:""
			);
	
		
	
		$this->data_table = $this->M->data_member($par_filter);
		$data = $this->_data(array(
				"base_url"	=> $this->own_link.'/index'
			));
			
		$data['jml'] = $this->data_table[total];	
			
		$this->_v($this->folder_view.$this->prefix_view."_export",$data,FALSE);
	}
	
	function upload_nilai(){
		if($_FILES['file_xls']['error'] != 4){
		//debugCode($_FILES);
			$this->upl(array(
				"input"	=> "file_xls"
			));
			redirect($this->own_link."?msg=".urlencode('File Berhasil diupload'));
		}else{
			redirect($this->own_link."?msg=".urlencode('Masukan file yang akan diupload'));
		}
			
	}
		
		
	function upl($par=array()){
		
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
				"file"	=> $full_path_file
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
		
		//debugCode($data->sheets[0]['numRows']);
		
		for ($i = 8; $i <= $data->sheets[0]['numRows']; $i++) {
			
			$this->db->update("mt_transaction",array(
				"detail_status"			=> $data->sheets[0]['cells'][$i][5],
				"detail_realisasi_skp" 	=> $data->sheets[0]['cells'][$i][6],
				"absen1"			 	=> $data->sheets[0]['cells'][$i][7],
				"absen2"			 	=> $data->sheets[0]['cells'][$i][8],
				"absen3"			 	=> $data->sheets[0]['cells'][$i][9]
				),array(
					"id"=>$data->sheets[0]['cells'][$i][2]
				));
						
			
		}
	}
	

}
