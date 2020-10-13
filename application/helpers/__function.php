<?php
	
	function convertRp($angka) {
		$jadi = $angka;
		if(is_numeric($angka)){
			$jadi = 'Rp '.number_format($angka,0,',','.'); 
		}
		return $jadi;
	}
	function convertRp2($angka) {
		$jadi = $angka;
		if(is_numeric($angka)){
			$jadi = 'Rp '.number_format($angka,0,'.',','); 
		}
		return $jadi;
	}
	function convertRpToInt($angka) {
		$jadi = str_replace(".", "", $angka);
		return $jadi;
	}

	function convertKg($angka) {
		$jadi = $angka;
		if(is_numeric($angka)){
			$jadi = ceil(($angka / 1000)); 
		}
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
	
	/* --- DATE --- */
	$timestamp = gmdate("Y-m-d H:i:s", time()+60*60*7); //GMT +7
	
	date_default_timezone_set('Asia/Jakarta'); //Sama aja GMT +7
	$timestamp2 = date("Y-m-d H:i:s");
	$dateNow = date("Y-m-d");
	$lastMonth = date("Y-m-d", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	
		$tahun = substr($timestamp, 0, 4);
		$tahun2 = substr($timestamp, 2, 2);
		$bulan = substr($timestamp, 5, 2);
		$tgl   = substr($timestamp, 8, 2);
		$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
	$datestamp = $tahun . "-" . $bulan . "-" . $tgl;
	$datestamp1 = $tgl . "-" . $bulan . "-" . $tahun;
	$datestamp2 = $tgl . "-" . $bulan . "-" . $tahun2;
	$datestamp3 = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun; 
	$datestamp4 = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun2; 
	
	function getYear($vardate) {
		return substr($vardate, 0, 4);
	}
	
	function convDatetoString($vardate) {
		return strtotime($vardate);
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
	
	function convDateTime($vardate) {
		if($vardate!=''){
			$tahun = substr($vardate, 0, 4);
			$tahun2 = substr($vardate, 2, 2);
			$bulan = substr($vardate, 5, 2);
			$tgl   = substr($vardate, 8, 2);
			
			$jam   = substr($vardate, 11, 2);
			$menit   = substr($vardate, 14, 2);
			$detik   = substr($vardate, 17, 2);
			$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
			return $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun .", ". $jam .":". $menit .":". $detik ;
		} else {
			return;
		}
	}
	function convDateMonth($vardate) {
		$bulan = $vardate;
		$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");
		return $BulanIndo[(int)$bulan-1];   
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
			$timeCalc = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun ." pukul ". $jam .":". $menit; 
        }        
        return $timeCalc;
    }
	
	function timeAgo($vardate){ //Berfungsi untuk menimbang hasil xTimeAgo, jika jam menit lebih dari 60 maka dihitung jam, jika jam lebih dari 24 maka dihitung hari
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
		return $go;
	}

	function TimeSince($original,$today){

        $periods = array(
            array(60 * 60 * 24 * 365 , 'years'),
            array(60 * 60 * 24 * 30 , 'months'),
            array(60 * 60 * 24 * 7, 'weeks'),
            array(60 * 60 * 24 , 'days'),
            array(60 * 60 , 'hours'),
            array(60 , 'minutes'),
		);

		$original 	= strtotime($original);
		$today 		= strtotime($today);
        $since 		= $today - $original;

        for ($i = 0, $j = count($periods); $i < $j; $i++){
            $seconds = $periods[$i][0];
            $name = $periods[$i][1];

            if (($count = floor($since / $seconds)) != 0){
                break;
            }
        }

        $output = ($count == 1) ? '1 '.$name : "$count $name";
        if ($i + 1 < $j){
            $seconds2 = $periods[$i + 1][0];
            $name2 = $periods[$i + 1][1];

            if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0){
                $output .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 $name2";
            }
        }
		
        return $output;
    }
	
	function convDateTimeEng($vardate) {
		return date("D M j, H:i", strtotime($vardate) ); 
	}
	function convDateTimeFullEng($vardate) {
		return date("l d F Y, H:i", strtotime($vardate) ); 
	}
	function convDateEng($vardate) {
		return date("l, F d, Y", strtotime($vardate) ); 
	}
	function convDateNewEng($vardate) {
		return date("d F Y", strtotime($vardate) ); 
	}
	function convDateNewEng2($vardate) {
		return date("d M Y", strtotime($vardate) ); 
	}

	function convRomawi($num){ // 
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
	/* --- /DATE --- */
	
	/* --- CURRENT PAGES --- */
	function pageUrl() {
		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;	
	}

	function pageNotFound($imgPageNotFound) {
		$page = '<div style="position: relative; width: 100%; padding: 20px 0px;">
                    <img style="position: relative; margin: 0px auto; display: block;" src="'.$imgPageNotFound.'">
                </div>';
		return $page;	
	}
	
	/* --- ACTIVITY LOG FUNCTION --- */
	function addLog($id_user,$description){
		$timestamp = gmdate("Y-m-d H:i:s", time()+60*60*7); //GMT +7
		$sql = "insert into activity_logs (id_user,description,date_created) VALUES ('".$id_user."','".$description."','".$timestamp."')"; 
		$result = mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
	}
	
	/* --- RESET PASSWORD RANDOM --- */
	function randomPassword( 
		$length=8, //string length
		$uselower=1, //use lowercase letters
		$useupper=1, // use uppercase letters
		$usespecial=0, //use special characters
		$usenumbers=1, //use numbers
		$prefix=''
		){
			$key = $prefix;
			// Seed random number generator
			srand((double)microtime() * rand(1000000, 9999999));
			$charset = "";
			if ($uselower == 1) $charset .= "abcdefghijkmnopqrstuvwxyz";
			if ($useupper == 1) $charset .= "ABCDEFGHIJKLMNPQRSTUVWXYZ";
			if ($usenumbers == 1) $charset .= "0123456789";
			if ($usespecial == 1) $charset .= "~#$%^*()_+-={}|][";
			while ($length > 0) {
				$key .= $charset[rand(0, strlen($charset)-1)];
				$length--;
			}
			return $key;
		}
	
	function randomString($length){
		$rand = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, +$length);
		return $rand;
	}
	function caseArrayStr($length){
		$key = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","A1","B1","C1","D1","E1","F1","G1","H1","I1","J1","K1","L1","M1","N1","O1","P1","Q1","R1","S1","T1","U1","V1","W1","X1","Y1","Z1","A2","B2","C2","D2","E2","F2","G2","H2","I2","J2","K2","L2","M2","N2","O2","P2","Q2","R2","S2","T2","U2","V2","W2","X2","Y2","Z2","A3","B3","C3","D3","E3","F3","G3","H3","I3","J3","K3","L3","M3","N3","O3","P3","Q3","R3","S3","T3","U3","V3","W3","X3","Y3","Z3");
		return $key[$length];
	}
	function caseArrayAbjad($length){
		$key = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		return $key[$length];
	}
	
	function changeNameUrl($txtName){
		$name = str_replace(" ","-",trim(strtolower($txtName)));
		$name = str_replace("&","and",trim($name));
		if ($name!='' && preg_match('/[^\w\d_-]/si',$name)) {
			$name = str_replace(' ','-',$name);
			if (preg_match('/[^\w\d_-]/si',$name))	{
				$name = preg_replace('/[^\w\d_-]/si','',$name);
			}
		}
		return $name;
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
	function getFirstHuruf($string){
        $string = substr(trim($string),0,1);
		$string = strtolower($string);
        return $string;
    }
	function getFirstStr($string){
        $string = explode(" ", $string);
        $string = $string[0];
        return $string;
    }
	function getFirstParaSmall($string){
        $string = str_replace("&nbsp;", "", $string);
		$string = html_entity_decode(strip_tags($string));
		if(strlen($string) > 70){ 
			$string = substr( $string, 0,70).' ...';
		}
        return $string;
    }
	function getFirstParaLarge($string){
        $string = str_replace("&nbsp;", "", $string);
		$string = html_entity_decode(strip_tags($string));
		if(strlen($string) > 500){ 
			$string = substr( $string, 0,500).' ...';
		}
        return $string;
    }
	function getFirstPara($string){
        $string = str_replace("&nbsp;", "", $string);
		$string = html_entity_decode(strip_tags($string));
		if(strlen($string) > 140){ 
			$string = substr( $string, 0,140).' ...';
		}
        //$string = substr($string,0, strpos($string, "</p>")+4);
		/*$string = preg_replace("/<p[^>]*?>/", "", $string);
		$string = str_replace("</p>", "<br/>", $string);*/
        return $string;
    }
	function getHidePara($string){
        //$string = substr($string,0, strpos($string, "</p>")+4);
		$string = preg_replace("/<p[^>]*?>/", "", $string);
		$string = str_replace("</p>", "<br/>", $string);
        return $string;
    }
	function getMsg($id){
		switch ($id) {
		    case 0: $string = "Not found!"; break;
		    case 1: $string = "Please fill input required."; break;
		    case 2: $string = "Successfully saved data."; break;
		    case 3: $string = "Failed saved data!"; break;
		    case 4: $string = "Successfully update data."; break;
		    case 5: $string = "Failed update data!"; break;		    
		    case 6: $string = "Successfully delete data."; break;
		    case 7: $string = "Failed delete data!"; break;
		    case 8: $string = "Are you sure you want to delete this data?"; break;
		    case 9: $string = "Not found in database!"; break;
		    case 10: $string = "Please login first!"; break;
		    case 11: $string = "Email already in use!"; break;
		    case 111: $string = "Email is free!"; break;
		    case 12: $string = "Wrong Password!"; break;
		    case 13: $string = "Wrong Old Password!"; break;
		    case 14: $string = "Invalid login credentials."; break;
		    case 15: $string = "Logout Successfully"; break;
		    case 16: $string = "The new password has been sent to your email."; break;
		    case 17: $string = "No account found with that email."; break;
		    case 18: $string = "You do not have access rights to the page."; break;
		    case 19: $string = "Your account frozen."; break;
		    case 20: $string = "Thank you signed up."; break;
		    case 21: $string = "Thank you already subscribe to news."; break;
		    case 22: $string = "Email already subscribe to news."; break;
		    case 23: $string = "Thanks for providing your data. We will keep you updated from us."; break;
		    case 24: $string = "Thank you for contacting us!"; break;
		    case 25: $string = "Thank you for you send testimonial!"; break;
		    case 49: $string = "Thank you for your orders"; break;
		    case 50: $string = 'Your shopping cart is empty. Check out our <a href="./collections.php">collections</a> to see what'."'".'s available.'; break;
		    case 51: $string = "You haven't placed any orders yet.!"; break;
		    case 52: $string = "Thank you for your orders, then please make a payment."; break;
		    case 53: $string = "You do not have an address list!"; break;
		    case 54: $string = "You do not have a primary address!"; break;
		    case 55: $string = "You do not have any additional tools!"; break;
		    case 56: $string = "Thanks you've been making payments."; break;
		    case 57: $string = "There is orders id in the database!"; break;
		    case 58: $string = "Orders ID not found!"; break;
		    case 59: $string = "Not found orders id would be sent!"; break;
		    case 60: $string = "Not found in the address list database!"; break;
		    case 61: $string = "You orders is failed payment!"; break;
		    case 62: $string = ""; break;
		    case 70: $string = "Not found images in the database!"; break;
		    case 71: $string = "Thanks you for testimonial!"; break;
		    case 72: $string = "Successfully broadcast message."; break;
		    case 73: $string = "Successfully save broadcast message to draft."; break;
		    default: $string = "Please fill input required."; break;
		}
        return $string;		
    }

    function getNamePrevilegeAdmin($id){
		switch ($id) {
		    case 1: $string = 'Administrator'; break;
		    case 2: $string = 'Admin Transaksi'; break;
		    default: $string = ''; break;
		}
        return $string;		
    }

	function getNameCity($id){
		$sqll="SELECT name FROM master_city WHERE id_master_city='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"];	
    }
	function getNameProvinsi($id){
		$sqll="SELECT name FROM master_provinsi WHERE id_master_provinsi='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"];	
    }
	function getNameMenu($id){
		$sqll="SELECT name FROM content_menu WHERE id_menu='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"];
	}
	function getNameCustomer($id){
		$sqll="SELECT first_name FROM customer WHERE id_customer='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["first_name"];
	}
	function getStatusNull($string){
		if( $string != ''){
			$string = $string;
		} else {
			$string = "-";
		}
        return $string;		
	}
	function getStatusNotif($id){
		switch ($id) {
		    case 0: $string = ''; break;
            case 1: $string = 'unread'; break;
            case 2: $string = 'newnotif'; break;
            case 3: $string = 'newnotif'; break;
            default : $string = ''; break;
		}
        return $string;	
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

    // --- Customer ---
	function getOrdersStatusCustomer($id){
		switch ($id) {
		    case 1: $string = 'Orders Awaiting Payment'; break;
		    case 2: $string = 'Waiting Payment Confirmed'; break;
		    case 3: $string = 'Payment Is Confirmed'; break;
		    case 4: $string = 'Orders Awaiting Shipment'; break;
		    case 5: $string = 'Orders Awaiting Shipment'; break;
		    case 6: $string = 'Partial Shipment'; break;
		    case 7: $string = 'Order Completed'; break;
		    case 9: $string = 'Cancellation Orders'; break;
		    case 10: $string = 'Payment Confirmation Failed'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    function getClassOrdersStatusCustomer($id){
		$string = 'success';
    	$arrSt = array(1,2,9,10); 
    	if(in_array($id, $arrSt)){
    		$string = 'error';
    	}
        return $string;	
    }

	function getTitleName($id){
		switch ($id) {
		    case '0': $string = 'Mr.'; break;
		    case '1': $string = 'Mrs.'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    // --- /Orders Customer ---

    // --- Orders Admin ---
	function getIdOrdersStatus($name){
		switch ($name) {
		    case getOrdersStatus(1): $string = 1; break;
            case getOrdersStatus(2): $string = 2; break;
            case getOrdersStatus(3): $string = 3; break;
            case getOrdersStatus(4): $string = 4; break;
            case getOrdersStatus(5): $string = 5; break;
            case getOrdersStatus(6): $string = 6; break;
            case getOrdersStatus(7): $string = 7; break;
            case getOrdersStatus(9): $string = 9; break;
            case getOrdersStatus(10): $string = 10; break;
            default : $string = ''; break;
		}
        return $string;	
    }
	function getOrdersStatus($id){
		switch ($id) {
		    case 1: $string = 'Orders Awaiting Payment'; break;
		    case 2: $string = 'Waiting Payment Confirmed'; break;
		    case 3: $string = 'Payment Is Confirmed'; break;
		    case 4: $string = 'Orders Awaiting Shipment'; break;
		    case 5: $string = 'Orders Ready Shipment'; break;
		    case 6: $string = 'Partial Shipment'; break;
		    case 7: $string = 'Order Completed'; break;
		    case 9: $string = 'Cancellation Orders'; break;
		    case 10: $string = 'Payment Confirmation Failed'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    function getClassOrdersStatusAdmin($id){
		switch ($id) {
		    case 1: $string = 'label-info'; break;
		    case 2: $string = 'label-info'; break;
		    case 3: $string = 'label-warning'; break;
		    case 4: $string = 'label-primary'; break;
		    case 5: $string = 'label-info'; break;
		    case 6: $string = 'label-danger'; break;
		    case 7: $string = 'label-success'; break;
		    case 9: $string = 'label-info'; break;
		    case 10: $string = 'label-danger'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    function getClassOrdersIconTask($id){
		switch ($id) {
		    case 1: $string = 'fa-exclamation-circle uncompleted'; break;
		    case 2: $string = 'fa-exclamation-circle uncompleted'; break;
		    case 3: $string = 'fa-credit-card progress'; break;
		    case 4: $string = 'fa-clock-o primary'; break;
		    case 5: $string = 'fa-location-arrow info'; break;
		    case 6: $string = 'fa-truck uncompleted'; break;
		    case 7: $string = 'fa-check-circle-o completed'; break;
		    case 9: $string = 'fa-sign-out uncompleted'; break;
		    case 10: $string = 'fa-warning uncompleted'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    // --- /Orders Admin ---

    // --- Supplier ---
	function getNameSupplier($id){
		$sqll="SELECT first_name, last_name FROM supplier WHERE id_supplier='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["first_name"].' '.$row11["last_name"];	
    }
	function getIdOrdersSupplierStatus($name){
		switch ($name) {
		    case getSupplierStatus(1): $string = 1; break;
            case getSupplierStatus(2): $string = 2; break;
            case getSupplierStatus(3): $string = 3; break;
            default : $string = ''; break;
		}
        return $string;	
    }
	function getSupplierStatus($id){
		switch ($id) {
		    case 1: $string = 'Orders Awaiting Confirmed'; break;
		    case 2: $string = 'Orders Process & Awaiting Shipping'; break;
		    case 3: $string = 'Orders Received'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    function getClassSupplierStatus($id){
		switch ($id) {
		    case 1: $string = 'label-danger'; break;
		    case 2: $string = 'label-warning'; break;
		    case 3: $string = 'label-success'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    function getClassSupplierIconTask($id){
		switch ($id) {
		    case 1: $string = 'fa-exclamation-circle uncompleted'; break;
		    case 2: $string = 'fa-clock-o progress'; break;
		    case 3: $string = 'fa-check-circle-o completed'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    // --- /Supplier ---

    // --- Payment ---
	function getIdPaymentStatus($name){
		switch ($name) {
		    case getPaymentStatus(1): $string = 1; break;
            case getPaymentStatus(2): $string = 2; break;
            case getPaymentStatus(3): $string = 3; break;
            case getPaymentStatus(10): $string = 10; break;
            default : $string = ''; break;
		}
        return $string;	
    }
	function getPaymentStatus($id){
		switch ($id) {
		    case 1: $string = getOrdersStatus(1); break;
		    case 2: $string = getOrdersStatus(2); break;
		    case 3: $string = getOrdersStatus(3); break;
		    case 10: $string = getOrdersStatus(10); break;
		    default: $string = 'Orders Awaiting Send Shipping Cost From Admin'; break;
		}
        return $string;	
    }
    function getClassPaymentStatus($id){
		switch ($id) {
		    case 1: $string = getClassOrdersStatusAdmin(1); break;
		    case 2: $string = getClassOrdersStatusAdmin(2); break;
		    case 3: $string = getClassOrdersStatusAdmin(7); break;
		    case 10: $string = getClassOrdersStatusAdmin(10); break;
		    default: $string = getClassOrdersStatusAdmin(10); break;
		}
        return $string;	
    }
    function getClassPaymentIconTask($id){
		switch ($id) {
		    case 1: $string = getClassOrdersIconTask(1); break;
		    case 2: $string = getClassOrdersIconTask(2); break;
		    case 3: $string = getClassOrdersIconTask(7); break;
		    case 10: $string = getClassOrdersIconTask(10); break;
		    default: $string = getClassOrdersIconTask(10); break;
		}
        return $string;	
    }

	function getPaymentOtherStatus($id){
		switch ($id) {
		    case 1: $string = getPaymentStatus(1).' Shipping Cost From Customer'; break;
		    case 2: $string = getPaymentStatus(2).' Shipping Cost From 3rd Party'; break;
		    case 3: $string = getPaymentStatus(3); break;
		    case 10: $string = getPaymentStatus(10); break;
		    default: $string = getPaymentStatus(''); break;
		}
        return $string;	
    }
    // --- /Payment ---

    // --- Shipping ---
	function getIdShippingStatus($name){
		switch ($name) {
		    case getShippingStatus(5): $string = 5; break;
            case getShippingStatus(6): $string = 6; break;
            case getShippingStatus(7): $string = 7; break;
            default : $string = ''; break;
		}
        return $string;	
    }
	function getShippingStatus($id){
		switch ($id) {
		    case 5: $string = getOrdersStatus(5); break;
		    case 6: $string = getOrdersStatus(6); break;
		    case 7: $string = 'Orders Received'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    function getClassShippingStatus($id){
		switch ($id) {
		    case 5: $string = getClassOrdersStatusAdmin(5); break;
		    case 6: $string = getClassOrdersStatusAdmin(6); break;
		    case 7: $string = getClassOrdersStatusAdmin(7); break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    function getClassShippingIconTask($id){
		switch ($id) {
		    case 5: $string = getClassOrdersIconTask(5); break;
		    case 6: $string = getClassOrdersIconTask(6); break;
		    case 7: $string = getClassOrdersIconTask(7); break;
		    default: $string = ''; break;
		}
        return $string;	
    }
	function getShippingMethod($id){
		switch ($id) {
		    case 1: $string = 'Delivery'; break;
		    case 2: $string = 'Pick Up'; break;
		    default: $string = ''; break;
		}
        return $string;	
    }
    // --- /Shipping ---

    function getIdBank($id){
		$sqll="SELECT name, name_rekening, no_rekening FROM setting_payment WHERE id_setting_payment='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"].' - '.$row11["name_rekening"].'('.$row11["no_rekening"].')';
	}


    // --- PRODUCT ---
	function getIdCategories($string){
		$sqll="SELECT id_product_categories FROM product_categories WHERE name='".$string."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["id_product_categories"];
	}
	function getNameCategories($id){
		$sqll="SELECT name FROM product_categories WHERE id_product_categories='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"];
	}

	function getIdOrigin($string){
		$sqll="SELECT id_product_origin FROM product_origin WHERE name='".$string."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["id_product_origin"];
	}
	function getNameOrigin($id){
		$sqll="SELECT name FROM product_origin WHERE id_product_origin='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"];
	}

	function getIdTypes($string){
		$sqll="SELECT id_product_type FROM product_type WHERE name='".$string."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["id_product_type"];
	}
	function getNameTypes($id){
		$sqll="SELECT name FROM product_type WHERE id_product_type='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"];
	}

	function getIdStock($string){
		$sqll="SELECT id_stock_status FROM product_stock_status WHERE name='".$string."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["id_stock_status"];
	}
	function getNameStock($id){
		$sqll="SELECT name FROM product_stock_status WHERE id_stock_status='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"];
	}

	function getIdDiscount($string){
		switch ($string) {
		    case getNameDiscount('0'): $id = '0'; break;
		    case getNameDiscount('1'): $id = '1'; break;
		    default: $id = ''; break;
		}
        return $id;	
	}
	function getNameDiscount($id){
		switch ($id) {
		    case '0': $string = 'No'; break;
		    case '1': $string = 'Yes'; break;
		    default: $string = ''; break;
		}
        return $string;	
	}

	function getIdProductStatus($string){
		switch ($string) {
		    case getProductStatus('1'): $id = '1'; break;
		    case getProductStatus('2'): $id = '2'; break;
		    case getProductStatus('3'): $id = '3'; break;
		    default: $id = ''; break;
		}
        return $id;	
	}
	function getProductStatus($id){
		switch ($id) {
		    case '1': $string = 'Approved'; break;
		    case '2': $string = 'Pending'; break;
		    case '3': $string = 'Reject'; break;
		    default: $string = ''; break;
		}
        return $string;	
	}
    // --- PRODUCT ---

	function getNameProduct($id){
		$sqll="SELECT name FROM product WHERE id_product='".$id."' LIMIT 1";
		$result1=mysql_query($sqll) or die("Invalid query: " . mysql_error() . " $sqll");
		$row11=mysql_fetch_assoc($result1);
		return $row11["name"];
	}
	function getNameUser($id){
		$sql="SELECT first_name, last_name FROM user WHERE id_user='".$id."' LIMIT 1";
		$result=mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
		$row=mysql_fetch_assoc($result);
		($row["first_name"] != ''?$first_name = $row["first_name"].' '.$row["last_name"]:$first_name = '');
		return $first_name;
	}
	
	function getStatusMessage($string){
		if( $string == '1' || $string == '2'){
			$string = 'New';
		} else {
			$string = 'Read';
		}
        return $string;
    }
	function changeStatusMessage($id, $string){
		$sql = "update contact set status_notif = '".$string."' where id_contact = '".$id."' limit 1";
		$result=mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
    }
	function getStatusTestimonial($string){
		if( $string == '1' || $string == '2'){
			$string = 'New';
		} else {
			$string = 'Read';
		}
        return $string;
    }
	function changeStatusTestimonial($id, $string){
		$sql = "update testimonial set status_notif = '".$string."' where id_testimonial = '".$id."' limit 1";
		$result=mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
    }

	// function changeStatusPayment($id, $string){
	// 	$sql = "update orders_payment set status_notif = '".$string."' where id = '".$id."' limit 1";
	// 	$result=mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
 //    }
 //    function getStatusPayment($id){
	// 	switch ($id) {
	// 	    case 1: $string = '<span style="padding:2px 5px; color:#fff; background-color:#FF0000;">Diterima</span>'; break;
	// 	    default: $string = '<span style="padding:2px 5px; color:#000;"><em>Belum Diterima</em></span>'; break;
	// 	}
 //        return $string;		
 //    }
 //    function getStatPayment($id){
	// 	switch ($id) {
	// 	    case 1: $string = 'Diterima'; break;
	// 	    default: $string = 'Belum Diterima'; break;
	// 	}
 //        return $string;		
 //    }
 //    function getSistemPayment($id){
	// 	switch ($id) {
	// 	    case 1: $string = 'Bank Transfer'; break;
	// 	    default: $string = 'Bank Manual'; break;
	// 	}
 //        return $string;		
 //    }
	
	
	// function getGrade($string){
	// 	if( $string == '1'){
	// 		$string = 'new_product';
	// 	} else if( $string == '2'){
	// 		$string = 'best_seller';
	// 	} else {
	// 		$string = '';
	// 	}
 //        return $string;		
 //    }
	// function getNameGrade($string){
	// 	if( $string == '1'){
	// 		$string = 'New Product';
	// 	} else if( $string == '2'){
	// 		$string = 'Best Seller';
	// 	} else {
	// 		$string = '';
	// 	}
 //        return $string;		
 //    }


  //   function ordersDateDeleteAuto(){
		// $sql    = "SELECT kd_transaksi, date_created FROM transaksi WHERE status = '0' LIMIT 100";
  //       $result = mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
  //       if( mysql_num_rows($result) > 0 ) {
  //           while($row = mysql_fetch_assoc($result)){
  //   			$timestamp = gmdate("Y-m-d H:i:s", time()+60*60*7); //GMT +7
  //           	$dateAwal  = $row['date_created'];
		// 		$datetime1 = new DateTime($dateAwal);
		// 		$datetime2 = new DateTime($timestamp);
		// 		$difference = $datetime1->diff($datetime2);
		// 		$resultDay = $difference->days;
		// 		if($resultDay > 3){
		// 			$sql_2	  = "DELETE FROM transaksi WHERE kd_transaksi = '".$row['kd_transaksi']."' LIMIT 1";
		// 			$result_2 = mysql_query($sql_2) or die("Invalid query: " . mysql_error() . " $sql_2");
		// 		}
  //           }
  //       }
  //   }

	function ordersAutoCompleted(){
		$sql    = "SELECT t1.id_orders, t1.no_orders, t2.date_6, t3.first_name, t3.last_name, t3.email 
					FROM orders AS t1
					LEFT JOIN orders_timestamp AS t2 ON t2.id_orders = t1.id_orders
					LEFT JOIN customer AS t3 ON t3.id_customer = t1.id_customer
					WHERE t1.orders_status = '6' LIMIT 50";
        $result = mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
        if ( mysql_num_rows($result) > 0 ){
            while($row = mysql_fetch_array($result)){
    			$timestamp = gmdate("Y-m-d H:i:s", time()+60*60*7); //GMT +7
            	$dateAwal  = $row['date_6'];
				$datetime1 = new DateTime($dateAwal);
				$datetime2 = new DateTime($timestamp);
				$difference = $datetime1->diff($datetime2);
				$resultDay = $difference->days;
				if($resultDay > 3){
					$id_orders = $row['id_orders'];
	                $orders_status = 7;
	                $sql1    = "UPDATE orders SET orders_status = '".$orders_status."', date_notify = '".$timestamp."' WHERE id_orders = '".$id_orders."' LIMIT 1";
	                $result1 = mysql_query($sql1) or die("Invalid query: " . mysql_error() . " $sql1");

	                $sql2    = "UPDATE orders_timestamp SET date_".$orders_status." = '".$timestamp."' WHERE id_orders = '".$id_orders."' LIMIT 1";
	                $result2 = mysql_query($sql2) or die("Invalid query: " . mysql_error() . " $sql2");

	                $sql3    = "UPDATE orders_shipping SET ship_status = '".$orders_status."', date_notify = '".$timestamp."' WHERE id_orders = '".$id_orders."' LIMIT 1";
	                $result3 = mysql_query($sql3) or die("Invalid query: " . mysql_error() . " $sql3");
				}
            }
        }
    }

    function sendTestimonialOrdersAuto(){
		$sql    = "SELECT t1.id_orders, t1.no_orders, t2.date_7, t3.first_name, t3.last_name, t3.email 
					FROM orders AS t1
					LEFT JOIN orders_timestamp AS t2 ON t2.id_orders = t1.id_orders
					LEFT JOIN customer AS t3 ON t3.id_customer = t1.id_customer
					WHERE t1.orders_status = '7' && t1.feedback = '0' LIMIT 50";
        $result = mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
        if ( mysql_num_rows($result) > 0 ){
            while($row = mysql_fetch_array($result)){
    			$timestamp = gmdate("Y-m-d H:i:s", time()+60*60*7); //GMT +7
            	$dateAwal  = $row['date_7'];
				$datetime1 = new DateTime($dateAwal);
				$datetime2 = new DateTime($timestamp);
				$difference = $datetime1->diff($datetime2);
				$resultDay = $difference->days;
				if($resultDay > 3){
	                $sql1    = "UPDATE orders SET feedback = '1' WHERE id_orders = '".$row['id_orders']."' LIMIT 1";
	                $result1 = mysql_query($sql1) or die("Invalid query: " . mysql_error() . " $sql1");

					$name = $row['first_name'].' '.$row['last_name'];
					$email = $row['email'];
					$description = 'Order ID #'.$row['no_orders'].'<br/><br/>Automatic feedback positif for this orders.';
					$sql2 = "INSERT INTO testimonial (name, email, description, testimonial_status, notify, date_created) values ('".$name."','".$email."','".$description."','1','3','".$timestamp."')";	
					$result2 = mysql_query($sql2) or die("Invalid query: " . mysql_error() . " $sql2");
				}
            }
        }
    }
	
	// function getCoverImages($id){
	// 	$sql="SELECT images FROM product_images WHERE id_product='".$id."' order by position asc limit 1";
	// 	$result=mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
	// 	$row=mysql_fetch_assoc($result);
	// 	$images = 'no_image_available.png';
	// 	if($row["images"] != ''){ $images = $row["images"]; }
	// 	return $images;
	// }

	// $sql_package = "SELECT t2.name, t2.price
	//                 FROM transaksi_detail as t1
	//                 join package as t2 on t2.kd_transaksi = t1.kd_transaksi
	//             where t1.kd_transaksi = '".$row_order['kd_transaksi']."' ORDER BY t2.name ASC";
	// $result_package = mysql_query($sql_package);
	// while($row_package = mysql_fetch_array($result_package)){
	//     $namePackage= $row_package['name'];
	//     $price= $row_package['price'];
	// }

	function isToken($token,$table) {
        if (isset($token) && $token) {
            //verification values in BD
            $sqlToken    = "SELECT token FROM ".$table." WHERE token = '".$token."' ";
            $resultToken = mysql_query($sqlToken) or die("Invalid query: " . mysql_error() . " $sqlToken");
            if (mysql_num_rows($resultToken) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function generateUniqueToken($number,$table){
        $token = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, +$number);
        if (isToken($token,$table)) {
            return generateUniqueToken($number,$table);
        } else {
            return $token;
        }
    }

	function isTokenInt($token,$table) {
        if (isset($token) && $token) {
            //verification values in BD
            $sqlToken    = "SELECT token FROM ".$table." WHERE token = '".$token."' ";
            $resultToken = mysql_query($sqlToken) or die("Invalid query: " . mysql_error() . " $sqlToken");
            if (mysql_num_rows($resultToken) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function generateUniqueTokenInt($number,$table){
        $token = substr(str_shuffle("0123456789"), 0, +$number);
        if (isTokenInt($token,$table)) {
            return generateUniqueTokenInt($number,$table);
        } else {
            return $token;
        }
    }

	function checkDiscount($this_voucher, $this_order, $this_price, $this_city, $this_provinsi){
        $timestamp     = gmdate("Y-m-d H:i:s", time()+60*60*7); //GMT +7
        $result_check  = '';
        $status        = false;
        $id_discount   = '';
        $name          = '';
        $type_discount      = '';
        $price_discount     = '';
        $max_price_discount = '';
        $msg           = 'error';
        $sqlVoucher    = " && id_voucher IS NULL ";
        $sqlOrder      = " ORDER BY id_setting_discount DESC";
        if($this_voucher != ''){ $sqlVoucher = " && id_voucher = '".$this_voucher."' "; $sqlOrder = " LIMIT 1"; }
        $sql = "SELECT * FROM setting_discount WHERE active = '1' ".$sqlVoucher." ".$sqlOrder." ";
        $result = mysql_query($sql) or die("Invalid query: " . mysql_error() . " $sql");
        if ( mysql_num_rows($result) > 0 ){
            while($row = mysql_fetch_array($result)){
                $id_discount   = $row['id_setting_discount'];
                $name          = $row['name'];
                $type_discount      = $row['type_discount'];
                $price_discount     = $row['price_discount'];
                $max_price_discount = $row['max_price_discount'];
                $min_order     = $row['min_order'];
                $max_order     = $row['max_order'];
                $min_price     = $row['min_price'];
                $max_price     = $row['max_price'];
                $city          = $row['city'];
                $provinsi      = $row['provinsi'];
                $date_start    = $row['date_start'];
                $date_end      = $row['date_end'];

                $chk_min_order = true;
                $chk_max_order = true;
                $chk_min_price = true;
                $chk_max_price = true;
                $chk_city      = true;
                $chk_provinsi  = true;
                $chk_date      = true;

                if($min_order != '' && ($min_order > $this_order)){ $chk_min_order = false; }
                if($max_order != '' && ($max_order < $this_order)){ $chk_max_order = false; }
                if($min_price != '' && ($min_price > $this_price)){ $chk_min_price = false; }
                if($max_price != '' && ($max_price < $this_price)){ $chk_max_price = false; }
                if($city != ''){
                    $arr_city = array();
                    $expArr   = explode(',', $city);
                    foreach ($expArr as $key){ $arr_city[] = $key; }
                    if(!in_array($this_city, $arr_city)){ $chk_city = false; }
                }
                if($provinsi != ''){
                    $arr_provinsi = array();
                    $expArrProv   = explode(',', $provinsi);
                    foreach ($expArrProv as $key){ $arr_provinsi[] = $key; }
                    if(!in_array($this_provinsi, $arr_provinsi)){ $chk_provinsi = false; }
                }

                if($date_start != '' && $date_end != '' && (($timestamp < $date_start) || ($timestamp > $date_end))){ $chk_date = false; }

                if($chk_min_order && $chk_max_order && $chk_min_price && $chk_max_price && $chk_city && $chk_provinsi && $chk_date){
                    $status = true;
                    $msg    = 'success';
                    $result_check = array( 'status' => $status, 
                                           'id_discount' => $id_discount, 
                                           'name' => $name, 
                                           'type_discount' => $type_discount, 
                                           'price_discount' => $price_discount, 
                                           'max_price_discount' => $max_price_discount, 
                                           'msg' => $msg 
                                          );
                    return $result_check;
                }

            }
        } else {
            $status = false;
            $msg    = 'Tidak ditemukan...';
            if($this_voucher != ''){ 
                $msg    = 'Kode Voucher tidak ditemukan...';
            }
        }

        if($status == false){
            $result_check = array( 'status' => $status, 
                         'id_discount' => '', 
                         'name' => '', 
                         'type_discount' => '', 
                         'price_discount' => '',
                         'max_price_discount' => '',
                         'msg' => $msg 
                        );
            return $result_check;
        }
	    // $result_check = checkDiscount($this_voucher, $this_order, $this_price, $this_city, $this_provinsi);
	    // echo 'Hasil Status: '.$result_check['status'].'<br/>';
    }

    function checkShippingCityArea($this_city){
        $result_check  = '';
        $status        = false;
        $msg           = 'error';
    	$shipping_city_area = '';
    	$sql_shipping = "SELECT shipping_city_area FROM setting_shipping WHERE id_setting_shipping = '1' LIMIT 1";
		$result_shipping = mysql_query($sql_shipping);
	    if ( mysql_num_rows($result_shipping) > 0 ){
			$row_shipping = mysql_fetch_array($result_shipping);
			$shipping_city_area = $row_shipping["shipping_city_area"];
		}

		if($shipping_city_area != ''){
            $arr_city = array();
            $expArr   = explode(',', $shipping_city_area);
            foreach ($expArr as $key){ $arr_city[] = $key; }
            if(in_array($this_city, $arr_city)){ 
		        $status = true;
		        $msg    = 'success';
            }
        }

        $result_check = array( 'status' => $status,
                               'msg' => $msg 
                              );
        return $result_check;
	    // $result_check = checkShippingCityArea($this_city);
	    // echo 'Hasil Status: '.$result_check['status'].'<br/>';
    }

?>