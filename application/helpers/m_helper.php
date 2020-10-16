<?php
function checkIsRoute($title){
	$arr = array('default_controller','admin','cms','404_override','front','auth','chat','data','site','news','load_more_news','about','story','developer','project','contact','send_message','term-of-use','privacy-policy','gallery','load_more_gallery','gallery-images','cart-store','checkout','membership','confirmation','messages','search','products','p','fb-login','google-login','twitter-login','authorize','login','logout','register','account-verification','reset-password','create-new-password','account','profile','change-email','change-password','change-photo-profile','check-form-member','account-send-verification','address','shop','cart','checkout','store','report');
    if (in_array($title, $arr)) {
    	return TRUE;
    } else {
    	return FALSE;
    }
}

function config_email(){
	$result = array();
	$result['protocol']  = 'smtp';
	$result['smtp_host'] = 'mail.butiksasha.com';
	$result['smtp_port'] = 25;
	$result['smtp_user'] = 'noreply@butiksasha.com';
	$result['smtp_pass'] = 'JE9XFM]4_xs=';
	$result['smtp_fullname'] = 'Butik Sasha';
	$result['mailtype']  = 'html';
	$result['charset']   = 'iso-8859-1';
	return $result;
}

function check_isnot($val=''){
	return ($val!=''?(substr($val,0,2)!='!='?' = ':'').$val:'');
}

function cleanSpace($string=""){
	$str = trim(preg_replace('/[\s]+/',' ',$string));
	return $str;
}

function DOMinnerHTML(DOMNode $element){
    $innerHTML = "";
    $children  = $element->childNodes;
    foreach ($children as $child){
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }
    return $innerHTML;
}

function get_name_app($alias=""){
	$CI = getCI();
	$m = $CI->db->get("mt_configuration")->row();
	return $m->$alias;
}

function truncate($table=""){
	$CI = getCI();
	$m = $CI->db->truncate($table);
}

function writeLog($par=array()){
	$CI = getCI();
	$data = array();
	$data['log_date'] 		= timestamp();
	$data['log_class'] 		= $par['log_class'];
	$data['log_function'] 	= $par['log_function'];
	$data['log_user_type']	= $par['log_user_type'];
	$data['log_user_name']	= $par['log_user_name'];
	$data['log_user_id'] 	= $par['log_user_id'];
	$data['log_role'] 		= $par['log_role'];
	$data['log_ip'] 		= $_SERVER['REMOTE_ADDR'];
	$data['log_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$data['log_url'] 		= $par['log_url'];
	$data['log_var_get'] 	= $par['log_var_get'];
	$data['log_var_post']	= $par['log_var_post'];
	$data['log_type']		= $par['log_type'];
	$data['log_detail_id']	= $par['log_detail_id'];
	$data['log_detail_item']= $par['log_detail_item'];
	$data['log_detail_qty'] = $par['log_detail_qty'];
	$data['log_title_id']   = $par['log_title_id'];
	$data['log_title']      = $par['log_title'];
	$data['log_desc']   	= $par['log_desc'];
	$data['log_status']   	= ($par['log_status']!=""?$par['log_status']:0);
	$data['app_id'] 		= $par['app_id'];
	$CI->DATA->table = "mt_app_log";
	$a = $CI->_save_master(
		$data,
		array(
			'log_id' => ''
		)
	);
}
function log_user_type($id=''){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Admin');
	$m[] = array('id' => '2', 'name' => 'Member');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name']);
				// log_user_type('1')['name'];
			}
		}
	}
	return $result;
}
function log_type($id=''){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'System');
	$m[] = array('id' => '2', 'name' => 'Product');
	$m[] = array('id' => '3', 'name' => 'Apps');
	$m[] = array('id' => '4', 'name' => 'Orders');
	$m[] = array('id' => '5', 'name' => 'Pemesanan Reseller');
	$m[] = array('id' => '6', 'name' => 'Scraper');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name']);
				// log_type('1')['name'];
			}
		}
	}
	return $result;
}
function log_title($id=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Produk Baru');
	$m[] = array('id' => '2', 'name' => 'Notif Item Stok Otomatis');
	$m[] = array('id' => '3', 'name' => 'Perubahan Stok');
	$m[] = array('id' => '4', 'name' => 'Produk Soldout Otomatis');
	$m[] = array('id' => '5', 'name' => 'Reset Stok');
	$m[] = array('id' => '6', 'name' => 'Produk Dijual Kembali');
	$m[] = array('id' => '7', 'name' => 'Produk diset Tidak Dijual');
	$m[] = array('id' => '8', 'name' => 'Perubahan Harga Reseller');
	$m[] = array('id' => '9', 'name' => 'Produk Dihapus');
	$m[] = array('id' => '10', 'name' => 'Penambahan Stok Otomatis');
	$m[] = array('id' => '11', 'name' => 'Pengurangan Stok Otomatis');
	$m[] = array('id' => '12', 'name' => 'Produk Dihapus Permanen');
	$m[] = array('id' => '13', 'name' => 'Empty Trash');
	$m[] = array('id' => '14', 'name' => 'Penambahan Stok Manual');
	$m[] = array('id' => '15', 'name' => 'Pengurangan Stok Manual');
	$m[] = array('id' => '16', 'name' => 'Produk Diarsipkan');
	$m[] = array('id' => '30', 'name' => 'Berhasil Checkout');
	$m[] = array('id' => '31', 'name' => 'Gagal Simpan Saldo Pemesanan Reseller');
	$m[] = array('id' => '32', 'name' => 'Gagal Update Saldo Pemesanan Reseller');
	$m[] = array('id' => '33', 'name' => 'Berhasil Booking');
	$m[] = array('id' => '34', 'name' => 'Berhasil Melakukan Pembayaran');
	$m[] = array('id' => '35', 'name' => 'Gagal Simpan Saldo Pembayaran');
	$m[] = array('id' => '36', 'name' => 'Gagal Update Saldo Pembayaran');
	$m[] = array('id' => '60', 'name' => 'Scraper Tokopedia');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name']);
				// getTitleLog('1')['name'];
			}
		}
	}
	return $result;
}

function getLang(){
	$CI = getCI();
	if(isset($_GET['lang'])){
		$lang_id 	 = '';
		$lang_status = false;
		switch ($_GET['lang']) {
			case 'en': $lang_id = 'en'; $lang_status = true; break;
			case 'ind': $lang_id = 'ind'; $lang_status = true; break;
			default: break;
		}
		if($lang_status){
			$CI->jCfg['lang'] = $lang_id;
			$CI->_releaseSession();
		}
	} else {
		if(!isset($CI->jCfg['lang'])){ $CI->jCfg['lang'] = 'en'; $CI->_releaseSession(); }
	}
}

function genOddEven($i=1,$a=2) {
	if ($i % $a == 0){
		return true; //odd
	} else {
		return false;
	}
}

function convDatetoString($vardate) {
		return strtotime($vardate);
	}
function convDateTable($vardate) {
	if($vardate!=''){
		$tahun = substr($vardate, 0, 4);
		$tahun2 = substr($vardate, 2, 2);
		$bulan = substr($vardate, 5, 2);
		$tgl   = substr($vardate, 8, 2);

		$jam   = substr($vardate, 11, 2);
		$menit   = substr($vardate, 14, 2);
		$detik   = substr($vardate, 17, 2);
		$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
		return $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun ;
	} else {
		return '-';
	}
}
function convDateTimeTable($vardate) {
	if($vardate!=''){
		$tahun = substr($vardate, 0, 4);
		$tahun2 = substr($vardate, 2, 2);
		$bulan = substr($vardate, 5, 2);
		$tgl   = substr($vardate, 8, 2);

		$jam   = substr($vardate, 11, 2);
		$menit   = substr($vardate, 14, 2);
		$detik   = substr($vardate, 17, 2);
		$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
		return $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun .", ". $jam .":". $menit ;
	} else {
		return '-';
	}
}
function convTime($vardate) {
	if($vardate!=''){
		$jam   = substr($vardate, 11, 2);
		$menit   = substr($vardate, 14, 2);
		$detik   = substr($vardate, 17, 2);
		return $jam .":". $menit ;
	} else {
		return '-';
	}
}
function convDateTimeEng($vardate) {
	if($vardate != ''){ return date("D M j, H:i", strtotime($vardate) ); } else { return '-'; }
}
function convDateTimeFullEng($vardate) {
	if($vardate != ''){ return date("l d F Y, H:i", strtotime($vardate) ); } else { return '-'; }
}
function convDateEng($vardate) {
	if($vardate != ''){ return date("l, F d, Y", strtotime($vardate) ); } else { return '-'; }
}
function convDateNewEng($vardate) {
	if($vardate != ''){ return date("d F Y", strtotime($vardate) ); } else { return '-'; }
}
function convDate($vardate) {
	$tahun = substr($vardate, 0, 4);
	$tahun2 = substr($vardate, 2, 2);
	$bulan = substr($vardate, 5, 2);
	$tgl   = substr($vardate, 8, 2);
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	return $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
}
function convDate2($vardate) {
	$tahun = substr($vardate, 0, 4);
	$tahun2 = substr($vardate, 2, 2);
	$bulan = substr($vardate, 5, 2);
	$tgl   = substr($vardate, 8, 2);
	$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
	return $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
}
function convDateINtoEN($vardate="4/28/2018") {
	$date = str_replace('/', '-', $vardate);
	return date('Y-m-d', strtotime($date));
}
function convDateINtoEN2($vardate="07 Oct 2018") {
	// $date = str_replace('/', '-', $vardate);
	return date('Y-m-d', strtotime($vardate));
}

function convDay($vardate="") {
	$tgl   = substr($vardate, 8, 2);
	return $tgl;
}
function convMonth($vardate="") {
	$bulan = substr($vardate, 5, 2);
	$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
	return $BulanIndo[(int)$bulan-1];
}
function convYears($vardate="") {
	$tahun = substr($vardate, 0, 4);
	return $tahun;
}
function convYearsMonth($vardate="") {
	$bulan = substr($vardate, 5, 2);
	$tahun = substr($vardate, 0, 4);
	$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
	return $BulanIndo[(int)$bulan-1].' '.$tahun;
}
function convTwoDate($date_start="",$date_end="") {
	$date_start = date("Y-m-d", strtotime($date_start));
	$date_end   = date("Y-m-d", strtotime($date_end));
	$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
	if($date_start == $date_end){
		return convDateTable($date_start);
	} else if( (getMonth($date_start) == getMonth($date_end)) && (getYear($date_start) == getYear($date_end)) ){
		return getDay($date_start).' - '.getDay($date_end).' '.$BulanIndo[(int)getMonth($date_start)-1].' '.getYear($date_start);
	} else if( (getMonth($date_start) != getMonth($date_end)) && (getYear($date_start) == getYear($date_end)) ) {
		return getDay($date_start).' '.$BulanIndo[(int)getMonth($date_start)-1].' - '.getDay($date_end).' '.$BulanIndo[(int)getMonth($date_end)-1].' '.getYear($date_start);
	} else if( (getYear($date_start) != getYear($date_end)) ) {
		return getDay($date_start).' '.$BulanIndo[(int)getMonth($date_start)-1].' '.getYear($date_start).' - '.getDay($date_end).' '.$BulanIndo[(int)getMonth($date_end)-1].' '.getYear($date_end);
	} else {
		return "";
	}
}
function convDateFilename($vardate="") {
	$tahun = substr($vardate, 0, 4);
	$bulan = substr($vardate, 5, 2);
	$tgl   = substr($vardate, 8, 2);
	$jam   = substr($vardate, 11, 2);
	$menit = substr($vardate, 14, 2);
	$detik = substr($vardate, 17, 2);
	return $tahun."_".$bulan."_".$tgl.'_'.$jam."_".$menit."_".$detik;
}

function timestamp(){
	return gmdate("Y-m-d H:i:s", time()+60*60*7); //GMT +7;
}

function xTimeAgo ($oldTime, $newTime, $timeType) { //Berfungsi untuk menghitung diff /selisih 2 datetime dan menjadikannya ke format menit,jam atau hari
    $timeCalc = strtotime($newTime) - strtotime($oldTime);
    if ($timeType == "s") {
        $timeCalc .= " seconds ago";
    }
    if ($timeType == "m") {
		if (round($timeCalc/60)==0){
			$timeCalc = "just now";
		} else {
			$timeCalc = round($timeCalc/60) . " minutes ago";
		}
    }
    if ($timeType == "h") {
        $timeCalc = round($timeCalc/60/60) . " hours ago";
    }
    if ($timeType == "d") {
        $timeCalc = round($timeCalc/60/60/24) . " days ago";
    }
    if ($timeType == "k") {
        $timeCalc = "yesterday";
    }
	if ($timeType == "t") {
		$tahun = substr($newTime, 0, 4);
		$tahun2 = substr($newTime, 2, 2);
		$bulan = substr($newTime, 5, 2);
		$tgl   = substr($newTime, 8, 2);

		$jam   = substr($newTime, 11, 2);
		$menit   = substr($newTime, 14, 2);
		$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
		$timeCalc = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun ." ". $jam .":". $menit;
    }
    return $timeCalc;
}
function timeAgo($vardate=""){ //Berfungsi untuk menimbang hasil xTimeAgo, jika jam menit lebih dari 60 maka dihitung jam, jika jam lebih dari 24 maka dihitung hari
	$go = '';
	if($vardate!=''){
		date_default_timezone_set('Asia/Jakarta');
		$skrg	= date("Y-m-d H:i:s");
		$isi	= str_replace("-","",xTimeAgo($skrg,$vardate,"m"));
		$isi2	= str_replace("-","",xTimeAgo($skrg,$vardate,"h"));
		$isi3	= str_replace("-","",xTimeAgo($skrg,$vardate,"k"));
		$isi4	= str_replace("-","",xTimeAgo($skrg,$vardate,"d"));
		$isi5	= xTimeAgo($skrg,$vardate,"t");
		$go = "";
		if($isi2 > 168) {
			$go = $isi5;
		} elseif($isi2 > 48) {
			$go = $isi4;
		} elseif($isi2 > 24) {
			$go = $isi3;
		} elseif($isi > 60) {
			$go = $isi2;
		} elseif($isi < 61) {
			$go = $isi;
		}
	}
	return $go;
}
function getDay($vardate) {
	$tgl = substr($vardate, 8, 2);
	return $tgl;
}
function getMonth($vardate) {
	$bulan = substr($vardate, 5, 2);
	return $bulan;
}
function getYear($vardate) {
	$tahun = substr($vardate, 0, 4);
	return $tahun;
}
function getNameMonth($id) {
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	return $BulanIndo[(int)$id-1];
}

function getMinDay($vardate, $length) {
	$jadi = date('Y-m-d H:i:s', strtotime('-'.$length.' days', strtotime( $vardate )));
	return $jadi;
}
function getMinWeekly($vardate, $length) {
	$jadi = date('Y-m-d H:i:s', strtotime('-'.$length.' week', strtotime( $vardate )));
	return $jadi;
}
function getMinMonth($vardate, $length) {
	$jadi = date('Y-m-d H:i:s', strtotime('-'.$length.' month', strtotime( $vardate )));
	return $jadi;
}
function getAddDay($vardate, $length) {
	$jadi = date('Y-m-d H:i:s', strtotime('+'.$length.' days', strtotime( $vardate )));
	return $jadi;
}
function getAddWeekly($vardate, $length) {
	$jadi = date('Y-m-d H:i:s', strtotime('+'.$length.' week', strtotime( $vardate )));
	return $jadi;
}
function getAddMonth($vardate, $length) {
	$jadi = date('Y-m-d H:i:s', strtotime('+'.$length.' month', strtotime( $vardate )));
	return $jadi;
}

function getYearMonthDate($vardate) {
	$tahun = substr($vardate, 0, 4);
	$bulan = substr($vardate, 5, 2);
	$tgl   = substr($vardate, 8, 2);
	return $tahun."-".$bulan."-".$tgl;
}
function getYearMonth($vardate) {
	$tahun = substr($vardate, 0, 4);
	$bulan = substr($vardate, 5, 2);
	return $tahun."-".$bulan;
}
function getMonthDate($vardate) {
	$tahun = substr($vardate, 0, 4);
	$bulan = substr($vardate, 5, 2);
	$tgl   = substr($vardate, 8, 2);
	return $bulan."-".$tgl;
}
function getMonthDate2($vardate) {
	$bulan = substr($vardate, 5, 2);
	$tgl   = substr($vardate, 8, 2);
	$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
	return $tgl." ".$BulanIndo[(int)$bulan-1];
}
function getDateMonth($vardate) {
	$tahun = substr($vardate, 0, 4);
	$bulan = substr($vardate, 5, 2);
	$tgl   = substr($vardate, 8, 2);
	return $tgl."/".$bulan;
}
function getLastMonth() {
	$m  = mktime(0, 0, 0, date("m")-1, date("d"), date("Y"));
	$jadi  = date("Y-m", $m);
	return $jadi;
}
function getDateGroup($tipe_date='',$date_start='',$order_by='asc'){
	$m  = array();
	if($tipe_date=='days'){ // get_date_group('days','2016-01-01');
		$date_end = getYearMonthDate(timestamp());
		$modify   = 'days';
		$format   = 'Y-m-d';
	}
	if($tipe_date=='monthly'){ // get_date_group('monthly','2016-01');
		$date_end = getYearMonth(timestamp());
		$modify   = 'months';
		$format   = 'Y-m';
	}
	if($tipe_date=='years'){ // get_date_group('years','2016');
		$date_end = getYear(timestamp());
		$modify   = 'years';
		$format   = 'Y';
	}

	$begin = new DateTime( $date_start );
	$end   = new DateTime( $date_end );
	if($order_by == 'desc'){
		for($i = $end; $end >= $begin; $i->modify('-1 '.$modify)){
		    $m[] = $i->format($format);
		}
	} else {
		for($i = $begin; $begin <= $end; $i->modify('+1 '.$modify)){
		    $m[] = $i->format($format);
		}
	}
	return $m;
}

function convRomawi($num){
    $n   = intval($num);
    $res = '';

    $roman_numerals = array(
                'M'  => 1000,
                'CM' => 900,
                'D'  => 500,
                'CD' => 400,
                'C'  => 100,
                'XC' => 90,
                'L'  => 50,
                'XL' => 40,
                'X'  => 10,
                'IX' => 9,
                'V'  => 5,
                'IV' => 4,
                'I'  => 1);

    foreach ($roman_numerals as $roman => $number){
        $matches = intval($n / $number);
        $res .= str_repeat($roman, $matches);
        $n = $n % $number;
    }
	return $res;
}

function convertPhone($angka) {
	$jadi = preg_replace('/[^0-9]/', '', $angka);
	$cek1 = substr($jadi,0,2);
	if($cek1 == '62'){
		$jadi = substr_replace($jadi,'0',0,2);
	}
	$cek2 = substr($jadi,0,1);
	if($cek2 != '0'){
		$jadi = '0'.$jadi;
	}
	return $jadi;
}
function convertRp($angka=0) {
	$jadi = $angka;
	if(is_numeric($angka)){
		$jadi = 'Rp '.number_format($angka,0,',','.');
	}
	return $jadi;
}
function convertRp2($angka=0) {
	$jadi = $angka;
	if(is_numeric($angka)){
		$jadi = number_format($angka,0,',','.');
	}
	return $jadi;
}
function convertRpToInt($angka=0) {
	$jadi = str_replace(".", "", $angka);
	return $jadi;
}
function convertRpToInt2($angka=0) {
	$jadi = preg_replace('/[^0-9]/', '', $angka);
	return $jadi;
}
function convertRpToInt3($angka=0) {
	$angka = explode('.', $angka);
	$jadi = preg_replace('/[^0-9]/', '', $angka[0]);
	return $jadi;
}
function convertGr($angka=0) {
	$jadi = $angka;
	if(is_numeric($angka)){
		$jadi = number_format($angka,0,',','.').' gr';
	}
	return $jadi;
}
function convertGrToKg($angka=0) {
	$jadi = $angka;
	if(is_numeric($angka)){
		$jadi = ($angka / 1000);
	}
	return $jadi;
}
function convertGrToKgCeil($angka) {
	$jadi = $angka;
	if(is_numeric($angka)){
		$jadi = ceil(($angka / 1000));
	}
	return $jadi;
}
function convertGrToKgStr($angka) {
	$jadi = $angka;
	if(is_numeric($angka)){
		$jadi = ($angka / 1000);
	}
	return $jadi;
}
function convertKgToGr($angka) {
	$jadi = $angka;
	// if(is_numeric($angka)){
	// 	$jadi = ceil(($angka / 1000));
	// }
	return $jadi;
}
function calcPercent($value, $total) {
	$jadi = '0';
	if(is_numeric($value) && is_numeric($total)){
		$jadi = ($value / $total) * 100;
		$jadi = number_format($jadi, 0, ',', '');
	}
	return $jadi;
}
function calcPercentDiscount($value, $total) {
	$jadi = '0';
	if(is_numeric($value) && is_numeric($total)){
		$jadi = ($value / $total) * 100;
		$jadi = 100 - number_format($jadi, 0, ',', '');
	}
	return $jadi;
}
function calcRating($rating, $review) {
	$jadi  = '0';
	if(is_numeric($rating) && is_numeric($review)){
		$jadi = ($rating / $review);
		$jadi = number_format($jadi, 1, '.', '');
	}
	return $jadi;
}

function autoClearAppSessions(){
	$CI = getCI();
	$timestamp = timestamp();
	$m1 = $CI->db->get_where("mt_configuration",array(
		"configuration_id"	=> 1
	),1,0)->row();
	$clear_app_sessions_date = $m1->clear_app_sessions_date;

	if($clear_app_sessions_date != ""){
		if(getYearMonthDate($timestamp) == getYearMonthDate($clear_app_sessions_date)){
			$CI->db->truncate("mt_app_sessions");

			$nextDate = getAddDay($timestamp, 7);
			$dataUpdate = array("clear_app_sessions_date" => $nextDate);
			$CI->db->update("mt_configuration",$dataUpdate,array("configuration_id"=>1));
		}
	}
}

function notifyMessage($class="",$alert="",$msg=""){
	$result = '';
	if($class!=""&&$alert!=""&&$msg!=""){
	$result = '<div class="alert '.$class.' square fade in alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>'.$alert.'</strong> '.$msg.'
            </div>';
	}
    return $result;
}

function ubah_huruf_awal($paragrap) {
	$pisahkalimat = explode(" ", $paragrap);
	$kalimatbaru = array();

	foreach ($pisahkalimat as $kalimat){
		$kalimatawalhurufbesar=ucfirst(strtolower($kalimat));
		$kalimatbaru[] = $kalimatawalhurufbesar;
	}

	$textgood = implode(" ", $kalimatbaru);
	return $textgood;
}
function getFirstStr($string){
    $string = explode(" ", $string);
    $string = $string[0];
    return $string;
}
function getFirstParaNumb($string,$numb="70"){
	$string = html_entity_decode(strip_tags(stripslashes($string)));
	if(strlen($string) > $numb){
		$string = substr( $string, 0,$numb).' ...';
	}
    return $string;
}
function getFirstParaNumbNoDot($string,$numb="70"){
	$string = html_entity_decode(strip_tags(stripslashes($string)));
	if(strlen($string) > $numb){
		$string = substr( $string, 0,$numb);
	}
    return $string;
}
function getFirstParaSm($string){
	$string = html_entity_decode(strip_tags(stripslashes($string)));
	if(strlen($string) > 70){
		$string = substr( $string, 0,70).' ...';
	}
    return $string;
}
function getFirstPara($string){
	$string = html_entity_decode(strip_tags(stripslashes($string)));
	if(strlen($string) > 140){
		$string = substr( $string, 0,140).' ...';
	}
    return $string;
}
function getFirstParaLg($string){
	$string = html_entity_decode(strip_tags(stripslashes($string)));
	if(strlen($string) > 140){
		$string = substr( $string, 0,240).' ...';
	}
    return $string;
}

function folder_views_lg(){
	$folder_views = "large/";
    $detect = new Mobile_Detect();
    if ($detect->isTablet()) {
    	$folder_views = "small/";
    } else if ($detect->isMobile() || $detect->isAndroidOS()) {
    	$folder_views = "thumb/";
    }
	return $folder_views;
}
function folder_views_sm(){
	$folder_views = "small/";
    $detect = new Mobile_Detect();
    if ($detect->isTablet()) {
    	$folder_views = "small/";
    } else if ($detect->isMobile() || $detect->isAndroidOS()) {
    	$folder_views = "thumb/";
    }
	return $folder_views;
}

function get_app_user($id=""){
	$CI = getCI();
	$CI->db->where("user_id",$id);
	$m = $CI->db->get("mt_app_user")->row();
	return $m;
}
function get_user_name($id=""){
	$CI = getCI();
	$CI->db->where("is_trash !=",1);
	$CI->db->where("user_id",$id);
	$m = $CI->db->get("mt_app_user")->row();
	$name = "";
	if( count($m) > 0){
		$name = $m->user_fullname;
	}
	return $name;
}
function get_user_group($id=""){
	$CI = getCI();
	$CI->db->where("is_trash !=",1);
	$CI->db->where("user_id",$id);
	$m = $CI->db->get("mt_app_user")->row();
	$name = "";
	if( count($m) > 0){
		$name = $m->user_group;
	}
	return $name;
}

function get_category_parent($id){
	$CI = getCI();
	$temp = "";
	$m1 = $CI->db->get_where("mt_article_category",array(
		"category_parent_id"	=> '0',
		"category_istrash"		=> '0'
	))->result();
	if(!empty($m1)){
		foreach($m1 as $k => $m1){
			if($m1->category_id == $id){
				$s1 = 'selected="selected"';
			}else{
				$s1 = '';
			}
			$temp .= '<option value="'.$m1->category_id.'" '.$s1.'>'.$m1->category_title.'</option>';
			$m2 = $CI->db->get_where("mt_article_category",array(
				"category_parent_id"	=> $m1->category_id
			))->result();
			if(!empty($m2)){
				foreach($m2 as $k => $m2){
					if($m2->category_id == $id){
						$s2 = 'selected="selected"';
					}else{
						$s2 = '';
					}
					$temp .= '<option value="'.$m2->category_id.'" '.$s2.'> &#10149; '.$m2->category_title.'</option>';
					$m3 = $CI->db->get_where("mt_article_category",array(
						"category_parent_id"	=> $m2->category_id
					))->result();
					if(!empty($m3)){
						foreach($m3 as $k => $m3){
							if($m3->category_id == $id){
								$s3 = 'selected="selected"';
							}else{
								$s3 = '';
							}
							$temp .= '<option value="'.$m3->category_id.'" '.$s3.'> &nbsp;&nbsp;&nbsp;&nbsp;&#10149; '.$m3->category_title.'</option>';
							$m4 = $CI->db->get_where("mt_article_category",array(
								"category_parent_id"	=> $m3->category_id
							))->result();
							if(!empty($m4)){
								foreach($m4 as $k => $m4){
									if($m4->category_id == $id){
										$s4 = 'selected="selected"';
									}else{
										$s4 = '';
									}
									$temp .= '<option value="'.$m4->category_id.'" '.$s4.'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10149; '.$m4->category_title.'</option>';
								}
							}
						}
					}
				}
			}
		}
	}

	return $temp;
}

function get_root_category_parent($id){
	$CI = getCI();
	$temp = "";
	$m1 = $CI->db->get_where("mt_article_category",array(
		"category_id"			=> $id,
		"category_istrash"		=> '0'
	))->result();
	if(!empty($m1)){
		foreach($m1 as $k => $m1){
			$temp = $m1->category_title;
			if($m1->category_parent_id != '0'){
				$m2 = $CI->db->get_where("mt_article_category",array(
					"category_id"	=> $m1->category_parent_id
				))->result();
				if(!empty($m2)){
					foreach($m2 as $k => $m2){
						$temp = $m2->category_title.' - '.$m1->category_title;
						if($m2->category_parent_id != '0'){
							$m3 = $CI->db->get_where("mt_article_category",array(
								"category_id"	=> $m2->category_parent_id
							))->result();
							if(!empty($m3)){
								foreach($m3 as $k => $m3){
									$temp = $m3->category_title.' - '.$m2->category_title.' - '.$m1->category_title;
								}
							}
						}
					}
				}
			}
		}
	} else {
		$temp = 'No Category Parent';
	}

	return $temp;
}

function get_product_category_parent($id){
	$CI = getCI();
	$temp = "";
	$m1 = $CI->db->order_by('position','asc')->get_where("mt_product_category",array(
		"product_category_parent_id"	=> '0',
		"product_category_istrash"		=> '0'
	))->result();
	if(!empty($m1)){
		foreach($m1 as $k => $m1){
			if($m1->product_category_id == $id){
				$s1 = 'selected="selected"';
			}else{
				$s1 = '';
			}
			$temp .= '<option value="'.$m1->product_category_id.'" data-code="'.$m1->product_category_code.'" '.$s1.'>'.$m1->product_category_title.'</option>';
			$m2 = $CI->db->order_by('position','asc')->get_where("mt_product_category",array(
				"product_category_parent_id"	=> $m1->product_category_id
			))->result();
			if(!empty($m2)){
				foreach($m2 as $k => $m2){
					if($m2->product_category_id == $id){
						$s2 = 'selected="selected"';
					}else{
						$s2 = '';
					}
					$temp .= '<option value="'.$m2->product_category_id.'" data-code="'.$m2->product_category_code.'" '.$s2.'> &#10149; '.$m2->product_category_title.'</option>';
					$m3 = $CI->db->order_by('position','asc')->get_where("mt_product_category",array(
						"product_category_parent_id"	=> $m2->product_category_id
					))->result();
					if(!empty($m3)){
						foreach($m3 as $k => $m3){
							if($m3->product_category_id == $id){
								$s3 = 'selected="selected"';
							}else{
								$s3 = '';
							}
							$temp .= '<option value="'.$m3->product_category_id.'" data-code="'.$m3->product_category_code.'" '.$s3.'> &nbsp;&nbsp;&nbsp;&nbsp;&#10149; '.$m3->product_category_title.'</option>';
							$m4 = $CI->db->order_by('position','asc')->get_where("mt_product_category",array(
								"product_category_parent_id"	=> $m3->product_category_id
							))->result();
							if(!empty($m4)){
								foreach($m4 as $k => $m4){
									if($m4->product_category_id == $id){
										$s4 = 'selected="selected"';
									}else{
										$s4 = '';
									}
									$temp .= '<option value="'.$m4->product_category_id.'" data-code="'.$m4->product_category_code.'" '.$s4.'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10149; '.$m4->product_category_title.'</option>';
								}
							}
						}
					}
				}
			}
		}
	}

	return $temp;
}

function get_root_product_category_parent($id){
	$CI = getCI();
	$temp = "";
	$m1 = $CI->db->get_where("mt_product_category",array(
		"product_category_id"			=> $id,
		"product_category_istrash"		=> '0'
	))->result();
	if(!empty($m1)){
		foreach($m1 as $k => $m1){
			$temp = $m1->product_category_title;
			if($m1->product_category_parent_id != '0'){
				$m2 = $CI->db->get_where("mt_product_category",array(
					"product_category_id"	=> $m1->product_category_parent_id
				))->result();
				if(!empty($m2)){
					foreach($m2 as $k => $m2){
						$temp = $m2->product_category_title.' - '.$m1->product_category_title;
						if($m2->product_category_parent_id != '0'){
							$m3 = $CI->db->get_where("mt_product_category",array(
								"product_category_id"	=> $m2->product_category_parent_id
							))->result();
							if(!empty($m3)){
								foreach($m3 as $k => $m3){
									$temp = $m3->product_category_title.' - '.$m2->product_category_title.' - '.$m1->product_category_title;
								}
							}
						}
					}
				}
			}
		}
	} else {
		$temp = 'No Category Parent';
	}

	return $temp;
}

function get_id_product_category_parent($id){
	$CI = getCI();
	$temp = '0';
	$m1 = $CI->db->get_where("mt_product_category",array(
		"product_category_id"			=> $id,
		"product_category_istrash"		=> '0'
	))->result();
	if(!empty($m1)){
		foreach($m1 as $k => $m1){
			$temp = $m1->product_category_id;
			if($m1->product_category_parent_id != '0'){
				$m2 = $CI->db->get_where("mt_product_category",array(
					"product_category_id"	=> $m1->product_category_parent_id
				))->result();
				if(!empty($m2)){
					foreach($m2 as $k => $m2){
						$temp = $m2->product_category_id;
						if($m2->product_category_parent_id != '0'){
							$m3 = $CI->db->get_where("mt_product_category",array(
								"product_category_id"	=> $m2->product_category_parent_id
							))->result();
							if(!empty($m3)){
								foreach($m3 as $k => $m3){
									$temp = $m3->product_category_id;
								}
							}
						}
					}
				}
			}
		}
	}

	return $temp;
}
function get_id_product_category_parent_numb($id,$numb){
	$CI = getCI();
	$temp = '';
	$m1 = $CI->db->get_where("mt_product_category",array(
		"product_category_id"			=> $id,
		"product_category_istrash"		=> '0'
	))->result();
	if(!empty($m1)){
		foreach($m1 as $k => $m1){
			$temp .= $m1->product_category_id;
			if($m1->product_category_parent_id != '0'){
				$m2 = $CI->db->get_where("mt_product_category",array(
					"product_category_id"	=> $m1->product_category_parent_id
				))->result();
				if(!empty($m2)){
					foreach($m2 as $k => $m2){
						$temp .= ($m1->product_category_id!=''?',':'').$m2->product_category_id;
						if($m2->product_category_parent_id != '0'){
							$m3 = $CI->db->get_where("mt_product_category",array(
								"product_category_id"	=> $m2->product_category_parent_id
							))->result();
							if(!empty($m3)){
								foreach($m3 as $k => $m3){
									$temp .= ($m2->product_category_id!=''?',':'').$m3->product_category_id;
								}
							}
						}
					}
				}
			}
		}
	}

	$x = explode(',', $temp);
	$count = count($x);
	$result = '';
	if($count==1){
		switch ($numb) {
			case '1': $result = $x[0]; break;
			default: break;
		}
	} else if($count==2){
		switch ($numb) {
			case '1': $result = $x[1]; break;
			case '2': $result = $x[0]; break;
			default: break;
		}
	} else if($count==3){
		switch ($numb) {
			case '1': $result = $x[2]; break;
			case '2': $result = $x[1]; break;
			case '3': $result = $x[0]; break;
			default: break;
		}
	}
	return $result;
}

function get_arr_id_product_category($id){
	$CI = getCI();
	$temp = $id;
	$m2a = $CI->db->get_where("mt_product_category",array(
		"product_category_parent_id"	=> $id,
		"product_category_istrash"		=> '0',
		"product_category_status"		=> '1'
	))->result();
	if(!empty($m2a)){
		foreach($m2a as $k => $m2a){
			$temp .= ','.$m2a->product_category_id;
			$m2b = $CI->db->get_where("mt_product_category",array(
				"product_category_parent_id"	=> $m2a->product_category_id,
				"product_category_istrash"		=> '0',
				"product_category_status"		=> '1'
			))->result();
			if(!empty($m2b)){
				foreach($m2b as $k => $m2b){
					$temp .= ','.$m2b->product_category_id;
					$m2c = $CI->db->get_where("mt_product_category",array(
						"product_category_parent_id"	=> $m2b->product_category_id,
						"product_category_istrash"		=> '0',
						"product_category_status"		=> '1'
					))->result();
					if(!empty($m2c)){
						foreach($m2c as $k => $m2c){
							$temp .= ','.$m2c->product_category_id;
						}
					}
				}
			}
		}
	}
	return $temp;
}

function get_menus_parent($id){
	$CI = getCI();
	$temp = "";
	$m1 = $CI->db->order_by('position','asc')->get_where("mt_menus",array(
		"menus_parent_id"	=> '0',
		"menus_istrash"		=> '0',
		"menus_status !="	=> '0'
	))->result();
	if(!empty($m1)){
		foreach($m1 as $k => $m1){
			if($m1->menus_id == $id){
				$s1 = 'selected="selected"';
			}else{
				$s1 = '';
			}
			$temp .= '<option value="'.$m1->menus_id.'" '.$s1.'>'.$m1->menus_title.'</option>';
			$m2 = $CI->db->get_where("mt_menus",array(
				"menus_parent_id"	=> $m1->menus_id,
				"menus_istrash"		=> '0',
			))->result();
			if(!empty($m2)){
				foreach($m2 as $k => $m2){
					if($m2->menus_id == $id){
						$s2 = 'selected="selected"';
					}else{
						$s2 = '';
					}
					$temp .= '<option value="'.$m2->menus_id.'" '.$s2.'> &#10149; '.$m2->menus_title.'</option>';
					$m3 = $CI->db->get_where("mt_menus",array(
						"menus_parent_id"	=> $m2->menus_id,
						"menus_istrash"		=> '0',
					))->result();
					if(!empty($m3)){
						foreach($m3 as $k => $m3){
							if($m3->menus_id == $id){
								$s3 = 'selected="selected"';
							}else{
								$s3 = '';
							}
							$temp .= '<option value="'.$m3->menus_id.'" '.$s3.'> &nbsp;&nbsp;&nbsp;&nbsp;&#10149; '.$m3->menus_title.'</option>';
							$m4 = $CI->db->get_where("mt_menus",array(
								"menus_parent_id"	=> $m3->menus_id,
								"menus_istrash"		=> '0',
							))->result();
							if(!empty($m4)){
								foreach($m4 as $k => $m4){
									if($m4->menus_id == $id){
										$s4 = 'selected="selected"';
									}else{
										$s4 = '';
									}
									$temp .= '<option value="'.$m4->menus_id.'" '.$s4.'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#10149; '.$m4->menus_title.'</option>';
								}
							}
						}
					}
				}
			}
		}
	}

	echo $temp;
}

function getFormSearchMain(){
	$CI = getCI();
	$CI->load->view($CI->jCfg['theme'].'/form-search-main');
}

function get_data_article(){
	$CI = getCI();
	$m = $CI->db->get_where("mt_article",array(
		"article_istrash"	=> '0',
		"article_status"	=> '1'
	))->result();
	return $m;
}

function get_data_member(){
	$CI = getCI();
	$m = $CI->db->get_where("mt_member",array(
		"member_istrash"	=> '0'
	))->result();
	return $m;
}
function get_data_id_member_status($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_member",array(
		"member_id"	=> $id,
	))->result();
	$return = isset($m[0]->member_status)?$m[0]->member_status:"0";
	return $return;
}
function get_data_member_status(){
	$CI = getCI();
	$m = $CI->db->get_where("mt_member",array(
		"member_id"	=> $CI->user_login,
	))->result();
	$return = isset($m[0]->member_status)?$m[0]->member_status:"0";
	return $return;
}
function get_search_data_member($p=array()){
	$CI = getCI();
	$where = '';
	if(isset($p['member_id']) && $p['member_id'] != ''){
		$where .= "&& member_id ".check_isnot($p['member_id'])." ";
	}
	if(isset($p['member_username']) && $p['member_username'] != ''){
		$where .= "&& member_username ".check_isnot($p['member_username'])." ";
	}
	if(isset($p['member_email']) && $p['member_email'] != ''){
		$where .= "&& member_email ".check_isnot($p['member_email'])." ";
	}
	if(isset($p['member_phone']) && $p['member_phone'] != ''){
		$where .= "&& member_phone ".check_isnot($p['member_phone'])." ";
	}

	$order_by = "member_date desc";
	$sql = "
	 	select
			member_id, member_name, member_username, member_email, member_phone, member_isreseller
		FROM mt_member
		WHERE member_istrash != 1
		".$where."
		ORDER BY ".$order_by."
	";
	$m = $CI->db->query($sql)->result();
	return $m;
}

function get_data_product(){
	$CI = getCI();
	$CI->db->order_by("position", "asc");
	$m = $CI->db->get_where("mt_product",array(
		"product_istrash"	=> '0',
		"product_show_id"	=> '1'
	))->result();
	return $m;
}
function get_detail_product($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_id"	=> $id
	),1,0)->row();
	return $m;
}
function get_product_setup($id="1"){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product_setup",array(
		"product_setup_id" => $id
	),1,0)->row();
	return $m;
}
function get_product_date_istrash($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_id" => $id
	),1,0)->row();
	return $m->product_date_istrash;
}

function set_last_date_product_setup($id="1"){
	$CI = getCI();
	$CI->db->update("mt_product_setup",array("product_setup_last_update"=>timestamp()),array("product_setup_id"=>$id));
}

// function get_data_product_detail($id=""){
// 	$CI = getCI();
// 	$CI->db->order_by("product_detail_id", "asc");
// 	$m = $CI->db->get_where("mt_product_detail",array(
// 		"product_id"		=> $id
// 	))->result();
// 	return $m;
// }

function get_title_article($id){
	$CI = getCI();
	$m = $CI->db->get_where("mt_article",array(
		"article_istrash"	=> '0',
		"article_id"		=> $id
	))->result();

	$titleArticle = isset($m[0]->article_title)?$m[0]->article_title:"No Title";
	return $titleArticle;
}

function get_member_article($id){
	$CI = getCI();
	$m = $CI->db->get_where("mt_member",array(
		"member_istrash"	=> '0',
		"member_id"			=> $id
	))->result();

	$titleArticle = isset($m[0]->nama)?$m[0]->nama:"No Title";
	return $titleArticle;
}

function get_banner_category($id){
	$CI = getCI();
	if($id==''){ $data = array("banner_category_istrash" => 0 ); }
	else { $data = array("banner_category_id" => $id ); }
	$m = $CI->db->order_by('banner_category_id','asc')->get_where("mt_banner_category",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_banner($id=""){
	$CI = getCI();
	if($id==''){ $data = array("banner_istrash" => 0 ); }
	else { $data = array("banner_id" => $id ); }
	$m = $CI->db->order_by('position','asc')->get_where("mt_banner",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_banner_by_category($id=""){
	$CI = getCI();
	$CI->db->order_by('position','asc');
	$m = $CI->db->get_where("mt_banner",array(
		"banner_category_id"	=> $id,
		"banner_istrash"		=> 0
	))->result();
	return $m;
}
function get_banner_homepage_popup(){
	$CI = getCI();
	$CI->db->order_by('position','asc');
	$m = $CI->db->get_where("mt_banner",array(
		"banner_category_id"	=> 1,
		"banner_status"			=> 1,
		"banner_istrash"		=> 0
	))->result();
	return $m;
}
function get_banner_homepage_new_product(){
	$CI = getCI();
	$CI->db->order_by('position','asc');
	$m = $CI->db->get_where("mt_banner",array(
		"banner_category_id"	=> 2,
		"banner_status"			=> 1,
		"banner_istrash"		=> 0
	),4,0)->result();
	return $m;
}
function get_banner_productpage_sideleft(){
	$CI = getCI();
	$CI->db->order_by('Rand()');
	$m = $CI->db->get_where("mt_banner",array(
		"banner_category_id"	=> 4,
		"banner_status"			=> 1,
		"banner_istrash"		=> 0
	),1,0)->result();
	return $m;
}

function get_shop_by_category(){
	$CI = getCI();
	$CI->db->order_by('position','asc');
	$m = $CI->db->get_where("mt_banner",array(
		"banner_category_id"	=> 3,
		"banner_status"			=> 1,
		"banner_istrash"		=> 0
	),3,0)->result();
	return $m;
}


function get_province($id){
	$CI = getCI();
	$m = $CI->db->get_where("mt_app_province",array(
		"province_id"		=> $id
	))->result();

	$titleArticle = isset($m[0]->province_title)?$m[0]->province_title:"No Title";
	return $titleArticle;
}

function get_data_province($prov=""){
	$CI  = getCI();
	$CI->db->order_by('province_title','ASC');
	$q = $CI->db->get_where("mt_app_province",array(
		"province_id"	=> $prov
	))->row();
	return $q;
}

function option_province($prov=""){
	$CI  = getCI();

	$CI->db->order_by('province_title','ASC');
	$q = $CI->db->get("mt_app_province")->result();

	if( count($q) > 0 ){
		foreach ($q as $key => $value) {
			$selected = $value->province_id==$prov?'selected="selected"':'';
			$callback .= '<option value="'.$value->province_id.'" '.$selected.' >'.$value->province_title.'</option>';
		}
	}
	return $callback;
}

function option_province_city($prov="",$city=""){
	$CI  = getCI();

	$CI->db->order_by('city_title','ASC');
	$q = $CI->db->get_where("mt_app_city",array(
		"city_province_id"	=> $prov
	))->result();
	if( count($q) > 0 ){
		foreach ($q as $key => $value) {
			$selected = $value->city_id==$city?'selected="selected"':'';
			$callback .= '<option value="'.$value->city_id.'" '.$selected.' >'.$value->city_title.'</option>';
		}
	}
	return $callback;
}

// function getall_city($city=""){
// 	$CI  = getCI();

// 	$CI->db->order_by('city_title','ASC');
// 	$q = $CI->db->get("mt_app_city")->result();
// 	// if( count($q) > 0 ){
// 	// 	foreach ($q as $key => $value) {
// 	// 		$selected = $value->city_id==$city?'selected="selected"':'';
// 	// 		$callback .= '<option value="'.$value->city_id.'" '.$selected.' >'.$value->city_title.'</option>';
// 	// 	}
// 	// }
// 	return $q;
// }
function getProvSet($id){
	$CI = getCI();
	$province_title = "";
	if(trim($id)!=""){
		$q = $CI->db->get_where("mt_app_province",array(
			"province_id"	=> $id
		),1,0)->row();
		if(count($q) > 0){
			$province_title = $q->province_title;
		}
	}
	return $province_title;
}

function getCitySet($id){
	$CI = getCI();
	$city_title = "";
	if(trim($id)!=""){
		$q = $CI->db->get_where("mt_app_city",array(
			"city_id"	=> $id
		),1,0)->row();
		if(count($q) > 0){
			$city_title = $q->city_title;
		}
	}
	return $city_title;
}

function get_autoComplete_city($id=''){
	$CI = getCI();
	$CI->db->select("mt_app_city.*, mt_app_province.*");
	$CI->db->select("mt_app_city.city_id, mt_app_city.city_title, mt_app_province.province_id, mt_app_province.province_title");
	$CI->db->join("mt_app_province","mt_app_province.province_id = mt_app_city.city_province_id",'left');

	if(trim($id)!=""){ $CI->db->where("mt_app_city.city_id", $id); }
	$CI->db->where("mt_app_city.city_status", 0);
	// $CI->db->limit($limit);
	$CI->db->order_by("mt_app_city.city_title", "asc");
	$m = $CI->db->get("mt_app_city")->result();

	return $m;
}
function option_city($city=""){
	$CI  = getCI();

	$CI->db->order_by('city_title','ASC');
	$q = $CI->db->get("mt_app_city")->result();

	if( count($q) > 0 ){
		foreach ($q as $key => $value) {
			$selected = $value->city_id==$city?'selected="selected"':'';
			$callback .= '<option value="'.$value->city_id.'" '.$selected.' >'.$value->city_title.'</option>';
		}
	}
	return $callback;
}
function option_city_chosen($city=""){
	$CI  = getCI();

	$CI->db->order_by('city_title','ASC');
	$q = $CI->db->get("mt_app_city")->result();

	if( count($q) > 0 ){
		foreach ($q as $key => $value) {
			$arr = array();
            foreach (explode(',', $city) as $n){ $arr[] = $n; }
			$selected = in_array($value->city_id, $arr)?'selected="selected"':'';

			// $selected = $value->city_id==$city?'selected="selected"':'';
			$province = get_data_province($value->city_province_id);
			$callback .= '<option value="'.$value->city_id.'" data-province="'.$value->city_province_id.'" data-province-name="'.$province->province_title.'" data-country="1" data-country-name="Indonesia" '.$selected.' >'.$value->city_title.' - '.$province->province_title.'</option>';
		}
	}
	return $callback;
}

function get_title_product($id){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_istrash"	=> '0',
		"product_id"		=> $id
	),1,0)->row();
	$titleArticle = isset($m->product_name)?$m->product_name:"";
	return $titleArticle;
}
function get_product_name_simple($id){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_istrash"	=> '0',
		"product_id"		=> $id
	),1,0)->row();
	$titleArticle = isset($m->product_name_simple)?$m->product_name_simple:"";
	return $titleArticle;
}
function get_product_code($id){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_istrash"	=> '0',
		"product_id"		=> $id
	),1,0)->row();
	$titleArticle = isset($m->product_code)?$m->product_code:"";
	return $titleArticle;
}

function get_count_views($id){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_istrash"	=> '0',
		"product_id"		=> $id
	),1,0)->result();

	$return = isset($m[0]->product_count)?$m[0]->product_count:"0";
	return $return;
}

function get_count_like($id,$field){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_istrash"	=> '0',
		"product_id"		=> $id
	),1,0)->result();
	$return = isset($m[0]->$field)?$m[0]->$field:"0";
	return $return;
}
function get_member_like($id,$field){
	$CI = getCI();
	$m = $CI->db->get_where("mt_member",array(
		"member_istrash"	=> '0',
		"member_id"			=> $id
	),1,0)->result();
	$return = isset($m[0]->$field)?$m[0]->$field:"";
	return $return;
}
function get_check_like($id,$field){
	$CI = getCI();
	$return = false;
	$CI->user_login = isset($CI->jCfg['member']['member_id'])?$CI->jCfg['member']['member_id']:'';
	if( trim($CI->user_login)!="" ){
		$like = get_member_like($CI->user_login,$field);
		if($like!=''){
			foreach (explode(',', $like) as $n){
				$arr[] = $n;
			}
			$return = (in_array($id, $arr)?true:false);
		}
	}
	return $return;
}

function get_count_product_category($id){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_show_id"		=> '1',
		"product_istrash"		=> '0',
		"product_category_id"	=> $id
	))->result();
	$return = count($m);
	return $return;
}

function get_product_description($id=""){
	$CI = getCI();
	if($id==''){ $data = array("product_id !=" => 0 ); }
	else { $data = array("product_id" => $id ); }
	$m = $CI->db->order_by('product_id','asc')->get_where("mt_product_description",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}

function get_product_satuan($id=""){
	$CI = getCI();
	if($id==''){ $data = array("product_satuan_id !=" => 0 ); }
	else { $data = array("product_satuan_id" => $id ); }
	$m = $CI->db->order_by('product_satuan_id','asc')->get_where("mt_product_satuan",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}

function get_product_status($id=""){
	$CI = getCI();
	if($id==''){ $data = array("product_status_id !=" => 0 ); }
	else { $data = array("product_status_id" => $id ); }
	$m = $CI->db->order_by('product_status_id','asc')->get_where("mt_product_status",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_name_product_status($id=''){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product_status",array(
		"product_status_id"	=> $id
	),1,0)->row();
	$cat_name = isset($m->product_status_name)?$m->product_status_name:'-';
	return $cat_name;
}
function get_count_product_status($id=""){
	$CI = getCI();
	$CI->db->select("mt_product.product_id");
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');
	$CI->db->where("mt_product_detail.product_status_id", $id);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->where("mt_product.product_approved", 1);
	$m = $CI->db->get("mt_product")->result();
	$return = count($m);
	return $return;
}

function get_list_barcode_product($store_id="", $reseller_id="", $status_id=""){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product_detail.*");
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');
	if($store_id != ""){
		$CI->db->where("mt_product.store_id", $store_id);
	}
	if($status_id != ""){
		// $CI->db->where("mt_product.product_show_id", 1);
		$CI->db->where("mt_product_detail.product_status_id", $status_id);
	}
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_approved", 1);
	$CI->db->order_by('mt_product.product_date_push','desc');
	$m = $CI->db->get("mt_product")->result();
	$result = array();
	foreach ($m as $key => $val) {
		$result[$key] = array(
		    'id'                   => $val->product_id,
		    'code'                 => $val->product_code,
		    'name'          	   => $val->product_name_simple,
		    'status'          	   => get_product_status($val->product_status_id)->product_status_name,
		    'images'          	   => get_cover_image_detail($val->product_id),
		    'price_sale'           => $val->product_price_sale,
		    'price_discount'       => $val->product_price_discount,
		    'stock'            	   => $val->product_stock,
		    'stock_copy'           => $val->product_stock_copy
		);

		if($reseller_id != ""){
			$reseller_price = get_reseller_price($reseller_id, $val->product_id);
			$result[$key]['price_sale']  = $reseller_price;
		}

		$arrProductPriceGrosir = NULL;
		if($val->product_price_grosir != ""){
			$arrProductPriceGrosir = array();
			$product_price_grosir = json_decode($val->product_price_grosir);
            foreach ($product_price_grosir as $key2 => $val2) {
				$arrProductPriceGrosir[$key2]['name']  = $val2->name;
				$arrProductPriceGrosir[$key2]['qty']   = $val2->qty;
				$arrProductPriceGrosir[$key2]['price'] = $val2->price;
            }
		}
		$result[$key]['price_grosir'] = $arrProductPriceGrosir;

		$arrProductStockDetail = NULL;
		if($val->product_stock_detail != ""){
			$arrProductStockDetail = array();
			$product_stock_detail = json_decode($val->product_stock_detail);
            foreach ($product_stock_detail as $key2 => $val2) {
				$arrProductStockDetail[$key2]['id']     = $val2->id;
				$arrProductStockDetail[$key2]['name']   = $val2->name;
				$arrProductStockDetail[$key2]['qty']    = $val2->qty;
				$arrProductStockDetail[$key2]['status'] = $val2->status;
            }
		}
		$result[$key]['stock_detail'] = $arrProductStockDetail;
	}

	return $result;
}

function get_product_sold_by_source($product_id="", $source_id=""){
	$CI = getCI();
	$result = array();
	if($source_id == 21){
		$result['id']   = $source_id;
		$result['name'] = "Reseller";
		$result['qty']  = 0;
		$m = $CI->db->get_where("mt_store_orders_detail", array(
			"product_id"	=> $product_id
		))->result();
		foreach ($m as $key => $val) {
			$result['qty'] += $val->orders_detail_qty;
		}
	} else {
		$source = get_orders_source($source_id);
		$result['id']   = $source->orders_source_id;
		$result['name'] = $source->orders_source_name;
		$result['qty']  = 0;

		$CI->db->select("mt_orders_detail.orders_detail_qty");
		$CI->db->join("mt_orders","mt_orders.orders_id = mt_orders_detail.orders_id",'left');

		$CI->db->where("mt_orders_detail.product_id", $product_id);
		$CI->db->where("mt_orders.orders_source_id", $source_id);
		// $CI->db->where("mt_orders.orders_status <=", 8);
		$CI->db->order_by("mt_orders.orders_date", "desc");
		$m = $CI->db->get("mt_orders_detail")->result();
		foreach ($m as $key => $val) {
			$result['qty'] += $val->orders_detail_qty;
		}

		$m2 = $CI->db->get_where("mt_temp_orders", array(
			"orders_source_id"	=> $source_id,
			"orders_booked"		=> 1
		))->result();
		foreach ($m2 as $key2 => $val2) {
			$product_detail_item = json_decode($val2->product_detail_item);
            foreach ($product_detail_item as $key3 => $val3) {
                if($product_id == $val3->id){
					$result['qty'] += $val3->qty;
                }
            }
		}
	}

	return arrayToObject($result);
}

function get_notif_temp_orders($store_id='1',$member_type='1'){
	$CI = getCI();
	$m = $CI->db->order_by('temp_orders_date','desc')->get_where("mt_temp_orders",array(
		"store_id"			=> $store_id,
		"member_type"		=> $member_type
	))->result();
	return $m;
}

function check_is_new_product($vardate=""){
	$result = false;
	if($vardate != ""){
		$timestamp  = timestamp();
		$start_date = new DateTime(getYearMonthDate($vardate));
		$end_date   = new DateTime(getYearMonthDate($timestamp));
		$interval   = $start_date->diff($end_date);
		if($interval->days <= 7){ $result = true; }
	}
	return $result;
}

function get_product_awards($id=""){
	$CI = getCI();
	if($id==''){ $data = array("product_awards_id !=" => 0 ); }
	else { $data = array("product_awards_id" => $id ); }
	$m = $CI->db->order_by('position','asc')->get_where("mt_product_awards",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}

function chk_awards_product($string="",$id=""){
	$return = false;
	if($string!=''){
		foreach (explode(',', $string) as $n){
			if($n == $id){ $return = true; }
		}
	}
    return $return;
}

function get_table_products($store_id="1"){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("mt_product.store_id", $store_id);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->order_by("mt_product.product_date_push", "desc");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}

function get_list_product_awards($id=""){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("FIND_IN_SET(".$id.",mt_product.product_awards) !=", 0);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->order_by("mt_product.product_name", "asc");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}
function get_count_product_awards($id){
	$CI = getCI();
	$CI->db->select("mt_product.*");
	$CI->db->where("FIND_IN_SET(".$id.",product_awards) !=", 0);
	$CI->db->where("product_istrash", 0);
	$CI->db->where("product_show_id", 1);
	$m = $CI->db->get("mt_product")->result();
	$return = count($m);
	return $return;
}

function get_category_name($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_article_category",array(
			"category_id"	=> $id
		))->row();
	$cat_name = isset($m->category_title)?$m->category_title:'-';
	return $cat_name;
}

function get_product_category_name($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product_category",array(
			"product_category_id"	=> $id
		))->row();
	$cat_name = isset($m->product_category_title)?$m->product_category_title:'-';
	return $cat_name;
}

function get_reseller_prices($product_id="",$reseller_prices_id=""){
	$CI = getCI();
	if($reseller_prices_id==''){ $data = array("product_id" => $product_id ); }
	else { $data = array("reseller_prices_id" => $reseller_prices_id ); }
	$m = $CI->db->order_by('store_id','desc')->get_where("mt_reseller_prices",$data);
	if($reseller_prices_id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_reseller_price($store_id="",$product_id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_reseller_prices",array(
			"store_id"		=> $store_id,
			"product_id"	=> $product_id
		),1,0)->row();
	$result = isset($m->price)?$m->price:'0';
	return $result;
}

function sendProductNotif($par=array()){
	$CI = getCI();
	$notif_id = "";
	$m = $CI->db->get_where("mt_product_notif",array(
		"product_id"	=> $par['product_id']
	),1,0)->row();
	if(count($m) > 0){ $notif_id = $m->notif_id; }

	$data = array();
	$data['user_id'] 		= $par['user_id'];
	$data['store_id'] 		= $par['store_id'];
	$data['product_id'] 	= $par['product_id'];
	$data['product_item'] 	= $par['product_item'];
	$data['product_qty'] 	= $par['product_qty'];
	$data['notif_title']	= $par['notif_title'];
	$data['notif_desc']		= $par['notif_desc'];
	$data['notif_status']	= isset($par['notif_status'])?$par['notif_status']:1;
	$data['notif_date'] 	= timestamp();
	$data['notif_notify'] 	= isset($par['notif_notify'])?$par['notif_notify']:3;
	$data['notif_istrash'] 	= 0;
	$CI->DATA->table = "mt_product_notif";
	$a = $CI->_save_master(
		$data,
		array(
			'notif_id' => $notif_id
		),
		$notif_id
	);
}
function get_notif_update_product($status='1',$store_id='1'){
	$CI = getCI();
	$m = $CI->db->order_by('notif_date','desc')->get_where("mt_product_notif",array(
		"store_id"			=> $store_id,
		"notif_status"		=> $status,
		"notif_istrash"		=> '0'
	))->result();
	return $m;
}

function get_product_mp($id=""){
	$CI = getCI();
    $m = $CI->db->get_where("mt_product_mp",array(
        'product_id'    => $id
    ),1,0)->row();
	return $m;
}

function get_member_name($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_member",array(
			"member_id"	=> $id
		))->row();

	$member_name = isset($m->member_name)?$m->member_name:'-';
	return $member_name;
}
function get_member_email($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_member",array(
			"member_id"	=> $id
		))->row();

	$member_email = isset($m->member_email)?$m->member_email:'-';
	return $member_email;
}
function get_member_username($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_member",array(
			"member_id"	=> $id
		))->row();

	$member_username = isset($m->member_username)?$m->member_username:'-';
	return $member_username;
}

function get_menus_name($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_menus",array(
			"menus_id"	=> $id
		))->row();
	$cat_name = isset($m->menus_title)?$m->menus_title:'-';
	return $cat_name;
}

function themeDefaultUrl(){
	$CI =getCI();
	return base_url().APPPATH."views/front/";
}

function front_get_logo(){
	$CI = getCI();
	$m = $CI->db->get_where("mt_configuration")->row();
	$logo = isset($m->configuration_logo)?$m->configuration_logo:'no_image.jpg';
	return $logo;
}

function get_menus_web($id){
	$CI = getCI();
	$CI->db->order_by("menus_order", "asc");
	$cat = $CI->db->get_where("mt_menus",array(
			"menus_parent_id"	=> $id
		))->result();

	$temp = "";
	if(!empty($cat)){
		foreach($cat as $key=>$c){
			$temp .= '<li><a href="#"> '.$c->menus_title.'</a>';
			$CI->db->order_by("menus_order", "asc");
			$sub = $CI->db->get_where("mt_menus",array(
					"menus_parent_id"	=> $c->menus_id
				))->result();
			if(!empty($sub)){
				$temp .= '<ul>';
				foreach($sub as $k=>$s){
					$temp .= '<li><a href="#">'.$s->menus_title.'</a></li>';
				}
				$temp .= '</ul>';
			}
			$temp .= ' </li>';
		}
	}
	echo  $temp;
}

function front_get_menus($id="0"){
	$CI = getCI();
	$CI->db->order_by("menus_order", "asc");
	$cat = $CI->db->get_where("mt_menus",array(
			"menus_parent_id"	=> $id,
			"menus_status"		=> 1,
			"menus_istrash"		=> 0
		))->result();
	return $cat;
}

function front_get_category_menu($id="0"){
	$CI = getCI();
	$CI->db->order_by("position", "asc");
	$cat = $CI->db->get_where("mt_product_category",array(
			"product_category_parent_id !="=> $id,
			"product_category_status"	=> 1,
			"product_category_istrash"	=> 0
		))->result();
	return $cat;
}

function front_home_headline(){
	$CI = getCI();
	$CI->db->order_by("position", "asc");
	$head = $CI->db->get_where("mt_headline",array(
			"headline_istrash"	=> 0,
			"headline_status"	=> 1
		))->result();
	return $head;
}

function front_latest_news($limit=5){
	$CI = getCI();
	$CI->db->order_by("article_publishdate", "desc");
	$head = $CI->db->get_where("mt_article",array(
			"article_category_id"		=> 1,
			"article_istrash"			=> 0,
			"article_status"			=> 1
		),$limit,0)->result();
	return $head;
}

function front_get_article($id=""){
	$CI = getCI();
	$CI->db->order_by("article_publishdate", "desc");
	$head = $CI->db->get_where("mt_article",array(
			"article_category_id"		=> isset($id)?$id:"",
			"article_istrash"			=> 0,
			"article_status"			=> 1
		),5,0)->result();
	return $head;
}

function front_load_article($id="",$rowsPerPage="",$pageShow=""){
	$CI = getCI();
	$CI->db->order_by("article_publishdate", "desc");
	$head = $CI->db->get_where("mt_article",array(
			"article_category_id"	=> $id,
			"article_istrash"	=> 0,
			"article_status"	=> 1
		),$rowsPerPage,$pageShow)->result();
	return $head;
}

function front_load_article_count($id=""){
	$CI = getCI();
	$CI->db->order_by("article_id", "desc");
	$head = $CI->db->get_where("mt_article",array(
			"article_category_id"	=> $id,
			"article_istrash"	=> 0,
			"article_status"	=> 1
		))->result();
	return $head;
}

// function get_related_article($id,$cat){

// 	$CI = getCI();
// 	$CI->db->select("mt_article.article_id,mt_article.article_title,mt_article.article_category_id, mt_article.article_image , mt_article_category.category_id , mt_article_category.category_parent_id");
// 	$CI->db->join("mt_article_category","mt_article_category.category_id = mt_article.article_category_id",'left');

// 	$CI->db->where("mt_article.article_istrash", 0);
// 	$CI->db->where("mt_article.article_status", 1);
// 	$CI->db->where("mt_article.article_category_id != ", $id);
// 	$CI->db->where("mt_article_category.category_parent_id", $cat);
// 	$CI->db->limit(2);
// 	$m = $CI->db->get("mt_article")->result();

// 	return $m;
// }

function front_related_article($id,$cat,$limit){
	$CI = getCI();
	$CI->db->select("*");
	$CI->db->order_by('rand()');
	$CI->db->where("article_istrash", 0);
	$CI->db->where("article_status", 1);
	$CI->db->where("mt_article.article_category_id ", $cat);
	$CI->db->where("mt_article.article_id != ", $id);
	$CI->db->limit($limit);
	$m = $CI->db->get("mt_article")->result();
	return $m;
}

function front_get_category(){
	$CI = getCI();
	$CI->db->order_by("category_id", "desc");
	$head = $CI->db->get_where("mt_article_category",array(
			"category_istrash"	=> 0,
			"category_status"	=> 1
		))->result();
	return $head;
}

function front_testimonial(){
	$CI = getCI();
	$CI->db->order_by('Rand()');
	$m = $CI->db->get_where('mt_testimonial',array(
		'testimonial_istrash' => 0,
		'testimonial_status'  => 1
	),10,0)->result();
	return $m;
}

function check_menus_parent($id){
	$CI = getCI();
	$CI->db->order_by("menus_order", "asc");
	$cat = $CI->db->get_where("mt_menus",array(
			"menus_parent_id"	=> $id,
			"menus_istrash"		=> '0',
			"menus_status"		=> '1'
		))->row();
	if(!empty($cat)){
		return "1";
	}else{
		return "0";
	}
}

function get_article_name($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_article",array(
			"article_id"	=> $id
		))->row();
	$art_name = isset($m->article_title)?$m->article_title:'-';
	return $art_name;
}

function get_menus_link($id){
	$CI = getCI();
	$cat = $CI->db->get_where("mt_menus",array(
			"menus_id"	=> $id
		))->row();
	$type = isset($cat->menus_type)?$cat->menus_type:"";
	if($type == "0"){
		return base_url()."category/".$cat->menus_category_id."-".$cat->menus_id."-".url_title($cat->menus_title);
	}
	if($type == "1"){
		return base_url()."detail/".$cat->menus_article_id."-".$cat->menus_id."-".url_title(get_article_name($cat->menus_article_id));
	}
	if($type == "2"){
		return base_url()."contact-us?id=".$id;
	}
	if($type == "3"){
		return "";
	}
	if($type == "4"){
		return base_url()."product-category/".$cat->menus_product_category_id."-".$cat->menus_id."-".url_title($cat->menus_title);
	}
	if($type == "5"){
		return base_url()."shop";
	}
	if($type == "6"){
		return base_url()."galeri/".$id;
	}
}

function get_data_article_front($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_article",array(
			"article_id"		=> $id,
			"article_status"	=> 1,
			"article_istrash"	=> 0,
		))->row();
	return $m;
}

function get_data_product_front($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
			"product_id"		=> $id,
			"product_show_id"	=> 1,
			"product_istrash"	=> 0
		))->row();
	return $m;
}

function get_image_detail($id=""){
	$CI = getCI();
	$m = $CI->db->order_by('position','ASC')->get_where("mt_product_image",array(
			"product_id"	=> $id
		))->result();
	return $m;
}

function get_cover_image_detail($id=""){
	$CI = getCI();
	$result = '';
	$m = $CI->db->order_by('position','ASC')->get_where("mt_product_image",array(
			"product_id"	=> $id
		),1,0)->row();
	if(count($m)>0){
		$result = $m->image_filename;
	}
	return $result;
}

function get_image_detail_archive($id=""){
	$CI = getCI();
	$m = $CI->db->order_by('position','ASC')->get_where("mt_product_archive_image",array(
			"product_id"	=> $id
		))->result();
	return $m;
}
function get_cover_image_detail_archive($id=""){
	$CI = getCI();
	$result = '';
	$m = $CI->db->order_by('position','ASC')->get_where("mt_product_archive_image",array(
			"product_id"	=> $id
		),1,0)->row();
	if(count($m)>0){
		$result = $m->image_filename;
	}
	return $result;
}

function get_configuration(){
	$CI = getCI();
	$m = $CI->db->get("mt_configuration")->row();
	return $m;
}

// function get_realeted_product($id,$cat){
// 	$CI = getCI();
// 	$CI->db->order_by("product_date", "desc");
// 	$m = $CI->db->get_where("mt_product",array(
// 			"product_istrash"		=> 0,
// 			"product_show_id"		=> 1,
// 			"product_category_id"	=> $cat,
// 			"product_id !="			=> $id
// 		),3,0)->result();
// 	return $m;
// }

// function get_product_terkini(){
// 	$CI = getCI();
// 	$CI->db->order_by("product_date", "desc");
// 	$m = $CI->db->get_where("mt_product",array(
// 			"product_istrash"	=> 0,
// 			"product_show_id"	=> 1,
// 		),8,0)->result();
// 	return $m;
// }

function get_product_detail($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product_detail",array(
		"product_id"		=> $id
	),1,0)->row();
	return $m;
}

function get_product_autocomplete($p=array()){
	$CI = getCI();
	$where = '';
	if(isset($p['product_id']) && $p['product_id'] != ''){
		$where .= "&& mt_product.product_id ".check_isnot($p['product_id'])." ";
	}
	if(isset($p['product_category_id']) && $p['product_category_id'] != ''){
		$where .= "&& mt_product.product_category_id ".check_isnot($p['product_category_id'])." ";
	}
	if(isset($p['product_show_id']) && $p['product_show_id'] != ''){
		$where .= "&& mt_product.product_show_id ".check_isnot($p['product_show_id'])." ";
	}
	if(isset($p['product_approved']) && $p['product_approved'] != ''){
		$where .= "&& mt_product.product_approved ".check_isnot($p['product_approved'])." ";
	}
	if(isset($p['product_status_id']) && $p['product_status_id'] != ''){
		$where .= "&& mt_product_detail.product_status_id ".check_isnot($p['product_status_id'])." ";
	}
	if( isset($p['keyword']) && trim($p['keyword'])!=""){
		$where .= " && ( mt_product.product_code like '%".pTxt($p['keyword'])."%'
						 OR mt_product.product_name like '%".pTxt($p['keyword'])."%'
					   )";
	}

	$order_by = "mt_product.product_date_push desc";
	$sql = "
	 	select
			mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*, mt_product_image.*
		FROM mt_product
        LEFT JOIN mt_product_category ON mt_product_category.product_category_id = mt_product.product_category_id
        LEFT JOIN mt_product_detail ON mt_product_detail.product_id = mt_product.product_id
        LEFT JOIN mt_product_image ON mt_product_image.image_id =
        (SELECT mt_product_image.image_id FROM mt_product_image WHERE mt_product_image.product_id = mt_product.product_id ORDER BY mt_product_image.position ASC LIMIT 1)
		WHERE mt_product.product_istrash != 1
		".$where."
		ORDER BY ".$order_by."
	";
	$m = $CI->db->query($sql)->result();
	return $m;
}

function get_product_new($limit="12"){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("mt_product_detail.product_status_id", 1);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->limit($limit);
	$CI->db->order_by("mt_product.product_date", "desc");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}
function get_product_best_selling($limit="12"){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("FIND_IN_SET(1,mt_product.product_awards) !=", 0);
	$CI->db->where("mt_product_detail.product_status_id", 1);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->limit($limit);
	$CI->db->order_by("RAND()");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}
function get_product_related($id="",$limit="20"){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("mt_product.product_category_id", $id);
	$CI->db->where("mt_product_detail.product_status_id", 1);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->limit($limit);
	$CI->db->order_by("RAND()");
	$m = $CI->db->get("mt_product")->result();

	return $m;
}
function get_product_discount($limit="12"){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("mt_product_detail.product_price_discount >", 0);
	$CI->db->where("mt_product_detail.product_status_id", 1);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->limit($limit);
	$CI->db->order_by("RAND()");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}

function get_product_last_push($limit="8",$offset="0"){
	$CI = getCI();
	$CI->db->order_by("product_date_push", "desc");
	$m = $CI->db->get_where("mt_product",array(
			"product_istrash"	=> 0,
			"product_show_id"	=> 1
		),$limit,$offset)->result();
	return $m;
}


function get_product_tags($limit="20",$offset="0"){
	$CI = getCI();
	$CI->db->order_by("product_tags_name", "asc");
	$m = $CI->db->get_where("mt_product_tags",array(
			"product_tags_status"	=> 1,
			"product_tags_istrash"	=> 0
		),$limit,$offset)->result();
	return $m;
}

function get_product_tags_position($limit="20",$offset="0"){
	$CI = getCI();
	$CI->db->order_by("position", "asc");
	$m = $CI->db->get_where("mt_product_tags",array(
			"product_tags_status"	=> 1,
			"product_tags_istrash"	=> 0
		),$limit,$offset)->result();
	return $m;
}
function get_product_tags_random($limit="20",$offset="0"){
	$CI = getCI();
	$CI->db->order_by("RAND()");
	$m = $CI->db->get_where("mt_product_tags",array(
			"product_tags_status"	=> 1,
			"product_tags_istrash"	=> 0
		),$limit,$offset)->result();
	return $m;
}

function get_detail_product_tags($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product_tags",array(
		"product_tags_id"	=> $id
	),1,0)->row();
	return $m;
}

function get_list_product_not_tagged(){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("mt_product.product_tags", "");
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->where("mt_product_detail.product_status_id", 1);
	$CI->db->order_by("mt_product.product_id", "desc");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}
function get_list_product_tagged($id=""){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("FIND_IN_SET(".$id.",mt_product.product_tags) !=", 0);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->order_by("mt_product.product_name", "asc");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}
function get_count_product_tagged($id=""){
	$CI = getCI();
	$CI->db->select("mt_product.*");
	$CI->db->where("FIND_IN_SET(".$id.",product_tags) !=", 0);
	$CI->db->where("product_istrash", 0);
	$CI->db->where("product_show_id", 1);
	$m = $CI->db->get("mt_product")->result();
	$return = count($m);
	return $return;
}

function get_product_brand($id=""){
	$CI = getCI();
	if($id==''){ $data = array("product_brand_istrash" => 0 ); }
	else { $data = array("product_brand_id" => $id ); }
	$m = $CI->db->order_by('product_brand_name','asc')->get_where("mt_product_brand",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}

function get_product_brand_by_limit($limit=12,$offset=0){
	$CI = getCI();
	$result = "";
	$m = $CI->db->order_by('product_brand_name','asc')->get_where("mt_product_brand",array(
		"product_brand_istrash"	=> 0
	),$limit,$offset)->result();
	return $m;
}
function get_product_brand_name($id=""){
	$CI = getCI();
	$result = "";
	$m = $CI->db->get_where("mt_product_brand",array(
		"product_brand_id"	=> $id
	),1,0)->row();
	if(count($m) > 0){
		$result = $m->product_brand_name;
	}
	return $result;
}
function get_list_product_brand($id=""){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*");
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	// $CI->db->where("FIND_IN_SET(".$id.",product_brand_id) !=", 0);
	$CI->db->where("mt_product.product_brand_id", $id);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.product_show_id", 1);
	$CI->db->order_by("mt_product.product_name", "asc");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}
function get_count_product_brand($id=""){
	$CI = getCI();
	$CI->db->select("mt_product.*");
	// $CI->db->where("FIND_IN_SET(".$id.",product_brand_id) !=", 0);
	$CI->db->where("product_brand_id", $id);
	$CI->db->where("product_istrash", 0);
	$CI->db->where("product_show_id", 1);
	$m = $CI->db->get("mt_product")->result();
	$return = count($m);
	return $return;
}

function get_product_review($id=""){
	$CI = getCI();
	if($id==''){ $data = array("product_review_istrash" => 0 ); }
	else { $data = array("product_review_id" => $id ); }
	$m = $CI->db->order_by('product_review_date','desc')->get_where("mt_product_review",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_product_review_by_product($id=""){
	$CI = getCI();
	$m = $CI->db->order_by('product_review_date','desc')->get_where("mt_product_review",array(
		"product_id"			 => $id,
		"product_review_status"	 => 1,
		"product_review_istrash" => 0
	))->result();
	return $m;
}

function get_product_group($id=""){
	$CI = getCI();
	if($id==''){ $data = array("product_group_istrash" => 0 ); }
	else { $data = array("product_group_id" => $id ); }
	$m = $CI->db->order_by('product_group_date','desc')->get_where("mt_product_group",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_product_group_name($id=""){
	$CI = getCI();
	$result = "";
	$m = $CI->db->get_where("mt_product_group",array(
		"product_group_id"	=> $id
	),1,0)->row();
	if(count($m) > 0){
		$result = $m->product_group_name;
	}
	return $result;
}
function get_count_product_group($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_group_id"	=> $id,
		"product_istrash"	=> 0,
		"product_show_id"	=> 1
	))->result();
	$result = count($m);
	return $result;
}
function get_cover_product_group($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_group_id"	=> $id,
		"product_istrash"	=> 0,
		"product_show_id"	=> 1
	),1,0)->row();
	$result = get_cover_image_detail($m->product_id);
	return $result;
}
function get_product_by_group($id="",$status=""){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product_detail.*");
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');
	$CI->db->where("product_group_id", $id);
	// if($store_id != ""){ $CI->db->where("store_id", $store_id); }
	if($status != ""){ $CI->db->where("product_status_id", $status); }
	$CI->db->where("product_istrash", 0);
	$CI->db->where("product_show_id", 1);
	$CI->db->order_by("product_date", "asc");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}
function get_product_no_group($status="", $store_id=""){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product_detail.*");
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');
	$CI->db->where("product_group_id", 0);
	if($store_id != ""){ $CI->db->where("store_id", $store_id); }
	if($status != ""){ $CI->db->where("product_status_id", $status); }
	$CI->db->where("product_istrash", 0);
	$CI->db->where("product_show_id", 1);
	$CI->db->order_by("product_date_push", "desc");
	$m = $CI->db->get("mt_product")->result();
	return $m;
}
function check_product_by_group(){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product_group",array(
		"product_group_istrash"	=> 0
	))->result();
	if(count($m) > 0){
		foreach ($m as $key => $val) {
			$isFound = true;
			if($val->product_group_show == 0){ $isFound = false; }
			$m2 = $CI->db->get_where("mt_product",array(
				"product_group_id"	=> $val->product_group_id
			))->result();
			foreach ($m2 as $key2 => $val2) {
				$m3 = $CI->db->get_where("mt_product_detail",array(
					"product_id"	=> $val2->product_id
				),1,0)->row();
				if($m3->product_status_id == 1){
					$isFound = false;
					if($val->product_group_show == 0){ $isFound = true; }
				}
			}
			if($isFound){
				$product_group_show = 0;
				if($val->product_group_show == 0){ $product_group_show = 1; }
				$CI->db->update("mt_product_group",array("product_group_show"=>$product_group_show),array("product_group_id"=>$val->product_group_id));
			}
		}
	}
}

function check_menus_id($menuId){
	$CI = getCI();
	$menus1 = $CI->db->get_where("mt_menus",array(
		"menus_id"			=> $menuId,
		"menus_istrash"		=> '0',
		"menus_status"		=> '1'
	))->row();
	$p = $menus1->menus_parent_id;
	$menusIdCheck= $menus1->menus_id;
	if($p > 0){

		$menus2 = $CI->db->get_where("mt_menus",array(
			"menus_id"			=> $menus1->menus_parent_id,
			"menus_istrash"		=> '0',
			"menus_status"		=> '1'
		))->row();
		$p = $menus2->menus_parent_id;
		$menusIdCheck= $menus2->menus_id;
	}
	if($p > 0){

		$menus3 = $CI->db->get_where("mt_menus",array(
			"menus_id"			=> $menus2->menus_parent_id,
			"menus_istrash"		=> '0',
			"menus_status"		=> '1'
		))->row();
		$p = $menus3->menus_parent_id;
		$menusIdCheck= $menus3->menus_id;
	}
	if($p > 0){

		$menus4 = $CI->db->get_where("mt_menus",array(
			"menus_id"			=> $menus3->menus_parent_id,
			"menus_istrash"		=> '0',
			"menus_status"		=> '1'
		))->row();
		$p = $menus4->menus_parent_id;
		$menusIdCheck= $menus4->menus_id;
	}
	$m = $CI->db->get_where("mt_menus",array(
		"menus_id"			=> $menusIdCheck,
		"menus_istrash"		=> '0',
		"menus_status"		=> '1'
	))->row();

	return $m->menus_id;
}


function front_load_gallery($id="",$rowsPerPage="",$pageShow=""){
	$CI = getCI();
	$CI->db->order_by("position", "asc");
	$head = $CI->db->get_where("mt_gallery",array(
			"gallery_istrash"	=> 0,
			"gallery_status"	=> 1
		),$rowsPerPage,$pageShow)->result();
	return $head;
}

function front_load_count_gallery($id=""){
	$CI = getCI();
	$CI->db->order_by("position", "asc");
	$head = $CI->db->get_where("mt_gallery",array(
		"gallery_istrash"	=> 0,
		"gallery_status"	=> 1
	))->result();
	return $head;
}

function get_gallery_name($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_gallery",array(
		"gallery_id"	=> $id
	))->row();
	$cat_name = isset($m->gallery_name)?$m->gallery_name:'-';
	return $cat_name;
}

function front_get_gallery_detail($id=""){
	$CI = getCI();
	if(!empty($id)){
		$CI->db->where('gallery_id', $id);
	}
	$CI->db->order_by("gallery_detail_id", "desc");
	$head = $CI->db->get("mt_gallery_detail")->result();
	return $head;
}

function front_get_random_testimonial($id=""){
	$CI = getCI();
	$CI->db->order_by("RAND()");
	$CI->db->select("*");
	$where = "(article_category_id='31' OR
			   article_category_id='32' OR
			   article_category_id='33' OR
			   article_category_id='34' OR
			   article_category_id='35'
			)";
	$CI->db->where($where);
	$CI->db->limit(5);
	$m = $CI->db->get("mt_article")->result();

	return $m;
}

function get_master_color($id=""){
	$CI = getCI();
	$CI->db->order_by("color_name", "asc");
	$q = array(
		"color_istrash"	=> 0
	);
	if($id != ""){
		$q = array(
			"color_id"		=> $id,
			"color_istrash"	=> 0
		);
	}
	$m = $CI->db->get_where("mt_master_color",$q)->result();
	return $m;
}

function get_supplier($id=""){
	$CI = getCI();
	if($id==''){ $data = array("supplier_istrash" => 0 ); }
	else { $data = array("supplier_id" => $id ); }
	$m = $CI->db->order_by('supplier_name','asc')->get_where("mt_supplier",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}

function get_orders($store_id=''){
	$CI = getCI();
	$m = $CI->db->order_by("orders_date", "desc")->get_where("mt_orders",array(
		"store_id"			=> $store_id,
		"orders_istrash"	=> 0
	))->result();
	return $m;
}
function get_detail_orders($store_id='',$id=''){
	$CI = getCI();
	$m = $CI->db->order_by("orders_date", "desc")->get_where("mt_orders",array(
		"store_id"			=> $store_id,
		"orders_status"		=> $id,
		"orders_istrash"	=> 0
	))->result();
	return $m;
}
function get_orders_detail($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("orders_detail_id", "asc")->get_where("mt_orders_detail",array(
		"orders_id"		=> $id
	))->result();
	return $m;
}
function get_detail_orders_payment($id=''){
	$CI = getCI();
	$m = $CI->db->get_where("mt_orders_payment",array(
		"orders_id"		=> $id
	),1,0)->row();
	return $m;
}
function get_detail_orders_shipping($id=''){
	$CI = getCI();
	$m = $CI->db->get_where("mt_orders_shipping",array(
		"orders_id"		=> $id
	),1,0)->row();
	return $m;
}
function get_detail_orders_timestamp($id=''){
	$CI = getCI();
	$m = $CI->db->get_where("mt_orders_timestamp",array(
		"orders_id"		=> $id
	),1,0)->row();
	return $m;
}
function get_orders_archive($store_id=''){
	$CI = getCI();
	$m = $CI->db->order_by("orders_date", "desc")->get_where("mt_orders_archive",array(
		"store_id"			=> $store_id,
		"orders_istrash"	=> 0
	))->result();
	return $m;
}
function get_detail_orders_archive($store_id='',$id=''){
	$CI = getCI();
	$m = $CI->db->order_by("orders_date", "desc")->get_where("mt_orders_archive",array(
		"store_id"			=> $store_id,
		"orders_status"		=> $id,
		"orders_istrash"	=> 0
	))->result();
	return $m;
}
function get_orders_detail_archive($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("orders_detail_id", "asc")->get_where("mt_orders_archive_detail",array(
		"orders_id"		=> $id
	))->result();
	return $m;
}
function get_detail_orders_payment_archive($id=''){
	$CI = getCI();
	$m = $CI->db->get_where("mt_orders_archive_payment",array(
		"orders_id"		=> $id
	),1,0)->row();
	return $m;
}
function get_detail_orders_shipping_archive($id=''){
	$CI = getCI();
	$m = $CI->db->get_where("mt_orders_archive_shipping",array(
		"orders_id"		=> $id
	),1,0)->row();
	return $m;
}
function get_detail_orders_timestamp_archive($id=''){
	$CI = getCI();
	$m = $CI->db->get_where("mt_orders_archive_timestamp",array(
		"orders_id"		=> $id
	),1,0)->row();
	return $m;
}

function insert_orders_timestamp($id='',$status=""){
	$CI = getCI();
	$data = array();
	if($id != "" && $status != ""){
		$m = $CI->db->get_where("mt_orders_timestamp",array(
			"orders_id"		=> $id
		),1,0)->row();
		if(!empty($m)){
			$arr_time  = array();
			$orders_timestamp = json_decode($m->orders_timestamp_desc);
			foreach ($orders_timestamp as $k => $v) {
				if($v->id != $status){
					$arr_time[] = array('id' 	=> $v->id,
										'date' 	=> $v->date
									  );
				}
			}
			$arr_time[] = array('id' 	=> $status,
								'date' 	=> timestamp()
							  );

			$data['orders_timestamp_desc'] = json_encode($arr_time);
			$CI->db->update("mt_orders_timestamp",$data,array("orders_id"=>$id));
		}
	}
}

function recalc_price_orders($orders_id=''){
	$CI = getCI();
	$data = array();
	if($orders_id != ""){
		$r2 = $CI->db->get_where("mt_orders_detail",array(
            "orders_id" => $orders_id
        ))->result();
        if(count($r2) > 0){
            $totalBuy   = 0;
            $totalPrice = 0;
            foreach ($r2 as $k2 => $v2) {
                $totalBuy   = $totalBuy + ($v2->product_price_buy * $v2->orders_detail_qty);
                $totalPrice = $totalPrice + ($v2->orders_detail_price * $v2->orders_detail_qty);
            }

            // $grandTotal = ($totalPrice + $v->orders_price_shipping + $v->orders_price_insurance) - ($v->orders_price_debet_ship + $v->orders_voucher_price);
            $grandTotal = ($totalPrice + $v->orders_price_shipping + $v->orders_price_insurance) - ($v->orders_voucher_price + $v->orders_price_return);

            $orders_price_debet_ship = 0;
            $isPriceDebetCourier = isPriceDebetCourier($v->orders_source_id, $v->orders_courier_id);
            if($isPriceDebetCourier){
                $orders_price_debet_ship  = $v->orders_price_shipping;
                $grandTotal = $totalPrice - ($v->orders_voucher_price + $v->orders_price_return);
            }

            $data2 = array(
                'orders_price_buy_total'   => $totalBuy,
                'orders_price_product'     => $totalPrice,
                'orders_price_grand_total' => $grandTotal,
                'orders_price_debet_ship'  => $orders_price_debet_ship
            );
	        $CI->db->update("mt_orders",$data2,array("orders_id"=>$orders_id));

            $data3 = array(
                'orders_payment_grand_total' => $grandTotal
            );
            $CI->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
        }

	}
}

function get_split_address($source_id="", $address=""){
	$CI = getCI();
	$result 		= array();
	$count 			= 0;
	$district 		= NULL;
	$city 			= NULL;
	$province 		= NULL;
	$postal_code 	= NULL;
	if($source_id != "" && $address != ""){
		if($source_id == 3){ // TOKOPEDIA
			$exp = explode(',', $address);
			$count 			= count($exp);
			if($count > 2){
				$district 		= NULL;
				$city 			= ltrim($exp[$count - 2], " ");
				$tmp_code 		= trim($exp[$count - 1]);
				$province 		= ltrim(preg_replace('/[0-9]/','',$tmp_code), " ");
				$postal_code 	= (int) filter_var($tmp_code, FILTER_SANITIZE_NUMBER_INT);
			}
		} else if($source_id == 2){ // BUKALAPAK
			$exp = explode(',', $address);
			$count 			= count($exp);
			if($count > 2){
				$district 		= ltrim($exp[$count - 3], " ");
				$city 			= ltrim($exp[$count - 2], " ");
				$tmp_code 		= trim($exp[$count - 1]);
				$province 		= ltrim(preg_replace('/[0-9]/','',$tmp_code), " ");
				$postal_code 	= (int) filter_var($tmp_code, FILTER_SANITIZE_NUMBER_INT);
			}
		} else if($source_id == 8){ // SHOPEE
			$exp = explode(',', $address);
			$count 			= count($exp);
			if($count > 2){
				$district 		= ltrim($exp[$count - 4], " ");
				$city 			= ltrim($exp[$count - 5], " ");
				$province 		= ltrim($exp[$count - 3], " ");
				$postal_code 	= trim($exp[$count - 1]);
			}
		} else if($source_id == 11){ // LAZADA
			$exp = explode(',', $address);
			$count 			= count($exp);
			if($count > 1){
				// $district 		= ltrim($exp[$count - 3], " ");
				// $city 			= ltrim($exp[$count - 2], " ");
				$tmp_code 		= trim($exp[$count - 1]);
				$province 		= ltrim(preg_replace('/[0-9]/','',$tmp_code), " ");
				$postal_code 	= (int) filter_var($tmp_code, FILTER_SANITIZE_NUMBER_INT);
			}
		}

	}
	$result['address'] 		= $address;
	$result['count'] 		= $count;
	$result['district'] 	= $district;
	$result['city'] 		= $city;
	$result['province'] 	= $province;
	$result['postal_code'] 	= $postal_code;
	return $result;
}

function isPickup($orders_courier_id=0){
	$result = false;
    $arrCourier = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40');
    if(in_array($orders_courier_id, $arrCourier)){
		$result = true;
	}
	return $result;
}
function isPriceDebetCourier($orders_source_id=0,$orders_courier_id=0){
	$result = false;
	if($orders_source_id == 8){ // SHOPEE
		$arrCourier = array('7','8','9','10','11','12','13','14','15','23','25','27','28','31','32','33','34','35','36','37','38','39','40');
	    if(in_array($orders_courier_id, $arrCourier)){
			$result = true;
		}
	} else if($orders_source_id == 3){ // TOKOPEDIA
		$arrCourier = array('7','8','9','10','11','12','13','14','15','23','26','27','31','32','34','35','36','37');
	    if(in_array($orders_courier_id, $arrCourier)){
			$result = true;
		}
	} else if($orders_source_id == 2){ // BUKALAPAK
		$arrCourier = array('7','8','9','10','11','12','13','14','15','26','27','31','32','34','35','36','37');
	    if(in_array($orders_courier_id, $arrCourier)){
			$result = true;
		}
	} else if($orders_source_id == 11){ // LAZADA
		$arrCourier = array('1','2','3','4','7','8','28','26','27','29','30');
	    if(in_array($orders_courier_id, $arrCourier)){
			$result = true;
		}
	}
	return $result;
}

function update_product_sold($product_id="", $qty=0, $action="plus"){
	$CI = getCI();
	$m = $CI->db->get_where("mt_product",array(
		"product_id"		=> $product_id
	),1,0)->row();
	if(count($m) > 0){
		$product_sold = $m->product_sold;
		if($action == "plus"){
			$product_sold = ($m->product_sold + $qty);
		} else if($action == "minus"){
			$product_sold = ($m->product_sold - $qty);
		}

		$CI->db->update("mt_product",array("product_sold"=>$product_sold),array("product_id"=>$m->product_id));
	}
}

function get_print_orders_shipping_name($id=''){
	$CI = getCI();
	$CI->db->select("mt_orders.orders_id, mt_orders.store_id, mt_orders.orders_date, mt_orders.orders_source_id, mt_orders.orders_source_invoice, mt_orders.orders_courier_id, mt_orders.orders_price_shipping, mt_orders.orders_price_insurance, mt_orders.orders_noted");
	$CI->db->select("mt_orders_shipping.orders_shipping_date, mt_orders_shipping.orders_shipping_method, mt_orders_shipping.orders_shipping_dropship, mt_orders_shipping.orders_ship_name, mt_orders_shipping.orders_ship_phone, mt_orders_shipping.orders_shipping_username, mt_orders_shipping.orders_shipping_name, mt_orders_shipping.orders_shipping_address, mt_orders_shipping.orders_shipping_phone, mt_orders_shipping.orders_shipping_resi, mt_orders_shipping.orders_shipping_weight, mt_orders_shipping.orders_product_category_title");
	$CI->db->join("mt_orders_shipping","mt_orders_shipping.orders_id = mt_orders.orders_id",'left');

	$CI->db->where("mt_orders.orders_id", $id);
	// $CI->db->order_by("mt_orders_shipping.orders_shipping_name", "asc");
	$CI->db->limit(1);
	$m = $CI->db->get("mt_orders")->row();
	return $m;
}
function get_ready_print_orders_shipping_name(){
	$CI = getCI();
	$CI->db->order_by('orders_print', 'asc');
	$m = $CI->db->get_where("mt_orders",array(
		"orders_id !="		=> 0,
		"orders_print"		=> 0
	))->result();
	return $m;
}

function get_print_address($id=""){
	$CI = getCI();
	if($id==''){ $data = array("print_address_istrash" => 0 ); }
	else { $data = array("print_address_id" => $id ); }
	$m = $CI->db->order_by('print_address_date','desc')->get_where("mt_print_address",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_print_address_shipping_name($id=''){
	$CI = getCI();
	if($id==''){ $data = array("print_address_id !=" => 0 ); }
	else { $data = array("print_address_id" => $id ); }
	$m = $CI->db->order_by('orders_shipping_name','asc')->get_where("mt_print_address",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_ready_print_address_shipping_name(){
	$CI = getCI();
	$CI->db->order_by('print_address_status asc, orders_shipping_name asc'); // DOUBLE ORDER BY
	$m = $CI->db->get_where("mt_print_address",array(
		"print_address_id !="		=> 0,
		"print_address_status"		=> 0
	))->result();
	return $m;
}

function get_print_barcode($id=""){
	$CI = getCI();
	if($id==''){ $data = array("print_barcode_istrash" => 0 ); }
	else { $data = array("print_barcode_id" => $id ); }
	$m = $CI->db->order_by('print_barcode_date','desc')->get_where("mt_print_barcode",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_printed_barcode($id=''){
	$CI = getCI();
	$CI->db->select("mt_print_barcode.*, mt_product_category.product_category_title, mt_product.product_code, mt_product.product_name, mt_product.product_name_simple, mt_product_detail.product_price_sale, mt_store.store_code");
	$CI->db->join("mt_product","mt_product.product_id = mt_print_barcode.product_id",'left');
	$CI->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');
	$CI->db->join("mt_store","mt_store.store_id = mt_print_barcode.store_id",'left');

	$CI->db->where("mt_print_barcode.print_barcode_id", $id);
	$CI->db->limit(1);
	$m = $CI->db->get("mt_print_barcode")->row();
	return $m;
}

function get_orders_by_date($tipe_date='',$date_start='',$date_end=''){
	$CI = getCI();
	if($date_start==''){ $date_start = timestamp(); }
	if($tipe_date=='today'){ $CI->db->where("orders_date LIKE","%".getYearMonthDate($date_start)."%"); }
	if($tipe_date=='monthly'){ $CI->db->where("orders_date LIKE","%".getYearMonth($date_start)."%"); }
	if($tipe_date=='lastmonth'){ $CI->db->where("orders_date LIKE","%".getLastMonth($date_start)."%"); }
	if($tipe_date=='periode'){
		$CI->db->where("( orders_date >= '".$date_start." 00:00:00' )");
		$CI->db->where("( orders_date <= '".$date_end." 23:59:59' )");
	}
	$CI->db->where("orders_istrash",'0');
	$m = $CI->db->get("mt_orders")->result();
	return $m;
}
function get_orders_status($id=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Menunggu Pembayaran', 'color' => 'info', 'icon' => 'fa-exclamation-circle uncompleted', 'url' => 'awaiting_payment');
	$m[] = array('id' => '2', 'name' => 'Konfirmasi Pembayaran', 'color' => 'info', 'icon' => 'fa-exclamation-circle uncompleted', 'url' => 'confirm_payment');
	$m[] = array('id' => '3', 'name' => 'Pesanan Baru', 'color' => 'warning', 'icon' => 'fa-credit-card progress', 'url' => 'new_orders');
	$m[] = array('id' => '4', 'name' => 'Konfirmasi Pengiriman', 'color' => 'primary', 'icon' => 'fa-location-arrow primary', 'url' => 'confirm_shipping');
	$m[] = array('id' => '5', 'name' => 'Status Pengiriman', 'color' => 'info', 'icon' => 'fa-truck info', 'url' => 'shipping');

	$m[] = array('id' => '8', 'name' => 'Order Completed', 'color' => 'success', 'icon' => 'fa-check-circle-o completed', 'url' => 'orders_completed');
	$m[] = array('id' => '9', 'name' => 'Order Dibatalkan', 'color' => 'danger', 'icon' => 'fa-sign-out uncompleted', 'url' => 'canceled_orders');
	$m[] = array('id' => '10', 'name' => 'Pembayaran Gagal', 'color' => 'danger', 'icon' => 'fa-warning uncompleted', 'url' => 'failed_payment');
	$m[] = array('id' => '11', 'name' => 'Return', 'color' => 'danger', 'icon' => 'fa-warning uncompleted', 'url' => 'return_orders');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name'], 'color' => $val['color'], 'icon' => $val['icon'], 'url' => $val['url']);
				// get_orders_status('1')['name'];
			}
		}
	}
	return $result;
}
function get_orders_claim($id=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Konfirmasi Claim', 'color' => 'danger', 'icon' => 'fa-exclamation-circle uncompleted', 'url' => '');
	$m[] = array('id' => '2', 'name' => 'Proses Claim', 'color' => 'info', 'icon' => 'fa-location-arrow primary', 'url' => '');
	$m[] = array('id' => '3', 'name' => 'Claim Selesai', 'color' => 'success', 'icon' => 'fa-check-circle-o completed', 'url' => '');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name'], 'color' => $val['color'], 'icon' => $val['icon'], 'url' => $val['url']);
				// get_orders_claim('1')['name'];
			}
		}
	}
	return $result;
}
function get_count_orders_claim($store_id='',$status_id=""){
	$CI = getCI();
	$arrStatus = array();
	$exp = explode(',', $status_id);
    foreach ($exp as $n) {
        $arrStatus[] = $n;
    }

	$CI->db->select("mt_orders.*");

    $CI->db->where_in("orders_claim_status", $arrStatus);
	$CI->db->where("store_id", $store_id);
	$CI->db->where("orders_istrash", 0);
	$CI->db->order_by("orders_date", "desc");
	$m = $CI->db->get("mt_orders")->result();
	return $m;
}

function get_orders_by_source($store_id='',$source_id=''){
	$CI = getCI();
	$arrSource = array();
	$exp = explode(',', $source_id);
    foreach ($exp as $n) {
        $arrSource[] = $n;
    }

	$CI->db->select("mt_orders.*");

    $CI->db->where_in("orders_source_id", $arrSource);
	$CI->db->where("store_id", $store_id);
	$CI->db->where("orders_istrash", 0);
	$CI->db->order_by("orders_date", "desc");
	$m = $CI->db->get("mt_orders")->result();
	return $m;
}
function get_detail_orders_by_source($store_id='',$status_id='',$source_id=''){
	$CI = getCI();
	$arrSource = array();
	$exp = explode(',', $source_id);
    foreach ($exp as $n) {
        $arrSource[] = $n;
    }

	$CI->db->select("mt_orders.*");

    $CI->db->where_in("orders_source_id", $arrSource);
	$CI->db->where("store_id", $store_id);
	$CI->db->where("orders_status", $status_id);
	$CI->db->where("orders_istrash", 0);
	$CI->db->order_by("orders_date", "desc");
	$m = $CI->db->get("mt_orders")->result();
	return $m;
}

function get_orders_product_detail($store_id='',$status_id=''){
	$CI = getCI();
	$result = array();
	$CI->db->select("mt_orders.*, mt_orders_shipping.*");
	$CI->db->join("mt_orders_shipping","mt_orders_shipping.orders_id = mt_orders.orders_id",'left');
	$CI->db->where("store_id", $store_id);
	$CI->db->where("orders_product_detail", $status_id);
	$CI->db->where("orders_status <=", 8);
	$CI->db->where("orders_istrash", 0);
	$CI->db->order_by("mt_orders_shipping.orders_shipping_name", "asc");
	$m = $CI->db->get("mt_orders")->result();
	if(count($m) > 0){
		foreach ($m as $k => $v) {
			$result[$k]->orders_id         = $v->orders_id;
			$result[$k]->orders_date       = $v->orders_date;
			$result[$k]->orders_source_name    = get_orders_source($v->orders_source_id)->orders_source_name;
			$result[$k]->orders_source_invoice = $v->orders_source_invoice;
			$result[$k]->orders_shipping_name  = $v->orders_shipping_name;
		}
	}

	return $result;
}

function get_orders_source($id=""){
	$CI = getCI();
	if($id==''){ $data = array("orders_source_id !=" => 0 ); }
	else { $data = array("orders_source_id" => $id ); }
	$m = $CI->db->order_by('position','asc')->get_where("mt_orders_source",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}

	// $result = '';
	// $m   = '';
	// $m[] = array('id' => '1', 'name' => 'Website', 'color' => 'dark', 'logo' => 'butiksasha.png');
	// $m[] = array('id' => '2', 'name' => 'Bukalapak', 'color' => 'danger', 'logo' => 'bukalapak.png');
	// $m[] = array('id' => '3', 'name' => 'Tokopedia', 'color' => 'primary', 'logo' => 'tokopedia.png');
	// $m[] = array('id' => '4', 'name' => 'Instagram', 'color' => 'dark', 'logo' => '');
	// $m[] = array('id' => '5', 'name' => 'Facebook', 'color' => 'dark', 'logo' => '');
	// $m[] = array('id' => '6', 'name' => 'Line', 'color' => 'dark', 'logo' => '');
	// $m[] = array('id' => '7', 'name' => 'Whatsapp', 'color' => 'success', 'logo' => 'whatsapp.png');
	// $m[] = array('id' => '8', 'name' => 'Shopee', 'color' => 'dark', 'logo' => 'shopee.png');
	// $m[] = array('id' => '9', 'name' => 'Pickup', 'color' => 'dark', 'logo' => '');
	// $m[] = array('id' => '10', 'name' => 'Lainnya', 'color' => 'dark', 'logo' => '');
	// $result = $m;
	// if($id != ''){
	// 	foreach ($m as $key => $val) {
	// 		if($val['id'] == $id){
	// 			$result = array('id' => $val['id'], 'name' => $val['name'], 'color' => $val['color'], 'logo' => $val['logo']);
	// 			// get_orders_source('1')['name'];
	// 		}
	// 	}
	// }
	// return $result;
}

function get_orders_source_image($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_orders_source",array(
		"orders_source_id" => $id
	),1,0)->row();
	$result = $m->orders_source_image;
	return $result;
}

function get_date_upload_payment($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_orders_source",array(
		"orders_source_id" => $id
	),1,0)->row();
	$result = $m->date_upload_payment;
	return $result;
}

function get_payment_method($id="", $parent=false){
	$CI = getCI();
	if($id==''){
		$data = array("parent_id" => 0);
	} else {
		if($parent){
			$data = array("parent_id" => $id );
		} else {
			$data = array("payment_method_id" => $id );
		}
	}

	$m = $CI->db->order_by('position','asc')->get_where("mt_payment_method",$data);
	if($id=='' || $parent){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_orders_courier($id="", $parent=false){
	$CI = getCI();
	if($id==''){
		$data = array("parent_id" => 0);
	} else {
		if($parent){
			$data = array("parent_id" => $id );
		} else {
			$data = array("orders_courier_id" => $id );
		}
	}

	$m = $CI->db->order_by('position','asc')->get_where("mt_orders_courier",$data);
	if($id=='' || $parent){
		return $m->result();
	} else {
		return $m->row();
	}
}
function get_child_orders_courier($id=""){
	$CI = getCI();
	$CI->db->order_by('position', 'asc');
	$m = $CI->db->get_where("mt_orders_courier",array(
		"parent_id"		=> $id
	))->result();
	return $m;
}
function get_parent_id_orders_courier($id=""){
	$CI = getCI();
	$result = "";
	$m = $CI->db->get_where("mt_orders_courier",array(
		"orders_courier_id"		=> $id
	),1,0)->row();
	if(count($m) > 0){
		$result = $m->parent_id;
	}
	return $result;
}
function get_count_courier_package($date_start=""){
	$CI = getCI();
	$result = "";
	$m = $CI->db->order_by('position', 'asc')->get_where("mt_orders_courier",array(
		"parent_id"				 => 0,
		"orders_courier_package" => 1
	))->result();
	if(count($m) > 0){
		foreach ($m as $k => $v) {
			$id   = $v->orders_courier_id;
			$name = $v->orders_courier_name;
			$qty  = 0;
			$CI->db->where("noted_type", "1");
			$CI->db->where("noted_temp_id", $v->orders_courier_id);
			$CI->db->where("noted_date LIKE", "%".$date_start."%");
			$CI->db->where("noted_istrash", "0");
			$noted = $CI->db->get("mt_noted")->row();
			if(count($noted) > 0){
				$qty  = $noted->noted_qty;
			}
			$result[$k]->id   = $id;
			$result[$k]->name = $name;
			$result[$k]->qty  = $qty;
		}
	}
	return $result;
}

function get_count_courier_payment($date_start="",$type="2"){
	$CI = getCI();
	$result = "";
	$m = $CI->db->order_by('position', 'asc')->get_where("mt_orders_courier",array(
		"parent_id"				 => 0,
		"orders_courier_payment" => 1
	))->result();
	if(count($m) > 0){
		foreach ($m as $k => $v) {
			$id   = $v->orders_courier_id;
			$name = $v->orders_courier_name;
			$qty  = 0;
			$CI->db->where("noted_type", $type);
			$CI->db->where("noted_temp_id", $v->orders_courier_id);
			$CI->db->where("noted_date LIKE", "%".$date_start."%");
			$CI->db->where("noted_istrash", "0");
			$noted = $CI->db->get("mt_noted")->row();
			if(count($noted) > 0){
				$qty  = $noted->noted_qty;
			}
			$result[$k]->id   = $id;
			$result[$k]->name = $name;
			$result[$k]->qty  = $qty;
		}
	}
	return $result;
}

function get_total_courier_payment(){
	$CI = getCI();
	$result = "";
	$m = $CI->db->order_by('position', 'asc')->get_where("mt_orders_courier",array(
		"parent_id"				 => 0,
		"orders_courier_payment" => 1
	))->result();
	if(count($m) > 0){
		foreach ($m as $k => $v) {
			$id   = $v->orders_courier_id;
			$name = $v->orders_courier_name;
			$qty  = 0;

			$CI->db->where_in("noted_type", array("2","3","4"));
			$CI->db->where("noted_temp_id", $v->orders_courier_id);
			$CI->db->where("noted_istrash", "0");
			$noted = $CI->db->get("mt_noted")->result();
			if(count($noted) > 0){
				foreach ($noted as $key => $val) {
					if($val->noted_type == "2"){
						$qty  += $val->noted_qty;
					} else if($val->noted_type == "3"){
						$qty  -= $val->noted_qty;
					} else if($val->noted_type == "4"){
						$qty  -= $val->noted_qty;
					}
				}
			}
			$result[$k]->id   = $id;
			$result[$k]->name = $name;
			$result[$k]->qty  = $qty;
		}
	}
	return $result;
}

function get_shipping_method($id=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Delivery');
	$m[] = array('id' => '2', 'name' => 'Pickup');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name']);
				// get_shipping_method('1')['name'];
			}
		}
	}
	return $result;
}

function set_shipping(){
	$result = array( 'city' 		=> 152,
				     'province' 	=> 6,
				     'fix_rate' 	=> 0,
				     'ppn' 			=> 0,
				     'service_tax'	=> 0,
				     'other_tax' 	=> 0
				 );
	return $result;
}

function get_temp_div_cart_all_total($id=''){
	$result = '';
	if($id == ''){
		$result = '
		<div id="temp-div-cart-all-total" style="display: none !important;">
		    <div class="cart-item-container">

		    </div>
		    <div class="cart-total-container">
		        <input type="hidden" name="cart-grandtotal-qty" value="0" />
		        <input type="hidden" name="cart-grandtotal-weight" value="0" />
		        <input type="hidden" name="cart-subgrandtotal-price" value="0" />
		        <input type="hidden" name="cart-ppn-price" value="0" />
		        <input type="hidden" name="cart-grandtotal-ppn-price" value="0" />
		        <input type="hidden" name="cart-shipping-price" value="0" />
		        <input type="hidden" name="cart-grandtotal-shipping-price" value="0" />
		        <input type="hidden" name="cart-voucher-price" value="0" />
		        <input type="hidden" name="cart-grandtotal-price" value="0" />
		    </div>
		</div>';
		// <div class="cart-item cart-item-1" data-id="1">
  //           <input type="hidden" name="cart-total-weight" value="0" />
  //           <input type="hidden" name="cart-total-qty" value="0" />
  //           <input type="hidden" name="cart-total-price" value="0" />
  //       </div>
	}
	return $result;
}

function get_user_store($id){
	$CI = getCI();
	$result = '';
	$m = $CI->db->get_where("mt_app_user",array(
		"user_id"	=> $id
	),1,0)->row();
	$result = $m->store_id;
	return $result;
}
function get_store(){
	$CI = getCI();
	$m = $CI->db->order_by("store_name", "asc")->get_where("mt_store",array(
		"store_istrash"	=> 0
	))->result();
	return $m;
}
function get_store_name($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_store",array(
		"store_id"	=> $id
	),1,0)->row();
	$result = $m->store_name;
	return $result;
}
function get_detail_store($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("store_date", "desc")->get_where("mt_store",array(
		"store_id"	=> $id
	),1,0)->row();
	return $m;
}
function get_saldo_store($store_id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_store",array(
			"store_id"		=> $store_id,
		),1,0)->row();
	$result = isset($m->store_saldo)?$m->store_saldo:"";
	return $result;
}

function get_store_sosmed($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("position", "asc")->get_where("mt_store_sosmed",array(
		"store_id"	=> $id
	))->result();
	return $m;
}
function get_store_sosmed_active($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("position", "asc")->get_where("mt_store_sosmed",array(
		"store_id"				=> $id,
		"store_sosmed_status"	=> 1
	))->result();
	return $m;
}
function get_store_cat_sosmed($id=""){
	$CI = getCI();
	if($id==''){ $data = array("store_cat_sosmed_id !=" => 0 ); }
	else { $data = array("store_cat_sosmed_id" => $id ); }
	$m = $CI->db->order_by('store_cat_sosmed_name','asc')->get_where("mt_store_cat_sosmed",$data);
	if($id==''){
		return $m->result();
	} else {
		return $m->row();
	}
}

function get_store_orders_status($id=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Booked');
	$m[] = array('id' => '2', 'name' => 'Dibeli');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name']);
				// get_store_orders_status('1')['name'];
			}
		}
	}
	return $result;
}
function get_store_orders_detail_status($id=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Dibeli', 'desc' => 'Sudah dibeli, tinggal menunggu pembayaran.');
	$m[] = array('id' => '2', 'name' => 'Dibayar', 'desc' => 'Sudah dibayar.');
	$m[] = array('id' => '9', 'name' => 'Order Dicancel', 'desc' => 'Order dibatalkan');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name'], 'desc' => $val['desc']);
				// get_store_orders_detail_status('1')['name'];
			}
		}
	}
	return $result;
}

function get_store_count_detail_orders($store_id=''){
	$CI = getCI();
	$m = $CI->db->order_by("store_orders_date", "desc")->get_where("mt_store_orders",array(
		"store_id"				=> $store_id,
		"store_orders_istrash"	=> 0
	))->result();
	return $m;
}
function get_store_orders_detail($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("orders_detail_id", "asc")->get_where("mt_store_orders_detail",array(
		"store_orders_id"	=> $id
	))->result();
	return $m;
}

function get_saldo(){
	$CI = getCI();
	$m = $CI->db->order_by("saldo_date", "desc")->get_where("mt_saldo",array(
		"saldo_id !="	=> 0
	))->result();
	return $m;
}
function get_detail_saldo($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("saldo_date", "desc")->get_where("mt_saldo",array(
		"orders_source_id"	=> $id
	))->result();
	return $m;
}
function insert_saldo($par=array()){
	$CI = getCI();
	$data = array();
	$return = false;
	$saldo_id    = '';
	$saldo_total = 0;

	$m = $CI->db->get_where("mt_orders_source",array(
		"orders_source_id"	=> $par['orders_source_id']
	),1,0)->row();
	if(count($m) > 0){
		$saldo_total = $m->orders_source_saldo;
	}

	if($par['orders_id']!=''){
		$m2 = $CI->db->get_where("mt_saldo",array(
			"orders_id"	=> $par['orders_id']
		),1,0)->row();
		if(count($m2) > 0){
			$saldo_id 	 = $m2->saldo_id;
			$selisih = $par['saldo_price'] - $m2->saldo_price;
		}
	}
	if($saldo_id == ''){
		if($par['saldo_type'] == '2'){ $saldo_total = ($saldo_total - $par['saldo_price']); }
		else { $saldo_total = ($saldo_total + $par['saldo_price']); }
	} else {
		$saldo_total = ($saldo_total + $selisih);
	}

	$CI->db->update("mt_orders_source",array("orders_source_saldo"=>$saldo_total),array("orders_source_id" => $par['orders_source_id']));

	$data['orders_source_id'] = $par['orders_source_id'];
	$data['orders_id'] 		  = $par['orders_id'];
	$data['saldo_price'] 	  = $par['saldo_price'];
	// $data['saldo_total'] 	  = $saldo_total;
	$data['saldo_noted']	  = $par['saldo_noted'];
	$data['saldo_type'] 	  = $par['saldo_type'];
	$data['saldo_date'] 	  = timestamp();

	$CI->DATA->table="mt_saldo";
	$a = $CI->_save_master(
		$data,
		array(
			'saldo_id' => $saldo_id
		),
		$saldo_id
	);
	$id = $a['id'];
	if($id != ''){ $return = true; }
	return $return;

	// insert_saldo(array(
	// 	'orders_source_id'	=> $data1['orders_source_id'],
	// 	'orders_id'		    => $id,
	// 	'saldo_price'	    => $data1['orders_price_grand_total'],
	// 	'saldo_noted'	    => '',
	// 	'saldo_type'	    => 1
	// ));
}

function convObjToArray($array=""){
	$result = array();
    foreach ($array as $k => $v) {
        foreach ($v as $k2 => $v2) {
            $result[$k]->$k2 = $v2;
        }
    }
    return $result;
}

function get_last_saldo($id=''){
	$CI = getCI();
	$saldo = 0;
	$m = $CI->db->get_where("mt_orders_source",array(
		"orders_source_id"	=> $id
	),1,0)->row();
	if(count($m) > 0){
		$saldo = $m->orders_source_saldo;
	}
	return $saldo;
}

function create_product_barcode($product_code='', $idDetail=''){
	$barcode = "";
	if($product_code != "" && $idDetail != ""){
		$barcode = $product_code.'-'.$idDetail;
	} else if($product_code != "" && $idDetail == ""){
		$barcode = $product_code;
	}
	return $barcode;
}

function create_product_code($code=''){
	// $fullcode = 'PMBSS01';
	$CI      = getCI();
	$data    = array();
	$CI->db->select_max('product_code');
	$CI->db->where('product_code LIKE', '%'.$code.'%');
	$result  = $CI->db->get('mt_product')->row();
	$idMax   = $result->product_code;
	$noUrut  = (int) substr($idMax, strlen($code), 2);
	$noUrut++;
	$newID   = sprintf("%02s", $noUrut);
	$data['code']    	= strtoupper($newID);
	$data['fullcode']   = strtoupper($code.$newID);
	return $data;
}
function create_orders_code($vardate=""){
	// $orders_code = '16040001';
	// $orders_invoice     = 'INV/20160402/XVI/IV/16040001';
	$CI      = getCI();
	$data    = array();
	$date_no = ($vardate==""?date("ym"):date("ym", strtotime($vardate)));
	$CI->db->select_max('orders_code');
	$CI->db->where('orders_code LIKE', '%'.$date_no.'%');
	$result  = $CI->db->get('mt_orders')->row();
	$idMax   = $result->orders_code;
	$noUrut  = (int) substr($idMax, 4, 4);
	$noUrut++;
	$newID   = $date_no . sprintf("%04s", $noUrut);
	$data['orders_code']    = $newID;
	$data['orders_invoice'] = 'INV/'.($vardate==""?date("Ymd"):date("Ymd", strtotime($vardate))).'/'.convRomawi(($vardate==""?date("y"):date("y", strtotime($vardate)))).'/'.convRomawi(($vardate==""?date("m"):date("m", strtotime($vardate)))).'/'.$newID;
	return $data;
}
function create_store_orders_code($vardate=""){
	// $orders_code = '16040001';
	// $orders_invoice     = 'INV/RESELLER/16040001';
	$CI      = getCI();
	$data    = array();
	$date_no = ($vardate==""?date("ym"):date("ym", strtotime($vardate)));
	$CI->db->select_max('store_orders_code');
	$CI->db->where('store_orders_code LIKE', '%'.$date_no.'%');
	$result  = $CI->db->get('mt_store_orders')->row();
	$idMax   = $result->store_orders_code;
	$noUrut  = (int) substr($idMax, 4, 4);
	$noUrut++;
	$newID   = $date_no . sprintf("%04s", $noUrut);
	$data['orders_code']    = $newID;
	$data['orders_invoice'] = 'INV/RESELLER/'.$newID;
	return $data;
}
function create_payment_code($vardate=""){
	// $payment_code     = 'PYM/BS/20160402/0001';
	$CI      = getCI();
	$data    = array();
    $date_no = 'PYM/BS/'.($vardate==""?date("Ymd"):date("Ymd", strtotime($vardate))).'/';
	$CI->db->select_max('orders_payment_code');
	$CI->db->where('orders_payment_code LIKE', '%'.$date_no.'%');
	$result  = $CI->db->get('mt_orders_payment')->row();
	$idMax   = $result->orders_payment_code;
	$noUrut  = (int) substr($idMax, 16, 4);
	$noUrut++;
	$newID   = $date_no . sprintf("%04s", $noUrut);
	$data['payment_code'] = $newID;
	return $data;
}

function get_purchase_payment_source($id=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Cash');
	$m[] = array('id' => '2', 'name' => 'Saldo Bukalapak');
	$m[] = array('id' => '3', 'name' => 'Saldo Tokopedia');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name']);
				// get_purchase_payment_source('2')['name'];
			}
		}
	}
	return $result;
}
function create_purchase_invoice($vardate=""){
	// $payment_code     = 'BUY/BS/20160402/0001';
	$CI      = getCI();
	$data    = array();
    $date_no = 'BUY/BS/'.($vardate==""?date("Ymd"):date("Ymd", strtotime($vardate))).'/';
	$CI->db->select_max('purchase_invoice');
	$CI->db->where('purchase_invoice LIKE', '%'.$date_no.'%');
	$result  = $CI->db->get('mt_purchase')->row();
	$idMax   = $result->purchase_invoice;
	$noUrut  = (int) substr($idMax, 16, 4);
	$noUrut++;
	$newID   = $date_no . sprintf("%04s", $noUrut);
	$data['purchase_invoice'] = $newID;
	return $data;
}
function get_purchase_detail($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("purchase_detail_id", "asc")->get_where("mt_purchase_detail",array(
		"purchase_id"	=> $id
	))->result();
	return $m;
}
function get_purchase_detail_status($id=''){
	$CI = getCI();
	$m = $CI->db->order_by("purchase_detail_id", "asc")->get_where("mt_purchase_detail",array(
		"product_id !=" 			=> 0,
		"purchase_detail_status"	=> $id
	))->result();
	return $m;
}
function get_purchase_payment_remaining(){
	$CI = getCI();
	$m = $CI->db->get_where("mt_purchase",array(
		"purchase_status"	=> 0,
		"purchase_istrash"	=> 0
	))->result();
	$total = 0;
	foreach ($m as $key => $val) {
		$total += $val->purchase_payment_remaining;
	}
	return $total;
}
function get_purchase_reseller_payment_remaining($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_purchase_detail",array(
		"store_id"					=> $id,
		"purchase_reseller_status"	=> 0
	))->result();
	$total = 0;
	foreach ($m as $key => $val) {
		$total += $val->purchase_reseller_remaining;
	}
	return $total;
}
function get_purchase_reseller_remaining($id=""){
	$CI = getCI();
	$m = $CI->db->get_where("mt_purchase_detail",array(
		"purchase_id"				=> $id,
		"purchase_reseller_status"	=> 0
	))->result();
	$total = 0;
	foreach ($m as $key => $val) {
		$total += $val->purchase_reseller_remaining;
	}
	return $total;
}
function get_purchase_supplier_remaining(){
	$CI = getCI();
	$m = $CI->db->group_by("supplier_id")->get_where("mt_purchase",array(
		"purchase_status"	=> 0,
		"purchase_istrash"	=> 0
	))->result();
	$result = array();
	if(count($m) > 0){
		foreach ($m as $key => $val) {
			$result[$key]->supplier_id   = $val->supplier_id;
			$result[$key]->supplier_name = get_supplier($val->supplier_id)->supplier_name;
			$result[$key]->purchase_payment_remaining = 0;
			$m2 = $CI->db->get_where("mt_purchase",array(
				"supplier_id"		=> $val->supplier_id,
				"purchase_status"	=> 0,
				"purchase_istrash"	=> 0
			))->result();
			if(count($m2) > 0){
				$total = 0;
				foreach ($m2 as $key2 => $val2) {
					$total += $val2->purchase_payment_remaining;
				}
				$result[$key]->purchase_payment_remaining = $total;
			}
		}
	}
	return $result;
}


function get_purchase_by_date($tipe_date='',$date_start='',$date_end=''){
	$CI = getCI();
	if($date_start==''){ $date_start = timestamp(); }
	if($tipe_date=='today'){ $CI->db->where("purchase_date LIKE","%".getYearMonthDate($date_start)."%"); }
	if($tipe_date=='monthly'){ $CI->db->where("purchase_date LIKE","%".getYearMonth($date_start)."%"); }
	if($tipe_date=='lastmonth'){ $CI->db->where("purchase_date LIKE","%".getLastMonth($date_start)."%"); }
	if($tipe_date=='periode'){
		$CI->db->where("( purchase_date >= '".$date_start." 00:00:00' )");
		$CI->db->where("( purchase_date <= '".$date_end." 23:59:59' )");
	}
	$CI->db->where("purchase_istrash",'0');
	$m = $CI->db->get("mt_purchase")->result();
	return $m;
}

function get_option_qty($i='', $count=500){
	$return = '';
	for($ii=1;$ii<=$count;$ii++){
		$return .= '<option value="'.$ii.'" '.($i==$ii?'selected':'').'>'.$ii.'</option>';
	}
	return $return;
}
// CUSTOM

function get_product_stock_no_sync($store_id='1'){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product_detail.*");
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("mt_product_detail.product_status_id", 1);
	$CI->db->where("mt_product.product_istrash", 0);
	$CI->db->where("mt_product.store_id", $store_id);
	$CI->db->order_by("mt_product.product_date", "desc");
	$m = $CI->db->get("mt_product")->result();

	$return = array();
	$founds = false;
	$item   = array();
	foreach ($m as $key => $val) {
		$total_item = 0;
		if($val->product_stock_detail != "" && $val->product_stock_detail != "[]"){
			$product_stock_detail = json_decode($val->product_stock_detail);
            foreach ($product_stock_detail as $key1 => $val1) {
                $total_item += $val1->qty;
            }
		} else {
			$total_item = $val->product_stock;
		}

		if($val->product_stock != $total_item){
			$founds = true;
			$item[] = array(
				"id"  	 	 => $val->product_id,
				"name"  	 => $val->product_name,
				"qty_stock"  => $val->product_stock,
				"qty_varian" => $total_item
				);

		}
	}

	$return = array(
		"founds" => $founds,
		"item" 	 => arrayToObject($item)
		);

	return $return;
}

function check_double_orders_detail($store_id="1"){
	$CI = getCI();
	$CI->db->select("mt_product.product_id");
	$CI->db->where("store_id", $store_id);
	$m = $CI->db->get("mt_product")->result();
	$result = array();
	$i = 0;
	foreach ($m as $key => $val) {
		$tempArr = array();
		$found   = false;
		$m2 = $CI->db->order_by("orders_detail_id", "asc")->get_where("mt_orders_detail",array(
			"product_id" => $val->product_id
		))->result();
		foreach ($m2 as $key2 => $val2) {
			if(in_array($val2->orders_id, $tempArr)){
				foreach ($val2 as $kk => $vv) {
                    $result[$i]->$kk = $vv;
                }
				$i += 1;
			} else {
				$tempArr[] = $val2->orders_id;
			}
		}
	}
	return $result;
}

function check_double_sku_marketplace($source_name=""){
	$CI = getCI();
	$CI->db->select("mt_product_mp.product_id");
	$CI->db->where("product_mp_id !=", 0);
	$m = $CI->db->get("mt_product_mp")->result();
	$result = array();
	$i = 0;
	foreach ($m as $key => $val) {
		$tempArr = array();
		switch ($source_name) {
			case 'shopee':
				if($val->product_mp_shopee != "" && $val->product_mp_shopee != "0"){
					if(in_array($val->product_mp_shopee, $tempArr)){
						$result[$i]->id   = $val->product_id;
						$result[$i]->name = get_title_product($val->product_id);
						$result[$i]->sku  = $val->product_mp_shopee;
						$i += 1;
					} else {
						$tempArr[] = $val->product_mp_shopee;
					}
				}
				break;

			default:
				# code...
				break;
		}
	}
	return $result;
}

function check_log_item_stock($log_type="2",$log_date="",$product_id="",$log_title_id="",$log_title=NULL,$orders_id=NULL){
	$CI = getCI();
	$CI->db->where("log_type", $log_type);
	$CI->db->where("log_date LIKE","%".getYearMonthDate($log_date)."%");
	$CI->db->where("log_detail_id", $product_id);
	$CI->db->where("log_title_id", $log_title_id);
	$CI->db->where("log_title", $log_title);
	$CI->db->where("log_desc", $orders_id);
	$m = $CI->db->get("mt_app_log")->result();
	return $m;
}

function noted_type($id=''){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Jumlah Kirim Paket');
	$m[] = array('id' => '2', 'name' => 'Tagihan Kurir');
	$m[] = array('id' => '3', 'name' => 'Pembayaran Kurir');
	$m[] = array('id' => '4', 'name' => 'Diskon Pengiriman');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name']);
				// noted_type('1')['name'];
			}
		}
	}
	return $result;
}

function check_orders_date_long(){
	$CI = getCI();
	$result = "";
	$date = date('Y-m-d', strtotime('-25 days'));
	$m = $CI->db->get_where("mt_orders",array(
		"orders_status <"		=> 8,
		"orders_claim_status "	=> 0,
		"orders_date <="		=> $date." 23:59:59"
	))->result();
	if(count($m) > 0){
		// $result = count($m);
		foreach ($m as $key => $val) {
			$CI->db->update("mt_orders",array("orders_claim_status"=>1),array("orders_id"=>$val->orders_id));
		}
	}
	return $result;
}


// function front_generate_read_link(){
// 	$link_type = $r->link_type;
// 	switch ($link_type) {
// 	    case '0': $link_id = ""; break;
// 	    case '1': $link_id = $r->link_article_id; break;
// 	    case '2': $link_id = $r->link_category_article_id; break;
// 	    case '3': $link_id = $r->link_product_id; break;
// 	    case '4': $link_id = $r->link_category_product_id; break;
// 	    case '5': $link_id = $r->link_gallery_id; break;
// 	    case '9': $link_id = $r->link_custom; break;
// 	    default: $link_id = ""; break;
// 	}
// }
function get_link_type($id=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '0', 'name' => 'Tidak Ada Link', 'type' => '', 'form' => '');
	$m[] = array('id' => '1', 'name' => 'Artikel', 'type' => 'article_select', 'form' => 'form-article');
	$m[] = array('id' => '2', 'name' => 'Article Category', 'type' => 'article_category_select', 'form' => '');
	$m[] = array('id' => '3', 'name' => 'Produk', 'type' => 'product_select', 'form' => 'form-produk');
	$m[] = array('id' => '4', 'name' => 'Product Category', 'type' => 'product_category_select', 'form' => 'form-category-produk');
	$m[] = array('id' => '5', 'name' => 'Gallery', 'type' => 'gallery_select', 'form' => 'form-gallery');
	$m[] = array('id' => '9', 'name' => 'Custom', 'type' => 'custom_select', 'form' => '');
	$result = $m;
	if($id != ''){
		foreach ($m as $key => $val) {
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name'], 'type' => $val['type'], 'form' => $val['form']);
				// get_link_type('1')['name'];
			}
		}
	}
	return $result;
}
function get_write_link($link_type="",$link_value=""){ //get_write_link($link_type,$link_value);
	if($link_type != ""){
		$arr = array(1,3,4,5);
		if(in_array($link_type, $arr)){
			$tmpVal = explode(".",$link_value);
			$link_value = $tmpVal[0];
		}
	}
	$result = array();
	$result['link_type']  = $link_type;
	$result['link_value'] = $link_value;
	return $result;
}
function get_read_link($link_type="",$link_value=""){ //get_read_link($link_type,$link_value);
	$CI = getCI();
	$return = "";
	switch ($link_type) {
        case '0': $return = ""; break;
        case '1':
        	$m = $CI->db->get_where("mt_article",array(
				"article_id"	=> $link_value
			),1,0)->row();
			if(count($m) > 0){
				$return = base_url().get_url_article_category($m->article_category_id).'/'.$m->url;
			}
        	break;
        case '2':
        	$m = $CI->db->get_where("mt_article_category",array(
				"category_id"	=> $link_value
			),1,0)->row();
			if(count($m) > 0){
				$return = base_url().$m->url;
			}
        	break;
        case '3':
        	$m = $CI->db->get_where("mt_product",array(
				"product_id"	=> $link_value
			),1,0)->row();
			if(count($m) > 0){
				$return = base_url().get_url_product_category($m->product_category_id).'/'.$m->url;
			}
        	break;
        case '4':
        	$m = $CI->db->get_where("mt_product_category",array(
				"product_category_id"	=> $link_value
			),1,0)->row();
			if(count($m) > 0){
				$return = base_url().$m->url;
			}
        	break;
        case '5':
        	$m = $CI->db->get_where("mt_gallery",array(
				"gallery_id"	=> $link_value
			),1,0)->row();
			if(count($m) > 0){
				$return = base_url().'gallery/'.$m->url;
			}
        	break;
        case '9': $return = $link_value; break;
        default: $return = ""; break;
    }
	return $return;
}
function get_url_article_category($id=""){
	$CI = getCI();
	$return = "";
	$m = $CI->db->get_where("mt_article_category",array(
		"category_id"	=> $id
	),1,0)->row();
	if(count($m) > 0){
		$return = $m->url;
	}
	return $return;
}
function get_url_product_category($id=""){
	$CI = getCI();
	$return = "";
	$m = $CI->db->get_where("mt_product_category",array(
		"product_category_id"	=> $id
	),1,0)->row();
	if(count($m) > 0){
		$return = $m->url;
	}
	return $return;
}
function get_product_category_code($id=""){
	$CI = getCI();
	$return = "";
	$m = $CI->db->get_where("mt_product_category",array(
		"product_category_id"	=> $id
	),1,0)->row();
	if(count($m) > 0){
		$return = $m->product_category_code;
	}
	return $return;
}

function remove_url_query($url="",$query=""){
	$result = "";
	$parts  = parse_url($url);
	$queryParams = array();
	parse_str($parts['query'], $queryParams);
	$exp = explode(",", $query);
	foreach ($exp as $n) {
		unset($queryParams[$n]);
	}
	$queryString = http_build_query($queryParams);
	$result = $parts['path'].'?'.$queryString;
	return $result;
}

function arrayToObj($array){ // Simple
	// Contoh
	// $item = array('id'		=> "1",
	// 	  		  'name'	=> "Nama",
	// 			);
	// array_push($product, $item);
	// $product = arrayToObj($product);

	return json_decode(json_encode($array), FALSE);
}

function array_to_obj($array, $obj){
	foreach ($array as $key => $value){
		if (is_array($value)){
			$obj->$key = new stdClass();
			array_to_obj($value, $obj->$key);
		} else {
			$obj->$key = $value;
		}
	}
	return $obj;
}
function arrayToObject($array){
	$object= new stdClass();
	return array_to_obj($array,$object);
}
function arrayToPrint($array){
	$result = "";
	foreach($array as $key => $val) {
        $result .= $key .' = '.$val. "<br>";
    }
    return $result;
}

function getCharMail($string){
    $string = substr(trim($string),0,1);
	$result = strtoupper($string);
    return $result;
}
function getAvatarMail($string){
    $string = substr(trim($string),0,1);
	$string = strtoupper($string);

	$result = 'bg-dark';
    if (in_array($string, array('A','G','M','S','Y'))) { $result = 'bg-dark'; }
    if (in_array($string, array('B','H','N','T','Z'))) { $result = 'bg-primary'; }
    if (in_array($string, array('C','I','O','U'))) { $result = 'bg-success'; }
    if (in_array($string, array('D','J','P','V'))) { $result = 'bg-info'; }
    if (in_array($string, array('E','K','Q','W'))) { $result = 'bg-danger'; }
    if (in_array($string, array('F','L','R','X'))) { $result = 'bg-warning'; }

    return $result;
}

function isToken($token,$table,$field) {
    if (isset($token) && $token) {
        $CI = getCI();
		$m = $CI->db->get_where($table,array(
			$field	=> $token
		))->result();
		if(!empty($m)){
			return true;
		} else {
			return false;
		}
    } else {
        return false;
    }
}
function generateUniqueToken($number,$table,$field){
    $token = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, +$number);
    if (isToken($token,$table,$field)) {
        return generateUniqueToken($number,$table,$field);
    } else {
        return $token;
    }
}

function isUniqueUsername($username) {
    if (isset($username) && $username) {
        //verification values in BD
        if(checkIsRoute($username)){
        	return true;
        } else {
        	$CI = getCI();
	        $v = $CI->db->get_where('mt_member',array(
					'member_username' => $username
				))->row();
			if(count($v)>0){
	            return true;
	        } else {
	            return false;
	        }
        }

    } else {
        return false;
    }
}
function generateUniqueUsername($title){
    $int = substr(str_shuffle("0123456789"), 0, +3);
    $username = changecharnum($title);
    if (isUniqueUsername($username)) {
    	$title = $title.$int;
        return generateUniqueUsername($title);
    } else {
        return $username;
    }
}

function getCountProductDetail($store_id=""){
	$CI = getCI();
	$CI->db->select("mt_product.*, mt_product_detail.*");
	$CI->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

	$CI->db->where("mt_product.store_id", $store_id);
	$CI->db->where("mt_product_detail.product_stock >", 0);
	$CI->db->order_by("mt_product.product_id", "desc");
	$m = $CI->db->get("mt_product")->result();
	$stock_ready = 0;
	$price_buy   = 0;
	$price_sale  = 0;
	foreach ($m as $k => $v) {
		$stock_ready += $v->product_stock;
		$price_buy   += ($v->product_stock * $v->product_price_buy);
		$price_sale  += ($v->product_stock * $v->product_price_sale);
	}
	$data = array();
	$data['stock_ready'] = $stock_ready;
	$data['price_buy']   = $price_buy;
	$data['price_sale']  = $price_sale;
	return $data;
}

// function inject_array_key_grosir(){
	// $CI = getCI();
	// $v = $CI->db->get_where('mt_product_detail',array(
	// 	'product_price_grosir !=' => ''
	// ))->result();
	// foreach ($v as $m => $r) {
	// 	$arr_new = array();
	// 	$count_before = array();
	//     $arr_decode = json_decode($r->product_price_grosir);
	//     foreach ($arr_decode as $key1 => $value1) {
	//         $count_before[] = $value1->qty;
	//     }
	//     $i = 1;
	//     foreach ($arr_decode as $key2 => $value2) {
	//         $min = $value2->qty;
	//         $max = $count_before[$i] - 1;
	//         $min_max = $min.' - '.$max;
	//         if($count_before[$i] == ''){
	//             $min_max = '>= '.$min;
	//         }
	//         $i += 1;

	//         $arr_new[] = array('name' 	=> $min_max,
	//     					    'qty' 	=> $value2->qty,
	// 						    'price'	=> $value2->price
	// 						);
	//     }
	//     $return = json_encode($arr_new);

	// 	$CI->db->update("mt_product_detail",array("product_price_grosir"=>$return),array("product_detail_id"=>$r->product_detail_id));
	// }
// }

// SCRAPER
function get_source($id="", $name="", $url=""){
	$result = '';
	$m   = '';
	$m[] = array('id' => '1', 'name' => 'Tokopedia', 'url' => 'tokopedia', 'max-title' => '70', 'max-desc' => '2000');
	$m[] = array('id' => '2', 'name' => 'Bukalapak', 'url' => 'bukalapak', 'max-title' => '100', 'max-desc' => '2000');
	$m[] = array('id' => '3', 'name' => 'Shopee', 'url' => 'shopee', 'max-title' => '100', 'max-desc' => '3000');
	$result = $m;
	foreach ($m as $key => $val) {
		if($id != ''){
			if($val['id'] == $id){
				$result = array('id' => $val['id'], 'name' => $val['name'], 'url' => $val['url'], 'max-title' => $val['max-title'], 'max-desc' => $val['max-desc']);
				// get_source('1')['name'];
			}
		} else if($name != ''){
			if($val['name'] == $name){
				$result = array('id' => $val['id'], 'name' => $val['name'], 'url' => $val['url'], 'max-title' => $val['max-title'], 'max-desc' => $val['max-desc']);
				// get_source('1')['name'];
			}
		} else if($url != ''){
			if($val['url'] == $url){
				$result = array('id' => $val['id'], 'name' => $val['name'], 'url' => $val['url'], 'max-title' => $val['max-title'], 'max-desc' => $val['max-desc']);
				// get_source('1')['name'];
			}
		}
	}
	return $result;
}

function convProductTitle($scraper_title='', $scraper_source_id='', $scraper_old_name='', $scraper_new_name='', $scraper_add_frontname='', $scraper_add_endname=''){
	$result = '';
	$result_end = '';
	$max_title = 250;
	if($scraper_source_id != ''){
		$source = get_source($scraper_source_id);
		$max_title = (int)$source['max-title'];
	}
	if($scraper_old_name != ''){ $scraper_title = str_ireplace($scraper_old_name, $scraper_new_name, $scraper_title); }
	if($scraper_add_frontname != ''){ $result = $scraper_add_frontname; }
	if($scraper_add_endname != ''){ $result_end = ' '.$scraper_add_endname; }

	$expTitle = explode(" ", $scraper_title);
	foreach ($expTitle as $val) {
		if((strlen($result) + strlen($val) + strlen($result_end)) <= $max_title){
			$result .= ($result!=''?' ':'').$val;
		}
	}
	return cleanSpace($result.$result_end);
}

function convProductDesc($scraper_desc='', $scraper_source_id='', $scraper_old_name='', $scraper_new_name='', $scraper_old_phone='', $scraper_new_phone='', $scraper_add_frontdesc='', $scraper_add_enddesc=''){
	$result = '';
	$result_end = '';
	$max_desc = 2000;
	if($scraper_source_id != ''){
		$source = get_source($scraper_source_id);
		$max_desc = (int)$source['max-desc'];
	}
	if($scraper_old_name != ''){ $scraper_desc = str_ireplace($scraper_old_name, $scraper_new_name, $scraper_desc); }
	if($scraper_old_phone != ''){ $scraper_desc = str_ireplace($scraper_old_phone, $scraper_new_phone, $scraper_desc); }
	if($scraper_add_frontdesc != ''){ $result = $scraper_add_frontdesc; }
	if($scraper_add_enddesc != ''){ $result_end = ' '.$scraper_add_enddesc; }

	$expDesc = explode(" ", $scraper_desc);
	foreach ($expDesc as $val) {
		if((strlen($result) + strlen($val) + strlen($result_end)) <= $max_desc){
			$result .= ($result!=''?' ':'').$val;
		}
	}
	// return $result.$result_end.' (Length: '.(strlen($result) + strlen($result_end)).')';
	return cleanSpace($result.$result_end);
}

/* creates a compressed zip file */
function create_zip($files = array(), $destination = '',$overwrite = false) {
	if(file_exists($destination) && !$overwrite) { return false; }
	$valid_files = array();
	if(is_array($files)) {
		foreach($files as $file) {
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	if(count($valid_files)) {
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		$zip->close();
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

/* Based on code from David Walsh – http://davidwalsh.name/bitly-php */
function make_bitly_url($url,$format = 'xml',$version = '2.0.1') {
	//Set up account info

	// $bitly_login = 'dewacode'; //dewacode@gmail.com
	// $bitly_api   = 'R_e9fdfa73cfa2435cb7d5ec73c7a25384';

	$bitly_login = 'febripixelnine'; //febri@pixelnine.id
	$bitly_api   = 'R_36ee9fd8b01143b3a0ab98a7090c4dd9';

	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$bitly_login.'&apiKey='.$bitly_api.'&format='.$format;
	$response = file_get_contents($bitly);
	if(strtolower($format) == 'json') {
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	} else {
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}
